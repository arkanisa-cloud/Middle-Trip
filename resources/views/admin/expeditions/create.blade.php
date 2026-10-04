@extends('layouts.admin')

@section('title', 'Buka Batch Ekspedisi Baru')
@section('header_title', 'Buka Batch Jadwal Ekspedisi Baru')
@section('header_subtitle', 'Tentukan destinasi, jalur, tanggal keberangkatan, dan kapasitas kuota peserta')

@section('content')
<div x-data="{
    mountains: @js($mountains),
    selectedMountainId: '{{ old('mountain_id', $selectedMountainId ?? ($mountains->first()->id ?? '')) }}',
    selectedMountainName: '{{ old('mountain_name', $mountains->first()->name ?? 'Pilih Gunung') }}',
    mountainDropdownOpen: false,
    availableRoutes: [],
    selectedRouteId: '{{ old('route_id', '') }}',
    selectedRouteName: 'Pilih Jalur Pendakian',
    routeDropdownOpen: false,
    hikingType: '{{ old('hiking_type', 'camping') }}',
    hikingTypeLabel: '{{ old('hiking_type', 'camping') === 'camping' ? 'Camping (Bermalam di Tenda)' : 'Tek-tok (1 Hari Langsung Turun)' }}',
    hikingDropdownOpen: false,
    status: '{{ old('status', 'open') }}',
    statusLabel: 'Pendaftaran Dibuka (Open)',
    statusDot: 'bg-emerald-500',
    statusDropdownOpen: false,

    updateRoutes() {
        let m = this.mountains.find(item => item.id == this.selectedMountainId);
        this.availableRoutes = m ? m.routes : [];
        if (this.availableRoutes.length > 0) {
            let found = this.availableRoutes.find(r => r.id == this.selectedRouteId);
            if (!found) {
                this.selectedRouteId = this.availableRoutes[0].id;
                this.selectedRouteName = `${this.availableRoutes[0].name} (${this.availableRoutes[0].grade})`;
            } else {
                this.selectedRouteName = `${found.name} (${found.grade})`;
            }
        } else {
            this.selectedRouteId = '';
            this.selectedRouteName = 'Tidak ada jalur terdaftar';
        }
    },
    init() {
        let m = this.mountains.find(item => item.id == this.selectedMountainId);
        if (m) this.selectedMountainName = `${m.name} (${m.province})`;
        this.updateRoutes();
    }
}" class="max-w-3xl mx-auto space-y-6">

    <!-- Page Header Banner -->
    <x-admin.page-header 
        title="Buka Batch Ekspedisi Baru" 
        subtitle="Tentukan destinasi gunung, jalur pendakian, tanggal keberangkatan, dan kapasitas kuota peserta."
        :backUrl="route('admin.expeditions.index')"
        backLabel="Kembali ke Daftar Batch"
    />

    <form method="POST" action="{{ route('admin.expeditions.store') }}" class="space-y-6">
        @csrf

        <div class="bg-surface-card border border-hairline rounded-3xl p-6 md:p-8 shadow-xs space-y-6">
            <div class="border-b border-hairline pb-4">
                <h3 class="text-base font-extrabold font-outfit text-ink-heading">Konfigurasi Jadwal Ekspedisi</h3>
                <p class="text-xs text-muted">Pastikan rute dan kuota kursi sesuai dengan izin kuota SIMAKSI resmi</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- 1. Mountain Selection (UI Kit Dropdown Varian 1 / Searchable Combobox Style) -->
                <div @click.outside="mountainDropdownOpen = false" class="relative">
                    <label class="block text-xs font-bold text-ink-heading uppercase tracking-wider mb-2">
                        Pilih Destinasi Gunung <span class="text-rose-500">*</span>
                    </label>
                    <input type="hidden" name="mountain_id" :value="selectedMountainId" required>

                    <button type="button" @click="mountainDropdownOpen = !mountainDropdownOpen; routeDropdownOpen = false;"
                            class="w-full text-xs rounded-xl border border-hairline bg-canvas p-3 text-ink flex items-center justify-between cursor-pointer focus:border-primary shadow-xs font-semibold transition select-none">
                        <span x-text="selectedMountainName" class="text-ink-heading block truncate"></span>
                        <svg class="w-4 h-4 text-muted transition-transform duration-200 shrink-0" 
                             :class="mountainDropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Elevated Menu -->
                    <div x-show="mountainDropdownOpen" x-cloak
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         class="absolute left-0 right-0 mt-2 bg-white border border-hairline rounded-2xl shadow-xl py-2 z-50 max-h-56 overflow-y-auto text-xs">
                        <div class="px-3 py-1.5 text-[10px] uppercase font-bold text-muted-soft tracking-wider border-b border-hairline/60 mb-1">
                            Pilih Gunung
                        </div>
                        <template x-for="m in mountains" :key="m.id">
                            <button type="button" 
                                    @click="selectedMountainId = m.id; selectedMountainName = `${m.name} (${m.province})`; mountainDropdownOpen = false; updateRoutes();"
                                    class="w-full text-left px-4 py-2 hover:bg-gray-50 text-body-strong font-medium flex items-center justify-between cursor-pointer"
                                    :class="selectedMountainId == m.id ? 'bg-primary-subtle/50 text-primary font-bold' : ''">
                                <span x-text="m.name"></span>
                                <span class="text-[10px] text-muted font-normal" x-text="m.province"></span>
                            </button>
                        </template>
                    </div>
                </div>

                <!-- 2. Route Selection (UI Kit Dropdown Varian 2 / Dynamic Single Select) -->
                <div @click.outside="routeDropdownOpen = false" class="relative">
                    <label class="block text-xs font-bold text-ink-heading uppercase tracking-wider mb-2">
                        Pilih Jalur Pendakian <span class="text-rose-500">*</span>
                    </label>
                    <input type="hidden" name="route_id" :value="selectedRouteId" required>

                    <button type="button" @click="routeDropdownOpen = !routeDropdownOpen; mountainDropdownOpen = false;"
                            class="w-full text-xs rounded-xl border border-hairline bg-canvas p-3 text-ink flex items-center justify-between cursor-pointer focus:border-primary shadow-xs font-semibold transition select-none">
                        <span x-text="selectedRouteName" class="text-ink-heading block truncate"></span>
                        <svg class="w-4 h-4 text-muted transition-transform duration-200 shrink-0" 
                             :class="routeDropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Elevated Menu -->
                    <div x-show="routeDropdownOpen" x-cloak
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         class="absolute left-0 right-0 mt-2 bg-white border border-hairline rounded-2xl shadow-xl py-2 z-50 max-h-56 overflow-y-auto text-xs">
                        <div class="px-3 py-1.5 text-[10px] uppercase font-bold text-muted-soft tracking-wider border-b border-hairline/60 mb-1">
                            Pilih Jalur
                        </div>
                        <template x-if="availableRoutes.length === 0">
                            <div class="px-4 py-3 text-muted text-xs italic">
                                Tidak ada jalur pendakian tersedia untuk gunung ini
                            </div>
                        </template>
                        <template x-for="r in availableRoutes" :key="r.id">
                            <button type="button" 
                                    @click="selectedRouteId = r.id; selectedRouteName = `${r.name} (${r.grade})`; routeDropdownOpen = false;"
                                    class="w-full text-left px-4 py-2 hover:bg-gray-50 text-body-strong font-medium flex items-center justify-between cursor-pointer"
                                    :class="selectedRouteId == r.id ? 'bg-primary-subtle/50 text-primary font-bold' : ''">
                                <span x-text="r.name"></span>
                                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-canvas border border-hairline" x-text="r.grade"></span>
                            </button>
                        </template>
                    </div>
                </div>

                <!-- 3. Trip Type (Fixed to Open Trip Badge) -->
                <div>
                    <label class="block text-xs font-bold text-ink-heading uppercase tracking-wider mb-2">
                        Tipe Ekspedisi
                    </label>
                    <div class="flex items-center justify-between p-3 rounded-xl border border-hairline bg-canvas/60 text-xs">
                        <span class="font-bold text-ink-heading flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            Open Trip (Batch Gabungan)
                        </span>
                        <span class="text-[10px] text-muted font-medium bg-white px-2 py-0.5 rounded-full border border-hairline">Slot & Kuota</span>
                    </div>
                    <input type="hidden" name="type" value="open">
                </div>

                <!-- 4. Hiking Type (UI Kit Dropdown) -->
                <div @click.outside="hikingDropdownOpen = false" class="relative">
                    <label class="block text-xs font-bold text-ink-heading uppercase tracking-wider mb-2">
                        Jenis Pendakian <span class="text-rose-500">*</span>
                    </label>
                    <input type="hidden" name="hiking_type" :value="hikingType" required>

                    <button type="button" @click="hikingDropdownOpen = !hikingDropdownOpen"
                            class="w-full text-xs rounded-xl border border-hairline bg-canvas p-3 text-ink flex items-center justify-between cursor-pointer focus:border-primary shadow-xs font-semibold transition select-none">
                        <span x-text="hikingTypeLabel" class="text-ink-heading block truncate"></span>
                        <svg class="w-4 h-4 text-muted transition-transform duration-200 shrink-0" 
                             :class="hikingDropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="hikingDropdownOpen" x-cloak
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         class="absolute left-0 right-0 mt-2 bg-white border border-hairline rounded-2xl shadow-xl py-2 z-50 text-xs">
                        <div class="px-3 py-1.5 text-[10px] uppercase font-bold text-muted-soft tracking-wider border-b border-hairline/60 mb-1">
                            Pilih Paket Pendakian
                        </div>
                        <button type="button" 
                                @click="hikingType = 'camping'; hikingTypeLabel = 'Camping (Bermalam di Tenda)'; hikingDropdownOpen = false;"
                                class="w-full text-left px-4 py-2 hover:bg-gray-50 text-body-strong font-medium flex items-center justify-between cursor-pointer"
                                :class="hikingType === 'camping' ? 'bg-primary-subtle/50 font-bold text-primary' : ''">
                            <span>Camping (Bermalam di Tenda)</span>
                            <span class="text-[10px] text-muted font-normal">2D1N / 3D2N</span>
                        </button>
                        <button type="button" 
                                @click="hikingType = 'tektok'; hikingTypeLabel = 'Tek-tok (1 Hari Langsung Turun)'; hikingDropdownOpen = false;"
                                class="w-full text-left px-4 py-2 hover:bg-gray-50 text-body-strong font-medium flex items-center justify-between cursor-pointer"
                                :class="hikingType === 'tektok' ? 'bg-primary-subtle/50 font-bold text-primary' : ''">
                            <span>Tek-tok (1 Hari Langsung Turun)</span>
                            <span class="text-[10px] text-muted font-normal">1 Day</span>
                        </button>
                    </div>
                </div>

                <!-- 5. Departure Date (UI Kit Dropdown Calendar) -->
                @php
                    $defaultDepDate = old('departure_date', now()->addDays(7)->toDateString());
                    $depCarbon = \Carbon\Carbon::parse($defaultDepDate);
                @endphp
                <div x-data="{
                    open: false,
                    dateVal: '{{ $defaultDepDate }}',
                    viewYear: {{ $depCarbon->year }},
                    viewMonth: {{ $depCarbon->month - 1 }},
                    monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                    dayNames: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                    get formattedLabel() {
                        if (!this.dateVal) return 'Pilih Tanggal';
                        const parts = this.dateVal.split('-');
                        if (parts.length !== 3) return this.dateVal;
                        const d = parseInt(parts[2], 10);
                        const m = parseInt(parts[1], 10) - 1;
                        const y = parseInt(parts[0], 10);
                        return `${d} ${this.monthNames[m]} ${y}`;
                    },
                    get daysInMonth() {
                        const days = [];
                        const firstDayIndex = new Date(this.viewYear, this.viewMonth, 1).getDay();
                        const numDays = new Date(this.viewYear, this.viewMonth + 1, 0).getDate();
                        for (let i = 0; i < firstDayIndex; i++) {
                            days.push({ day: '', fullDate: '', empty: true });
                        }
                        for (let d = 1; d <= numDays; d++) {
                            const mStr = String(this.viewMonth + 1).padStart(2, '0');
                            const dStr = String(d).padStart(2, '0');
                            const fullDate = `${this.viewYear}-${mStr}-${dStr}`;
                            days.push({ day: d, fullDate: fullDate, empty: false });
                        }
                        return days;
                    },
                    prevMonth() {
                        if (this.viewMonth === 0) {
                            this.viewMonth = 11;
                            this.viewYear--;
                        } else {
                            this.viewMonth--;
                        }
                    },
                    nextMonth() {
                        if (this.viewMonth === 11) {
                            this.viewMonth = 0;
                            this.viewYear++;
                        } else {
                            this.viewMonth++;
                        }
                    },
                    selectDate(fullDate) {
                        this.dateVal = fullDate;
                        this.open = false;
                    }
                }" @click.outside="open = false" class="relative">
                    <label class="block text-xs font-bold text-ink-heading uppercase tracking-wider mb-2">
                        Tanggal Berangkat <span class="text-rose-500">*</span>
                    </label>
                    <input type="hidden" name="departure_date" :value="dateVal" required>

                    <button type="button" @click="open = !open"
                        class="w-full text-xs rounded-xl border border-hairline bg-canvas p-3 text-ink flex items-center justify-between cursor-pointer focus:border-primary shadow-xs font-semibold transition select-none">
                        <span class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-muted shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span x-text="formattedLabel" class="text-ink-heading font-bold"></span>
                        </span>
                        <svg class="w-4 h-4 text-muted transition-transform duration-200 shrink-0"
                            :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Calendar Dropdown Popover -->
                    <div x-show="open" x-cloak
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        class="absolute left-0 mt-2 bg-white border border-hairline rounded-2xl shadow-xl p-4 z-50 w-72 select-none text-xs">
                        
                        <div class="flex items-center justify-between mb-3 pb-2 border-b border-hairline">
                            <button type="button" @click="prevMonth"
                                class="p-1.5 rounded-lg hover:bg-gray-100 text-muted hover:text-ink transition cursor-pointer">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>
                            <span class="font-bold text-ink-heading text-xs" x-text="`${monthNames[viewMonth]} ${viewYear}`"></span>
                            <button type="button" @click="nextMonth"
                                class="p-1.5 rounded-lg hover:bg-gray-100 text-muted hover:text-ink transition cursor-pointer">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>

                        <div class="grid grid-cols-7 gap-1 text-center mb-1">
                            <template x-for="dayName in dayNames" :key="dayName">
                                <span class="text-[10px] font-bold text-muted-soft uppercase py-1" x-text="dayName"></span>
                            </template>
                        </div>

                        <div class="grid grid-cols-7 gap-1 text-center">
                            <template x-for="(cell, i) in daysInMonth" :key="i">
                                <div>
                                    <template x-if="cell.empty">
                                        <div class="w-8 h-8"></div>
                                    </template>
                                    <template x-if="!cell.empty">
                                        <button type="button" @click="selectDate(cell.fullDate)"
                                            class="w-8 h-8 rounded-xl flex items-center justify-center text-xs font-semibold transition cursor-pointer"
                                            :class="dateVal === cell.fullDate ? 'bg-primary text-white font-bold shadow-xs' : 'text-ink hover:bg-gray-100'">
                                            <span x-text="cell.day"></span>
                                        </button>
                                    </template>
                                </div>
                            </template>
                        </div>

                        <div class="mt-3 pt-2 border-t border-hairline flex items-center justify-between text-[11px]">
                            <button type="button" 
                                @click="dateVal = new Date().toISOString().split('T')[0]; viewYear = new Date().getFullYear(); viewMonth = new Date().getMonth(); open = false;"
                                class="text-primary font-bold hover:underline cursor-pointer">
                                Hari Ini
                            </button>
                            <button type="button" @click="open = false" class="text-muted hover:text-ink cursor-pointer">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>

                <!-- 6. Return Date (UI Kit Dropdown Calendar) -->
                @php
                    $defaultRetDate = old('return_date', now()->addDays(9)->toDateString());
                    $retCarbon = \Carbon\Carbon::parse($defaultRetDate);
                @endphp
                <div x-data="{
                    open: false,
                    dateVal: '{{ $defaultRetDate }}',
                    viewYear: {{ $retCarbon->year }},
                    viewMonth: {{ $retCarbon->month - 1 }},
                    monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                    dayNames: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                    get formattedLabel() {
                        if (!this.dateVal) return 'Pilih Tanggal';
                        const parts = this.dateVal.split('-');
                        if (parts.length !== 3) return this.dateVal;
                        const d = parseInt(parts[2], 10);
                        const m = parseInt(parts[1], 10) - 1;
                        const y = parseInt(parts[0], 10);
                        return `${d} ${this.monthNames[m]} ${y}`;
                    },
                    get daysInMonth() {
                        const days = [];
                        const firstDayIndex = new Date(this.viewYear, this.viewMonth, 1).getDay();
                        const numDays = new Date(this.viewYear, this.viewMonth + 1, 0).getDate();
                        for (let i = 0; i < firstDayIndex; i++) {
                            days.push({ day: '', fullDate: '', empty: true });
                        }
                        for (let d = 1; d <= numDays; d++) {
                            const mStr = String(this.viewMonth + 1).padStart(2, '0');
                            const dStr = String(d).padStart(2, '0');
                            const fullDate = `${this.viewYear}-${mStr}-${dStr}`;
                            days.push({ day: d, fullDate: fullDate, empty: false });
                        }
                        return days;
                    },
                    prevMonth() {
                        if (this.viewMonth === 0) {
                            this.viewMonth = 11;
                            this.viewYear--;
                        } else {
                            this.viewMonth--;
                        }
                    },
                    nextMonth() {
                        if (this.viewMonth === 11) {
                            this.viewMonth = 0;
                            this.viewYear++;
                        } else {
                            this.viewMonth++;
                        }
                    },
                    selectDate(fullDate) {
                        this.dateVal = fullDate;
                        this.open = false;
                    }
                }" @click.outside="open = false" class="relative">
                    <label class="block text-xs font-bold text-ink-heading uppercase tracking-wider mb-2">
                        Tanggal Kembali / Selesai <span class="text-rose-500">*</span>
                    </label>
                    <input type="hidden" name="return_date" :value="dateVal" required>

                    <button type="button" @click="open = !open"
                        class="w-full text-xs rounded-xl border border-hairline bg-canvas p-3 text-ink flex items-center justify-between cursor-pointer focus:border-primary shadow-xs font-semibold transition select-none">
                        <span class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-muted shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span x-text="formattedLabel" class="text-ink-heading font-bold"></span>
                        </span>
                        <svg class="w-4 h-4 text-muted transition-transform duration-200 shrink-0"
                            :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Calendar Dropdown Popover -->
                    <div x-show="open" x-cloak
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        class="absolute left-0 mt-2 bg-white border border-hairline rounded-2xl shadow-xl p-4 z-50 w-72 select-none text-xs">
                        
                        <div class="flex items-center justify-between mb-3 pb-2 border-b border-hairline">
                            <button type="button" @click="prevMonth"
                                class="p-1.5 rounded-lg hover:bg-gray-100 text-muted hover:text-ink transition cursor-pointer">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>
                            <span class="font-bold text-ink-heading text-xs" x-text="`${monthNames[viewMonth]} ${viewYear}`"></span>
                            <button type="button" @click="nextMonth"
                                class="p-1.5 rounded-lg hover:bg-gray-100 text-muted hover:text-ink transition cursor-pointer">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>

                        <div class="grid grid-cols-7 gap-1 text-center mb-1">
                            <template x-for="dayName in dayNames" :key="dayName">
                                <span class="text-[10px] font-bold text-muted-soft uppercase py-1" x-text="dayName"></span>
                            </template>
                        </div>

                        <div class="grid grid-cols-7 gap-1 text-center">
                            <template x-for="(cell, i) in daysInMonth" :key="i">
                                <div>
                                    <template x-if="cell.empty">
                                        <div class="w-8 h-8"></div>
                                    </template>
                                    <template x-if="!cell.empty">
                                        <button type="button" @click="selectDate(cell.fullDate)"
                                            class="w-8 h-8 rounded-xl flex items-center justify-center text-xs font-semibold transition cursor-pointer"
                                            :class="dateVal === cell.fullDate ? 'bg-primary text-white font-bold shadow-xs' : 'text-ink hover:bg-gray-100'">
                                            <span x-text="cell.day"></span>
                                        </button>
                                    </template>
                                </div>
                            </template>
                        </div>

                        <div class="mt-3 pt-2 border-t border-hairline flex items-center justify-between text-[11px]">
                            <button type="button" 
                                @click="dateVal = new Date().toISOString().split('T')[0]; viewYear = new Date().getFullYear(); viewMonth = new Date().getMonth(); open = false;"
                                class="text-primary font-bold hover:underline cursor-pointer">
                                Hari Ini
                            </button>
                            <button type="button" @click="open = false" class="text-muted hover:text-ink cursor-pointer">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>

                <!-- 7. Quota Max -->
                <div>
                    <label class="block text-xs font-bold text-ink-heading uppercase tracking-wider mb-2">
                        Kapasitas Kuota Kursi <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="quota_max" value="{{ old('quota_max', 10) }}" min="1" max="100" required 
                           class="w-full text-xs rounded-xl border border-hairline bg-canvas p-3 text-ink focus:border-primary shadow-xs font-bold">
                </div>

                <!-- 8. Batas Price Lock (H-X Hari) -->
                <div>
                    <label class="block text-xs font-bold text-ink-heading uppercase tracking-wider mb-2">
                        Batas Price Lock (H-X Hari) <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="price_lock_days_before_departure" value="{{ old('price_lock_days_before_departure', 3) }}" min="1" max="30" required 
                           placeholder="3"
                           class="w-full text-xs rounded-xl border border-hairline bg-canvas p-3 text-ink focus:border-primary shadow-xs font-bold">
                    <span class="text-[10.5px] text-muted mt-1 block">Harga batch akan dikunci otomatis pada H-X sebelum berangkat</span>
                </div>

                <!-- 8. Status Initial (UI Kit Dropdown Varian 3 / Status Dot) -->
                <div @click.outside="statusDropdownOpen = false" class="relative">
                    <label class="block text-xs font-bold text-ink-heading uppercase tracking-wider mb-2">
                        Status Awal Batch <span class="text-rose-500">*</span>
                    </label>
                    <input type="hidden" name="status" :value="status" required>

                    <button type="button" @click="statusDropdownOpen = !statusDropdownOpen"
                            class="w-full text-xs rounded-xl border border-hairline bg-canvas p-3 text-ink flex items-center justify-between cursor-pointer focus:border-primary shadow-xs font-semibold transition select-none">
                        <span class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full" :class="statusDot"></span>
                            <span x-text="statusLabel" class="text-ink-heading"></span>
                        </span>
                        <svg class="w-4 h-4 text-muted transition-transform duration-200 shrink-0" 
                             :class="statusDropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="statusDropdownOpen" x-cloak
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         class="absolute left-0 right-0 mt-2 bg-white border border-hairline rounded-2xl shadow-xl py-2 z-50 text-xs">
                        <div class="px-3 py-1.5 text-[10px] uppercase font-bold text-muted-soft tracking-wider border-b border-hairline/60 mb-1">
                            Pilih Status
                        </div>
                        <button type="button" 
                                @click="status = 'open'; statusLabel = 'Pendaftaran Dibuka (Open)'; statusDot = 'bg-emerald-500'; statusDropdownOpen = false;"
                                class="w-full text-left px-4 py-2 hover:bg-gray-50 text-body-strong font-medium flex items-center justify-between cursor-pointer"
                                :class="status === 'open' ? 'bg-primary-subtle/50 font-bold' : ''">
                            <span class="text-emerald-700">Pendaftaran Dibuka (Open)</span>
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        </button>
                        <button type="button" 
                                @click="status = 'price_locked'; statusLabel = 'Terkunci (Price Locked)'; statusDot = 'bg-indigo-600'; statusDropdownOpen = false;"
                                class="w-full text-left px-4 py-2 hover:bg-gray-50 text-body-strong font-medium flex items-center justify-between cursor-pointer"
                                :class="status === 'price_locked' ? 'bg-primary-subtle/50 font-bold' : ''">
                            <span class="text-indigo-700">Terkunci (Price Locked)</span>
                            <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                        </button>
                        <button type="button" 
                                @click="status = 'completed'; statusLabel = 'Selesai (Completed)'; statusDot = 'bg-gray-500'; statusDropdownOpen = false;"
                                class="w-full text-left px-4 py-2 hover:bg-gray-50 text-body-strong font-medium flex items-center justify-between cursor-pointer"
                                :class="status === 'completed' ? 'bg-primary-subtle/50 font-bold' : ''">
                            <span class="text-gray-700">Selesai (Completed)</span>
                            <span class="w-2 h-2 rounded-full bg-gray-500"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
            </div>
        </div>

        <!-- Submit Button Bar -->
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.expeditions.index') }}" 
               class="px-6 py-2.5 rounded-full border border-hairline text-xs font-semibold text-ink hover:bg-canvas transition-colors">
                Batal
            </a>
            <button type="submit" 
                    class="px-8 py-2.5 rounded-full bg-primary text-white text-xs font-bold hover:bg-primary-hover transition-colors shadow-sm">
                Terbitkan Batch Ekspedisi
            </button>
        </div>
    </form>

</div>
@endsection
