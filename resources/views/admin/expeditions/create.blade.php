@extends('layouts.admin')

@section('title', 'Buka Batch Ekspedisi Baru')
@section('header_title', 'Buka Batch Jadwal Ekspedisi Baru')
@section('header_subtitle', 'Tentukan destinasi, jalur, tanggal keberangkatan, dan kapasitas kuota peserta')

@section('content')
<div x-data="{
    mountains: @js($mountains),
    selectedMountainId: {{ old('mountain_id', $selectedMountainId ?? ($mountains->first()->id ?? 0)) }},
    availableRoutes: [],
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
                <!-- Mountain Selection -->
                <div>
                    <label class="block text-xs font-bold text-ink-heading uppercase tracking-wider mb-2">
                        Pilih Destinasi Gunung <span class="text-rose-500">*</span>
                    </label>
                    <select name="mountain_id" x-model="selectedMountainId" @change="updateRoutes()" required 
                            class="w-full text-xs rounded-xl border border-hairline bg-canvas p-3 text-ink focus:border-primary shadow-xs font-semibold">
                        @foreach($mountains as $m)
                            <option value="{{ $m->id }}">{{ $m->name }} ({{ $m->province }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Route Selection (Dynamic) -->
                <div>
                    <label class="block text-xs font-bold text-ink-heading uppercase tracking-wider mb-2">
                        Pilih Jalur Pendakian <span class="text-rose-500">*</span>
                    </label>
                    <select name="route_id" required 
                            class="w-full text-xs rounded-xl border border-hairline bg-canvas p-3 text-ink focus:border-primary shadow-xs font-semibold">
                        <template x-for="r in availableRoutes" :key="r.id">
                            <option :value="r.id" x-text="`${r.name} (${r.grade})`"></option>
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
                        <option value="camping">Camping (Bermalam di Tenda)</option>
                        <option value="tektok">Tek-tok (1 Hari Langsung Turun)</option>
                    </select>
                </div>

                <!-- Departure Date -->
                <div>
                    <label class="block text-xs font-bold text-ink-heading uppercase tracking-wider mb-2">
                        Tanggal Berangkat <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" name="departure_date" value="{{ old('departure_date', now()->addDays(7)->toDateString()) }}" required 
                           class="w-full text-xs rounded-xl border border-hairline bg-canvas p-3 text-ink focus:border-primary shadow-xs">
                </div>

                <!-- Return Date -->
                <div>
                    <label class="block text-xs font-bold text-ink-heading uppercase tracking-wider mb-2">
                        Tanggal Kembali / Selesai <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" name="return_date" value="{{ old('return_date', now()->addDays(9)->toDateString()) }}" required 
                           class="w-full text-xs rounded-xl border border-hairline bg-canvas p-3 text-ink focus:border-primary shadow-xs">
                </div>

                <!-- Quota Max -->
                <div>
                    <label class="block text-xs font-bold text-ink-heading uppercase tracking-wider mb-2">
                        Kapasitas Kuota Kursi <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="quota_max" value="{{ old('quota_max', 10) }}" min="1" max="100" required 
                           class="w-full text-xs rounded-xl border border-hairline bg-canvas p-3 text-ink focus:border-primary shadow-xs font-bold">
                </div>

                <!-- Status Initial -->
                <div>
                    <label class="block text-xs font-bold text-ink-heading uppercase tracking-wider mb-2">
                        Status Awal Batch <span class="text-rose-500">*</span>
                    </label>
                    <select name="status" required 
                            class="w-full text-xs rounded-xl border border-hairline bg-canvas p-3 text-ink focus:border-primary shadow-xs">
                        <option value="open">Pendaftaran Dibuka (Open)</option>
                        <option value="price_locked">Terkunci (Price Locked)</option>
                        <option value="completed">Selesai (Completed)</option>
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
                Terbitkan Batch Ekspedisi
            </button>
        </div>
    </form>

</div>
@endsection
