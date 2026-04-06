@extends('layouts.admin')

@section('title', 'Edit User: ' . $user->full_name)

@section('content')

@if($errors->any())
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-ban"></i> Validation Error!</h5>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PATCH')
    <div class="row">
        {{-- Left Column --}}
        <div class="col-md-4">
            <!-- Profile Image Card -->
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle"
                             src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : 'https://placehold.co/150x150/6c757d/ffffff?text=' . substr($user->full_name, 0, 1) }}"
                             alt="User profile picture">
                    </div>
                    <h3 class="profile-username text-center">{{ $user->full_name }}</h3>
                    <p class="text-muted text-center">{{ $user->role === 'admin' ? 'Administrator' : 'User' }}</p>
                </div>
            </div>

            <!-- Account Management Card -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Account Management</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="role">User Role</label>
                        <select class="form-control" id="role" name="role">
                            <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label for="verification_status">ID Verification Status</label>
                        <select class="form-control" id="verification_status" name="verification_status">
                            <option value="not_uploaded" {{ old('verification_status', $user->verification_status) == 'not_uploaded' ? 'selected' : '' }}>Not Uploaded</option>
                            <option value="pending" {{ old('verification_status', $user->verification_status) == 'pending' ? 'selected' : '' }}>Pending Approval</option>
                            <option value="verified" {{ old('verification_status', $user->verification_status) == 'verified' ? 'selected' : '' }}>Verified (Approved)</option>
                            <option value="rejected" {{ old('verification_status', $user->verification_status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    @if($user->id_proof)
                        <div class="mt-2 text-center">
                            <a href="{{ asset('storage/' . $user->id_proof) }}" target="_blank" class="btn btn-sm btn-outline-info btn-block">
                                <i class="fas fa-id-card"></i> View ID Proof
                            </a>
                        </div>
                    @endif
                    <hr>
                    <div class="form-group">
                        <label for="membership_id">Active Membership Plan</label>
                        <select class="form-control" id="membership_id" name="membership_id">
                            <option value="">None (No Active Plan)</option>
                            @foreach($memberships as $plan)
                                <option value="{{ $plan->id }}" {{ $currentSubscription && $currentSubscription->membership_id == $plan->id ? 'selected' : '' }}>
                                    {{ $plan->name }} ({{ $plan->visits_allowed }} visits)
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @if($currentSubscription)
                        <p class="text-muted">
                            Current Visits Used: {{ $currentSubscription->visits_used }}
                        </p>
                    @else
                        <p class="text-muted">This user has no active subscription.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right Column --}}
        <div class="col-md-8">
            <div class="card card-primary card-tabs">
                <div class="card-header p-0 pt-1">
                    <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                        <li class="nav-item"><a class="nav-link active" id="tab-basic-info-tab" data-toggle="pill" href="#tab-basic-info" role="tab">Basic Info</a></li>
                        <li class="nav-item"><a class="nav-link" id="tab-details-tab" data-toggle="pill" href="#tab-details" role="tab">Personal Details</a></li>
                        <li class="nav-item"><a class="nav-link" id="tab-professional-tab" data-toggle="pill" href="#tab-professional" role="tab">Professional</a></li>
                        <li class="nav-item"><a class="nav-link" id="tab-contact-tab" data-toggle="pill" href="#tab-contact" role="tab">Contact</a></li>
                        <li class="nav-item"><a class="nav-link" id="tab-interests-tab" data-toggle="pill" href="#tab-interests" role="tab">Interests</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-one-tabContent">
                        {{-- Basic Info Tab --}}
                        <div class="tab-pane fade show active" id="tab-basic-info" role="tabpanel">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Full Name *</label>
                                    <input type="text" name="full_name" class="form-control" value="{{ old('full_name', $user->full_name) }}" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Gender *</label>
                                    <select name="gender" class="form-control" required>
                                        <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Height *</label>
                                    <select name="height" class="form-control" required>
                                        <option value="" {{ !old('height', $user->height) ? 'selected' : '' }}>Select Height</option>
                                        @foreach(['4ft ( 121 cm )', '4ft 1in ( 124cm )', '4ft 2in ( 127cm )', '4ft 3in ( 129cm )', '4ft 4in ( 132cm )', '4ft 5in ( 132cm )', '4ft 6in ( 137cm )', '4ft 7in ( 139cm )', '4ft 8in ( 142cm )', '4ft 9in ( 144cm )', '4ft 10in ( 147cm )', '4ft 11in ( 149cm )', '5ft ( 152cm )', '5ft 1in ( 154cm )', '5ft 2in ( 157cm )', '5ft 3in ( 160cm )', '5ft 4in ( 162cm )', '5ft 5in ( 165cm )', '5ft 6in ( 167cm )', '5ft 7in ( 170cm )', '5ft 8in ( 172cm )', '5ft 9in ( 175cm )', '5ft 10in ( 177cm )', '5ft 11in ( 180cm )', '6ft ( 182cm )', '6ft 1in ( 185cm )', '6ft 2in ( 187cm )', '6ft 3in ( 190cm )', '6ft 4in ( 193cm )', '6ft 5in ( 195cm )', '6ft 6in ( 198cm )', '6ft 7in ( 200cm )', '6ft 8in ( 203cm )', '6ft 9in ( 205cm )', '6ft 10in ( 208cm )', '6ft 11in ( 210cm )', '7ft ( 213cm )'] as $height)
                                            <option value="{{ $height }}" {{ old('height', $user->height) == $height ? 'selected' : '' }}>{{ $height }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Weight (kg)</label>
                                    <input type="number" name="weight" class="form-control" value="{{ old('weight', $user->weight) }}">
                                </div>
                            </div>
                             @php
                                $dob = $user->dob ? \Carbon\Carbon::parse($user->dob) : null;
                            @endphp
                            <div class="form-group">
                                <label>Date of Birth *</label>
                                <div class="row">
                                    <div class="col-4"><select name="birth_day" class="form-control" required>
                                        @for($i=1; $i<=31; $i++) <option value="{{$i}}" {{ $dob && $dob->day == $i ? 'selected' : '' }}>{{$i}}</option> @endfor
                                    </select></div>
                                    <div class="col-4"><select name="birth_month" class="form-control" required>
                                        @for($i=1; $i<=12; $i++) <option value="{{$i}}" {{ $dob && $dob->month == $i ? 'selected' : '' }}>{{ date('F', mktime(0,0,0,$i,10)) }}</option> @endfor
                                    </select></div>
                                    <div class="col-4"><select name="birth_year" class="form-control" required>
                                        @for($i=date('Y')-18; $i>=1950; $i--) <option value="{{$i}}" {{ $dob && $dob->year == $i ? 'selected' : '' }}>{{$i}}</option> @endfor
                                    </select></div>
                                </div>
                            </div>
                        </div>
                        {{-- Personal Details Tab --}}
                        <div class="tab-pane fade" id="tab-details" role="tabpanel">
                             <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Marital Status *</label>
                                    <select name="marital_status" class="form-control" required>
                                        @php
                                            $currentMaritalStatus = old('marital_status', $user->marital_status ?: 'UnMarried');
                                        @endphp
                                        <option value="UnMarried" {{ $currentMaritalStatus == 'UnMarried' ? 'selected' : '' }}>UnMarried</option>
                                        <option value="Never Married" {{ $currentMaritalStatus == 'Never Married' ? 'selected' : '' }}>Never Married</option>
                                        <option value="Divorced" {{ $currentMaritalStatus == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                        <option value="Divorcee" {{ $currentMaritalStatus == 'Divorcee' ? 'selected' : '' }}>Divorcee</option>
                                        <option value="Widowed" {{ $currentMaritalStatus == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                        <option value="Widow/Widower" {{ $currentMaritalStatus == 'Widow/Widower' ? 'selected' : '' }}>Widow/Widower</option>
                                        <option value="Seperated" {{ $currentMaritalStatus == 'Seperated' ? 'selected' : '' }}>Seperated</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Mother Tongue *</label>
                                    <select name="mother_tongue_id" class="form-control select2" required>
                                        <option value="">Select Mother Tongue</option>
                                        @foreach($motherTongues as $tongue)
                                            <option value="{{ $tongue->id }}" {{ old('mother_tongue_id', $user->mother_tongue_id) == $tongue->id ? 'selected' : '' }}>{{ $tongue->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                             </div>
                        </div>
                        {{-- Professional Tab --}}
                        <div class="tab-pane fade" id="tab-professional" role="tabpanel">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Highest Education *</label>
                                    <select name="highest_education_id" id="highest_education_id" class="form-control select2" required>
                                        <option value="">Select Highest Education</option>
                                        @foreach($highestQualifications as $hq)
                                            <option value="{{ $hq->id }}" {{ old('highest_education_id', $user->highest_education_id) == $hq->id ? 'selected' : '' }}>{{ $hq->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Education Details *</label>
                                    <select name="education_id" id="education_id" class="form-control select2" required>
                                        <option value="">Select Education Details</option>
                                        @foreach($educations as $edu)
                                            <option value="{{ $edu->id }}" {{ old('education_id', $user->education_id) == $edu->id ? 'selected' : '' }}>{{ $edu->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Employed In</label>
                                    <select name="employed_in" class="form-control">
                                        <option value="" {{ !old('employed_in', $user->employed_in) ? 'selected' : '' }}>Select Sector</option>
                                        @foreach(['Government/PSU', 'Private', 'Business/Self-Employed', 'Not Working', 'Defense'] as $sector)
                                            <option value="{{ $sector }}" {{ old('employed_in', $user->employed_in) == $sector ? 'selected' : '' }}>{{ $sector }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Occupation *</label>
                                    <select name="occupation_id" class="form-control select2" required>
                                        <option value="">Select Occupation</option>
                                        @foreach($occupations as $occ)
                                            <option value="{{ $occ->id }}" {{ old('occupation_id', $user->occupation_id) == $occ->id ? 'selected' : '' }}>{{ $occ->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        {{-- Contact Tab --}}
                        <div class="tab-pane fade" id="tab-contact" role="tabpanel">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Email *</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Mobile Number</label>
                                    <input type="text" name="mobile_number" class="form-control" value="{{ old('mobile_number', $user->mobile_number) }}">
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-12">
                                    <h5 class="text-primary border-bottom pb-2">Residential Address</h5>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Country</label>
                                    <input type="text" name="residential_country" id="res_country" class="form-control" value="{{ old('residential_country', $user->residential_country) }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>State</label>
                                    <input type="text" name="residential_state" id="res_state" class="form-control" value="{{ old('residential_state', $user->residential_state) }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>City</label>
                                    <input type="text" name="residential_city" id="res_city" class="form-control" value="{{ old('residential_city', $user->residential_city) }}">
                                </div>
                                <div class="form-group col-12">
                                    <label>Address Details</label>
                                    <textarea name="residential_address" id="res_addr" class="form-control" rows="2">{{ old('residential_address', $user->residential_address) }}</textarea>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-12 d-flex justify-content-between align-items-center">
                                    <h5 class="text-primary border-bottom pb-2 flex-grow-1">Working Address</h5>
                                    <div class="custom-control custom-checkbox ml-3">
                                        <input type="checkbox" class="custom-control-input" id="same_as_residential">
                                        <label class="custom-control-label" for="same_as_residential">Same as Residential</label>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Country</label>
                                    <input type="text" name="working_country" id="work_country" class="form-control" value="{{ old('working_country', $user->working_country) }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>State</label>
                                    <input type="text" name="working_state" id="work_state" class="form-control" value="{{ old('working_state', $user->working_state) }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>City</label>
                                    <input type="text" name="working_city" id="work_city" class="form-control" value="{{ old('working_city', $user->working_city) }}">
                                </div>
                                <div class="form-group col-12">
                                    <label>Address Details</label>
                                    <textarea name="working_address" id="work_addr" class="form-control" rows="2">{{ old('working_address', $user->working_address) }}</textarea>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-12">
                                    <h5 class="text-muted border-bottom pb-2">Location (Searchable)</h5>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Master Country *</label>
                                    <select name="country_id" id="country_id" class="form-control select2" required>
                                        <option value="">Select Country</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}" {{ old('country_id', $user->country_id) == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Master State *</label>
                                    <select name="state_id" id="state_id" class="form-control select2" required>
                                        <option value="">Select State</option>
                                        @foreach($states as $state)
                                            <option value="{{ $state->id }}" {{ old('state_id', $user->state_id) == $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Master City *</label>
                                    <select name="city_id" id="city_id" class="form-control select2" required>
                                        <option value="">Select City</option>
                                        @foreach($cities as $city)
                                            <option value="{{ $city->id }}" {{ old('city_id', $user->city_id) == $city->id ? 'selected' : '' }}>{{ $city->city_master }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        {{-- Interests Tab --}}
                        <div class="tab-pane fade" id="tab-interests" role="tabpanel">
                            <div class="row">
                                <div class="form-group col-12">
                                    <label>Hobbies / Interests</label>
                                    <select name="hobbies[]" id="hobbies" class="form-control select2" multiple="multiple" data-placeholder="Select Hobbies">
                                        @foreach($allHobbies as $hobby)
                                            <option value="{{ $hobby->id }}" {{ in_array($hobby->id, old('hobbies', $userHobbyIds)) ? 'selected' : '' }}>
                                                {{ $hobby->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">Select multiple hobbies that apply.</small>
                                </div>
                                <div class="form-group col-12 mt-3">
                                    <label>Languages Known</label>
                                    <select name="languages[]" id="languages" class="form-control select2" multiple="multiple" data-placeholder="Select Languages">
                                        @foreach(['English', 'Hindi', 'Gujarati', 'Marathi', 'Punjabi', 'Bengali', 'Tamil', 'Telugu', 'Kannada', 'Malayalam'] as $lang)
                                            @php
                                                $knownLanguages = array_map('trim', explode(',', $user->languages_known ?? ''));
                                            @endphp
                                            <option value="{{ $lang }}" {{ in_array($lang, old('languages', $knownLanguages)) ? 'selected' : '' }}>
                                                {{ $lang }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-4">
             <button type="submit" class="btn btn-primary" id="submitBtn">Save All Changes</button>
             <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </div>
</form>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #007bff;
        border-color: #006fe6;
        color: #fff;
        padding: 0 10px;
        margin-top: 5px;
    }
    .select2-container .select2-selection--single {
        height: 38px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }
    .is-invalid + .select2-container .select2-selection {
        border-color: #dc3545 !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        width: '100%',
        theme: 'default'
    });

    // Dependent Dropdown for Country -> State
    $('#country_id').on('change', function() {
        var countryId = $(this).val();
        $('#state_id').html('<option value="">Loading...</option>');
        $('#city_id').html('<option value="">Select City</option>');
        
        if (countryId) {
            $.get('/admin/settings/get-states/' + countryId, function(states) {
                var html = '<option value="">Select State</option>';
                states.forEach(function(state) {
                    html += `<option value="${state.id}">${state.name}</option>`;
                });
                $('#state_id').html(html).trigger('change');
            });
        } else {
            $('#state_id').html('<option value="">Select State</option>');
        }
    });

    // Dependent Dropdown for State -> City
    $('#state_id').on('change', function() {
        var stateId = $(this).val();
        $('#city_id').html('<option value="">Loading...</option>');
        
        if (stateId) {
            $.get('/admin/settings/get-cities/' + stateId, function(cities) {
                var html = '<option value="">Select City</option>';
                cities.forEach(function(city) {
                    html += `<option value="${city.id}">${city.name}</option>`;
                });
                $('#city_id').html(html);
            });
        } else {
            $('#city_id').html('<option value="">Select City</option>');
        }
    });

    // Dependent Dropdown for Highest Education -> Education Details
    $('#highest_education_id').on('change', function() {
        var hqId = $(this).val();
        $('#education_id').html('<option value="">Loading...</option>');
        
        if (hqId) {
            $.get('/admin/settings/get-educations/' + hqId, function(educations) {
                var html = '<option value="">Select Education Details</option>';
                educations.forEach(function(edu) {
                    html += `<option value="${edu.id}">${edu.name}</option>`;
                });
                $('#education_id').html(html);
            });
        } else {
            $('#education_id').html('<option value="">Select Education Details</option>');
        }
    });

    // Prevent default HTML5 validation
    $('form').attr('novalidate', 'novalidate');
    
    // Handle form submission to show tabs with errors
    $('form').on('submit', function(e) {
        var form = this;
        var isValid = true;
        var firstInvalidField = null;
        var firstInvalidTab = null;
        
        // Remove previous invalid classes
        $(form).find('.is-invalid').removeClass('is-invalid');
        
        // Check all required fields
        $(form).find('input[required], select[required], textarea[required]').each(function() {
            var field = $(this);
            var fieldValue = field.val();
            var isFieldValid = true;
            
            // Check if field is empty or invalid
            if (field.attr('type') === 'email') {
                var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                isFieldValid = fieldValue && emailPattern.test(fieldValue);
            } else if (field.is('select')) {
                isFieldValid = fieldValue && fieldValue !== '';
            } else {
                isFieldValid = fieldValue && fieldValue.trim() !== '';
            }
            
            if (!isFieldValid) {
                isValid = false;
                field.addClass('is-invalid');
                
                // Find the tab containing this field
                var tabPane = field.closest('.tab-pane');
                
                if (tabPane.length && !tabPane.hasClass('active')) {
                    var tabId = tabPane.attr('id');
                    var tabLink = $('a[href="#' + tabId + '"]');
                    
                    // Activate the tab
                    tabLink.tab('show');
                    
                    // Store first invalid tab for scrolling
                    if (!firstInvalidTab) {
                        firstInvalidTab = tabPane;
                    }
                }
                
                // Store first invalid field for focusing
                if (!firstInvalidField) {
                    firstInvalidField = field;
                }
            }
        });
        
        // If form is invalid, prevent submission
        if (!isValid) {
            e.preventDefault();
            e.stopPropagation();
            
            // Show alert
            alert('Please fill in all required fields. The form will navigate to fields that need attention.');
            
            // Wait for tab animation, then focus and scroll
            setTimeout(function() {
                if (firstInvalidTab) {
                    $('html, body').animate({
                        scrollTop: $(firstInvalidTab).offset().top - 100
                    }, 300);
                }
                
                if (firstInvalidField) {
                    firstInvalidField.focus();
                }
            }, 400);
            
            return false;
        }
    });
    
    // Same as Residential Address Logic (Admin)
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
                }
            });
        } else {
            workFields.forEach((field) => {
                const workElement = document.getElementById(field);
                if (workElement) {
                    workElement.removeAttribute('readonly');
                }
            });
        }
    }
    
    if (sameAsResCheckbox) {
        sameAsResCheckbox.addEventListener('change', syncAddress);
        
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
    }

    // Existing select2 and other logic...
    // Remove invalid class on input
    $('input, select, textarea').on('input change', function() {
        var field = $(this);
        var fieldValue = field.val();
        var isFieldValid = true;
        
        if (field.attr('required')) {
            if (field.attr('type') === 'email') {
                var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                isFieldValid = fieldValue && emailPattern.test(fieldValue);
            } else if (field.is('select')) {
                isFieldValid = fieldValue && fieldValue !== '';
            } else {
                isFieldValid = fieldValue && fieldValue.trim() !== '';
            }
            
            if (isFieldValid) {
                field.removeClass('is-invalid');
            }
        }
    });
});
</script>
@endpush
@endsection

