@extends('layouts.user')

@section('title', __('common.Verify ID') . ' - GoswamiSangath')

@section('content')
<div class="flex flex-col gap-8 max-w-4xl">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 border-b border-gray-200 dark:border-white/5 pb-6">
        <div>
            <h1 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white tracking-tight mb-2">{{ __('common.Verify ID') }}</h1>
            <p class="text-gray-500 dark:text-[#b9a19d] font-medium">{{ __('Get verified and earn more trust on your profile.') }}</p>
        </div>
        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-primary transition-colors">
            <span class="material-symbols-outlined text-lg">arrow_back</span>
            {{ __('common.Back to Profile') }}
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-500/10 border border-green-500/50 rounded-xl p-4 flex items-center gap-3 text-green-600 dark:text-green-400">
            <span class="material-symbols-outlined">check_circle</span>
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-500/10 border border-red-500/50 rounded-xl p-4 flex items-center gap-3 text-red-600 dark:text-red-400">
            <span class="material-symbols-outlined">error</span>
            <p class="font-medium">{{ session('error') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Info Card -->
        <div class="bg-white dark:bg-surface-dark rounded-2xl border border-gray-200 dark:border-white/5 p-6 md:p-8 shadow-sm">
            <div class="flex items-center gap-4 mb-6">
                <div class="p-3 bg-primary/10 rounded-full text-primary">
                    <span class="material-symbols-outlined text-3xl">badge</span>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('common.Why verify?') }}</h2>
                </div>
            </div>
            <p class="text-gray-600 dark:text-gray-400 mb-6 leading-relaxed">
                {{ __("common.Verified profiles get up to 3x more views and responses.") }}
            </p>
            <p class="text-gray-600 dark:text-gray-400 mb-6 leading-relaxed">
                {{ __("common.ID verification helps build trust in the community. You can upload a government-issued ID (Aadhaar, PAN, or passport) and we'll verify it securely. Your document is not shared with other members.") }}
            </p>
            <div class="flex items-center gap-3 p-4 bg-gray-50 dark:bg-white/5 rounded-xl border border-gray-100 dark:border-white/5">
                <span class="material-symbols-outlined text-primary">security</span>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    {{ __("common.Your information is securely encrypted and never shared without permission.") }}
                </p>
            </div>
        </div>

        <!-- Status & Action Card -->
        <div class="bg-white dark:bg-surface-dark rounded-2xl border border-gray-200 dark:border-white/5 p-6 md:p-8 shadow-sm">
            <h3 class="text-lg font-bold mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">info</span>
                {{ __('Verification Status') }}
            </h3>

            @if($user->verification_status === 'verified')
                <div class="flex flex-col items-center justify-center py-10 text-center">
                    <div class="w-20 h-20 bg-green-500/10 rounded-full flex items-center justify-center text-green-500 mb-4 animate-bounce">
                        <span class="material-symbols-outlined text-5xl">verified</span>
                    </div>
                    <h4 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ __('common.Verified') }}</h4>
                    <p class="text-gray-500 dark:text-gray-400 mb-6">{{ __('Your identity has been successfully verified.') }}</p>
                    <div class="inline-flex items-center gap-2 py-2 px-4 bg-green-500/10 text-green-600 rounded-full text-sm font-bold">
                        <span class="material-symbols-outlined text-lg">check_circle</span>
                        {{ __('ID Document Verified') }}
                    </div>
                </div>
            @elseif($user->verification_status === 'pending')
                <div class="flex flex-col items-center justify-center py-10 text-center">
                    <div class="w-20 h-20 bg-amber-500/10 rounded-full flex items-center justify-center text-amber-500 mb-4 animate-pulse">
                        <span class="material-symbols-outlined text-5xl">pending</span>
                    </div>
                    <h4 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ __('Verification Pending') }}</h4>
                    <p class="text-gray-500 dark:text-gray-400 mb-6">{{ __('We are currently reviewing your documents. This usually takes 24-48 hours.') }}</p>
                    
                    <div class="w-full max-w-xs space-y-4">
                         <div class="p-4 bg-amber-500/5 rounded-xl border border-amber-500/20">
                            <p class="text-xs text-amber-600 dark:text-amber-400 font-medium">
                                {{ __('You will receive a notification once the review is complete.') }}
                            </p>
                         </div>
                         <p class="text-xs text-gray-400 italic">{{ __('Need to change your document?') }}</p>
                         <button onclick="document.getElementById('upload-form-section').classList.remove('hidden'); this.parentElement.classList.add('hidden')" class="text-primary text-sm font-bold hover:underline mb-2">
                            {{ __('Re-upload Document') }}
                         </button>
                    </div>
                </div>
            @elseif($user->verification_status === 'rejected')
                <div class="flex flex-col items-center justify-center py-10 text-center">
                    <div class="w-20 h-20 bg-red-500/10 rounded-full flex items-center justify-center text-red-500 mb-4">
                        <span class="material-symbols-outlined text-5xl">cancel</span>
                    </div>
                    <h4 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ __('Verification Rejected') }}</h4>
                    <p class="text-gray-500 dark:text-gray-400 mb-6">{{ __('Your document was not accepted. Please upload a clear photo of a valid government ID.') }}</p>
                    <button onclick="document.getElementById('upload-form-section').classList.remove('hidden')" class="py-3 px-8 bg-primary hover:bg-red-600 text-white font-bold rounded-xl transition-all shadow-lg shadow-primary/20">
                        {{ __('Try Again') }}
                    </button>
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-10 text-center" id="upload-prompt">
                    <div class="w-20 h-20 bg-gray-100 dark:bg-white/5 rounded-full flex items-center justify-center text-gray-400 mb-4">
                        <span class="material-symbols-outlined text-5xl">cloud_upload</span>
                    </div>
                    <h4 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ __('Not Verified') }}</h4>
                    <p class="text-gray-500 dark:text-gray-400 mb-8">{{ __('Upload your ID to build trust and get more matches.') }}</p>
                    <button onclick="document.getElementById('upload-form-section').classList.remove('hidden'); document.getElementById('upload-prompt').classList.add('hidden')" class="py-3 px-8 bg-primary hover:bg-red-600 text-white font-bold rounded-xl transition-all shadow-lg shadow-primary/20">
                        {{ __('Start Verification') }}
                    </button>
                </div>
            @endif

            <div id="upload-form-section" class="{{ in_array($user->verification_status, ['not_uploaded', 'rejected']) ? 'hidden' : 'hidden' }} mt-6 pt-6 border-t border-gray-100 dark:border-white/5">
                <form action="{{ route('profile.verify-id.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-bold text-gray-700 dark:text-gray-200">{{ __('common.Upload ID Proof (Aadhar/PAN/License)') }} <span class="text-primary">*</span></label>
                        <div class="relative group">
                            <input type="file" name="id_proof" required accept="image/*,.pdf" class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-black file:bg-primary/10 file:text-primary hover:file:bg-primary hover:file:text-white transition-all cursor-pointer border border-dashed border-gray-300 dark:border-white/10 p-4 rounded-xl group-hover:border-primary group-hover:bg-primary/5">
                        </div>
                        <p class="text-[10px] text-gray-400 italic">{{ __('Max file size: 4MB. Supported formats: JPEG, PNG, PDF.') }}</p>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="flex-1 py-3 px-6 bg-primary hover:bg-red-600 text-white font-black rounded-xl transition-all shadow-lg shadow-primary/20">
                            {{ __('common.Submit') }}
                        </button>
                        @if($user->id_proof)
                        <button type="button" onclick="document.getElementById('upload-form-section').classList.add('hidden'); document.getElementById('upload-prompt') ? document.getElementById('upload-prompt').classList.remove('hidden') : null" class="py-3 px-6 bg-gray-100 dark:bg-white/5 text-gray-600 dark:text-gray-300 font-bold rounded-xl transition-all hover:bg-gray-200">
                            {{ __('common.Cancel') }}
                        </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // If user's status is 'not_uploaded' or 'rejected', show the form by default if prompt is clicked or automatically
    @if($user->verification_status === 'not_uploaded' || $user->verification_status === 'rejected')
        // We could auto-show but let's keep the UX clean with the Start button
    @endif
</script>
@endsection
