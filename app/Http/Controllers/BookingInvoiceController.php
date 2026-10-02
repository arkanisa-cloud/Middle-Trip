<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class BookingInvoiceController extends Controller
{
    /**
     * Menampilkan dokumen e-tiket dan bukti reservasi resmi yang siap dicetak ke PDF.
     */
    public function show(Request $request, string $bookingCode): View
    {
        $booking = Booking::with([
            'expedition.mountain',
            'route.mountain',
            'meetingPoint',
            'participants',
            'addons',
            'paymentTransactions' => fn ($q) => $q->latest(),
        ])->where('booking_code', $bookingCode)->firstOrFail();

        return view('customer.booking_invoice', compact('booking'));
    }
}
