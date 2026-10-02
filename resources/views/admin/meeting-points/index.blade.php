@extends('layouts.admin')

@section('title', 'Titik Kumpul Shuttle')
@section('header_title', 'Titik Kumpul Shuttle & Antar-Jemput')
@section('header_subtitle', 'Kelola titik penjemputan resmi (stasiun, bandara, basecamp) dan biaya shuttle per orang')

@section('content')
<div x-data="{ addModalOpen: false, editModalOpen: false, activePoint: {} }" class="space-y-6">

    <!-- Page Header -->
    <x-admin.page-header
        badge="Master Data"
        title="Titik Kumpul Shuttle"
        subtitle="Kelola titik penjemputan resmi rombongan (stasiun, bandara, terminal, basecamp) dan biaya shuttle per orang."
    >
        <x-slot:actions>
            <button type="button" @click="addModalOpen = true" 
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-primary text-white text-xs font-bold hover:bg-primary-hover transition-colors shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Tambah Titik Kumpul</span>
            </button>
        </x-slot:actions>
    </x-admin.page-header>

    <!-- Mountain Filter Bar -->
    <div class="flex items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.meeting-points.index') }}" class="flex items-center gap-2.5">
            <span class="text-xs font-bold font-outfit text-muted uppercase tracking-wider">Filter Destinasi:</span>
            <select name="mountain_id" onchange="this.form.submit()" 
                    class="text-xs rounded-full border border-hairline bg-surface-card text-ink py-2 px-4 focus:border-primary shadow-xs font-semibold">
                <option value="">Semua Gunung</option>
                @foreach($mountains as $m)
                    <option value="{{ $m->id }}" {{ $mountainId == $m->id ? 'selected' : '' }}>
                        {{ $m->name }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    <!-- Table Card -->
    <div class="bg-surface-card border border-hairline rounded-3xl shadow-xs overflow-hidden">
        @if($meetingPoints->isEmpty())
            <div class="py-16 text-center text-muted">
                <svg class="w-12 h-12 mx-auto text-muted-soft mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <p class="text-sm font-semibold font-outfit text-ink-heading">Belum ada titik kumpul shuttle.</p>
                <p class="text-xs text-muted mt-1">Tambahkan meeting point (seperti stasiun kereta atau basecamp) untuk gunung.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-canvas border-b border-hairline text-muted font-bold font-outfit uppercase tracking-wider text-[11px]">
                        <tr>
                            <th class="py-3.5 px-5">Nama Titik Kumpul</th>
                            <th class="py-3.5 px-5">Destinasi Gunung</th>
                            <th class="py-3.5 px-5">Tipe Lokasi</th>
                            <th class="py-3.5 px-5">Biaya Tambahan / Pax</th>
                            <th class="py-3.5 px-5">Status</th>
                            <th class="py-3.5 px-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-hairline">
                        @foreach($meetingPoints as $p)
                            <tr class="hover:bg-canvas/50 transition-colors">
                                <td class="py-4 px-5 font-bold text-ink-heading">
                                    {{ $p->name }}
                                    @if($p->is_default)
                                        <span class="ml-1.5 px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-primary-subtle text-primary border border-primary/20">
                                            Default
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-5 font-semibold text-ink">
                                    {{ $p->mountain->name ?? '-' }}
                                </td>
                                <td class="py-4 px-5">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-canvas border border-hairline capitalize text-ink">
                                        {{ $p->location_type }}
                                    </span>
                                </td>
                                <td class="py-4 px-5 font-bold text-ink-heading">
                                    @if($p->additional_price_per_pax > 0)
                                        + Rp {{ number_format($p->additional_price_per_pax, 0, ',', '.') }}
                                    @else
                                        <span class="text-emerald-600">Gratis (Rp 0)</span>
                                    @endif
                                </td>
                                <td class="py-4 px-5">
                                    <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                                    </span>
                                </td>
                                <td class="py-4 px-5 text-right">
                                    <form method="POST" action="{{ route('admin.meeting-points.destroy', $p->id) }}" 
                                          onsubmit="return confirm('Hapus titik kumpul ini?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg border border-hairline text-ink hover:text-rose-600 hover:border-rose-400 transition-colors" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($meetingPoints->hasPages())
                <div class="p-4 border-t border-hairline">
                    {{ $meetingPoints->links() }}
                </div>
            @endif
        @endif
    </div>

    <!-- Modal: Tambah Titik Kumpul -->
    <div x-show="addModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-xs" @click="addModalOpen = false"></div>
        <div class="relative bg-surface-card rounded-3xl border border-hairline shadow-xl max-w-md w-full p-6 space-y-5 z-10">
            <div class="flex items-center justify-between border-b border-hairline pb-3">
                <h3 class="text-base font-extrabold font-outfit text-ink-heading">Tambah Titik Kumpul Shuttle</h3>
                <button type="button" @click="addModalOpen = false" class="text-muted hover:text-ink text-lg">&times;</button>
            </div>

            <form method="POST" action="{{ route('admin.meeting-points.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[11px] font-bold text-muted uppercase mb-1">Destinasi Gunung</label>
                    <select name="mountain_id" required class="w-full text-xs rounded-xl border border-hairline bg-canvas p-2.5 text-ink focus:border-primary">
                        @foreach($mountains as $m)
                            <option value="{{ $m->id }}">{{ $m->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-muted uppercase mb-1">Nama Titik Kumpul</label>
                    <input type="text" name="name" required placeholder="Contoh: Stasiun Solo Balapan" 
                           class="w-full text-xs rounded-xl border border-hairline bg-canvas p-2.5 text-ink focus:border-primary">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-muted uppercase mb-1">Tipe Lokasi</label>
                        <select name="location_type" required class="w-full text-xs rounded-xl border border-hairline bg-canvas p-2.5 text-ink focus:border-primary">
                            <option value="basecamp">Basecamp</option>
                            <option value="station">Stasiun Kereta</option>
                            <option value="airport">Bandara</option>
                            <option value="terminal">Terminal Bus</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-muted uppercase mb-1">Biaya Shuttle / Pax (Rp)</label>
                        <input type="number" name="additional_price_per_pax" value="0" min="0" required 
                               class="w-full text-xs rounded-xl border border-hairline bg-canvas p-2.5 text-ink focus:border-primary font-bold">
                    </div>
                </div>

                <div>
                    <label class="flex items-center gap-2 cursor-pointer text-xs font-semibold text-ink-heading">
                        <input type="checkbox" name="is_default" value="1" class="rounded border-hairline text-primary focus:ring-primary">
                        <span>Jadikan Pilihan Titik Kumpul Default</span>
                    </label>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-hairline">
                    <button type="button" @click="addModalOpen = false" class="px-4 py-2 rounded-full border border-hairline text-xs font-semibold">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-full bg-primary text-white text-xs font-bold hover:bg-primary-hover">Simpan</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
