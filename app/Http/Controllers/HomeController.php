<?php

namespace App\Http\Controllers;

use App\Models\Mountain;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman beranda MiddleTrip.
     */
    public function index(): View
    {
        // 1. Ambil data seluruh gunung aktif beserta jalurnya untuk Hero Search Bar dependent dropdown
        $mountains = Mountain::query()
            ->active()
            ->with(['routes' => fn ($query) => $query->orderByDesc('is_primary')->orderBy('name')])
            ->orderBy('name')
            ->get();

        // 2. Ambil gunung unggulan Bento Grid (1 Hero Utama Span 7)
        $featuredHero = Mountain::query()
            ->active()
            ->featured()
            ->where('featured_order', 1)
            ->with('primaryRoute')
            ->first();

        // 3. Ambil 2 gunung sekunder Bento Grid (Span 5)
        $featuredCards = Mountain::query()
            ->active()
            ->featured()
            ->whereIn('featured_order', [2, 3])
            ->with('primaryRoute')
            ->orderBy('featured_order')
            ->get();

        return view('home', compact('mountains', 'featuredHero', 'featuredCards'));
    }
}
