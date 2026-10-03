<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-5 py-2.5 bg-primary hover:bg-primary-hover active:bg-primary-active border border-transparent rounded-full font-bold text-xs text-white tracking-wide shadow-2xs hover:shadow-xs focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition-all duration-150 cursor-pointer']) }}>
    {{ $slot }}
</button>
