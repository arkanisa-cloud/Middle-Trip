<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\PaymentTransaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BookingManagementController extends Controller
{
    /**
     * Display a listing of bookings.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $tripType = $request->query('trip_type');
        $search = $request->query('search');

        $bookings = Booking::with(['expedition.mountain', 'route', 'meetingPoint'])
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($tripType, fn ($q) => $q->where('trip_type', $tripType))
            ->when($search, function ($query, $term) {
                $query->where('booking_code', 'like', "%{$term}%")
                    ->orWhere('customer_name', 'like', "%{$term}%")
                    ->orWhere('customer_phone', 'like', "%{$term}%")
                    ->orWhere('customer_nik', 'like', "%{$term}%");
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $statusCounts = [
            'all' => Booking::count(),
            'open' => Booking::where('status', 'open')->count(),
            'reserved' => Booking::where('status', 'reserved')->count(),
            'price_locked' => Booking::where('status', 'price_locked')->count(),
            'paid' => Booking::where('status', 'paid')->count(),
            'expired' => Booking::where('status', 'expired')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
        ];

        return view('admin.bookings.index', compact('bookings', 'status', 'tripType', 'search', 'statusCounts'));
    }

    /**
     * Display the specified booking details and participant SIMAKSI list.
     */
    public function show(Booking $booking): View
    {
        $booking->load([
            'expedition.mountain',
            'expedition.route',
            'route',
            'meetingPoint',
            'participants',
            'addons',
            'paymentTransactions' => fn ($q) => $q->latest(),
        ]);

        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Manually update the booking payment/operational status.
     */
    public function updateStatus(Request $request, Booking $booking): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:open,reserved,price_locked,paid,expired,cancelled',
            'notes' => 'nullable|string',
        ]);

        $oldStatus = $booking->status;
        $newStatus = $validated['status'];

        $booking->update([
            'status' => $newStatus,
            'notes' => $validated['notes'] ?? $booking->notes,
        ]);

        // If marked as paid or reserved manually, audit transaction
        if ($newStatus === 'paid' && $oldStatus !== 'paid') {
            PaymentTransaction::create([
                'booking_id' => $booking->id,
                'transaction_code' => 'MANUAL-SETTLE-'.strtoupper(Str::random(8)),
                'payment_stage' => $booking->trip_type === 'private' ? 'full_payment' : 'settlement',
                'payment_method' => 'Manual Verification',
                'amount' => $booking->remaining_payment_total ?: $booking->grand_total,
                'status' => 'success',
                'paid_at' => now(),
            ]);
        } elseif ($newStatus === 'reserved' && $oldStatus === 'open') {
            PaymentTransaction::create([
                'booking_id' => $booking->id,
                'transaction_code' => 'MANUAL-DP-'.strtoupper(Str::random(8)),
                'payment_stage' => 'booking_fee',
                'payment_method' => 'Manual Verification',
                'amount' => $booking->total_booking_fee,
                'status' => 'success',
                'paid_at' => now(),
            ]);
        }

        // Sinkronisasi kuota batch ekspedisi jika status booking berubah
        if ($oldStatus !== $newStatus && $booking->expedition) {
            $booking->expedition->syncQuotaBooked();
        }

        return back()->with('success', "Status reservasi {$booking->booking_code} berhasil diubah ke {$newStatus}.");
    }
}
