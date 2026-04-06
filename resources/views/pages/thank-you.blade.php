@extends('layouts.app')

@section('title', __('common.thank_you') . ' - ' . ($siteSettings->site_name ?? 'GoswamiSangath'))

@push('styles')
<style>
    header, footer.site-footer {
        display: none !important;
    }
</style>
@endpush

@php
    $launchDateDisplay = '31st March';
    if (!empty($liveAt)) {
        try {
            $launchDateDisplay = \Carbon\Carbon::parse($liveAt)->format('jS F');
        } catch (\Exception $e) { }
    } elseif (isset($siteSettings->live_at) && !empty($siteSettings->live_at)) {
        try {
            $launchDateDisplay = \Carbon\Carbon::parse($siteSettings->live_at)->format('jS F');
        } catch (\Exception $e) { }
    }
@endphp

@section('content')
<div class="relative min-h-screen flex items-center justify-center bg-slate-50/50 dark:bg-[#181211] overflow-hidden py-12 px-4 selection:bg-primary selection:text-white">
    
    <!-- Ambient Background Lighting -->
    <div class="absolute top-0 left-0 w-full h-full opacity-30 dark:opacity-20 pointer-events-none z-0">
        <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] bg-primary rounded-full blur-[150px]"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] bg-blue-500 rounded-full blur-[150px]"></div>
    </div>

    <div class="relative z-10 w-full max-w-[850px] animate-slide-up">
        
        <!-- Premium Glass Card -->
        <div class="bg-white/80 dark:bg-[#271d1c]/80 backdrop-blur-xl rounded-[2.5rem] md:rounded-[3rem] shadow-[0_20px_50px_rgba(0,0,0,0.05)] dark:shadow-[0_20px_50px_rgba(0,0,0,0.5)] border border-white/50 dark:border-[#392b28] p-8 md:p-14 overflow-hidden relative">
            
            <!-- Shimmer effect overlay -->
            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent pointer-events-none"></div>
            
            <div class="relative z-10">
                
                <!-- Header Section -->
                <div class="text-center mb-14">
                    <div class="relative inline-flex items-center justify-center mb-8 mx-auto group">
                        <!-- Inner glowing shadow -->
                        <div class="absolute inset-1 bg-green-500/20 dark:bg-green-500/20 rounded-full blur-xl"></div>
                        <!-- Main icon container -->
                        <div class="relative w-24 h-24 bg-gradient-to-tr from-green-500 to-emerald-400 rounded-full flex items-center justify-center shadow-lg shadow-green-500/30 border-[6px] border-white dark:border-[#181211] z-10 overflow-hidden transform group-hover:scale-105 transition-transform duration-500">
                            <!-- Shine effect -->
                            <div class="absolute inset-0 bg-gradient-to-tr from-white/0 via-white/40 to-white/0 -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                            <!-- Icon -->
                            <i class="fas fa-check text-white" style="font-size: 48px;"></i>
                        </div>
                    </div>
                    
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-gray-900 dark:text-white mb-6 tracking-tighter leading-tight">
                        {{ __('Thank You!') }} <br/>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-orange-500">{{ __('Registration Complete.') }}</span>
                    </h1>
                    
                    <p class="text-lg md:text-xl text-gray-500 dark:text-gray-400 font-medium max-w-2xl mx-auto leading-relaxed">
                        {{ __('Your profile has been successfully created. Welcome to') }} <strong class="text-primary">{{ $siteSettings->site_name ?? 'GoswamiSangath' }}</strong>.
                    </p>
                </div>

                <!-- Roadmap Container -->
                <div class="bg-white/60 dark:bg-[#181211]/50 backdrop-blur-sm rounded-[2rem] p-8 md:p-12 border border-slate-100 dark:border-[#392b28]/50 shadow-inner mb-12">
                    <div class="mb-12 text-center md:text-left">
                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full border border-primary/20 bg-primary/5 w-fit mb-4">
                            <span class="size-2 rounded-full bg-primary animate-pulse"></span>
                            <span class="text-xs font-bold text-primary uppercase tracking-wider">{{ __('Roadmap') }}</span>
                        </span>
                        <h2 class="text-3xl md:text-4xl font-black text-gray-900 dark:text-white tracking-tighter">{{ __('Our Path to') }} <span class="text-primary italic">{{ __('Launch') }}</span> 🚀</h2>
                        <p class="text-base md:text-lg text-gray-500 dark:text-gray-400 mt-3 font-medium">✨ {{ __('Focused on quality, authenticity, and absolute trust.') }}</p>
                    </div>

                    <div class="flex flex-col gap-10">
                        <!-- Step 1 -->
                        <div class="flex gap-6 md:gap-8 group">
                            <div class="flex flex-col items-center">
                                <div class="size-14 md:size-16 rounded-2xl bg-slate-50 dark:bg-[#271d1c] flex items-center justify-center text-2xl md:text-3xl font-black group-hover:bg-primary group-hover:text-white transition-all duration-300 shadow-sm border border-slate-100 dark:border-[#392b28] z-10 shrink-0 group-hover:scale-110">📝</div>
                                <div class="w-1 md:w-1.5 h-full bg-gradient-to-b from-primary/50 to-transparent my-3 rounded-full opacity-50 group-hover:opacity-100 transition-opacity"></div>
                            </div>
                            <div class="pt-1 pb-4 flex-1">
                                <h4 class="text-xl md:text-2xl font-black text-gray-900 dark:text-white mb-2 tracking-tight">{{ __('Pre-Registration') }}</h4>
                                <p class="inline-block px-3 py-1 rounded-md bg-primary/10 text-primary text-sm md:text-base font-black uppercase tracking-wider mb-3">{{ __('First 15 Days') }}</p>
                                <p class="text-base text-gray-500 dark:text-gray-400 leading-relaxed font-medium">{{ __('Early access for founding members. Secure your premium username and early-bird benefits.') }}</p>
                            </div>
                        </div>
                        
                        <!-- Step 2 -->
                        <div class="flex gap-6 md:gap-8 group">
                            <div class="flex flex-col items-center">
                                <div class="size-14 md:size-16 rounded-2xl bg-slate-50 dark:bg-[#271d1c] flex items-center justify-center text-2xl md:text-3xl font-black group-hover:bg-primary group-hover:text-white transition-all duration-300 shadow-sm border border-slate-100 dark:border-[#392b28] z-10 shrink-0 group-hover:scale-110">🔍</div>
                                <div class="w-1 md:w-1.5 h-full bg-gradient-to-b from-primary/50 to-transparent my-3 rounded-full opacity-50 group-hover:opacity-100 transition-opacity"></div>
                            </div>
                            <div class="pt-1 pb-4 flex-1">
                                <h4 class="text-xl md:text-2xl font-black text-gray-900 dark:text-white mb-2 tracking-tight">{{ __('Verification Phase') }}</h4>
                                <p class="inline-block px-3 py-1 rounded-md bg-primary/10 text-primary text-sm md:text-base font-black uppercase tracking-wider mb-3">{{ __('Next 10 Days') }}</p>
                                <p class="text-base text-gray-500 dark:text-gray-400 leading-relaxed font-medium">{{ __('Our team manually verifies all early profiles to ensure a high-quality, authentic community base.') }}</p>
                            </div>
                        </div>
                        
                        <!-- Step 3 -->
                        <div class="flex gap-6 md:gap-8 group mt-2">
                            <div class="flex flex-col items-center">
                                <div class="size-14 md:size-16 rounded-2xl bg-primary/10 dark:bg-primary/20 border-2 border-primary border-dashed flex items-center justify-center text-2xl md:text-3xl font-black text-primary animate-pulse shadow-xl shadow-primary/20 z-10 shrink-0">🎉</div>
                            </div>
                            <div class="pt-1 flex-1">
                                <h4 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white mb-2 tracking-tight">{{ __('Official Launch') }}</h4>
                                <p class="inline-block px-4 py-1.5 rounded-lg bg-primary text-white text-base md:text-lg font-black uppercase tracking-widest mb-3 shadow-lg shadow-primary/30">{{ $launchDateDisplay }}</p>
                                <p class="text-base text-gray-500 dark:text-gray-400 leading-relaxed font-medium">{{ __('Platform opens globally. Start connecting with verified matches.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Action -->
                <div class="flex justify-center pt-4">
                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="group relative h-14 px-10 rounded-full bg-[#1f2937] dark:bg-white text-white dark:text-[#1f2937] font-bold hover:bg-primary dark:hover:bg-primary hover:text-white dark:hover:text-white transition-all duration-300 inline-flex items-center justify-center gap-3 overflow-hidden shadow-xl hover:shadow-primary/30 hover:-translate-y-1">
                            <span class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-out"></span>
                            <span class="relative z-10 flex items-center gap-2">
                                <i class="fas fa-sign-out-alt text-[20px] transition-transform group-hover:-translate-x-1"></i>
                                <span class="tracking-wide">{{ __('Secure Logout') }}</span>
                            </span>
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
