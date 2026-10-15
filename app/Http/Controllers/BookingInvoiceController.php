<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BookingInvoiceController extends Controller
{
    /**
     * Menampilkan dokumen e-tiket dan bukti reservasi resmi yang siap dicetak ke PDF.
     */
    public function show(Request $request, string $bookingCode): View|RedirectResponse
    {
        $booking = Booking::with([
            'expedition.mountain',
            'route.mountain',
            'meetingPoint',
            'participants',
            'addons',
            'paymentTransactions' => fn ($q) => $q->latest(),
        ])->where('booking_code', $bookingCode)->firstOrFail();

        // Invoice dan E-Tiket hanya tersedia jika seluruh tagihan telah lunas (100% paid)
        if ($booking->status !== 'paid' && ! auth()->user()?->isAdmin()) {
            if (in_array($booking->status, ['reserved', 'price_locked'], true)) {
                return redirect()->route('checkout.status', $booking->booking_code)
                    ->with('warning', 'Invoice dan E-Tiket resmi baru dapat dicetak setelah Anda melunasi pembayaran.');
            }

            return redirect()->route('home')
                ->with('error', 'Invoice dan E-Tiket resmi hanya tersedia untuk pesanan yang telah lunas 100%.');
        }

        return view('customer.booking_invoice', compact('booking'));
    }
}
