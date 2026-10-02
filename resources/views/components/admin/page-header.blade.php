@props([
    'title',
    'subtitle' => null,
    'backUrl' => null,
    'backLabel' => 'Kembali',
])

<div class="bg-surface-card border border-hairline rounded-2xl md:rounded-3xl p-6 md:p-7 flex flex-col md:flex-row md:items-center justify-between gap-5 shadow-xs">
    <div class="space-y-1 max-w-2xl">
        @if ($backUrl)
            <a href="{{ $backUrl }}"
                class="inline-flex items-center gap-1.5 text-xs font-semibold font-outfit text-muted hover:text-primary transition-colors mb-1.5 group/back">
                <svg class="w-4 h-4 transition-transform group-hover/back:-translate-x-0.5" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>{{ $backLabel }}</span>
            </a>
        @endif

        <h1 class="text-2xl md:text-3xl font-bold font-outfit text-ink-heading tracking-tight leading-tight">
            {{ $title }}
        </h1>

        @if ($subtitle)
            <p class="text-xs md:text-sm text-muted font-normal leading-relaxed">
                {{ $subtitle }}
            </p>
        @endif
    </div>

    @if (isset($actions) || $slot->isNotEmpty())
        <div class="flex flex-wrap items-center gap-3 shrink-0 w-full md:w-auto">
            {{ $actions ?? $slot }}
        </div>
    @endif
</div>
