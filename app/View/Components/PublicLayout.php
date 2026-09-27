<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class PublicLayout extends Component
{
    public function __construct(
        public string $title = 'MiddleTrip - Jalan Tengah Menuju Puncak yang Sesungguhnya',
        public string $description = 'Platform digital ekspedisi pendakian gunung di Indonesia. Layanan Open Trip, Private Trip, klasifikasi jalur Grade A/B/C, dan pemanduan profesional.',
        public string $active = 'home',
        public bool $hero = false,
        public string $bodyClass = 'bg-canvas text-ink antialiased font-sans',
        public bool $withNavbar = true,
        public bool $withFooter = true,
        public bool $withFab = true,
    ) {}

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.public');
    }
}
