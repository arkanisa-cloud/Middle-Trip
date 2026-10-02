@props([
    'title',
    'value',
    'subtitle' => null,
    'icon' => null,
    'trend' => null,
    'trendType' => 'up',
    'iconBg' => 'bg-primary/10 text-primary',
])

<div class="bg-surface-card border border-hairline rounded-2xl p-5 md:p-6 shadow-xs transition-all duration-200 hover:shadow-md hover:border-hairline/80 flex flex-col justify-between group">
    <div>
        <div class="flex items-center justify-between gap-3">
            <span class="text-xs font-semibold text-muted font-outfit">{{ $title }}</span>
            @if($icon)
                <div class="w-10 h-10 rounded-xl {{ $iconBg }} flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform duration-200">
                    {!! $icon !!}
                </div>
            @endif
        </div>
        <div class="text-2xl md:text-3xl font-extrabold font-outfit text-ink-heading tracking-tight mt-2">
            {{ $value }}
        </div>
    </div>

    @if($subtitle || $trend)
        <div class="mt-4 pt-3 border-t border-hairline/60 flex items-center justify-between text-xs">
            @if($subtitle)
                <span class="text-muted-soft text-[11px]">{{ $subtitle }}</span>
            @endif
            @if($trend)
                <span class="font-semibold {{ $trendType === 'up' ? 'text-emerald-600' : 'text-rose-600' }}">{{ $trend }}</span>
            @endif
        </div>
    @endif
</div>
