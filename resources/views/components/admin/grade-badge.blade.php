@props(['grade'])

@php
    $normalized = trim($grade ?? '');
    $config = match(true) {
        str_contains($normalized, 'Grade A') => [
            'bg' => 'bg-grade-a-bg text-grade-a-text border-grade-a-dot/20',
            'dot' => 'bg-grade-a-dot',
            'label' => 'Grade A (Pemula)'
        ],
        str_contains($normalized, 'Grade B') => [
            'bg' => 'bg-grade-b-bg text-grade-b-text border-grade-b-dot/20',
            'dot' => 'bg-grade-b-dot',
            'label' => 'Grade B (Menengah)'
        ],
        str_contains($normalized, 'Grade C') => [
            'bg' => 'bg-grade-c-bg text-grade-c-text border-grade-c-dot/20',
            'dot' => 'bg-grade-c-dot',
            'label' => 'Grade C (Ahli)'
        ],
        default => [
            'bg' => 'bg-gray-100 text-gray-700 border-gray-200',
            'dot' => 'bg-gray-400',
            'label' => $normalized ?: 'Umum'
        ]
    };
@endphp

<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold font-outfit border {{ $config['bg'] }}">
    <span class="w-1.5 h-1.5 rounded-full {{ $config['dot'] }}"></span>
    {{ $config['label'] }}
</span>
