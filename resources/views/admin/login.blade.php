<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Admin Portal - GoswamiSangath</title>
    <link href="https://fonts.googleapis.com/css2?family=Spline+Sans:wght@300;400;500;600;700&family=Noto+Sans:wght@400;500;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#ec3713",
                        "admin-gold": "#d4af37",
                        "dark-blue": "#0a0e1a",
                    },
                    fontFamily: {
                        "display": ["Spline Sans", "sans-serif"],
                        "body": ["Noto Sans", "sans-serif"],
                    },
                },
            },
        }
    </script>
    <style>
        body {
            background-image: url('{{ asset('images/admin-bg.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh;
        }
        .glass-panel {
            background: rgba(10, 14, 26, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.8);
        }
        .admin-gradient-text {
            background: linear-gradient(135deg, #ffffff 0%, #d4af37 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="flex items-center justify-center p-6 font-body">
    <div class="w-full max-w-md">
        <div class="glass-panel rounded-2xl overflow-hidden p-8 space-y-8 animate-in fade-in zoom-in duration-500">
            <!-- Header -->
            <div class="text-center space-y-2">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-xl bg-primary/10 border border-primary/20 mb-4">
                    <span class="material-symbols-outlined text-primary text-4xl">admin_panel_settings</span>
                </div>
                <h1 class="text-3xl font-display font-bold admin-gradient-text">Admin Portal</h1>
                <p class="text-gray-400 text-sm">Secure authorization required for dashboard access</p>
            </div>

            @if(session('error'))
                <div class="bg-red-500/10 border border-red-500/20 text-red-400 p-4 rounded-lg text-sm flex items-center gap-3">
                    <span class="material-symbols-outlined">error</span>
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="bg-green-500/10 border border-green-500/20 text-green-400 p-4 rounded-lg text-sm flex items-center gap-3">
                    <span class="material-symbols-outlined">check_circle</span>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('admin.login.store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="space-y-4">
                    <!-- Email -->
                    <div class="space-y-2">
                        <label for="email" class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-1">Administrator Email</label>
                        <div class="relative group">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 group-focus-within:text-primary transition-colors">mail</span>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                                class="w-full bg-black/40 border-white/10 rounded-xl pl-10 pr-4 py-3 text-white placeholder-gray-600 focus:border-primary focus:ring-1 focus:ring-primary transition-all outline-none"
                                placeholder="admin@goswamisangath.com">
                        </div>
                        @error('email')
                            <p class="text-red-400 text-xs mt-1 px-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="space-y-2">
                        <div class="flex justify-between items-center px-1">
                            <label for="password" class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Secret Key</label>
                            <a href="{{ route('password.request') }}" class="text-[10px] text-gray-500 hover:text-primary transition-colors">Forgot Access Code?</a>
                        </div>
                        <div class="relative group">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 group-focus-within:text-primary transition-colors">lock</span>
                            <input type="password" id="password" name="password" required
                                class="w-full bg-black/40 border-white/10 rounded-xl pl-10 pr-4 py-3 text-white placeholder-gray-600 focus:border-primary focus:ring-1 focus:ring-primary transition-all outline-none"
                                placeholder="••••••••••••">
                        </div>
                        @error('password')
                            <p class="text-red-400 text-xs mt-1 px-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Remember -->
                <div class="flex items-center">
                    <input type="checkbox" id="remember" name="remember" 
                        class="w-4 h-4 rounded border-white/10 bg-black/40 text-primary focus:ring-offset-black">
                    <label for="remember" class="ml-2 text-sm text-gray-400">Maintain session security</label>
                </div>

                <!-- Submit -->
                <button type="submit" 
                    class="w-full bg-primary hover:bg-primary/90 text-white font-bold py-4 rounded-xl shadow-lg shadow-primary/20 transition-all active:scale-[0.98] flex items-center justify-center gap-2 group">
                    <span>Initialize Authorization</span>
                    <span class="material-symbols-outlined text-sm group-hover:translate-x-1 transition-transform">arrow_forward</span>
                </button>
            </form>

            <!-- Footer -->
            <div class="pt-6 border-t border-white/5 text-center">
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-white transition-colors">
                    <span class="material-symbols-outlined text-sm">person</span>
                    Standard User Login
                </a>
            </div>
        </div>
        
        <p class="text-center text-gray-600 text-[10px] mt-8 uppercase tracking-[0.2em]">
            © {{ date('Y') }} GoswamiSangath Infrastructure. All Rights Reserved.
        </p>
    </div>
</body>
</html>
