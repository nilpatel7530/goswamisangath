<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title data-translate="Shortlist & Favorites - GoswamiSangath">{{ __('Shortlist & Favorites - GoswamiSangath') }}</title>
    
    @php
        $isFreePlan = !isset($membership) || !$membership || $membership->name === 'Free';
    @endphp
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Spline+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
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
                        "background-dark": "#221310",
                        "surface-dark": "#2d1a17",
                        "surface-highlight": "#3a221f"
                    },
                    fontFamily: {
                        "display": ["Spline Sans", "sans-serif"]
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
        /* Custom scrollbar for sidebar */
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f8f6f6;
        }
        .dark ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #e5e7eb;
        }
        .dark ::-webkit-scrollbar-thumb {
            background: #3a221f;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #ec3713;
        }
        
        .hover-card-trigger:hover .hover-action-reveal {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
    <!-- Global Theme System -->
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-background-light dark:bg-background-dark text-gray-900 dark:text-white font-display overflow-x-hidden selection:bg-primary selection:text-white">
    <div class="flex flex-1 overflow-hidden">
        @include('partials.user-sidebar')
        
        <div class="flex min-h-screen relative flex-1 lg:ml-80">
        <!-- Main Content Area -->
        <main class="flex-1 flex flex-col min-h-screen relative p-6 lg:p-12 xl:p-16">
            <div class="max-w-[1200px] mx-auto w-full flex flex-col gap-10 pb-24">
                <!-- Header & Stats -->
                <header class="flex flex-wrap items-end justify-between gap-8">
                    <div class="flex flex-col gap-2 max-w-xl">
                        <h2 class="text-5xl md:text-6xl lg:text-7xl font-black text-slate-900 dark:text-white tracking-tighter leading-[0.9]">
                            <span data-translate="Your">{{ __('Your') }}</span> <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-orange-500" data-translate="Shortlist.">{{ __('Shortlist.') }}</span>
                        </h2>
                        <p class="text-slate-600 dark:text-gray-400 text-lg font-light mt-2 max-w-md" data-translate="Candidates you've saved for a closer look. Send interest to them before they find someone else.">{{ __('Candidates you\'ve saved for a closer look. Send interest to them before they find someone else.') }}</p>
                    </div>
                    <!-- Stats Cards -->
                    <div class="flex gap-4">
                        <div class="flex flex-col justify-center px-6 py-4 rounded-xl bg-white dark:bg-surface-dark border border-gray-200 dark:border-white/5 min-w-[140px]">
                            <p class="text-slate-600 dark:text-gray-400 text-sm font-medium uppercase tracking-wider" data-translate="Saved">{{ __('Saved') }}</p>
                            <div class="flex items-baseline gap-1">
                                <span class="text-4xl font-bold text-slate-900 dark:text-white">{{ $totalCount }}</span>
                                <span class="text-sm text-slate-500 dark:text-gray-500" data-translate="profiles">{{ __('profiles') }}</span>
                            </div>
                        </div>
                        <div class="flex flex-col justify-center px-6 py-4 rounded-xl bg-white dark:bg-surface-dark border border-primary/20 min-w-[140px] relative overflow-hidden">
                            <div class="absolute -right-4 -top-4 size-16 bg-primary/10 rounded-full blur-xl"></div>
                            <p class="text-primary text-sm font-medium uppercase tracking-wider" data-translate="Mutual">{{ __('Mutual') }}</p>
                            <div class="flex items-baseline gap-1">
                                <span class="text-4xl font-bold text-slate-900 dark:text-white">{{ $mutualCount }}</span>
                                <span class="text-sm text-slate-500 dark:text-gray-500" data-translate="favorites">{{ __('favorites') }}</span>
                            </div>
                        </div>
                    </div>
                </header>
                
                <!-- Filters / Chips -->
                <div class="flex flex-wrap items-center gap-3 py-2 overflow-x-auto no-scrollbar">
                    <button class="h-10 px-6 rounded-full bg-primary text-white font-bold text-sm border border-transparent hover:scale-105 transition-transform" data-translate="All Profiles">{{ __('All Profiles') }}</button>
                </div>
                
                <!-- Profile List (Placards) -->
                @if($shortlistedUsers->count() > 0)
                <div class="flex flex-col gap-6">
                    @foreach($shortlistedUsers as $profile)
                    <div class="group hover-card-trigger relative flex flex-col md:flex-row items-start md:items-center gap-6 p-5 rounded-xl md:rounded-[2.5rem] {{ $profile->isMutual ?? false ? 'bg-gradient-to-r from-primary/10 dark:from-[#3a1d18] to-white dark:to-surface-dark border border-primary/20 hover:border-primary/50' : 'bg-white dark:bg-surface-dark border border-gray-200 dark:border-white/5 hover:border-primary/30 hover:bg-gray-50 dark:hover:bg-surface-highlight' }} transition-all duration-300 shadow-xl">
                        @if($profile->isMutual ?? false)
                        <!-- Special Tag for Mutual Favorite -->
                        <div class="absolute top-0 right-0 md:right-auto md:left-8 -mt-3 bg-gradient-to-r from-orange-500 to-primary text-white text-[10px] font-bold uppercase tracking-widest px-3 py-1 rounded-full shadow-lg z-10">
                            <span data-translate="Mutual Favorite">{{ __('Mutual Favorite') }}</span>
                        </div>
                        @endif
                        
                        <!-- Image -->
                        <div class="relative shrink-0 {{ $profile->isMutual ?? false ? 'mt-2 md:mt-0' : '' }}">
                            <div class="size-24 md:size-32 rounded-full bg-cover bg-center border-4 {{ $profile->isMutual ?? false ? 'border-primary/30 group-hover:border-primary/60' : 'border-gray-300 dark:border-background-dark group-hover:border-primary/20' }} transition-colors duration-300" 
                                 style="background-image: url('{{ $profile->profile_image ? asset('storage/' . $profile->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($profile->full_name) . '&size=400&background=ec3713&color=fff' }}');">
                            </div>
                            <div class="absolute bottom-1 right-1 bg-white dark:bg-background-dark p-1 rounded-full">
                                @if($profile->isMutual ?? false)
                                <div class="bg-primary text-white text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1 shadow-lg shadow-primary/40">
                                    <span class="material-symbols-outlined icon-xs">favorite</span> Match
                                </div>
                                @else
                                <div class="{{ ($profile->matchPercentage ?? 95) >= 90 ? 'bg-primary' : 'bg-gray-300 dark:bg-gray-700' }} {{ ($profile->matchPercentage ?? 95) >= 90 ? 'text-white' : 'text-slate-900 dark:text-white' }} text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1">
                                    <span class="material-symbols-outlined icon-xs">verified</span> {{ $profile->matchPercentage ?? 95 }}%
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Content -->
                        <div class="flex-1 min-w-0 flex flex-col gap-2">
                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                                <h3 class="text-2xl md:text-3xl font-bold text-slate-900 dark:text-white group-hover:text-primary transition-colors">
                                    <a href="{{ route('profile.view', $profile->id) }}">{{ $profile->full_name }}, {{ $profile->age ?? 'N/A' }}</a>
                                </h3>
                                @if($profile->marital_status)
                                <span class="px-3 py-1 rounded-full bg-gray-100 dark:bg-white/5 text-slate-600 dark:text-gray-400 text-xs font-medium border border-gray-300 dark:border-white/5">{{ $profile->marital_status }}</span>
                                @endif
                                @if($profile->height)
                                <span class="px-3 py-1 rounded-full bg-gray-100 dark:bg-white/5 text-slate-600 dark:text-gray-400 text-xs font-medium border border-gray-300 dark:border-white/5">{{ $profile->height }}</span>
                                @endif
                                @if($profile->verification_status === 'verified')
                                <span class="px-3 py-1 rounded-full bg-primary/10 text-primary text-xs font-bold border border-primary/20 flex items-center gap-1">
                                    <span class="material-symbols-outlined icon-xs filled">verified</span> Verified
                                </span>
                                @endif
                            </div>
                            @if($profile->occupation)
                            <p class="text-lg text-slate-600 dark:text-gray-300 font-medium">{{ $profile->occupation }}</p>
                            @endif
                            <div class="flex items-center gap-4 text-sm text-slate-500 dark:text-gray-500">
                                @if($profile->location)
                                <span class="flex items-center gap-1">
                                    <span class="material-symbols-outlined icon-sm">location_on</span> 
                                    {{ $profile->location }}
                                </span>
                                @endif
                                @if($profile->mother_tongue)
                                <span class="flex items-center gap-1">
                                    <span class="material-symbols-outlined icon-sm">translate</span> 
                                    {{ $profile->mother_tongue }}
                                </span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div class="flex flex-row md:flex-col lg:flex-row items-center gap-3 w-full md:w-auto mt-2 md:mt-0">
                            @if($profile->canChat ?? false)
                            <a href="#" class="flex-1 md:flex-none h-12 px-6 rounded-full bg-white dark:bg-surface-dark text-primary font-bold text-sm hover:bg-gray-100 dark:hover:bg-surface-highlight transition-colors flex items-center justify-center gap-2 shadow-lg transform hover:-translate-y-0.5 active:translate-y-0">
                                <span class="material-symbols-outlined icon-md">chat</span>
                                    <span data-translate="Message">{{ __('Message') }}</span>
                            </a>
                            @elseif($profile->interestSent ?? false)
                            <div class="flex-1 md:flex-none h-12 px-6 rounded-full bg-green-500/20 dark:bg-green-500/20 border border-green-500 dark:border-green-500 text-green-700 dark:text-green-400 font-bold text-sm flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined icon-md filled">check_circle</span>
                                <span>Interest Sent</span>
                            </div>
                            @else
                            @if($isFreePlan)
                                <button type="button" onclick="event.preventDefault(); event.stopPropagation(); document.getElementById('upgrade-modal').classList.remove('hidden');" class="flex-1 md:flex-none h-12 px-6 rounded-full bg-primary text-white font-bold text-sm hover:bg-red-600 transition-colors flex items-center justify-center gap-2 shadow-[0_4px_20px_rgba(236,55,19,0.3)] hover:shadow-[0_4px_25px_rgba(236,55,19,0.5)] transform hover:-translate-y-0.5 active:translate-y-0">
                                    <span class="material-symbols-outlined icon-md">favorite</span>
                                    <span data-translate="Send Interest">{{ __('Send Interest') }}</span>
                                </button>
                            @else
                                <form action="{{ route('profile.send-interest', $profile) }}" method="POST" class="flex-1 md:flex-none send-interest-form" data-user-id="{{ $profile->id }}">
                                    @csrf
                                    <button type="submit" class="send-interest-btn w-full h-12 px-6 rounded-full bg-primary text-white font-bold text-sm hover:bg-red-600 transition-colors flex items-center justify-center gap-2 shadow-[0_4px_20px_rgba(236,55,19,0.3)] hover:shadow-[0_4px_25px_rgba(236,55,19,0.5)] transform hover:-translate-y-0.5 active:translate-y-0">
                                        <span class="material-symbols-outlined icon-md">favorite</span>
                                        <span data-translate="Send Interest">{{ __('Send Interest') }}</span>
                                    </button>
                                </form>
                            @endif
                            @endif
                            <form action="{{ route('profile.toggle-shortlist', $profile) }}" method="POST" class="inline toggle-shortlist-form" data-user-id="{{ $profile->id }}">
                                @csrf
                                <button type="submit" aria-label="Remove from shortlist" class="toggle-shortlist-btn size-12 rounded-full border border-gray-300 dark:border-white/10 flex items-center justify-center text-slate-600 dark:text-gray-400 hover:text-slate-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-white/10 hover:border-gray-400 dark:hover:border-white/30 transition-all">
                                    <span class="shortlist-icon material-symbols-outlined">close</span>
                                </button>
                            </form>
                            <a href="{{ route('profile.view', $profile) }}" aria-label="View Profile" class="size-12 rounded-full border border-gray-300 dark:border-white/10 flex items-center justify-center text-slate-600 dark:text-gray-400 hover:text-slate-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-white/10 hover:border-gray-400 dark:hover:border-white/30 transition-all md:hidden xl:flex">
                                <span class="material-symbols-outlined">arrow_outward</span>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <!-- Empty State -->
                <div class="flex flex-col items-center justify-center py-20 text-center">
                    <div class="size-24 bg-gray-100 dark:bg-surface-dark rounded-full flex items-center justify-center mb-6">
                        <span class="material-symbols-outlined text-5xl text-slate-400 dark:text-gray-500">star_border</span>
                    </div>
                    <h3 class="text-slate-900 dark:text-white text-2xl font-bold mb-2" data-translate="Your shortlist is empty">{{ __('Your shortlist is empty') }}</h3>
                    <p class="text-slate-600 dark:text-gray-400 max-w-md mb-8" data-translate="Start exploring profiles and add them to your shortlist to keep track of your favorites.">{{ __('Start exploring profiles and add them to your shortlist to keep track of your favorites.') }}</p>
                    <a href="{{ route('matches') }}" class="bg-primary text-white px-8 py-3 rounded-full font-bold hover:bg-red-600 transition-colors" data-translate="Browse Matches">{{ __('Browse Matches') }}</a>
                </div>
                @endif
                
                @if($shortlistedUsers->count() > 0)
                <!-- Footer / Load More -->
                <div class="flex justify-center mt-8">
                    <p class="text-slate-500 dark:text-gray-500 text-sm" data-translate="You've reached the end of your shortlist.">{{ __('You\'ve reached the end of your shortlist.') }}</p>
                </div>
                @endif
            </div>
        </main>
        </div>
    </div>
    
    <!-- Global Theme Manager -->
    <script src="{{ asset('js/theme.js') }}"></script>
    <!-- Language Switcher -->
    <script src="{{ asset('js/language-switcher.js') }}"></script>
    
    <script>
        // Handle Send Interest form submissions via AJAX
        document.querySelectorAll('.send-interest-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const button = this.querySelector('.send-interest-btn');
                const originalContent = button.innerHTML;
                const formData = new FormData(this);
                const url = this.action;
                
                // Disable button and show loading state
                button.disabled = true;
                button.innerHTML = '<span class="material-symbols-outlined icon-md animate-spin">sync</span> Sending...';
                
                // Send AJAX request
                fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Replace form with success message
                        const formContainer = this;
                        formContainer.innerHTML = `
                            <div class="flex-1 md:flex-none h-12 px-6 rounded-full bg-green-500/20 dark:bg-green-500/20 border border-green-500 dark:border-green-500 text-green-700 dark:text-green-400 font-bold text-sm flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined icon-md filled">check_circle</span>
                                <span>Interest Sent</span>
                            </div>
                        `;
                    } else {
                        // Show error
                        button.disabled = false;
                        button.innerHTML = originalContent;
                        alert(data.message || 'Failed to send interest. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    button.disabled = false;
                    button.innerHTML = originalContent;
                    alert('An error occurred. Please try again.');
                });
            });
        });
        
        // Handle Toggle Shortlist form submissions via AJAX (for removing from shortlist)
        document.querySelectorAll('.toggle-shortlist-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const button = this.querySelector('.toggle-shortlist-btn');
                const icon = this.querySelector('.shortlist-icon');
                const profileCard = this.closest('.bg-white, .dark\\:bg-card-dark'); // Find the parent card
                const originalIcon = icon.innerHTML;
                const formData = new FormData(this);
                const url = this.action;
                
                // Show loading state
                icon.innerHTML = '<span class="material-symbols-outlined icon-md animate-spin">sync</span>';
                button.disabled = true;
                
                // Send AJAX request
                fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Find the profile card container - look for the parent div with 'group' class
                        let cardToRemove = this.closest('.group.hover-card-trigger');
                        if (!cardToRemove) {
                            // Fallback: find the closest div with flex classes
                            cardToRemove = this.closest('div.flex.flex-col');
                        }
                        if (!cardToRemove) {
                            // Last fallback: find parent div
                            cardToRemove = this.closest('div');
                        }
                        
                        // Remove the profile card with fade out animation
                        if (cardToRemove) {
                            cardToRemove.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                            cardToRemove.style.opacity = '0';
                            cardToRemove.style.transform = 'translateY(-10px)';
                            setTimeout(() => {
                                cardToRemove.remove();
                                
                                // Check if there are no more profiles
                                const profilesContainer = document.querySelector('.grid');
                                if (profilesContainer && profilesContainer.children.length === 0) {
                                    // Reload page to show empty state
                                    window.location.reload();
                                }
                            }, 300);
                        } else {
                            // Fallback: reload page
                            window.location.reload();
                        }
                    } else {
                        // Show error
                        icon.innerHTML = originalIcon;
                        button.disabled = false;
                        alert(data.message || 'Failed to remove from shortlist. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    icon.innerHTML = originalIcon;
                    button.disabled = false;
                    alert('An error occurred. Please try again.');
                });
            });
        });
    </script>
    @include('partials.upgrade-modal')
</body>
</html>