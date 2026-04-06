<header class="w-full bg-white/95 dark:bg-background-dark/95 backdrop-blur-md shadow-sm py-6 px-6 md:px-10 sticky top-0 z-50 border-b border-gray-200 dark:border-[#392b28]/50 transition-all duration-300">
    <div class="w-full mx-auto flex justify-between items-center text-slate-900 dark:text-white">
        @if(isset($siteSettings->site_name_type) && $siteSettings->site_name_type === 'image' && isset($siteSettings->site_name_image))
            <div class="relative h-10 min-w-[200px]">
                @if(isset($siteSettings->site_name_image_dark) && $siteSettings->site_name_image_dark)
                    <a href="{{ route('home') }}" class="header-logo absolute inset-0 transition-opacity duration-500 ease-in-out dark:opacity-0 hover:scale-105 transform">
                        <img src="{{ asset('storage/' . $siteSettings->site_name_image) }}" alt="{{ $siteSettings->site_name ?? 'GoswamiSangath' }}" class="h-10 w-auto object-contain">
                    </a>
                    <a href="{{ route('home') }}" class="header-logo absolute inset-0 transition-opacity duration-500 ease-in-out opacity-0 dark:opacity-100 hover:scale-105 transform">
                        <img src="{{ asset('storage/' . $siteSettings->site_name_image_dark) }}" alt="{{ $siteSettings->site_name ?? 'GoswamiSangath' }} Dark" class="h-10 w-auto object-contain">
                    </a>
                @else
                    <a href="{{ route('home') }}" class="header-logo transition-transform hover:scale-105">
                        <img src="{{ asset('storage/' . $siteSettings->site_name_image) }}" alt="{{ $siteSettings->site_name ?? 'GoswamiSangath' }}" class="h-10 w-auto object-contain">
                    </a>
                @endif
            </div>
        @else
            <a href="{{ route('home') }}" class="header-logo text-2xl font-black font-display transition-colors duration-500 ease-in-out hover:text-primary">{{ $siteSettings->site_name ?? 'GoswamiSangath' }}</a>
        @endif
        <nav class="hidden md:flex items-center space-x-8">
            <a href="{{ route('home') }}" class="text-sm font-semibold hover:text-primary transition-colors {{ request()->routeIs('home') ? 'text-primary' : 'text-slate-600 dark:text-text-muted' }}">Home</a>
            <a href="{{ route('about') }}" class="text-sm font-semibold hover:text-primary transition-colors {{ request()->routeIs('about') ? 'text-primary' : 'text-slate-600 dark:text-text-muted' }}">About</a>
            <a href="{{ route('membership') }}" class="text-sm font-semibold hover:text-primary transition-colors {{ request()->routeIs('membership') ? 'text-primary' : 'text-slate-600 dark:text-text-muted' }}">Membership</a>
        </nav>
        <div class="flex items-center space-x-3">
            @auth
                {{-- If user is logged in --}}
                <a href="{{ route('dashboard') }}" class="bg-indigo-100 text-indigo-600 px-4 py-2 rounded-lg font-semibold hover:bg-indigo-200 transition">Dashboard</a>
                
                {{-- Show Admin Panel link only if user is an admin --}}
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="bg-red-100 text-red-600 px-4 py-2 rounded-lg font-semibold hover:bg-red-200 transition">Admin Panel</a>
                @endif
                
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-semibold hover:bg-gray-300 transition">Logout</button>
                </form>
            @else
                {{-- If user is a guest --}}
                <a href="{{ route('signup') }}" class="bg-indigo-100 text-indigo-600 px-4 py-2 rounded-lg font-semibold hover:bg-indigo-200 transition">Sign Up</a>
                <a href="{{ route('login') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-semibold hover:bg-gray-300 transition">Login</a>
            @endauth
        </div>
    </div>
</header>
