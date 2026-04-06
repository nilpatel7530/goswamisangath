<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Report User</title>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Spline+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <!-- Global Theme System -->
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
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
                        "surface-dark": "#2f1f1c",
                        "card-dark": "#2f1f1c",
                        "card-border": "#392b28",
                        "text-secondary": "#b9a19d"
                    },
                    fontFamily: {
                        "display": ["Spline Sans", "sans-serif"]
                    },
                    borderRadius: {"DEFAULT": "1rem", "lg": "2rem", "xl": "3rem", "full": "9999px"},
                },
            },
        }
    </script>
    <style>
        .material-symbols-outlined.filled {
            font-variation-settings: 'FILL' 1;
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-background-light dark:bg-background-dark text-[#181211] dark:text-white font-display min-h-screen flex flex-col overflow-x-hidden selection:bg-primary selection:text-white">
    <div class="flex flex-1 overflow-hidden">
        @include('partials.user-sidebar')
        
        <main class="flex-1 px-4 py-8 md:py-12 lg:ml-80 overflow-y-auto">
            <div class="mx-auto max-w-[680px]">
                <!-- Page Heading -->
                <div class="mb-8">
                    <div class="flex flex-col gap-2">
                        <h1 class="text-3xl md:text-5xl font-black tracking-tighter text-slate-900 dark:text-white">Report Profile</h1>
                        <p class="text-lg text-slate-600 dark:text-[#b9a19d] font-normal">Help us keep this community safe and respectful.</p>
                    </div>
                </div>
                
                <!-- Target Profile Card -->
                <div class="group relative mb-8 overflow-hidden rounded-2xl bg-white dark:bg-surface-dark border border-gray-200 dark:border-[#392b28] p-6 transition-all hover:border-gray-400 dark:hover:border-[#543f3b]">
                    <div class="flex items-center gap-5">
                        <div class="relative">
                            <div class="size-20 rounded-full border-2 border-primary/20 bg-cover bg-center" style='background-image: url("{{ $reportedUser->profile_image ? asset('storage/' . $reportedUser->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($reportedUser->full_name) . '&size=400&background=ec3713&color=fff' }}");'></div>
                            <div class="absolute bottom-0 right-0 flex size-6 items-center justify-center rounded-full bg-gray-900 dark:bg-[#221310] text-red-500 ring-2 ring-gray-900 dark:ring-[#221310]">
                                <span class="material-symbols-outlined icon-sm">warning</span>
                            </div>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm font-semibold uppercase tracking-wider text-primary">Reporting User</span>
                            <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $reportedUser->full_name }}</p>
                            <p class="text-sm text-slate-600 dark:text-[#b9a19d]">ID: {{ str_pad($reportedUser->id, 7, '0', STR_PAD_LEFT) }}@if($reportedUser->city) • {{ $reportedUser->city }}@endif</p>
                        </div>
                    </div>
                </div>
                
                <!-- Report Form -->
                <form id="reportForm" class="flex flex-col gap-8">
                    <!-- Section 1: Reason -->
                    <div class="space-y-4">
                        <h3 class="flex items-center gap-2 text-xl font-bold text-slate-900 dark:text-white">
                            <span class="flex size-7 items-center justify-center rounded-full bg-gray-100 dark:bg-white/10 text-sm text-slate-900 dark:text-white">1</span>
                            Why are you reporting this user?
                        </h3>
                        <div class="grid gap-3 sm:grid-cols-2">
                            <!-- Option 1 -->
                            <label class="relative flex cursor-pointer rounded-xl border border-gray-300 dark:border-[#392b28] bg-white dark:bg-surface-dark p-4 transition-all hover:border-primary/50 hover:bg-gray-50 dark:hover:bg-white/5 has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                                <input class="peer sr-only" name="reason" type="radio" value="spam_scam"/>
                                <div class="flex w-full items-center gap-3">
                                    <div class="flex size-5 shrink-0 items-center justify-center rounded-full border border-gray-400 dark:border-[#543f3b] peer-checked:border-primary peer-checked:bg-primary">
                                        <div class="size-2 rounded-full bg-white opacity-0 peer-checked:opacity-100"></div>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-900 dark:text-white">Spam or Scam</span>
                                        <span class="text-xs text-slate-600 dark:text-[#b9a19d]">Fake profile, advertising</span>
                                    </div>
                                </div>
                                <div class="absolute right-4 top-4 text-primary opacity-0 peer-checked:opacity-100">
                                    <span class="material-symbols-outlined">check_circle</span>
                                </div>
                            </label>
                            
                            <!-- Option 2 -->
                            <label class="relative flex cursor-pointer rounded-xl border border-gray-300 dark:border-[#392b28] bg-white dark:bg-surface-dark p-4 transition-all hover:border-primary/50 hover:bg-gray-50 dark:hover:bg-white/5 has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                                <input class="peer sr-only" name="reason" type="radio" value="harassment"/>
                                <div class="flex w-full items-center gap-3">
                                    <div class="flex size-5 shrink-0 items-center justify-center rounded-full border border-gray-400 dark:border-[#543f3b] peer-checked:border-primary peer-checked:bg-primary">
                                        <div class="size-2 rounded-full bg-white opacity-0 peer-checked:opacity-100"></div>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-900 dark:text-white">Harassment</span>
                                        <span class="text-xs text-slate-600 dark:text-[#b9a19d]">Abusive messages, threats</span>
                                    </div>
                                </div>
                                <div class="absolute right-4 top-4 text-primary opacity-0 peer-checked:opacity-100">
                                    <span class="material-symbols-outlined">check_circle</span>
                                </div>
                            </label>
                            
                            <!-- Option 3 -->
                            <label class="relative flex cursor-pointer rounded-xl border border-gray-300 dark:border-[#392b28] bg-white dark:bg-surface-dark p-4 transition-all hover:border-primary/50 hover:bg-gray-50 dark:hover:bg-white/5 has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                                <input class="peer sr-only" name="reason" type="radio" value="inappropriate_photos"/>
                                <div class="flex w-full items-center gap-3">
                                    <div class="flex size-5 shrink-0 items-center justify-center rounded-full border border-gray-400 dark:border-[#543f3b] peer-checked:border-primary peer-checked:bg-primary">
                                        <div class="size-2 rounded-full bg-white opacity-0 peer-checked:opacity-100"></div>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-900 dark:text-white">Inappropriate Photos</span>
                                        <span class="text-xs text-slate-600 dark:text-[#b9a19d]">Nudity, graphic content</span>
                                    </div>
                                </div>
                                <div class="absolute right-4 top-4 text-primary opacity-0 peer-checked:opacity-100">
                                    <span class="material-symbols-outlined">check_circle</span>
                                </div>
                            </label>
                            
                            <!-- Option 4 -->
                            <label class="relative flex cursor-pointer rounded-xl border border-gray-300 dark:border-[#392b28] bg-white dark:bg-surface-dark p-4 transition-all hover:border-primary/50 hover:bg-gray-50 dark:hover:bg-white/5 has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                                <input class="peer sr-only" name="reason" type="radio" value="underage"/>
                                <div class="flex w-full items-center gap-3">
                                    <div class="flex size-5 shrink-0 items-center justify-center rounded-full border border-gray-400 dark:border-[#543f3b] peer-checked:border-primary peer-checked:bg-primary">
                                        <div class="size-2 rounded-full bg-white opacity-0 peer-checked:opacity-100"></div>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-900 dark:text-white">Underage Profile</span>
                                        <span class="text-xs text-slate-600 dark:text-[#b9a19d]">User appears under 18</span>
                                    </div>
                                </div>
                                <div class="absolute right-4 top-4 text-primary opacity-0 peer-checked:opacity-100">
                                    <span class="material-symbols-outlined">check_circle</span>
                                </div>
                            </label>
                            
                            <!-- Option 5 -->
                            <label class="relative flex cursor-pointer rounded-xl border border-gray-300 dark:border-[#392b28] bg-white dark:bg-surface-dark p-4 transition-all hover:border-primary/50 hover:bg-gray-50 dark:hover:bg-white/5 has-[:checked]:border-primary has-[:checked]:bg-primary/5 sm:col-span-2">
                                <input class="peer sr-only" name="reason" type="radio" value="other"/>
                                <div class="flex w-full items-center gap-3">
                                    <div class="flex size-5 shrink-0 items-center justify-center rounded-full border border-gray-400 dark:border-[#543f3b] peer-checked:border-primary peer-checked:bg-primary">
                                        <div class="size-2 rounded-full bg-white opacity-0 peer-checked:opacity-100"></div>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-900 dark:text-white">Other Reason</span>
                                        <span class="text-xs text-slate-600 dark:text-[#b9a19d]">Doesn't fit above categories</span>
                                    </div>
                                </div>
                                <div class="absolute right-4 top-4 text-primary opacity-0 peer-checked:opacity-100">
                                    <span class="material-symbols-outlined">check_circle</span>
                                </div>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Section 2: Details -->
                    <div class="space-y-4">
                        <h3 class="flex items-center gap-2 text-xl font-bold text-slate-900 dark:text-white">
                            <span class="flex size-7 items-center justify-center rounded-full bg-gray-100 dark:bg-white/10 text-sm text-slate-900 dark:text-white">2</span>
                            Tell us more details
                        </h3>
                        <div class="relative">
                            <textarea id="detailsInput" name="details" class="w-full resize-none rounded-2xl border border-gray-300 dark:border-[#392b28] bg-white dark:bg-surface-dark p-4 text-slate-900 dark:text-white placeholder:text-slate-400 dark:placeholder:text-[#543f3b] focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none" placeholder="Please provide specific details to help us investigate (e.g. time of incident, specific messages)..." rows="5" maxlength="500"></textarea>
                            <span class="absolute bottom-4 right-4 text-xs font-medium text-slate-500 dark:text-[#543f3b]"><span id="charCount">0</span>/500</span>
                        </div>
                    </div>
                    
                    <!-- Section 3: Block -->
                    <div class="rounded-2xl border border-gray-300 dark:border-[#392b28] bg-white dark:bg-surface-dark p-5">
                        <label class="flex cursor-pointer items-center justify-between gap-4">
                            <div class="flex items-start gap-4">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gray-900 dark:bg-[#221310] text-primary">
                                    <span class="material-symbols-outlined">block</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-base font-bold text-slate-900 dark:text-white">Block this user?</span>
                                    <span class="text-sm text-slate-600 dark:text-[#b9a19d]">They won't be able to find your profile or message you.</span>
                                </div>
                            </div>
                            <div class="relative inline-flex items-center">
                                <input id="blockUser" name="block_user" class="peer sr-only" type="checkbox" value="1"/>
                                <div class="h-7 w-12 rounded-full bg-gray-300 dark:bg-[#392b28] after:absolute after:start-[2px] after:top-[2px] after:h-6 after:w-6 after:rounded-full after:bg-gray-500 dark:after:bg-[#b9a19d] after:transition-all after:content-[''] peer-checked:bg-primary peer-checked:after:translate-x-full peer-checked:after:bg-white peer-focus:outline-none"></div>
                            </div>
                        </label>
                    </div>
                    
                    <!-- Footer & Actions -->
                    <div class="mt-4 flex flex-col gap-6">
                        <div class="flex items-start gap-3 rounded-lg bg-primary/10 p-4 text-primary">
                            <span class="material-symbols-outlined shrink-0 icon-lg">info</span>
                            <p class="text-sm font-medium leading-relaxed">
                                Your report is strictly confidential and anonymous. The user will not be notified that you reported them. Our team reviews all reports within 24 hours.
                            </p>
                        </div>
                        <div class="flex flex-col-reverse gap-4 sm:flex-row sm:justify-end">
                            <a href="{{ route('profile.view', $reportedUser) }}" class="group flex h-12 items-center justify-center rounded-full border border-transparent px-8 text-base font-bold text-slate-600 dark:text-[#b9a19d] transition-colors hover:bg-gray-100 dark:hover:bg-white/5 hover:text-slate-900 dark:hover:text-white">
                                Cancel
                            </a>
                            <button type="submit" class="group flex h-12 items-center justify-center gap-2 rounded-full bg-primary px-10 text-base font-bold text-white shadow-[0_0_20px_-5px_#ec3713] transition-all hover:bg-[#ff421a] hover:shadow-[0_0_25px_-5px_#ec3713] active:scale-95">
                                <span class="material-symbols-outlined icon-md">send</span>
                                Submit Report
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        const detailsInput = document.getElementById('detailsInput');
        const charCount = document.getElementById('charCount');
        const reportForm = document.getElementById('reportForm');
        
        // Character counter
        if (detailsInput && charCount) {
            detailsInput.addEventListener('input', function() {
                charCount.textContent = this.value.length;
            });
        }
        
        // Form submission
        if (reportForm) {
            reportForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const reason = formData.get('reason');
                
                if (!reason) {
                    alert('Please select a reason for reporting.');
                    return;
                }
                
                const data = {
                    reason: reason,
                    details: formData.get('details') || '',
                    block_user: formData.get('block_user') === '1',
                };
                
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="material-symbols-outlined icon-md animate-spin">hourglass_empty</span> Submitting...';
                
                fetch(`{{ route('report.store', $reportedUser->id) }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        window.location.href = '{{ route('profile.view', $reportedUser) }}';
                    } else {
                        alert(data.error || 'Failed to submit report. Please try again.');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                });
            });
        }
    </script>
    
    <!-- Global Theme Manager -->
    <!-- Global Theme Manager -->
    <script src="{{ asset('js/theme.js') }}"></script>
</body>
</html>

