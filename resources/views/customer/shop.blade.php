<x-public-layout title="MiddleTrip - Katalog Ekspedisi 2026" active="ekspedisi" :hero="false"
    body-class="min-h-screen flex flex-col justify-between bg-canvas text-ink antialiased selection:bg-primary selection:text-white font-sans overflow-x-hidden">

    <div class="flex-1">
        {{-- =====================================================================
             1. HEADER TITLE SECTION
        ===================================================================== --}}
        <section class="max-w-4xl mx-auto px-4 pt-24 md:pt-28 pb-7 text-center">
            <div
                class="inline-flex items-center px-4 py-1 rounded-full bg-gray-200/70 text-gray-800 text-[11px] md:text-xs font-semibold mb-4 tracking-wide">
                Katalog Ekspedisi 2026
            </div>
            <h1
                class="text-3xl sm:text-4xl md:text-[44px] font-extrabold text-ink-heading tracking-tight leading-tight mb-3">
                Jelajahi Puncak Indonesia
            </h1>
            <p class="text-muted text-xs sm:text-sm md:text-[14.5px] max-w-2xl mx-auto leading-relaxed">
                Temukan petualangan tak terlupakan di jajaran gunung api dan pegunungan tropis nusantara dengan standar
                pemanduan profesional.
            </p>
        </section>

        {{-- =====================================================================
             2. FILTER & SEGMENTED CONTROL
        ===================================================================== --}}
        <section class="max-w-6xl mx-auto px-4 md:px-8 mb-8 sm:mb-9 w-full">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3">

                <!-- Trip Category Filter (Pill segmented control) -->
                <div class="bg-gray-200/60 p-1 rounded-full flex items-center justify-center sm:justify-start gap-1 w-full sm:w-auto overflow-x-auto no-scrollbar">
                    <button type="button" onclick="setTripType('all', this)"
                        class="trip-filter-btn px-4 py-1.5 rounded-full text-xs font-semibold bg-white text-ink-heading shadow-sm transition whitespace-nowrap">
                        Semua Trip
                    </button>
                    <button type="button" onclick="setTripType('open', this)"
                        class="trip-filter-btn px-4 py-1.5 rounded-full text-xs font-medium text-muted hover:text-ink-heading transition whitespace-nowrap">
                        Open Trip Only
                    </button>
                    <button type="button" onclick="setTripType('private', this)"
                        class="trip-filter-btn px-4 py-1.5 rounded-full text-xs font-medium text-muted hover:text-ink-heading transition whitespace-nowrap">
                        Private Trip Only
                    </button>
                </div>

                <!-- Right Action Filters: Grade Select -->
                <div class="flex items-center gap-2 self-end sm:self-auto">

                    <!-- Grade Dropdown Menu -->
                    <div class="relative">
                        <button id="grade-dropdown-btn" type="button" onclick="toggleGradeMenu()"
                            class="bg-gray-200/60 hover:bg-gray-200 text-body-strong text-xs font-semibold px-4 py-2 rounded-full flex items-center gap-2 transition cursor-pointer">
                            <span id="grade-selected-label">Grade</span>
                            <svg id="grade-chevron"
                                class="w-3.5 h-3.5 text-muted transition-transform duration-200 shrink-0" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div id="grade-menu"
                            class="hidden absolute right-0 mt-2 w-52 bg-white border border-hairline rounded-2xl shadow-xl py-2 z-30 text-xs">
                            <div class="px-3 py-1.5 text-[10px] uppercase font-bold text-muted-soft tracking-wider">
                                Pilih Grade
                            </div>
                            <button type="button" onclick="filterByGrade('all')"
                                class="w-full text-left px-4 py-2 hover:bg-gray-50 font-medium text-body-strong flex items-center justify-between cursor-pointer">
                                <span>Semua Grade</span>
                            </button>
                            <button type="button" onclick="filterByGrade('Grade A')"
                                class="w-full text-left px-4 py-2 hover:bg-gray-50 font-medium text-grade-a-text flex items-center justify-between cursor-pointer">
                                <span>Grade A (Pemula)</span>
                                <span class="w-2 h-2 rounded-full bg-grade-a-dot"></span>
                            </button>
                            <button type="button" onclick="filterByGrade('Grade B')"
                                class="w-full text-left px-4 py-2 hover:bg-gray-50 font-medium text-grade-b-text flex items-center justify-between cursor-pointer">
                                <span>Grade B (Menengah)</span>
                                <span class="w-2 h-2 rounded-full bg-grade-b-dot"></span>
                            </button>
                            <button type="button" onclick="filterByGrade('Grade C')"
                                class="w-full text-left px-4 py-2 hover:bg-gray-50 font-medium text-grade-c-text flex items-center justify-between cursor-pointer">
                                <span>Grade C (Ahli)</span>
                                <span class="w-2 h-2 rounded-full bg-grade-c-dot"></span>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        {{-- =====================================================================
             3. MOUNTAIN CARDS GRID SECTION
        ===================================================================== --}}
        <main class="max-w-6xl mx-auto px-4 md:px-8 w-full mb-20">
            <!-- Active Search Filter Badge -->
            <div id="active-search-badge" class="hidden mb-6 items-center">
                <button type="button" onclick="clearSearchFilter()" title="Klik untuk menghapus filter"
                    class="inline-flex items-center gap-2 bg-primary-subtle text-primary hover:bg-primary hover:text-white text-xs font-semibold px-3.5 py-1.5 rounded-full border border-primary/20 shadow-xs transition-all duration-200 cursor-pointer group">
                    <span id="active-search-text"></span>
                    <span
                        class="w-4 h-4 rounded-full bg-primary/10 group-hover:bg-white/20 flex items-center justify-center text-[10px] font-bold transition">✕</span>
                </button>
            </div>

            {{-- Trips Grid --}}
            <div id="trips-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($mountains as $mountain)
                    <x-mountain-card :mountain="$mountain" />
                @endforeach
            </div>

            <!-- Empty State -->
            <div id="no-results" class="hidden text-center py-16 bg-surface-card rounded-2xl border border-hairline">
                <svg class="w-12 h-12 text-muted-soft mx-auto mb-3" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-ink-heading font-semibold text-sm">Tidak ada ekspedisi yang cocok</p>
                <p class="text-muted text-xs mt-1">Coba ubah kriteria filter atau pilih semua grade.</p>
                <button type="button" onclick="resetFilters()"
                    class="mt-4 text-xs font-semibold text-primary hover:underline cursor-pointer">
                    Reset Semua Filter
                </button>
            </div>
        </main>
    </div>

    {{-- =====================================================================
         4. CLIENT-SIDE FILTER SCRIPTS
    ===================================================================== --}}
    @push('scripts')
        <script>
            let activeTypeFilter = 'all';
            let activeGradeFilter = 'all';
            let searchMountainQuery = '';

            function setTripType(type, element) {
                activeTypeFilter = type;

                document.querySelectorAll('.trip-filter-btn').forEach(btn => {
                    btn.className =
                        'trip-filter-btn px-4 py-1.5 rounded-full text-xs font-medium text-muted hover:text-ink-heading transition whitespace-nowrap';
                });
                element.className =
                    'trip-filter-btn px-4 py-1.5 rounded-full text-xs font-semibold bg-white text-ink-heading shadow-sm transition whitespace-nowrap';

                applyFilters();
            }

            function toggleGradeMenu() {
                const menu = document.getElementById('grade-menu');
                const chevron = document.getElementById('grade-chevron');
                if (menu.classList.contains('hidden')) {
                    menu.classList.remove('hidden');
                    chevron?.classList.add('rotate-180');
                } else {
                    menu.classList.add('hidden');
                    chevron?.classList.remove('rotate-180');
                }
            }

            function filterByGrade(grade) {
                activeGradeFilter = grade;
                const label = document.getElementById('grade-selected-label');
                label.textContent = grade === 'all' ? 'Grade' : grade;
                document.getElementById('grade-menu').classList.add('hidden');
                document.getElementById('grade-chevron')?.classList.remove('rotate-180');
                applyFilters();
            }

            function applyFilters() {
                const cards = document.querySelectorAll('.trip-card');
                let visibleCount = 0;

                cards.forEach(card => {
                    const cardGrade = card.getAttribute('data-grade');
                    const cardType = card.getAttribute('data-type');
                    const cardMountain = (card.getAttribute('data-mountain') || '').toLowerCase();
                    const cardTitle = (card.getAttribute('data-title') || '').toLowerCase();

                    const matchGrade = (activeGradeFilter === 'all') || (cardGrade === activeGradeFilter);
                    const matchType = (activeTypeFilter === 'all') || (cardType === activeTypeFilter) || (cardType ===
                        'both');
                    const matchMountain = !searchMountainQuery ||
                        cardMountain.includes(searchMountainQuery) ||
                        cardTitle.includes(searchMountainQuery);

                    if (matchGrade && matchType && matchMountain) {
                        card.classList.remove('hidden');
                        visibleCount++;
                    } else {
                        card.classList.add('hidden');
                    }
                });

                const noResults = document.getElementById('no-results');
                if (visibleCount === 0) {
                    noResults.classList.remove('hidden');
                } else {
                    noResults.classList.add('hidden');
                }
            }

            function clearSearchFilter() {
                searchMountainQuery = '';
                const badge = document.getElementById('active-search-badge');
                if (badge) {
                    badge.classList.add('hidden');
                    badge.classList.remove('flex');
                }

                if (window.history.replaceState) {
                    const url = new URL(window.location);
                    url.searchParams.delete('gunung');
                    url.searchParams.delete('q');
                    url.searchParams.delete('search');
                    window.history.replaceState({}, '', url);
                }

                applyFilters();
            }

            function resetFilters() {
                activeTypeFilter = 'all';
                activeGradeFilter = 'all';
                searchMountainQuery = '';

                const badge = document.getElementById('active-search-badge');
                if (badge) {
                    badge.classList.add('hidden');
                    badge.classList.remove('flex');
                }

                if (window.history.replaceState) {
                    const cleanUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
                    window.history.replaceState({
                        path: cleanUrl
                    }, '', cleanUrl);
                }

                const buttons = document.querySelectorAll('.trip-filter-btn');
                buttons.forEach((btn, idx) => {
                    if (idx === 0) {
                        btn.className =
                            'trip-filter-btn px-4 py-1.5 rounded-full text-xs font-semibold bg-white text-ink-heading shadow-sm transition whitespace-nowrap';
                    } else {
                        btn.className =
                            'trip-filter-btn px-4 py-1.5 rounded-full text-xs font-medium text-muted hover:text-ink-heading transition whitespace-nowrap';
                    }
                });

                document.getElementById('grade-selected-label').textContent = 'Grade';
                document.getElementById('grade-menu').classList.add('hidden');
                document.getElementById('grade-chevron')?.classList.remove('rotate-180');
                applyFilters();
            }

            document.addEventListener('click', (e) => {
                const dropdown = document.getElementById('grade-dropdown-btn');
                const menu = document.getElementById('grade-menu');
                if (dropdown && menu && !dropdown.contains(e.target) && !menu.contains(e.target)) {
                    menu.classList.add('hidden');
                    document.getElementById('grade-chevron')?.classList.remove('rotate-180');
                }
            });

            // Parse initial URL query params
            (function() {
                const urlParams = new URLSearchParams(window.location.search);
                const queryGunung = urlParams.get('q') || urlParams.get('gunung') || urlParams.get('search');
                const queryGrade = urlParams.get('grade');

                if (queryGunung) {
                    searchMountainQuery = queryGunung.toLowerCase().replace(/^(mt\.?|gunung)\s*/i, '').trim();
                    const badge = document.getElementById('active-search-badge');
                    const badgeText = document.getElementById('active-search-text');
                    if (badge && badgeText) {
                        badgeText.textContent = `Pencarian: "${queryGunung}"`;
                        badge.classList.remove('hidden');
                        badge.classList.add('flex');
                    }
                }

                if (queryGrade && ['Grade A', 'Grade B', 'Grade C'].includes(queryGrade)) {
                    activeGradeFilter = queryGrade;
                    const label = document.getElementById('grade-selected-label');
                    if (label) label.textContent = queryGrade;
                }

                if (queryGunung || queryGrade) {
                    applyFilters();
                }
            })();
        </script>
    @endpush

</x-public-layout>
