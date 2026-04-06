<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title data-translate="View Profile">{{ __('View Profile') }} - {{ $user->full_name }} - GoswamiSangath</title>
    @php
        $isFreePlan = !isset($membership) || !$membership || $membership->name === 'Free';
    @endphp
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Spline+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <!-- Global Theme System -->
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#ec3713",
                        "background-light": "#f8f6f6",
                        "background-dark": "#221310",
                        "card-dark": "#2f1f1c",
                        "card-border": "#392b28",
                        "text-secondary": "#b9a19d"
                    },
                    fontFamily: {
                        "display": ["Spline Sans", "sans-serif"]
                    },
                    borderRadius: {"DEFAULT": "1rem", "lg": "2rem", "xl": "3rem", "full": "9999px"},
                },
            },
        }
    </script>
    <style>
        .watermark-overlay {
            background-image: repeating-linear-gradient(45deg, transparent, transparent 20px, rgba(255, 255, 255, 0.05) 20px, rgba(255, 255, 255, 0.05) 40px);
        }
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .material-symbols-outlined.filled {
            font-variation-settings: 'FILL' 1;
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-slate-900 dark:text-white overflow-x-hidden min-h-screen flex flex-col">
    <div class="flex flex-1 overflow-hidden">
        @include('partials.user-sidebar')
        
        <!-- Main Content -->
        <main class="flex-grow w-full px-4 lg:px-10 py-8 mx-auto max-w-[1400px] overflow-y-auto lg:ml-80">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 relative">
            <!-- Left Sidebar (Sticky Profile Card) -->
            <div class="lg:col-span-4 xl:col-span-3">
                <div class="sticky top-0">
                    <!-- Profile Card -->
                    <div class="bg-white dark:bg-card-dark rounded-[2rem] p-4 border border-gray-200 dark:border-card-border shadow-2xl relative overflow-hidden group">
                        <!-- Main Image -->
                        <div class="relative w-full aspect-square rounded-2xl overflow-hidden mb-5">
                            <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($user->full_name) . '&size=800&background=ec3713&color=fff' }}"
                                 alt="{{ $user->full_name }}"
                                 class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                                 onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($user->full_name) }}&size=800&background=ec3713&color=fff';">
                            <!-- Watermark Overlay -->
                            <div class="absolute inset-0 watermark-overlay opacity-30 pointer-events-none"></div>
                            <div class="absolute bottom-4 left-0 w-full text-center opacity-30 font-bold text-white text-xs tracking-widest uppercase pointer-events-none -rotate-12">GoswamiSangath ID: {{ str_pad($user->id, 6, '0', STR_PAD_LEFT) }}</div>
                            <!-- Online Status Badge -->
                            <div class="absolute top-4 left-4 bg-white/80 dark:bg-black/40 backdrop-blur-md px-3 py-1 rounded-full flex items-center gap-2 border border-gray-300/50 dark:border-white/10">
                                <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                                <span class="text-xs font-medium text-slate-900 dark:text-white" data-translate="Online Now">{{ __('Online Now') }}</span>
                            </div>
                        </div>
                        
                        <!-- Basic Info -->
                        <div class="px-2 pb-2">
                            <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-1">{{ $user->full_name }}, {{ $user->age ?? 'N/A' }}</h1>
                            <p class="text-slate-600 dark:text-text-secondary text-sm font-medium mb-4 flex items-center gap-1">
                                <span class="material-symbols-outlined icon-xs">location_on</span> {{ $user->location ?? __('Location not specified') }}
                            </p>
                            
                            <!-- Verification Chips -->
                            <div class="flex gap-2 flex-wrap mb-6">
                                @if($user->verification_status === 'verified')
                                <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-gray-100 dark:bg-[#392b28]/50 border border-gray-300 dark:border-[#392b28]">
                                    <span class="material-symbols-outlined text-primary icon-sm filled">verified_user</span>
                                    <span class="text-xs font-medium text-slate-900 dark:text-white/90" data-translate="ID Verified">{{ __('ID Verified') }}</span>
                                </div>
                                @endif
                                @if($user->mobile_number)
                                <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-gray-100 dark:bg-[#392b28]/50 border border-gray-300 dark:border-[#392b28]">
                                    <span class="material-symbols-outlined text-blue-400 icon-sm">phonelink_ring</span>
                                    <span class="text-xs font-medium text-slate-900 dark:text-white/90" data-translate="Mobile">{{ __('Mobile') }}</span>
                                </div>
                                @endif
                            </div>
                            
                            <!-- Match Score -->
                            <div class="mb-6 p-4 rounded-xl bg-gray-100 dark:bg-background-dark border border-gray-200 dark:border-card-border">
                                <div class="flex gap-6 justify-between items-end mb-2">
                                    <div class="flex flex-col">
                                        <p class="text-slate-600 dark:text-text-secondary text-xs font-medium uppercase tracking-wider">Compatibility Score</p>
                                        <p class="text-slate-900 dark:text-white text-xl font-bold leading-normal">{{ $matchPercentage }}%</p>
                                    </div>
                                    <span class="material-symbols-outlined text-primary icon-xl">favorite</span>
                                </div>
                                <div class="rounded-full bg-gray-300 dark:bg-[#543f3b] h-1.5 overflow-hidden">
                                    <div class="h-full rounded-full bg-primary" style="width: {{ $matchPercentage }}%;"></div>
                                </div>
                            </div>
                            
                            <!-- Actions -->
                            <div class="grid {{ $canChat ? 'grid-cols-2' : 'grid-cols-1' }} gap-3 mb-4">
                                <form action="{{ route('profile.toggle-shortlist', $user) }}" method="POST" class="toggle-shortlist-form {{ $canChat ? 'col-span-1' : 'col-span-1' }}" data-user-id="{{ $user->id }}">
                                    @csrf
                                    <button type="submit" class="toggle-shortlist-btn w-full flex items-center justify-center gap-2 h-12 rounded-full {{ $isShortlisted ? 'bg-primary text-white' : 'bg-gray-100 dark:bg-white text-slate-900 dark:text-background-dark' }} font-bold text-sm hover:bg-gray-200 dark:hover:bg-gray-100 hover:bg-primary-dark transition-colors">
                                        <span class="shortlist-icon material-symbols-outlined icon-md {{ $isShortlisted ? 'filled' : '' }}">star</span>
                                        <span class="shortlist-text">{{ $isShortlisted ? 'Shortlisted' : 'Shortlist' }}</span>
                                    </button>
                                </form>
                                <!-- Success Message (hidden by default) -->
                                <div class="shortlist-success-message col-span-2 w-full flex items-center justify-center gap-2 h-12 rounded-full bg-green-500/20 dark:bg-green-500/20 border border-green-500 dark:border-green-500 text-green-700 dark:text-green-400 font-bold text-sm opacity-0 pointer-events-none transition-all duration-300 translate-y-4 hidden">
                                    <span class="material-symbols-outlined icon-md filled">check_circle</span>
                                    <span class="shortlist-success-text"></span>
                                </div>
                                @if($canChat)
                                <button class="col-span-1 flex items-center justify-center gap-2 h-12 rounded-full bg-transparent border border-gray-300 dark:border-white/20 text-slate-900 dark:text-white font-bold text-sm hover:bg-gray-100 dark:hover:bg-white/5 transition-colors">
                                    <span class="material-symbols-outlined icon-md">chat_bubble</span>
                                    Chat
                                </button>
                                @endif
                                @if(!$interestSent)
                                    @if(isset($isFreePlan) && $isFreePlan)
                                        <button type="button" onclick="event.preventDefault(); event.stopPropagation(); document.getElementById('upgrade-modal').classList.remove('hidden');" class="col-span-2 w-full flex items-center justify-center gap-2 h-14 rounded-full bg-primary text-white font-bold text-base shadow-lg shadow-primary/25 hover:bg-red-600 transition-all hover:scale-[1.02]">
                                            <span class="material-symbols-outlined icon-md">favorite</span>
                                            <span data-translate="Send Interest">{{ __('Send Interest') }}</span>
                                        </button>
                                    @else
                                        <form action="{{ route('profile.send-interest', $user) }}" method="POST" class="col-span-2" id="sendInterestForm">
                                            @csrf
                                            <button type="submit" id="sendInterestBtn" class="w-full flex items-center justify-center gap-2 h-14 rounded-full bg-primary text-white font-bold text-base shadow-lg shadow-primary/25 hover:bg-red-600 transition-all hover:scale-[1.02]">
                                                <span class="material-symbols-outlined icon-md">favorite</span>
                                                <span data-translate="Send Interest">{{ __('Send Interest') }}</span>
                                            </button>
                                        </form>
                                    @endif
                                @else
                                <div class="col-span-2 flex items-center justify-center gap-2 h-14 rounded-full bg-green-500/20 border border-green-500/50 text-green-400 font-bold text-base">
                                    <span class="material-symbols-outlined icon-md">check_circle</span>
                                    <span data-translate="Interest Sent">{{ __('Interest Sent') }}</span>
                                </div>
                                @endif
                            </div>
                            
                            <!-- Safety -->
                            <div class="flex justify-center gap-6 pt-2 border-t border-gray-200 dark:border-white/5">
                                @if(isset($isBlocked) && $isBlocked)
                                <form action="{{ route('block.unblock', $user->id) }}" method="POST" class="inline-block unblock-form" data-user-id="{{ $user->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300 text-xs flex items-center gap-1 transition-colors">
                                        <span class="material-symbols-outlined icon-xs filled">check_circle</span> <span data-translate="Unblock">{{ __('Unblock') }}</span>
                                    </button>
                                </form>
                                @elseif(!$isOwnProfile)
                                <form action="{{ route('block.block', $user->id) }}" method="POST" class="inline-block block-form" data-user-id="{{ $user->id }}">
                                    @csrf
                                    <button type="submit" class="text-slate-600 dark:text-text-secondary hover:text-red-600 dark:hover:text-red-400 text-xs flex items-center gap-1 transition-colors">
                                        <span class="material-symbols-outlined icon-xs">block</span> <span data-translate="Block">{{ __('Block') }}</span>
                                    </button>
                                </form>
                                @endif
                                @if(!$isOwnProfile)
                                <a href="{{ route('report.create', $user->id) }}" class="text-slate-600 dark:text-text-secondary hover:text-slate-900 dark:hover:text-white text-xs flex items-center gap-1 transition-colors">
                                    <span class="material-symbols-outlined icon-xs">flag</span> <span data-translate="Report">{{ __('Report') }}</span>
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Content (Scrollable Details) -->
            <div class="lg:col-span-8 xl:col-span-9 space-y-8">
                <!-- Headline Section -->
                <div class="p-6 md:p-10 rounded-[2rem] bg-gradient-to-br from-gray-100 dark:from-card-dark to-gray-200 dark:to-background-dark border border-gray-300 dark:border-card-border relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-10 opacity-10">
                        <span class="material-symbols-outlined text-[120px] text-slate-900 dark:text-white">format_quote</span>
                </div>
                    <h2 class="text-slate-900 dark:text-white text-2xl md:text-4xl font-bold leading-tight relative z-10 max-w-2xl">
                        @if($user->additional_info)
                            "{{ $user->additional_info }}"
                        @else
                            "Looking for a life partner who shares similar values and interests."
                        @endif
                    </h2>
                    <div class="mt-6 flex flex-wrap gap-3 relative z-10">
                        @if($user->marital_status)
                        <span class="px-4 py-2 bg-gray-200 dark:bg-white/5 rounded-full text-sm font-medium text-slate-900 dark:text-white border border-gray-300 dark:border-white/10">{{ $user->marital_status }}</span>
                        @endif
                        @if($user->occupation)
                        <span class="px-4 py-2 bg-gray-200 dark:bg-white/5 rounded-full text-sm font-medium text-slate-900 dark:text-white border border-gray-300 dark:border-white/10">{{ $user->occupation }}</span>
                        @endif
                        @if($user->age && $user->height)
                        <span class="px-4 py-2 bg-gray-200 dark:bg-white/5 rounded-full text-sm font-medium text-slate-900 dark:text-white border border-gray-300 dark:border-white/10">{{ $user->age }} Yrs, {{ $user->height }}</span>
                        @endif
            </div>
        </div>

                <!-- About Me -->
                @if($user->additional_info)
                <div class="space-y-4">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="w-1 h-6 bg-primary rounded-full"></span>
                        <span data-translate="About">{{ __('About') }}</span> {{ explode(' ', $user->full_name)[0] }}
                    </h3>
                    <p class="text-slate-600 dark:text-text-secondary text-lg leading-relaxed font-light">
                        {{ $user->additional_info }}
                    </p>
                </div>
                @endif
                
                <!-- Photo Gallery (Grid) -->
                @if($user->profile_image)
                <div class="space-y-4">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="w-1 h-6 bg-primary rounded-full"></span>
                        Photos
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="aspect-square rounded-2xl bg-gray-100 dark:bg-card-dark border border-gray-300 dark:border-card-border overflow-hidden cursor-pointer hover:opacity-90 transition-opacity">
                            <div class="w-full h-full bg-cover bg-center" style="background-image: url('{{ asset('storage/' . $user->profile_image) }}');"></div>
                        </div>
                        <!-- Placeholder for additional photos -->
                        <div class="aspect-square rounded-2xl bg-gray-100 dark:bg-card-dark border border-gray-300 dark:border-card-border overflow-hidden flex items-center justify-center cursor-pointer hover:bg-gray-200 dark:hover:bg-white/5 transition-colors group relative">
                            <span class="relative text-slate-900 dark:text-white font-medium z-10">+ More</span>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Detailed Information Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Basic Details -->
                    <div class="bg-white dark:bg-card-dark rounded-3xl p-6 border border-gray-200 dark:border-card-border">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="p-2.5 bg-gray-100 dark:bg-background-dark rounded-xl text-primary">
                                <span class="material-symbols-outlined">person</span>
                            </div>
                            <h4 class="text-lg font-bold text-slate-900 dark:text-white" data-translate="Basic Details">{{ __('Basic Details') }}</h4>
                        </div>
                        <div class="space-y-4">
                            @if($user->age || $user->height)
                            <div class="flex justify-between border-b border-gray-200 dark:border-white/5 pb-2">
                                <span class="text-slate-600 dark:text-text-secondary text-sm">Age / Height</span>
                                <span class="text-slate-900 dark:text-white font-medium text-sm">{{ $user->age ?? 'N/A' }} Yrs{{ $user->height ? ', ' . $user->height : '' }}</span>
                            </div>
                            @endif
                            @if($user->marital_status)
                            <div class="flex justify-between border-b border-gray-200 dark:border-white/5 pb-2">
                                <span class="text-slate-600 dark:text-text-secondary text-sm">Marital Status</span>
                                <span class="text-slate-900 dark:text-white font-medium text-sm">{{ $user->marital_status }}</span>
                            </div>
                            @endif
                            @if($user->mother_tongue)
                            <div class="flex justify-between border-b border-gray-200 dark:border-white/5 pb-2">
                                <span class="text-slate-600 dark:text-text-secondary text-sm">Mother Tongue</span>
                                <span class="text-slate-900 dark:text-white font-medium text-sm">{{ $user->mother_tongue }}</span>
                            </div>
                            @endif
                            @if($user->diet)
                            <div class="flex justify-between border-b border-gray-200 dark:border-white/5 pb-2">
                                <span class="text-slate-600 dark:text-text-secondary text-sm">Diet</span>
                                <span class="text-slate-900 dark:text-white font-medium text-sm">{{ $user->diet }}</span>
                            </div>
                            @endif
                            @if($user->weight)
                            <div class="flex justify-between border-b border-gray-200 dark:border-white/5 pb-2">
                                <span class="text-slate-600 dark:text-text-secondary text-sm">Weight</span>
                                <span class="text-slate-900 dark:text-white font-medium text-sm">{{ $user->weight }} KG</span>
                            </div>
                            @endif
                            @if($user->physically_handicap)
                            <div class="flex justify-between border-b border-gray-200 dark:border-white/5 pb-2">
                                <span class="text-slate-600 dark:text-text-secondary text-sm">Physically Challenged</span>
                                <span class="text-slate-900 dark:text-white font-medium text-sm">{{ $user->physically_handicap }}</span>
                            </div>
                            @endif
                            @if($user->dob)
                            <div class="flex justify-between border-b border-gray-200 dark:border-white/5 pb-2">
                                <span class="text-slate-600 dark:text-text-secondary text-sm">Date of Birth</span>
                                <span class="text-slate-900 dark:text-white font-medium text-sm">{{ $user->dobFormatted ?? Carbon\Carbon::parse($user->dob)->format('d M Y') }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Professional Info -->
                    <div class="bg-white dark:bg-card-dark rounded-3xl p-6 border border-gray-200 dark:border-card-border">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="p-2.5 bg-gray-100 dark:bg-background-dark rounded-xl text-primary">
                                <span class="material-symbols-outlined">work</span>
                            </div>
                            <h4 class="text-lg font-bold text-slate-900 dark:text-white" data-translate="Career & Education">{{ __('Career & Education') }}</h4>
                        </div>
                        <div class="space-y-4">
                            @if($user->occupation)
                            <div class="flex flex-col gap-1 border-b border-gray-200 dark:border-white/5 pb-3">
                                <span class="text-slate-600 dark:text-text-secondary text-sm">Profession</span>
                                <span class="text-slate-900 dark:text-white font-medium text-base">{{ $user->occupation }}</span>
                            </div>
                            @endif
                            @if($user->highest_education || $user->education_details)
                            <div class="flex flex-col gap-1 border-b border-gray-200 dark:border-white/5 pb-3">
                                <span class="text-slate-600 dark:text-text-secondary text-sm" data-translate="Education">{{ __('Education') }}</span>
                                <span class="text-slate-900 dark:text-white font-medium text-base">
                                    @if($user->education_details){{ $user->education_details }}@endif
                                    @if($user->highest_education && $user->education_details) - @endif
                                    @if($user->highest_education){{ $user->highest_education }}@endif
                                </span>
                            </div>
                            @endif
                            @if($user->annual_income)
                            <div class="flex flex-col gap-1 pb-1">
                                <span class="text-slate-600 dark:text-text-secondary text-sm">Annual Income</span>
                                <span class="text-slate-900 dark:text-white font-medium text-base">{{ $user->annual_income }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Family Details -->
                    <div class="bg-white dark:bg-card-dark rounded-3xl p-6 border border-gray-200 dark:border-card-border">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="p-2.5 bg-gray-100 dark:bg-background-dark rounded-xl text-primary">
                                <span class="material-symbols-outlined">family_restroom</span>
                            </div>
                            <h4 class="text-lg font-bold text-slate-900 dark:text-white" data-translate="Family Background">{{ __('Family Background') }}</h4>
                        </div>
                        <div class="space-y-4">
                            @if($user->caste)
                            <div class="flex justify-between items-center border-b border-gray-200 dark:border-white/5 pb-2">
                                <span class="text-slate-600 dark:text-text-secondary text-sm">Caste</span>
                                <span class="text-slate-900 dark:text-white font-medium text-sm">{{ $user->caste }}</span>
                            </div>
                            @endif
                            @if($user->location)
                            <div class="flex justify-between items-center pb-2">
                                <span class="text-slate-600 dark:text-text-secondary text-sm" data-translate="Family Location">{{ __('Family Location') }}</span>
                                <span class="text-slate-900 dark:text-white font-medium text-sm">{{ $user->location }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Lifestyle & Hobbies -->
                    <div class="bg-white dark:bg-card-dark rounded-3xl p-6 border border-gray-200 dark:border-card-border">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="p-2.5 bg-gray-100 dark:bg-background-dark rounded-xl text-primary">
                                <span class="material-symbols-outlined">local_activity</span>
                            </div>
                            <h4 class="text-lg font-bold text-slate-900 dark:text-white" data-translate="Lifestyle & Interests">{{ __('Lifestyle & Interests') }}</h4>
                        </div>
                        <div class="space-y-5">
                            <div>
                                <p class="text-slate-600 dark:text-text-secondary text-xs font-medium uppercase tracking-wider mb-3">Habits</p>
                                <div class="flex gap-2 flex-wrap">
                                    <div class="px-3 py-1.5 rounded-lg bg-gray-100 dark:bg-background-dark border border-gray-300 dark:border-white/5 text-sm text-slate-900 dark:text-white flex items-center gap-2">
                                        <span class="material-symbols-outlined icon-xs text-green-400">smoke_free</span> Non-Smoker
                                    </div>
                                    <div class="px-3 py-1.5 rounded-lg bg-gray-100 dark:bg-background-dark border border-gray-300 dark:border-white/5 text-sm text-slate-900 dark:text-white flex items-center gap-2">
                                        <span class="material-symbols-outlined icon-xs text-yellow-400">no_drinks</span> Non-Drinker
                                    </div>
                                </div>
                            </div>
                            @if($user->hobbies->isNotEmpty())
                            <div>
                                <p class="text-slate-600 dark:text-text-secondary text-xs font-medium uppercase tracking-wider mb-3" data-translate="Hobbies & Interests">{{ __('Hobbies & Interests') }}</p>
                                <div class="flex gap-2 flex-wrap">
                                    @foreach($user->hobbies as $hobby)
                                        <span class="px-3 py-1 rounded-full bg-gray-100 dark:bg-white/5 hover:bg-gray-200 dark:hover:bg-white/10 text-sm text-slate-900 dark:text-white cursor-default transition-colors border border-gray-300 dark:border-white/5">{{ $hobby->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Horoscope (Full Width) -->
                @if($user->raashi)
                <div class="bg-white dark:bg-card-dark rounded-3xl p-6 md:p-8 border border-gray-200 dark:border-card-border">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="p-2.5 bg-gray-100 dark:bg-background-dark rounded-xl text-primary">
                                <span class="material-symbols-outlined">auto_awesome</span>
                            </div>
                            <h4 class="text-lg font-bold text-slate-900 dark:text-white">Horoscope Details</h4>
                        </div>
                        <span class="text-xs px-2 py-1 bg-gray-100 dark:bg-background-dark rounded border border-gray-300 dark:border-white/10 text-slate-600 dark:text-text-secondary">Optional</span>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="p-4 bg-gray-100 dark:bg-background-dark rounded-2xl text-center">
                            <p class="text-slate-600 dark:text-text-secondary text-xs uppercase tracking-wide mb-1">Raasi / Moon Sign</p>
                            <p class="text-slate-900 dark:text-white font-bold text-lg">{{ $user->raashi }}</p>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Contact Details Section -->
                @if($canViewContact || $canViewAddress)
                <div class="bg-white dark:bg-card-dark rounded-3xl p-6 border border-gray-200 dark:border-card-border overflow-hidden relative">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-2.5 bg-green-100 dark:bg-green-900/20 rounded-xl text-green-600 dark:text-green-400">
                            <span class="material-symbols-outlined">lock_open</span>
                        </div>
                        <h4 class="text-lg font-bold text-slate-900 dark:text-white">Contact Details</h4>
                    </div>
                    
                    <div class="space-y-4">
                        <!-- Mobile & Email -->
                        @if($canViewContact)
                            @if($user->mobile_number)
                            <div class="flex justify-between items-center border-b border-gray-200 dark:border-white/5 pb-3">
                                <span class="text-slate-600 dark:text-text-secondary text-sm flex items-center gap-2">
                                    <span class="material-symbols-outlined icon-base">phone</span> Mobile Number
                                </span>
                                <span class="text-slate-900 dark:text-white font-medium text-sm">{{ $user->mobile_number }}</span>
                            </div>
                            @endif
                            @if($user->email)
                            <div class="flex justify-between items-center border-b border-gray-200 dark:border-white/5 pb-3">
                                <span class="text-slate-600 dark:text-text-secondary text-sm flex items-center gap-2">
                                    <span class="material-symbols-outlined icon-base">email</span> Email
                                </span>
                                <span class="text-slate-900 dark:text-white font-medium text-sm">{{ $user->email }}</span>
                            </div>
                            @endif
                        @else
                            <div class="p-4 rounded-xl bg-gray-50 dark:bg-background-dark border border-dashed border-gray-300 dark:border-white/10 flex items-center justify-between group">
                                <div class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-slate-400 group-hover:text-primary transition-colors">contact_phone</span>
                                    <span class="text-sm text-slate-500 italic">Contact info hidden</span>
                                </div>
                                <span class="material-symbols-outlined text-slate-300 icon-xs">lock</span>
                            </div>
                        @endif

                        <!-- Addresses -->
                        @if($canViewAddress)
                            @if($user->residential_address || $user->residential_country)
                            <div class="flex flex-col gap-1 border-b border-gray-200 dark:border-white/5 pb-3">
                                <span class="text-slate-600 dark:text-text-secondary text-sm flex items-center gap-2">
                                    <span class="material-symbols-outlined icon-base">home</span> Residential Address
                                </span>
                                <span class="text-slate-900 dark:text-white font-medium text-sm leading-relaxed">
                                    {{ $user->residential_address }}<br>
                                    <span class="text-xs opacity-75">
                                        @if($user->residential_city){{ $user->residential_city }}, @endif
                                        @if($user->residential_state){{ $user->residential_state }}, @endif
                                        @if($user->residential_country){{ $user->residential_country }}@endif
                                    </span>
                                </span>
                            </div>
                            @endif
                            @if($user->working_address || $user->working_country)
                            <div class="flex flex-col gap-1 pb-1">
                                <span class="text-slate-600 dark:text-text-secondary text-sm flex items-center gap-2">
                                    <span class="material-symbols-outlined icon-base">business</span> Working Address
                                </span>
                                <span class="text-slate-900 dark:text-white font-medium text-sm leading-relaxed">
                                    {{ $user->working_address }}<br>
                                    <span class="text-xs opacity-75">
                                        @if($user->working_city){{ $user->working_city }}, @endif
                                        @if($user->working_state){{ $user->working_state }}, @endif
                                        @if($user->working_country){{ $user->working_country }}@endif
                                    </span>
                                </span>
                            </div>
                            @endif
                        @else
                            <div class="p-4 rounded-xl bg-gray-50 dark:bg-background-dark border border-dashed border-gray-300 dark:border-white/10 flex items-center justify-between group">
                                <div class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-slate-400 group-hover:text-primary transition-colors">location_off</span>
                                    <span class="text-sm text-slate-500 italic">Address hidden</span>
                                </div>
                                <span class="material-symbols-outlined text-slate-300 icon-xs">lock</span>
                            </div>
                        @endif
                    </div>
                </div>
                @else
                <!-- Contact Unlock Section (Upsell) -->
                <div class="relative rounded-3xl overflow-hidden border border-gray-300 dark:border-card-border">
                    <!-- Blurred Content Background -->
                    <div class="absolute inset-0 bg-gray-100 dark:bg-card-dark blur-sm scale-110 z-0"></div>
                    <div class="relative z-10 p-8 flex flex-col md:flex-row items-center justify-between gap-6 bg-gradient-to-r from-gray-100/95 dark:from-card-dark/95 to-gray-200/90 dark:to-background-dark/90 backdrop-blur-md">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-primary/20 flex items-center justify-center text-primary shrink-0">
                                <span class="material-symbols-outlined icon-lg">lock</span>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-slate-900 dark:text-white mb-1">Contact Details Locked</h4>
                                @if($isFreePlan && !$hasMutualInterest)
                                <p class="text-slate-600 dark:text-text-secondary text-sm" data-translate="Upgrade to Premium and establish mutual interest to view contact details.">{{ __('Upgrade to Premium and establish mutual interest to view contact details.') }}</p>
                                @elseif($isFreePlan)
                                <p class="text-slate-600 dark:text-text-secondary text-sm">Upgrade to Premium to view phone number and email.</p>
                                @elseif(!$hasMutualInterest)
                                <p class="text-slate-600 dark:text-text-secondary text-sm" data-translate="Establish mutual interest to view contact details.">{{ __('Establish mutual interest to view contact details.') }}</p>
                                @else
                                <p class="text-slate-600 dark:text-text-secondary text-sm">Upgrade to Premium to view phone number and email.</p>
                                @endif
                            </div>
                        </div>
                        @if($isFreePlan)
                        <a href="{{ route('membership') }}" class="px-8 py-3 rounded-full bg-gradient-to-r from-yellow-600 to-yellow-500 text-white font-bold text-sm shadow-lg hover:shadow-yellow-500/20 transition-all transform hover:scale-105 whitespace-nowrap">
                            Upgrade Now
                        </a>
                        @elseif(!$hasMutualInterest)
                        <div class="px-8 py-3 rounded-full bg-gray-200 dark:bg-gray-700 text-slate-600 dark:text-gray-400 font-bold text-sm whitespace-nowrap">
                            <span data-translate="Send Interest First">{{ __('Send Interest First') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </main>
    </div>

    <!-- Global Theme Manager -->
    <script src="{{ asset('js/theme.js') }}"></script>
    <!-- Language Switcher -->
    <script src="{{ asset('js/language-switcher.js') }}"></script>
    
    <script>
        // Handle Block/Unblock form submission via AJAX
        document.querySelectorAll('.block-form, .unblock-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const form = this;
                const button = form.querySelector('button[type="submit"]');
                const isUnblock = form.classList.contains('unblock-form');
                const originalText = button.innerHTML;
                const userId = form.dataset.userId;
                
                // Disable button and show loading
                button.disabled = true;
                button.innerHTML = '<span class="material-symbols-outlined icon-xs animate-spin">sync</span> ' + (isUnblock ? 'Unblocking...' : 'Blocking...');
                
                const url = isUnblock 
                    ? '{{ route("block.unblock", ":id") }}'.replace(':id', userId)
                    : '{{ route("block.block", ":id") }}'.replace(':id', userId);
                const method = isUnblock ? 'DELETE' : 'POST';
                
                fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: method === 'DELETE' ? null : JSON.stringify({})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success' || data.success) {
                        // Reload page to update UI
                        window.location.reload();
                    } else {
                        alert(data.message || 'An error occurred. Please try again.');
                        button.disabled = false;
                        button.innerHTML = originalText;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                    button.disabled = false;
                    button.innerHTML = originalText;
                });
            });
        });
        
        // Handle Send Interest form submission via AJAX
        const sendInterestForm = document.getElementById('sendInterestForm');
        if (sendInterestForm) {
            sendInterestForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const button = document.getElementById('sendInterestBtn');
                const originalContent = button.innerHTML;
                
                // Disable button and show loading state
                button.disabled = true;
                button.innerHTML = '<span class="material-symbols-outlined icon-md animate-spin">sync</span> Sending...';
                
                // Get form data
                const formData = new FormData(this);
                const url = this.action;
                
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
                        const formContainer = sendInterestForm.parentElement;
                        formContainer.innerHTML = `
                            <div class="col-span-2 flex items-center justify-center gap-2 h-14 rounded-full bg-green-500/20 border border-green-500/50 text-green-400 font-bold text-base">
                                <span class="material-symbols-outlined icon-md">check_circle</span>
                                Interest Sent
</div>
                        `;
                        
                        // Show success notification if available
                        if (data.message) {
                            // You can add a toast notification here if you have one
                            console.log(data.message);
                        }
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
        }
        
        // Handle Toggle Shortlist form submission via AJAX
        const toggleShortlistForm = document.querySelector('.toggle-shortlist-form');
        if (toggleShortlistForm) {
            toggleShortlistForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const button = this.querySelector('.toggle-shortlist-btn');
                const icon = this.querySelector('.shortlist-icon');
                const text = this.querySelector('.shortlist-text');
                const successMessage = document.querySelector('.shortlist-success-message');
                const successText = successMessage ? successMessage.querySelector('.shortlist-success-text') : null;
                const originalIcon = icon.innerHTML;
                const originalText = text ? text.textContent : '';
                const formData = new FormData(this);
                const url = this.action;
                const isCurrentlyShortlisted = button.classList.contains('bg-primary');
                
                // Show loading state
                if (icon) icon.innerHTML = '<span class="material-symbols-outlined icon-md animate-spin">sync</span>';
                if (text) text.textContent = 'Updating...';
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
                        // Update button state
                        if (data.isShortlisted) {
                            button.classList.add('bg-primary', 'text-white');
                            button.classList.remove('bg-gray-100', 'dark:bg-white', 'text-slate-900', 'dark:text-background-dark');
                            if (icon) {
                                icon.innerHTML = '<span class="material-symbols-outlined icon-md filled">star</span>';
                                icon.classList.add('filled');
                            }
                            if (text) text.textContent = 'Shortlisted';
                        } else {
                            button.classList.remove('bg-primary', 'text-white');
                            button.classList.add('bg-gray-100', 'dark:bg-white', 'text-slate-900', 'dark:text-background-dark');
                            if (icon) {
                                icon.innerHTML = '<span class="material-symbols-outlined icon-md">star</span>';
                                icon.classList.remove('filled');
                            }
                            if (text) text.textContent = 'Shortlist';
                        }
                        
                        // Show success message
                        if (successMessage && successText) {
                            successText.textContent = data.message;
                            successMessage.classList.remove('hidden');
                            setTimeout(() => {
                                successMessage.classList.remove('opacity-0', 'pointer-events-none', 'translate-y-4');
                                successMessage.classList.add('opacity-100');
                                successMessage.style.transform = 'translateY(0)';
                            }, 50);
                            
                            // Hide success message after 3 seconds
                            setTimeout(() => {
                                successMessage.classList.add('opacity-0', 'translate-y-4');
                                setTimeout(() => {
                                    successMessage.classList.add('hidden');
                                }, 300);
                            }, 3000);
                        }
                        
                        button.disabled = false;
                    } else {
                        // Show error
                        if (icon) icon.innerHTML = originalIcon;
                        if (text) text.textContent = originalText;
                        button.disabled = false;
                        alert(data.message || 'Failed to update shortlist. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (icon) icon.innerHTML = originalIcon;
                    if (text) text.textContent = originalText;
                    button.disabled = false;
                    alert('An error occurred. Please try again.');
                });
            });
        }
    </script>
    @include('partials.upgrade-modal')
</body>
</html>
