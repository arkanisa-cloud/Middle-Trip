<aside id="admin-sidebar" 
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
       class="fixed inset-y-0 left-0 z-40 w-64 h-screen bg-surface-forest text-white border-r border-surface-forest-border flex flex-col shrink-0 select-none topo-pattern transition-transform duration-300 ease-in-out md:translate-x-0">
    
    <!-- Brand / Header -->
    <div class="h-16 flex items-center justify-between px-5 border-b border-surface-forest-border shrink-0">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
            <div class="w-9 h-9 rounded-xl bg-primary text-white flex items-center justify-center font-black font-outfit text-lg shadow-sm group-hover:scale-105 transition-transform">
                M
            </div>
            <div>
                <span class="font-extrabold font-outfit text-lg tracking-tight text-white block leading-tight">MiddleTrip</span>
                <span class="text-[10px] font-bold font-outfit tracking-wider uppercase text-emerald-400 block">Operator Admin</span>
            </div>
        </a>

        <!-- Mobile close button -->
        <button @click="sidebarOpen = false" class="md:hidden text-gray-400 hover:text-white p-1 rounded-lg focus:outline-hidden">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <!-- Navigation Links -->
    <div class="flex-1 overflow-y-auto py-4 px-3 space-y-6 no-scrollbar">
        <!-- Group: MENU -->
        <div>
            <div class="px-3 mb-2 text-xs font-semibold text-gray-400">Menu</div>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150 {{ request()->routeIs('admin.dashboard') ? 'bg-primary text-white shadow-xs font-semibold' : 'text-gray-300 hover:bg-surface-forest-card hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Group: MASTER DATA -->
        <div>
            <div class="px-3 mb-2 text-xs font-semibold text-gray-400">Master Data</div>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('admin.mountains.index') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150 {{ request()->routeIs('admin.mountains.*') ? 'bg-primary text-white shadow-xs font-semibold' : 'text-gray-300 hover:bg-surface-forest-card hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 17l6-6 4 4 8-8M3 21h18"/>
                        </svg>
                        <span>Gunung & Jalur</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.meeting-points.index') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150 {{ request()->routeIs('admin.meeting-points.*') ? 'bg-primary text-white shadow-xs font-semibold' : 'text-gray-300 hover:bg-surface-forest-card hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>Titik Kumpul Shuttle</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.addons.index') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150 {{ request()->routeIs('admin.addons.*') ? 'bg-primary text-white shadow-xs font-semibold' : 'text-gray-300 hover:bg-surface-forest-card hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        <span>Add-ons Sewa Alat</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Group: EKSPEDISI -->
        <div>
            <div class="px-3 mb-2 text-xs font-semibold text-gray-400">Jadwal Ekspedisi</div>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('admin.expeditions.index') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150 {{ request()->routeIs('admin.expeditions.*') ? 'bg-primary text-white shadow-xs font-semibold' : 'text-gray-300 hover:bg-surface-forest-card hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>Batch Jadwal Trip</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Group: TRANSAKSI -->
        <div>
            <div class="px-3 mb-2 text-xs font-semibold text-gray-400">Operasional & Booking</div>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('admin.bookings.index') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150 {{ request()->routeIs('admin.bookings.*') ? 'bg-primary text-white shadow-xs font-semibold' : 'text-gray-300 hover:bg-surface-forest-card hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>Reservasi & SIMAKSI</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- User Profile & Logout Bottom Card -->
    <div class="p-3 border-t border-surface-forest-border shrink-0 bg-surface-forest-card">
        <div class="flex items-center justify-between gap-3 px-2 py-1.5">
            <a href="{{ route('admin.profile.edit') }}" title="Pengaturan Profil" class="flex items-center gap-2.5 overflow-hidden group">
                <div class="w-9 h-9 rounded-full bg-surface-forest-border flex items-center justify-center font-bold text-sm text-primary uppercase shrink-0 border border-surface-forest-border group-hover:border-primary transition-colors">
                    {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                </div>
                <div class="overflow-hidden">
                    <p class="text-xs font-bold text-white truncate leading-tight group-hover:text-primary transition-colors">{{ Auth::user()->name ?? 'Administrator' }}</p>
                    <p class="text-[10px] text-gray-400 truncate">{{ Auth::user()->email ?? 'admin@middletrip.id' }}</p>
                </div>
            </a>
            <form method="POST" action="{{ route('logout') }}" class="shrink-0">
                @csrf
                <button type="submit" title="Logout" class="p-1.5 text-gray-400 hover:text-rose-400 rounded-lg hover:bg-surface-forest transition-colors cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</aside>
