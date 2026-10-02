@extends('layouts.admin')

@section('title', 'Master Gunung & Jalur')
@section('header_title', 'Master Data Gunung & Jalur')
@section('header_subtitle', 'Kelola destinasi pendakian, penetapan grade jalur, dan matriks harga dinamis')

@section('content')
    <div class="space-y-6">

        <!-- Page Header Banner -->
        <x-admin.page-header title="Katalog Gunung & Destinasi"
            subtitle="Kelola destinasi pendakian gunung, standarisasi grade jalur (A/B/C), kuota, dan matriks harga bertingkat.">
            <x-slot:actions>
                <a href="{{ route('admin.mountains.create') }}"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-primary text-white text-xs font-semibold font-outfit hover:bg-primary-hover transition-colors shadow-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Tambah Gunung Baru</span>
                </a>
            </x-slot:actions>
        </x-admin.page-header>

        <!-- Action Bar: Search -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
            <form method="GET" action="{{ route('admin.mountains.index') }}" class="relative max-w-sm w-full">
                <input type="text" name="search" value="{{ $search ?? '' }}"
                    placeholder="Cari nama gunung atau provinsi..."
                    class="w-full pl-10 pr-4 py-2 text-xs rounded-full border border-hairline bg-surface-card text-ink placeholder-muted focus:border-primary focus:ring-1 focus:ring-primary shadow-xs">
                <svg class="w-4 h-4 text-muted absolute left-3.5 top-1/2 -translate-y-1/2" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </form>
        </div>

        <!-- Mountains Table Card -->
        <div class="bg-surface-card border border-hairline rounded-3xl shadow-xs overflow-hidden">
            @if ($mountains->isEmpty())
                <div class="py-16 text-center text-muted">
                    <svg class="w-12 h-12 mx-auto text-muted-soft mb-3" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M3 17l6-6 4 4 8-8M3 21h18" />
                    </svg>
                    <p class="text-sm font-semibold text-ink-heading font-outfit">Belum ada data gunung.</p>
                    <p class="text-xs text-muted mt-1">Mulai tambahkan gunung pertama untuk membuka jadwal ekspedisi.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-canvas border-b border-hairline text-muted font-semibold font-outfit text-xs">
                            <tr>
                                <th class="py-3.5 px-5">Destinasi</th>
                                <th class="py-3.5 px-5">Elevasi</th>
                                <th class="py-3.5 px-5">Jalur & Grade</th>
                                <th class="py-3.5 px-5">Booking Fee (DP)</th>
                                <th class="py-3.5 px-5">Price Lock</th>
                                <th class="py-3.5 px-5">Status</th>
                                <th class="py-3.5 px-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-hairline">
                            @foreach ($mountains as $m)
                                <tr class="hover:bg-canvas/50 transition-colors">
                                    <!-- Mountain Info & Cover -->
                                    <td class="py-4 px-5">
                                        <div class="flex items-center gap-3">
                                            <img src="{{ $m->cover_image }}" alt="{{ $m->name }}"
                                                class="w-12 h-12 rounded-xl object-cover border border-hairline shrink-0 bg-canvas"
                                                onerror="this.src='https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=100&fit=crop'">
                                            <div>
                                                <a href="{{ route('admin.mountains.edit', $m->id) }}"
                                                    class="font-bold text-sm text-ink-heading hover:text-primary transition-colors">
                                                    {{ $m->name }}
                                                </a>
                                                <div class="flex items-center gap-1.5 flex-wrap">
                                                    <span class="text-[11px] text-muted">{{ $m->province }}</span>
                                                    @if ($m->is_featured)
                                                        <span
                                                            class="inline-flex items-center gap-1 text-[10px] font-bold text-amber-800 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200">
                                                            ⭐ Home Slot {{ $m->featured_order ?? 1 }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Elevation -->
                                    <td class="py-4 px-5 font-semibold text-ink-heading">
                                        {{ number_format($m->elevation) }} MDPL
                                    </td>

                                    <!-- Routes & Grades -->
                                    <td class="py-4 px-5">
                                        <div class="flex flex-wrap gap-1.5 max-w-xs">
                                            @forelse($m->routes as $r)
                                                <x-admin.grade-badge :grade="$r->grade->value ?? $r->grade" />
                                            @empty
                                                <span class="text-xs text-muted italic">Belum ada jalur</span>
                                            @endforelse
                                        </div>
                                    </td>

                                    <!-- DP -->
                                    <td class="py-4 px-5 font-bold text-ink-heading">
                                        Rp {{ number_format($m->booking_fee_per_pax, 0, ',', '.') }}
                                        <span class="block text-[10px] text-muted font-normal">per orang</span>
                                    </td>

                                    <!-- Price Lock Days -->
                                    <td class="py-4 px-5">
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-canvas border border-hairline text-ink">
                                            H-{{ $m->price_lock_days_before_departure }} Hari
                                        </span>
                                    </td>

                                    <!-- Active Badge -->
                                    <td class="py-4 px-5">
                                        @if ($m->is_active)
                                            <span
                                                class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1 text-[11px] font-bold text-gray-500 bg-gray-100 px-2.5 py-0.5 rounded-full border border-gray-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Nonaktif
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Actions -->
                                    <td class="py-4 px-5 text-right whitespace-nowrap">
                                        <div class="inline-flex items-center justify-end gap-1.5">
                                            <a href="{{ route('admin.expeditions.create', ['mountain_id' => $m->id]) }}"
                                                class="inline-flex items-center justify-center p-2 rounded-xl border border-hairline bg-surface-card text-ink hover:text-emerald-600 hover:border-emerald-300 hover:bg-emerald-50/50 transition-colors shadow-2xs"
                                                title="Buka Batch Jadwal Baru">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </a>

                                            <a href="{{ route('admin.mountains.edit', $m->id) }}"
                                                class="inline-flex items-center justify-center p-2 rounded-xl border border-hairline bg-surface-card text-ink hover:text-primary hover:border-primary/40 hover:bg-primary-subtle transition-colors shadow-2xs"
                                                title="Edit Master Gunung">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>

                                            <form method="POST" action="{{ route('admin.mountains.destroy', $m->id) }}"
                                                data-confirm="Apakah Anda yakin ingin menghapus destinasi '{{ $m->name }}'? Seluruh data rute terkait akan ikut terhapus."
                                                data-title="Hapus Destinasi Gunung"
                                                class="confirm-delete inline-block m-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center justify-center p-2 rounded-xl border border-hairline bg-surface-card text-ink hover:text-rose-600 hover:border-rose-300 hover:bg-rose-50/50 transition-colors shadow-2xs"
                                                    title="Hapus Gunung">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($mountains->hasPages())
                    <div class="p-4 border-t border-hairline">
                        {{ $mountains->links() }}
                    </div>
                @endif
            @endif
        </div>

    </div>
@endsection
