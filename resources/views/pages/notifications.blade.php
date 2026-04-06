<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title data-translate="Notifications Center - GoswamiSangath">{{ __('Notifications Center - GoswamiSangath') }}</title>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Spline+Sans:wght@300;400;500;600;700&family=Noto+Sans:wght@400;500;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#ec3713",
                        "primary-hover": "#c92e10",
                        "background-light": "#f8f6f6",
                        "background-dark": "#221310",
                        "surface-dark": "#2f1a16",
                        "surface-light": "#ffffff",
                        "border-dark": "#482923",
                    },
                    fontFamily: {
                        "display": ["Spline Sans", "sans-serif"],
                        "body": ["Noto Sans", "sans-serif"],
                    },
                    borderRadius: {"DEFAULT": "1rem", "lg": "2rem", "xl": "3rem", "full": "9999px"},
                },
            },
        }
    </script>
    <!-- Global Theme System -->
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    <style>
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f8f6f6;
        }
        .dark ::-webkit-scrollbar-track {
            background: #221310;
        }
        ::-webkit-scrollbar-thumb {
            background: #e5e7eb;
            border-radius: 4px;
        }
        .dark ::-webkit-scrollbar-thumb {
            background: #482923;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #ec3713;
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark font-display min-h-screen flex flex-col overflow-x-hidden text-slate-900 dark:text-white selection:bg-primary selection:text-white">
    @include('partials.user-sidebar')
    
    <!-- Main Content Layout -->
    <main class="flex-1 lg:ml-80 w-full px-4 md:px-10 py-8">
        <div class="max-w-[1176px] mx-auto grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8">
            <!-- Left Column: Notification Feed -->
            <div class="lg:col-span-8 flex flex-col gap-6 order-2 lg:order-1">
                <!-- Page Header -->
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <h1 class="text-slate-900 dark:text-white text-4xl font-black leading-tight tracking-[-0.033em]" data-translate="Notifications">{{ __('Notifications') }}</h1>
                    <button onclick="markAllAsRead()" class="group flex items-center gap-2 cursor-pointer text-sm font-bold text-primary hover:text-primary-hover transition-colors">
                        <span class="material-symbols-outlined icon-md">done_all</span>
                        <span data-translate="Mark all as read">{{ __('Mark all as read') }}</span>
                    </button>
                </div>
                
                <!-- Feed List -->
                <div id="notificationsList" class="flex flex-col gap-4">
                    @forelse($notifications as $notification)
                        <div class="notification-item group relative flex flex-col md:flex-row md:items-center gap-4 {{ $notification->is_read ? 'bg-transparent border-b border-gray-100 dark:border-border-dark/50 opacity-80 hover:opacity-100' : 'bg-white dark:bg-surface-dark border-l-4 border-primary' }} p-5 rounded-r-2xl shadow-sm hover:shadow-md transition-all" data-notification-id="{{ $notification->id }}" data-type="{{ $notification->type }}">
                            <div class="flex items-center gap-4 flex-1">
                                @if($notification->relatedUser)
                                <a href="{{ route('profile.view', $notification->relatedUser) }}" class="relative shrink-0">
                                    @if($notification->relatedUser->profile_image)
                                        <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full h-14 w-14 ring-2 ring-primary/20 group-hover:ring-primary transition-all" style="background-image: url('{{ asset('storage/' . $notification->relatedUser->profile_image) }}');"></div>
                                    @else
                                        <div class="bg-gray-200 dark:bg-[#3a221d] rounded-full h-14 w-14 flex items-center justify-center">
                                            <span class="material-symbols-outlined text-gray-400 dark:text-gray-600">person</span>
                                        </div>
                                    @endif
                                @else
                                <div class="relative shrink-0">
                                    <div class="bg-gray-200 dark:bg-[#3a221d] rounded-full h-14 w-14 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-gray-400 dark:text-gray-600">person</span>
                                    </div>
                                @endif
                                    @if($notification->icon)
                                        @php
                                            $iconColorClass = match($notification->icon_color) {
                                                'primary' => 'bg-primary',
                                                'blue-500' => 'bg-blue-500',
                                                'green-500' => 'bg-green-500',
                                                'gray-500' => 'bg-gray-500',
                                                default => 'bg-primary',
                                            };
                                        @endphp
                                        <div class="absolute -bottom-1 -right-1 {{ $iconColorClass }} text-white rounded-full p-1 border-2 border-white dark:border-surface-dark">
                                            <span class="material-symbols-outlined icon-xs font-bold block">{{ $notification->icon }}</span>
                                        </div>
                                    @endif
                                @if($notification->relatedUser)
                                </a>
                                @else
                                </div>
                                @endif
                                <div class="flex flex-col justify-center">
                                    <p class="text-slate-900 dark:text-white text-base font-bold leading-normal">{{ $notification->message }}</p>
                                    <p class="text-slate-500 dark:text-[#c99b92] text-sm font-normal leading-normal">
                                        {{ $notification->created_at->diffForHumans() }}
                                        @if(!$notification->is_read)
                                            • <span class="text-primary font-medium" data-translate="Unread">{{ __('Unread') }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 shrink-0 ml-16 md:ml-0">
                                @if($notification->type === 'interest' && !$notification->is_read)
                                    <a href="{{ route('requests') }}" class="flex-1 md:flex-none h-9 px-6 bg-primary hover:bg-primary-hover text-white text-sm font-bold rounded-full transition-transform active:scale-95 shadow-lg shadow-primary/20">
                                        <span data-translate="View Request">{{ __('View Request') }}</span>
                                    </a>
                                @elseif($notification->type === 'message')
                                    <a href="{{ route('messages') }}" class="flex-1 md:flex-none h-9 px-6 bg-primary hover:bg-primary-hover text-white text-sm font-bold rounded-full transition-transform active:scale-95 shadow-lg shadow-primary/20 flex items-center justify-center gap-2">
                                        <span class="material-symbols-outlined icon-base">chat_bubble</span>
                                        <span data-translate="View Message">{{ __('View Message') }}</span>
                                    </a>
                                @elseif($notification->type === 'match' || $notification->type === 'interest_accepted')
                                    @if($notification->relatedUser)
                                        <a href="{{ route('profile.view', $notification->relatedUser) }}" class="flex-1 md:flex-none h-9 px-6 bg-slate-900 dark:bg-[#482923] hover:bg-slate-700 dark:hover:bg-[#5e352d] text-white text-sm font-medium rounded-full transition-colors border border-transparent hover:border-primary">
                                            <span data-translate="View Profile">{{ __('View Profile') }}</span>
                                        </a>
                                    @endif
                                @endif
                                <button onclick="deleteNotification({{ $notification->id }})" class="size-9 flex items-center justify-center rounded-full bg-gray-100 dark:bg-[#3a221d] text-slate-600 dark:text-white hover:bg-gray-200 dark:hover:bg-[#4d2d26] transition-colors">
                                    <span class="material-symbols-outlined icon-md">close</span>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center py-20 text-center">
                            <div class="bg-surface-dark rounded-full p-8 mb-6">
                                <span class="material-symbols-outlined text-6xl text-primary/50">notifications_off</span>
                            </div>
                            <h3 class="text-white text-xl font-bold mb-2" data-translate="No new updates">{{ __('No new updates') }}</h3>
                            <p class="text-[#c99b92]" data-translate="We'll notify you when something exciting happens.">{{ __('We\'ll notify you when something exciting happens.') }}</p>
                        </div>
                    @endforelse
                </div>
                
                <!-- Pagination -->
                @if($notifications->hasPages())
                    <div class="mt-6">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>
            
            <!-- Right Column: Preferences (Sticky) -->
            <div class="lg:col-span-4 order-1 lg:order-2 min-w-0">
                <div class="sticky top-24 space-y-6">
                    <!-- Promo Card -->
                    <div class="bg-gradient-to-br from-primary to-[#ff6b4a] rounded-3xl p-6 text-white relative overflow-hidden">
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/20 rounded-full blur-2xl"></div>
                        <h4 class="text-lg font-bold mb-2 relative z-10" data-translate="Go Premium">{{ __('Go Premium') }}</h4>
                        <p class="text-white/90 text-sm mb-4 relative z-10" data-translate="See exactly who liked you without waiting.">{{ __('See exactly who liked you without waiting.') }}</p>
                        <a href="{{ route('membership') }}" class="block w-full bg-white text-primary text-sm font-bold py-3 rounded-xl hover:bg-gray-100 transition-colors relative z-10 text-center" data-translate="Upgrade Now">{{ __('Upgrade Now') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <script>
        let currentFilter = '{{ $filter }}';
        let lastNotificationId = {{ $notifications->isNotEmpty() ? $notifications->first()->id : 0 }};
        let pollingInterval;
        
        // Filter notifications
        function filterNotifications(filter) {
            currentFilter = filter;
            window.location.href = '/notifications?filter=' + filter;
        }
        
        // Mark notification as read
        function markAsRead(id) {
            fetch(`/notifications/${id}/mark-read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const item = document.querySelector(`[data-notification-id="${id}"]`);
                    if (item) {
                        item.classList.remove('bg-white', 'dark:bg-surface-dark', 'border-l-4', 'border-primary');
                        item.classList.add('bg-transparent', 'border-b', 'border-gray-100', 'dark:border-border-dark/50', 'opacity-80');
                        const unreadBadge = item.querySelector('.text-primary');
                        if (unreadBadge) {
                            unreadBadge.remove();
                        }
                    }
                }
            });
        }
        
        // Delete notification
        function deleteNotification(id) {
            fetch(`/notifications/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const item = document.querySelector(`[data-notification-id="${id}"]`);
                    if (item) {
                        // Add fade out animation
                        item.style.transition = 'opacity 0.3s ease-out, transform 0.3s ease-out';
                        item.style.opacity = '0';
                        item.style.transform = 'translateX(-20px)';
                        
                        // Remove from DOM after animation
                        setTimeout(() => {
                            item.remove();
                            
                            // Check if list is empty and show empty state
                            const list = document.getElementById('notificationsList');
                            if (list && list.children.length === 0) {
                                location.reload();
                            }
                        }, 300);
                    }
                }
            })
            .catch(error => {
                console.error('Error deleting notification:', error);
            });
        }
        
        // Mark all as read
        function markAllAsRead() {
            fetch('/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }
        
        // Real-time polling for new notifications
        function pollNotifications() {
            fetch(`/notifications/get?filter=${currentFilter}&last_notification_id=${lastNotificationId}`, {
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.notifications && data.notifications.length > 0) {
                    // Update last notification ID
                    lastNotificationId = Math.max(...data.notifications.map(n => n.id));
                    
                    // Prepend new notifications to the list
                    const list = document.getElementById('notificationsList');
                    data.notifications.forEach(notification => {
                        const item = createNotificationItem(notification);
                        list.insertBefore(item, list.firstChild);
                    });
                }
            })
            .catch(error => console.error('Error polling notifications:', error));
        }
        
        // Create notification item HTML
        function createNotificationItem(notification) {
            const div = document.createElement('div');
            div.className = `notification-item group relative flex flex-col md:flex-row md:items-center gap-4 bg-white dark:bg-surface-dark border-l-4 border-primary p-5 rounded-r-2xl shadow-sm hover:shadow-md transition-all`;
            div.setAttribute('data-notification-id', notification.id);
            div.setAttribute('data-type', notification.type);
            
            const timeAgo = new Date(notification.created_at).toLocaleString();
            const profileImage = notification.related_user?.profile_image 
                ? `{{ asset('storage/') }}/${notification.related_user.profile_image}`
                : '';
            
            // Map icon colors to Tailwind classes
            const iconColorMap = {
                'primary': 'bg-primary',
                'blue-500': 'bg-blue-500',
                'green-500': 'bg-green-500',
                'gray-500': 'bg-gray-500',
            };
            const iconColorClass = iconColorMap[notification.icon_color] || 'bg-primary';
            
            const profileLink = notification.related_user && notification.related_user.id 
                ? `/profile/${notification.related_user.id}` 
                : '#';
            
            div.innerHTML = `
                <div class="flex items-center gap-4 flex-1">
                    ${notification.related_user && notification.related_user.id 
                        ? `<a href="${profileLink}" class="relative shrink-0">` 
                        : `<div class="relative shrink-0">`}
                        ${profileImage ? `<div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full h-14 w-14 ring-2 ring-primary/20 group-hover:ring-primary transition-all" style="background-image: url('${profileImage}');"></div>` : `<div class="bg-gray-200 dark:bg-[#3a221d] rounded-full h-14 w-14 flex items-center justify-center"><span class="material-symbols-outlined text-gray-400 dark:text-gray-600">person</span></div>`}
                        ${notification.icon ? `<div class="absolute -bottom-1 -right-1 ${iconColorClass} text-white rounded-full p-1 border-2 border-white dark:border-surface-dark"><span class="material-symbols-outlined icon-xs font-bold block">${notification.icon}</span></div>` : ''}
                    ${notification.related_user && notification.related_user.id ? '</a>' : '</div>'}
                    <div class="flex flex-col justify-center">
                        <p class="text-slate-900 dark:text-white text-base font-bold leading-normal">${notification.message}</p>
                        <p class="text-slate-500 dark:text-[#c99b92] text-sm font-normal leading-normal">${timeAgo} • <span class="text-primary font-medium">Unread</span></p>
                    </div>
                </div>
                <div class="flex items-center gap-3 shrink-0 ml-16 md:ml-0">
                    ${notification.type === 'interest' && !notification.is_read 
                        ? `<a href="/requests" class="flex-1 md:flex-none h-9 px-6 bg-primary hover:bg-primary-hover text-white text-sm font-bold rounded-full transition-transform active:scale-95 shadow-lg shadow-primary/20">View Request</a>` 
                        : ''}
                    ${notification.type === 'message' 
                        ? `<a href="/messages" class="flex-1 md:flex-none h-9 px-6 bg-primary hover:bg-primary-hover text-white text-sm font-bold rounded-full transition-transform active:scale-95 shadow-lg shadow-primary/20 flex items-center justify-center gap-2"><span class="material-symbols-outlined icon-base">chat_bubble</span><span>View Message</span></a>` 
                        : ''}
                    ${(notification.type === 'match' || notification.type === 'interest_accepted') && notification.related_user && notification.related_user.id 
                        ? `<a href="/profile/${notification.related_user.id}" class="flex-1 md:flex-none h-9 px-6 bg-slate-900 dark:bg-[#482923] hover:bg-slate-700 dark:hover:bg-[#5e352d] text-white text-sm font-medium rounded-full transition-colors border border-transparent hover:border-primary">View Profile</a>` 
                        : ''}
                    <button onclick="deleteNotification(${notification.id})" class="size-9 flex items-center justify-center rounded-full bg-gray-100 dark:bg-[#3a221d] text-slate-600 dark:text-white hover:bg-gray-200 dark:hover:bg-[#4d2d26] transition-colors">
                        <span class="material-symbols-outlined icon-md">close</span>
                    </button>
                </div>
            `;
            
            return div;
        }
        
        // Start polling every 5 seconds
        pollingInterval = setInterval(pollNotifications, 5000);
        
        // Cleanup on page unload
        window.addEventListener('beforeunload', () => {
            if (pollingInterval) {
                clearInterval(pollingInterval);
            }
        });
    </script>
    
    <!-- Global Theme Manager -->
    <script src="{{ asset('js/theme.js') }}"></script>
    <!-- Language Switcher -->
    <script src="{{ asset('js/language-switcher.js') }}"></script>
</body>
</html>

