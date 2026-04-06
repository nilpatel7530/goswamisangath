<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title data-translate="My Profile Overview - GoswamiSangath">{{ __('My Profile Overview - GoswamiSangath') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Spline+Sans:wght@300;400;500;600;700&family=Noto+Sans:wght@400;500;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <!-- Global Theme System -->
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#ec3713",
                        "background-light": "#f8f6f6",
                        "background-dark": "#181211",
                        "surface-dark": "#221a18",
                        "surface-light": "#ffffff",
                    },
                    fontFamily: {
                        "display": ["Spline Sans", "sans-serif"],
                        "body": ["Noto Sans", "sans-serif"],
                    },
                    borderRadius: {"DEFAULT": "1rem", "lg": "1.5rem", "xl": "2rem", "2xl": "3rem", "full": "9999px"},
                },
            },
        }
    </script>
<style>
        body { font-family: 'Spline Sans', sans-serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .material-symbols-outlined.filled { font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        
        /* Profile sections: card style with header accent */
        .profile-section {
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.05), 0 1px 2px -1px rgb(0 0 0 / 0.05);
        }
        .dark .profile-section {
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.2);
        }
        .profile-section:hover {
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.07), 0 2px 4px -2px rgb(0 0 0 / 0.05);
        }
        .dark .profile-section:hover {
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.25);
        }
        .profile-section .section-header {
            padding-bottom: 1rem;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid rgb(229 231 235);
        }
        .dark .profile-section .section-header {
            border-bottom-color: rgba(255, 255, 255, 0.08);
        }
        .profile-section .section-icon {
            width: 2.5rem;
            height: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.75rem;
            background: rgb(243 244 246);
        }
        .dark .profile-section .section-icon {
            background: rgba(255, 255, 255, 0.06);
        }
        
        /* Dropdowns: full field style, rounded, clear focus */
        #profileForm select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            cursor: pointer;
            width: 100%;
            padding: 0.625rem 2.25rem 0.625rem 0.75rem;
            font-size: 0.9375rem;
            font-weight: 500;
            line-height: 1.4;
            color: rgb(17 24 39);
            background-color: rgb(249 250 251);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.25rem 1.25rem;
            border: 1px solid rgb(209 213 219);
            border-radius: 0.75rem;
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        #profileForm select:hover {
            border-color: rgb(156 163 175);
        }
        #profileForm select:focus {
            outline: none;
            border-color: #ec3713;
            box-shadow: 0 0 0 3px rgba(236, 55, 19, 0.15);
        }
        .dark #profileForm select {
            color: rgb(243 244 246);
            background-color: rgba(255, 255, 255, 0.05);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%239ca3af' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            border-color: rgba(255, 255, 255, 0.12);
        }
        .dark #profileForm select:hover {
            border-color: rgba(255, 255, 255, 0.2);
        }
        .dark #profileForm select:focus {
            border-color: #ec3713;
            box-shadow: 0 0 0 3px rgba(236, 55, 19, 0.25);
        }
        #profileForm select option {
            background-color: white;
            color: #1f2937;
        }
        .dark #profileForm select option {
            background-color: #271d1c;
            color: #e5e7eb;
        }
        /* Inline/compact selects (e.g. DOB) */
        #profileForm select.min-w-\[80px\],
        #profileForm select.min-w-\[100px\] {
            width: auto;
            min-height: 2.5rem;
        }
        /* Text inputs & textareas: same rounded field style as dropdowns */
        #profileForm input[type="text"],
        #profileForm input[type="email"],
        #profileForm input[type="number"],
        #profileForm textarea {
            width: 100%;
            padding: 0.625rem 0.75rem;
            font-size: 0.9375rem;
            font-weight: 500;
            line-height: 1.4;
            color: rgb(17 24 39);
            background-color: rgb(249 250 251);
            border: 1px solid rgb(209 213 219);
            border-radius: 0.75rem;
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        #profileForm input[type="text"]:hover,
        #profileForm input[type="email"]:hover,
        #profileForm input[type="number"]:hover,
        #profileForm textarea:hover {
            border-color: rgb(156 163 175);
        }
        #profileForm input[type="text"]:focus,
        #profileForm input[type="email"]:focus,
        #profileForm input[type="number"]:focus,
        #profileForm textarea:focus {
            outline: none;
            border-color: #ec3713;
            box-shadow: 0 0 0 3px rgba(236, 55, 19, 0.15);
        }
        .dark #profileForm input[type="text"],
        .dark #profileForm input[type="email"],
        .dark #profileForm input[type="number"],
        .dark #profileForm textarea {
            color: rgb(243 244 246);
            background-color: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.12);
        }
        .dark #profileForm input[type="text"]:hover,
        .dark #profileForm input[type="email"]:hover,
        .dark #profileForm input[type="number"]:hover,
        .dark #profileForm textarea:hover {
            border-color: rgba(255, 255, 255, 0.2);
        }
        .dark #profileForm input[type="text"]:focus,
        .dark #profileForm input[type="email"]:focus,
        .dark #profileForm input[type="number"]:focus,
        .dark #profileForm textarea:focus {
            border-color: #ec3713;
            box-shadow: 0 0 0 3px rgba(236, 55, 19, 0.25);
        }
        #profileForm textarea {
            min-height: 5rem;
            resize: vertical;
        }
