<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? ($siteSettings->site_name ?? 'GoswamiSangath') }}</title>

    <!-- Global Theme System -->
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Spline+Sans:wght@300;400;500;600;700&family=Noto+Sans:wght@400;500;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#ec3713",
                        "background-light": "#f8f6f6",
                        "background-dark": "#181211",
                        "surface-dark": "#271d1c",
                        "border-dark": "#392b28",
                        "text-muted": "#a1a1aa",
                    },
                    fontFamily: {
                        "display": ["Spline Sans", "sans-serif"],
                        "body": ["Noto Sans", "sans-serif"],
                    },
                    animation: {
                        "fade-in": "fadeIn 0.5s ease-out forwards",
                        "slide-up": "slideUp 0.6s ease-out forwards",
                        "scale-in": "scaleIn 0.4s cubic-bezier(0.19, 1, 0.22, 1) forwards",
                    },
                    keyframes: {
                        fadeIn: {
                            "0%": { opacity: "0" },
                            "100%": { opacity: "1" },
                        },
                        slideUp: {
                            "0%": { opacity: "0", transform: "translateY(10px)" },
                            "100%": { opacity: "1", transform: "translateY(0)" },
                        },
                        scaleIn: {
                            "0%": { opacity: "0", transform: "scale(0.95)" },
                            "100%": { opacity: "1", transform: "scale(1)" },
                        }
                    }
                },
            },
        }
    </script>

    @stack('styles')
    @livewireStyles
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-slate-900 dark:text-white overflow-x-hidden min-h-screen flex flex-col antialiased">
    
    <div class="flex flex-1 overflow-hidden">
        @include('partials.user-sidebar')
        
        <div class="flex flex-1 relative lg:ml-80">
            {{ $slot ?? '' }}
            @yield('content')
        </div>
    </div>

    @include('partials.upgrade-modal')

    <!-- Global Theme Manager -->
    <script src="{{ asset('js/theme.js') }}"></script>
    <!-- Language Switcher -->
    <script src="{{ asset('js/language-switcher.js') }}"></script>

    @stack('scripts')
    @livewireScripts
</body>
</html>
