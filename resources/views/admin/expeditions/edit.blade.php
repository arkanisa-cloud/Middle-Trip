@extends('layouts.admin')

@section('title', 'Edit Batch Ekspedisi')
@section('header_title', 'Perbarui Batch Ekspedisi')
@section('header_subtitle', 'Ubah kuota, penyesuaian tanggal, atau perbarui status ekspedisi')

@section('content')
<div x-data="{
    mountains: @js($mountains),
    selectedMountainId: '{{ old('mountain_id', $expedition->mountain_id) }}',
    selectedMountainName: '{{ $expedition->mountain->name ?? 'Pilih Gunung' }} ({{ $expedition->mountain->province ?? '' }})',
    mountainDropdownOpen: false,
    availableRoutes: [],
    selectedRouteId: '{{ old('route_id', $expedition->route_id) }}',
    selectedRouteName: '{{ $expedition->route->name ?? 'Pilih Jalur' }} ({{ $expedition->route->grade ?? '' }})',
    routeDropdownOpen: false,
    hikingType: '{{ old('hiking_type', $expedition->hiking_type) }}',
    hikingTypeLabel: '{{ old('hiking_type', $expedition->hiking_type) === 'camping' ? 'Camping (Bermalam di Tenda)' : 'Tek-tok (1 Hari Langsung Turun)' }}',
    hikingDropdownOpen: false,
    status: '{{ old('status', $expedition->status) }}',
    statusLabel: '{{ $expedition->status === 'open' ? 'Pendaftaran Buka (Open)' : ($expedition->status === 'price_locked' ? 'Terkunci (Price Locked)' : ($expedition->status === 'completed' ? 'Selesai (Completed)' : 'Dibatalkan (Cancelled)')) }}',
    statusDot: '{{ $expedition->status === 'open' ? 'bg-emerald-500' : ($expedition->status === 'price_locked' ? 'bg-indigo-600' : ($expedition->status === 'completed' ? 'bg-gray-500' : 'bg-rose-500')) }}',
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
        :title="'Edit Batch #' . $expedition->id . ': ' . ($expedition->mountain->name ?? 'Ekspedisi')" 
        subtitle="Ubah kuota peserta, tanggal keberangkatan, rute, atau perbarui siklus status ekspedisi."
        :backUrl="route('admin.expeditions.index')"
        backLabel="Kembali ke Daftar Batch"
    />

    <form method="POST" action="{{ route('admin.expeditions.update', $expedition->id) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-surface-card border border-hairline rounded-3xl p-6 md:p-8 shadow-xs space-y-6">
            <div class="border-b border-hairline pb-4 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-extrabold font-outfit text-ink-heading">Konfigurasi Batch #{{ $expedition->id }}</h3>
                    <p class="text-xs text-muted">Akumulasi kuota terisi saat ini: {{ $expedition->quota_booked }} peserta</p>
                </div>
                <x-admin.status-badge :status="$expedition->status" />
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

                <!-- 5. Departure Date (UI Kit Styled Date Input) -->
                <div>
                    <label class="block text-xs font-bold text-ink-heading uppercase tracking-wider mb-2">
                        Tanggal Berangkat <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="date" name="departure_date" 
                               value="{{ old('departure_date', \Carbon\Carbon::parse($expedition->departure_date)->toDateString()) }}" required 
                               class="w-full text-xs rounded-xl border border-hairline bg-canvas p-3 text-ink focus:border-primary shadow-xs font-semibold">
                    </div>
                </div>

                <!-- 6. Return Date (UI Kit Styled Date Input) -->
                <div>
                    <label class="block text-xs font-bold text-ink-heading uppercase tracking-wider mb-2">
                        Tanggal Selesai <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="date" name="return_date" 
                               value="{{ old('return_date', \Carbon\Carbon::parse($expedition->return_date)->toDateString()) }}" required 
                               class="w-full text-xs rounded-xl border border-hairline bg-canvas p-3 text-ink focus:border-primary shadow-xs font-semibold">
                    </div>
                </div>

                <!-- 7. Quota Max -->
                <div>
                    <label class="block text-xs font-bold text-ink-heading uppercase tracking-wider mb-2">
                        Kapasitas Kuota Kursi <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="quota_max" value="{{ old('quota_max', $expedition->quota_max) }}" min="{{ $expedition->quota_booked }}" required 
                           class="w-full text-xs rounded-xl border border-hairline bg-canvas p-3 text-ink focus:border-primary shadow-xs font-bold">
                    <p class="text-[10px] text-muted mt-1">Minimal {{ $expedition->quota_booked }} kursi (sudah dipesan).</p>
                </div>

                <!-- 8. Status Batch (UI Kit Dropdown Varian 3 / Status Dot) -->
                <div @click.outside="statusDropdownOpen = false" class="relative">
                    <label class="block text-xs font-bold text-ink-heading uppercase tracking-wider mb-2">
                        Status Batch <span class="text-rose-500">*</span>
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
                                @click="status = 'open'; statusLabel = 'Pendaftaran Buka (Open)'; statusDot = 'bg-emerald-500'; statusDropdownOpen = false;"
                                class="w-full text-left px-4 py-2 hover:bg-gray-50 text-body-strong font-medium flex items-center justify-between cursor-pointer"
                                :class="status === 'open' ? 'bg-primary-subtle/50 font-bold' : ''">
                            <span class="text-emerald-700">Pendaftaran Buka (Open)</span>
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
                        <button type="button" 
                                @click="status = 'cancelled'; statusLabel = 'Dibatalkan (Cancelled)'; statusDot = 'bg-rose-500'; statusDropdownOpen = false;"
                                class="w-full text-left px-4 py-2 hover:bg-gray-50 text-body-strong font-medium flex items-center justify-between cursor-pointer"
                                :class="status === 'cancelled' ? 'bg-primary-subtle/50 font-bold' : ''">
                            <span class="text-rose-700">Dibatalkan (Cancelled)</span>
                            <span class="w-2 h-2 rounded-full bg-rose-500"></span>
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
                Perbarui Batch Ekspedisi
            </button>
        </div>
    </form>

</div>
@endsection