</style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Select2 Custom Styling for Dark Mode */
        .select2-container--default .select2-selection--multiple {
            background-color: #f9fafb !important;
            border: 1px solid #d1d5db !important;
            min-height: 46px;
            border-radius: 0.75rem;
            display: flex !important;
            align-items: center !important;
            padding: 2px 4px !important;
        }
        .dark .select2-container--default .select2-selection--multiple {
            background-color: rgba(255, 255, 255, 0.05) !important;
            border-color: rgba(255, 255, 255, 0.12) !important;
        }
        
        .select2-container--default .select2-selection--multiple .select2-selection__rendered {
            display: flex !important;
            flex-wrap: wrap !important;
            align-items: center !important;
            gap: 6px !important;
            padding: 0 4px !important;
            width: 100%;
            margin: 0 !important;
            list-style: none !important;
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: #ec3713 !important;
            box-shadow: 0 0 0 3px rgba(236, 55, 19, 0.15);
        }
        .dark .select2-container--default.select2-container--focus .select2-selection--multiple {
            box-shadow: 0 0 0 3px rgba(236, 55, 19, 0.25);
        }
        
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #ec3713 !important;
            border: none !important;
            color: white !important;
            border-radius: 6px !important;
            padding: 4px 12px !important;
            margin: 0 !important; 
            font-size: 0.875rem;
            font-weight: 500;
            display: flex;
            align-items: center;
        }
        
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: white !important;
            margin-right: 8px !important;
            border: none !important;
            display: flex;
            align-items: center;
            padding: 0 !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            background: none !important;
            color: rgba(255,255,255,0.8) !important;
        }

        .select2-container--default .select2-search--inline {
            flex-grow: 1;
            margin: 0 !important;
            display: flex;
            align-items: center;
        }

        /* Specific ID override to beat #profileForm textarea rule */
        #profileForm .select2-container--default .select2-search--inline .select2-search__field {
            color: inherit !important;
            font-family: inherit !important;
            background: transparent !important;
            border: none !important;
            width: 100% !important;
            height: 32px !important;
            min-height: 32px !important;
            margin: 0 !important;
            padding: 0 4px !important;
            outline: none !important;
            box-shadow: none !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__placeholder {
            color: #9ca3af !important;
            margin: 0 !important;
            line-height: normal !important;
        }

        .select2-dropdown {
            background-color: #ffffff !important;
            border-color: #e5e7eb !important;
            border-radius: 0.75rem !important;
            overflow: hidden;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            z-index: 9999;
        }
        .dark .select2-dropdown {
            background-color: #271d1c !important;
            border-color: rgba(255, 255, 255, 0.12) !important;
            color: white !important;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #ec3713 !important;
        }
        .select2-container--default .select2-results__option {
            padding: 8px 12px !important;
            font-size: 0.9375rem;
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark text-gray-900 dark:text-gray-100 min-h-screen flex flex-col overflow-x-hidden transition-colors duration-300">
    <div class="flex flex-1 overflow-hidden">
        @include('partials.user-sidebar')
        
        <!-- Main Content Area -->
        <main class="flex-1 flex flex-col gap-8 p-6 lg:p-10 max-w-[1400px] mx-auto w-full overflow-y-auto lg:ml-80">
            <!-- Success Message -->
            @if(session('success'))
                <div id="success-notification" class="bg-green-500/20 border border-green-500 text-green-300 px-4 py-3 rounded-lg transition-opacity duration-300">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Page Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 border-b border-gray-200 dark:border-white/5 pb-6">
                <div>
                    <h1 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white tracking-tight mb-2" data-translate="My Profile Overview">{{ __('My Profile Overview') }}</h1>
                    <p class="text-gray-500 dark:text-[#b9a19d] font-medium flex items-center gap-2">
                        <span class="inline-block size-2 rounded-full bg-green-500"></span>
                        <span data-translate="Last login:">{{ __('Last login:') }}</span> {{ $user->updated_at ? $user->updated_at->diffForHumans() : __('Recently') }}
                    </p>
                </div>
                <a href="{{ route('profile.view', $user) }}" class="hidden md:flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-primary transition-colors">
                    <span class="material-symbols-outlined icon-base">visibility</span>
                    <span data-translate="Preview Public View">{{ __('Preview Public View') }}</span>
                </a>
            </div>

            <!-- Form -->
            <form action="{{ route('profile.update') }}" method="POST" id="profileForm">
        @csrf
        @method('PATCH')

                <!-- Completeness Gauge & Hero -->
                <section class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch mb-6" id="overview">
                    <!-- Hero Identity Card -->
                    <div class="lg:col-span-2 relative bg-surface-light dark:bg-surface-dark rounded-xl p-6 md:p-8 flex flex-col sm:flex-row gap-6 items-center sm:items-start shadow-xl dark:shadow-none border border-gray-200 dark:border-white/5 overflow-hidden group h-full">
                        <div class="absolute top-0 right-0 p-4">
                            <div class="px-3 py-1 bg-primary/10 text-primary text-xs font-bold rounded-full uppercase tracking-widest border border-primary/20 flex items-center gap-1">
                                <span class="material-symbols-outlined icon-xs">verified</span> <span data-translate="Verified">{{ __('Verified') }}</span>
                            </div>
                        </div>
                        <div class="relative">
                            <div class="size-32 rounded-full p-1 bg-gradient-to-tr from-primary to-orange-400">
                                <img id="profile-image-preview"
                                     src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($user->full_name) . '&size=400&background=ec3713&color=fff' }}"
                                     alt="{{ $user->full_name ?? 'User' }}"
                                     class="size-full rounded-full object-cover border-4 border-surface-light dark:border-surface-dark"
                                     onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($user->full_name ?? 'User') }}&size=400&background=ec3713&color=fff';">
                            </div>
                            <button type="button" id="changePhotoBtn" class="absolute bottom-0 right-0 bg-primary text-white p-2 rounded-full shadow-lg hover:scale-110 transition-transform" data-translate-title="Change Photo" title="{{ __('Change Photo') }}">
                                <span class="material-symbols-outlined icon-xs">edit</span>
                            </button>
                            <input type="file" id="profile_image_input" class="hidden" accept="image/*">
            <input type="hidden" name="profile_image" id="profile_image_data">
        </div>
                        <div class="flex flex-col text-center sm:text-left flex-1 justify-center h-full pt-2">
                            <input type="text" name="full_name" value="{{ old('full_name', $user->full_name) }}" 
                                   class="profile-input text-3xl font-bold text-gray-900 dark:text-white mb-1 w-full" 
                                   data-translate-placeholder="Your Name" placeholder="{{ __('Your Name') }}">
                            <p class="text-gray-500 dark:text-[#b9a19d] text-lg mb-4">
                                <span id="age-display">{{ $user->dob ? Carbon\Carbon::parse($user->dob)->age : '' }}</span>
                                @if($user->occupation)
                                    <span> • {{ $user->occupation }}</span>
                                @endif
                            </p>
                            <div class="flex flex-wrap justify-center sm:justify-start gap-3 mt-auto">
                                @if($user->height)
                                <span class="bg-gray-100 dark:bg-white/5 text-gray-700 dark:text-gray-300 px-3 py-1.5 rounded-full text-sm font-medium border border-gray-200 dark:border-white/5">{{ $user->height }}</span>
                                @endif
                                @if($user->marital_status)
                                <span class="bg-gray-100 dark:bg-white/5 text-gray-700 dark:text-gray-300 px-3 py-1.5 rounded-full text-sm font-medium border border-gray-200 dark:border-white/5">{{ $user->marital_status }}</span>
                                @endif
                                @if($user->mother_tongue)
                                <span class="bg-gray-100 dark:bg-white/5 text-gray-700 dark:text-gray-300 px-3 py-1.5 rounded-full text-sm font-medium border border-gray-200 dark:border-white/5">
                                    {{ $user->mother_tongue }}
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- Completeness Gauge -->
                    <div class="bg-surface-light dark:bg-surface-dark rounded-xl p-4 md:p-5 flex flex-col justify-between shadow-xl dark:shadow-none border border-gray-200 dark:border-white/5 relative overflow-hidden h-full">
                        <div class="flex justify-between items-center mb-3 z-10">
                            <h3 class="text-base font-bold text-gray-900 dark:text-white" data-translate="Profile Strength">{{ __('Profile Strength') }}</h3>
                            <span class="text-primary font-black text-xl">{{ $completeness }}%</span>
                        </div>
                        <div class="relative h-3 bg-gray-200 dark:bg-white/10 rounded-full overflow-hidden mb-3 z-10">
                            <div class="absolute top-0 left-0 h-full bg-gradient-to-r from-primary to-orange-500 rounded-full shadow-[0_0_15px_rgba(236,55,19,0.5)]" style="width: {{ $completeness }}%"></div>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-4 z-10 leading-relaxed">
                            <span data-translate="Great job! Verify your ID to reach">{{ __('Great job! Verify your ID to reach') }}</span> <strong class="text-gray-900 dark:text-white">100%</strong> <span data-translate="and get">{{ __('and get') }}</span> <span class="text-primary font-bold" data-translate="more views">{{ __('more views') }}</span>.
                        </p>
                        <a href="{{ route('profile.verify-id') }}" class="w-full py-2 bg-gray-900 dark:bg-white text-white dark:text-black font-bold rounded-full hover:bg-primary hover:text-white dark:hover:bg-primary dark:hover:text-white transition-all z-10 flex items-center justify-center gap-2 text-sm">
                            <span data-translate="Verify ID Now">{{ __('Verify ID Now') }}</span>
                            <span class="material-symbols-outlined icon-xs">arrow_forward</span>
                        </a>
                        <div class="absolute -bottom-10 -right-10 size-40 bg-primary/10 rounded-full blur-3xl z-0"></div>
                    </div>
                </section>

                <!-- Grid Layout for Details -->
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                    <!-- Basic Info -->
                    <section class="profile-section group bg-surface-light dark:bg-surface-dark rounded-2xl border border-gray-200 dark:border-white/5 p-6 md:p-8 transition-all hover:border-primary/30 relative" id="basic-info">
                        <div class="section-header flex justify-between items-start">
                            <div class="flex items-center gap-3">
                                <div class="section-icon text-primary">
                                    <span class="material-symbols-outlined icon-lg">badge</span>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white" data-translate="Basic Information">{{ __('Basic Information') }}</h3>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-4">
                            <div class="sm:col-span-2">
                                <p class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-bold mb-2" data-translate="Date of Birth">{{ __('Date of Birth') }}</p>
                                <div class="flex gap-2 flex-wrap">
                                    <select name="birth_day" class="profile-select flex-1 min-w-[80px]">
                                        <option value="" data-translate="Day">{{ __('Day') }}</option>
                                        @for($i = 1; $i <= 31; $i++)
                                            <option value="{{ $i }}" {{ old('birth_day', $birthDay) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                    <select name="birth_month" class="profile-select flex-1 min-w-[100px]">
                                        <option value="" data-translate="Month">{{ __('Month') }}</option>
                                        @for($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}" {{ old('birth_month', $birthMonth) == $i ? 'selected' : '' }}>{{ date('M', mktime(0,0,0,$i,1)) }}</option>
                                        @endfor
                                    </select>
                                    <select name="birth_year" class="profile-select flex-1 min-w-[100px]">
                                        <option value="" data-translate="Year">{{ __('Year') }}</option>
                                        @for($i = date('Y'); $i >= 1950; $i--)
                                            <option value="{{ $i }}" {{ old('birth_year', $birthYear) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-bold mb-2" data-translate="Gender">{{ __('Gender') }}</p>
                                <select name="gender" class="profile-select w-full">
                                    <option value="" data-translate="Select">{{ __('Select') }}</option>
                                    <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }} data-translate="Male">{{ __('Male') }}</option>
                                    <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }} data-translate="Female">{{ __('Female') }}</option>
                                </select>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-bold mb-1" data-translate="Mother Tongue">{{ __('Mother Tongue') }}</p>
                                <select name="mother_tongue" class="profile-select w-full">
                                    <option value="" data-translate="Select">{{ __('Select') }}</option>
                                    @foreach($motherTongues as $mt)
                                        <option value="{{ $mt->title }}" {{ old('mother_tongue', $user->mother_tongue) == $mt->title ? 'selected' : '' }}>{{ $mt->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-bold mb-1" data-translate="Marital Status">{{ __('Marital Status') }}</p>
                                <select name="marital_status" class="profile-select w-full">
                                    <option value="" data-translate="Select">{{ __('Select') }}</option>
                                    <option value="Unmarried" {{ old('marital_status', $user->marital_status) == 'Unmarried' || old('marital_status', $user->marital_status) == 'Never Married' ? 'selected' : '' }}>Unmarried</option>
                                    <option value="Widowed" {{ old('marital_status', $user->marital_status) == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                    <option value="Divorced" {{ old('marital_status', $user->marital_status) == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                    <option value="Separated" {{ old('marital_status', $user->marital_status) == 'Separated' ? 'selected' : '' }}>Separated</option>
                                </select>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-bold mb-1" data-translate="Height">{{ __('Height') }}</p>
                                <input type="text" name="height" value="{{ old('height', $user->height) }}" 
                                       class="profile-input text-base font-semibold text-gray-900 dark:text-white w-full" 
                                       data-translate-placeholder="e.g., 5' 10\"" placeholder="{{ __('e.g., 5\' 10"') }}">
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-bold mb-1" data-translate="Raashi">{{ __('Raashi') }}</p>
                                <select name="raashi" class="profile-select w-full">
                                    <option value="" data-translate="Select">{{ __('Select') }}</option>
                                    @foreach($raashis as $raashi)
                                        <option value="{{ $raashi->name }}" {{ old('raashi', $user->raashi) == $raashi->name ? 'selected' : '' }}>{{ $raashi->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-bold mb-2" data-translate="Physically Challenged?">{{ __('Physically Challenged?') }}</p>
                                <div class="flex items-center gap-6 mt-2">
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="radio" name="is_handicapped" value="1" {{ old('is_handicapped', $user->physically_handicap == 'Yes' ? 1 : 0) == 1 ? 'checked' : '' }} class="size-5 text-primary focus:ring-primary border-gray-300 dark:border-[#543f3b] bg-gray-50 dark:bg-[#181211]">
                                        <span class="text-sm font-medium text-slate-700 dark:text-gray-300 group-hover:text-primary transition-colors" data-translate="Yes">{{ __('Yes') }}</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="radio" name="is_handicapped" value="0" {{ old('is_handicapped', $user->physically_handicap == 'Yes' ? 1 : 0) == 0 ? 'checked' : '' }} class="size-5 text-primary focus:ring-primary border-gray-300 dark:border-[#543f3b] bg-gray-50 dark:bg-[#181211]">
                                        <span class="text-sm font-medium text-slate-700 dark:text-gray-300 group-hover:text-primary transition-colors" data-translate="No">{{ __('No') }}</span>
                                    </label>
                                </div>
                            </div>
                            <div class="sm:col-span-2">
                                <p class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-bold mb-2" data-translate="Bio / About Me">{{ __('Bio / About Me') }}</p>
                                <textarea name="additional_info" rows="3" 
                                          class="profile-input text-sm text-gray-600 dark:text-gray-300 leading-relaxed w-full resize-y"
                                          data-translate-placeholder="Tell us about yourself..." placeholder="{{ __('Tell us about yourself...') }}">{{ old('additional_info', $user->additional_info) }}</textarea>
                            </div>
                        </div>
                    </section>

                    <!-- Career & Education -->
                    <section class="profile-section group bg-surface-light dark:bg-surface-dark rounded-2xl border border-gray-200 dark:border-white/5 p-6 md:p-8 transition-all hover:border-primary/30 relative" id="career">
                        <div class="section-header flex justify-between items-start">
                            <div class="flex items-center gap-3">
                                <div class="section-icon text-primary">
                                    <span class="material-symbols-outlined icon-lg">school</span>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white" data-translate="Career & Education">{{ __('Career & Education') }}</h3>
                            </div>
                        </div>
                        <div class="space-y-6">
                            <div>
                                <p class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-bold mb-1" data-translate="Highest Education">{{ __('Highest Education') }}</p>
                                <select name="highest_education_id" id="highest_education_id" class="profile-select w-full">
                                    <option value="" data-translate="Select">{{ __('Select') }}</option>
                                    @foreach($highestQualifications as $qual)
                                        <option value="{{ $qual->id }}" {{ old('highest_education_id') == $qual->id || ($user->highest_education && $user->highest_education == $qual->name) ? 'selected' : '' }}>{{ $qual->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-bold mb-1" data-translate="Education Details">{{ __('Education Details') }}</p>
                                <select name="education_id" id="education_id" class="profile-select w-full">
                                    <option value="" data-translate="Select">{{ __('Select') }}</option>
                                    @foreach($educations as $edu)
                                        <option value="{{ $edu->id }}" {{ old('education_id') == $edu->id || ($user->education_details && $user->education_details == $edu->name) ? 'selected' : '' }}>{{ $edu->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-bold mb-1" data-translate="Occupation">{{ __('Occupation') }}</p>
                                <select name="occupation_id" class="profile-select w-full">
                                    <option value="" data-translate="Select">{{ __('Select') }}</option>
                                    @foreach($occupations as $occ)
                                        <option value="{{ $occ->id }}" {{ old('occupation_id') == $occ->id || ($user->occupation && $user->occupation == $occ->name) ? 'selected' : '' }}>{{ $occ->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="pt-4 border-t border-gray-200 dark:border-white/5 grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-bold mb-1" data-translate="Annual Income">{{ __('Annual Income') }}</p>
                                    <input type="text" name="annual_income" value="{{ old('annual_income', $user->annual_income) }}" 
                                           class="profile-input text-base font-semibold text-gray-900 dark:text-white w-full" 
                                           data-translate-placeholder="e.g., ₹ 35L - 50L" placeholder="{{ __('e.g., ₹ 35L - 50L') }}">
                                </div>
                                <div>
                                    <p class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-bold mb-1" data-translate="Employed In">{{ __('Employed In') }}</p>
                                    <select name="employed_in" class="profile-select w-full">
                                        <option value="" data-translate="Select">{{ __('Select') }}</option>
                                        <option value="Business" {{ old('employed_in', $user->employed_in) == 'Business' ? 'selected' : '' }}>Business</option>
                                        <option value="Defence" {{ old('employed_in', $user->employed_in) == 'Defence' ? 'selected' : '' }}>Defence</option>
                                        <option value="Government" {{ old('employed_in', $user->employed_in) == 'Government' ? 'selected' : '' }}>Government</option>
                                        <option value="Private" {{ old('employed_in', $user->employed_in) == 'Private' ? 'selected' : '' }}>Private</option>
                                        <option value="Not Employed" {{ old('employed_in', $user->employed_in) == 'Not Employed' ? 'selected' : '' }}>Not Employed</option>
                                        <option value="Others" {{ old('employed_in', $user->employed_in) == 'Others' ? 'selected' : '' }}>Others</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Family Details / Location -->
                    <section class="profile-section group bg-surface-light dark:bg-surface-dark rounded-2xl border border-gray-200 dark:border-white/5 p-6 md:p-8 transition-all hover:border-primary/30 relative" id="family">
                        <div class="section-header flex justify-between items-start">
                            <div class="flex items-center gap-3">
                                <div class="section-icon text-primary">
                                    <span class="material-symbols-outlined icon-lg">diversity_3</span>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white" data-translate="Location">{{ __('Location') }}</h3>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-4">
                            <div>
                                <p class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-bold mb-1" data-translate="Country">{{ __('Country') }}</p>
                                <input type="text" name="country" value="{{ old('country', $user->country) }}" 
                                       class="profile-input text-base font-semibold text-gray-900 dark:text-white w-full" 
                                       data-translate-placeholder="Country" placeholder="{{ __('Country') }}">
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-bold mb-1" data-translate="State">{{ __('State') }}</p>
                                <input type="text" name="state" value="{{ old('state', $user->state) }}" 
                                       class="profile-input text-base font-semibold text-gray-900 dark:text-white w-full" 
                                       data-translate-placeholder="State" placeholder="{{ __('State') }}">
                            </div>
                            <div class="sm:col-span-2">
                                <p class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-bold mb-1" data-translate="City">{{ __('City') }}</p>
                                <input type="text" name="city" value="{{ old('city', $user->city) }}" 
                                       class="profile-input text-base font-semibold text-gray-900 dark:text-white w-full" 
                                       data-translate-placeholder="City" placeholder="{{ __('City') }}">
                            </div>
                        </div>
                    </section>

                    <!-- Lifestyle & Interests -->
                    <section class="profile-section group bg-surface-light dark:bg-surface-dark rounded-2xl border border-gray-200 dark:border-white/5 p-6 md:p-8 transition-all hover:border-primary/30 relative" id="lifestyle">
                        <div class="section-header flex justify-between items-start">
                            <div class="flex items-center gap-3">
                                <div class="section-icon text-primary">
                                    <span class="material-symbols-outlined icon-lg">local_cafe</span>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white" data-translate="Lifestyle & Interests">{{ __('Lifestyle & Interests') }}</h3>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-6 mb-6">
                            <div>
                                <p class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-bold mb-1" data-translate="Diet">{{ __('Diet') }}</p>
                                <select name="diet" class="profile-select w-full">
                                    <option value="" data-translate="Select">{{ __('Select') }}</option>
                                    <option value="Vegetarian" {{ old('diet', $user->diet) == 'Vegetarian' ? 'selected' : '' }} data-translate="Vegetarian">{{ __('Vegetarian') }}</option>
                                    <option value="Non-Vegetarian" {{ old('diet', $user->diet) == 'Non-Vegetarian' ? 'selected' : '' }} data-translate="Non-Vegetarian">{{ __('Non-Vegetarian') }}</option>
                                    <option value="Vegan" {{ old('diet', $user->diet) == 'Vegan' ? 'selected' : '' }} data-translate="Vegan">{{ __('Vegan') }}</option>
                                </select>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-bold mb-1" data-translate="Weight (kg)">{{ __('Weight (kg)') }}</p>
                                <input type="number" name="weight" value="{{ old('weight', $user->weight) }}" 
                                       class="profile-input text-base font-semibold text-gray-900 dark:text-white w-full" 
                                       data-translate-placeholder="Weight" placeholder="{{ __('Weight') }}">
                            </div>
                            <div class="col-span-2">
                                <p class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-bold mb-1" data-translate="Hobbies / Interests">{{ __('Hobbies / Interests') }}</p>
                                <select name="hobbies[]" id="hobbies_select" class="w-full select2-hobbies" multiple="multiple">
                                    @foreach($hobbies as $hobby)
                                        <option value="{{ $hobby->id }}" {{ in_array($hobby->id, $userHobbies) ? 'selected' : '' }}>{{ $hobby->name }}</option>
                                    @endforeach
                                </select>
                                <p class="text-[10px] text-gray-400 mt-2 italic">* {{ __('Select from list or type and press Enter to add new one') }}</p>
                            </div>
                        </div>
                    </section>

                    <!-- Contact Section -->
                    <section class="xl:col-span-2 group bg-surface-light dark:bg-surface-dark rounded-xl border border-gray-200 dark:border-white/5 p-6 md:p-8 transition-all hover:shadow-2xl hover:border-primary/30 relative" id="contact">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-gray-100 dark:bg-white/5 rounded-full text-primary">
                                    <span class="material-symbols-outlined icon-lg">home_pin</span>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white" data-translate="Contact & Location">{{ __('Contact & Location') }}</h3>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-bold mb-1" data-translate="Phone Number">{{ __('Phone Number') }}</p>
                                <input type="text" name="mobile_number" value="{{ old('mobile_number', $user->mobile_number) }}" 
                                       class="profile-input text-base font-semibold text-gray-900 dark:text-white w-full" 
                                       data-translate-placeholder="Mobile Number" placeholder="{{ __('Mobile Number') }}">
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-bold mb-1" data-translate="Email">{{ __('Email') }}</p>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                                       class="profile-input text-base font-semibold text-gray-900 dark:text-white w-full" 
                                       data-translate-placeholder="Email" placeholder="{{ __('Email') }}">
                                @if($user->email_verified_at)
                                <span class="material-symbols-outlined icon-xs text-green-500" title="Verified">check_circle</span>
                                @endif
                            </div>
                            <div class="md:col-span-2 space-y-4">
                                <div class="flex items-center justify-between">
                                    <p class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-bold" data-translate="Residential Address">{{ __('Residential Address') }}</p>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div>
                                        <p class="text-[10px] uppercase text-gray-400 font-bold mb-1" data-translate="Country">{{ __('Country') }}</p>
                                        <input type="text" name="residential_country" id="res_country" value="{{ old('residential_country', $user->residential_country) }}" 
                                               class="profile-input text-sm font-semibold w-full" data-translate-placeholder="Country" placeholder="{{ __('Country') }}">
                                    </div>
                                    <div>
                                        <p class="text-[10px] uppercase text-gray-400 font-bold mb-1" data-translate="State">{{ __('State') }}</p>
                                        <input type="text" name="residential_state" id="res_state" value="{{ old('residential_state', $user->residential_state) }}" 
                                               class="profile-input text-sm font-semibold w-full" data-translate-placeholder="State" placeholder="{{ __('State') }}">
                                    </div>
                                    <div>
                                        <p class="text-[10px] uppercase text-gray-400 font-bold mb-1" data-translate="City">{{ __('City') }}</p>
                                        <input type="text" name="residential_city" id="res_city" value="{{ old('residential_city', $user->residential_city) }}" 
                                               class="profile-input text-sm font-semibold w-full" data-translate-placeholder="City" placeholder="{{ __('City') }}">
                                    </div>
                                </div>
                                <textarea name="residential_address" id="res_addr" rows="2" 
                                          class="profile-input text-sm text-gray-600 dark:text-gray-300 leading-relaxed w-full resize-y"
                                          data-translate-placeholder="Building, Street, Area..." placeholder="{{ __('Building, Street, Area...') }}">{{ old('residential_address', $user->residential_address) }}</textarea>
                            </div>
                            <div class="md:col-span-2 space-y-4 mt-2">
                                <div class="flex items-center justify-between">
                                    <p class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-bold" data-translate="Working Address">{{ __('Working Address') }}</p>
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="checkbox" id="same_as_residential" class="size-4 text-primary focus:ring-primary border-gray-300 dark:border-white/10 rounded">
                                        <span class="text-[10px] font-bold text-gray-500 dark:text-gray-400 group-hover:text-primary transition-colors uppercase tracking-tight" data-translate="Same as Residential">{{ __('Same as Residential') }}</span>
                                    </label>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div>
                                        <p class="text-[10px] uppercase text-gray-400 font-bold mb-1" data-translate="Country">{{ __('Country') }}</p>
                                        <input type="text" name="working_country" id="work_country" value="{{ old('working_country', $user->working_country) }}" 
                                               class="profile-input text-sm font-semibold w-full" data-translate-placeholder="Country" placeholder="{{ __('Country') }}">
                                    </div>
                                    <div>
                                        <p class="text-[10px] uppercase text-gray-400 font-bold mb-1" data-translate="State">{{ __('State') }}</p>
                                        <input type="text" name="working_state" id="work_state" value="{{ old('working_state', $user->working_state) }}" 
                                               class="profile-input text-sm font-semibold w-full" data-translate-placeholder="State" placeholder="{{ __('State') }}">
                                    </div>
                                    <div>
                                        <p class="text-[10px] uppercase text-gray-400 font-bold mb-1" data-translate="City">{{ __('City') }}</p>
                                        <input type="text" name="working_city" id="work_city" value="{{ old('working_city', $user->working_city) }}" 
                                               class="profile-input text-sm font-semibold w-full" data-translate-placeholder="City" placeholder="{{ __('City') }}">
                                    </div>
                                </div>
                                <textarea name="working_address" id="work_addr" rows="2" 
                                          class="profile-input text-sm text-gray-600 dark:text-gray-300 leading-relaxed w-full resize-y"
                                          data-translate-placeholder="Building, Street, Area..." placeholder="{{ __('Building, Street, Area...') }}">{{ old('working_address', $user->working_address) }}</textarea>
                            </div>
                        </div>
                    </section>
                </div>

                <!-- Photos Section -->
                <section class="xl:col-span-2 group bg-surface-light dark:bg-surface-dark rounded-xl border border-gray-200 dark:border-white/5 p-6 md:p-8 transition-all hover:shadow-2xl hover:border-primary/30 relative mt-6" id="photos">
                    <div class="flex justify-between items-start mb-6">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-gray-100 dark:bg-white/5 rounded-full text-primary">
                                <span class="material-symbols-outlined">photo_library</span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white" data-translate="Photos">{{ __('Photos') }}</h3>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" id="photos-grid">
                        <!-- Existing Photos -->
                        @if($user->profile_image)
                        <div class="relative group aspect-square rounded-xl overflow-hidden border-2 border-gray-200 dark:border-white/5">
                            <img src="{{ asset('storage/' . $user->profile_image) }}"
                                 alt="Profile Photo"
                                 class="w-full h-full object-cover"
                                 onerror="this.onerror=null; this.parentElement.style.display='none';">
                            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                <button type="button" class="p-2 bg-white/20 backdrop-blur-sm rounded-full hover:bg-white/30 transition-colors" data-translate-title="Set as Profile Photo" title="{{ __('Set as Profile Photo') }}">
                                    <span class="material-symbols-outlined text-white icon-xs">star</span>
                                </button>
                                <button type="button" class="p-2 bg-white/20 backdrop-blur-sm rounded-full hover:bg-white/30 transition-colors delete-photo-btn" data-photo-id="profile" data-translate-title="Delete" title="{{ __('Delete') }}">
                                    <span class="material-symbols-outlined text-white icon-xs">delete</span>
                                </button>
                            </div>
                            <div class="absolute top-2 left-2 px-2 py-1 bg-primary text-white text-xs font-bold rounded" data-translate="Profile">{{ __('Profile') }}</div>
                        </div>
                        @endif
                        
                        <!-- Placeholder for additional photos - initially show only 1 -->
                        <div class="relative aspect-square rounded-xl border-2 border-dashed border-gray-300 dark:border-white/10 flex items-center justify-center cursor-pointer hover:border-primary transition-colors bg-gray-50 dark:bg-white/5 upload-photo-area group" data-photo-index="0">
                            <input type="file" class="hidden photo-upload-input" accept="image/*" data-index="0">
                            <div class="text-center p-4">
                                <span class="material-symbols-outlined text-gray-400 dark:text-gray-500 icon-2xl mb-2 group-hover:text-primary transition-colors">add_photo_alternate</span>
                                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium group-hover:text-primary transition-colors" data-translate="Add Photo">{{ __('Add Photo') }}</p>
                            </div>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-4 flex items-center gap-2">
                        <span class="material-symbols-outlined icon-xs">info</span>
                        <span data-translate="You can upload up to 8 additional photos. Your profile photo is displayed first.">{{ __('You can upload up to 8 additional photos. Your profile photo is displayed first.') }}</span>
                    </p>
                </section>

                <!-- Submit Button -->
                <div class="flex justify-end gap-4 mt-8">
                    <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-gray-200 dark:bg-surface-dark text-gray-700 dark:text-white rounded-full font-bold hover:bg-gray-300 dark:hover:bg-surface-border transition-colors">
                        <span data-translate="Cancel">{{ __('Cancel') }}</span>
                    </a>
                    <button type="submit" class="px-8 py-3 bg-primary text-white rounded-full font-bold hover:bg-primary-dark transition-colors shadow-lg shadow-primary/20">
                        <span data-translate="Save Changes">{{ __('Save Changes') }}</span>
                    </button>
        </div>
    </form>

            <!-- Footer -->
            @include('partials.footer')
        </main>
</div>

<!-- Cropper Modal -->
<div id="cropModal" class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center p-4 z-50 hidden">
        <div class="bg-white dark:bg-surface-dark p-6 rounded-lg shadow-xl max-w-lg w-full relative">
            <button type="button" id="closeCropModal" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 text-2xl leading-none">&times;</button>
            <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-white" data-translate="Crop Your Image">{{ __('Crop Your Image') }}</h3>
        <div><img id="imageToCrop" src=""></div>
        <div class="mt-4 flex justify-end space-x-2">
                <button type="button" id="cancelCrop" class="bg-gray-200 dark:bg-surface-border text-gray-700 dark:text-white px-4 py-2 rounded-lg font-semibold hover:bg-gray-300 dark:hover:bg-surface-dark transition" data-translate="Cancel">{{ __('Cancel') }}</button>
                <button type="button" id="cropButton" class="bg-primary text-white px-4 py-2 rounded-lg font-semibold hover:bg-primary-dark transition" data-translate="Crop and Save">{{ __('Crop and Save') }}</button>
            </div>
        </div>
    </div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const imageInput = document.getElementById('profile_image_input');
            const changePhotoBtn = document.getElementById('changePhotoBtn');
        const modal = document.getElementById('cropModal');
        const imageToCrop = document.getElementById('imageToCrop');
        const cropButton = document.getElementById('cropButton');
        const hiddenImageDataInput = document.getElementById('profile_image_data');
            const profileImagePreview = document.getElementById('profile-image-preview');
        let cropper;

            changePhotoBtn.addEventListener('click', () => {
                imageInput.click();
            });

        function closeModal() {
            modal.classList.add('hidden');
            if (cropper) {
                cropper.destroy();
                    cropper = null;
            }
        }
        
        document.getElementById('cancelCrop').addEventListener('click', () => {
            closeModal();
                imageInput.value = '';
        });
            
        document.getElementById('closeCropModal').addEventListener('click', () => {
            closeModal();
                imageInput.value = '';
    });

        imageInput.addEventListener('change', function (e) {
            const files = e.target.files;
            if (files && files.length > 0) {
                const reader = new FileReader();
                reader.onload = function (event) {
                    imageToCrop.src = event.target.result;
                    modal.classList.remove('hidden');
                        if (cropper) {
                            cropper.destroy();
                        }
                    cropper = new Cropper(imageToCrop, {
                        aspectRatio: 1,
                        viewMode: 1,
                        dragMode: 'move',
                        background: false,
                    });
                };
                reader.readAsDataURL(files[0]);
            }
        });

        cropButton.addEventListener('click', function () {
            if (cropper) {
                const canvas = cropper.getCroppedCanvas({ width: 400, height: 400 });
                const croppedImageDataURL = canvas.toDataURL('image/jpeg');
                
                hiddenImageDataInput.value = croppedImageDataURL;
                    profileImagePreview.src = croppedImageDataURL;
                    
                    closeModal();
                }
            });

            // Update age display when DOB changes
            const birthDay = document.querySelector('select[name="birth_day"]');
            const birthMonth = document.querySelector('select[name="birth_month"]');
            const birthYear = document.querySelector('select[name="birth_year"]');
            const ageDisplay = document.getElementById('age-display');

            function updateAge() {
                const day = birthDay.value;
                const month = birthMonth.value;
                const year = birthYear.value;
                
                if (day && month && year) {
                    const dob = new Date(year, month - 1, day);
                    const today = new Date();
                    let age = today.getFullYear() - dob.getFullYear();
                    const monthDiff = today.getMonth() - dob.getMonth();
                    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                        age--;
                    }
                    ageDisplay.textContent = age;
            } else {
                    ageDisplay.textContent = '';
                }
            }

            birthDay.addEventListener('change', updateAge);
            birthMonth.addEventListener('change', updateAge);
            birthYear.addEventListener('change', updateAge);

            // Initialize Select2 for hobbies
            if ($('.select2-hobbies').length > 0) {
                $('.select2-hobbies').select2({
                    tags: true,
                    tokenSeparators: [',', ';'],
                    width: '100%',
                    placeholder: "Select or type hobbies..."
                });
            }

            // Load education details when highest education changes
            const highestEducationSelect = document.getElementById('highest_education_id');
            const educationSelect = document.getElementById('education_id');

            if (highestEducationSelect) {
                highestEducationSelect.addEventListener('change', function() {
                    const qualificationId = this.value;
                    const translations = window.translations || {};
                    educationSelect.innerHTML = '<option value="">' + (translations['Loading...'] || 'Loading...') + '</option>';
                    educationSelect.disabled = true;
                    
                    if (qualificationId) {
                        fetch('/get-educations/' + qualificationId)
                            .then(response => response.json())
                            .then(data => {
                                const translations = window.translations || {};
                                educationSelect.innerHTML = '<option value="">' + (translations['Select'] || 'Select') + '</option>';
                                data.forEach(education => {
                                    const option = document.createElement('option');
                                    option.value = education.id;
                                    option.textContent = education.name;
                                    educationSelect.appendChild(option);
                                });
                                educationSelect.disabled = false;
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                const translations = window.translations || {};
                                educationSelect.innerHTML = '<option value="">' + (translations['Error loading'] || 'Error loading') + '</option>';
                                educationSelect.disabled = false;
                            });
                    } else {
                        const translations = window.translations || {};
                        educationSelect.innerHTML = '<option value="">' + (translations['Select'] || 'Select') + '</option>';
                        educationSelect.disabled = false;
                    }
                });
            }

            // Photo upload functionality with dynamic field creation
            const photosGrid = document.getElementById('photos-grid');
            let currentPhotoIndex = 0;
            const maxPhotos = 8;

            function createUploadArea(index) {
                const area = document.createElement('div');
                area.className = 'relative aspect-square rounded-xl border-2 border-dashed border-gray-300 dark:border-white/10 flex items-center justify-center cursor-pointer hover:border-primary transition-colors bg-gray-50 dark:bg-white/5 upload-photo-area group';
                area.setAttribute('data-photo-index', index);
                
                const input = document.createElement('input');
                input.type = 'file';
                input.className = 'hidden photo-upload-input';
                input.accept = 'image/*';
                input.setAttribute('data-index', index);
                
                area.innerHTML = `
                    <div class="text-center p-4">
                        <span class="material-symbols-outlined text-gray-400 dark:text-gray-500 icon-2xl mb-2 group-hover:text-primary transition-colors">add_photo_alternate</span>
                                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium group-hover:text-primary transition-colors" data-translate="Add Photo">{{ __('Add Photo') }}</p>
                    </div>
                `;
                area.appendChild(input);
                
                // Add click handler
                area.addEventListener('click', () => {
                    input.click();
                });
                
                // Add change handler
                input.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        // Check file size (max 5MB)
                        if (file.size > 5 * 1024 * 1024) {
                            alert('File size must be less than 5MB');
                            input.value = '';
                            return;
                        }
                        
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            // Replace the upload area with the uploaded photo
                            area.innerHTML = `
                                <div class="w-full h-full bg-cover bg-center rounded-xl" style="background-image: url('${event.target.result}');"></div>
                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2 rounded-xl">
                                    <button type="button" class="p-2 bg-white/20 backdrop-blur-sm rounded-full hover:bg-white/30 transition-colors delete-uploaded-photo" data-translate-title="Remove" title="{{ __('Remove') }}">
                                        <span class="material-symbols-outlined text-white icon-xs">delete</span>
                                    </button>
                                </div>
                            `;
                            area.classList.remove('border-dashed', 'border-gray-300', 'dark:border-white/10', 'bg-gray-50', 'dark:bg-white/5');
                            area.classList.add('border-solid', 'border-gray-200', 'dark:border-white/5', 'group');
                            
                            // Store the image data
                            area.dataset.imageData = event.target.result;
                            
                            // Add delete functionality
                            const deleteBtn = area.querySelector('.delete-uploaded-photo');
                            deleteBtn.addEventListener('click', function(e) {
                                e.stopPropagation();
                                // Reset the area
                                area.innerHTML = `
                                    <div class="text-center p-4">
                                        <span class="material-symbols-outlined text-gray-400 dark:text-gray-500 icon-2xl mb-2">add_photo_alternate</span>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Add Photo</p>
                                    </div>
                                `;
                                area.classList.add('border-dashed', 'border-gray-300', 'dark:border-white/10', 'bg-gray-50', 'dark:bg-white/5');
                                area.classList.remove('border-solid', 'border-gray-200', 'dark:border-white/5', 'group');
                                
                                // Recreate input
                                const newInput = document.createElement('input');
                                newInput.type = 'file';
                                newInput.className = 'hidden photo-upload-input';
                                newInput.accept = 'image/*';
                                newInput.setAttribute('data-index', index);
                                area.appendChild(newInput);
                                
                                // Reattach handlers
                                area.addEventListener('click', () => newInput.click());
                                newInput.addEventListener('change', arguments.callee);
                                
                                delete area.dataset.imageData;
                                
                                // Remove next upload areas if this was not the last one
                                removeSubsequentUploadAreas(index);
                            });
                            
                            // Show next upload area if we haven't reached max
                            if (index < maxPhotos - 1) {
                                currentPhotoIndex = index + 1;
                                const nextArea = createUploadArea(currentPhotoIndex);
                                photosGrid.appendChild(nextArea);
                            }
                        };
                        reader.readAsDataURL(file);
                    }
                });
                
                return area;
            }
            
            function removeSubsequentUploadAreas(fromIndex) {
                const areas = document.querySelectorAll('.upload-photo-area');
                areas.forEach(area => {
                    const areaIndex = parseInt(area.getAttribute('data-photo-index'));
                    if (areaIndex > fromIndex) {
                        area.remove();
                    }
                });
                // Update currentPhotoIndex to the highest remaining index
                const remainingAreas = document.querySelectorAll('.upload-photo-area');
                if (remainingAreas.length > 0) {
                    const indices = Array.from(remainingAreas).map(a => parseInt(a.getAttribute('data-photo-index')));
                    currentPhotoIndex = Math.max(...indices);
                } else {
                    currentPhotoIndex = 0;
                }
            }
            
            // Initialize with first upload area if it doesn't exist
            const existingUploadAreas = document.querySelectorAll('.upload-photo-area');
            if (existingUploadAreas.length === 0) {
                const firstArea = createUploadArea(0);
                photosGrid.appendChild(firstArea);
            } else {
                // Attach handlers to existing upload areas
                existingUploadAreas.forEach((area, idx) => {
                    const input = area.querySelector('.photo-upload-input');
                    if (input) {
                        area.addEventListener('click', () => input.click());
                        input.addEventListener('change', function(e) {
                            const file = e.target.files[0];
                            if (file) {
                                if (file.size > 5 * 1024 * 1024) {
                                    alert('File size must be less than 5MB');
                                    input.value = '';
                                    return;
                                }
                                
                                const reader = new FileReader();
                                reader.onload = function(event) {
                                    area.innerHTML = `
                                        <div class="w-full h-full bg-cover bg-center rounded-xl" style="background-image: url('${event.target.result}');"></div>
                                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2 rounded-xl">
                                            <button type="button" class="p-2 bg-white/20 backdrop-blur-sm rounded-full hover:bg-white/30 transition-colors delete-uploaded-photo" data-translate-title="Remove" title="{{ __('Remove') }}">
                                                <span class="material-symbols-outlined text-white icon-xs">delete</span>
                                            </button>
                                        </div>
                                    `;
                                    area.classList.remove('border-dashed', 'border-gray-300', 'dark:border-white/10', 'bg-gray-50', 'dark:bg-white/5');
                                    area.classList.add('border-solid', 'border-gray-200', 'dark:border-white/5', 'group');
                                    area.dataset.imageData = event.target.result;
                                    
                                    const deleteBtn = area.querySelector('.delete-uploaded-photo');
                                    deleteBtn.addEventListener('click', function(e) {
                                        e.stopPropagation();
                                        area.innerHTML = `
                                            <div class="text-center p-4">
                                                <span class="material-symbols-outlined text-gray-400 dark:text-gray-500 icon-2xl mb-2">add_photo_alternate</span>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Add Photo</p>
                                            </div>
                                        `;
                                        area.classList.add('border-dashed', 'border-gray-300', 'dark:border-white/10', 'bg-gray-50', 'dark:bg-white/5');
                                        area.classList.remove('border-solid', 'border-gray-200', 'dark:border-white/5', 'group');
                                        
                                        const newInput = document.createElement('input');
                                        newInput.type = 'file';
                                        newInput.className = 'hidden photo-upload-input';
                                        newInput.accept = 'image/*';
                                        newInput.setAttribute('data-index', idx);
                                        area.appendChild(newInput);
                                        area.addEventListener('click', () => newInput.click());
                                        newInput.addEventListener('change', arguments.callee);
                                        delete area.dataset.imageData;
                                        removeSubsequentUploadAreas(idx);
                                    });
                                    
                                    if (idx < maxPhotos - 1) {
                                        currentPhotoIndex = idx + 1;
                                        const nextArea = createUploadArea(currentPhotoIndex);
                                        photosGrid.appendChild(nextArea);
                                    }
                                };
                                reader.readAsDataURL(file);
                            }
                        });
                    }
                });
                // Set currentPhotoIndex to the highest existing index
                if (existingUploadAreas.length > 0) {
                    const indices = Array.from(existingUploadAreas).map(a => parseInt(a.getAttribute('data-photo-index')));
                    currentPhotoIndex = Math.max(...indices);
                }
            }

            // Collect all uploaded photos before form submission
            const profileForm = document.getElementById('profileForm');
            profileForm.addEventListener('submit', function(e) {
                const uploadedPhotos = [];
                const uploadAreas = document.querySelectorAll('.upload-photo-area');
                uploadAreas.forEach((area) => {
                    if (area.dataset.imageData) {
                        const index = parseInt(area.getAttribute('data-photo-index'));
                        uploadedPhotos.push({
                            index: index,
                            data: area.dataset.imageData
                        });
                    }
                });
                
                // Store photos data in a hidden input
                if (uploadedPhotos.length > 0) {
                    let photosInput = document.getElementById('additional_photos_data');
                    if (!photosInput) {
                        photosInput = document.createElement('input');
                        photosInput.type = 'hidden';
                        photosInput.name = 'additional_photos';
                        photosInput.id = 'additional_photos_data';
                        profileForm.appendChild(photosInput);
                    }
                    photosInput.value = JSON.stringify(uploadedPhotos);
                }
            });
        });
</script>
        </div>
    </div>

    <!-- Global Theme Manager -->
    <script src="{{ asset('js/theme.js') }}"></script>
    <!-- Language Switcher -->
    <script src="{{ asset('js/language-switcher.js') }}"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Same as Residential Address Logic
        const sameAsResCheckbox = document.getElementById('same_as_residential');
        const resFields = ['res_country', 'res_state', 'res_city', 'res_addr'];
        const workFields = ['work_country', 'work_state', 'work_city', 'work_addr'];
        
        function syncAddress() {
            if (sameAsResCheckbox.checked) {
                workFields.forEach((field, index) => {
                    const resElement = document.getElementById(resFields[index]);
                    const workElement = document.getElementById(field);
                    if (resElement && workElement) {
                        workElement.value = resElement.value;
                        workElement.setAttribute('readonly', true);
                        workElement.classList.add('opacity-70', 'bg-gray-100', 'dark:bg-white/5');
                    }
                });
            } else {
                workFields.forEach((field) => {
                    const workElement = document.getElementById(field);
                    if (workElement) {
                        workElement.removeAttribute('readonly');
                        workElement.classList.remove('opacity-70', 'bg-gray-100', 'dark:bg-white/5');
                    }
                });
            }
        }
        
        sameAsResCheckbox.addEventListener('change', syncAddress);
        
        // Initial sync check if already checked or if values match significantly
        // (Optional: auto-check if values are identical on load)
        const isIdentical = workFields.every((field, index) => {
            const resVal = document.getElementById(resFields[index])?.value;
            const workVal = document.getElementById(field)?.value;
            return resVal && workVal && resVal === workVal;
        });
        if (isIdentical && document.getElementById('work_addr').value !== '') {
            sameAsResCheckbox.checked = true;
            syncAddress();
        }

        // Live sync if checkbox is checked
        resFields.forEach((field, index) => {
            const resElement = document.getElementById(field);
            if (resElement) {
                resElement.addEventListener('input', () => {
                    if (sameAsResCheckbox.checked) {
                        const workElement = document.getElementById(workFields[index]);
                        if (workElement) workElement.value = resElement.value;
                    }
                });
            }
        });

        // Auto-dismiss notifications after 5 seconds
        const notifications = document.querySelectorAll('#success-notification, #error-notification, [id$="-notification"]');
        notifications.forEach(notification => {
            setTimeout(() => {
                notification.style.opacity = '0';
                setTimeout(() => {
                    notification.style.display = 'none';
                }, 300);
            }, 5000);
        });
    });
</script>
</body>
</html>
