<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\PaymentTransaction;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
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
     * Memproses pembayaran Booking Fee (DP) awal.
     */
    public function payDp(Request $request, string $bookingCode): RedirectResponse
    {
        $booking = Booking::where('booking_code', $bookingCode)->firstOrFail();

        $paymentMethod = $request->input('payment_method', 'BCA Virtual Account');

        // Catat transaksi
        PaymentTransaction::create([
            'booking_id' => $booking->id,
            'transaction_code' => 'TRX-DP-'.strtoupper(Str::random(10)),
            'payment_stage' => 'booking_fee',
            'payment_method' => $paymentMethod,
            'amount' => $booking->total_booking_fee,
            'status' => 'success',
            'paid_at' => now(),
        ]);

        // Update status booking ke reserved
        $booking->update([
            'status' => 'reserved',
        ]);

        return redirect()->route('checkout.status', $booking->booking_code);
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
     * Memproses sisa pelunasan (Settlement).
     */
    public function settle(Request $request, string $bookingCode): RedirectResponse
    {
        $booking = Booking::where('booking_code', $bookingCode)->firstOrFail();

        $paymentMethod = $request->input('payment_method', 'QRIS');
        $amount = $booking->remaining_payment_total ?? 0;

        PaymentTransaction::create([
            'booking_id' => $booking->id,
            'transaction_code' => 'TRX-SETTLE-'.strtoupper(Str::random(10)),
            'payment_stage' => 'settlement',
            'payment_method' => $paymentMethod,
            'amount' => $amount,
            'status' => 'success',
            'paid_at' => now(),
        ]);

        $booking->update([
            'status' => 'paid',
        ]);

        return redirect()->route('checkout.success', $booking->booking_code);
    }

    /**
     * Menampilkan halaman sukses pembayaran & tautan grup WA (Step 3).
     */
    public function success(string $bookingCode): View
    {
        $booking = Booking::with(['expedition.mountain', 'route', 'meetingPoint', 'participants', 'addons'])
            ->where('booking_code', $bookingCode)
            ->firstOrFail();

        return view('customer.checkout.step3_success', compact('booking'));
    }
}
