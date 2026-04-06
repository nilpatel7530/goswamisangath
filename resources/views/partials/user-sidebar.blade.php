{{-- Language Switcher Script - Loaded in main layout to avoid duplicates --}}

<!-- Common User Sidebar -->
<nav class="hidden lg:flex flex-col w-80 h-screen border-r border-gray-200 dark:border-[#392b28] bg-white dark:bg-background-dark shrink-0 fixed top-0 left-0">
    <div class="flex flex-col h-full p-4 overflow-y-auto">
        <!-- Branding / Logo -->
        <div class="flex items-center gap-4 mb-6 pb-6 border-b border-gray-200 dark:border-[#392b28]/50">
            <div class="size-12 text-primary flex items-center justify-center transition-transform hover:scale-110 relative">
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
                <div class="relative h-12 min-w-[150px]">
                    @if(isset($siteSettings->site_name_image_dark) && $siteSettings->site_name_image_dark)
                        <img src="{{ asset('storage/' . $siteSettings->site_name_image) }}" alt="{{ $siteSettings->site_name ?? 'GoswamiSangath' }}" class="h-12 w-auto object-contain transition-opacity duration-500 ease-in-out dark:opacity-0 absolute inset-0">
                        <img src="{{ asset('storage/' . $siteSettings->site_name_image_dark) }}" alt="{{ $siteSettings->site_name ?? 'GoswamiSangath' }} Dark" class="h-12 w-auto object-contain transition-opacity duration-500 ease-in-out opacity-0 dark:opacity-100 absolute inset-0">
                    @else
                        <img src="{{ asset('storage/' . $siteSettings->site_name_image) }}" alt="{{ $siteSettings->site_name ?? 'GoswamiSangath' }}" class="h-12 w-auto object-contain transition-all duration-300">
                    @endif
                </div>
            @else
                <h2 class="text-slate-900 dark:text-white text-2xl font-black leading-tight tracking-tight font-display transition-colors duration-500 ease-in-out">{{ $siteSettings->site_name ?? 'GoswamiSangath' }}</h2>
            @endif
        </div>
        
        @auth
        <!-- User Profile -->
        <div class="flex items-center gap-4 mb-4 pb-4 border-b border-gray-200 dark:border-[#392b28]">
            <div class="relative h-12 w-12 shrink-0">
                <img src="{{ Auth::user()->profile_image ? asset('storage/' . Auth::user()->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->full_name ?? 'User') . '&size=128&background=ec3713&color=fff' }}" 
                     alt="{{ Auth::user()->full_name ?? 'User' }}"
                     class="w-full h-full rounded-full object-cover border-2 border-gray-300 dark:border-[#392b28]"
                     onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->full_name ?? 'User') }}&size=128&background=ec3713&color=fff';">
                <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-white dark:border-background-dark"></div>
            </div>
            <div class="flex-1 min-w-0">
                <h1 class="text-slate-900 dark:text-white text-lg font-bold leading-tight truncate font-display">{{ Auth::user()->full_name ?? 'User' }}</h1>
                <p class="text-slate-600 dark:text-text-secondary text-sm font-display">
                    @php
                        $membership = DB::table('user_memberships')
                            ->join('memberships', 'user_memberships.membership_id', '=', 'memberships.id')
                            ->where('user_memberships.user_id', Auth::id())
                            ->where('user_memberships.is_active', 1)
                            ->select('memberships.name')
                            ->first();
                    @endphp
                    @if($membership)
                        {{ $membership->name }} <span data-translate="Member">{{ __('Member') }}</span>
                    @else
                        <span data-translate="Free Member">{{ __('Free Member') }}</span>
                    @endif
                </p>
            </div>
        </div>
        
        <!-- Navigation Links -->
        <div class="flex flex-col gap-1 flex-1">
            <a class="flex items-center gap-4 px-4 py-2 rounded-xl {{ request()->routeIs('dashboard') ? 'bg-gray-100 dark:bg-[#392b28] text-slate-900 dark:text-white' : 'hover:bg-gray-100 dark:hover:bg-[#392b28] text-slate-600 dark:text-text-secondary hover:text-slate-900 dark:hover:text-white' }} transition-colors group" href="{{ route('dashboard') }}">
                <span class="material-symbols-outlined filled icon-lg text-slate-600 dark:text-text-secondary {{ request()->routeIs('dashboard') ? '!text-primary' : 'group-hover:!text-primary' }} transition-colors">dashboard</span>
                <p class="text-sm font-medium font-display" data-translate="Dashboard">{{ __('Dashboard') }}</p>
            </a>
            <a class="flex items-center gap-4 px-4 py-2 rounded-xl {{ request()->routeIs('matches') ? 'bg-gray-100 dark:bg-[#392b28] text-slate-900 dark:text-white' : 'hover:bg-gray-100 dark:hover:bg-[#392b28] text-slate-600 dark:text-text-secondary hover:text-slate-900 dark:hover:text-white' }} transition-colors group" href="{{ route('matches') }}">
                <span class="material-symbols-outlined filled icon-lg text-slate-600 dark:text-text-secondary {{ request()->routeIs('matches') ? '!text-primary' : 'group-hover:!text-primary' }} transition-colors">favorite</span>
                <p class="text-sm font-medium font-display" data-translate="Matches">{{ __('Matches') }}</p>
            </a>
            <a class="flex items-center gap-4 px-4 py-2 rounded-xl {{ request()->routeIs('shortlist') ? 'bg-gray-100 dark:bg-[#392b28] text-slate-900 dark:text-white' : 'hover:bg-gray-100 dark:hover:bg-[#392b28] text-slate-600 dark:text-text-secondary hover:text-slate-900 dark:hover:text-white' }} transition-colors group" href="{{ route('shortlist') }}">
                <span class="material-symbols-outlined filled icon-lg text-slate-600 dark:text-text-secondary {{ request()->routeIs('shortlist') ? '!text-primary' : 'group-hover:!text-primary' }} transition-colors">star</span>
                <p class="text-sm font-medium font-display" data-translate="Shortlist">{{ __('Shortlist') }}</p>
            </a>
            <a class="flex items-center gap-4 px-4 py-2 rounded-xl {{ request()->routeIs('requests') ? 'bg-gray-100 dark:bg-[#392b28] text-slate-900 dark:text-white' : 'hover:bg-gray-100 dark:hover:bg-[#392b28] text-slate-600 dark:text-text-secondary hover:text-slate-900 dark:hover:text-white' }} transition-colors group" href="{{ route('requests') }}">
                <span class="material-symbols-outlined filled icon-lg text-slate-600 dark:text-text-secondary {{ request()->routeIs('requests') ? '!text-primary' : 'group-hover:!text-primary' }} transition-colors">mark_email_unread</span>
                <p class="text-sm font-medium font-display" data-translate="Requests">{{ __('Requests') }}</p>
                @php
                    $receivedCount = 0;
                    try {
                        $receivedCount = DB::table('user_interests')
                            ->where('receiver_id', Auth::id())
                            ->where('status', 'pending')
                            ->count();
                    } catch (\Exception $e) {
                        // Table might not exist
                    }
                @endphp
                @if($receivedCount > 0)
                <span class="ml-auto bg-primary text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $receivedCount }}</span>
                @endif
            </a>
            <a class="flex items-center gap-4 px-4 py-2 rounded-xl {{ request()->routeIs('messages') ? 'bg-gray-100 dark:bg-[#392b28] text-slate-900 dark:text-white' : 'hover:bg-gray-100 dark:hover:bg-[#392b28] text-slate-600 dark:text-text-secondary hover:text-slate-900 dark:hover:text-white' }} transition-colors group" href="{{ route('messages') }}">
                <span class="material-symbols-outlined filled icon-lg text-slate-600 dark:text-text-secondary {{ request()->routeIs('messages') ? '!text-primary' : 'group-hover:!text-primary' }} transition-colors">chat_bubble</span>
                <p class="text-sm font-medium font-display" data-translate="Messages">{{ __('Messages') }}</p>
                <span id="messageCount" class="ml-auto bg-primary text-white text-[10px] font-bold px-2 py-0.5 rounded-full hidden">0</span>
            </a>
            <a class="flex items-center gap-4 px-4 py-2 rounded-xl {{ request()->routeIs('notifications') ? 'bg-gray-100 dark:bg-[#392b28] text-slate-900 dark:text-white' : 'hover:bg-gray-100 dark:hover:bg-[#392b28] text-slate-600 dark:text-text-secondary hover:text-slate-900 dark:hover:text-white' }} transition-colors group" href="{{ route('notifications') }}">
                <span class="material-symbols-outlined filled icon-lg text-slate-600 dark:text-text-secondary {{ request()->routeIs('notifications') ? '!text-primary' : 'group-hover:!text-primary' }} transition-colors">notifications</span>
                <p class="text-sm font-medium font-display" data-translate="Notifications">{{ __('Notifications') }}</p>
                <span id="notificationCount" class="ml-auto bg-primary text-white text-[10px] font-bold px-2 py-0.5 rounded-full hidden">0</span>
            </a>
            <a class="flex items-center gap-4 px-4 py-2 rounded-xl {{ request()->routeIs('profile.edit') || request()->routeIs('profile.view') ? 'bg-gray-100 dark:bg-[#392b28] text-slate-900 dark:text-white' : 'hover:bg-gray-100 dark:hover:bg-[#392b28] text-slate-600 dark:text-text-secondary hover:text-slate-900 dark:hover:text-white' }} transition-colors group" href="{{ route('profile.edit') }}">
                <span class="material-symbols-outlined filled icon-lg text-slate-600 dark:text-text-secondary {{ request()->routeIs('profile.edit') || request()->routeIs('profile.view') ? '!text-primary' : 'group-hover:!text-primary' }} transition-colors">person</span>
                <p class="text-sm font-medium font-display" data-translate="Profile">{{ __('Profile') }}</p>
            </a>
            <a class="flex items-center gap-4 px-4 py-2 rounded-xl {{ request()->routeIs('membership') ? 'bg-gray-100 dark:bg-[#392b28] text-slate-900 dark:text-white' : 'hover:bg-gray-100 dark:hover:bg-[#392b28] text-slate-600 dark:text-text-secondary hover:text-slate-900 dark:hover:text-white' }} transition-colors group" href="{{ route('membership') }}">
                <span class="material-symbols-outlined filled icon-lg {{ request()->routeIs('membership') ? 'text-primary' : 'group-hover:text-primary' }} transition-colors">workspace_premium</span>
                <p class="text-sm font-medium font-display" data-translate="Upgrade">{{ __('Upgrade') }}</p>
            </a>
        </div>
        
        <!-- Footer Section -->
        <div class="mt-auto pt-4 border-t border-gray-200 dark:border-[#392b28] space-y-2">
            <!-- Theme Toggle -->
            <div class="px-4 py-1">
                @include('partials.theme-toggle')
            </div>
            <!-- Language Switcher -->
            <div class="px-4 py-1" x-data="{ open: false }">
                <button @click="open = !open" class="w-full flex items-center gap-4 px-4 py-2 rounded-xl hover:bg-gray-100 dark:hover:bg-[#392b28] text-slate-600 dark:text-text-secondary hover:text-slate-900 dark:hover:text-white transition-colors group">
                    <span class="material-symbols-outlined filled icon-lg text-slate-600 dark:text-text-secondary group-hover:!text-primary transition-colors">language</span>
                    <p class="text-sm font-medium font-display flex-1 text-left language-display">{{ strtoupper(app()->getLocale()) }}</p>
                    <span class="material-symbols-outlined icon-md transition-transform text-slate-600 dark:text-text-secondary" :class="open ? 'rotate-180' : ''">expand_more</span>
                </button>
                <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="mt-2 ml-12 rounded-lg shadow-xl overflow-hidden bg-white dark:bg-surface-dark border border-gray-200 dark:border-[#392b28]">
                    <button type="button" onclick="console.log('Button clicked: English'); if(typeof window.switchLanguage === 'function') { console.log('Calling switchLanguage'); window.switchLanguage('en'); } else { console.error('switchLanguage not found, redirecting'); window.location.href='/language/en'; } return false;" class="w-full text-left block px-4 py-2 text-sm text-slate-900 dark:text-white hover:bg-gray-100 dark:hover:bg-[#392b28] hover:text-primary dark:hover:text-primary transition-colors">English</button>
                    <button type="button" onclick="console.log('Button clicked: Hindi'); if(typeof window.switchLanguage === 'function') { console.log('Calling switchLanguage'); window.switchLanguage('hi'); } else { console.error('switchLanguage not found, redirecting'); window.location.href='/language/hi'; } return false;" class="w-full text-left block px-4 py-2 text-sm text-slate-900 dark:text-white hover:bg-gray-100 dark:hover:bg-[#392b28] hover:text-primary dark:hover:text-primary transition-colors">हिन्दી</button>
                    <button type="button" onclick="console.log('Button clicked: Gujarati'); if(typeof window.switchLanguage === 'function') { console.log('Calling switchLanguage'); window.switchLanguage('gu'); } else { console.error('switchLanguage not found, redirecting'); window.location.href='/language/gu'; } return false;" class="w-full text-left block px-4 py-2 text-sm text-slate-900 dark:text-white hover:bg-gray-100 dark:hover:bg-[#392b28] hover:text-primary dark:hover:text-primary transition-colors">ગુજરાતી</button>
                </div>
            </div>
            <!-- Logout Button -->
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" class="w-full flex items-center gap-4 px-4 py-2 rounded-xl hover:bg-gray-100 dark:hover:bg-[#392b28] text-slate-600 dark:text-text-secondary hover:text-slate-900 dark:hover:text-white transition-colors group">
                    <span class="material-symbols-outlined filled icon-lg text-slate-600 dark:text-text-secondary group-hover:!text-primary transition-colors">logout</span>
                    <p class="text-sm font-medium font-display" data-translate="Logout">{{ __('Logout') }}</p>
                </button>
            </form>
            <!-- Footer Links -->
            <div class="flex flex-wrap gap-4 text-xs text-slate-600 dark:text-text-secondary">
                <a class="hover:text-slate-900 dark:hover:text-white transition-colors" href="#" data-translate="Privacy">{{ __('Privacy') }}</a>
                <a class="hover:text-slate-900 dark:hover:text-white transition-colors" href="#" data-translate="Help">{{ __('Help') }}</a>
                <a class="hover:text-slate-900 dark:hover:text-white transition-colors" href="{{ route('terms') }}" data-translate="Terms">{{ __('Terms') }}</a>
            </div>
        </div>
        @else
        <!-- Guest Navigation -->
        <div class="flex flex-col gap-1 flex-1">
            <a class="flex items-center gap-4 px-4 py-2 rounded-xl {{ request()->routeIs('home') ? 'bg-gray-100 dark:bg-[#392b28] text-slate-900 dark:text-white' : 'hover:bg-gray-100 dark:hover:bg-[#392b28] text-slate-600 dark:text-text-secondary hover:text-slate-900 dark:hover:text-white' }} transition-colors group" href="{{ route('home') }}">
                <span class="material-symbols-outlined filled icon-lg text-slate-600 dark:text-text-secondary {{ request()->routeIs('home') ? '!text-primary' : 'group-hover:!text-primary' }} transition-colors">home</span>
                <p class="text-sm font-medium font-display">Home</p>
            </a>
            <a class="flex items-center gap-4 px-4 py-2 rounded-xl {{ request()->routeIs('about') ? 'bg-gray-100 dark:bg-[#392b28] text-slate-900 dark:text-white' : 'hover:bg-gray-100 dark:hover:bg-[#392b28] text-slate-600 dark:text-text-secondary hover:text-slate-900 dark:hover:text-white' }} transition-colors group" href="{{ route('about') }}">
                <span class="material-symbols-outlined filled icon-lg text-slate-600 dark:text-text-secondary {{ request()->routeIs('about') ? '!text-primary' : 'group-hover:!text-primary' }} transition-colors">info</span>
                <p class="text-sm font-medium font-display">About Us</p>
            </a>
            <a class="flex items-center gap-4 px-4 py-2 rounded-xl {{ request()->routeIs('success-stories') ? 'bg-gray-100 dark:bg-[#392b28] text-slate-900 dark:text-white' : 'hover:bg-gray-100 dark:hover:bg-[#392b28] text-slate-600 dark:text-text-secondary hover:text-slate-900 dark:hover:text-white' }} transition-colors group" href="{{ route('success-stories') }}">
                <span class="material-symbols-outlined filled icon-lg text-slate-600 dark:text-text-secondary {{ request()->routeIs('success-stories') ? '!text-primary' : 'group-hover:!text-primary' }} transition-colors">favorite</span>
                <p class="text-sm font-medium font-display">Success Stories</p>
            </a>
            <a class="flex items-center gap-4 px-4 py-2 rounded-xl {{ request()->routeIs('membership') ? 'bg-gray-100 dark:bg-[#392b28] text-slate-900 dark:text-white' : 'hover:bg-gray-100 dark:hover:bg-[#392b28] text-slate-600 dark:text-text-secondary hover:text-slate-900 dark:hover:text-white' }} transition-colors group" href="{{ route('membership') }}">
                <span class="material-symbols-outlined filled icon-lg {{ request()->routeIs('membership') ? 'text-primary' : 'group-hover:text-primary' }} transition-colors">workspace_premium</span>
                <p class="text-sm font-medium font-display">Membership</p>
            </a>
        </div>
        
        <!-- Footer Section -->
        <div class="mt-auto pt-4 border-t border-gray-200 dark:border-[#392b28] space-y-2">
            <!-- Language Switcher -->
            <div class="px-4 py-1" x-data="{ open: false }">
                <button @click="open = !open" class="w-full flex items-center gap-4 px-4 py-2 rounded-xl hover:bg-gray-100 dark:hover:bg-[#392b28] text-slate-600 dark:text-text-secondary hover:text-slate-900 dark:hover:text-white transition-colors group">
                    <span class="material-symbols-outlined filled icon-lg text-slate-600 dark:text-text-secondary group-hover:!text-primary transition-colors">language</span>
                    <p class="text-sm font-medium font-display flex-1 text-left language-display">{{ strtoupper(app()->getLocale()) }}</p>
                    <span class="material-symbols-outlined icon-md transition-transform text-slate-600 dark:text-text-secondary" :class="open ? 'rotate-180' : ''">expand_more</span>
                </button>
                <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="mt-2 ml-12 rounded-lg shadow-xl overflow-hidden bg-white dark:bg-surface-dark border border-gray-200 dark:border-[#392b28]">
                    <button onclick="switchLanguage('en')" class="w-full text-left block px-4 py-2 text-sm text-slate-900 dark:text-white hover:bg-gray-100 dark:hover:bg-[#392b28] hover:text-primary dark:hover:text-primary transition-colors">English</button>
                    <button onclick="switchLanguage('hi')" class="w-full text-left block px-4 py-2 text-sm text-slate-900 dark:text-white hover:bg-gray-100 dark:hover:bg-[#392b28] hover:text-primary dark:hover:text-primary transition-colors">हिन्दी</button>
                    <button onclick="switchLanguage('gu')" class="w-full text-left block px-4 py-2 text-sm text-slate-900 dark:text-white hover:bg-gray-100 dark:hover:bg-[#392b28] hover:text-primary dark:hover:text-primary transition-colors">ગુજરાતી</button>
                </div>
            </div>
            <!-- Login Button -->
            <a href="{{ route('login') }}" class="w-full flex items-center gap-4 px-4 py-2 rounded-xl hover:bg-gray-100 dark:hover:bg-[#392b28] text-slate-600 dark:text-text-secondary hover:text-slate-900 dark:hover:text-white transition-colors group">
                <span class="material-symbols-outlined filled icon-lg text-slate-600 dark:text-text-secondary group-hover:!text-primary transition-colors">login</span>
                <p class="text-sm font-medium font-display">Login</p>
            </a>
            <!-- Footer Links -->
            <div class="flex flex-wrap gap-4 text-xs text-slate-600 dark:text-text-secondary">
                <a class="hover:text-slate-900 dark:hover:text-white transition-colors" href="#" data-translate="Privacy">{{ __('Privacy') }}</a>
                <a class="hover:text-slate-900 dark:hover:text-white transition-colors" href="#" data-translate="Help">{{ __('Help') }}</a>
                <a class="hover:text-slate-900 dark:hover:text-white transition-colors" href="{{ route('terms') }}" data-translate="Terms">{{ __('Terms') }}</a>
            </div>
        </div>
        @endauth
    </div>
</nav>

@auth
<script>
    // Real-time polling for unread counts
    function updateUnreadCounts() {
        fetch('/notifications/unread-count', {
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
        })
        .then(response => response.json())
        .then(data => {
            // Update message count
            const messageCountEl = document.getElementById('messageCount');
            if (messageCountEl) {
                if (data.messages > 0) {
                    messageCountEl.textContent = data.messages;
                    messageCountEl.classList.remove('hidden');
                } else {
                    messageCountEl.classList.add('hidden');
                }
            }
            
            // Update notification count
            const notificationCountEl = document.getElementById('notificationCount');
            if (notificationCountEl) {
                if (data.notifications > 0) {
                    notificationCountEl.textContent = data.notifications;
                    notificationCountEl.classList.remove('hidden');
                } else {
                    notificationCountEl.classList.add('hidden');
                }
            }
        })
        .catch(error => console.error('Error updating unread counts:', error));
    }
    
    // Update counts on page load
    updateUnreadCounts();
    
    // Poll every 5 seconds
    setInterval(updateUnreadCounts, 5000);
    
    // Smooth page transitions for sidebar navigation
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarLinks = document.querySelectorAll('nav a[href^="/"], nav a[href^="{{ url("/") }}"]');
        const mainContent = document.querySelector('main') || document.querySelector('.flex-1') || document.querySelector('body > div:not(nav)');
        
        // Fade-in on page load
        if (mainContent) {
            mainContent.classList.add('page-content-fade-in');
            
            requestAnimationFrame(() => {
                setTimeout(() => {
                    mainContent.classList.add('visible');
                }, 50);
            });
        }
        
        sidebarLinks.forEach(link => {
            // Skip external links
            const href = link.getAttribute('href');
            if (!href || href.startsWith('http') && !href.includes(window.location.hostname)) {
                return;
            }
            
            // Skip hash links, JavaScript links, and same page links
            if (href === '#' || href.startsWith('javascript:') || href === window.location.pathname) {
                return;
            }
            
            link.addEventListener('click', function(e) {
                const targetHref = this.getAttribute('href');
                
                // Skip if it's the same page
                if (targetHref === window.location.pathname) {
                    e.preventDefault();
                    return;
                }
                
                // Add fade-out effect to main content
                if (mainContent) {
                    mainContent.classList.remove('page-content-fade-in', 'visible');
                    mainContent.classList.add('page-content-fade-out');
                }
                
                // Allow navigation after fade-out animation
                setTimeout(() => {
                    window.location.href = targetHref;
                }, 200);
            });
        });
    });
</script>
@endauth

