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

        // 2. Ambil gunung unggulan Bento Grid (1 Hero Utama Span 7 - Slot 1)
        $featuredHero = Mountain::query()
            ->active()
            ->featured()
            ->where('featured_order', 1)
            ->with('primaryRoute')
            ->first();

        // Fallback jika Slot 1 belum diset admin
        if (! $featuredHero) {
            $featuredHero = Mountain::query()
                ->active()
                ->featured()
                ->with('primaryRoute')
                ->first();
        }

        // 3. Ambil 2 gunung sekunder Bento Grid (Span 5 - Slot 2 & Slot 3)
        $featuredCards = Mountain::query()
            ->active()
            ->featured()
            ->when($featuredHero, fn ($query) => $query->where('id', '!=', $featuredHero->id))
            ->whereIn('featured_order', [2, 3])
            ->with('primaryRoute')
            ->orderBy('featured_order')
            ->get();

        // Fallback jika slot 2 atau 3 belum lengkap
        if ($featuredCards->count() < 2) {
            $excludeIds = array_filter([$featuredHero?->id, ...$featuredCards->pluck('id')->all()]);
            $additionalCards = Mountain::query()
                ->active()
                ->whereNotIn('id', $excludeIds)
                ->with('primaryRoute')
                ->take(2 - $featuredCards->count())
                ->get();
            $featuredCards = $featuredCards->merge($additionalCards);
        }

        return view('home', compact('mountains', 'featuredHero', 'featuredCards'));
    }
}
