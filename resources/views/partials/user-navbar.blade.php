<header class="sticky top-0 z-50 flex items-center justify-between whitespace-nowrap border-b border-solid border-gray-200 dark:border-card-border bg-background-light/95 dark:bg-background-dark/95 backdrop-blur-md px-4 lg:px-10 py-5 transition-all duration-300">
    <div class="flex items-center gap-8">
        <a href="{{ route('dashboard') }}">
            <div class="flex items-center gap-4 transition-transform hover:scale-105">
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
        </a>
        <nav class="hidden lg:flex items-center gap-9">
            <a class="text-sm font-semibold leading-normal transition-colors {{ request()->routeIs('dashboard') ? 'text-primary' : 'text-slate-600 dark:text-text-muted hover:text-slate-900 dark:hover:text-white' }}" href="{{ route('dashboard') }}">Dashboard</a>
            <a class="text-sm font-semibold leading-normal transition-colors {{ request()->routeIs('matches') ? 'text-primary' : 'text-slate-600 dark:text-text-muted hover:text-slate-900 dark:hover:text-white' }}" href="{{ route('matches') }}">Matches</a>
            <a class="text-sm font-semibold leading-normal transition-colors {{ request()->routeIs('shortlist') ? 'text-primary' : 'text-slate-600 dark:text-text-muted hover:text-slate-900 dark:hover:text-white' }}" href="{{ route('shortlist') }}">Shortlist</a>
            <a class="text-sm font-semibold leading-normal transition-colors {{ request()->routeIs('requests') ? 'text-primary' : 'text-slate-600 dark:text-text-muted hover:text-slate-900 dark:hover:text-white' }}" href="{{ route('requests') }}">Inbox</a>
            <a class="text-sm font-semibold leading-normal transition-colors {{ request()->routeIs('profile.edit') || request()->routeIs('profile.view') ? 'text-primary' : 'text-slate-600 dark:text-text-muted hover:text-slate-900 dark:hover:text-white' }}" href="{{ route('profile.edit') }}">Profile</a>
        </nav>
    </div>
    <div class="flex flex-1 justify-end gap-6 items-center">
        <div class="hidden md:flex w-full max-w-xs items-center rounded-full bg-gray-100 dark:bg-surface-dark h-11 px-4 ring-1 ring-gray-200 dark:ring-border-dark focus-within:ring-primary transition-all">
            <span class="material-symbols-outlined text-slate-400 dark:text-text-muted text-[20px]">search</span>
            <input class="w-full bg-transparent border-none text-slate-900 dark:text-white placeholder:text-slate-400 dark:placeholder:text-text-muted focus:ring-0 text-sm ml-2" placeholder="Search by ID or Keyword"/>
        </div>
        <a href="{{ route('membership') }}" class="flex items-center justify-center rounded-full h-11 px-7 bg-primary text-white text-sm font-black uppercase tracking-wider hover:bg-red-600 transition-all hover:scale-105 shadow-lg shadow-primary/20">
            Upgrade
        </a>
        <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-11 border-2 border-gray-200 dark:border-card-border cursor-pointer relative shadow-md transition-transform hover:scale-110" 
             style='background-image: url("{{ Auth::user()->profile_image ? asset('storage/' . Auth::user()->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->full_name) . '&background=ec3713&color=fff' }}");'>
            <div class="absolute bottom-0 right-0 w-3.5 h-3.5 bg-green-500 rounded-full border-2 border-white dark:border-background-dark"></div>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button type="submit" class="text-slate-500 dark:text-text-muted hover:text-primary dark:hover:text-white text-sm font-bold transition-colors uppercase tracking-tight">Logout</button>
        </form>
    </div>
</header>

