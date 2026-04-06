@extends('layouts.admin')

@section('title', 'Site Settings')

@section('content')
<div class="container-fluid">
    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row mb-3">
            <div class="col-12">
                <div class="card card-primary card-outline card-outline-tabs shadow-sm">
                    <div class="card-header p-0 border-bottom-0">
                        <ul class="nav nav-tabs" id="settingsTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="general-tab" data-toggle="pill" href="#general" role="tab" aria-controls="general" aria-selected="true">
                                    <i class="fas fa-cogs mr-1"></i> General Settings
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pages-tab" data-toggle="pill" href="#pages" role="tab" aria-controls="pages" aria-selected="false">
                                    <i class="fas fa-file-alt mr-1"></i> Page Content
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-content" id="settingsTabContent">
            <!-- General Settings Tab -->
            <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                <div class="row">
                    <!-- Left Column: Branding & Footer -->
                    <div class="col-lg-7">
                        <!-- Branding Card -->
                        <div class="card card-outline card-primary shadow-sm mb-4">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-brand-base mr-2"></i> General Branding
                                </h3>
                            </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <i class="icon fas fa-check mr-2"></i> {{ session('success') }}
                            </div>
                        @endif

                        {{-- Site Logo (Light/Dark) --}}
                        <div class="row mb-4">
                            <div class="col-md-6 border-right">
                                <label class="font-weight-bold">Site Logo (Light Mode)</label>
                                @if(isset($settings['site_logo']) && $settings['site_logo'])
                                    <div class="mb-3 p-2 border rounded bg-light d-inline-block d-block text-center">
                                        <img src="{{ asset('storage/' . $settings['site_logo']) }}" alt="Light Logo" class="img-fluid" style="max-height: 80px;">
                                    </div>
                                @endif
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="site_logo" name="site_logo">
                                    <label class="custom-file-label" for="site_logo">Choose light logo...</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="font-weight-bold">Site Logo (Dark Mode)</label>
                                @if(isset($settings['site_logo_dark']) && $settings['site_logo_dark'])
                                    <div class="mb-3 p-2 border rounded bg-dark d-inline-block d-block text-center">
                                        <img src="{{ asset('storage/' . $settings['site_logo_dark']) }}" alt="Dark Logo" class="img-fluid" style="max-height: 80px;">
                                    </div>
                                @endif
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="site_logo_dark" name="site_logo_dark">
                                    <label class="custom-file-label" for="site_logo_dark">Choose dark logo...</label>
                                </div>
                                <small class="text-muted d-block mt-1">Used when dark theme is enabled.</small>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="font-weight-bold d-block">Site Name Display Type</label>
                            <div class="d-flex gap-4 align-items-center mt-2">
                                <div class="custom-control custom-radio mr-4">
                                    <input class="custom-control-input" type="radio" id="site_name_type_text" name="site_name_type" value="text" {{ ($settings['site_name_type'] ?? 'text') === 'text' ? 'checked' : '' }} onchange="toggleSiteNameInputs()">
                                    <label for="site_name_type_text" class="custom-control-label">Text Based</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input" type="radio" id="site_name_type_image" name="site_name_type" value="image" {{ ($settings['site_name_type'] ?? 'text') === 'image' ? 'checked' : '' }} onchange="toggleSiteNameInputs()">
                                    <label for="site_name_type_image" class="custom-control-label">Image Based</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4" id="site_name_text_container" style="{{ ($settings['site_name_type'] ?? 'text') === 'image' ? 'display:none;' : '' }}">
                            <label for="site_name" class="font-weight-bold">Site Name (Text)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-globe"></i></span>
                                </div>
                                <input type="text" class="form-control @error('site_name') is-invalid @enderror" id="site_name" name="site_name" placeholder="Enter site name" value="{{ $settings['site_name'] ?? 'GoswamiSangath' }}">
                            </div>
                            @error('site_name')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div id="site_name_image_container" style="{{ ($settings['site_name_type'] ?? 'text') === 'text' ? 'display:none;' : '' }}">
                            <div class="row">
                                <div class="col-md-6 border-right">
                                    <label class="font-weight-bold">Site Name Image (Light Mode)</label>
                                    @if(isset($settings['site_name_image']) && $settings['site_name_image'])
                                        <div class="mb-3 p-2 border rounded bg-light d-inline-block d-block text-center">
                                            <img src="{{ asset('storage/' . $settings['site_name_image']) }}" alt="Light Name Image" class="img-fluid" style="max-height: 60px;">
                                        </div>
                                    @endif
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="site_name_image" name="site_name_image">
                                        <label class="custom-file-label" for="site_name_image">Choose light brand image...</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="font-weight-bold">Site Name Image (Dark Mode)</label>
                                    @if(isset($settings['site_name_image_dark']) && $settings['site_name_image_dark'])
                                        <div class="mb-3 p-2 border rounded bg-dark d-inline-block d-block text-center">
                                            <img src="{{ asset('storage/' . $settings['site_name_image_dark']) }}" alt="Dark Name Image" class="img-fluid" style="max-height: 60px;">
                                        </div>
                                    @endif
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="site_name_image_dark" name="site_name_image_dark">
                                        <label class="custom-file-label" for="site_name_image_dark">Choose dark brand image...</label>
                                    </div>
                                </div>
                            </div>
                            <small class="form-text text-muted mt-2">
                                <i class="fas fa-info-circle mr-1"></i> These images will be used instead of the site name text.
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Footer Card -->
                <div class="card card-outline card-secondary shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-info-circle mr-2"></i> Footer Information
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="footer_info" class="font-weight-bold">Footer Text</label>
                            <textarea class="form-control @error('footer_info') is-invalid @enderror" id="footer_info" name="footer_info" rows="4" placeholder="Enter footer information / about text">{{ $settings['footer_info'] ?? '' }}</textarea>
                            <small class="form-text text-muted">This text will appear in the footer section of the website.</small>
                            @error('footer_info')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Demo Mode & Controls -->
            <div class="col-lg-5">
                <!-- Demo Mode Card -->
                <div class="card card-outline {{ ($settings['demo_mode'] ?? 'off') === 'on' ? 'card-warning' : 'card-success' }} shadow-sm mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h3 class="card-title">
                            <i class="fas fa-clock mr-2"></i> Demo Mode & Maintenance
                        </h3>
                        <div class="card-tools ml-auto">
                            <div class="custom-control custom-switch custom-switch-xl">
                                <input type="checkbox" class="custom-control-input" id="demo_mode" name="demo_mode" {{ ($settings['demo_mode'] ?? 'off') === 'on' ? 'checked' : '' }}>
                                <label class="custom-control-label" for="demo_mode"></label>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <span class="badge {{ ($settings['demo_mode'] ?? 'off') === 'on' ? 'badge-warning' : 'badge-success' }} mb-2 p-2 px-3" id="demo_status_badge">
                                {{ ($settings['demo_mode'] ?? 'off') === 'on' ? 'DEMO MODE ACTIVE' : 'SITE IS LIVE' }}
                            </span>
                            <div class="text-muted small mt-1">
                                <i class="fas fa-exclamation-triangle mr-1 text-warning"></i>
                                When enabled, regular users are restricted to the countdown page. Admins can still access the full site.
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="live_at" class="font-weight-bold">Platform Launch Date & Time</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                </div>
                                <input type="datetime-local" class="form-control @error('live_at') is-invalid @enderror" id="live_at" name="live_at" value="{{ isset($settings['live_at']) ? date('Y-m-d\TH:i', strtotime($settings['live_at'])) : '' }}">
                            </div>
                            <small class="form-text text-muted">The countdown timer counts down to this target date.</small>
                            @error('live_at')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mt-3">
                            <label for="demo_bonus_interests_limit" class="font-weight-bold text-warning">
                                <i class="fas fa-gift mr-1"></i> Bonus Interests for Demo Mode Users
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-heart"></i></span>
                                </div>
                                <input type="number" class="form-control @error('demo_bonus_interests_limit') is-invalid @enderror" id="demo_bonus_interests_limit" name="demo_bonus_interests_limit" value="{{ $settings['demo_bonus_interests_limit'] ?? '5' }}" min="0">
                            </div>
                            <small class="form-text text-muted">Automatically applied to users who register while Demo Mode is active. Set 0 for default free plan limit.</small>
                            @error('demo_bonus_interests_limit')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Privacy & Visibility Card -->
                <div class="card card-outline card-info shadow-sm mb-4">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-user-shield mr-2"></i> Privacy & Visibility
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-4">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <label for="hide_contact_if_not_accepted" class="mb-0 font-weight-bold">
                                    Hide Contact Info if Interest Not Accepted
                                </label>
                                <div class="custom-control custom-switch custom-switch-xl">
                                    <input type="checkbox" class="custom-control-input" id="hide_contact_if_not_accepted" name="hide_contact_if_not_accepted" {{ ($settings['hide_contact_if_not_accepted'] ?? 'on') === 'on' ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="hide_contact_if_not_accepted"></label>
                                </div>
                            </div>
                            <small class="form-text text-muted">
                                If enabled, Mobile Number and Email will be hidden from matches until they accept an interest or have mutual interest.
                            </small>
                        </div>

                        <hr>

                        <div class="form-group mb-0 mt-3">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <label for="hide_address_if_not_accepted" class="mb-0 font-weight-bold">
                                    Hide Address Info if Interest Not Accepted
                                </label>
                                <div class="custom-control custom-switch custom-switch-xl">
                                    <input type="checkbox" class="custom-control-input" id="hide_address_if_not_accepted" name="hide_address_if_not_accepted" {{ ($settings['hide_address_if_not_accepted'] ?? 'on') === 'on' ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="hide_address_if_not_accepted"></label>
                                </div>
                            </div>
                            <small class="form-text text-muted">
                                If enabled, Residential and Working Addresses will be hidden from matches until they accept an interest or have mutual interest.
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Mobile App Links Card -->
                <div class="card card-outline card-primary shadow-sm mb-4">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-mobile-alt mr-2"></i> Mobile App Settings
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="android_app_link" class="font-weight-bold">
                                <i class="fab fa-android mr-1 text-success"></i> Google Play Store Link
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-link"></i></span>
                                </div>
                                <input type="url" class="form-control @error('android_app_link') is-invalid @enderror" id="android_app_link" name="android_app_link" placeholder="https://play.google.com/store/apps/details?id=..." value="{{ $settings['android_app_link'] ?? '' }}">
                            </div>
                            @error('android_app_link')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mt-3">
                            <label for="ios_app_link" class="font-weight-bold">
                                <i class="fab fa-apple mr-1 text-dark"></i> Apple App Store Link
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-link"></i></span>
                                </div>
                                <input type="url" class="form-control @error('ios_app_link') is-invalid @enderror" id="ios_app_link" name="ios_app_link" placeholder="https://apps.apple.com/app/..." value="{{ $settings['ios_app_link'] ?? '' }}">
                            </div>
                            @error('ios_app_link')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- Close the General tab content div -->

    <!-- Page Content Tab -->
    <div class="tab-pane fade" id="pages" role="tabpanel" aria-labelledby="pages-tab">
        <div class="row">
            <div class="col-lg-12">
                <!-- Terms & Conditions Card -->
                <div class="card card-outline card-primary shadow-sm mb-4">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-file-contract mr-2"></i> Terms and Conditions Content
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="terms_content" class="font-weight-bold">HTML Content</label>
                            <textarea class="form-control @error('terms_content') is-invalid @enderror" id="terms_content" name="terms_content" rows="15" placeholder="Enter HTML content for Terms and Conditions">{{ $settings['terms_content'] ?? '' }}</textarea>
                            <small class="form-text text-muted">You can use HTML tags to format the content. This will replace the entire content section of the Terms page.</small>
                            @error('terms_content')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Privacy Policy Card -->
                <div class="card card-outline card-info shadow-sm mb-4">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-user-shield mr-2"></i> Privacy Policy Content
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="privacy_content" class="font-weight-bold">HTML Content</label>
                            <textarea class="form-control @error('privacy_content') is-invalid @enderror" id="privacy_content" name="privacy_content" rows="15" placeholder="Enter HTML content for Privacy Policy">{{ $settings['privacy_content'] ?? '' }}</textarea>
                            <small class="form-text text-muted">You can use HTML tags to format the content. This will be displayed on the Privacy Policy page.</small>
                            @error('privacy_content')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> <!-- Close the tab-content div -->

<!-- Save Card (Outside Tabs) -->
<div class="row">
    <div class="col-12">
        <div class="card bg-light shadow-sm">
            <div class="card-body py-4">
                <div class="d-flex align-items-center justify-content-between">
                    <p class="text-muted small mb-0 flex-grow-1">
                        <i class="fas fa-lightbulb mr-1 text-primary"></i> Changes made in any tab will be saved together.
                    </p>
                    <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm">
                        <i class="fas fa-save mr-2"></i> Save All Settings
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
</form>
</div>

<style>
    .custom-switch-xl .custom-control-label {
        padding-left: 3.5rem;
        padding-top: 0.1rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        height: 2rem;
        user-select: none;
    }
    .custom-switch-xl .custom-control-label::before {
        height: 2rem;
        width: 3.5rem;
        border-radius: 2rem;
        background-color: #28a745 !important; /* Emerald Green (OFF) */
        border: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        top: 0 !important;
        left: 0 !important;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);
    }
    .custom-switch-xl .custom-control-label::after {
        width: calc(2rem - 8px);
        height: calc(2rem - 8px);
        background-color: #fff !important;
        border-radius: 50%;
        top: 4px !important;
        left: 4px !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    .custom-switch-xl .custom-control-input:checked ~ .custom-control-label::before {
        background-color: #fd7e14 !important; /* Deep Orange (ON) */
    }
    .custom-switch-xl .custom-control-input:checked ~ .custom-control-label::after {
        transform: translateX(1.5rem);
    }
    
    /* Hover Glow Effect */
    .custom-switch-xl .custom-control-label:hover::before {
        filter: brightness(1.05);
        box-shadow: 0 0 12px rgba(0,0,0,0.15), inset 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .card-tools .custom-switch-xl {
        display: flex;
        align-items: center;
        height: 100%;
    }
</style>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    // Logo file input label update
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });

    // Toggle between site name text and image containers
    $('input[name="site_name_type"]').change(function() {
        if ($(this).val() === 'text') {
            $('#site_name_text_container').slideDown();
            $('#site_name_image_container').slideUp();
        } else {
            $('#site_name_text_container').slideUp();
            $('#site_name_image_container').slideDown();
        }
    });

    // Dynamic card color change based on toggle
    $('#demo_mode').change(function() {
        const card = $(this).closest('.card');
        const badge = $('#demo_status_badge');
        
        if($(this).is(':checked')) {
            card.removeClass('card-success').addClass('card-warning');
            badge.removeClass('badge-success').addClass('badge-warning').text('DEMO MODE ACTIVE');
        } else {
            card.removeClass('card-warning').addClass('card-success');
            badge.removeClass('badge-warning').addClass('badge-success').text('SITE IS LIVE');
        }
    });
});
</script>
@endpush
