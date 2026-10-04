<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil & Pesanan Saya - MiddleTrip</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <x-favicon />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="bg-canvas text-ink antialiased selection:bg-primary selection:text-white font-sans min-h-screen flex flex-col justify-between"
    x-data="{ 
        activeTab: '{{ request('tab', ($bookings->count() > 0 ? 'orders' : 'orders')) }}',
        filterStatus: 'all'
    }">

    <!-- Top Navigation Component -->
    <x-navbar active="" :hero="false" />

    <!-- Main Content Container -->
    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 md:pt-28 pb-16 w-full flex-1">

        <!-- Breadcrumb Navigation -->
        <nav class="flex items-center gap-2 text-xs font-medium text-muted mb-6" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-primary transition-colors flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
                <span>Home</span>
            </a>
            <span class="text-muted-soft">/</span>
            <span class="text-ink-heading font-semibold" x-text="activeTab === 'orders' ? 'Pesanan Saya' : 'Pengaturan Akun'">Pesanan Saya</span>
        </nav>

        <!-- User Profile Hero Banner -->
        <div class="bg-white rounded-3xl border border-hairline p-5 sm:p-8 shadow-xs mb-6 sm:mb-8 relative overflow-hidden">
            <!-- Decorative Mountain Silhouette -->
            <div class="absolute -right-8 -bottom-10 opacity-[0.03] pointer-events-none select-none hidden sm:block">
                <svg class="w-72 h-72 text-ink-heading" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M8 3l4 8 5-5 5 15H2L8 3z" />
                </svg>
            </div>

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-5 sm:gap-6 relative z-10">
                <!-- User Ident Card -->
                <div class="flex items-center gap-3.5 sm:gap-5">
                    @if ($user->avatar)
                        <img src="{{ str_starts_with($user->avatar, 'http') ? $user->avatar : asset('storage/' . $user->avatar) }}"
                            alt="{{ $user->name }}"
                            decoding="async"
                            class="w-14 h-14 sm:w-20 sm:h-20 rounded-2xl object-cover shrink-0 border-2 border-white ring-4 ring-primary-subtle shadow-sm">
                    @else
                        <div class="w-14 h-14 sm:w-20 sm:h-20 rounded-2xl bg-gradient-to-br from-primary via-primary-hover to-primary-active text-white flex items-center justify-center font-extrabold text-xl sm:text-3xl shadow-sm tracking-tight shrink-0 border-2 border-white ring-4 ring-primary-subtle">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                    @endif
                    <div class="min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h1 class="text-lg sm:text-2xl font-extrabold text-ink-heading tracking-tight truncate">
                                {{ $user->name }}
                            </h1>
                            <span class="inline-flex items-center gap-1.5 px-2.5 sm:px-3 py-0.5 sm:py-1 rounded-full text-[10px] sm:text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Pendaki Terverifikasi
                            </span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-3 text-xs sm:text-sm text-muted mt-1 font-medium">
                            <span class="flex items-center gap-1.5 truncate">
                                <svg class="w-3.5 h-3.5 text-muted-soft shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                                <span class="truncate">{{ $user->email }}</span>
                            </span>
                            <span class="text-muted-soft hidden sm:inline">&bull;</span>
                            <span class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-muted-soft shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                </svg>
                                <span>Bergabung {{ $user->created_at ? $user->created_at->translatedFormat('M Y') : '2024' }}</span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Quick Metrics Bar -->
                <div class="w-full md:w-auto flex items-center justify-between sm:justify-center gap-1 sm:gap-2 self-stretch md:self-center bg-canvas/80 p-2 rounded-2xl border border-hairline/80">
                    <div class="flex-1 md:flex-none text-center px-2.5 sm:px-4 py-1.5">
                        <span class="block text-base sm:text-xl font-extrabold text-ink-heading leading-tight">{{ $bookings->count() }}</span>
                        <span class="text-[9px] sm:text-[10px] uppercase font-bold text-muted tracking-wider whitespace-nowrap">Total Trip</span>
                    </div>
                    <div class="w-px h-7 bg-hairline"></div>
                    <div class="flex-1 md:flex-none text-center px-2.5 sm:px-4 py-1.5">
                        <span class="block text-base sm:text-xl font-extrabold text-sky-600 leading-tight">
                            {{ $bookings->whereIn('status', ['reserved', 'price_locked'])->count() }}
                        </span>
                        <span class="text-[9px] sm:text-[10px] uppercase font-bold text-muted tracking-wider whitespace-nowrap">DP / Aktif</span>
                    </div>
                    <div class="w-px h-7 bg-hairline"></div>
                    <div class="flex-1 md:flex-none text-center px-2.5 sm:px-4 py-1.5">
                        <span class="block text-base sm:text-xl font-extrabold text-emerald-600 leading-tight">
                            {{ $bookings->where('status', 'paid')->count() }}
                        </span>
                        <span class="text-[9px] sm:text-[10px] uppercase font-bold text-muted tracking-wider whitespace-nowrap">Lunas</span>
                    </div>
                </div>
            </div>

            <!-- Tab Switcher Navigation (Design System Segmented Capsule) -->
            <div class="mt-6 sm:mt-7 pt-5 sm:pt-6 border-t border-hairline/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4">
                <div class="bg-canvas p-1 rounded-2xl sm:rounded-full flex border border-hairline shadow-2xs w-full sm:w-auto">
                    <button type="button"
                        @click="activeTab = 'orders'"
                        :class="activeTab === 'orders' 
                            ? 'bg-white text-ink-heading shadow-xs font-bold' 
                            : 'text-muted hover:text-ink-heading font-medium'"
                        class="flex-1 sm:flex-none px-3.5 sm:px-5 py-2 sm:py-2.5 rounded-xl sm:rounded-full text-xs sm:text-sm transition-all flex items-center justify-center gap-1.5 sm:gap-2 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <span>Pesanan Saya</span>
                        @if ($bookings->count() > 0)
                            <span class="text-[10px] font-extrabold px-1.5 sm:px-2 py-0.5 rounded-full transition-colors ml-0.5"
                                :class="activeTab === 'orders' ? 'bg-primary text-white' : 'bg-gray-200 text-muted'">
                                {{ $bookings->count() }}
                            </span>
                        @endif
                    </button>

                    <button type="button"
                        @click="activeTab = 'settings'"
                        :class="activeTab === 'settings' 
                            ? 'bg-white text-ink-heading shadow-xs font-bold' 
                            : 'text-muted hover:text-ink-heading font-medium'"
                        class="flex-1 sm:flex-none px-3.5 sm:px-5 py-2 sm:py-2.5 rounded-xl sm:rounded-full text-xs sm:text-sm transition-all flex items-center justify-center gap-1.5 sm:gap-2 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Pengaturan Akun</span>
                    </button>
                </div>

                <a href="{{ route('ekspedisi.index') }}" class="text-xs font-bold text-primary hover:text-primary-hover flex items-center justify-center gap-1.5 transition-colors self-center sm:self-center py-1">
                    <span>Cari Destinasi Baru</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 1: PESANAN SAYA -->
        <!-- ========================================== -->
        <div x-show="activeTab === 'orders'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">

            <!-- Sub-filter Status Pills (Segmented Filter Bar - Selalu Tampil) -->
            <div class="flex items-center gap-2 overflow-x-auto pb-2 no-scrollbar">
                <button type="button" @click="filterStatus = 'all'"
                    :class="filterStatus === 'all' ? 'bg-primary text-white font-bold shadow-xs' : 'bg-white text-body hover:text-ink-heading border border-hairline font-semibold'"
                    class="px-4 py-2 rounded-full text-xs transition-all shadow-2xs whitespace-nowrap cursor-pointer">
                    Semua Trip ({{ $bookings->count() }})
                </button>

                <button type="button" @click="filterStatus = 'pending_dp'"
                    :class="filterStatus === 'pending_dp' ? 'bg-primary text-white font-bold shadow-xs' : 'bg-white text-body hover:text-ink-heading border border-hairline font-semibold'"
                    class="px-4 py-2 rounded-full text-xs transition-all shadow-2xs whitespace-nowrap cursor-pointer flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full" :class="filterStatus === 'pending_dp' ? 'bg-white' : 'bg-amber-500'"></span>
                    Menunggu DP ({{ $bookings->where('status', 'open')->count() }})
                </button>

                <button type="button" @click="filterStatus = 'pending_settle'"
                    :class="filterStatus === 'pending_settle' ? 'bg-primary text-white font-bold shadow-xs' : 'bg-white text-body hover:text-ink-heading border border-hairline font-semibold'"
                    class="px-4 py-2 rounded-full text-xs transition-all shadow-2xs whitespace-nowrap cursor-pointer flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full" :class="filterStatus === 'pending_settle' ? 'bg-white' : 'bg-orange-500'"></span>
                    Menunggu Pelunasan ({{ $bookings->where('status', 'price_locked')->count() }})
                </button>

                <button type="button" @click="filterStatus = 'reserved'"
                    :class="filterStatus === 'reserved' ? 'bg-primary text-white font-bold shadow-xs' : 'bg-white text-body hover:text-ink-heading border border-hairline font-semibold'"
                    class="px-4 py-2 rounded-full text-xs transition-all shadow-2xs whitespace-nowrap cursor-pointer flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full" :class="filterStatus === 'reserved' ? 'bg-white' : 'bg-sky-500'"></span>
                    DP Terbayar ({{ $bookings->where('status', 'reserved')->count() }})
                </button>

                <button type="button" @click="filterStatus = 'paid'"
                    :class="filterStatus === 'paid' ? 'bg-primary text-white font-bold shadow-xs' : 'bg-white text-body hover:text-ink-heading border border-hairline font-semibold'"
                    class="px-4 py-2 rounded-full text-xs transition-all shadow-2xs whitespace-nowrap cursor-pointer flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full" :class="filterStatus === 'paid' ? 'bg-white' : 'bg-emerald-500'"></span>
                    Lunas &amp; Tiket Terbit ({{ $bookings->where('status', 'paid')->count() }})
                </button>
            </div>

            @if ($bookings->count() > 0)
                <!-- Empty Filter Category Notice -->
                <div x-show="filterStatus === 'pending_dp' && {{ $bookings->where('status', 'open')->count() }} === 0"
                    class="bg-white rounded-3xl border border-hairline p-8 text-center shadow-xs">
                    <p class="text-xs sm:text-sm text-muted">Tidak ada trip yang sedang menunggu pembayaran DP.</p>
                    <button type="button" @click="filterStatus = 'all'" class="mt-3 text-xs font-bold text-primary hover:underline">
                        Lihat Semua Trip
                    </button>
                </div>
                <div x-show="filterStatus === 'pending_settle' && {{ $bookings->where('status', 'price_locked')->count() }} === 0"
                    class="bg-white rounded-3xl border border-hairline p-8 text-center shadow-xs">
                    <p class="text-xs sm:text-sm text-muted">Tidak ada trip yang sedang menunggu pelunasan.</p>
                    <button type="button" @click="filterStatus = 'all'" class="mt-3 text-xs font-bold text-primary hover:underline">
                        Lihat Semua Trip
                    </button>
                </div>
                <div x-show="filterStatus === 'reserved' && {{ $bookings->where('status', 'reserved')->count() }} === 0"
                    class="bg-white rounded-3xl border border-hairline p-8 text-center shadow-xs">
                    <p class="text-xs sm:text-sm text-muted">Tidak ada trip yang berstatus DP terbayar (menunggu price lock).</p>
                    <button type="button" @click="filterStatus = 'all'" class="mt-3 text-xs font-bold text-primary hover:underline">
                        Lihat Semua Trip
                    </button>
                </div>
                <div x-show="filterStatus === 'paid' && {{ $bookings->where('status', 'paid')->count() }} === 0"
                    class="bg-white rounded-3xl border border-hairline p-8 text-center shadow-xs">
                    <p class="text-xs sm:text-sm text-muted">Tidak ada trip yang sudah lunas.</p>
                    <button type="button" @click="filterStatus = 'all'" class="mt-3 text-xs font-bold text-primary hover:underline">
                        Lihat Semua Trip
                    </button>
                </div>
            @endif

            <!-- List of Order Cards -->
            @forelse ($bookings as $booking)
                @php
                    $mountain = $booking->expedition?->mountain ?? $booking->route?->mountain;
                    $mountainName = $mountain?->name ?? 'Ekspedisi Pendakian';
                    $elevationText = $mountain?->elevation ? number_format($mountain->elevation, 0, ',', '.') . ' MDPL' : '3.000+ MDPL';
                    $rawCover = $mountain?->cover_image;
                    if ($rawCover) {
                        if (str_starts_with($rawCover, 'http://') || str_starts_with($rawCover, 'https://') || str_starts_with($rawCover, '/')) {
                            $coverImg = $rawCover;
                        } else {
                            $coverImg = asset('storage/' . $rawCover);
                        }
                    } else {
                        $coverImg = 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&w=800&q=80';
                    }

                    $grade = $booking->route?->grade?->value ?? $booking->route?->grade ?? 'Grade A';
                    $gradeBadgeClass = match($grade) {
                        'Grade B' => 'bg-[#FFF0E6] text-[#B85320] border-[#B85320]/20',
                        'Grade C' => 'bg-[#FDECEB] text-[#B92F26] border-[#B92F26]/20',
                        default => 'bg-[#EAF5EF] text-[#226848] border-[#226848]/20',
                    };
                    $gradeDotClass = match($grade) {
                        'Grade B' => 'bg-[#F59E0B]',
                        'Grade C' => 'bg-[#F43F5E]',
                        default => 'bg-[#10B981]',
                    };

                    // Action route & status styling
                    if ($booking->status === 'open') {
                        $actionUrl = $booking->trip_type === 'private' ? route('checkout.private', $booking->booking_code) : route('checkout.step1', $booking->booking_code);
                        $actionLabel = $booking->trip_type === 'private' ? 'Bayar Penuh' : 'Bayar DP Sekarang';
                        $statusBadgeClass = 'bg-amber-50 text-amber-800 border-amber-200';
                        $statusText = $booking->trip_type === 'private' ? 'Menunggu Pembayaran Penuh' : 'Menunggu Pembayaran DP';
                        $statusDot = 'bg-amber-500';
                        $filterCategory = 'pending_dp';
                    } elseif ($booking->status === 'price_locked') {
                        $actionUrl = route('checkout.status', $booking->booking_code);
                        $actionLabel = 'Bayar Pelunasan Sekarang';
                        $statusBadgeClass = 'bg-orange-50 text-orange-800 border-orange-200';
                        $statusText = 'Menunggu Pelunasan';
                        $statusDot = 'bg-orange-500';
                        $filterCategory = 'pending_settle';
                    } elseif ($booking->status === 'reserved') {
                        $actionUrl = route('checkout.status', $booking->booking_code);
                        $actionLabel = 'Cek Status & Jadwal';
                        $statusBadgeClass = 'bg-sky-50 text-sky-800 border-sky-200';
                        $statusText = 'DP Terbayar (Menunggu Price Lock)';
                        $statusDot = 'bg-sky-500';
                        $filterCategory = 'reserved';
                    } elseif ($booking->status === 'paid') {
                        $actionUrl = route('checkout.success', $booking->booking_code);
                        $actionLabel = 'Lihat E-Tiket';
                        $statusBadgeClass = 'bg-emerald-50 text-emerald-800 border-emerald-200';
                        $statusText = 'Lunas - Siap Mendaki';
                        $statusDot = 'bg-emerald-500';
                        $filterCategory = 'paid';
                    } else {
                        $actionUrl = '#';
                        $actionLabel = 'Detail Pesanan';
                        $statusBadgeClass = 'bg-rose-50 text-rose-800 border-rose-200';
                        $statusText = 'Dibatalkan / Kadaluwarsa';
                        $statusDot = 'bg-rose-500';
                        $filterCategory = 'cancelled';
                    }
                @endphp

                <div x-show="filterStatus === 'all' || filterStatus === '{{ $filterCategory }}'"
                    class="bg-white rounded-3xl border border-hairline p-5 sm:p-6 shadow-xs hover:shadow-md transition-all duration-300">
                    
                    <!-- Card Top Header: Booking Code & Status -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-hairline/80 gap-3">
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <span class="font-mono text-xs font-bold text-ink-heading bg-canvas px-3 py-1 rounded-full border border-hairline inline-flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-muted-soft" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 0 1 0-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375Z" />
                                </svg>
                                <span>#{{ $booking->booking_code }}</span>
                            </span>
                            <span class="text-xs text-muted font-medium">
                                Dipesan pada {{ $booking->created_at ? $booking->created_at->translatedFormat('d M Y, H:i') : '-' }} WIB
                            </span>
                        </div>

                        <!-- Status Badge -->
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border self-start sm:self-auto {{ $statusBadgeClass }}">
                            <span class="w-2 h-2 rounded-full {{ $statusDot }} animate-pulse"></span>
                            {{ $statusText }}
                        </span>
                    </div>

                    <!-- Card Body: Mountain Thumbnail & Destination Details -->
                    <div class="py-4 sm:py-5 flex flex-row items-start sm:items-center gap-3.5 sm:gap-7 w-full">
                        
                        <!-- Mountain Thumbnail Cover -->
                        <div class="relative w-20 h-20 sm:w-28 sm:h-28 md:w-32 md:h-32 rounded-2xl overflow-hidden shrink-0 border border-hairline shadow-2xs group bg-gray-100">
                            <img src="{{ $coverImg }}" alt="{{ $mountainName }}"
                                loading="lazy" decoding="async"
                                onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&w=800&q=80';"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent pointer-events-none"></div>
                            <span class="absolute bottom-1 sm:bottom-1.5 left-1 sm:left-1.5 right-1 sm:right-1.5 text-center text-[9px] sm:text-[10px] font-extrabold text-white bg-black/60 backdrop-blur-xs px-1.5 sm:px-2 py-0.5 rounded-md sm:rounded-lg border border-white/20 truncate">
                                {{ $elevationText }}
                            </span>
                        </div>

                        <!-- Destination & Route Meta -->
                        <div class="space-y-2 sm:space-y-3 flex-1 min-w-0">
                            <div class="flex items-center gap-2.5 flex-wrap">
                                <h2 class="text-base sm:text-xl font-extrabold text-ink-heading hover:text-primary transition-colors truncate">
                                    {{ $mountainName }}
                                </h2>
                            </div>

                            <!-- Feature Pills (Trip Type, Hike Type, Route Grade) -->
                            <div class="flex items-center gap-1.5 sm:gap-2 flex-wrap">
                                <!-- Trip Type Pill -->
                                <span class="text-[10px] sm:text-xs font-bold px-2.5 sm:px-3 py-0.5 sm:py-1 rounded-full {{ $booking->trip_type === 'private' ? 'bg-purple-50 text-purple-700 border border-purple-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">
                                    {{ $booking->trip_type === 'private' ? 'Private Trip' : 'Open Trip' }}
                                </span>

                                <!-- Hiking Type Pill -->
                                <span class="text-[10px] sm:text-xs font-bold px-2.5 sm:px-3 py-0.5 sm:py-1 rounded-full {{ $booking->hiking_type === 'tektok' ? 'bg-sky-50 text-sky-700 border border-sky-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                    {{ $booking->hiking_type === 'tektok' ? 'Tektok (1 Hari)' : 'Camping Ceria' }}
                                </span>

                                <!-- Route & Grade Pill -->
                                <span class="text-[10px] sm:text-xs font-bold px-2.5 sm:px-3 py-0.5 sm:py-1 rounded-full border inline-flex items-center gap-1 sm:gap-1.5 {{ $gradeBadgeClass }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $gradeDotClass }}"></span>
                                    {{ $booking->route?->name ?? 'Jalur Standar' }}
                                </span>
                            </div>

                            <!-- Schedule, Pax, & Meeting Point Info -->
                            <div class="text-[11px] sm:text-xs text-muted flex items-center gap-2 sm:gap-3.5 pt-0.5 flex-wrap font-medium">
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-muted-soft" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ $booking->departure_date ? $booking->departure_date->translatedFormat('d M Y') : 'Fleksibel' }}
                                </span>
                                <span class="text-muted-soft">&bull;</span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-muted-soft" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    {{ $booking->pax_count }} Peserta
                                </span>
                                @if ($booking->meetingPoint)
                                    <span class="text-muted-soft">&bull;</span>
                                    <span class="flex items-center gap-1 truncate max-w-[200px] sm:max-w-[240px]" title="{{ $booking->meetingPoint->name }}">
                                        <svg class="w-3.5 h-3.5 text-muted-soft shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span class="truncate">{{ $booking->meetingPoint->name }}</span>
                                    </span>
                                @endif
                            </div>
                        </div>

                    </div>

                    <!-- Card Footer: Dedicated Pricing & Action CTA Bar -->
                    <div class="border-t border-hairline/80 pt-3.5 sm:pt-4 mt-2 flex flex-col sm:flex-row sm:items-center justify-between gap-3.5 sm:gap-4 bg-canvas/50 -mx-5 sm:-mx-6 -mb-5 sm:-mb-6 px-4 sm:px-6 py-3.5 sm:py-4 rounded-b-3xl">
                        <div>
                            <span class="text-[10px] sm:text-[11px] font-semibold text-muted block">Total Biaya Ekspedisi</span>
                            <div class="flex items-baseline gap-2 flex-wrap">
                                <span class="text-base sm:text-xl font-extrabold text-ink-heading">
                                    Rp {{ number_format($booking->grand_total, 0, ',', '.') }}
                                </span>
                                @if ($booking->status === 'open' && $booking->trip_type === 'open')
                                    <span class="text-[10px] sm:text-[11px] text-amber-700 font-bold bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200">
                                        DP Awal: Rp {{ number_format($booking->total_booking_fee, 0, ',', '.') }}
                                    </span>
                                @elseif ($booking->status === 'price_locked')
                                    <span class="text-[10px] sm:text-[11px] text-orange-700 font-bold bg-orange-50 px-2 py-0.5 rounded-full border border-orange-200">
                                        Wajib Pelunasan: Rp {{ number_format($booking->remaining_payment_total, 0, ',', '.') }}
                                    </span>
                                @elseif ($booking->status === 'reserved')
                                    <span class="text-[10px] sm:text-[11px] text-sky-700 font-bold bg-sky-50 px-2 py-0.5 rounded-full border border-sky-200">
                                        DP Lunas: Rp {{ number_format($booking->total_booking_fee, 0, ',', '.') }}
                                    </span>
                                @elseif ($booking->status === 'paid')
                                    <span class="text-[10px] sm:text-[11px] text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                                        Lunas 100%
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="flex flex-col xs:flex-row sm:flex-row items-stretch sm:items-center gap-2 w-full sm:w-auto">
                            @if (in_array($booking->status, ['reserved', 'price_locked', 'paid']))
                                <a href="{{ route('bookings.print', $booking->booking_code) }}" target="_blank"
                                    class="w-full sm:w-auto bg-white hover:bg-slate-50 text-ink border border-hairline hover:border-slate-300 font-bold text-xs sm:text-sm px-4 py-2.5 rounded-xl sm:rounded-full transition-all inline-flex items-center justify-center gap-1.5 shadow-2xs hover:shadow-xs cursor-pointer"
                                    title="Cetak E-Tiket / Invoice">
                                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                    </svg>
                                    <span>Cetak PDF</span>
                                </a>
                            @endif

                            @if ($actionUrl !== '#')
                                <a href="{{ $actionUrl }}"
                                    class="w-full sm:w-auto {{ $booking->status === 'open' ? 'bg-primary hover:bg-primary-hover shadow-xs hover:shadow' : ($booking->status === 'paid' ? 'bg-emerald-600 hover:bg-emerald-700 shadow-xs hover:shadow' : 'bg-surface-forest hover:bg-surface-forest-card shadow-xs hover:shadow') }} text-white font-bold text-xs sm:text-sm px-6 py-2.5 rounded-xl sm:rounded-full transition-all inline-flex items-center justify-center gap-2 active:scale-95 cursor-pointer text-center">
                                    <span>{{ $actionLabel }}</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>

                </div>
            @empty
                <!-- Empty State (Outdoor Alpine Aesthetic) -->
                <div class="bg-white rounded-3xl border border-hairline p-8 sm:p-14 text-center shadow-xs">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-primary-subtle text-primary mx-auto flex items-center justify-center mb-4 border border-primary/20">
                        <svg class="w-8 h-8 sm:w-10 sm:h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-base sm:text-xl font-extrabold text-ink-heading tracking-tight mb-2">
                        Belum Ada Riwayat Pesanan
                    </h3>
                    <p class="text-xs sm:text-sm text-muted max-w-md mx-auto mb-6 leading-relaxed">
                        Anda belum memiliki jadwal ekspedisi gunung aktif. Mari wujudkan petualangan mendaki impian Anda dengan fasilitas terlengkap dan guide profesional.
                    </p>
                    <a href="{{ route('ekspedisi.index') }}"
                        class="w-full sm:w-auto bg-primary hover:bg-primary-hover active:bg-primary-active text-white text-xs sm:text-sm font-bold px-7 py-3 rounded-xl sm:rounded-full shadow-sm hover:shadow transition-all inline-flex items-center justify-center gap-2">
                        <span>Jelajahi Ekspedisi Gunung</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                </div>
            @endforelse

        </div>

        <!-- ========================================== -->
        <!-- TAB 2: PENGATURAN AKUN -->
        <!-- ========================================== -->
        <div x-show="activeTab === 'settings'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">

            <!-- Card 1: Ubah Nama & Email -->
            <div class="bg-white rounded-3xl border border-hairline p-5 sm:p-8 shadow-xs">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-hairline/80">
                    <div class="w-10 h-10 rounded-2xl bg-primary-subtle text-primary flex items-center justify-center shrink-0 border border-primary/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-ink-heading">Informasi Akun</h2>
                        <p class="text-xs text-muted">Perbarui nama lengkap dan alamat email yang terdaftar pada akun pendaki Anda.</p>
                    </div>
                </div>

                @if (session('status') === 'profile-updated')
                    <div class="mb-5 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs flex items-center gap-2.5 shadow-2xs">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="font-semibold">Informasi profil Anda berhasil diperbarui!</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    @method('patch')

                    <!-- Foto Profil -->
                    <div x-data="{
                        photoPreview: null,
                        removePhoto: false,
                        async updatePreview() {
                            let file = $refs.photo.files[0];
                            if (!file) return;
                            if (window.convertToWebP) {
                                file = await window.convertToWebP(file, 0.85, 512);
                                if (window.DataTransfer) {
                                    const dt = new DataTransfer();
                                    dt.items.add(file);
                                    $refs.photo.files = dt.files;
                                }
                            }
                            this.photoPreview = URL.createObjectURL(file);
                            this.removePhoto = false;
                        },
                        clearPhoto() {
                            this.photoPreview = null;
                            this.removePhoto = true;
                            $refs.photo.value = '';
                        }
                    }" class="pb-2">
                        <label class="block text-xs font-bold text-ink-heading uppercase tracking-wider mb-3">
                            Foto Profil
                        </label>

                        <input type="hidden" name="remove_avatar" :value="removePhoto ? 1 : 0">

                        <div class="flex items-center gap-4 sm:gap-6">
                            <!-- Avatar Preview Box -->
                            <div class="relative shrink-0">
                                <!-- Selected New Photo Preview -->
                                <template x-if="photoPreview">
                                    <img :src="photoPreview" alt="Preview Foto Profil"
                                        class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl object-cover border-2 border-primary ring-4 ring-primary-subtle shadow-xs">
                                </template>

                                <!-- Current Photo / Initials Fallback (when not previewing) -->
                                <template x-if="!photoPreview">
                                    <div>
                                        @if ($user->avatar)
                                            <div x-show="!removePhoto">
                                                <img src="{{ str_starts_with($user->avatar, 'http') ? $user->avatar : asset('storage/' . $user->avatar) }}"
                                                    alt="{{ $user->name }}"
                                                    decoding="async"
                                                    class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl object-cover border border-hairline ring-4 ring-slate-100 shadow-xs">
                                            </div>
                                            <div x-show="removePhoto"
                                                class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-gradient-to-br from-primary via-primary-hover to-primary-active text-white flex items-center justify-center font-extrabold text-xl sm:text-2xl shadow-xs border border-hairline ring-4 ring-slate-100">
                                                {{ strtoupper(substr($user->name, 0, 2)) }}
                                            </div>
                                        @else
                                            <div
                                                class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-gradient-to-br from-primary via-primary-hover to-primary-active text-white flex items-center justify-center font-extrabold text-xl sm:text-2xl shadow-xs border border-hairline ring-4 ring-slate-100">
                                                {{ strtoupper(substr($user->name, 0, 2)) }}
                                            </div>
                                        @endif
                                    </div>
                                </template>
                            </div>

                            <!-- Upload Controls -->
                            <div class="space-y-2">
                                <input type="file" x-ref="photo" name="avatar" id="avatar" accept="image/png,image/jpeg,image/jpg,image/webp"
                                    class="hidden" @change="updatePreview()">

                                <div class="flex items-center gap-2 flex-wrap">
                                    <button type="button" @click="$refs.photo.click()"
                                        class="px-4 py-2 rounded-xl border border-hairline bg-white hover:bg-canvas text-ink-heading text-xs font-bold transition shadow-2xs hover:shadow-xs cursor-pointer inline-flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-muted-soft" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                                        </svg>
                                        <span>Pilih Foto Baru</span>
                                    </button>

                                    @if ($user->avatar)
                                        <button type="button" x-show="!removePhoto || photoPreview" @click="clearPhoto()"
                                            class="px-3.5 py-2 rounded-xl border border-rose-200 bg-rose-50/50 hover:bg-rose-50 text-rose-700 text-xs font-semibold transition cursor-pointer inline-flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                            <span>Hapus Foto</span>
                                        </button>
                                    @else
                                        <button type="button" x-show="photoPreview" @click="clearPhoto()"
                                            class="px-3.5 py-2 rounded-xl border border-rose-200 bg-rose-50/50 hover:bg-rose-50 text-rose-700 text-xs font-semibold transition cursor-pointer inline-flex items-center gap-1.5">
                                            <span>Batal</span>
                                        </button>
                                    @endif
                                </div>

                                <p class="text-[11px] text-muted leading-relaxed">
                                    Format: JPG, PNG, atau WebP (Maksimal 2MB).
                                </p>
                            </div>
                        </div>

                        @error('avatar')
                            <p class="text-xs text-rose-600 mt-2 font-medium flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    <!-- Nama Lengkap -->
                    <div>
                        <label for="name" class="block text-xs font-bold text-ink-heading uppercase tracking-wider mb-2">
                            Nama Lengkap <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-muted-soft">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                </svg>
                            </div>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                                class="w-full pl-10 pr-4 py-2.5 border border-hairline rounded-xl text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none text-ink bg-white transition shadow-2xs font-outfit font-normal">
                        </div>
                        @error('name')
                            <p class="text-xs text-rose-600 mt-1.5 font-medium flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-ink-heading uppercase tracking-wider mb-2">
                            Alamat Email <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-muted-soft">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                </svg>
                            </div>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                                class="w-full pl-10 pr-4 py-2.5 border border-hairline rounded-xl text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none text-ink bg-white transition shadow-2xs font-outfit font-normal">
                        </div>
                        @error('email')
                            <p class="text-xs text-rose-600 mt-1.5 font-medium flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="submit"
                            class="w-full sm:w-auto bg-primary hover:bg-primary-hover active:bg-primary-active active:scale-[0.99] text-white text-xs sm:text-sm font-bold px-7 py-2.5 rounded-xl sm:rounded-full shadow-xs hover:shadow transition-all cursor-pointer text-center justify-center">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Card 2: Ubah Password -->
            <div class="bg-white rounded-3xl border border-hairline p-5 sm:p-8 shadow-xs">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-hairline/80">
                    <div class="w-10 h-10 rounded-2xl bg-canvas text-body-strong flex items-center justify-center shrink-0 border border-hairline">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-ink-heading">Keamanan Kata Sandi</h2>
                        <p class="text-xs text-muted">Pastikan akun pendaki Anda selalu aman dengan menggunakan kombinasi kata sandi yang kuat.</p>
                    </div>
                </div>

                @if (session('status') === 'password-updated')
                    <div class="mb-5 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs flex items-center gap-2.5 shadow-2xs">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="font-semibold">Kata sandi akun Anda berhasil diperbarui!</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                    @csrf
                    @method('put')

                    <!-- Password Saat Ini -->
                    <div>
                        <label for="current_password" class="block text-xs font-bold text-ink-heading uppercase tracking-wider mb-2">
                            Kata Sandi Saat Ini <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-muted-soft">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                </svg>
                            </div>
                            <input type="password" id="current_password" name="current_password" required autocomplete="current-password"
                                class="w-full pl-10 pr-4 py-2.5 border border-hairline rounded-xl text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none text-ink bg-white transition shadow-2xs font-outfit font-normal">
                        </div>
                        @if ($errors->updatePassword->has('current_password'))
                            <p class="text-xs text-rose-600 mt-1.5 font-medium flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                <span>{{ $errors->updatePassword->first('current_password') }}</span>
                            </p>
                        @endif
                    </div>

                    <!-- Password Baru & Konfirmasi -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-xs font-bold text-ink-heading uppercase tracking-wider mb-2">
                                Kata Sandi Baru <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-muted-soft">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                                    </svg>
                                </div>
                                <input type="password" id="password" name="password" required autocomplete="new-password"
                                    class="w-full pl-10 pr-4 py-2.5 border border-hairline rounded-xl text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none text-ink bg-white transition shadow-2xs font-outfit font-normal">
                            </div>
                            @if ($errors->updatePassword->has('password'))
                                <p class="text-xs text-rose-600 mt-1.5 font-medium flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                    <span>{{ $errors->updatePassword->first('password') }}</span>
                                </p>
                            @endif
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold text-ink-heading uppercase tracking-wider mb-2">
                                Konfirmasi Kata Sandi Baru <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-muted-soft">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0118 0Z" />
                                    </svg>
                                </div>
                                <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"
                                    class="w-full pl-10 pr-4 py-2.5 border border-hairline rounded-xl text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none text-ink bg-white transition shadow-2xs font-outfit font-normal">
                            </div>
                            @if ($errors->updatePassword->has('password_confirmation'))
                                <p class="text-xs text-rose-600 mt-1.5 font-medium flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                    <span>{{ $errors->updatePassword->first('password_confirmation') }}</span>
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="submit"
                            class="w-full sm:w-auto bg-surface-dark hover:bg-black active:scale-[0.99] text-white text-xs sm:text-sm font-bold px-7 py-2.5 rounded-xl sm:rounded-full shadow-xs hover:shadow transition-all cursor-pointer text-center justify-center">
                            Perbarui Kata Sandi
                        </button>
                    </div>
                </form>
            </div>

            <!-- Card 3: Keluar dari Akun (Sesi Login) -->
            <div class="bg-white rounded-3xl border border-hairline p-5 sm:p-8 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0 border border-rose-200/60">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-ink-heading">Keluar dari Akun</h2>
                        <p class="text-xs text-muted">Akhiri sesi login Anda di perangkat ini dengan aman.</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto shrink-0">
                    @csrf
                    <button type="submit"
                        class="w-full sm:w-auto bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs sm:text-sm px-6 py-2.5 rounded-xl sm:rounded-full border border-rose-200 shadow-2xs hover:shadow-xs transition-all cursor-pointer flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>Keluar Akun</span>
                    </button>
                </form>
            </div>

        </div>

    </main>

    <!-- Footer Component -->
    <x-footer />

</body>

</html>
