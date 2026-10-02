@props(['title', 'value', 'subtitle' => null, 'icon' => null, 'trend' => null, 'trendType' => 'up'])

<div class="bg-surface-card border border-hairline rounded-2xl p-5 shadow-xs transition-all duration-200 hover:shadow-md hover:border-hairline/80 relative overflow-hidden group">
    <div class="flex items-center justify-between gap-4">
        <div>
            <p class="text-xs font-bold font-outfit uppercase tracking-wider text-muted">{{ $title }}</p>
            <h3 class="text-2xl font-extrabold font-outfit text-ink-heading mt-1 tracking-tight">{{ $value }}</h3>
            @if($subtitle)
                <p class="text-xs text-muted-soft mt-1">{{ $subtitle }}</p>
            @endif
        </div>
        @if($icon)
            <div class="w-12 h-12 rounded-xl bg-primary-subtle text-primary flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform duration-200">
                {!! $icon !!}
            </div>
        @endif
    </div>
    @if($trend)
        <div class="mt-3 pt-3 border-t border-hairline-soft flex items-center text-xs {{ $trendType === 'up' ? 'text-emerald-600' : 'text-rose-600' }}">
            <span>{{ $trend }}</span>
        </div>
    @endif
</div>
