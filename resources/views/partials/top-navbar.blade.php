<header class="sticky top-0 z-50 flex items-center justify-between border-b border-solid border-gray-200 dark:border-[#392b28]/50 bg-white/95 dark:bg-background-dark/90 backdrop-blur-md px-4 sm:px-6 py-4 lg:px-20 lg:py-6 transition-all duration-300">
    <div class="flex items-center gap-4">
        <a href="{{ route('home') }}" class="flex items-center gap-3 md:gap-5 transition-transform hover:scale-105">
            <div class="size-10 md:size-12 text-primary flex items-center justify-center relative">
                @if(isset($siteSettings->site_logo) && $siteSettings->site_logo)
                    @if(isset($siteSettings->site_logo_dark) && $siteSettings->site_logo_dark)
                        <img src="{{ asset('storage/' . $siteSettings->site_logo) }}" alt="Logo" class="h-12 w-auto object-contain transition-opacity duration-500 ease-in-out dark:opacity-0 absolute inset-0 m-auto">
                        <img src="{{ asset('storage/' . $siteSettings->site_logo_dark) }}" alt="Logo Dark" class="h-12 w-auto object-contain transition-opacity duration-500 ease-in-out opacity-0 dark:opacity-100 absolute inset-0 m-auto">
                    @else
                        <img src="{{ asset('storage/' . $siteSettings->site_logo) }}" alt="Logo" class="h-12 w-auto object-contain transition-all duration-300">
                    @endif
                @else
                    <span class="material-symbols-outlined text-4xl">diversity_1</span>
                @endif
            </div>
            
            @if(isset($siteSettings->site_name_type) && $siteSettings->site_name_type === 'image' && isset($siteSettings->site_name_image))
                <div class="relative h-10 min-w-[150px]">
                    @if(isset($siteSettings->site_name_image_dark) && $siteSettings->site_name_image_dark)
                        <img src="{{ asset('storage/' . $siteSettings->site_name_image) }}" alt="{{ $siteSettings->site_name ?? 'GoswamiSangath' }}" class="h-10 w-auto object-contain transition-opacity duration-500 ease-in-out dark:opacity-0 absolute inset-0">
                        <img src="{{ asset('storage/' . $siteSettings->site_name_image_dark) }}" alt="{{ $siteSettings->site_name ?? 'GoswamiSangath' }} Dark" class="h-10 w-auto object-contain transition-opacity duration-500 ease-in-out opacity-0 dark:opacity-100 absolute inset-0">
                    @else
                        <img src="{{ asset('storage/' . $siteSettings->site_name_image) }}" alt="{{ $siteSettings->site_name ?? 'GoswamiSangath' }}" class="h-10 w-auto object-contain transition-all duration-300">
                    @endif
                </div>
            @else
                <h1 class="text-slate-900 dark:text-white text-xl sm:text-2xl lg:text-3xl font-black leading-tight tracking-tight font-display transition-colors duration-500 ease-in-out">{{ $siteSettings->site_name ?? 'GoswamiSangath' }}</h1>
            @endif
        </a>
    </div>
    <div class="hidden lg:flex flex-1 justify-center gap-8">
        <nav class="flex items-center gap-9">
            <a class="text-slate-600 dark:text-white/80 hover:text-primary dark:hover:text-primary transition-colors text-sm font-semibold leading-normal min-w-[70px] text-center {{ request()->routeIs('home') ? 'text-slate-900 dark:text-white border-b-2 border-primary pb-0.5' : '' }}" href="{{ route('home') }}">{{ __('Home') }}</a>
            <!-- <a class="text-slate-600 dark:text-white/80 hover:text-primary dark:hover:text-primary transition-colors text-sm font-semibold leading-normal min-w-[80px] text-center {{ request()->routeIs('about') ? 'text-slate-900 dark:text-white border-b-2 border-primary pb-0.5' : '' }}" href="{{ route('about') }}">{{ __('About Us') }}</a>
            <a class="text-slate-600 dark:text-white/80 hover:text-primary dark:hover:text-primary transition-colors text-sm font-semibold leading-normal min-w-[120px] text-center {{ request()->routeIs('success-stories') ? 'text-slate-900 dark:text-white border-b-2 border-primary pb-0.5' : '' }}" href="{{ route('success-stories') }}">{{ __('Success Stories') }}</a> -->
            <a class="text-slate-600 dark:text-white/80 hover:text-primary dark:hover:text-primary transition-colors text-sm font-semibold leading-normal min-w-[90px] text-center {{ request()->routeIs('membership') ? 'text-slate-900 dark:text-white border-b-2 border-primary pb-0.5' : '' }}" href="{{ route('membership') }}">{{ __('Membership') }}</a>
        </nav>
    </div>
    <div class="flex items-center gap-2 sm:gap-4">
        {{-- Theme Toggle --}}
        <div class="theme-toggle-wrapper">
            @include('partials.theme-toggle')
        </div>
        
        {{-- Language Switcher Dropdown --}}
        <div class="relative hidden sm:block" x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 rounded-lg text-slate-700 dark:text-white/90 hover:text-primary dark:hover:text-white hover:bg-gray-100 dark:hover:bg-white/10 transition-colors">
                <span class="material-symbols-outlined text-lg">language</span>
                <span class="text-sm font-medium language-display uppercase">{{ strtoupper(app()->getLocale()) }}</span>
                <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 mt-2 w-36 rounded-lg shadow-xl z-20 bg-white dark:bg-[#271d1c] border border-gray-200 dark:border-[#392b28]" style="display: none;">
                <button type="button" onclick="console.log('Button clicked: English'); if(typeof window.switchLanguage === 'function') { console.log('Calling switchLanguage'); window.switchLanguage('en'); } else { console.error('switchLanguage not found, redirecting'); window.location.href='/language/en'; } return false;" class="w-full text-left block px-4 py-2 text-sm text-slate-900 dark:text-white hover:bg-gray-100 dark:hover:bg-[#392b28] hover:text-primary dark:hover:text-primary transition-colors">English</button>
                <button type="button" onclick="console.log('Button clicked: Hindi'); if(typeof window.switchLanguage === 'function') { console.log('Calling switchLanguage'); window.switchLanguage('hi'); } else { console.error('switchLanguage not found, redirecting'); window.location.href='/language/hi'; } return false;" class="w-full text-left block px-4 py-2 text-sm text-slate-900 dark:text-white hover:bg-gray-100 dark:hover:bg-[#392b28] hover:text-primary dark:hover:text-primary transition-colors">हिन्दी</button>
                <button type="button" onclick="console.log('Button clicked: Gujarati'); if(typeof window.switchLanguage === 'function') { console.log('Calling switchLanguage'); window.switchLanguage('gu'); } else { console.error('switchLanguage not found, redirecting'); window.location.href='/language/gu'; } return false;" class="w-full text-left block px-4 py-2 text-sm text-slate-900 dark:text-white hover:bg-gray-100 dark:hover:bg-[#392b28] hover:text-primary dark:hover:text-primary transition-colors">ગુજરાતી</button>
            </div>
        </div>
        
        @auth
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 transition-transform hover:scale-110">
                <div class="size-10 rounded-full bg-cover bg-center border-2 border-primary shadow-lg shadow-primary/20" style="background-image: url('{{ Auth::user()->profile_image ? asset('storage/' . Auth::user()->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->full_name) . '&background=ec3713&color=fff' }}');"></div>
            </a>
        @else
            @if(isset($siteSettings->demo_mode) && filter_var($siteSettings->demo_mode, FILTER_VALIDATE_BOOLEAN))
                <a href="{{ route('signup') }}" class="group relative flex cursor-pointer items-center justify-center overflow-hidden rounded-full h-10 px-5 sm:h-12 sm:px-8 bg-primary hover:bg-primary-dark transition-all duration-300 shadow-lg shadow-primary/25 text-white text-xs sm:text-sm font-black tracking-wider uppercase">
                    <span class="relative z-10">{{ __('Register') }}</span>
                    <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                </a>
            @else
                <a href="{{ route('login') }}" class="group relative flex cursor-pointer items-center justify-center overflow-hidden rounded-full h-10 px-5 sm:h-12 sm:px-8 bg-primary hover:bg-primary-dark transition-all duration-300 shadow-lg shadow-primary/25 text-white text-xs sm:text-sm font-black tracking-wider uppercase">
                    <span class="relative z-10">{{ __('Login') }}</span>
                    <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                </a>
            @endif
        @endauth
    </div>
</header>

{{-- Add Alpine.js for dropdown functionality --}}
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
{{-- Language Switcher Script - Loaded in main layout to avoid duplicates --}}

