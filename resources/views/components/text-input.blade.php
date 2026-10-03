@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-hairline bg-white text-ink text-xs sm:text-sm focus:border-primary focus:ring-1 focus:ring-primary rounded-xl shadow-2xs transition placeholder-muted-soft']) }}>
