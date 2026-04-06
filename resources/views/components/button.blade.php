@props([
    'variant' => 'primary',
    'size' => 'md',
    'icon' => null,
    'loading' => null, // Target for wire:loading
    'type' => 'button',
    'href' => null
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-bold transition-all duration-300 ease-out-expo active:scale-95 disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary/50 dark:focus:ring-offset-surface-dark';
    
    $variants = [
        'primary' => 'bg-primary text-white hover:bg-primary-dark hover:shadow-lg hover:shadow-primary/25 border border-transparent',
        'secondary' => 'bg-white dark:bg-surface-dark text-slate-900 dark:text-white border border-gray-200 dark:border-border-dark hover:border-primary hover:text-primary hover:shadow-md',
        'outline' => 'bg-transparent border border-gray-300 dark:border-border-dark text-slate-600 dark:text-text-secondary hover:border-primary hover:text-primary',
        'ghost' => 'bg-transparent text-slate-600 dark:text-text-secondary hover:bg-gray-100 dark:hover:bg-white/5 hover:text-primary',
        'glass' => 'bg-white/10 backdrop-blur-md border border-white/20 text-white hover:bg-white/20 shadow-xl',
    ];

    $sizes = [
        'sm' => 'h-8 px-3 text-xs rounded-lg',
        'md' => 'h-10 px-5 text-sm rounded-xl',
        'lg' => 'h-12 px-8 text-base rounded-2xl',
        'xl' => 'h-14 px-10 text-lg rounded-full',
        'icon' => 'size-10 rounded-full p-0',
    ];

    $classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon) <span class="material-symbols-outlined {{ $size === 'sm' ? 'icon-xs' : 'icon-sm' }} mr-2">{{ $icon }}</span> @endif
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        <!-- Loading Spinner -->
        @if($loading)
            <svg wire:loading wire:target="{{ $loading }}" class="animate-spin -ml-1 mr-2 h-4 w-4 text-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        @endif

        @if($icon) 
            <span class="material-symbols-outlined {{ $size === 'sm' ? 'icon-xs' : 'icon-sm' }} mr-2 transition-transform group-hover:scale-110" wire:loading.class="hidden" @if($loading) wire:target="{{ $loading }}" @endif>{{ $icon }}</span> 
        @endif
        
        <span @if($loading) wire:loading.class="opacity-70" wire:target="{{ $loading }}" @endif>
            {{ $slot }}
        </span>
    </button>
@endif
