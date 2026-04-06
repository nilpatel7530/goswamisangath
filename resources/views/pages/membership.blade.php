<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title data-translate="Membership Plans & Comparison">{{ __('Membership Plans & Comparison') }}</title>
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
                        "background-dark": "#181211",
                        "surface-dark": "#271d1c",
                        "surface-darker": "#1f1615",
                        "border-dark": "#392b28",
                        "text-dim": "#b9a19d",
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
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-background-light dark:bg-background-dark font-display antialiased text-slate-900 dark:text-white selection:bg-primary selection:text-white min-h-screen flex flex-col overflow-x-hidden">
    @if(Auth::check())
        <div class="flex flex-1 overflow-hidden">
            @include('partials.user-sidebar')
            <main class="flex-1 flex flex-col min-h-screen relative overflow-y-auto lg:ml-80">
    @else
        <div class="flex flex-col min-h-screen">
            @include('partials.top-navbar')
            <main class="flex-1 flex flex-col min-h-screen relative overflow-y-auto">
    @endif
                <!-- Main Content Wrapper -->
                <div class="layout-container flex h-full grow flex-col items-center">
                    <!-- Hero Heading -->
                    <div class="w-full max-w-[1200px] px-4 md:px-10 py-12 md:py-16 text-center">
                        <h1 class="text-slate-900 dark:text-white text-4xl md:text-6xl font-black leading-tight tracking-[-0.033em] mb-4">
                            {{ __('Invest in your') }} <span class="text-primary">{{ __('future') }}</span> {{ __('together') }}
                        </h1>
                        <p class="text-slate-600 dark:text-text-dim text-lg font-normal leading-normal max-w-2xl mx-auto" data-translate="Select a membership plan that suits your journey. Unlock premium features to find your perfect match faster and more securely.">
                            {{ __('Select a membership plan that suits your journey. Unlock premium features to find your perfect match faster and more securely.') }}
                        </p>
                    </div>

                    <!-- Error Messages -->
                    @if(session('error'))
                        <div class="w-full max-w-[1200px] px-4 md:px-6 mb-6">
                            <div class="bg-red-500/20 dark:bg-red-500/20 border border-red-500 dark:border-red-500 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined">error</span>
                                    <span>{{ session('error') }}</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Pricing Cards Grid -->
                    <div class="w-full max-w-[1200px] px-4 md:px-6 mb-16">
                        @php
                            $totalPlans = count($memberships);
                            $gridClass = 'grid-cols-1';
                            if ($totalPlans == 1) $gridClass .= ' md:grid-cols-1';
                            elseif ($totalPlans == 2) $gridClass .= ' md:grid-cols-2';
                            elseif ($totalPlans == 3) $gridClass .= ' md:grid-cols-2 lg:grid-cols-3';
                            else $gridClass .= ' md:grid-cols-2 lg:grid-cols-4';
                        @endphp
                        <div class="grid {{ $gridClass }} gap-6">
                            <!-- Dynamic Plans from Database -->
                            @foreach($memberships as $plan)
                                <div class="relative flex flex-col gap-6 rounded-2xl {{ $plan->is_featured ? 'border-2 border-primary bg-white dark:bg-surface-darker shadow-[0_0_30px_rgba(236,55,19,0.15)] transform md:-translate-y-4' : 'border border-solid border-gray-300 dark:border-border-dark bg-white dark:bg-surface-dark hover:border-slate-400 dark:hover:border-text-dim transition-colors duration-300' }} p-8">
                                    @if($plan->is_featured)
                                        <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-primary text-white text-xs font-bold px-4 py-1 rounded-full shadow-lg whitespace-nowrap" data-translate="MOST POPULAR">
                                            {{ __('MOST POPULAR') }}
                                        </div>
                                    @endif
                                    <div class="flex flex-col gap-2 {{ $plan->is_featured ? 'mt-2' : '' }}">
                                        <div class="flex items-center justify-between">
                                            <h3 class="text-slate-900 dark:text-white text-xl font-bold">{{ $plan->name }}</h3>
                                            @if($plan->badge)
                                                <span class="{{ $plan->is_featured ? 'bg-primary/20 text-primary' : 'text-slate-600 dark:text-text-dim border border-gray-300 dark:border-border-dark' }} text-xs font-bold uppercase tracking-wider rounded-full px-3 py-1">{{ $plan->badge }}</span>
                                            @endif
                                        </div>
                                        <div class="flex items-baseline gap-1 mt-2">
                                            <span class="text-slate-900 dark:text-white text-5xl font-black tracking-tighter">₹{{ number_format($plan->price) }}</span>
                                            <span class="text-slate-600 dark:text-text-dim text-base font-medium" data-translate="/mo">{{ __('/mo') }}</span>
                                        </div>
                                        <p class="text-slate-600 dark:text-text-dim text-sm" data-translate="Billed monthly.">{{ $plan->description ?? __('Billed monthly.') }}</p>
                                    </div>
                                    <div class="h-px w-full bg-gray-200 dark:bg-border-dark my-2"></div>
                                    <div class="flex flex-col gap-4 flex-1">
                                        @if($plan->features)
                                            @foreach(explode("\n", $plan->features) as $feature)
                                                @if(trim($feature))
                                                    <div class="flex items-start gap-3 text-sm text-slate-900 dark:text-white">
                                                        <span class="material-symbols-outlined text-primary">check_circle</span>
                                                        <span>{{ trim($feature) }}</span>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @else
                                            <!-- Default features if none specified -->
                                            <div class="flex items-start gap-3 text-sm text-slate-900 dark:text-white">
                                                <span class="material-symbols-outlined text-primary">check_circle</span>
                                                <span><span data-translate="View">{{ __('View') }}</span> {{ $plan->visits_allowed }} <span data-translate="Phone Numbers">{{ __('Phone Numbers') }}</span></span>
                                            </div>
                                            <div class="flex items-start gap-3 text-sm {{ $plan->price > 0 ? 'text-slate-900 dark:text-white' : 'text-slate-400 dark:text-gray-500 line-through' }}">
                                                <span class="material-symbols-outlined {{ $plan->price > 0 ? 'text-primary' : 'text-slate-400 dark:text-gray-500' }}">{{ $plan->price > 0 ? 'check_circle' : 'cancel' }}</span>
                                                <span data-translate="Unlimited Direct Chat">{{ __('Unlimited Direct Chat') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    @if($currentUserMembership && $currentUserMembership->id == $plan->id)
                                        <button class="w-full h-12 rounded-full bg-primary/20 border border-primary text-primary text-sm font-bold" data-translate="Current Plan">
                                            {{ __('Current Plan') }}
                                        </button>
                                    @else
                                        @auth
                                        @if($plan->price <= 0)
                                        <form action="{{ route('subscribe', $plan->id) }}" method="POST" class="w-full">
                                            @csrf
                                            <input type="hidden" name="billing_period" value="monthly">
                                            <button type="submit" class="w-full h-12 rounded-full {{ $plan->is_featured ? 'bg-primary text-white hover:bg-red-600 shadow-lg shadow-red-900/20' : 'border border-gray-300 dark:border-border-dark bg-transparent text-slate-900 dark:text-white hover:bg-gray-100 dark:hover:bg-border-dark' }} text-sm font-bold transition-colors">
                                                @if($plan->is_featured)
                                                    <span data-translate="Upgrade to">{{ __('Upgrade to') }}</span> {{ $plan->name }}
                                                @else
                                                    <span data-translate="Select">{{ __('Select') }}</span> {{ $plan->name }}
                                                @endif
                                            </button>
                                        </form>
                                        @else
                                        <a href="{{ route('payment.create', $plan->id) }}" class="block w-full h-12 rounded-full {{ $plan->is_featured ? 'bg-primary text-white hover:bg-red-600 shadow-lg shadow-red-900/20' : 'border border-gray-300 dark:border-border-dark bg-transparent text-slate-900 dark:text-white hover:bg-gray-100 dark:hover:bg-border-dark' }} text-sm font-bold transition-colors text-center leading-[48px]">
                                            @if($plan->is_featured)
                                                <span data-translate="Upgrade to">{{ __('Upgrade to') }}</span> {{ $plan->name }}
                                            @else
                                                <span data-translate="Select">{{ __('Select') }}</span> {{ $plan->name }}
                                            @endif
                                        </a>
                                        @endif
                                        @else
                                        <a href="{{ route('login') }}" class="block w-full h-12 rounded-full {{ $plan->is_featured ? 'bg-primary text-white hover:bg-red-600 shadow-lg shadow-red-900/20' : 'border border-gray-300 dark:border-border-dark bg-transparent text-slate-900 dark:text-white hover:bg-gray-100 dark:hover:bg-border-dark' }} text-sm font-bold transition-colors text-center leading-[48px]">
                                            @if($plan->is_featured)
                                                <span data-translate="Upgrade to">{{ __('Upgrade to') }}</span> {{ $plan->name }}
                                            @else
                                                <span data-translate="Select">{{ __('Select') }}</span> {{ $plan->name }}
                                            @endif
                                        </a>
                                        @endauth
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Detailed Feature Comparison -->
                    @if(count($memberships) > 0)
                    <div class="w-full max-w-[960px] px-4 md:px-6 mb-20">
                        <h2 class="text-slate-900 dark:text-white text-2xl md:text-3xl font-bold mb-8 text-center" data-translate="Detailed Comparison">{{ __('Detailed Comparison') }}</h2>
                        <div class="overflow-hidden rounded-2xl border border-gray-300 dark:border-border-dark bg-white dark:bg-surface-dark">
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-gray-100 dark:bg-surface-darker border-b border-gray-300 dark:border-border-dark">
                                            <th class="p-4 md:p-6 text-sm font-bold text-slate-600 dark:text-text-dim uppercase tracking-wider" style="width: 30%;" data-translate="Features">{{ __('Features') }}</th>
                                            @foreach($memberships as $plan)
                                                <th class="p-4 md:p-6 text-sm font-bold {{ $plan->is_featured ? 'text-primary' : 'text-slate-900 dark:text-white' }} text-center" style="width: {{ 70 / count($memberships) }}%;">{{ $plan->name }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody class="text-sm">
                                        <tr class="border-b border-gray-200 dark:border-border-dark hover:bg-gray-50 dark:hover:bg-background-dark/50 transition-colors">
                                            <td class="p-4 md:px-6 md:py-4 font-medium text-slate-900 dark:text-white" data-translate="Browse Profiles">{{ __('Browse Profiles') }}</td>
                                            @foreach($memberships as $plan)
                                                <td class="p-4 text-center"><span class="material-symbols-outlined text-primary">check_circle</span></td>
                                            @endforeach
                                        </tr>
                                        <tr class="border-b border-gray-200 dark:border-border-dark hover:bg-gray-50 dark:hover:bg-background-dark/50 transition-colors">
                                            <td class="p-4 md:px-6 md:py-4 font-medium text-slate-900 dark:text-white" data-translate="Send Interest">{{ __('Send Interest') }}</td>
                                            @foreach($memberships as $plan)
                                                <td class="p-4 text-center text-slate-900 dark:text-white">
                                                    @if($plan->visits_allowed >= 999)
                                                        <span data-translate="Unlimited">{{ __('Unlimited') }}</span>
                                                    @else
                                                        {{ $plan->visits_allowed }}<span data-translate="/month">{{ __('/month') }}</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                        <tr class="border-b border-gray-200 dark:border-border-dark hover:bg-gray-50 dark:hover:bg-background-dark/50 transition-colors">
                                            <td class="p-4 md:px-6 md:py-4 font-medium text-slate-900 dark:text-white" data-translate="Instant Chat">{{ __('Instant Chat') }}</td>
                                            @foreach($memberships as $plan)
                                                <td class="p-4 text-center">
                                                    @if($plan->price > 0)
                                                        <span class="material-symbols-outlined text-primary">check_circle</span>
                                                    @else
                                                        <span class="material-symbols-outlined text-slate-300 dark:text-border-dark">cancel</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                        <tr class="border-b border-gray-200 dark:border-border-dark hover:bg-gray-50 dark:hover:bg-background-dark/50 transition-colors">
                                            <td class="p-4 md:px-6 md:py-4 font-medium text-slate-900 dark:text-white" data-translate="Price (Monthly)">{{ __('Price (Monthly)') }}</td>
                                            @foreach($memberships as $plan)
                                                <td class="p-4 text-center text-slate-900 dark:text-white font-bold">₹{{ number_format($plan->price) }}</td>
                                            @endforeach
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif


                </div>
            </main>
        </div>

    
    <!-- Global Theme Manager -->
    <script src="{{ asset('js/theme.js') }}"></script>
    <!-- Language Switcher - Load BEFORE Alpine.js -->
    <script src="{{ asset('js/language-switcher.js') }}"></script>
    <!-- Alpine.js for dropdown functionality -->
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
</body>
</html>
