<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Profile Creation - GoswamiSangath</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
                        "surface-dark": "#2c1c19",
                        "surface-light": "#ffffff",
                    },
                    fontFamily: {
                        "display": ["Plus Jakarta Sans", "sans-serif"],
                        "body": ["Plus Jakarta Sans", "sans-serif"],
                    },
                    borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px"},
                },
            },
        }
    </script>
    <!-- Global Theme System -->
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    <style>
        /* Custom scrollbar for better aesthetic in dark mode */
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
            background: #543f3b;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #ec3713;
        }
        
        /* Subtle entrance animation */
        .fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
            opacity: 0;
            transform: translateY(20px);
        }
        
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
        
        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Hide default tailwind forms select arrow since we use custom material icons */
        select {
            background-image: none !important;
        }

        /* Select2 Custom Styling for Dark Mode */
        .select2-container--default .select2-selection--multiple {
            background-color: #ffffff !important;
            border: 1px solid #e5e7eb !important;
            min-height: 48px;
            border-radius: 0.5rem;
            display: flex !important;
            flex-wrap: wrap !important;
            padding: 2px !important;
        }
        .dark .select2-container--default .select2-selection--multiple {
            background-color: #181211 !important;
            border-color: #543f3b !important;
        }
        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: #ec3713 !important;
            box-shadow: 0 0 0 3px rgba(236, 55, 19, 0.15) !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #ec3713 !important;
            border: none !important;
            color: white !important;
            border-radius: 4px !important;
            padding: 4px 10px !important;
            margin: 4px !important;
            display: flex !important;
            align-items: center !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: white !important;
            margin-right: 8px !important;
            border: none !important;
            background: transparent !important;
            font-size: 18px !important;
            line-height: 1 !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            color: #ffcccc !important;
            background: transparent !important;
        }
        .select2-dropdown {
            background-color: #ffffff !important;
            border-color: #e5e7eb !important;
            z-index: 9999 !important;
        }
        .dark .select2-dropdown {
            background-color: #2c1c19 !important;
            border-color: #543f3b !important;
            color: white !important;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #ec3713 !important;
        }
        .select2-container--default .select2-search--inline .select2-search__field {
            color: inherit !important;
            font-family: inherit !important;
            margin-top: 4px !important;
            padding-left: 8px !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__placeholder {
            color: #9ca3af !important;
            margin-top: 8px !important;
            padding-left: 8px !important;
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-white font-display overflow-x-hidden transition-colors duration-300">
    @include('partials.top-navbar')

    <main class="flex flex-col min-h-screen items-center py-10 px-4 md:px-0 bg-[url('https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?q=80&w=2564&auto=format&fit=crop')] bg-cover bg-center bg-no-repeat bg-fixed relative">
        <!-- Overlay for better readability on bg image -->
        <div class="absolute inset-0 bg-background-light/95 dark:bg-background-dark/95 backdrop-blur-sm z-0"></div>
        
        <div class="relative z-10 w-full max-w-[800px] flex flex-col gap-8 fade-in-up">
            <!-- Progress Bar -->
            <div class="w-full">
                <div class="flex flex-col gap-3">
                    <div class="flex gap-6 justify-between items-end">
                        <div class="flex flex-col">
                            <span class="text-primary text-sm font-bold tracking-widest uppercase mb-1" data-translate="Step 1 of 2">{{ __('Step 1 of 2') }}</span>
                            <h2 class="text-slate-900 dark:text-white text-3xl md:text-4xl font-bold font-display italic" data-translate="Create Your Profile">{{ __('Create Your Profile') }}</h2>
                        </div>
                        <p class="text-slate-500 dark:text-[#b9a19d] text-lg font-medium font-display">50%</p>
                    </div>
                    <div class="rounded-full bg-gray-200 dark:bg-[#392b28] h-1.5 overflow-hidden">
                        <div class="h-full rounded-full bg-primary shadow-[0_0_10px_rgba(236,55,19,0.5)]" style="width: 50%;"></div>
                    </div>
                </div>
            </div>

            <!-- Main Form Card -->
            <div class="bg-white dark:bg-surface-dark border border-gray-100 dark:border-[#392b28] rounded-2xl p-8 md:p-10 shadow-xl fade-in-up delay-100">
                <div class="mb-8 text-center md:text-left">
                    <p class="text-slate-600 dark:text-gray-300 text-lg font-normal leading-relaxed" data-translate="Help us find your perfect match by sharing your cultural and professional background. Accuracy here leads to better compatibility.">
                        {{ __('Help us find your perfect match by sharing your cultural and professional background. Accuracy here leads to better compatibility.') }}
                    </p>
                </div>

                @if($errors->any())
                    <div class="mb-6 bg-red-500/10 border border-red-500/50 rounded-lg p-4">
                        <ul class="text-red-400 text-sm space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('success'))
                    <div id="success-notification" class="mb-6 bg-green-500/10 border border-green-500/50 rounded-lg p-4 transition-opacity duration-300">
                        <p class="text-green-400 text-sm">{{ session('success') }}</p>
                    </div>
                @endif

                <form action="{{ route('signup.store') }}" method="POST" class="flex flex-col gap-10" id="registration-form" enctype="multipart/form-data">
                    @csrf

                    <!-- Section 0: Basic Information -->
                    <section class="flex flex-col gap-6">
                        <div class="border-b border-gray-100 dark:border-[#392b28] pb-4 flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary">person</span>
                            <div class="flex flex-col">
                                <span class="text-[10px] text-primary font-bold uppercase tracking-wider" data-translate="Person">{{ __('Person') }}</span>
                                <h3 class="text-slate-900 dark:text-white text-xl font-bold leading-tight" data-translate="Basic Information">{{ __('Basic Information') }}</h3>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="flex flex-col gap-2 md:col-span-2">
                                <label class="text-slate-700 dark:text-gray-200 text-sm font-medium" data-translate="Profile Picture">{{ __('Profile Picture') }}</label>
                                <div class="flex items-center gap-4">
                                    <div class="flex flex-col items-center gap-2">
                                        <img id="profile-preview" src="https://placehold.co/120x120/e2f0cb/702963?text=Preview" alt="{{ __('Profile Preview') }}" data-translate-alt="Profile Preview" class="w-24 h-24 rounded-full border-2 border-gray-300 dark:border-[#543f3b] object-cover">
                                        <span class="text-[10px] text-gray-500 uppercase tracking-tight font-medium" data-translate="Profile Preview">{{ __('Profile Preview') }}</span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="relative group">
                                            <input type="file" 
                                                   id="profile_image_input" 
                                                   name="profile_image_input" 
                                                   accept="image/*"
                                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                                   onchange="handleFileSelect(this)"/>
                                            <div class="flex items-center gap-3 w-full h-12 rounded-lg bg-gray-50 dark:bg-[#181211] border border-gray-200 dark:border-[#543f3b] px-4 py-2 text-slate-500 dark:text-gray-400 font-body text-sm group-hover:border-primary transition-colors">
                                                <span class="material-symbols-outlined text-[20px] text-primary">upload_file</span>
                                                <span id="file-name" data-translate="No file chosen">{{ __('No file chosen') }}</span>
                                            </div>
                                        </div>
                                        <input type="hidden" name="profile_image" id="profile_image_data">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1" data-translate="Upload your profile picture (optional)">{{ __('Upload your profile picture (optional)') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-col gap-2">
                                <label class="text-slate-700 dark:text-gray-200 text-sm font-medium"><span data-translate="Full Name">{{ __('Full Name') }}</span> <span class="text-primary">*</span></label>
                                <input type="text" 
                                       name="full_name" 
                                       value="{{ old('full_name', $user->full_name) }}"
                                       class="w-full h-12 rounded-lg bg-gray-50 dark:bg-[#181211] border border-gray-200 dark:border-[#543f3b] pl-4 pr-10 text-slate-900 dark:text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all placeholder-gray-400 dark:placeholder-gray-600 font-body" 
                                       placeholder="{{ __('Enter your full name') }}" data-translate-placeholder="Enter your full name"
                                       required/>
                            </div>
                            <div class="flex flex-col gap-2">
                                <label class="text-slate-700 dark:text-gray-200 text-sm font-medium"><span data-translate="Gender">{{ __('Gender') }}</span> <span class="text-primary">*</span></label>
                                <div class="relative">
                                    <select name="gender" 
                                            class="w-full h-12 rounded-lg bg-gray-50 dark:bg-[#181211] border border-gray-200 dark:border-[#543f3b] pl-4 pr-10 text-slate-900 dark:text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none appearance-none transition-all cursor-pointer font-body" 
                                            required>
                                        <option disabled selected value="" data-translate="Select Gender">{{ __('Select Gender') }}</option>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }} data-translate="Male">{{ __('Male') }}</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }} data-translate="Female">{{ __('Female') }}</option>
                                    </select>
                                    <span class="material-symbols-outlined absolute right-3 top-3 text-gray-400 pointer-events-none">expand_more</span>
                                </div>
                            </div>
                            <div class="flex flex-col gap-2">
                                <label class="text-slate-700 dark:text-gray-200 text-sm font-medium"><span data-translate="Email">{{ __('Email') }}</span> <span class="text-primary">*</span></label>
                                <input type="email" 
                                       name="email" 
                                       value="{{ old('email', $user->email) }}"
                                       class="w-full h-12 rounded-lg bg-gray-50 dark:bg-[#181211] border border-gray-200 dark:border-[#543f3b] pl-4 pr-10 text-slate-900 dark:text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all placeholder-gray-400 dark:placeholder-gray-600 font-body" 
                                       placeholder="{{ __('your@email.com') }}" data-translate-placeholder="your@email.com"
                                       required/>
                            </div>
                            <div class="flex flex-col gap-2">
                                <label class="text-slate-700 dark:text-gray-200 text-sm font-medium"><span data-translate="Mobile Number">{{ __('Mobile Number') }}</span> <span class="text-primary">*</span></label>
                                <input type="tel" 
                                       name="mobile_number" 
                                       value="{{ old('mobile_number') }}"
                                       class="w-full h-12 rounded-lg bg-gray-50 dark:bg-[#181211] border border-gray-200 dark:border-[#543f3b] pl-4 pr-10 text-slate-900 dark:text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all placeholder-gray-400 dark:placeholder-gray-600 font-body" 
                                       placeholder="{{ __('+91 9876543210') }}" data-translate-placeholder="+91 9876543210"
                                       required/>
                            </div>
                            <div class="flex flex-col gap-2">
                                <label class="text-slate-700 dark:text-gray-200 text-sm font-medium"><span data-translate="Password">{{ __('Password') }}</span> <span class="text-primary">*</span></label>
                                <input type="password" 
                                       id="password"
                                       name="password" 
                                       class="w-full h-12 rounded-lg bg-gray-50 dark:bg-[#181211] border border-gray-200 dark:border-[#543f3b] pl-4 pr-10 text-slate-900 dark:text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all placeholder-gray-400 dark:placeholder-gray-600 font-body" 
                                       placeholder="{{ __('Minimum 8 characters') }}" data-translate-placeholder="Minimum 8 characters"
                                       required/>
                            </div>
                            <div class="flex flex-col gap-2">
                                <label class="text-slate-700 dark:text-gray-200 text-sm font-medium"><span data-translate="Confirm Password">{{ __('Confirm Password') }}</span> <span class="text-primary">*</span></label>
                                <div class="relative">
                                    <input type="password" 
                                           id="password_confirmation"
                                           name="password_confirmation" 
                                           class="w-full h-12 rounded-lg bg-gray-50 dark:bg-[#181211] border border-gray-200 dark:border-[#543f3b] pl-4 pr-10 text-slate-900 dark:text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all placeholder-gray-400 dark:placeholder-gray-600 font-body" 
                                           placeholder="{{ __('Confirm your password') }}" data-translate-placeholder="Confirm your password"
                                           required/>
                                    <div id="password-match-indicator" class="absolute right-3 top-3 hidden">
                                        <span class="material-symbols-outlined text-green-500" id="match-icon">check_circle</span>
                                        <span class="material-symbols-outlined text-red-500 hidden" id="mismatch-icon">cancel</span>
                                    </div>
                                </div>
                                <p id="password-error-message" class="text-xs text-red-500 mt-1 hidden" data-translate="Passwords do not match">{{ __('Passwords do not match') }}</p>
                            </div>
                            <div class="flex flex-col gap-2 md:col-span-2">
                                <label class="text-slate-700 dark:text-gray-200 text-sm font-medium"><span data-translate="Date of Birth">{{ __('Date of Birth') }}</span> <span class="text-primary">*</span></label>
                                <div class="grid grid-cols-3 gap-4">
                                    <div class="flex flex-col gap-2">
                                        <label class="text-xs text-gray-500 dark:text-gray-400" data-translate="Day">{{ __('Day') }}</label>
                                        <div class="relative">
                                            <select name="birth_day" 
                                                    class="w-full h-12 rounded-lg bg-gray-50 dark:bg-[#181211] border border-gray-200 dark:border-[#543f3b] pl-4 pr-10 text-slate-900 dark:text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none appearance-none transition-all cursor-pointer font-body" 
                                                    required>
                                                <option disabled selected value="" data-translate="Day">{{ __('Day') }}</option>
                                                @for($i = 1; $i <= 31; $i++)
                                                    <option value="{{ $i }}" {{ old('birth_day') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                                @endfor
                                            </select>
                                            <span class="material-symbols-outlined absolute right-3 top-3 text-gray-400 pointer-events-none">expand_more</span>
                                        </div>
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <label class="text-xs text-gray-500 dark:text-gray-400" data-translate="Month">{{ __('Month') }}</label>
                                        <div class="relative">
                                            <select name="birth_month" 
                                                    class="w-full h-12 rounded-lg bg-gray-50 dark:bg-[#181211] border border-gray-200 dark:border-[#543f3b] pl-4 pr-10 text-slate-900 dark:text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none appearance-none transition-all cursor-pointer font-body" 
                                                    required>
                                                <option disabled selected value="" data-translate="Month">{{ __('Month') }}</option>
                                                @for($i = 1; $i <= 12; $i++)
                                                    @php $monthName = date('F', mktime(0, 0, 0, $i, 1)); @endphp
                                                    <option value="{{ $i }}" {{ old('birth_month') == $i ? 'selected' : '' }} data-translate="{{ $monthName }}">{{ __($monthName) }}</option>
                                                @endfor
                                            </select>
                                            <span class="material-symbols-outlined absolute right-3 top-3 text-gray-400 pointer-events-none">expand_more</span>
                                        </div>
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <label class="text-xs text-gray-500 dark:text-gray-400" data-translate="Year">{{ __('Year') }}</label>
                                        <div class="relative">
                                            <select name="birth_year" 
                                                    class="w-full h-12 rounded-lg bg-gray-50 dark:bg-[#181211] border border-gray-200 dark:border-[#543f3b] pl-4 pr-10 text-slate-900 dark:text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none appearance-none transition-all cursor-pointer font-body" 
                                                    required>
                                                <option disabled selected value="" data-translate="Year">{{ __('Year') }}</option>
                                                @for($i = date('Y') - 18; $i >= date('Y') - 80; $i--)
                                                    <option value="{{ $i }}" {{ old('birth_year') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                                @endfor
                                            </select>
                                            <span class="material-symbols-outlined absolute right-3 top-3 text-gray-400 pointer-events-none">expand_more</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Section 1: Cultural Roots -->
                    <section class="flex flex-col gap-6">
                        <div class="border-b border-gray-100 dark:border-[#392b28] pb-4 flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary">temple_hindu</span>
                            <div class="flex flex-col">
                                <span class="text-[10px] text-primary font-bold uppercase tracking-wider" data-translate="Community">{{ __('Community') }}</span>
                                <h3 class="text-slate-900 dark:text-white text-xl font-bold leading-tight" data-translate="Cultural Roots">{{ __('Cultural Roots') }}</h3>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="flex flex-col gap-2">
                                <label class="text-slate-700 dark:text-gray-200 text-sm font-medium"><span data-translate="Mother Tongue">{{ __('Mother Tongue') }}</span> <span class="text-primary">*</span></label>
                                <div class="relative">
                                    <select name="mother_tongue" 
                                            class="w-full h-12 rounded-lg bg-gray-50 dark:bg-[#181211] border border-gray-200 dark:border-[#543f3b] pl-4 pr-10 text-slate-900 dark:text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none appearance-none transition-all cursor-pointer font-body" 
                                            required>
                                        <option disabled selected value="" data-translate="Select Mother Tongue">{{ __('Select Mother Tongue') }}</option>
                                        @foreach($motherTongues as $tongue)
                                            <option value="{{ $tongue->title }}" {{ old('mother_tongue') == $tongue->title ? 'selected' : '' }}>{{ $tongue->title }}</option>
                                        @endforeach
                                    </select>
                                    <span class="material-symbols-outlined absolute right-3 top-3 text-gray-400 pointer-events-none">expand_more</span>
                                </div>
                            </div>
                            <div class="flex flex-col gap-2">
                                <div class="flex justify-between">
                                    <label class="text-slate-700 dark:text-gray-200 text-sm font-medium" data-translate="Raashi">{{ __('Raashi') }}</label>
                                    <span class="text-xs text-gray-400 italic" data-translate="Optional">{{ __('Optional') }}</span>
                                </div>
                                <div class="relative">
                                    <select name="raashi" 
                                            class="w-full h-12 rounded-lg bg-gray-50 dark:bg-[#181211] border border-gray-200 dark:border-[#543f3b] pl-4 pr-10 text-slate-900 dark:text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none appearance-none transition-all cursor-pointer font-body">
                                        <option value="" data-translate="Select Raashi">{{ __('Select Raashi') }}</option>
                                        @foreach($raashis as $raashi)
                                            <option value="{{ $raashi->name }}" {{ old('raashi') == $raashi->name ? 'selected' : '' }}>{{ $raashi->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="material-symbols-outlined absolute right-3 top-3 text-gray-400 pointer-events-none">expand_more</span>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Section 2: Education & Career -->
                    <section class="flex flex-col gap-6 fade-in-up delay-200">
                        <div class="border-b border-gray-100 dark:border-[#392b28] pb-4 flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary">school</span>
                            <div class="flex flex-col">
                                <span class="text-[10px] text-primary font-bold uppercase tracking-wider" data-translate="Career">{{ __('Career') }}</span>
                                <h3 class="text-slate-900 dark:text-white text-xl font-bold leading-tight" data-translate="Education & Career">{{ __('Education & Career') }}</h3>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="flex flex-col gap-2">
                                <label class="text-slate-700 dark:text-gray-200 text-sm font-medium"><span data-translate="Highest Education">{{ __('Highest Education') }}</span> <span class="text-primary">*</span></label>
                                <div class="relative">
                                    <select name="highest_education_id" 
                                            id="highest_education_id"
                                            class="w-full h-12 rounded-lg bg-gray-50 dark:bg-[#181211] border border-gray-200 dark:border-[#543f3b] pl-4 pr-10 text-slate-900 dark:text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none appearance-none transition-all cursor-pointer font-body" 
                                            required>
                                        <option disabled selected value="" data-translate="Select Degree">{{ __('Select Degree') }}</option>
                                        @foreach($highest_qualifications as $qualification)
                                            <option value="{{ $qualification->id }}">{{ $qualification->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="material-symbols-outlined absolute right-3 top-3 text-gray-400 pointer-events-none">expand_more</span>
                                </div>
                            </div>
                            <div class="flex flex-col gap-2">
                                <label class="text-slate-700 dark:text-gray-200 text-sm font-medium" data-translate="Education Details">{{ __('Education Details') }}</label>
                                <div class="relative">
                                    <select name="education_id" 
                                            id="education_id"
                                            class="w-full h-12 rounded-lg bg-gray-50 dark:bg-[#181211] border border-gray-200 dark:border-[#543f3b] pl-4 pr-10 text-slate-900 dark:text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none appearance-none transition-all cursor-pointer font-body">
                                        <option value="" data-translate="Select Education Details">{{ __('Select Education Details') }}</option>
                                        @if(old('education_id'))
                                            @php
                                                $selectedEducation = \Illuminate\Support\Facades\DB::table('education_master')->where('id', old('education_id'))->first();
                                            @endphp
                                            @if($selectedEducation)
                                                <option value="{{ $selectedEducation->id }}" selected>{{ $selectedEducation->name }}</option>
                                            @endif
                                        @endif
                                    </select>
                                    <span class="material-symbols-outlined absolute right-3 top-3 text-gray-400 pointer-events-none">expand_more</span>
                                </div>
                            </div>
                            <div class="flex flex-col gap-2">
                                <label class="text-slate-700 dark:text-gray-200 text-sm font-medium"><span data-translate="Occupation">{{ __('Occupation') }}</span> <span class="text-primary">*</span></label>
                                <div class="relative">
                                    <select name="occupation_id" 
                                            class="w-full h-12 rounded-lg bg-gray-50 dark:bg-[#181211] border border-gray-200 dark:border-[#543f3b] pl-4 pr-10 text-slate-900 dark:text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none appearance-none transition-all cursor-pointer font-body" 
                                            required>
                                        <option disabled selected value="" data-translate="Select Occupation">{{ __('Select Occupation') }}</option>
                                        @foreach($occupations as $occupation)
                                            <option value="{{ $occupation->id }}" {{ old('occupation_id') == $occupation->id ? 'selected' : '' }}>{{ $occupation->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="material-symbols-outlined absolute right-3 top-3 text-gray-400 pointer-events-none">expand_more</span>
                                </div>
                            </div>
                            <div class="flex flex-col gap-2">
                                <label class="text-slate-700 dark:text-gray-200 text-sm font-medium" data-translate="Employed In">{{ __('Employed In') }}</label>
                                <div class="relative">
                                    <select name="employed_in" 
                                            class="w-full h-12 rounded-lg bg-gray-50 dark:bg-[#181211] border border-gray-200 dark:border-[#543f3b] pl-4 pr-10 text-slate-900 dark:text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none appearance-none transition-all cursor-pointer font-body">
                                        <option value="" data-translate="Select Employment Type">{{ __('Select Employment Type') }}</option>
                                        <option value="Business" {{ old('employed_in') == 'Business' ? 'selected' : '' }} data-translate="Business">{{ __('Business') }}</option>
                                        <option value="Defence" {{ old('employed_in') == 'Defence' ? 'selected' : '' }} data-translate="Defence">{{ __('Defence') }}</option>
                                        <option value="Government" {{ old('employed_in') == 'Government' ? 'selected' : '' }} data-translate="Government">{{ __('Government') }}</option>
                                        <option value="Private" {{ old('employed_in') == 'Private' ? 'selected' : '' }} data-translate="Private">{{ __('Private') }}</option>
                                        <option value="Not Employed" {{ old('employed_in') == 'Not Employed' ? 'selected' : '' }} data-translate="Not Employed">{{ __('Not Employed') }}</option>
                                        <option value="Others" {{ old('employed_in') == 'Others' ? 'selected' : '' }} data-translate="Others">{{ __('Others') }}</option>
                                    </select>
                                    <span class="material-symbols-outlined absolute right-3 top-3 text-gray-400 pointer-events-none">expand_more</span>
                                </div>
                            </div>
                            <div class="flex flex-col gap-2 md:col-span-2">
                                <label class="text-slate-700 dark:text-gray-200 text-sm font-medium" data-translate="Annual Income">{{ __('Annual Income') }}</label>
                                <div class="p-6 bg-gray-50 dark:bg-[#181211] rounded-lg border border-gray-200 dark:border-[#543f3b]">
                                    <input type="range" 
                                           id="annual_income"
                                           class="w-full h-2 bg-gray-300 dark:bg-[#392b28] rounded-lg appearance-none cursor-pointer accent-primary" 
                                           min="0" 
                                           max="5000000" 
                                           step="50000"
                                           value="{{ old('annual_income', 500000) }}"
                                           oninput="updateIncomeDisplay(this.value)"/>
                                    <div class="flex justify-between mt-2 text-xs text-gray-500 dark:text-gray-400 font-body">
                                        <span>₹0</span>
                                        <span id="income-display" class="text-primary font-bold text-sm">₹{{ number_format(old('annual_income', 500000)) }}</span>
                                        <span data-translate="₹50L+">{{ __('₹50L+') }}</span>
                                    </div>
                                    <input type="hidden" name="annual_income" id="annual_income_hidden" value="{{ old('annual_income', 500000) }}">
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Section 3: Interests & Hobbies -->
                    <section class="flex flex-col gap-6 fade-in-up delay-300">
                        <div class="border-b border-gray-100 dark:border-[#392b28] pb-4 flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary">favorite</span>
                            <div class="flex flex-col">
                                <span class="text-[10px] text-primary font-bold uppercase tracking-wider" data-translate="Lifestyle">{{ __('Lifestyle') }}</span>
                                <h3 class="text-slate-900 dark:text-white text-xl font-bold leading-tight" data-translate="Interests & Hobbies">{{ __('Interests & Hobbies') }}</h3>
                            </div>
                        </div>
                        <div class="flex flex-col gap-4">
                            <label class="text-slate-700 dark:text-gray-200 text-sm font-medium" data-translate="Hobbies / Interests">{{ __('Hobbies / Interests') }}</label>
                            <div class="relative">
                                <select name="hobbies[]" id="hobbies" class="w-full select2-hobbies" multiple="multiple" data-placeholder="{{ __('Select or type your hobbies') }}" data-translate-placeholder="Select or type your hobbies">
                                    @foreach($hobbies as $hobby)
                                        <option value="{{ $hobby->name }}">{{ $hobby->name }}</option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 font-body" data-translate="Select from existing hobbies or type and press Enter to add your own.">
                                    {{ __('Select from existing hobbies or type and press Enter to add your own.') }}
                                </p>
                            </div>
                        </div>
                    </section>

                    <!-- Terms and Conditions -->
                    <div class="flex items-start gap-3 pt-4 border-t border-gray-100 dark:border-[#392b28]">
                        <input type="checkbox" 
                               id="terms" 
                               name="terms" 
                               class="mt-1 h-4 w-4 rounded border-gray-300 dark:border-[#543f3b] bg-gray-50 dark:bg-[#181211] text-primary focus:ring-primary cursor-pointer" 
                               required/>
                        <label for="terms" class="text-sm text-slate-600 dark:text-gray-300 font-body">
                            <span data-translate="I agree to the">{{ __('I agree to the') }}</span> 
                            <a href="{{ route('terms') }}" class="text-primary hover:underline" target="_blank" data-translate="Terms and Conditions">{{ __('Terms and Conditions') }}</a> 
                            <span data-translate="and">{{ __('and') }}</span> 
                            <a href="#" class="text-primary hover:underline" data-translate="Privacy Policy">{{ __('Privacy Policy') }}</a> 
                            <span class="text-primary">*</span>
                        </label>
            </div>

                    <!-- Navigation Actions -->
                    <div class="flex items-center justify-between pt-6 mt-2 border-t border-gray-100 dark:border-[#392b28] fade-in-up delay-300">
                        <a href="{{ route('home') }}" class="group flex items-center gap-2 px-6 py-3 rounded-lg text-slate-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition-colors font-medium text-sm">
                            <span class="material-symbols-outlined text-[20px] group-hover:-translate-x-1 transition-transform">arrow_back</span>
                            <span data-translate="Back">{{ __('Back') }}</span>
                        </a>
                        <button type="submit" class="flex items-center gap-2 px-8 py-3 rounded-lg bg-primary hover:bg-[#d42e0f] text-white shadow-lg shadow-primary/20 hover:shadow-primary/40 transition-all font-bold text-sm tracking-wide">
                            <span data-translate="Create Account">{{ __('Create Account') }}</span>
                            <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
                        </button>
        </div>
    </form>
</div>

            <!-- Security Badge/Trust Indicator -->
            <div class="flex justify-center items-center gap-2 text-slate-500 dark:text-[#b9a19d] text-xs pb-10 fade-in-up delay-300">
                <span class="material-symbols-outlined text-[16px]">lock</span>
                <p data-translate="Your information is securely encrypted and never shared without permission.">{{ __('Your information is securely encrypted and never shared without permission.') }}</p>
        </div>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
        // Profile image preview and cropping
        let cropperInstance = null;
        
        function handleFileSelect(input) {
            if (input.files && input.files[0]) {
                const fileName = input.files[0].name;
                document.getElementById('file-name').textContent = fileName;
                // Temporarily remove data-translate so the filename isn't overwritten
                document.getElementById('file-name').removeAttribute('data-translate');
                previewProfileImage(input);
            }
        }
        
        function previewProfileImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('profile-preview');
                    preview.src = e.target.result;
                    
                    // Destroy existing cropper if any
                    if (cropperInstance) {
                        cropperInstance.destroy();
                        cropperInstance = null;
                    }
                    
                    // Initialize cropper
                    cropperInstance = new Cropper(preview, {
                        aspectRatio: 1,
                        viewMode: 1,
                        guides: true,
                        background: false,
                        autoCropArea: 0.8,
                        responsive: true,
                        cropBoxResizable: true,
                        ready: function() {
                            // Get cropped canvas when ready
                            updateCroppedImage();
                        }
                    });
                    
                    // Update hidden input when crop changes
                    preview.addEventListener('crop', function() {
                        updateCroppedImage();
                    });
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        function updateCroppedImage() {
            if (cropperInstance) {
                const canvas = cropperInstance.getCroppedCanvas({
                    width: 400,
                    height: 400,
                    imageSmoothingEnabled: true,
                    imageSmoothingQuality: 'high'
                });
                if (canvas) {
                    canvas.toBlob(function(blob) {
                        const reader = new FileReader();
                        reader.onload = function() {
                            const base64 = reader.result;
                            document.getElementById('profile_image_data').value = base64;
                        };
                        reader.readAsDataURL(blob);
                    }, 'image/jpeg', 0.9);
                }
            }
        }

        // Update income display
        function updateIncomeDisplay(value) {
            const formatted = new Intl.NumberFormat('en-IN').format(value);
            document.getElementById('income-display').textContent = '₹' + formatted;
            document.getElementById('annual_income_hidden').value = value;
        }


        // Wait for jQuery and DOM to be ready
        $(document).ready(function() {
            // Load education details when highest education is selected
            $('#highest_education_id').on('change', function() {
                const qualificationId = $(this).val();
                const educationSelect = $('#education_id');
                
                console.log('Highest education changed:', qualificationId);
                                                
                const loadingLabel = (typeof translations !== 'undefined' && translations['Loading...']) ? translations['Loading...'] : 'Loading...';
                educationSelect.html('<option value="">' + loadingLabel + '</option>');
                educationSelect.prop('disabled', true);
                
                if (qualificationId) {
                    const url = '/get-educations/' + qualificationId;
                    console.log('Fetching education details from:', url);
                    
                    $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            console.log('Education details received:', data);
                            const selectLabel = (typeof translations !== 'undefined' && translations['Select Education Details']) ? translations['Select Education Details'] : 'Select Education Details';
                            educationSelect.html('<option value="">' + selectLabel + '</option>');
                            if (data && data.length > 0) {
                                $.each(data, function(index, education) {
                                    educationSelect.append('<option value="' + education.id + '">' + education.name + '</option>');
                                });
                            } else {
                                const noDetailsLabel = (typeof translations !== 'undefined' && translations['No education details available']) ? translations['No education details available'] : 'No education details available';
                                educationSelect.append('<option value="">' + noDetailsLabel + '</option>');
                            }
                            educationSelect.prop('disabled', false);
                        },
                        error: function(xhr, status, error) {
                            console.error('Error loading education details:', error);
                            const errorLabel = (typeof translations !== 'undefined' && translations['Error loading education details. Please try again.']) ? translations['Error loading education details. Please try again.'] : 'Error loading education details. Please try again.';
                            educationSelect.html('<option value="">' + errorLabel + '</option>');
                            educationSelect.prop('disabled', false);
                        }
                    });
                } else {
                    const selectLabel = (typeof translations !== 'undefined' && translations['Select Education Details']) ? translations['Select Education Details'] : 'Select Education Details';
                    educationSelect.html('<option value="">' + selectLabel + '</option>');
                    educationSelect.prop('disabled', false);
                }
            });

            // Initialize income display on page load
            const incomeValue = document.getElementById('annual_income').value;
            if (incomeValue) {
                updateIncomeDisplay(incomeValue);
            }

            // Real-time password verification
            const passwordInput = $('#password');
            const confirmInput = $('#password_confirmation');
            const indicator = $('#password-match-indicator');
            const matchIcon = $('#match-icon');
            const mismatchIcon = $('#mismatch-icon');
            const errorMessage = $('#password-error-message');

            function verifyPasswords() {
                const password = passwordInput.val();
                const confirmPassword = confirmInput.val();

                if (confirmPassword.length === 0) {
                    indicator.addClass('hidden');
                    errorMessage.addClass('hidden');
                    confirmInput.removeClass('border-green-500 border-red-500');
                    return;
                }

                indicator.removeClass('hidden');
                if (password === confirmPassword) {
                    matchIcon.removeClass('hidden');
                    mismatchIcon.addClass('hidden');
                    errorMessage.addClass('hidden');
                    confirmInput.addClass('border-green-500').removeClass('border-red-500');
                } else {
                    matchIcon.addClass('hidden');
                    mismatchIcon.removeClass('hidden');
                    errorMessage.removeClass('hidden');
                    confirmInput.addClass('border-red-500').removeClass('border-green-500');
                }
            }

            passwordInput.on('input', verifyPasswords);
            confirmInput.on('input', verifyPasswords);

            // Initialize Select2 for hobbies
            $('.select2-hobbies').select2({
                tags: true,
                tokenSeparators: [',', ';'],
                width: '100%',
                placeholder: $('.select2-hobbies').data('placeholder')
            });
        });
    </script>
    
    <!-- Global Theme Manager -->
    <script src="{{ asset('js/theme.js') }}"></script>
    <!-- Alpine.js for dropdown functionality -->
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <!-- Language Switcher -->
    <script src="{{ asset('js/language-switcher.js') }}"></script>
    
    <script>
        // Auto-dismiss notifications after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const notifications = document.querySelectorAll('[id*="notification"], [id*="success"], [id*="error"], [id*="info"], [id*="status"]');
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
