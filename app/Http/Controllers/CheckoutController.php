<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Expedition;
use App\Models\PaymentTransaction;
use App\Services\MidtransService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    public function __construct(
        public readonly MidtransService $midtrans,
    ) {}

    /**
     * Menampilkan formulir pemesanan & pembayaran DP awal (Step 1).
     */
    public function step1(string $bookingCode): View
    {
        $booking = Booking::with(['expedition.mountain', 'route', 'meetingPoint', 'participants', 'addons'])
            ->where('booking_code', $bookingCode)
            ->firstOrFail();

        return view('customer.checkout.step1_payment', compact('booking'));
    }

    /**
     * Memproses inisiasi pembayaran Booking Fee (DP) awal melalui Midtrans Snap.
     */
    public function payDp(Request $request, string $bookingCode): JsonResponse|RedirectResponse
    {
        $booking = Booking::with('expedition')->where('booking_code', $bookingCode)->firstOrFail();

        $this->updateCustomerAndParticipants($request, $booking);

        // Jika booking sudah reserved, price_locked, atau paid, langsung arahkan ke status
        if (in_array($booking->status, ['reserved', 'price_locked', 'paid'], true)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'already_paid',
                    'redirect_url' => route('checkout.status', $booking->booking_code),
                ]);
            }

            return redirect()->route('checkout.status', $booking->booking_code);
        }

        $snapData = DB::transaction(function () use ($booking): array {
            $expedition = Expedition::where('id', $booking->expedition_id)
                ->lockForUpdate()
                ->first();

            if ($booking->trip_type !== 'private' && $expedition) {
                $availableQuota = $expedition->quota_max - $expedition->quota_booked;

                if ($availableQuota < $booking->pax_count) {
                    throw ValidationException::withMessages([
                        'quota' => 'Maaf, kuota untuk batch ekspedisi ini sudah habis.',
                    ]);
                }

                $expedition->increment('quota_booked', $booking->pax_count);
            }

            // Minta Snap Token dari Midtrans
            $snap = $this->midtrans->createSnapToken($booking, 'booking_fee', $booking->total_booking_fee);

            // Catat record transaksi dengan status pending
            PaymentTransaction::create([
                'booking_id' => $booking->id,
                'transaction_code' => $snap['order_id'],
                'payment_stage' => 'booking_fee',
                'payment_method' => 'midtrans',
                'amount' => $booking->total_booking_fee,
                'status' => 'pending',
            ]);

            return $snap;
        });

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'snap_token' => $snapData['token'],
                'redirect_url' => $snapData['redirect_url'],
                'order_id' => $snapData['order_id'],
            ]);
        }

        return redirect()->away($snapData['redirect_url']);
    }

    /**
     * Menampilkan status pemesanan & countdown / formulir pelunasan (Step 2).
     */
    public function status(string $bookingCode): View
    {
        $booking = Booking::with(['expedition.mountain', 'route', 'meetingPoint', 'participants', 'addons'])
            ->where('booking_code', $bookingCode)
            ->firstOrFail();

        return view('customer.checkout.step2_status', compact('booking'));
    }

    /**
     * Memproses sisa pelunasan (Settlement) melalui Midtrans Snap.
     */
    public function settle(Request $request, string $bookingCode): JsonResponse|RedirectResponse
    {
        $booking = Booking::where('booking_code', $bookingCode)->firstOrFail();

        if ($booking->status === 'paid') {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'already_paid',
                    'redirect_url' => route('checkout.success', $booking->booking_code),
                ]);
            }

            return redirect()->route('checkout.success', $booking->booking_code);
        }

        $amount = (int) ($booking->remaining_payment_total ?? 0);

        if ($amount <= 0) {
            $booking->update(['status' => 'paid']);

            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'success',
                    'redirect_url' => route('checkout.success', $booking->booking_code),
                ]);
            }

            return redirect()->route('checkout.success', $booking->booking_code);
        }

        $snapData = $this->midtrans->createSnapToken($booking, 'settlement', $amount);

        PaymentTransaction::create([
            'booking_id' => $booking->id,
            'transaction_code' => $snapData['order_id'],
            'payment_stage' => 'settlement',
            'payment_method' => 'midtrans',
            'amount' => $amount,
            'status' => 'pending',
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'snap_token' => $snapData['token'],
                'redirect_url' => $snapData['redirect_url'],
                'order_id' => $snapData['order_id'],
            ]);
        }

        return redirect()->away($snapData['redirect_url']);
    }

    /**
     * Menampilkan halaman pembayaran langsung 100% tanpa DP untuk Private Trip.
     */
    public function private(string $bookingCode): View|RedirectResponse
    {
        $booking = Booking::with(['expedition.mountain', 'route', 'meetingPoint', 'participants', 'addons'])
            ->where('booking_code', $bookingCode)
            ->firstOrFail();

        if ($booking->status === 'paid') {
            return redirect()->route('checkout.success', $booking->booking_code);
        }

        return view('customer.checkout.private_payment', compact('booking'));
    }

    /**
     * Memproses pembayaran 100% lunas untuk Private Trip melalui Midtrans Snap.
     */
    public function payPrivate(Request $request, string $bookingCode): JsonResponse|RedirectResponse
    {
        $booking = Booking::where('booking_code', $bookingCode)->firstOrFail();

        if ($booking->status === 'paid') {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'already_paid',
                    'redirect_url' => route('checkout.success', $booking->booking_code),
                ]);
            }

            return redirect()->route('checkout.success', $booking->booking_code);
        }

        $this->updateCustomerAndParticipants($request, $booking);

        $amount = (int) $booking->grand_total;

        $snapData = $this->midtrans->createSnapToken($booking, 'full_payment', $amount);

        PaymentTransaction::create([
            'booking_id' => $booking->id,
            'transaction_code' => $snapData['order_id'],
            'payment_stage' => 'full_payment',
            'payment_method' => 'midtrans',
            'amount' => $amount,
            'status' => 'pending',
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'snap_token' => $snapData['token'],
                'redirect_url' => $snapData['redirect_url'],
                'order_id' => $snapData['order_id'],
            ]);
        }

        return redirect()->away($snapData['redirect_url']);
    }

    /**
     * Menampilkan halaman sukses pembayaran & tautan grup WA (Step 3).
     */
    public function success(string $bookingCode): View
    {
        $booking = Booking::with(['expedition.mountain', 'route', 'meetingPoint', 'participants', 'addons'])
            ->where('booking_code', $bookingCode)
            ->firstOrFail();

        if ($booking->trip_type === 'private' || $booking->expedition->type === 'private') {
            return view('customer.checkout.private_success', compact('booking'));
        }

        return view('customer.checkout.step3_success', compact('booking'));
    }

    /**
     * Memproses pembaruan data pemesan dan data seluruh peserta rombongan jika dikirimkan.
     */
    private function updateCustomerAndParticipants(Request $request, Booking $booking): void
    {
        if ($request->has('customer_name') || $request->has('participants')) {
            $validated = $request->validate([
                'customer_name' => ['required', 'string', 'min:3', 'max:150'],
                'customer_phone' => ['required', 'string', 'min:9', 'max:25'],
                'customer_nik' => ['required', 'digits:16'],
                'customer_email' => ['required', 'email', 'max:150'],
                'participants' => ['required', 'array', 'size:'.$booking->pax_count],
                'participants.*.full_name' => ['required', 'string', 'min:3', 'max:150'],
                'participants.*.nik' => ['required', 'digits:16'],
                'participants.*.is_leader' => ['nullable'],
            ]);

            $booking->update([
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'customer_nik' => $validated['customer_nik'],
                'customer_email' => $validated['customer_email'],
            ]);

            $booking->participants()->delete();
            foreach ($validated['participants'] as $index => $part) {
                $booking->participants()->create([
                    'full_name' => $part['full_name'],
                    'nik' => $part['nik'],
                    'is_leader' => (bool) ($part['is_leader'] ?? ($index === 0)),
                ]);
            }
        }
    }
}
