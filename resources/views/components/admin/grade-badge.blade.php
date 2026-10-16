@props(['grade', 'short' => false])

@php
    $normalized = trim($grade ?? '');
    $config = match(true) {
        str_contains($normalized, 'Grade A') => [
            'bg' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
            'dot' => 'bg-emerald-500',
            'label' => $short ? 'Grade A' : 'Grade A • Pemula'
        ],
        str_contains($normalized, 'Grade B') => [
            'bg' => 'bg-amber-50 text-amber-800 border-amber-200',
            'dot' => 'bg-amber-500',
            'label' => $short ? 'Grade B' : 'Grade B • Menengah'
        ],
        str_contains($normalized, 'Grade C') => [
            'bg' => 'bg-rose-50 text-rose-800 border-rose-200',
            'dot' => 'bg-rose-500',
            'label' => $short ? 'Grade C' : 'Grade C • Ahli'
        ],
        default => [
            'bg' => 'bg-gray-100 text-gray-700 border-gray-200',
            'dot' => 'bg-gray-400',
            'label' => $normalized ?: 'Umum'
        ]
    };
@endphp

<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold font-outfit border whitespace-nowrap shadow-2xs {{ $config['bg'] }}">
    <span class="w-1.5 h-1.5 rounded-full shrink-0 {{ $config['dot'] }}"></span>
    <span>{{ $config['label'] }}</span>
</span>
