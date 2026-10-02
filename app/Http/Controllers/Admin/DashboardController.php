<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Expedition;
use App\Models\Mountain;
use App\Models\PaymentTransaction;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the admin operational dashboard.
     */
    public function index(): View
    {
        $metrics = [
            'total_mountains' => Mountain::count(),
            'active_expeditions' => Expedition::whereIn('status', ['open', 'price_locked'])->count(),
            'total_bookings' => Booking::count(),
            'pending_settlement' => Booking::where('status', 'price_locked')->count(),
            'total_revenue' => (int) PaymentTransaction::where('status', 'success')->sum('amount'),
        ];

        $recentBookings = Booking::with(['expedition.mountain', 'route'])
            ->latest()
            ->take(6)
            ->get();

        $upcomingPriceLocks = Expedition::with(['mountain', 'route'])
            ->where('status', 'open')
            ->whereDate('departure_date', '>=', now())
            ->orderBy('departure_date', 'asc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('metrics', 'recentBookings', 'upcomingPriceLocks'));
    }
}
