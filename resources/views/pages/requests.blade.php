<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title data-translate="Interest/Request Management">{{ __('Interest/Request Management') }}</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Spline+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <!-- Global Theme System -->
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <!-- Theme Config -->
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#ec3713",
                        "background-light": "#f8f6f6",
                        "background-dark": "#221310",
                        "surface-dark": "#2a1d1a",
                        "text-secondary": "#b9a19d",
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
    <style>
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
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .material-symbols-outlined.fill {
            font-variation-settings: 'FILL' 1;
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-background-light dark:bg-background-dark text-black dark:text-white font-display overflow-hidden h-screen flex flex-col selection:bg-primary selection:text-white">
    <div class="flex flex-1 overflow-hidden">
        @include('partials.user-sidebar')

    <!-- Main Content Area -->
    <main class="flex-1 h-full overflow-y-auto relative flex flex-col lg:ml-80">
        <div class="flex-1 w-full max-w-[1200px] mx-auto p-4 md:p-8 lg:p-12">

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div id="success-notification" class="bg-green-500/20 dark:bg-green-500/20 border border-green-500 dark:border-green-500 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg mb-6 transition-opacity duration-300">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div id="error-notification" class="bg-red-500/20 dark:bg-red-500/20 border border-red-500 dark:border-red-500 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg mb-6 transition-opacity duration-300">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Page Header -->
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end gap-6 mb-10">
                <div class="flex flex-col gap-2 max-w-2xl">
                    <h2 class="text-4xl md:text-5xl font-black tracking-tighter text-slate-900 dark:text-white" data-translate="Connection Requests">{{ __('Connection Requests') }}</h2>
                    <p class="text-slate-600 dark:text-text-secondary text-lg font-normal">
                        @if($type === 'received')
                            <span data-translate="You have">{{ __('You have') }}</span> <span class="text-slate-900 dark:text-white font-bold">{{ $receivedCount }} <span data-translate="{{ $receivedCount == 1 ? 'person' : 'people' }}">{{ $receivedCount == 1 ? __('person') : __('people') }}</span></span> <span data-translate="waiting for a response. Action these requests to start a conversation.">{{ __('waiting for a response. Action these requests to start a conversation.') }}</span>
                        @else
                            <span data-translate="You have sent">{{ __('You have sent') }}</span> <span class="text-slate-900 dark:text-white font-bold">{{ $sentCount }} <span data-translate="{{ $sentCount == 1 ? 'request' : 'requests' }}">{{ $sentCount == 1 ? __('request') : __('requests') }}</span></span> <span data-translate="that are pending.">{{ __('that are pending.') }}</span>
                        @endif
                    </p>
                </div>
                <!-- Action Buttons / Filters -->
                <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
                    <div class="bg-white dark:bg-surface-dark p-1 rounded-full flex w-full sm:w-auto shadow-inner border border-gray-200 dark:border-transparent">
                        <a href="{{ route('requests', ['type' => 'received', 'sort' => $sort ?? 'newest']) }}" 
                           class="flex-1 sm:flex-none px-6 py-2 rounded-full {{ $type === 'received' ? 'bg-primary text-white' : 'text-slate-600 dark:text-text-secondary hover:text-slate-900 dark:hover:text-white' }} text-sm font-bold shadow-lg transition-all transform hover:scale-105">
                            <span data-translate="Received">{{ __('Received') }}</span> ({{ $receivedCount }})
                        </a>
                        <a href="{{ route('requests', ['type' => 'sent', 'sort' => $sort ?? 'newest']) }}" 
                           class="flex-1 sm:flex-none px-6 py-2 rounded-full {{ $type === 'sent' ? 'bg-primary text-white' : 'text-slate-600 dark:text-text-secondary hover:text-slate-900 dark:hover:text-white' }} text-sm font-medium transition-colors">
                            <span data-translate="Sent">{{ __('Sent') }}</span> ({{ $sentCount }})
                        </a>
                    </div>
                </div>
            </div>

            <!-- Filters & Sorting -->
            <div class="flex flex-wrap items-center gap-3 mb-8">
                <span class="text-slate-600 dark:text-text-secondary text-sm font-medium mr-2" data-translate="Sort by:">{{ __('Sort by:') }}</span>
                <div class="relative" id="sortDropdownWrap">
                    <button type="button" id="sortDropdownBtn" class="flex items-center gap-2 px-4 py-2 rounded-full border border-gray-300 dark:border-[#392b28] hover:border-slate-600 dark:hover:border-text-secondary hover:bg-gray-100 dark:hover:bg-surface-dark transition-all group bg-white dark:bg-surface-dark" aria-haspopup="true" aria-expanded="false">
                        <span class="text-slate-900 dark:text-white text-sm">{{ ($sort ?? 'newest') === 'oldest' ? __('Oldest First') : __('Newest First') }}</span>
                        <span class="material-symbols-outlined icon-base text-slate-600 dark:text-text-secondary group-hover:text-slate-900 dark:group-hover:text-white transition-transform" id="sortDropdownIcon">expand_more</span>
                    </button>
                    <div id="sortDropdownMenu" class="absolute left-0 top-full mt-2 min-w-[180px] py-1 rounded-xl border border-gray-200 dark:border-[#392b28] bg-white dark:bg-surface-dark shadow-lg z-50 hidden">
                        <a href="{{ route('requests', ['type' => $type, 'sort' => 'newest']) }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-slate-700 dark:text-white hover:bg-gray-100 dark:hover:bg-[#322420] {{ ($sort ?? 'newest') === 'newest' ? 'bg-primary/10 text-primary dark:text-primary font-medium' : '' }}">
                            <span class="material-symbols-outlined icon-base w-6 inline-block text-center">{{ ($sort ?? 'newest') === 'newest' ? 'check' : '' }}</span>
                            <span data-translate="Newest First">{{ __('Newest First') }}</span>
                        </a>
                        <a href="{{ route('requests', ['type' => $type, 'sort' => 'oldest']) }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-slate-700 dark:text-white hover:bg-gray-100 dark:hover:bg-[#322420] {{ ($sort ?? 'newest') === 'oldest' ? 'bg-primary/10 text-primary dark:text-primary font-medium' : '' }}">
                            <span class="material-symbols-outlined icon-base w-6 inline-block text-center">{{ ($sort ?? 'newest') === 'oldest' ? 'check' : '' }}</span>
                            <span data-translate="Oldest First">{{ __('Oldest First') }}</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Grid Content -->
            @if($type === 'received' && $receivedRequests->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-12">
                    @foreach($receivedRequests as $request)
                        <div class="group bg-white dark:bg-surface-dark rounded-[2rem] p-6 hover:bg-gray-50 dark:hover:bg-[#322420] transition-all duration-300 relative border border-gray-200 dark:border-transparent hover:border-gray-300 dark:hover:border-[#392b28] shadow-sm dark:shadow-none">
                            @if($loop->first)
                            <div class="absolute top-6 right-6 bg-gray-200 dark:bg-[#392b28] text-primary text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span> New
                            </div>
                            @endif
                            <div class="flex flex-col gap-6">
                                <div class="flex items-start gap-4">
                                    <a href="{{ route('profile.view', $request->id) }}" class="relative">
                                        <div class="w-20 h-20 rounded-full bg-cover bg-center ring-4 ring-gray-200 dark:ring-[#221310]" 
                                             style='background-image: url("{{ $request->profile_image ? asset('storage/' . $request->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($request->full_name) . '&size=400&background=ec3713&color=fff' }}");'></div>
                                        <div class="absolute -bottom-1 -right-1 bg-green-500 rounded-full p-1 border-4 border-white dark:border-surface-dark" title="Online">
                                            <div class="w-2 h-2 rounded-full bg-white"></div>
                                        </div>
                                    </a>
                                    <div class="flex flex-col pt-1">
                                        <h3 class="text-slate-900 dark:text-white text-xl font-bold leading-tight group-hover:text-primary transition-colors">
                                            <a href="{{ route('profile.view', $request->id) }}">{{ $request->full_name }}</a>
                                        </h3>
                                        <p class="text-slate-600 dark:text-text-secondary text-sm mb-1">
                                            {{ $request->age ?? 'N/A' }} yrs
                                            @if($request->occupation) • {{ $request->occupation }}@endif
                                        </p>
                                        @if($request->location)
                                        <p class="text-slate-600 dark:text-text-secondary text-sm flex items-center gap-1">
                                            <span class="material-symbols-outlined icon-sm">location_on</span>
                                            {{ $request->location }}
                                        </p>
                                        @endif
                                    </div>
                                </div>
                                @if($request->languages_known)
                                <div class="bg-gray-100 dark:bg-[#221310] rounded-xl p-4">
                                    <p class="text-slate-600 dark:text-text-secondary text-sm italic line-clamp-2">"{{ \Illuminate\Support\Str::limit($request->languages_known, 100) }}"</p>
                                </div>
                                @endif
                                <div class="grid grid-cols-2 gap-3 mt-auto">
                                    <form action="{{ route('requests.accept', $request->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center justify-center h-12 rounded-full bg-primary text-white font-bold text-sm hover:bg-red-600 hover:shadow-[0_0_15px_rgba(236,55,19,0.4)] transition-all">
                                            <span data-translate="Accept">{{ __('Accept') }}</span>
                                        </button>
                                    </form>
                                    <form action="{{ route('requests.decline', $request->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center justify-center h-12 rounded-full bg-transparent border border-gray-300 dark:border-[#392b28] text-slate-600 dark:text-text-secondary font-bold text-sm hover:bg-gray-100 dark:hover:bg-[#221310] hover:text-slate-900 dark:hover:text-white transition-all">
                                            <span data-translate="Decline">{{ __('Decline') }}</span>
                                        </button>
                                    </form>
                                </div>
                                <div class="text-center">
                                    <p class="text-[11px] text-slate-500 dark:text-[#5c4743] font-medium uppercase tracking-widest">
                                        Received {{ \Carbon\Carbon::parse($request->request_created_at)->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @elseif($type === 'sent' && $sentRequests->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-12">
                    @foreach($sentRequests as $request)
                        <div class="group bg-white dark:bg-surface-dark rounded-[2rem] p-6 hover:bg-gray-50 dark:hover:bg-[#322420] transition-all duration-300 relative border border-gray-200 dark:border-transparent hover:border-gray-300 dark:hover:border-[#392b28] shadow-sm dark:shadow-none">
                            <div class="flex flex-col gap-6 h-full">
                                <div class="flex items-start gap-4">
                                    <a href="{{ route('profile.view', $request->id) }}" class="relative">
                                        <div class="w-20 h-20 rounded-full bg-cover bg-center ring-4 ring-gray-200 dark:ring-[#221310]" 
                                             style='background-image: url("{{ $request->profile_image ? asset('storage/' . $request->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($request->full_name) . '&size=400&background=ec3713&color=fff' }}");'></div>
                                    </a>
                                    <div class="flex flex-col pt-1">
                                        <h3 class="text-slate-900 dark:text-white text-xl font-bold leading-tight group-hover:text-primary transition-colors">
                                            <a href="{{ route('profile.view', $request->id) }}">{{ $request->full_name }}</a>
                                        </h3>
                                        <p class="text-slate-600 dark:text-text-secondary text-sm mb-1">
                                            {{ $request->age ?? 'N/A' }} yrs
                                            @if($request->occupation) • {{ $request->occupation }}@endif
                                        </p>
                                        @if($request->location)
                                        <p class="text-slate-600 dark:text-text-secondary text-sm flex items-center gap-1">
                                            <span class="material-symbols-outlined icon-sm">location_on</span>
                                            {{ $request->location }}
                                        </p>
                                        @endif
                                    </div>
                                </div>
                                @if($request->languages_known)
                                <div class="bg-gray-100 dark:bg-[#221310] rounded-xl p-4">
                                    <p class="text-slate-600 dark:text-text-secondary text-sm italic line-clamp-2">"{{ \Illuminate\Support\Str::limit($request->languages_known, 100) }}"</p>
                                </div>
                                @endif
                                <div class="mt-auto text-center">
                                    <span class="inline-block px-4 py-2 bg-gray-200 dark:bg-[#392b28] text-slate-600 dark:text-text-secondary text-sm font-medium rounded-full" data-translate="Pending">{{ __('Pending') }}</span>
                                </div>
                                <div class="text-center">
                                    <p class="text-[11px] text-slate-500 dark:text-[#5c4743] font-medium uppercase tracking-widest">
                                        Sent {{ \Carbon\Carbon::parse($request->request_created_at)->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="flex flex-col items-center justify-center py-20 text-center">
                    <div class="w-24 h-24 bg-gray-100 dark:bg-[#2a1d1a] rounded-full flex items-center justify-center mb-6">
                        <span class="material-symbols-outlined icon-2xl text-slate-400 dark:text-[#392b28]">inbox</span>
                    </div>
                    <h3 class="text-slate-900 dark:text-white text-xl font-bold mb-2" data-translate="No pending requests">{{ __('No pending requests') }}</h3>
                    <p class="text-slate-600 dark:text-text-secondary max-w-md mb-8">
                        @if($type === 'received')
                            <span data-translate="You're all caught up! Explore new matches to find your special someone.">{{ __('You\'re all caught up! Explore new matches to find your special someone.') }}</span>
                        @else
                            <span data-translate="You haven't sent any requests yet. Start exploring matches to send interest.">{{ __('You haven\'t sent any requests yet. Start exploring matches to send interest.') }}</span>
                        @endif
                    </p>
                    <a href="{{ route('dashboard') }}" class="bg-primary text-white px-8 py-3 rounded-full font-bold hover:bg-red-600 transition-colors" data-translate="Explore Matches">{{ __('Explore Matches') }}</a>
                </div>
            @endif
        </div>
    </main>
    </div>
    
    <!-- Global Theme Manager -->
    <script src="{{ asset('js/theme.js') }}"></script>
    <!-- Language Switcher -->
    <script src="{{ asset('js/language-switcher.js') }}"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-dismiss notifications after 5 seconds
            const notifications = document.querySelectorAll('[id*="notification"], [id*="success"], [id*="error"], [id*="info"], [id*="status"]');
            notifications.forEach(notification => {
                setTimeout(() => {
                    notification.style.opacity = '0';
                    setTimeout(() => {
                        notification.style.display = 'none';
                    }, 300);
                }, 5000);
            });

            // Sort dropdown: toggle on button click, close on outside click
            const sortBtn = document.getElementById('sortDropdownBtn');
            const sortMenu = document.getElementById('sortDropdownMenu');
            const sortIcon = document.getElementById('sortDropdownIcon');
            if (sortBtn && sortMenu) {
                sortBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const isOpen = !sortMenu.classList.contains('hidden');
                    sortMenu.classList.toggle('hidden', isOpen);
                    sortBtn.setAttribute('aria-expanded', !isOpen);
                    if (sortIcon) sortIcon.textContent = isOpen ? 'expand_more' : 'expand_less';
                });
                document.addEventListener('click', function() {
                    sortMenu.classList.add('hidden');
                    sortBtn.setAttribute('aria-expanded', 'false');
                    if (sortIcon) sortIcon.textContent = 'expand_more';
                });
                sortMenu.addEventListener('click', function(e) { e.stopPropagation(); });
            }
        });
    </script>
</body>
</html>

