<!-- Basic Information -->
<h2 class="text-2xl font-bold text-gray-700 pb-2 border-b-2 border-indigo-200 mb-6">{{ __('basic_information') }}</h2>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <div>
        <label for="full_name" class="form-label">{{ __('full_name') }} *</label>
        <input type="text" id="full_name" name="full_name" class="form-input" value="{{ old('full_name', $user->full_name) }}" required>
    </div>
    <div>
        <label for="gender" class="form-label">{{ __('gender') }} *</label>
        <select id="gender" name="gender" class="form-select" required>
            <option value="" disabled {{ !$user->gender ? 'selected' : '' }}>{{ __('select_gender') }}</option>
            <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>{{ __('male') }}</option>
            <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>{{ __('female') }}</option>
        </select>
    </div>
    <div>
        <label for="height" class="form-label">{{ __('height') }} *</label>
        <select id="height" name="height" class="form-select" required>
            <option value="" disabled {{ !$user->height ? 'selected' : '' }}>{{ __('select_height') }}</option>
            @foreach(['4ft ( 121 cm )', '4ft 1in ( 124cm )', '4ft 2in ( 127cm )', '4ft 3in ( 129cm )', '4ft 4in ( 132cm )', '4ft 5in ( 132cm )', '4ft 6in ( 137cm )', '4ft 7in ( 139cm )', '4ft 8in ( 142cm )', '4ft 9in ( 144cm )', '4ft 10in ( 147cm )', '4ft 11in ( 149cm )', '5ft ( 152cm )', '5ft 1in ( 154cm )', '5ft 2in ( 157cm )', '5ft 3in ( 160cm )', '5ft 4in ( 162cm )', '5ft 5in ( 165cm )', '5ft 6in ( 167cm )', '5ft 7in ( 170cm )', '5ft 8in ( 172cm )', '5ft 9in ( 175cm )', '5ft 10in ( 177cm )', '5ft 11in ( 180cm )', '6ft ( 182cm )', '6ft 1in ( 185cm )', '6ft 2in ( 187cm )', '6ft 3in ( 190cm )', '6ft 4in ( 193cm )', '6ft 5in ( 195cm )', '6ft 6in ( 198cm )', '6ft 7in ( 200cm )', '6ft 8in ( 203cm )', '6ft 9in ( 205cm )', '6ft 10in ( 208cm )', '6ft 11in ( 210cm )', '7ft ( 213cm )'] as $height)
                <option value="{{ $height }}" {{ old('height', $user->height) == $height ? 'selected' : '' }}>{{ $height }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="weight" class="form-label">{{ __('weight_kg') }}</label>
        <input type="number" id="weight" name="weight" class="form-input" value="{{ old('weight', $user->weight) }}">
    </div>
    @php
        $dob = $user->dob ? \Carbon\Carbon::parse($user->dob) : null;
    @endphp
    <div class="lg:col-span-2">
        <label class="form-label">{{ __('date_of_birth') }} *</label>
        <div class="grid grid-cols-3 gap-4">
            <select name="birth_day" class="form-select" required>
                <option value="" disabled {{ !$dob ? 'selected' : '' }}>{{__('day')}}</option>
                @for($i=1; $i<=31; $i++)
                    <option value="{{$i}}" {{ old('birth_day', $dob ? $dob->day : '') == $i ? 'selected' : '' }}>{{$i}}</option>
                @endfor
            </select>
            <select name="birth_month" class="form-select" required>
                <option value="" disabled {{ !$dob ? 'selected' : '' }}>{{__('month')}}</option>
                @for($i=1; $i<=12; $i++)
                    <option value="{{$i}}" {{ old('birth_month', $dob ? $dob->month : '') == $i ? 'selected' : '' }}>{{ date('F', mktime(0,0,0,$i,10)) }}</option>
                @endfor
            </select>
            <select name="birth_year" class="form-select" required>
                <option value="" disabled {{ !$dob ? 'selected' : '' }}>{{__('year')}}</option>
                @for($i=date('Y')-18; $i>=1950; $i--)
                    <option value="{{$i}}" {{ old('birth_year', $dob ? $dob->year : '') == $i ? 'selected' : '' }}>{{$i}}</option>
                @endfor
            </select>
        </div>
    </div>
</div>

<!-- Horoscope Details -->
<h2 class="text-2xl font-bold text-gray-700 pb-2 border-b-2 border-indigo-200 mb-6">{{ __('horoscope_details') }}</h2>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
     @php
        $birth_time = $user->birth_time ? \Carbon\Carbon::parse($user->birth_time) : null;
    @endphp
    <div>
        <label class="form-label">{{ __('birth_time') }}</label>
        <div class="grid grid-cols-3 gap-2">
            <select name="birth_hour" class="form-select">
                <option value="" disabled {{ !$birth_time ? 'selected' : '' }}>HH</option>
                @for($i = 1; $i <= 12; $i++)
                    <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" {{ old('birth_hour', $birth_time ? $birth_time->format('h') : '') == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                @endfor
            </select>
            <select name="birth_minute" class="form-select">
                <option value="" disabled {{ !$birth_time ? 'selected' : '' }}>MM</option>
                @for($i = 0; $i <= 59; $i++)
                    <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" {{ old('birth_minute', $birth_time ? $birth_time->format('i') : '') == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                @endfor
            </select>
            <select name="birth_ampm" class="form-select">
                <option value="AM" {{ old('birth_ampm', $birth_time ? $birth_time->format('A') : '') == 'AM' ? 'selected' : '' }}>AM</option>
                <option value="PM" {{ old('birth_ampm', $birth_time ? $birth_time->format('A') : '') == 'PM' ? 'selected' : '' }}>PM</option>
            </select>
        </div>
    </div>
    <div>
        <label for="birth_place" class="form-label">{{ __('birth_place') }}</label>
        <input type="text" id="birth_place" name="birth_place" class="form-input" value="{{ old('birth_place', $user->birth_place) }}">
    </div>
</div>

<!-- Personal Details -->
<h2 class="text-2xl font-bold text-gray-700 pb-2 border-b-2 border-indigo-200 mb-6">{{ __('personal_details') }}</h2>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
      <div>
        <label for="marital_status" class="form-label">{{ __('marital_status') }} *</label>
        <select id="marital_status" name="marital_status" class="form-select" required>
            <option value="" disabled {{ !$user->marital_status ? 'selected' : '' }}>{{ __('select_marital_status') }}</option>
            <option value="UnMarried" {{ old('marital_status', $user->marital_status) == 'UnMarried' ? 'selected' : '' }}>{{ __('unmarried') }}</option>
            <option value="Widow/Widower" {{ old('marital_status', $user->marital_status) == 'Widow/Widower' ? 'selected' : '' }}>{{ __('widow_widower') }}</option>
            <option value="Divorcee" {{ old('marital_status', $user->marital_status) == 'Divorcee' ? 'selected' : '' }}>{{ __('divorcee') }}</option>
            <option value="Seperated" {{ old('marital_status', $user->marital_status) == 'Seperated' ? 'selected' : '' }}>{{ __('separated') }}</option>
        </select>
    </div>
    <div>
        <label for="mother_tongue" class="form-label">{{ __('mother_tongue') }}</label>
        <select id="mother_tongue" name="mother_tongue" class="form-select">
            <option value="" disabled {{ !$user->mother_tongue ? 'selected' : '' }}>{{ __('select_mother_tongue') }}</option>
            @if(isset($motherTongues))
                @foreach($motherTongues as $tongue)
                    <option value="{{ $tongue->title }}" {{ old('mother_tongue', $user->mother_tongue) == $tongue->title ? 'selected' : '' }}>{{ $tongue->title }}</option>
                @endforeach
            @endif
        </select>
    </div>
     <div class="lg:col-span-3">
        <label class="form-label">{{ __('dietary_choice') }}</label>
        <div class="flex items-center space-x-4 mt-2">
            <label class="flex items-center"><input type="radio" name="diet" value="veg" class="h-4 w-4" {{ old('diet', $user->diet) == 'veg' ? 'checked' : '' }}> <span class="ml-2">{{ __('veg') }}</span></label>
            <label class="flex items-center"><input type="radio" name="diet" value="non-veg" class="h-4 w-4" {{ old('diet', $user->diet) == 'non-veg' ? 'checked' : '' }}> <span class="ml-2">{{ __('non_veg') }}</span></label>
        </div>
    </div>
     @php
        $known_languages = array_filter(explode(',', old('languages_known', $user->languages_known)));
    @endphp
    <div class="lg:col-span-3">
        <label class="form-label">{{ __('languages_known') }}</label>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="flex items-end">
                <div class="flex-grow">
                    <select id="languages_known_select" class="form-select w-full">
                        <option value="">{{ __('select') }}</option>
                        @if(isset($motherTongues))
                            @foreach($motherTongues as $tongue)
                                <option value="{{ $tongue->title }}">{{ $tongue->title }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <button type="button" id="add_language_btn" class="ml-2 bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">{{ __('add') }}</button>
            </div>
            <div id="languages_known_container" class="pt-2 flex flex-wrap gap-2">
                {{-- Badges will be injected here by JS --}}
            </div>
        </div>
        <div id="languages_hidden_inputs" class="hidden">
            @foreach($known_languages as $lang)
                <input type="hidden" name="languages[]" value="{{ $lang }}">
            @endforeach
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const select = document.getElementById('languages_known_select');
    const addButton = document.getElementById('add_language_btn');
    const container = document.getElementById('languages_known_container');
    const hiddenInputsContainer = document.getElementById('languages_hidden_inputs');

    // Initialize the set of selected languages from the hidden inputs
    const selectedLanguages = new Set(
        Array.from(hiddenInputsContainer.querySelectorAll('input[name="languages[]"]'))
             .map(input => input.value)
    );

    // Function to create a language badge
    function createBadge(language) {
        const badge = document.createElement('div');
        badge.className = 'flex items-center bg-indigo-100 text-indigo-800 text-sm font-medium px-2.5 py-0.5 rounded-full';
        badge.dataset.language = language;

        const text = document.createElement('span');
        text.textContent = language;
        badge.appendChild(text);

        const removeButton = document.createElement('button');
        removeButton.type = 'button';
        removeButton.className = 'ml-2 text-indigo-500 hover:text-indigo-700 focus:outline-none';
        removeButton.innerHTML = '&times;';
        removeButton.setAttribute('aria-label', 'Remove ' + language);
        removeButton.onclick = function() {
            removeLanguage(language);
        };
        badge.appendChild(removeButton);

        return badge;
    }

    // Function to add a new language
    function addLanguage(language) {
        if (!language || selectedLanguages.has(language)) {
            return; // Don't add empty or duplicate languages
        }

        selectedLanguages.add(language);

        // Add badge to the container
        const badge = createBadge(language);
        container.appendChild(badge);

        // Add hidden input for form submission
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'languages[]';
        input.value = language;
        hiddenInputsContainer.appendChild(input);
    }

    // Function to remove a language
    function removeLanguage(language) {
        selectedLanguages.delete(language);

        // Remove badge from the container
        const badge = container.querySelector(`[data-language="${language}"]`);
        if (badge) {
            container.removeChild(badge);
        }

        // Remove hidden input
        const input = hiddenInputsContainer.querySelector(`input[value="${language}"]`);
        if (input) {
            hiddenInputsContainer.removeChild(input);
        }
    }

    // --- Event Listeners ---

    // Add button click
    addButton.addEventListener('click', function() {
        const selectedValue = select.value;
        if (selectedValue) {
            addLanguage(selectedValue);
            select.value = ''; // Reset dropdown
        }
    });

    // Optional: Allow adding language by pressing Enter in the select dropdown
    select.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const selectedValue = select.value;
            if (selectedValue) {
                addLanguage(selectedValue);
                select.value = ''; // Reset dropdown
            }
        }
    });

    // --- Initialization ---

    // Create badges for any languages that were already selected (e.g., from old input or db)
    selectedLanguages.forEach(lang => {
        const badge = createBadge(lang);
        container.appendChild(badge);
    });

    // Dynamic education dropdowns
    const highestEducationSelect = document.getElementById('highest_education_id');
    const educationSelect = document.getElementById('education_id');
    const oldEducationId = '{{ old('education_id', $user->education_id ?? '') }}';

    function fetchEducations(highestEducationId, selectedEducationId = null) {
        if (!highestEducationId) {
            educationSelect.innerHTML = '<option value="">{{ __("Select Education Detail") }}</option>';
            return;
        }

        fetch(`/get-educations/${highestEducationId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                educationSelect.innerHTML = '<option value="">{{ __("Select Education Detail") }}</option>';
                data.forEach(education => {
                    const option = document.createElement('option');
                    option.value = education.id;
                    option.textContent = education.name;
                    if (selectedEducationId && education.id == selectedEducationId) {
                        option.selected = true;
                    }
                    educationSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('There has been a problem with your fetch operation:', error);
            });
    }

    highestEducationSelect.addEventListener('change', function() {
        fetchEducations(this.value);
    });

    // On page load, if a highest education is already selected, fetch the educations
    const initialHighestEducationId = highestEducationSelect.value;
    if (initialHighestEducationId) {
        fetchEducations(initialHighestEducationId, oldEducationId);
    }
});
</script>
@endpush

<!-- Qualification & Occupation -->
<h2 class="text-2xl font-bold text-gray-700 pb-2 border-b-2 border-indigo-200 mb-6">{{ __('qualification_occupation') }}</h2>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <div>
        <label for="highest_education_id" class="form-label">{{ __('highest_education') }} *</label>
        <select name="highest_education_id" id="highest_education_id" class="form-input" required>
            <option value="">{{ __('Select Highest Education') }}</option>
            @if(isset($highest_qualifications))
                @foreach($highest_qualifications as $qualification)
                    <option value="{{ $qualification->id }}" {{ old('highest_education_id', $user->highest_education_id ?? '') == $qualification->id ? 'selected' : '' }}>{{ $qualification->name }}</option>
                @endforeach
            @endif
        </select>
    </div>
    <div>
        <label for="education_id" class="form-label">{{ __('education_details') }} *</label>
        <select name="education_id" id="education_id" class="form-input" required>
            <option value="">{{ __('Select Education Detail') }}</option>
        </select>
    </div>
    <div>
        <label for="employed_in" class="form-label">{{ __('employed_in') }}</label>
        <select name="employed_in" id="employed_in" class="form-input">
            <option value="">{{ __('Select Employment Type') }}</option>
            <option value="Business" {{ old('employed_in', $user->employed_in ?? '') == 'Business' ? 'selected' : '' }}>{{ __('Business') }}</option>
            <option value="Defence" {{ old('employed_in', $user->employed_in ?? '') == 'Defence' ? 'selected' : '' }}>{{ __('Defence') }}</option>
            <option value="Government" {{ old('employed_in', $user->employed_in ?? '') == 'Government' ? 'selected' : '' }}>{{ __('Government') }}</option>
            <option value="Not Employed in" {{ old('employed_in', $user->employed_in ?? '') == 'Not Employed in' ? 'selected' : '' }}>{{ __('Not Employed in') }}</option>
            <option value="Private" {{ old('employed_in', $user->employed_in ?? '') == 'Private' ? 'selected' : '' }}>{{ __('Private') }}</option>
            <option value="Others" {{ old('employed_in', $user->employed_in ?? '') == 'Others' ? 'selected' : '' }}>{{ __('Others') }}</option>
        </select>
    </div>
    <div>
        <label for="occupation_id" class="form-label">{{ __('occupation') }}</label>
        <select name="occupation_id" id="occupation_id" class="form-input">
            <option value="">{{ __('Select Occupation') }}</option>
            @if(isset($occupations))
                @foreach($occupations as $occupation)
                    <option value="{{ $occupation->id }}" {{ old('occupation_id', $user->occupation_id ?? '') == $occupation->id ? 'selected' : '' }}>{{ $occupation->name }}</option>
                @endforeach
            @endif
        </select>
    </div>
    <div class="lg:col-span-2">
        <label for="annual_income" class="form-label">{{ __('annual_income') }}</label>
        <input type="text" name="annual_income" class="form-input" value="{{ old('annual_income', $user->annual_income) }}">
    </div>
</div>

<!-- Contact Details -->
<h2 class="text-2xl font-bold text-gray-700 pb-2 border-b-2 border-indigo-200 mb-6">{{ __('contact_login_details') }}</h2>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <div>
        <label for="country" class="form-label">{{ __('country') }} *</label>
        <select name="country_id" id="country" class="form-input" required>
            <option value="">{{ __('select_country') }}</option>
        </select>
    </div>
    <div>
        <label for="state" class="form-label">{{ __('state') }} *</label>
        <select name="state_id" id="state" class="form-input" required>
            <option value="">{{ __('select_state') }}</option>
        </select>
    </div>
    <div>
        <label for="city" class="form-label">{{ __('city') }} *</label>
        <select name="city_id" id="city" class="form-input" required>
            <option value="">{{ __('select_city') }}</option>
        </select>
    </div>

@push('scripts')
<script>
    $(document).ready(function() {
        var userCountryId = '{{ old('country_id', $user->country_id) }}';
        var userStateId = '{{ old('state_id', $user->state_id) }}';
        var userCityId = '{{ old('city_id', $user->city_id) }}';

        // Fetch Countries
        $.ajax({
            url: '{{ route("getCountries") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(data) {
                $('#country').empty().append('<option value="">{{ __("select_country") }}</option>');
                $.each(data, function(key, value) {
                    var isSelected = value.id == userCountryId ? ' selected' : '';
                    $('#country').append('<option value="' + value.id + '"' + isSelected + '>' + value.name + '</option>');
                });
                if (userCountryId) {
                    $('#country').trigger('change');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching countries:', error);
            }
        });

        $('#country').on('change', function() {
            var countryId = $(this).val();
            console.log('Country changed to:', countryId);
            
            if (countryId) {
                $.ajax({
                    url: '{{ route("getStates") }}',
                    type: 'POST',
                    data: {
                        country_id: countryId,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(data) {
                        console.log('States response:', data);
                        $('#state').empty().append('<option value="">{{ __("select_state") }}</option>');
                        $.each(data, function(key, value) {
                            var isSelected = value.id == userStateId ? ' selected' : '';
                            $('#state').append('<option value="' + value.id + '"' + isSelected + '>' + value.name + '</option>');
                        });
                        
                        if (userStateId) {
                            $('#state').trigger('change');
                            userStateId = null; // Use it only once
                        } else {
                             $('#city').empty().append('<option value="">{{ __("select_city") }}</option>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching states:', error);
                        console.error('Response:', xhr.responseText);
                    }
                });
            } else {
                $('#state').empty().append('<option value="">{{ __("select_state") }}</option>');
                $('#city').empty().append('<option value="">{{ __("select_city") }}</option>');
            }
        });

        $('#state').on('change', function() {
            var stateId = $(this).val();
            if (stateId) {
                $.ajax({
                    url: '{{ route("getCities") }}',
                    type: 'POST',
                    data: {
                        state_id: stateId,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(data) {
                        $('#city').empty().append('<option value="">{{ __("select_city") }}</option>');
                        $.each(data, function(key, value) {
                            var isSelected = value.id == userCityId ? ' selected' : '';
                            $('#city').append('<option value="' + value.id + '"' + isSelected + '>' + value.city_master + '</option>');
                        });
                        if (userCityId) {
                            userCityId = null; // Use it only once
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching cities:', error);
                    }
                });
            } else {
                $('#city').empty().append('<option value="">{{ __("select_city") }}</option>');
            }
        });

    });
</script>
@endpush
    <div>
        <label for="mobile_number" class="form-label">{{ __('mobile_number') }} *</label>
        <input type="tel" id="mobile_number" name="mobile_number" class="form-input" value="{{ old('mobile_number', $user->mobile_number) }}" required>
    </div>
    <div>
        <label for="email" class="form-label">{{ __('email_id') }} *</label>
        <input type="email" id="email" name="email" class="form-input" value="{{ old('email', $user->email) }}" required>
    </div>
    @if(!$user->exists)
    <div>
        <label for="password" class="form-label">{{ __('password') }} *</label>
        <input type="password" id="password" name="password" class="form-input" required>
    </div>
    <div>
        <label for="password_confirmation" class="form-label">{{ __('confirm_password') }} *</label>
        <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" required>
    </div>
    @endif
</div>

