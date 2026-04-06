<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- The title will be set on each individual page -->
    <title>@yield('title', $siteSettings->site_name ?? 'GoswamiSangath') - Genuine Matrimony</title>
    <!-- SEO Meta Tags -->
    <meta name="description" content="Find your perfect life partner on GoswamiSangath, the #1 trusted matrimony platform for the Goswami community. Secure, verified profiles, and seamless matchmaking.">
    <meta name="keywords" content="Goswami matrimony, Goswami samaj, matrimony for Goswami, Goswami sangath">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="GoswamiSangath - Genuine Matrimony">
    <meta property="og:description" content="Join GoswamiSangath to find your life partner within the Goswami community.">
    <meta property="og:image" content="{{ asset('favicon.png') }}">
    <!-- Global Theme System -->
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: ['selector', '[data-theme="dark"]'],
            theme: {
                extend: {
                    colors: {
                        primary: 'var(--theme-primary)',
                        'primary-dark': 'var(--theme-primary-dark)',
                        secondary: 'var(--theme-secondary)',
                        accent: 'var(--theme-accent)',
                        surface: 'var(--theme-surface)',
                        'surface-hover': 'var(--theme-surface-hover)',
                        'bg-default': 'var(--theme-bg)',
                        'text-main': 'var(--theme-text)',
                        'text-secondary': 'var(--theme-text-secondary)',
                    },
                    transitionTimingFunction: {
                        'out-expo': 'cubic-bezier(0.19, 1, 0.22, 1)',
                        'in-expo': 'cubic-bezier(0.19, 1, 0.22, 1)',
                        'elastic': 'cubic-bezier(0.68, -0.55, 0.265, 1.55)',
                    },
                    transitionDuration: {
                        '400': '400ms',
                        '600': '600ms',
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.6s cubic-bezier(0.19, 1, 0.22, 1) forwards',
                        'slide-up': 'slideUp 0.6s cubic-bezier(0.19, 1, 0.22, 1) forwards',
                        'scale-in': 'scaleIn 0.4s cubic-bezier(0.19, 1, 0.22, 1) forwards',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideUp: {
                            '0%': { opacity: '0', transform: 'translateY(10px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        scaleIn: {
                            '0%': { opacity: '0', transform: 'scale(0.95)' },
                            '100%': { opacity: '1', transform: 'scale(1)' },
                        }
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <!-- Link to the external stylesheet in the public folder -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    @stack('styles')
    @livewireStyles
</head>
<body style="font-family: 'Plus Jakarta Sans', sans-serif;">

    <div class="loader-wrapper" id="loader">
        <div class="flex flex-col items-center gap-6">
            <div class="size-20 text-primary flex items-center justify-center animate-bounce relative">
                @if(isset($siteSettings->site_logo) && $siteSettings->site_logo)
                    @if(isset($siteSettings->site_logo_dark) && $siteSettings->site_logo_dark)
                        <img src="{{ asset('storage/' . $siteSettings->site_logo) }}" alt="Logo" class="h-20 w-auto object-contain transition-opacity duration-500 ease-in-out dark:opacity-0 absolute inset-0 m-auto">
                        <img src="{{ asset('storage/' . $siteSettings->site_logo_dark) }}" alt="Logo Dark" class="h-20 w-auto object-contain transition-opacity duration-500 ease-in-out opacity-0 dark:opacity-100 absolute inset-0 m-auto">
                    @else
                        <img src="{{ asset('storage/' . $siteSettings->site_logo) }}" alt="Logo" class="h-20 w-auto object-contain">
                    @endif
                @else
                    <span class="material-symbols-outlined text-7xl">diversity_1</span>
                @endif
            </div>
            @if(isset($siteSettings->site_name_type) && $siteSettings->site_name_type === 'image' && isset($siteSettings->site_name_image))
                <div class="relative h-12 min-w-[200px]">
                    @if(isset($siteSettings->site_name_image_dark) && $siteSettings->site_name_image_dark)
                        <img src="{{ asset('storage/' . $siteSettings->site_name_image) }}" alt="{{ $siteSettings->site_name ?? 'GoswamiSangath' }}" class="h-12 w-auto object-contain transition-opacity duration-500 ease-in-out dark:opacity-0 absolute inset-0 m-auto">
                        <img src="{{ asset('storage/' . $siteSettings->site_name_image_dark) }}" alt="{{ $siteSettings->site_name ?? 'GoswamiSangath' }} Dark" class="h-12 w-auto object-contain transition-opacity duration-500 ease-in-out opacity-0 dark:opacity-100 absolute inset-0 m-auto">
                    @else
                        <img src="{{ asset('storage/' . $siteSettings->site_name_image) }}" alt="{{ $siteSettings->site_name ?? 'GoswamiSangath' }}" class="h-12 w-auto object-contain">
                    @endif
                </div>
            @else
                <h1 class="text-slate-900 dark:text-white text-4xl font-black leading-tight tracking-tight font-display transition-colors duration-500 ease-in-out">{{ $siteSettings->site_name ?? 'GoswamiSangath' }}</h1>
            @endif
        </div>
    </div>

    @include('partials.top-navbar')

    <main>
        @yield('content')
        {{ $slot ?? '' }}
    </main>

    @include('partials.upgrade-modal')

    @include('partials.footer')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <!-- Global Theme Manager -->
    <script src="{{ asset('js/theme.js') }}"></script>
    <!-- Language Switcher -->
    <script src="{{ asset('js/language-switcher.js') }}"></script>
    @stack('scripts')
    @livewireScripts
</body>
</html>
