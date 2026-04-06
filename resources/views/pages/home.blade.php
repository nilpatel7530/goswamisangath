<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('GoswamiSangath - Genuine Matrimony for the Goswami Community') }}</title>
    <!-- SEO Meta Tags -->
    <meta name="description" content="Find your perfect life partner on GoswamiSangath, the #1 trusted matrimony platform for the Goswami community. Secure, verified profiles, and seamless matchmaking.">
    <meta name="keywords" content="Goswami matrimony, Goswami samaj, matrimony for Goswami, Goswami sangath, find Goswami bride, find Goswami groom, Indian matrimony">
    <link rel="canonical" href="{{ url()->current() }}">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="GoswamiSangath - Genuine Matrimony for the Goswami Community">
    <meta property="og:description" content="Find your perfect life partner on GoswamiSangath, the #1 trusted matrimony platform for the Goswami community.">
    <meta property="og:image" content="{{ asset('favicon.png') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="GoswamiSangath - Genuine Matrimony for the Goswami Community">
    <meta property="twitter:description" content="Find your perfect life partner on GoswamiSangath, the #1 trusted matrimony platform for the Goswami community.">
    <meta property="twitter:image" content="{{ asset('favicon.png') }}">

    <!-- Global Theme System -->
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800&display=swap" rel="stylesheet"/>
    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <!-- Theme Configuration -->
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
                    },
                    fontFamily: {
                        "display": ["Plus Jakarta Sans", "sans-serif"],
                        "body": ["Plus Jakarta Sans", "sans-serif"],
                    },
                    borderRadius: {"DEFAULT": "1rem", "lg": "1.5rem", "xl": "2rem", "2xl": "3rem", "full": "9999px"},
                },
            },
        }
    </script>
    <style>
        /* Custom scrollbar - theme aware */
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
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        /* Float animation for overlays */
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .animate-float {
            animation: float 4s ease-in-out infinite;
        }
        /* Mobile menu transitions */
        .mobile-menu-enter {
            transform: translateX(100%);
        }
        .mobile-menu-active {
            transform: translateX(0);
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-white font-display overflow-x-hidden antialiased selection:bg-primary selection:text-white">
    <div class="flex flex-col min-h-screen">
        @include('partials.top-navbar')
        
        <!-- Main Content -->
        <main class="flex-1 flex flex-col">
    <!-- Hero Section -->
    <section class="relative min-h-[85vh] md:min-h-[95vh] flex items-center justify-center overflow-hidden bg-[#f8f6f6] dark:bg-[#181211]">
        <!-- Background Imagery -->
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/landing/hero.png') }}" alt="Premium Indian Couple" class="w-full h-full object-cover object-center opacity-90 dark:opacity-60 transition-opacity duration-1000">
            <div class="absolute inset-0 bg-gradient-to-r from-[#f8f6f6] via-[#f8f6f6]/90 to-[#f8f6f6]/40 dark:from-[#181211] dark:via-[#181211]/90 dark:to-[#181211]/40 md:from-[#f8f6f6] md:via-[#f8f6f6]/80 md:to-transparent md:dark:from-[#181211] md:dark:via-[#181211]/80 md:dark:to-transparent"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-[#f8f6f6] dark:from-[#181211] via-transparent to-transparent"></div>
        </div>

        <div class="max-w-[1440px] w-full px-4 sm:px-6 md:px-10 relative z-10 pt-8 md:pt-0">
            <div class="max-w-3xl flex flex-col gap-5 md:gap-8">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full border border-primary/20 bg-primary/5 w-fit backdrop-blur-sm">
                    <span class="size-2 rounded-full bg-primary animate-pulse"></span>
                    <span class="text-[10px] md:text-xs font-bold text-primary uppercase tracking-wider">{{ __('#1 Trusted Platform for Genuine Matches') }}</span>
                </div>
                
                <h1 class="text-4xl sm:text-5xl md:text-7xl lg:text-[6.5rem] font-black leading-[0.9] tracking-tighter text-[#1f2937] dark:text-white">
                    {{ __('A Trusted Matrimonial Platform for Our') }} <br/>
                    <span class="text-primary italic">{{ __('Community') }}</span> 
                </h1>
                
                <p class="text-base sm:text-lg md:text-2xl text-[#4b5563] dark:text-[#b9a19d] max-w-xl font-medium leading-relaxed">
                    {{ __('Where young individuals can find their ideal life partners with ease and respect.') }}
                </p>

                <!-- Search Bar Container -->
                <div class="mt-6 max-w-3xl">
                    <form action="{{ route('signup') }}" method="GET" class="flex flex-col gap-0">
                        <input type="hidden" name="ageFrom" id="ageFrom" value="26">
                        <input type="hidden" name="ageTo" id="ageTo" value="30">
                        
                        <div class="bg-white/95 dark:bg-[#271d1c]/95 backdrop-blur-xl border border-slate-200/80 dark:border-[#392b28] rounded-[1.75rem] shadow-[0_25px_60px_rgba(0,0,0,0.08)] dark:shadow-[0_25px_60px_rgba(0,0,0,0.4)] overflow-hidden">
                            <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-slate-100 dark:divide-[#392b28]/60">
                                <!-- I am a -->
                                <div class="group px-6 py-5 hover:bg-slate-50/80 dark:hover:bg-[#2f2220] transition-colors duration-200 cursor-pointer">
                                    <label class="flex items-center gap-2 mb-2">
                                        <span class="material-symbols-outlined text-primary text-[18px]">person</span>
                                        <span class="text-[11px] uppercase text-slate-400 dark:text-[#8b7a77] font-black tracking-[0.15em]">{{ __('I am a') }}</span>
                                    </label>
                                    <select name="genderPref" class="bg-transparent border-none p-0 text-[#1f2937] dark:text-white font-bold focus:ring-0 text-[15px] w-full cursor-pointer appearance-none" style="background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23ec3713' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E&quot;); background-repeat: no-repeat; background-position: right 0 center; background-size: 16px; padding-right: 24px;">
                                        <option value="female">{{ __('Man looking for Woman') }}</option>
                                        <option value="male">{{ __('Woman looking for Man') }}</option>
                                    </select>
                                </div>
                                <!-- Age -->
                                <div class="group px-6 py-5 hover:bg-slate-50/80 dark:hover:bg-[#2f2220] transition-colors duration-200 cursor-pointer">
                                    <label class="flex items-center gap-2 mb-2">
                                        <span class="material-symbols-outlined text-primary text-[18px]">calendar_month</span>
                                        <span class="text-[11px] uppercase text-slate-400 dark:text-[#8b7a77] font-black tracking-[0.15em]">{{ __('Aged Between') }}</span>
                                    </label>
                                    <select id="age_range_select" class="bg-transparent border-none p-0 text-[#1f2937] dark:text-white font-bold focus:ring-0 text-[15px] w-full cursor-pointer appearance-none" style="background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23ec3713' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E&quot;); background-repeat: no-repeat; background-position: right 0 center; background-size: 16px; padding-right: 24px;">
                                        <option value="18-22">18 - 22 {{ __('Years') }}</option>
                                        <option value="23-25">23 - 25 {{ __('Years') }}</option>
                                        <option value="26-30" selected>26 - 30 {{ __('Years') }}</option>
                                        <option value="31-35">31 - 35 {{ __('Years') }}</option>
                                        <option value="36-40">36 - 40 {{ __('Years') }}</option>
                                        <option value="41-50">41 - 50 {{ __('Years') }}</option>
                                    </select>
                                </div>

                            </div>
                        </div>
                        
                        <!-- Search Button — Below the card -->
                        <div class="flex justify-center mt-5">
                            <button type="submit" class="group px-10 h-14 bg-primary rounded-full flex items-center justify-center text-white hover:bg-[#d42e0f] transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-primary/30 active:scale-95 font-bold gap-3 text-base">
                                <span class="material-symbols-outlined text-[22px] group-hover:rotate-12 transition-transform">search</span>
                                <span>{{ __('Search Now') }}</span>
                                <span class="material-symbols-outlined text-[18px] opacity-60 group-hover:translate-x-1 transition-transform">arrow_forward</span>
                            </button>
                        </div>
                    </form>
                </div>

                <div class="h-6"></div> <!-- Spacer to maintain layout balance -->
            </div>
        </div>
    </section>

            <!-- Trust Section -->
            <section class="py-16 md:py-32 px-4 sm:px-6 md:px-10 bg-white dark:bg-background-dark/30 border-y border-gray-100 dark:border-border-dark/50 flex items-center">
                <div class="max-w-[1440px] mx-auto grid grid-cols-1 lg:grid-cols-2 gap-10 md:gap-16 items-center">
                    <div class="relative rounded-2xl md:rounded-3xl overflow-hidden shadow-2xl group">
                        <img src="{{ asset('images/landing/trust.png') }}" alt="Trusted Indian Family" class="w-full h-auto min-h-[250px] object-cover group-hover:scale-105 transition-transform duration-1000">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <div class="absolute bottom-6 left-6 md:bottom-10 md:left-10">
                            <p class="text-3xl md:text-4xl font-black text-white mb-1 md:mb-2">5M+</p>
                            <p class="text-xs md:text-sm font-bold text-white/80 uppercase tracking-widest">{{ __('Verified Families') }}</p>
                        </div>
                    </div>
                    
                    <div class="flex flex-col gap-10">
                        <div>
                            <h2 class="text-3xl sm:text-4xl md:text-6xl font-black text-[#1f2937] dark:text-white leading-tight mb-4 md:mb-6">
                                {{ __('Built on Trust.') }} <br/>
                                <span class="text-primary">{{ __('Powered by Community') }}</span> 🤝
                            </h2>
                            <p class="text-base md:text-xl text-[#4b5563] dark:text-[#b9a19d] leading-relaxed font-medium mb-3 md:mb-4">
                                {{ __('Created by a genuine member of the community who understands its values and traditions.') }}
                            </p>
                            <p class="text-base md:text-xl text-[#4b5563] dark:text-[#b9a19d] leading-relaxed font-medium mb-4 md:mb-6">
                                {{ __('Your data is सुरक्षित, private, and never misused.') }}
                            </p>
                            <p class="text-xl md:text-2xl font-black text-[#1f2937] dark:text-white">
                                💙 {{ __('Your trust is our responsibility.') }}
                            </p>
                        </div>

                        <div class="grid grid-cols-2 gap-4 md:gap-8">
                            <div class="flex flex-col gap-1 md:gap-2">
                                <h4 class="text-2xl md:text-3xl font-black text-primary">2M+</h4>
                                <p class="text-[10px] md:text-xs font-bold text-[#6b7280] uppercase tracking-wider">{{ __('Matches Made') }}</p>
                            </div>
                            <div class="flex flex-col gap-1 md:gap-2">
                                <h4 class="text-2xl md:text-3xl font-black text-primary">15+ {{ __('Years') }}</h4>
                                <p class="text-[10px] md:text-xs font-bold text-[#6b7280] uppercase tracking-wider">{{ __('Legacy of Trust') }}</p>
                            </div>
                            <div class="flex flex-col gap-1 md:gap-2">
                                <h4 class="text-2xl md:text-3xl font-black text-primary">100%</h4>
                                <p class="text-[10px] md:text-xs font-bold text-[#6b7280] uppercase tracking-wider">{{ __('Privacy Control') }}</p>
                            </div>
                            <div class="flex flex-col gap-1 md:gap-2">
                                <h4 class="text-2xl md:text-3xl font-black text-primary">24/7</h4>
                                <p class="text-[10px] md:text-xs font-bold text-[#6b7280] uppercase tracking-wider">{{ __('Support') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>            <!-- Benefits Section -->
            <section class="py-16 md:py-32 px-4 sm:px-6 md:px-10 bg-[#f8f6f6] dark:bg-[#181211]">
                <div class="max-w-[1440px] mx-auto grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-24 items-center">
                    
                    <div>
                        <span class="text-primary font-black uppercase tracking-[0.2em] text-xs md:text-sm mb-3 md:mb-4 block">{{ __('The Benefits') }}</span>
                        <h2 class="text-3xl sm:text-4xl md:text-6xl lg:text-7xl font-black text-[#1f2937] dark:text-white tracking-tighter transition-colors mb-6 md:mb-10">{{ __('Why We Are Different') }}</h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-6">
                            <div class="flex items-center gap-4 p-5 bg-white dark:bg-[#271d1c] rounded-2xl border border-slate-100 dark:border-[#392b28] shadow-sm hover:shadow-md transition-all">
                                <div class="size-10 rounded-full bg-green-50 dark:bg-green-900/20 flex items-center justify-center text-green-600 dark:text-green-400 shrink-0">
                                    <span class="material-symbols-outlined text-xl">check</span>
                                </div>
                                <h3 class="text-lg font-bold text-[#1f2937] dark:text-white">{{ __('Community-focused matches') }}</h3>
                            </div>
                            <div class="flex items-center gap-4 p-5 bg-white dark:bg-[#271d1c] rounded-2xl border border-slate-100 dark:border-[#392b28] shadow-sm hover:shadow-md transition-all">
                                <div class="size-10 rounded-full bg-green-50 dark:bg-green-900/20 flex items-center justify-center text-green-600 dark:text-green-400 shrink-0">
                                    <span class="material-symbols-outlined text-xl">check</span>
                                </div>
                                <h3 class="text-lg font-bold text-[#1f2937] dark:text-white">{{ __('Smart search & filters') }}</h3>
                            </div>
                            <div class="flex items-center gap-4 p-5 bg-white dark:bg-[#271d1c] rounded-2xl border border-slate-100 dark:border-[#392b28] shadow-sm hover:shadow-md transition-all">
                                <div class="size-10 rounded-full bg-green-50 dark:bg-green-900/20 flex items-center justify-center text-green-600 dark:text-green-400 shrink-0">
                                    <span class="material-symbols-outlined text-xl">check</span>
                                </div>
                                <h3 class="text-lg font-bold text-[#1f2937] dark:text-white">{{ __('Verified & genuine profiles') }}</h3>
                            </div>
                            <div class="flex items-center gap-4 p-5 bg-white dark:bg-[#271d1c] rounded-2xl border border-slate-100 dark:border-[#392b28] shadow-sm hover:shadow-md transition-all">
                                <div class="size-10 rounded-full bg-green-50 dark:bg-green-900/20 flex items-center justify-center text-green-600 dark:text-green-400 shrink-0">
                                    <span class="material-symbols-outlined text-xl">check</span>
                                </div>
                                <h3 class="text-lg font-bold text-[#1f2937] dark:text-white">{{ __('Secure & private platform') }}</h3>
                            </div>
                            <div class="flex items-center gap-4 p-5 bg-white dark:bg-[#271d1c] rounded-2xl border border-slate-100 dark:border-[#392b28] shadow-sm hover:shadow-md transition-all">
                                <div class="size-10 rounded-full bg-green-50 dark:bg-green-900/20 flex items-center justify-center text-green-600 dark:text-green-400 shrink-0">
                                    <span class="material-symbols-outlined text-xl">check</span>
                                </div>
                                <h3 class="text-lg font-bold text-[#1f2937] dark:text-white">{{ __('Easy to use') }}</h3>
                            </div>
                            <div class="flex items-center gap-4 p-5 bg-white dark:bg-[#271d1c] rounded-2xl border border-slate-100 dark:border-[#392b28] shadow-sm hover:shadow-md transition-all">
                                <div class="size-10 rounded-full bg-green-50 dark:bg-green-900/20 flex items-center justify-center text-green-600 dark:text-green-400 shrink-0">
                                    <span class="material-symbols-outlined text-xl">check</span>
                                </div>
                                <h3 class="text-lg font-bold text-[#1f2937] dark:text-white">{{ __('Built by your community') }}</h3>
                            </div>
                        </div>
                        
                        <div class="mt-12">
                            <p class="text-2xl font-black text-[#1f2937] dark:text-white">
                                💙 {{ __('A simple, safe, and meaningful experience.') }}
                            </p>
                        </div>
                    </div>

                    <!-- Image Column -->
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-tr from-primary/20 to-transparent blur-3xl rounded-full scale-90"></div>
                        <div class="relative z-10 rounded-[2rem] md:rounded-[3rem] overflow-hidden border-4 md:border-8 border-white dark:border-surface-dark shadow-[0_20px_50px_rgba(0,0,0,0.1)] dark:shadow-[0_20px_50px_rgba(0,0,0,0.5)] transform hover:-translate-y-2 transition-transform duration-500">
                            <!-- User's uploaded image -->
                            <img src="{{ asset('images/landing/benefits_app.jpg') }}" alt="GoswamiSangath App on Google Play" class="w-full h-auto object-cover">
                        </div>
                    </div>

                </div>
            </section>

            <!-- Key Features Section -->
            <section class="py-16 md:py-32 px-4 sm:px-6 md:px-10 overflow-hidden relative bg-white dark:bg-background-dark">
                <!-- Decorative Elements -->
                <div class="absolute top-0 right-0 w-full h-px bg-gradient-to-r from-transparent via-gray-200 dark:via-border-dark to-transparent"></div>
                <div class="absolute -top-40 -right-40 w-96 h-96 bg-primary/5 rounded-full blur-[100px] pointer-events-none"></div>
                <div class="absolute top-1/2 left-0 w-72 h-72 bg-blue-500/5 rounded-full blur-[80px] pointer-events-none"></div>

                <div class="max-w-[1440px] mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16 lg:gap-24 items-center">
                    
                    <!-- Text Content Phase -->
                    <div class="flex flex-col gap-8 order-2 lg:order-1">
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary/10 border border-primary/20 w-fit">
                            <span class="material-symbols-outlined text-primary text-sm">bolt</span>
                            <span class="text-primary font-bold uppercase tracking-wider text-xs">{{ __('Smart Sharing') }}</span>
                        </div>
                        
                        <h2 class="text-3xl sm:text-4xl md:text-6xl lg:text-7xl font-black text-[#1f2937] dark:text-white tracking-tighter leading-[1.1]">
                            {{ __('No more sharing biodata manually') }} <span class="text-primary">📄</span>
                        </h2>
                        
                        <p class="text-base md:text-xl lg:text-2xl text-[#4b5563] dark:text-[#b9a19d] leading-relaxed font-medium">
                            {{ __('Just share your unique profile link.') }} <br class="hidden md:block"/>
                            {{ __('Your complete, verified details are beautifully organized and available in one secure place.') }}
                        </p>

                        <div class="flex flex-col sm:flex-row gap-4 mt-4">
                            <div class="flex items-center gap-3 p-4 rounded-2xl bg-gray-50 dark:bg-[#271d1c] border border-gray-100 dark:border-[#392b28]">
                                <div class="size-10 rounded-full bg-white dark:bg-surface-dark shadow-sm flex items-center justify-center text-primary">
                                    <span class="material-symbols-outlined">link</span>
                                </div>
                                <span class="font-bold text-[#1f2937] dark:text-white">{{ __('One-Click Link') }}</span>
                            </div>
                            <div class="flex items-center gap-3 p-4 rounded-2xl bg-gray-50 dark:bg-[#271d1c] border border-gray-100 dark:border-[#392b28]">
                                <div class="size-10 rounded-full bg-white dark:bg-surface-dark shadow-sm flex items-center justify-center text-primary">
                                    <span class="material-symbols-outlined">lock</span>
                                </div>
                                <span class="font-bold text-[#1f2937] dark:text-white">{{ __('Privacy First') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Image/Mockup Phase -->
                    <div class="relative order-1 lg:order-2">
                        <!-- Background Glow -->
                        <div class="absolute inset-0 bg-gradient-to-tr from-primary/20 to-transparent blur-3xl rounded-full scale-90"></div>
                        
                        <div class="relative z-10 rounded-[2rem] md:rounded-[3rem] overflow-hidden border-4 md:border-8 border-white dark:border-surface-dark shadow-[0_20px_50px_rgba(0,0,0,0.1)] dark:shadow-[0_20px_50px_rgba(0,0,0,0.5)] transform hover:-translate-y-2 transition-transform duration-500">
                            <!-- Premium Mockup Image generated for Biodata -->
                            <img src="{{ asset('images/landing/features_biodata.png') }}" alt="GoswamiSangath Digital Biodata Mockup" class="w-full h-auto object-cover">
                            
                            <!-- Floating Trust Overlay -->
                            <div class="absolute bottom-4 left-2 sm:bottom-8 sm:left-[-20px] md:left-[-40px] p-3 sm:p-4 md:p-6 bg-white/90 dark:bg-surface-dark/90 backdrop-blur-md rounded-2xl md:rounded-3xl shadow-2xl border border-white/50 dark:border-border-dark animate-float z-20 max-w-[calc(100%-1rem)] sm:max-w-none">
                                <div class="flex items-center gap-4">
                                    <div class="size-12 rounded-full bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 flex items-center justify-center shrink-0">
                                        <span class="material-symbols-outlined animate-pulse">verified_user</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-[#1f2937] dark:text-white uppercase tracking-wider">{{ __('Verified Profile') }}</p>
                                        <p class="text-xs font-medium text-[#6b7280] dark:text-gray-400">{{ __('100% Secure Data') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </section>
            
            <!-- Why Choose Us -->
            <section class="py-16 md:py-32 px-4 sm:px-6 md:px-10 bg-slate-50/50 dark:bg-[#181211] overflow-hidden relative transition-colors duration-500">
                <div class="absolute top-0 left-0 w-full h-full opacity-5 dark:opacity-10 pointer-events-none">
                    <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-primary rounded-full blur-[150px]"></div>
                    <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-blue-500 rounded-full blur-[150px]"></div>
                </div>
                
                <div class="max-w-[1440px] mx-auto relative z-10">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
                        <div>
                            <span class="text-primary font-black uppercase tracking-[0.2em] text-sm mb-4 block">{{ __('Why Choose Us') }}</span>
                            <h2 class="text-3xl sm:text-5xl md:text-7xl font-black mb-6 md:mb-10 tracking-tighter leading-tight text-[#1f2937] dark:text-white">{{ __('Your Happiness,') }} <br/> {{ __('Our Mission.') }}</h2>
                            
                            <div class="flex flex-col gap-6">
                                <div class="flex items-center gap-4 group">
                                    <div class="size-10 rounded-full bg-primary/10 dark:bg-white/10 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-all duration-300 shadow-sm shrink-0">
                                        <span class="material-symbols-outlined text-sm">check</span>
                                    </div>
                                    <h4 class="text-xl font-bold text-[#1f2937] dark:text-white">{{ __('Built by a trusted community member') }}</h4>
                                </div>
                                <div class="flex items-center gap-4 group">
                                    <div class="size-10 rounded-full bg-primary/10 dark:bg-white/10 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-all duration-300 shadow-sm shrink-0">
                                        <span class="material-symbols-outlined text-sm">check</span>
                                    </div>
                                    <h4 class="text-xl font-bold text-[#1f2937] dark:text-white">{{ __('Genuine profiles only') }}</h4>
                                </div>
                                <div class="flex items-center gap-4 group">
                                    <div class="size-10 rounded-full bg-primary/10 dark:bg-white/10 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-all duration-300 shadow-sm shrink-0">
                                        <span class="material-symbols-outlined text-sm">check</span>
                                    </div>
                                    <h4 class="text-xl font-bold text-[#1f2937] dark:text-white">{{ __('Privacy-first approach') }}</h4>
                                </div>
                                <div class="flex items-center gap-4 group">
                                    <div class="size-10 rounded-full bg-primary/10 dark:bg-white/10 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-all duration-300 shadow-sm shrink-0">
                                        <span class="material-symbols-outlined text-sm">check</span>
                                    </div>
                                    <h4 class="text-xl font-bold text-[#1f2937] dark:text-white">{{ __('Easy for individuals & families') }}</h4>
                                </div>
                                <div class="flex items-center gap-4 group">
                                    <div class="size-10 rounded-full bg-primary/10 dark:bg-white/10 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-all duration-300 shadow-sm shrink-0">
                                        <span class="material-symbols-outlined text-sm">check</span>
                                    </div>
                                    <h4 class="text-xl font-bold text-[#1f2937] dark:text-white">{{ __('Strengthening community connections') }}</h4>
                                </div>
                            </div>
                        </div>

                        <div class="relative h-[300px] sm:h-[400px] lg:h-[600px] flex items-center justify-center">
                            <div class="absolute inset-0 border-[10px] md:border-[20px] border-primary/5 dark:border-white/5 rounded-full scale-75 animate-pulse"></div>
                            <div class="absolute inset-0 border border-primary/10 dark:border-white/10 rounded-full scale-110 hidden sm:block"></div>
                            <div class="relative z-10 w-[85%] sm:w-4/5 aspect-square rounded-[2rem] md:rounded-[4rem] overflow-hidden shadow-2xl border-4 border-white/20 dark:border-white/10">
                                <img src="{{ asset('images/landing/why_us.png') }}" alt="Why Choose TrueUnion" class="w-full h-full object-cover transform hover:scale-105 transition-transform duration-700">
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- Roadmap Section -->
            <section class="py-16 md:py-32 px-4 sm:px-6 md:px-10 bg-white dark:bg-background-dark/30">
                <div class="max-w-[1440px] mx-auto">
                    <div class="flex flex-col lg:flex-row gap-12 md:gap-20 items-center">
                        <div class="lg:w-1/2 relative">
                            <img src="{{ asset('images/landing/roadmap.png') }}" alt="Platform Roadmap" class="w-full h-auto rounded-[2rem] md:rounded-[3rem] shadow-2xl skew-y-2 hover:skew-y-0 transition-transform duration-1000">
                            <div class="absolute -top-10 -left-10 size-40 bg-primary/20 rounded-full blur-3xl hidden md:block"></div>
                            
                            <!-- Floating Trust Overlay -->
                            <div class="absolute bottom-4 right-2 sm:bottom-8 sm:right-[-20px] md:right-[-40px] p-3 sm:p-4 md:p-6 bg-white/95 dark:bg-surface-dark/95 backdrop-blur-md rounded-2xl md:rounded-3xl shadow-2xl border border-white/50 dark:border-border-dark animate-float z-20 max-w-[calc(100%-1rem)] sm:max-w-none">
                                <div class="flex items-center gap-4">
                                    <div class="size-12 rounded-full bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 flex items-center justify-center shrink-0">
                                        <span class="material-symbols-outlined animate-pulse">hotel_class</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-[#1f2937] dark:text-white uppercase tracking-wider">{{ __('Quality First') }}</p>
                                        <p class="text-xs font-medium text-[#6b7280] dark:text-gray-400">{{ __('Built on Trust') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="lg:w-1/2 flex flex-col gap-12">
                            <div>
                                <span class="text-primary font-black uppercase tracking-[0.2em] text-sm mb-4 block">{{ __('Roadmap') }} 🚀</span>
                                <h2 class="text-3xl sm:text-5xl md:text-7xl font-black text-[#1f2937] dark:text-white tracking-tighter leading-tight">{{ __('Our Path to') }} <br/> <span class="text-primary italic">{{ __('Launch') }}</span></h2>
                                <p class="text-base md:text-xl text-[#4b5563] dark:text-[#b9a19d] mt-4 md:mt-6 font-medium">✨ {{ __('Focused on quality and trust.') }}</p>
                            </div>

                            <div class="flex flex-col gap-8">
                                <div class="flex gap-8 group">
                                    <div class="flex flex-col items-center">
                                        <div class="size-14 rounded-2xl bg-slate-100 dark:bg-[#271d1c] flex items-center justify-center text-2xl font-black group-hover:bg-primary group-hover:text-white transition-colors duration-300 shadow-sm shadow-primary/10">📝</div>
                                        <div class="w-px h-full bg-gradient-to-b from-primary/50 to-transparent my-4"></div>
                                    </div>
                                    <div class="pt-2">
                                        <h4 class="text-2xl font-black text-[#1f2937] dark:text-white mb-2">{{ __('Pre-Registration') }}</h4>
                                        <p class="text-lg text-primary font-bold">{{ __('First 15 Days') }}</p>
                                        <p class="text-[#4b5563] dark:text-[#b9a19d] mt-2 leading-relaxed">{{ __('Early access for founding members. Secure your premium username and early-bird benefits.') }}</p>
                                    </div>
                                </div>
                                <div class="flex gap-8 group">
                                    <div class="flex flex-col items-center">
                                        <div class="size-14 rounded-2xl bg-slate-100 dark:bg-[#271d1c] flex items-center justify-center text-2xl font-black group-hover:bg-primary group-hover:text-white transition-colors duration-300 shadow-sm shadow-primary/10">🔍</div>
                                        <div class="w-px h-full bg-gradient-to-b from-primary/50 to-transparent my-4"></div>
                                    </div>
                                    <div class="pt-2">
                                        <h4 class="text-2xl font-black text-[#1f2937] dark:text-white mb-2">{{ __('Verification Phase') }}</h4>
                                        <p class="text-lg text-primary font-bold">{{ __('Next 10 Days') }}</p>
                                        <p class="text-[#4b5563] dark:text-[#b9a19d] mt-2 leading-relaxed">{{ __('Our team manually verifies all early profiles to ensure a high-quality, authentic community base.') }}</p>
                                    </div>
                                </div>
                                <div class="flex gap-8 group mt-2">
                                    <div class="flex flex-col items-center">
                                        <div class="size-14 rounded-2xl bg-primary/10 dark:bg-primary/20 border-2 border-primary border-dashed flex items-center justify-center text-2xl font-black text-primary animate-pulse shadow-lg shadow-primary/20">🎉</div>
                                    </div>
                                    <div class="pt-2">
                                        <h4 class="text-3xl font-black text-[#1f2937] dark:text-white mb-2">{{ __('Official Launch') }}</h4>
                                        @php
                                            $launchDateDisplay = '31st March';
                                            if (isset($siteSettings->live_at) && !empty($siteSettings->live_at)) {
                                                try {
                                                    $launchDateDisplay = \Carbon\Carbon::parse($siteSettings->live_at)->format('jS F');
                                                } catch (\Exception $e) { }
                                            }
                                        @endphp
                                        <p class="text-xl text-primary font-black uppercase tracking-wider">{{ __($launchDateDisplay) }}</p>
                                        <p class="text-[#4b5563] dark:text-[#b9a19d] mt-2 leading-relaxed">{{ __('Platform opens globally. Start connecting with verified matches.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- CTA Section -->
            <section class="py-20 md:py-32 px-4 sm:px-6 relative overflow-hidden bg-[#181211]">
                <div class="absolute inset-0 opacity-20 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-primary via-transparent to-transparent pointer-events-none"></div>
                <div class="relative max-w-5xl mx-auto text-center flex flex-col items-center gap-8 md:gap-12">
                    <h2 class="text-4xl sm:text-5xl md:text-8xl font-black text-white leading-none tracking-tighter">
                        {{ __('Find Your') }} <br/>
                        <span class="text-primary italic">{{ __('Eternal') }}</span> {{ __('Union Today.') }}
                    </h2>
                    <p class="text-base md:text-xl text-[#b9a19d] max-w-2xl font-medium leading-relaxed">
                        {{ __('Join millions of verified families who found their perfect match. Your journey towards a lifetime of happiness starts here.') }}
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-6 w-full sm:w-auto">
                        @auth
                            <a href="{{ route('dashboard') }}" class="group relative px-10 py-5 bg-primary rounded-full font-black text-xl text-white overflow-hidden transition-all hover:scale-105 active:scale-95 shadow-2xl shadow-primary/30">
                                <span class="relative z-10">{{ __('Go to Dashboard') }}</span>
                                <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-500"></div>
                            </a>
                        @else
                            <a href="{{ route('signup') }}" class="group relative px-10 py-5 bg-primary rounded-full font-black text-xl text-white overflow-hidden transition-all hover:scale-105 active:scale-95 shadow-2xl shadow-primary/30">
                                <span class="relative z-10">{{ __('Begin Your Journey') }}</span>
                                <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-500"></div>
                            </a>
                        @endauth
                        <!-- <a href="{{ route('success-stories') }}" class="px-10 py-5 rounded-full font-black text-xl text-white border-2 border-white/20 hover:bg-white hover:text-[#181211] transition-all">
                            {{ __('View Success Stories') }}
                        </a> -->
                    </div>
                </div>
            </section>
        </main>
        
        @include('partials.footer')
</div>
    
    <!-- Global Theme Manager -->
    <script src="{{ asset('js/theme.js') }}"></script>
    <!-- Language Switcher - Load BEFORE Alpine.js to ensure function is available -->
    <script src="{{ asset('js/language-switcher.js') }}"></script>
    <!-- Alpine.js for dropdown functionality -->
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <script>
        // Verify function is available immediately and after load
        (function checkFunction() {
            if (typeof window.switchLanguage === 'function') {
                console.log('✓ switchLanguage function is available');
            } else {
                console.warn('⚠ switchLanguage function not yet available, will check again...');
                setTimeout(checkFunction, 100);
            }
        })();
        
        window.addEventListener('load', function() {
            if (typeof window.switchLanguage === 'function') {
                console.log('✓ switchLanguage function confirmed available after page load');
            } else {
                console.error('✗ switchLanguage function NOT available after page load!');
            }
        });
    </script>

    @if(isset($siteSettings->demo_mode) && filter_var($siteSettings->demo_mode, FILTER_VALIDATE_BOOLEAN))
        <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
        <script>
            window.addEventListener('load', function() {
                var duration = 5 * 1000;
                var animationEnd = Date.now() + duration;
                var defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 1000 };

                function randomInRange(min, max) {
                  return Math.random() * (max - min) + min;
                }

                var interval = setInterval(function() {
                  var timeLeft = animationEnd - Date.now();

                  if (timeLeft <= 0) {
                    return clearInterval(interval);
                  }

                  var particleCount = 50 * (timeLeft / duration);
                  // since particles fall down, start a bit higher than random
                  confetti(Object.assign({}, defaults, { particleCount, origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 }, colors: ['#ec3713', '#ffffff', '#ffd700'] }));
                  confetti(Object.assign({}, defaults, { particleCount, origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 }, colors: ['#ec3713', '#ffffff', '#ffd700'] }));
                }, 250);
            });
        </script>
    @endif
</body>
</html>
