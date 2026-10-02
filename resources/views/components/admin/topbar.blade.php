<header class="h-16 bg-surface-card border-b border-hairline sticky top-0 z-30 px-4 md:px-8 flex items-center justify-between">
    <!-- Left Section: Mobile Toggle & Page Title -->
    <div class="flex items-center gap-3">
        <button @click="sidebarOpen = true" class="md:hidden text-ink p-1.5 rounded-lg border border-hairline hover:bg-canvas transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        <div>
            <h1 class="text-lg font-extrabold font-outfit text-ink-heading leading-tight tracking-tight">
                @yield('header_title', 'Admin Panel')
            </h1>
            <p class="text-xs text-muted hidden sm:block">
                @yield('header_subtitle', 'Pusat Operasional Ekspedisi MiddleTrip')
            </p>
        </div>
    </div>

    <!-- Right Section: Website Link, Quick Info, User Avatar -->
    <div class="flex items-center gap-3">
        <!-- Link to Main Site -->
        <a href="{{ route('home') }}" target="_blank" 
           class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-full border border-hairline text-ink hover:bg-canvas hover:border-muted-soft transition-colors shadow-2xs">
            <span>Lihat Website</span>
            <svg class="w-3.5 h-3.5 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
        </a>

        <!-- Live Server Status Indicator -->
        <div class="hidden lg:flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-xs font-semibold text-emerald-700">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span>Sistem Operasional Aktif</span>
        </div>

        <!-- Quick Profile Pill -->
        <div class="flex items-center gap-2 pl-2 border-l border-hairline">
            <div class="w-8 h-8 rounded-full bg-primary-subtle border border-primary/20 text-primary flex items-center justify-center font-bold text-xs uppercase">
                {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
            </div>
            <span class="text-xs font-bold text-ink-heading hidden sm:block">{{ Auth::user()->name ?? 'Admin' }}</span>
        </div>
    </div>
</header>
