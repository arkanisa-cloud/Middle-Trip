@props(['status', 'context' => 'booking'])

@php
    $normalized = strtolower(trim($status ?? ''));
    $isExpedition = in_array($context, ['expedition', 'batch']);

    $config = match($normalized) {
        'open' => [
            'bg' => $isExpedition ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : 'bg-amber-50 text-amber-800 border-amber-200',
            'dot' => $isExpedition ? 'bg-emerald-500' : 'bg-amber-500',
            'label' => $isExpedition ? 'Batch Dibuka' : 'Menunggu DP'
        ],
        'reserved' => [
            'bg' => 'bg-blue-50 text-blue-700 border-blue-200',
            'dot' => 'bg-blue-500',
            'label' => 'DP Terbayar (Reserved)'
        ],
        'price_locked' => [
            'bg' => 'bg-amber-50 text-amber-800 border-amber-200',
            'dot' => 'bg-amber-500',
            'label' => $isExpedition ? 'Price Locked' : 'Menunggu Pelunasan'
        ],
        'paid' => [
            'bg' => 'bg-emerald-50 text-emerald-800 border-emerald-300 font-bold',
            'dot' => 'bg-emerald-600',
            'label' => 'Lunas'
        ],
        'completed' => [
            'bg' => 'bg-purple-50 text-purple-700 border-purple-200',
            'dot' => 'bg-purple-500',
            'label' => 'Selesai'
        ],
        'expired' => [
            'bg' => 'bg-gray-100 text-gray-600 border-gray-200',
            'dot' => 'bg-gray-400',
            'label' => 'Expired'
        ],
        'cancelled', 'cancelled_refund' => [
            'bg' => 'bg-rose-50 text-rose-700 border-rose-200',
            'dot' => 'bg-rose-500',
            'label' => 'Dibatalkan'
        ],
        default => [
            'bg' => 'bg-gray-100 text-gray-700 border-gray-200',
            'dot' => 'bg-gray-400',
            'label' => ucfirst($normalized)
        ]
    };
@endphp

<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold font-outfit border whitespace-nowrap shadow-2xs {{ $config['bg'] }}">
    <span class="w-1.5 h-1.5 rounded-full shrink-0 {{ $config['dot'] }}"></span>
    <span>{{ $config['label'] }}</span>
</span>
