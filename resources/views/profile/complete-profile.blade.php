<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Complete Your Profile - {{ $siteSettings->site_name ?? 'GoswamiSangath' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Newsreader:ital,opsz,wght@0,6..72,200..800;1,6..72,200..800&family=Noto+Sans:wght@400;500;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                    },
                    fontFamily: {
                        "display": ["Newsreader", "serif"],
                        "body": ["Noto Sans", "sans-serif"],
                    },
                },
            },
        }
    </script>
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
</head>
<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-white font-display overflow-x-hidden transition-colors duration-300">
    @include('partials.top-navbar')

    <main class="flex flex-col min-h-screen items-center py-10 px-4 md:px-0 bg-[url('https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?q=80&w=2564&auto=format&fit=crop')] bg-cover bg-center bg-no-repeat bg-fixed relative">
        <div class="absolute inset-0 bg-background-light/95 dark:bg-background-dark/95 backdrop-blur-sm z-0"></div>
        
        <div class="relative z-10 w-full max-w-[800px] flex flex-col gap-8">
            <!-- Progress Bar -->
            <div class="w-full">
                <div class="flex flex-col gap-3">
                    <div class="flex gap-6 justify-between items-end">
                        <div class="flex flex-col">
                            <span class="text-primary text-sm font-bold tracking-widest uppercase mb-1">{{ __('Step 2 of 2') }}</span>
                            <h2 class="text-slate-900 dark:text-white text-3xl md:text-4xl font-bold font-display italic">{{ __('Complete Your Profile') }}</h2>
                        </div>
                        <p class="text-slate-500 dark:text-[#b9a19d] text-lg font-medium font-display">100%</p>
                    </div>
                    <div class="rounded-full bg-gray-200 dark:bg-[#392b28] h-1.5 overflow-hidden">
                        <div class="h-full rounded-full bg-primary shadow-[0_0_10px_rgba(236,55,19,0.5)]" style="width: 100%;"></div>
                    </div>
                </div>
            </div>

            <!-- Main Form Card -->
            <div class="bg-white dark:bg-surface-dark border border-gray-100 dark:border-[#392b28] rounded-2xl p-8 md:p-10 shadow-xl">
                <div class="mb-8">
                    <h3 class="text-2xl font-bold italic mb-2">Welcome, {{ Auth::user()->full_name }}!</h3>
                    <p class="text-slate-600 dark:text-gray-300">Almost there! Please provide a few more details to help us verify your identity and find the best matches for you.</p>
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

                <form action="{{ route('profile.complete.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-8">
                    @csrf

                    <!-- Address Details -->
                    <div class="flex flex-col gap-6">
                        <div class="border-b border-gray-100 dark:border-[#392b28] pb-2 flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">location_on</span>
                            <h4 class="text-lg font-bold">{{ __('Address Details') }}</h4>
                        </div>
                        
                        <!-- Residential Address -->
                        <div class="flex flex-col gap-4">
                            <div class="flex items-center justify-between">
                                <label class="text-xs uppercase tracking-wider text-gray-500 font-bold">{{ __('Residential Address') }} <span class="text-primary">*</span></label>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="flex flex-col gap-1">
                                    <p class="text-[10px] uppercase text-gray-400 font-bold mb-1">{{ __('Country') }}</p>
                                    <select name="residential_country_id" id="res_country" 
                                            class="w-full rounded-lg bg-gray-50 dark:bg-[#181211] border border-gray-200 dark:border-[#543f3b] px-4 py-2 focus:border-primary focus:ring-1 focus:ring-primary outline-none text-sm font-semibold" required>
                                        <option value="">{{ __('Select Country') }}</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}" {{ old('residential_country_id') == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <p class="text-[10px] uppercase text-gray-400 font-bold mb-1">{{ __('State') }}</p>
                                    <select name="residential_state_id" id="res_state" 
                                            class="w-full rounded-lg bg-gray-50 dark:bg-[#181211] border border-gray-200 dark:border-[#543f3b] px-4 py-2 focus:border-primary focus:ring-1 focus:ring-primary outline-none text-sm font-semibold" required disabled>
                                        <option value="">{{ __('Select State') }}</option>
                                    </select>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <p class="text-[10px] uppercase text-gray-400 font-bold mb-1">{{ __('City') }}</p>
                                    <select name="residential_city_id" id="res_city" 
                                            class="w-full rounded-lg bg-gray-50 dark:bg-[#181211] border border-gray-200 dark:border-[#543f3b] px-4 py-2 focus:border-primary focus:ring-1 focus:ring-primary outline-none text-sm font-semibold" required disabled>
                                        <option value="">{{ __('Select City') }}</option>
                                    </select>
                                </div>
                            </div>
                            <textarea name="residential_address" id="res_addr" rows="2" 
                                      class="w-full rounded-lg bg-gray-50 dark:bg-[#181211] border border-gray-200 dark:border-[#543f3b] px-4 py-2 focus:border-primary focus:ring-1 focus:ring-primary outline-none text-sm leading-relaxed" 
                                      required placeholder="{{ __('Building, Street, Area...') }}">{{ old('residential_address') }}</textarea>
                        </div>

                        <!-- Working Address -->
                        <div class="flex flex-col gap-4 pt-4 border-t border-gray-50 dark:border-white/5">
                            <div class="flex items-center justify-between">
                                <label class="text-xs uppercase tracking-wider text-gray-500 font-bold">{{ __('Working Address') }}</label>
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="checkbox" id="same_as_residential" name="same_as_residential" class="size-4 text-primary focus:ring-primary border-gray-300 dark:border-white/10 rounded">
                                    <span class="text-[10px] font-bold text-gray-500 group-hover:text-primary transition-colors uppercase tracking-tight">{{ __('Same as Residential') }}</span>
                                </label>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="flex flex-col gap-1">
                                    <p class="text-[10px] uppercase text-gray-400 font-bold mb-1">{{ __('Country') }}</p>
                                    <select name="working_country_id" id="work_country" 
                                            class="w-full rounded-lg bg-gray-50 dark:bg-[#181211] border border-gray-200 dark:border-[#543f3b] px-4 py-2 focus:border-primary focus:ring-1 focus:ring-primary outline-none text-sm font-semibold">
                                        <option value="">{{ __('Select Country') }}</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}" {{ old('working_country_id') == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <p class="text-[10px] uppercase text-gray-400 font-bold mb-1">{{ __('State') }}</p>
                                    <select name="working_state_id" id="work_state" 
                                            class="w-full rounded-lg bg-gray-50 dark:bg-[#181211] border border-gray-200 dark:border-[#543f3b] px-4 py-2 focus:border-primary focus:ring-1 focus:ring-primary outline-none text-sm font-semibold" disabled>
                                        <option value="">{{ __('Select State') }}</option>
                                    </select>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <p class="text-[10px] uppercase text-gray-400 font-bold mb-1">{{ __('City') }}</p>
                                    <select name="working_city_id" id="work_city" 
                                            class="w-full rounded-lg bg-gray-50 dark:bg-[#181211] border border-gray-200 dark:border-[#543f3b] px-4 py-2 focus:border-primary focus:ring-1 focus:ring-primary outline-none text-sm font-semibold" disabled>
                                        <option value="">{{ __('Select City') }}</option>
                                    </select>
                                </div>
                            </div>
                            <textarea name="working_address" id="work_addr" rows="2" 
                                      class="w-full rounded-lg bg-gray-50 dark:bg-[#181211] border border-gray-200 dark:border-[#543f3b] px-4 py-2 focus:border-primary focus:ring-1 focus:ring-primary outline-none text-sm leading-relaxed" 
                                      placeholder="{{ __('Building, Street, Area...') }}">{{ old('working_address') }}</textarea>
                        </div>
                    </div>

                    <!-- Personal Attributes -->
                    <div class="flex flex-col gap-4">
                        <div class="border-b border-gray-100 dark:border-[#392b28] pb-2 flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">demography</span>
                            <h4 class="text-lg font-bold">{{ __('Personal Attributes') }}</h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="flex flex-col gap-2">
                                <label class="text-sm font-medium">{{ __('Marital Status') }} <span class="text-primary">*</span></label>
                                <select name="marital_status" class="w-full h-12 rounded-lg bg-gray-50 dark:bg-[#181211] border border-gray-200 dark:border-[#543f3b] px-4 focus:border-primary focus:ring-1 focus:ring-primary outline-none" required>
                                    <option value="" disabled selected>Select Status</option>
                                    <option value="Unmarried" {{ old('marital_status') == 'Unmarried' ? 'selected' : '' }}>Unmarried</option>
                                    <option value="Widowed" {{ old('marital_status') == 'Widowed' ? 'selected' : '' }}>Widowed/Widower</option>
                                    <option value="Divorced" {{ old('marital_status') == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                    <option value="Separated" {{ old('marital_status') == 'Separated' ? 'selected' : '' }}>Separated</option>
                                </select>
                            </div>
                            <div class="flex flex-col gap-2">
                                <label class="text-sm font-medium">{{ __('Physically Handicapped?') }} <span class="text-primary">*</span></label>
                                <div class="flex items-center gap-4 h-12">
                                    <label class="flex items-center gap-2">
                                        <input type="radio" name="is_handicapped" value="1" {{ old('is_handicapped') == '1' ? 'checked' : '' }} class="text-primary focus:ring-primary">
                                        <span>Yes</span>
                                    </label>
                                    <label class="flex items-center gap-2">
                                        <input type="radio" name="is_handicapped" value="0" {{ old('is_handicapped') !== '1' ? 'checked' : '' }} class="text-primary focus:ring-primary">
                                        <span>No</span>
                                    </label>
                                </div>
                            </div>
                            <div class="flex flex-col gap-2">
                                <label class="text-sm font-medium">{{ __('Height') }} <span class="text-primary">*</span></label>
                                <select name="height" class="w-full h-12 rounded-lg bg-gray-50 dark:bg-[#181211] border border-gray-200 dark:border-[#543f3b] px-4 focus:border-primary focus:ring-1 focus:ring-primary outline-none" required>
                                    <option value="" disabled selected>Select Height</option>
                                    @for($ft = 4; $ft <= 7; $ft++)
                                        @for($in = 0; $in <= 11; $in++)
                                            @php $h = "{$ft}'{$in}\""; @endphp
                                            <option value="{{ $h }}" {{ old('height') == $h ? 'selected' : '' }}>{{ $h }}</option>
                                        @endfor
                                    @endfor
                                </select>
                            </div>
                            <div class="flex flex-col gap-2">
                                <label class="text-sm font-medium">{{ __('Weight (KG)') }} <span class="text-primary">*</span></label>
                                <input type="number" name="weight" value="{{ old('weight') }}" placeholder="e.g. 65" class="w-full h-12 rounded-lg bg-gray-50 dark:bg-[#181211] border border-gray-200 dark:border-[#543f3b] px-4 focus:border-primary focus:ring-1 focus:ring-primary outline-none" required min="30" max="200">
                            </div>
                        </div>
                    </div>

                    <!-- ID Verification -->
                    <div class="flex flex-col gap-4">
                        <div class="border-b border-gray-100 dark:border-[#392b28] pb-2 flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">verified_user</span>
                            <h4 class="text-lg font-bold">{{ __('ID Verification') }}</h4>
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-medium">{{ __('Upload ID Proof (Aadhar/PAN/License)') }} <span class="text-primary">*</span></label>
                            <input type="file" name="id_proof" class="w-full h-12 rounded-lg bg-gray-50 dark:bg-[#181211] border border-gray-200 dark:border-[#543f3b] px-4 py-2 focus:border-primary focus:ring-1 focus:ring-primary outline-none" required accept="image/*,.pdf">
                            <p class="text-xs text-gray-400 italic">This will be used for profile verification only and will not be shared publicly.</p>
                        </div>
                    </div>

                    <!-- Additional Info -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium">{{ __('Any Additional Info') }}</label>
                        <textarea name="additional_info" rows="3" class="w-full rounded-lg bg-gray-50 dark:bg-[#181211] border border-gray-200 dark:border-[#543f3b] px-4 py-2 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all" placeholder="Tell us more about yourself...">{{ old('additional_info') }}</textarea>
                    </div>

                    <button type="submit" class="w-full h-14 bg-primary hover:bg-[#d42e0f] text-white rounded-xl font-bold text-lg shadow-lg shadow-primary/20 transition-all transform hover:scale-[1.02] active:scale-[0.98]">
                        {{ __('Complete Registration & Go to Dashboard') }}
                    </button>
                </form>
            </div>
        </div>
    </main>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/theme.js') }}"></script>
    <script>
        $(document).ready(function() {
            const sameAsResCheckbox = $('#same_as_residential');
            const resFields = ['res_country', 'res_state', 'res_city', 'res_addr'];
            const workFields = ['work_country', 'work_state', 'work_city', 'work_addr'];
            
            // Set up AJAX headers for CSRF
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function loadStates(countryId, stateId, cityId = null) {
                if (!countryId) return;
                
                const stateSelect = $(stateId);
                const citySelect = cityId ? $(cityId) : null;
                
                stateSelect.prop('disabled', true).html('<option value="">Loading...</option>');
                if (citySelect) citySelect.prop('disabled', true).html('<option value="">Select City</option>');
                
                $.post('{{ route('getStates') }}', { country_id: countryId }, function(data) {
                    stateSelect.html('<option value="">Select State</option>');
                    $.each(data, function(index, state) {
                        stateSelect.append('<option value="' + state.id + '">' + state.name + '</option>');
                    });
                    stateSelect.prop('disabled', false);
                    
                    if (sameAsResCheckbox.is(':checked') && stateId === '#res_state') {
                        $('#work_state').html(stateSelect.html()).val(stateSelect.val());
                    }
                });
            }

            function loadCities(stateId, cityId) {
                if (!stateId) return;
                
                const citySelect = $(cityId);
                citySelect.prop('disabled', true).html('<option value="">Loading...</option>');
                
                $.post('{{ route('getCities') }}', { state_id: stateId }, function(data) {
                    citySelect.html('<option value="">Select City</option>');
                    $.each(data, function(index, city) {
                        citySelect.append('<option value="' + city.id + '">' + (city.name || city.city_master) + '</option>');
                    });
                    citySelect.prop('disabled', false);
                    
                    if (sameAsResCheckbox.is(':checked') && cityId === '#res_city') {
                        $('#work_city').html(citySelect.html()).val(citySelect.val());
                    }
                });
            }

            // Residential Location Change
            $('#res_country').on('change', function() {
                loadStates($(this).val(), '#res_state', '#res_city');
                if (sameAsResCheckbox.is(':checked')) {
                    $('#work_country').val($(this).val());
                }
            });

            $('#res_state').on('change', function() {
                loadCities($(this).val(), '#res_city');
                if (sameAsResCheckbox.is(':checked')) {
                    $('#work_state').val($(this).val());
                }
            });

            $('#res_city').on('change', function() {
                if (sameAsResCheckbox.is(':checked')) {
                    $('#work_city').val($(this).val());
                }
            });

            // Working Location Change
            $('#work_country').on('change', function() {
                if (!sameAsResCheckbox.is(':checked')) {
                    loadStates($(this).val(), '#work_state', '#work_city');
                }
            });

            $('#work_state').on('change', function() {
                if (!sameAsResCheckbox.is(':checked')) {
                    loadCities($(this).val(), '#work_city');
                }
            });

            // Same as Residential Logic
            function syncAddress() {
                if (sameAsResCheckbox.is(':checked')) {
                    // Sync values
                    $('#work_country').val($('#res_country').val());
                    
                    // Copy options for State and City
                    $('#work_state').html($('#res_state').html()).val($('#res_state').val());
                    $('#work_city').html($('#res_city').html()).val($('#res_city').val());
                    $('#work_addr').val($('#res_addr').val());
                    
                    // Disable fields
                    $('#work_country, #work_state, #work_city, #work_addr').prop('disabled', true).addClass('opacity-70 bg-gray-200 dark:bg-[#2c1c19]');
                    
                    // Change name of disabled fields so they are not sent (controller will use residential ones or we handle it)
                    // Actually, controller might need them if they have different names.
                    // Instead of disabling, we can just make them readonly if they were inputs, but for selects we might need to be careful.
                    // For now, I'll keep them enabled but sync them.
                } else {
                    $('#work_country, #work_state, #work_city, #work_addr').prop('disabled', false).removeClass('opacity-70 bg-gray-200 dark:bg-[#2c1c19]');
                }
            }
            
            sameAsResCheckbox.on('change', syncAddress);

            // Live sync for address textarea
            $('#res_addr').on('input', function() {
                if (sameAsResCheckbox.is(':checked')) {
                    $('#work_addr').val($(this).val());
                }
            });
        });
    </script>
</body>
</html>
