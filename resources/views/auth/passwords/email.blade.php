<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Reset Password - {{ $siteSettings->site_name ?? 'GoswamiSangath' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Newsreader:ital,opsz,wght@0,6..72,200..800;1,6..72,200..800&family=Noto+Sans:wght@400;500;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
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
                    },
                    fontFamily: {
                        "display": ["Newsreader", "serif"],
                        "body": ["Noto Sans", "sans-serif"],
                    },
                },
            },
        }
    </script>
</head>
<body class="h-full bg-background-light dark:bg-background-dark text-slate-900 dark:text-white font-body flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl shadow-xl p-8 overflow-hidden relative">
        <div class="absolute top-0 right-0 p-4 opacity-10">
            <span class="material-symbols-outlined text-7xl text-primary">lock_reset</span>
        </div>
        
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-display font-bold italic mb-2">Reset your password</h1>
            <p class="text-slate-500 dark:text-zinc-400 text-sm">Enter your email and we'll send you a link to reset your password.</p>
        </div>

        @if (session('status'))
            <div class="mb-6 p-4 rounded-xl bg-green-500/10 border border-green-500/30 text-green-600 dark:text-green-400 text-sm font-medium">
                {{ session('status') }}
            </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label for="email" class="block text-sm font-semibold mb-2">Email Address</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">mail</span>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="your@email.com" class="w-full pl-10 pr-4 py-3 rounded-xl bg-slate-50 dark:bg-zinc-800 border-slate-200 dark:border-zinc-700 focus:ring-primary focus:border-primary transition-all">
                </div>
                @error('email')
                    <p class="mt-2 text-xs text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full py-4 bg-primary hover:bg-[#d42e0f] text-white rounded-xl font-bold transition-all shadow-lg shadow-primary/20 hover:shadow-primary/40 transform hover:-translate-y-0.5 active:translate-y-0">
                Send Reset Link
            </button>
        </form>

        <div class="mt-8 text-center">
            <a href="{{ route('login') }}" class="text-sm font-medium text-slate-400 hover:text-primary transition-colors flex items-center justify-center gap-1 group">
                <span class="material-symbols-outlined text-sm group-hover:-translate-x-1 transition-transform">arrow_back</span>
                Back to login
            </a>
        </div>
    </div>
</body>
</html>
