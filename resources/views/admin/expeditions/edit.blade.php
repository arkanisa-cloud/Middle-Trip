@extends('layouts.admin')

@section('title', 'Edit Batch Ekspedisi')
@section('header_title', 'Perbarui Batch Ekspedisi')
@section('header_subtitle', 'Ubah kuota, penyesuaian tanggal, atau perbarui status ekspedisi')

@section('content')
<div x-data="{
    mountains: @js($mountains),
    selectedMountainId: {{ old('mountain_id', $expedition->mountain_id) }},
    availableRoutes: [],
    selectedRouteId: {{ old('route_id', $expedition->route_id) }},
    updateRoutes() {
        let m = this.mountains.find(item => item.id == this.selectedMountainId);
        this.availableRoutes = m ? m.routes : [];
    },
    init() {
        this.updateRoutes();
    }
}" class="max-w-3xl mx-auto space-y-6">

    <!-- Page Header Banner -->
    <x-admin.page-header 
        badge="Jadwal & Kuota" 
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
                <!-- Mountain Selection -->
                <div>
                    <label class="block text-xs font-bold text-ink-heading uppercase tracking-wider mb-2">
                        Pilih Destinasi Gunung <span class="text-rose-500">*</span>
                    </label>
                    <select name="mountain_id" x-model="selectedMountainId" @change="updateRoutes()" required 
                            class="w-full text-xs rounded-xl border border-hairline bg-canvas p-3 text-ink focus:border-primary shadow-xs font-semibold">
                        @foreach($mountains as $m)
                            <option value="{{ $m->id }}" {{ $expedition->mountain_id == $m->id ? 'selected' : '' }}>
                                {{ $m->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Route Selection (Dynamic) -->
                <div>
                    <label class="block text-xs font-bold text-ink-heading uppercase tracking-wider mb-2">
                        Pilih Jalur Pendakian <span class="text-rose-500">*</span>
                    </label>
                    <select name="route_id" x-model="selectedRouteId" required 
                            class="w-full text-xs rounded-xl border border-hairline bg-canvas p-3 text-ink focus:border-primary shadow-xs font-semibold">
                        <template x-for="r in availableRoutes" :key="r.id">
                            <option :value="r.id" x-text="`${r.name} (${r.grade})`" :selected="r.id == selectedRouteId"></option>
                        </template>
                    </select>
                </div>

                <!-- Trip Type (Fixed to Open Trip) -->
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

                <!-- Hiking Type -->
                <div>
                    <label class="block text-xs font-bold text-ink-heading uppercase tracking-wider mb-2">
                        Jenis Pendakian <span class="text-rose-500">*</span>
                    </label>
                    <select name="hiking_type" required 
                            class="w-full text-xs rounded-xl border border-hairline bg-canvas p-3 text-ink focus:border-primary shadow-xs">
                        <option value="camping" {{ $expedition->hiking_type === 'camping' ? 'selected' : '' }}>Camping (Bermalam di Tenda)</option>
                        <option value="tektok" {{ $expedition->hiking_type === 'tektok' ? 'selected' : '' }}>Tek-tok (1 Hari Langsung Turun)</option>
                    </select>
                </div>

                <!-- Departure Date -->
                <div>
                    <label class="block text-xs font-bold text-ink-heading uppercase tracking-wider mb-2">
                        Tanggal Berangkat <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" name="departure_date" value="{{ old('departure_date', \Carbon\Carbon::parse($expedition->departure_date)->toDateString()) }}" required 
                           class="w-full text-xs rounded-xl border border-hairline bg-canvas p-3 text-ink focus:border-primary shadow-xs">
                </div>

                <!-- Return Date -->
                <div>
                    <label class="block text-xs font-bold text-ink-heading uppercase tracking-wider mb-2">
                        Tanggal Selesai <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" name="return_date" value="{{ old('return_date', \Carbon\Carbon::parse($expedition->return_date)->toDateString()) }}" required 
                           class="w-full text-xs rounded-xl border border-hairline bg-canvas p-3 text-ink focus:border-primary shadow-xs">
                </div>

                <!-- Quota Max -->
                <div>
                    <label class="block text-xs font-bold text-ink-heading uppercase tracking-wider mb-2">
                        Kapasitas Kuota Kursi <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="quota_max" value="{{ old('quota_max', $expedition->quota_max) }}" min="{{ $expedition->quota_booked }}" required 
                           class="w-full text-xs rounded-xl border border-hairline bg-canvas p-3 text-ink focus:border-primary shadow-xs font-bold">
                    <p class="text-[10px] text-muted mt-1">Minimal {{ $expedition->quota_booked }} kursi (sudah dipesan).</p>
                </div>

                <!-- Status Batch -->
                <div>
                    <label class="block text-xs font-bold text-ink-heading uppercase tracking-wider mb-2">
                        Status Batch <span class="text-rose-500">*</span>
                    </label>
                    <select name="status" required 
                            class="w-full text-xs rounded-xl border border-hairline bg-canvas p-3 text-ink focus:border-primary shadow-xs font-bold">
                        <option value="open" {{ $expedition->status === 'open' ? 'selected' : '' }}>Pendaftaran Buka (Open)</option>
                        <option value="price_locked" {{ $expedition->status === 'price_locked' ? 'selected' : '' }}>Terkunci (Price Locked)</option>
                        <option value="completed" {{ $expedition->status === 'completed' ? 'selected' : '' }}>Selesai (Completed)</option>
                        <option value="cancelled" {{ $expedition->status === 'cancelled' ? 'selected' : '' }}>Dibatalkan (Cancelled)</option>
                    </select>
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
