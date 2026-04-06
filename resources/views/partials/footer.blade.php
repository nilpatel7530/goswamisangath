<footer class="bg-white dark:bg-surface-dark border-t border-gray-200 dark:border-[#392b28] pt-16 pb-8 px-4 md:px-10 mt-auto">
    <div class="max-w-[1440px] mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
            <div class="flex flex-col gap-6 text-left">
                <div class="flex items-center gap-5 transition-transform hover:scale-105 origin-left">
                    <div class="size-10 text-primary flex items-center justify-center relative">
                        @if(isset($siteSettings->site_logo) && $siteSettings->site_logo)
                            @if(isset($siteSettings->site_logo_dark) && $siteSettings->site_logo_dark)
                                <img src="{{ asset('storage/' . $siteSettings->site_logo) }}" alt="Logo" class="h-10 w-auto object-contain transition-opacity duration-500 ease-in-out dark:opacity-0 absolute inset-0 m-auto">
                                <img src="{{ asset('storage/' . $siteSettings->site_logo_dark) }}" alt="Logo Dark" class="h-10 w-auto object-contain transition-opacity duration-500 ease-in-out opacity-0 dark:opacity-100 absolute inset-0 m-auto">
                            @else
                                <img src="{{ asset('storage/' . $siteSettings->site_logo) }}" alt="Logo" class="h-10 w-auto object-contain transition-all duration-300">
                            @endif
                        @else
                            <span class="material-symbols-outlined text-3xl">diversity_1</span>
                        @endif
                    </div>
                    
                    @if(isset($siteSettings->site_name_type) && $siteSettings->site_name_type === 'image' && isset($siteSettings->site_name_image))
                        <div class="relative h-8 min-w-[120px]">
                            @if(isset($siteSettings->site_name_image_dark) && $siteSettings->site_name_image_dark)
                                <img src="{{ asset('storage/' . $siteSettings->site_name_image) }}" alt="{{ $siteSettings->site_name ?? 'GoswamiSangath' }}" class="h-8 w-auto object-contain transition-opacity duration-500 ease-in-out dark:opacity-0 absolute inset-0">
                                <img src="{{ asset('storage/' . $siteSettings->site_name_image_dark) }}" alt="{{ $siteSettings->site_name ?? 'GoswamiSangath' }} Dark" class="h-8 w-auto object-contain transition-opacity duration-500 ease-in-out opacity-0 dark:opacity-100 absolute inset-0">
                            @else
                                <img src="{{ asset('storage/' . $siteSettings->site_name_image) }}" alt="{{ $siteSettings->site_name ?? 'GoswamiSangath' }}" class="h-8 w-auto object-contain transition-all duration-300">
                            @endif
                        </div>
                    @else
                        <h1 class="text-slate-900 dark:text-white text-xl font-black leading-tight tracking-tight font-display transition-colors duration-500 ease-in-out">{{ $siteSettings->site_name ?? 'GoswamiSangath' }}</h1>
                    @endif
                </div>
                <p class="text-slate-600 dark:text-gray-400 text-sm" data-translate="The most trusted platform for finding genuine life partners. Safe, Secure and designed for you.">
                    {{ __('The most trusted platform for finding genuine life partners. Safe, Secure and designed for you.') }}
                </p>
                @if(isset($siteSettings->footer_info) && $siteSettings->footer_info)
                    <p class="text-xs text-slate-500 dark:text-gray-500 mt-2 opacity-75 italic">{{ $siteSettings->footer_info }}</p>
                @endif
            </div>
            <div>
                <!-- Removed Company Section as per request -->
            </div>
            <div>
                <h4 class="text-slate-900 dark:text-white font-bold mb-6" data-translate="Support">{{ __('Support') }}</h4>
                <ul class="flex flex-col gap-3 text-sm text-slate-600 dark:text-gray-400">
                    <li><a class="hover:text-primary transition-colors font-medium flex items-center gap-2" href="{{ route('terms') }}" data-translate="Terms of Service">{{ __('Terms of Service') }}</a></li>
                    <li><a class="hover:text-primary transition-colors font-medium flex items-center gap-2" href="{{ route('privacy') }}" data-translate="Privacy Policy">{{ __('Privacy Policy') }}</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-slate-900 dark:text-white font-bold mb-6" data-translate="Get the App">{{ __('Get the App') }}</h4>
                <div class="flex flex-col gap-3">
                    <a href="{{ $siteSettings->android_app_link ?? '#' }}" target="_blank" class="flex items-center gap-3 bg-white dark:bg-background-dark border border-gray-200 dark:border-border-dark hover:border-gray-400 dark:hover:border-white/40 p-3 rounded-xl transition-all group text-left w-fit shadow-sm hover:shadow-md">
                        <span class="material-symbols-outlined text-slate-900 dark:text-white icon-xl">android</span>
                        <div>
                            <p class="text-[10px] text-slate-500 dark:text-gray-400 uppercase font-bold tracking-tighter" data-translate="Get it on">{{ __('Get it on') }}</p>
                            <p class="text-sm text-slate-900 dark:text-white font-bold" data-translate="Google Play">{{ __('Google Play') }}</p>
                        </div>
                    </a>
                    <a href="{{ $siteSettings->ios_app_link ?? '#' }}" target="_blank" class="flex items-center gap-3 bg-white dark:bg-background-dark border border-gray-200 dark:border-border-dark hover:border-gray-400 dark:hover:border-white/40 p-3 rounded-xl transition-all group text-left w-fit shadow-sm hover:shadow-md">
                        <span class="material-symbols-outlined text-slate-900 dark:text-white icon-xl">phone_iphone</span>
                        <div>
                            <p class="text-[10px] text-slate-500 dark:text-gray-400 uppercase font-bold tracking-tighter" data-translate="Download on the">{{ __('Download on the') }}</p>
                            <p class="text-sm text-slate-900 dark:text-white font-bold" data-translate="App Store">{{ __('App Store') }}</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="pt-8 border-t border-gray-200 dark:border-[#392b28] flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-slate-500 dark:text-gray-400 font-medium">
            <p>© {{ date('Y') }} {{ $siteSettings->site_name ?? 'GoswamiSangath' }} Inc. <span data-translate="All Rights Reserved.">{{ __('All Rights Reserved.') }}</span></p>
            <div class="flex gap-8">
                <a class="hover:text-primary transition-colors" href="#">Instagram</a>
                <a class="hover:text-primary transition-colors" href="#">Twitter</a>
                <a class="hover:text-primary transition-colors" href="#">Facebook</a>
            </div>
        </div>
    </div>
</footer>

<!-- Loader JavaScript -->
<script>
    const loader = document.getElementById('loader');
    const links = document.querySelectorAll('a');

    const showLoader = () => {
        if (loader) loader.classList.add('show');
    };

    const hideLoader = () => {
        if (loader) loader.classList.remove('show');
    };

    window.addEventListener('load', hideLoader);

    window.addEventListener('pageshow', function (event) {
        if (event.persisted) {
            hideLoader();
        }
    });

    links.forEach(link => {
        link.addEventListener('click', (e) => {
            const href = link.getAttribute('href');
            if (href && !href.startsWith('#') && href !== window.location.href) {
                showLoader();
            }
        });
    });
</script>
