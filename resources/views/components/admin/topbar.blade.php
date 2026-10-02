<header
    class="h-16 bg-surface-card border-b border-hairline sticky top-0 z-30 px-4 md:px-8 flex items-center justify-between">
    <!-- Left Section: Mobile Toggle & Breadcrumbs -->
    <div class="flex items-center gap-3">
        <button @click="sidebarOpen = true"
            class="md:hidden text-ink p-1.5 rounded-lg border border-hairline hover:bg-canvas transition-colors cursor-pointer"
            aria-label="Buka Menu">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <div class="flex items-center gap-2 text-xs font-medium text-muted">
            <span>Admin</span>
            <span class="text-muted-soft">/</span>
            <span class="text-ink-heading font-semibold">@yield('title', 'Dashboard')</span>
        </div>
    </div>

    <!-- Right Section: User Profile & Dropdown -->
    <div class="flex items-center gap-3">

        <!-- Profile Dropdown Component -->
        <div x-data="{ open: false }" class="relative">
            <!-- Profile Button Trigger -->
            <button @click="open = !open" type="button" id="admin-profile-menu-button" aria-haspopup="true"
                :aria-expanded="open.toString()"
                class="flex items-center gap-2.5 pl-3 border-l border-hairline group cursor-pointer focus:outline-hidden">
                <div class="text-right hidden sm:block leading-tight">
                    <span class="block text-xs font-bold text-ink-heading group-hover:text-primary transition-colors">
                        {{ Auth::user()->name ?? 'Administrator' }}
                    </span>
                    <span class="block text-[10px] font-medium text-muted">
                        {{ (Auth::user()->role ?? 'admin') === 'admin' ? 'Administrator' : 'Operator' }}
                    </span>
                </div>
                <div
                    class="w-8 h-8 rounded-full bg-primary-subtle text-primary border border-primary/20 flex items-center justify-center font-bold text-xs uppercase group-hover:bg-primary group-hover:text-white transition-colors shadow-2xs">
                    {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                </div>
                <svg class="w-3.5 h-3.5 text-muted transition-transform duration-200 hidden sm:block"
                    :class="open ? 'rotate-180 text-primary' : ''" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Dropdown Menu -->
            <div x-show="open" @click.outside="open = false" @keydown.escape.window="open = false"
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 -translate-y-1" x-cloak
                class="absolute right-0 mt-2 w-56 bg-surface-card border border-hairline rounded-2xl shadow-xl py-2 z-50 divide-y divide-hairline">

                <!-- Header User Info -->
                <div class="px-4 py-2.5">
                    <p class="text-[10px] font-bold text-muted uppercase tracking-wider">Signed in as</p>
                    <p class="text-xs font-bold text-ink-heading truncate mt-0.5">
                        {{ Auth::user()->name ?? 'Administrator' }}</p>
                    <p class="text-[11px] text-muted truncate">{{ Auth::user()->email ?? 'admin@middletrip.id' }}</p>
                </div>

                <!-- Action Links -->
                <div class="py-1">
                    <a href="{{ route('admin.profile.edit') }}" @click="open = false"
                        class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-ink hover:bg-canvas hover:text-primary transition-colors">
                        <svg class="w-4 h-4 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Pengaturan Akun</span>
                    </a>

                    <a href="{{ route('home') }}" target="_blank" @click="open = false"
                        class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-ink hover:bg-canvas hover:text-primary transition-colors">
                        <svg class="w-4 h-4 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                        <span>Lihat Website</span>
                    </a>
                </div>

                <!-- Logout Action -->
                <div class="py-1">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 hover:text-rose-700 transition-colors cursor-pointer text-left">
                            <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
