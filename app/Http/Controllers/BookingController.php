<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Services\BookingService;
use Illuminate\Http\JsonResponse;

class BookingController extends Controller
{
    public function __construct(
        private readonly BookingService $bookingService,
    ) {}

    /**
     * Menyimpan reservasi booking baru dari modal atau form pemesanan.
     */
    public function store(StoreBookingRequest $request): JsonResponse
    {
        $booking = $this->bookingService->createBooking(
            $request->validated(),
            $request->user()?->id,
        );

        return response()->json([
            'success' => true,
            'message' => 'Reservasi booking berhasil dibuat.',
            'booking_code' => $booking->booking_code,
            'redirect_url' => route('checkout.step1', $booking->booking_code),
        ], 201);
    }
}
