@extends('layouts.app')

@section('title', 'Service Unavailable - ' . ($siteSettings->site_name ?? 'GoswamiSangath'))

@section('content')
<div class="min-h-[70vh] flex items-center justify-center px-4 py-20 bg-gray-50 dark:bg-gray-900 transition-colors duration-400">
    <div class="max-w-md w-full text-center slide-up">
        <div class="relative mb-8">
            <h1 class="text-[12rem] font-bold text-gray-200 dark:text-gray-800 leading-none select-none">503</h1>
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="w-24 h-24 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center animate-pulse">
                    <i class="fas fa-tools text-blue-600 dark:text-blue-400 text-4xl"></i>
                </div>
            </div>
        </div>
        
        <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
            {{ __('common.service_unavailable') ?? 'Service Unavailable' }}
        </h2>
        
        <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-sm mx-auto">
            {{ __('common.503_msg') ?? 'We are currently performing maintenance. Please check back soon.' }}
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ url('/') }}" class="w-full sm:w-auto px-8 py-3 bg-primary hover:bg-primary-dark text-white font-semibold rounded-xl shadow-lg shadow-primary/20 transition-all duration-300 transform hover:-translate-y-1">
                <i class="fas fa-home mr-2"></i> {{ __('common.back_to_home') ?? 'Back to Home' }}
            </a>
        </div>
    </div>
</div>

<style>
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .slide-up {
        animation: slideUp 0.6s cubic-bezier(0.19, 1, 0.22, 1) forwards;
    }
</style>
@endsection
