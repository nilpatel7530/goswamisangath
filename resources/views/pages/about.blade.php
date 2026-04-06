<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>About Us - GoswamiSangath</title>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Spline+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
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
                        "surface-border": "#543f3b",
                        "text-secondary": "#b9a19d"
                    },
                    fontFamily: {
                        "display": ["Spline Sans", "sans-serif"],
                        "sans": ["Spline Sans", "sans-serif"]
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
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f8f6f6; 
        }
        .dark ::-webkit-scrollbar-track {
            background: #181211; 
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
<body class="bg-background-light dark:bg-background-dark text-black dark:text-white font-display overflow-x-hidden antialiased selection:bg-primary selection:text-white">
    <div class="relative flex flex-col min-h-screen w-full">
        @include('partials.top-navbar')
        
        <!-- Main Content Wrapper -->
        <main class="flex flex-col flex-1 w-full max-w-[1280px] mx-auto px-4 lg:px-20 py-8 lg:py-12 gap-16 lg:gap-24">
            <!-- Hero Section -->
            <section class="relative rounded-3xl overflow-hidden min-h-[500px] flex items-center justify-center">
                <!-- Abstract Background with Overlay -->
                <div class="absolute inset-0 z-0 bg-cover bg-center" data-alt="Abstract dark bokeh lights background representing connection" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuD0mRWbgpXHsEeAUMiX1X-9HdUpnrTlCwWLg8F8FououfpFV95LcuoUc4eku-ouUP5HFsIjDfvNyrZJpJRJG3uWSbSCuC50Frz2DFLPBeNZnmInlYmNk4HEf_OQWoe0aiezCPBXc_sdIl8rJjc5xQ7SvhCHwv2ellCs1rYz2eHn7fVQfFkjkmMmQSfuZFcPtC-GpNsQORazimtPykbGQWpxvotFCtSMFMFbUy3jwZVdwA7tPDGaMKf44yMwe0atyYT-NovWf_Z4Lz8');">
                </div>
                <div class="absolute inset-0 bg-gradient-to-t from-background-dark via-background-dark/80 to-transparent dark:via-background-dark/80 z-10"></div>
                <div class="absolute inset-0 bg-black/20 dark:bg-black/40 z-10"></div>
                <div class="relative z-20 flex flex-col items-center text-center max-w-4xl px-4 gap-6">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 dark:bg-white/10 backdrop-blur-sm border border-white/20 dark:border-white/10">
                        <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                        <span class="text-xs font-semibold tracking-wider uppercase text-white/90 dark:text-white/90">Since 2024</span>
                    </div>
                    <h1 class="text-5xl lg:text-7xl font-bold leading-[1.1] tracking-tight text-white dark:text-white">
                        <span data-translate="We Connect">{{ __('We Connect') }}</span> <span class="text-primary italic" data-translate="Lives">{{ __('Lives') }}</span>, <br/> <span data-translate="Not Just Profiles.">{{ __('Not Just Profiles.') }}</span>
                    </h1>
                    <p class="text-lg lg:text-xl text-white/90 dark:text-text-secondary max-w-2xl font-light leading-relaxed" data-translate="Modern matchmaking for serious commitments. We are redefining how tradition meets technology in a chaotic digital world.">{{ __('Modern matchmaking for serious commitments. We are redefining how tradition meets technology in a chaotic digital world.') }}</p>
                </div>
            </section>
            
            <!-- Mission Statement (Card Variant) -->
            <section class="grid lg:grid-cols-2 gap-8 lg:gap-12 items-center">
                <div class="relative group rounded-xl overflow-hidden aspect-[4/3] lg:aspect-auto lg:h-full min-h-[400px]">
                    <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-105" data-alt="Abstract connection lines or nodes glowing in dark representing intelligent matching" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAZS7ko61R0VjuAM4SnEVTPPU6Xa7BtcKPJstRjvu9i28yL60KegHYV7XJKbPkJKbJyU--1FtJxEBLzsI1QLaIx5AxaCPxmb1TZ2vyrJy59ZnjbSjgPd-ZcWpltwD5CNUJxxHftIGlWl_icfiB6CaaYU0WyPKqUl9zEFbViNZlqW0TuiZMwQ2S92eKa5PxIme2VNhJPq-qK4O1SgME_8J4JEgIstfH_N5ubl2rA8wMTfOBs_EDsvvn_hqThOghjtNg9Us9Lp9klFC8');">
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-t from-background-dark/90 to-transparent"></div>
                    <div class="absolute bottom-8 left-8 right-8">
                        <p class="text-white text-2xl font-bold">The Science of Connection</p>
                    </div>
                </div>
                <div class="flex flex-col justify-center space-y-8">
                    <div class="space-y-4">
                        <h2 class="text-sm font-bold tracking-widest uppercase text-primary" data-translate="Our Mission">{{ __('Our Mission') }}</h2>
                        <p class="text-3xl lg:text-4xl font-bold leading-tight text-slate-900 dark:text-white" data-translate="Eliminating the noise of dating apps to restore the sanctity of finding a life partner.">{{ __('Eliminating the noise of dating apps to restore the sanctity of finding a life partner.') }}</p>
                    </div>
                    <div class="space-y-6">
                        <p class="text-slate-600 dark:text-text-secondary text-lg leading-relaxed" data-translate="In an era of endless swiping and superficial judgments, we built a platform grounded in verified data, serious intent, and intelligent compatibility. We believe that finding a partner is one of life's most significant journeys, and it deserves a dignified, precise approach.">{{ __('In an era of endless swiping and superficial judgments, we built a platform grounded in verified data, serious intent, and intelligent compatibility. We believe that finding a partner is one of life\'s most significant journeys, and it deserves a dignified, precise approach.') }}</p>
                        <div class="pl-6 border-l-4 border-primary/30">
                            <p class="text-slate-700 dark:text-white/90 italic text-xl" data-translate='"We don\'t just match profiles; we align futures."'>{{ __('"We don\'t just match profiles; we align futures."') }}</p>
                        </div>
                    </div>
                    <button class="w-fit flex items-center gap-2 text-slate-900 dark:text-white font-semibold hover:text-primary transition-colors group">
                        <span data-translate="Read Our Manifesto">{{ __('Read Our Manifesto') }}</span> 
                        <span class="material-symbols-outlined text-lg group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </button>
                </div>
            </section>
            
            <!-- Stats Section -->
            <section class="border-y border-gray-200 dark:border-surface-border py-12">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12 text-center lg:text-left">
                    <div class="flex flex-col gap-2">
                        <p class="text-5xl lg:text-6xl font-bold text-slate-900 dark:text-white tracking-tighter">10k+</p>
                        <p class="text-primary font-medium text-sm uppercase tracking-wide" data-translate="Matches Made">{{ __('Matches Made') }}</p>
                    </div>
                    <div class="flex flex-col gap-2">
                        <p class="text-5xl lg:text-6xl font-bold text-slate-900 dark:text-white tracking-tighter">5</p>
                        <p class="text-primary font-medium text-sm uppercase tracking-wide" data-translate="Years of Service">{{ __('Years of Service') }}</p>
                    </div>
                    <div class="flex flex-col gap-2">
                        <p class="text-5xl lg:text-6xl font-bold text-slate-900 dark:text-white tracking-tighter">100%</p>
                        <p class="text-primary font-medium text-sm uppercase tracking-wide" data-translate="Verified Profiles">{{ __('Verified Profiles') }}</p>
                    </div>
                    <div class="flex flex-col gap-2">
                        <p class="text-5xl lg:text-6xl font-bold text-slate-900 dark:text-white tracking-tighter">24/7</p>
                        <p class="text-primary font-medium text-sm uppercase tracking-wide" data-translate="Human Support">{{ __('Human Support') }}</p>
                    </div>
                </div>
            </section>
            
            <!-- Core Values -->
            <section class="flex flex-col gap-12">
                <div class="max-w-2xl">
                    <h2 class="text-3xl lg:text-5xl font-bold mb-4 text-slate-900 dark:text-white" data-translate="Core Values">{{ __('Core Values') }}</h2>
                    <p class="text-slate-600 dark:text-text-secondary text-lg" data-translate="The principles that guide every feature we build and every match we suggest.">{{ __('The principles that guide every feature we build and every match we suggest.') }}</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Value 1 -->
                    <div class="group flex flex-col gap-6 p-8 rounded-2xl bg-white dark:bg-surface-dark border border-gray-200 dark:border-surface-border hover:border-primary/50 transition-all duration-300">
                        <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-surface-border/50 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-colors">
                            <span class="material-symbols-outlined">visibility</span>
                        </div>
                        <div class="space-y-3">
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white">{{ __('Transparency') }}</h3>
                            <p class="text-slate-600 dark:text-text-secondary leading-relaxed">
                                {{ __('We don\'t hide behind black-box algorithms. You understand why you matched and what compatibility means.') }}
                            </p>
                        </div>
                    </div>
                    <!-- Value 2 -->
                    <div class="group flex flex-col gap-6 p-8 rounded-2xl bg-white dark:bg-surface-dark border border-gray-200 dark:border-surface-border hover:border-primary/50 transition-all duration-300">
                        <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-surface-border/50 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-colors">
                            <span class="material-symbols-outlined">encrypted</span>
                        </div>
                        <div class="space-y-3">
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white">{{ __('Security') }}</h3>
                            <p class="text-slate-600 dark:text-text-secondary leading-relaxed">
                                {{ __('Your data is yours alone. We employ enterprise-grade encryption and strict verification processes.') }}
                            </p>
                        </div>
                    </div>
                    <!-- Value 3 -->
                    <div class="group flex flex-col gap-6 p-8 rounded-2xl bg-white dark:bg-surface-dark border border-gray-200 dark:border-surface-border hover:border-primary/50 transition-all duration-300">
                        <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-surface-border/50 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-colors">
                            <span class="material-symbols-outlined">diversity_1</span>
                        </div>
                        <div class="space-y-3">
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white">{{ __('Empathy') }}</h3>
                            <p class="text-slate-600 dark:text-text-secondary leading-relaxed">
                                {{ __('Technology with a human touch. Our support team is available to guide you at every step of the journey.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- Team Section -->
            <section class="flex flex-col gap-12 py-8">
                <div class="flex flex-col md:flex-row justify-between items-end gap-6">
                    <div class="max-w-xl">
                        <h2 class="text-3xl lg:text-5xl font-bold mb-4 text-slate-900 dark:text-white" data-translate="Meet the Leadership">{{ __('Meet the Leadership') }}</h2>
                        <p class="text-slate-600 dark:text-text-secondary text-lg" data-translate="The minds dedicated to helping you find your heart.">{{ __('The minds dedicated to helping you find your heart.') }}</p>
                    </div>
                    <!-- <a class="hidden md:flex items-center gap-2 text-primary font-semibold hover:text-slate-900 dark:hover:text-white transition-colors" href="#">
                        <span data-translate="View all careers">{{ __('View all careers') }}</span> <span class="material-symbols-outlined">arrow_outward</span>
                    </a> -->
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Team Member 1 -->
                    <div class="flex flex-col gap-4 group">
                        <div class="relative overflow-hidden rounded-full aspect-square bg-gray-100 dark:bg-surface-dark border border-gray-200 dark:border-surface-border">
                            <img alt="Portrait of James Carter, CEO" class="w-full h-full object-cover filter grayscale group-hover:grayscale-0 transition-all duration-500 scale-100 group-hover:scale-110" data-alt="Professional headshot of man in suit" src="https://lh3.googleusercontent.com/aida-public/AB6AXuD6oN8y3ZdSDjLsSPG19FV3dkXYIXnqYs6_j8uaadOXoK7GtoNgTz36C-R5YHyxtB1XEiCd2Pr37yjd2fDDg2_M7lpd78OGo7SWxnhTE6Acvjb_UytgJZ_qDEJPB81Q1PiU8plTWPQ6v1PGCP6seUIiLwh_BJMCR_FNVJ74XkgcHhab16hhAIdy78S3IknNVpDxyWRb793HLCsF_oKrOUZbASmQQuGNGjn6fSsuQvvwwgaoSG0gKhLPbFkdTAi_p8RQY6os3W3E_ZA"/>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach($leadership ?? [] as $member)
                    <div class="flex flex-col gap-4 group">
                        <div class="relative overflow-hidden rounded-full aspect-square bg-gray-100 dark:bg-surface-dark border border-gray-200 dark:border-surface-border">
                            <img alt="Portrait of {{ $member['name'] }}, {{ $member['role'] }}" 
                                 class="w-full h-full object-cover filter grayscale group-hover:grayscale-0 transition-all duration-500 scale-100 group-hover:scale-110" 
                                 src="{{ $member['image'] }}"/>
                        </div>
                        <div class="text-center">
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __($member['name']) }}</h3>
                            <p class="text-primary text-sm font-medium">{{ __($member['role']) }}</p>
                            @if(isset($member['description']))
                                <p class="text-slate-500 dark:text-white/60 text-xs mt-1 line-clamp-2 px-4 italic">{{ __($member['description']) }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>
            
            <!-- CTA Section -->
            <section class="relative rounded-3xl bg-primary/10 border border-primary/20 p-8 lg:p-16 text-center overflow-hidden my-8">
                <div class="absolute -top-24 -left-24 w-64 h-64 bg-primary/20 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-24 -right-24 w-64 h-64 bg-primary/20 rounded-full blur-3xl"></div>
                <div class="relative z-10 flex flex-col items-center gap-6">
                    <h2 class="text-3xl lg:text-5xl font-bold text-slate-900 dark:text-white" data-translate="Ready for a serious commitment?">{{ __('Ready for a serious commitment?') }}</h2>
                    <p class="text-slate-600 dark:text-text-secondary max-w-2xl text-lg" data-translate="Join thousands of verified members who have found their life partners through our intelligent matchmaking.">{{ __('Join thousands of verified members who have found their life partners through our intelligent matchmaking.') }}</p>
                    @auth
                        <a href="{{ route('dashboard') }}" class="mt-4 flex min-w-[160px] cursor-pointer items-center justify-center overflow-hidden rounded-full h-14 px-8 bg-primary hover:bg-red-600 transition-colors text-white text-base font-bold tracking-wide shadow-lg shadow-primary/25">
                            <span data-translate="Go to Dashboard">{{ __('Go to Dashboard') }}</span>
                        </a>
                    @else
                        <a href="{{ route('signup') }}" class="mt-4 flex min-w-[160px] cursor-pointer items-center justify-center overflow-hidden rounded-full h-14 px-8 bg-primary hover:bg-red-600 transition-colors text-white text-base font-bold tracking-wide shadow-lg shadow-primary/25">
                            <span data-translate="Start Your Journey">{{ __('Start Your Journey') }}</span>
                        </a>
                    @endauth
                </div>
            </section>
        </main>
        
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
