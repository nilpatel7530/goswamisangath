<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Terms and Conditions - GoswamiSangath</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Spline+Sans:wght@300;400;500;600;700&family=Noto+Sans:wght@400;500;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#ec3713",
                        "background-light": "#f8f6f6",
                        "background-dark": "#221310",
                        "surface-dark": "#2F1B18",
                    },
                    fontFamily: {
                        "display": ["Spline Sans", "sans-serif"],
                        "body": ["Noto Sans", "sans-serif"],
                    },
                    borderRadius: {
                        "DEFAULT": "1rem",
                        "lg": "2rem",
                        "xl": "3rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
    <!-- Global Theme System -->
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f8f6f6;
        }
        .dark ::-webkit-scrollbar-track {
            background: #221310;
        }
        ::-webkit-scrollbar-thumb {
            background: #e5e7eb;
            border-radius: 4px;
        }
        .dark ::-webkit-scrollbar-thumb {
            background: #392b28;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #ec3713;
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-gray-900 dark:text-white antialiased overflow-x-hidden selection:bg-primary selection:text-white">
    <div class="relative flex h-auto min-h-screen w-full flex-col">
        @include('partials.top-navbar')
        
        <!-- Hero Section -->
        <div class="px-6 lg:px-40 flex flex-1 justify-center py-12 lg:py-20 relative overflow-hidden">
            <!-- Background Decoration -->
            <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-primary/10 rounded-full blur-[100px] pointer-events-none translate-x-1/3 -translate-y-1/3"></div>
            <div class="layout-content-container flex flex-col max-w-[1200px] flex-1 z-10">
                <div class="@container">
                    <div class="flex flex-col gap-12 lg:gap-20">
                        <!-- Typography Heavy Intro -->
                        <div class="flex flex-col gap-6 max-w-4xl">
                            <h1 class="text-slate-900 dark:text-white text-5xl lg:text-7xl font-black leading-[1.1] tracking-[-0.033em]">
                                <span data-translate="Terms and Conditions">{{ __('Terms and Conditions') }}</span>
                            </h1>
                            <p class="text-slate-600 dark:text-white/70 text-lg lg:text-2xl font-normal leading-relaxed max-w-2xl" data-translate="Please read these terms carefully before using our matrimonial services. By accessing or using our platform, you agree to be bound by these terms.">
                                {{ __('Please read these terms carefully before using our matrimonial services. By accessing or using our platform, you agree to be bound by these terms.') }}
                            </p>
                            <p class="text-slate-400 dark:text-white/50 text-sm">
                                Last updated: {{ date('F d, Y') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Terms Content Section -->
        <div class="px-6 lg:px-40 flex flex-1 justify-center py-12 bg-background-light dark:bg-background-dark">
            <div class="layout-content-container flex flex-col max-w-[1200px] flex-1 gap-12">
                @if(isset($siteSettings->terms_content) && $siteSettings->terms_content)
                    <div class="prose dark:prose-invert max-w-none text-slate-700 dark:text-[#b9a19d]">
                        {!! $siteSettings->terms_content !!}
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-20 text-center gap-6">
                        <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-surface-dark border border-gray-200 dark:border-[#392b28] flex items-center justify-center">
                            <span class="material-symbols-outlined text-[#b9a19d]">lock_reset</span>
                        </div>
                        <p class="text-slate-500 dark:text-[#b9a19d] max-w-sm">{{ __('No terms and conditions content found. Please update the content in the admin settings panel.') }}</p>
                        <a href="{{ route('home') }}" class="px-8 py-3 rounded-full bg-primary text-white font-bold transition-all hover:scale-105 active:scale-95 shadow-lg shadow-primary/20">
                            {{ __('Back to Home') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- CTA Section -->
        <div class="px-6 lg:px-40 py-24 flex justify-center bg-background-light dark:bg-background-dark relative overflow-hidden">
            <!-- Decorative gradient -->
            <div class="absolute inset-0 bg-gradient-to-b from-transparent to-primary/5 pointer-events-none"></div>
            <div class="layout-content-container flex flex-col items-center max-w-[800px] text-center gap-8 relative z-10">
                <div class="w-20 h-20 rounded-full bg-slate-100 dark:bg-surface-dark border border-gray-200 dark:border-[#392b28] flex items-center justify-center mb-4 shadow-lg shadow-primary/20">
                    <span class="material-symbols-outlined text-4xl text-primary">description</span>
                </div>
                <h2 class="text-slate-900 dark:text-white text-5xl lg:text-7xl font-black tracking-tight leading-none">
                    Questions? <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-orange-500">We're Here</span>
                </h2>
                <p class="text-slate-600 dark:text-white/60 text-xl max-w-xl">
                    If you have any questions about these Terms and Conditions, please don't hesitate to reach out to our support team.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 w-full justify-center mt-6">
                    @auth
                        <a href="{{ route('dashboard') }}" class="flex items-center justify-center rounded-full h-14 px-8 bg-primary hover:bg-red-600 text-white text-lg font-bold leading-normal tracking-[0.015em] transition-all transform hover:scale-105 shadow-xl shadow-primary/30">
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('signup') }}" class="flex items-center justify-center rounded-full h-14 px-8 bg-primary hover:bg-red-600 text-white text-lg font-bold leading-normal tracking-[0.015em] transition-all transform hover:scale-105 shadow-xl shadow-primary/30">
                            Get Started
                        </a>
                    @endauth
                    <a href="{{ route('home') }}" class="flex items-center justify-center rounded-full h-14 px-8 bg-transparent border border-gray-300 dark:border-[#392b28] hover:bg-black/5 dark:hover:bg-white/5 text-slate-900 dark:text-white text-lg font-bold leading-normal transition-colors">
                        Back to Home
                    </a>
                </div>
            </div>
        </div>
        
        @include('partials.footer')
    </div>
    
    <!-- Global Theme Manager -->
    <script src="{{ asset('js/theme.js') }}"></script>
    <!-- Alpine.js for dropdown functionality -->
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <!-- Language Switcher -->
    <script src="{{ asset('js/language-switcher.js') }}"></script>
</body>
</html>

