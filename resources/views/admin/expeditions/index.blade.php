@extends('layouts.admin')

@section('title', 'Jadwal Batch Ekspedisi')
@section('header_title', 'Jadwal Batch Ekspedisi (Open & Private Trip)')
@section('header_subtitle', 'Kelola kuota peserta, tanggal keberangkatan, dan eksekusi penguncian harga (Price Lock)')

@section('content')
<div class="space-y-6">

    <!-- Page Header Banner -->
    <x-admin.page-header 
        badge="Jadwal & Kuota" 
        title="Batch Jadwal Ekspedisi" 
        subtitle="Kelola batch keberangkatan Open Trip & Private Trip, akumulasi kuota pendaftar, dan eksekusi penguncian harga (Price Lock).">
        <x-slot:actions>
            <a href="{{ route('admin.expeditions.create') }}" 
               class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-full bg-primary text-white text-xs font-bold font-outfit hover:bg-primary-hover transition-colors shadow-xs shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Buka Batch Baru</span>
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <!-- Filter Bar -->
    <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar pb-1">
        <a href="{{ route('admin.expeditions.index') }}" 
           class="px-4 py-2 rounded-full text-xs font-bold font-outfit whitespace-nowrap transition-colors {{ empty($status) ? 'bg-primary text-white shadow-xs' : 'bg-surface-card border border-hairline text-ink hover:bg-canvas' }}">
            Semua Jadwal
        </a>
        <a href="{{ route('admin.expeditions.index', ['status' => 'open']) }}" 
           class="px-4 py-2 rounded-full text-xs font-bold font-outfit whitespace-nowrap transition-colors {{ $status === 'open' ? 'bg-primary text-white shadow-xs' : 'bg-surface-card border border-hairline text-ink hover:bg-canvas' }}">
            Pendaftaran Buka
        </a>
        <a href="{{ route('admin.expeditions.index', ['status' => 'price_locked']) }}" 
           class="px-4 py-2 rounded-full text-xs font-bold font-outfit whitespace-nowrap transition-colors {{ $status === 'price_locked' ? 'bg-primary text-white shadow-xs' : 'bg-surface-card border border-hairline text-ink hover:bg-canvas' }}">
            Price Locked
        </a>
        <a href="{{ route('admin.expeditions.index', ['status' => 'completed']) }}" 
           class="px-4 py-2 rounded-full text-xs font-bold font-outfit whitespace-nowrap transition-colors {{ $status === 'completed' ? 'bg-primary text-white shadow-xs' : 'bg-surface-card border border-hairline text-ink hover:bg-canvas' }}">
            Selesai
        </a>
    </div>

    <!-- Expeditions Table Card -->
    <div class="bg-surface-card border border-hairline rounded-3xl shadow-xs overflow-hidden">
        @if($expeditions->isEmpty())
            <div class="py-16 text-center text-muted">
                <svg class="w-12 h-12 mx-auto text-muted-soft mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-sm font-semibold text-ink-heading font-outfit">Belum ada batch ekspedisi untuk filter ini.</p>
                <p class="text-xs text-muted mt-1">Buat jadwal batch baru untuk membuka kuota pendaftaran pendaki.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-canvas border-b border-hairline text-muted font-bold font-outfit uppercase tracking-wider text-[11px]">
                        <tr>
                            <th class="py-3.5 px-5">Destinasi & Jalur</th>
                            <th class="py-3.5 px-5">Tipe & Mode</th>
                            <th class="py-3.5 px-5">Jadwal Tanggal</th>
                            <th class="py-3.5 px-5">Kuota Kursi</th>
                            <th class="py-3.5 px-5">Harga Terkunci</th>
                            <th class="py-3.5 px-5">Status</th>
                            <th class="py-3.5 px-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-hairline">
                        @foreach($expeditions as $exp)
                            @php
                                $percent = $exp->quota_max > 0 ? min(100, round(($exp->quota_booked / $exp->quota_max) * 100)) : 0;
                            @endphp
                            <tr class="hover:bg-canvas/50 transition-colors">
                                <!-- Mountain & Route -->
                                <td class="py-4 px-5">
                                    <div class="font-bold text-sm text-ink-heading">
                                        {{ $exp->mountain->name ?? 'Gunung' }}
                                    </div>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-xs text-muted">{{ $exp->route->name ?? 'Jalur' }}</span>
                                        @if($exp->route)
                                            <x-admin.grade-badge :grade="$exp->route->grade->value ?? $exp->route->grade" />
                                        @endif
                                    </div>
                                </td>

                                <!-- Type -->
                                <td class="py-4 px-5">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $exp->type === 'open' ? 'bg-primary-subtle text-primary border border-primary/20' : 'bg-purple-50 text-purple-700 border border-purple-200' }}">
                                        {{ strtoupper($exp->type) }} TRIP
                                    </span>
                                    <span class="block text-[11px] text-muted mt-1 capitalize font-medium">
                                        {{ $exp->hiking_type }}
                                    </span>
                                </td>

                                <!-- Dates -->
                                <td class="py-4 px-5">
                                    <span class="font-bold text-ink-heading block">
                                        {{ \Carbon\Carbon::parse($exp->departure_date)->format('d M Y') }}
                                    </span>
                                    <span class="text-[11px] text-muted block">
                                        s.d. {{ \Carbon\Carbon::parse($exp->return_date)->format('d M Y') }}
                                    </span>
                                </td>

                                <!-- Quota Progress -->
                                <td class="py-4 px-5 min-w-[140px]">
                                    <div class="flex items-center justify-between text-xs mb-1">
                                        <span class="font-bold text-ink-heading">{{ $exp->quota_booked }} / {{ $exp->quota_max }}</span>
                                        <span class="text-[11px] font-semibold text-muted">{{ $percent }}%</span>
                                    </div>
                                    <div class="w-full bg-hairline rounded-full h-1.5 overflow-hidden">
                                        <div class="bg-primary h-1.5 rounded-full" style="width: {{ $percent }}%"></div>
                                    </div>
                                </td>

                                <!-- Price Lock Info -->
                                <td class="py-4 px-5 font-bold">
                                    @if($exp->status === 'price_locked' || $exp->current_locked_price)
                                        <span class="text-emerald-700 font-mono text-sm block">
                                            Rp {{ number_format($exp->current_locked_price, 0, ',', '.') }}
                                        </span>
                                        <span class="text-[10px] text-muted font-normal">Harga Terkunci</span>
                                    @else
                                        <span class="text-muted text-xs italic">Menunggu H-X</span>
                                    @endif
                                </td>

                                <!-- Status Badge -->
                                <td class="py-4 px-5">
                                    <x-admin.status-badge :status="$exp->status" />
                                </td>

                                <!-- Actions -->
                                <td class="py-4 px-5 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1.5">
                                        @if($exp->status === 'open')
                                            <form method="POST" action="{{ route('admin.expeditions.price_lock', $exp->id) }}" 
                                                  onsubmit="return confirm('Kunci harga batch ini sekarang berdasarkan {{ $exp->quota_booked }} peserta yang terdaftar?')">
                                                @csrf
                                                <button type="submit" 
                                                        class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-800 border border-amber-300 hover:bg-amber-100 transition-colors"
                                                        title="Kunci Harga Sekarang">
                                                    🔒 Lock Price
                                                </button>
                                            </form>
                                        @endif

                                        <a href="{{ route('admin.expeditions.edit', $exp->id) }}" 
                                           class="p-1.5 rounded-lg border border-hairline text-ink hover:text-primary hover:border-primary transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>

                                        <form method="POST" action="{{ route('admin.expeditions.destroy', $exp->id) }}" 
                                              onsubmit="return confirm('Hapus jadwal batch ini?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-lg border border-hairline text-ink hover:text-rose-600 hover:border-rose-400 transition-colors" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($expeditions->hasPages())
                <div class="p-4 border-t border-hairline">
                    {{ $expeditions->links() }}
                </div>
            @endif
        @endif
    </div>

</div>
@endsection
