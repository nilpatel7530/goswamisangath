<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title data-translate="Internal Chat System - GoswamiSangath Connect">{{ __('Internal Chat System - GoswamiSangath Connect') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#EC3713",
                        "background-dark": "#221310",
                        "surface-dark": "#3B1F1C",
                        "border-dark": "#4C2B27",
                        "chat-bg": "#221310",
                        "sidebar-bg": "#2A1815",
                        "right-sidebar-bg": "#2A1815",
                        "text-primary-light": "#F8F1E7",
                        "text-secondary": "#A38D88",
                        "status-indicator": "#C67645",
                        "chat-bubble-other": "#3B1F1C",
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                    borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "2xl": "1rem", "full": "9999px"},
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
            background: #392b28;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #ec3713;
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-slate-900 dark:text-text-primary-light overflow-hidden h-screen flex flex-col">
    <div class="flex flex-1 overflow-hidden">
        @include('partials.user-sidebar')
        
        <main class="flex-1 flex overflow-hidden relative lg:ml-80">
            <!-- Mobile Menu Button -->
            <button id="mobileMenuToggle" class="lg:hidden fixed top-4 left-4 z-50 bg-white dark:bg-sidebar-bg text-slate-900 dark:text-text-primary-light p-3 rounded-full shadow-lg border border-gray-200 dark:border-border-dark" onclick="toggleMobileMenu()">
                <span class="material-symbols-outlined">menu</span>
            </button>
            
            <!-- Mobile Connections Overlay -->
            <div id="mobileConnectionsOverlay" class="lg:hidden fixed inset-0 bg-black/50 z-40 hidden" onclick="closeMobileMenu()"></div>
            
            <aside id="connectionsSidebar" class="w-80 flex-none bg-white dark:bg-sidebar-bg border-r border-gray-200 dark:border-border-dark flex flex-col fixed lg:relative inset-y-0 left-0 z-40 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
                <div class="p-5 border-b border-gray-200 dark:border-border-dark lg:border-b-0">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-bold text-slate-900 dark:text-text-primary-light" data-translate="Matches">{{ __('Matches') }}</h3>
                        <button class="lg:hidden text-slate-500 dark:text-text-secondary hover:text-slate-900 dark:hover:text-text-primary-light" onclick="closeMobileMenu()">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-2.5 text-slate-500 dark:text-text-secondary icon-md">search</span>
                        <input id="searchMatches" class="w-full bg-gray-50 dark:bg-surface-dark text-slate-900 dark:text-text-primary-light text-sm rounded-lg pl-10 pr-4 py-2.5 border border-gray-200 dark:border-border-dark focus:ring-1 focus:ring-primary focus:border-primary placeholder-slate-400 dark:placeholder-text-secondary" placeholder="{{ __('Search matches...') }}" data-translate-placeholder="Search matches..." type="text"/>
                    </div>
                </div>
                <div class="flex-1 overflow-y-auto" id="connectionsList">
                    @foreach($connections as $connection)
                    <div class="connection-item flex items-center gap-3 p-4 cursor-pointer hover:bg-gray-100 dark:hover:bg-surface-dark/50 transition-colors border-l-2 border-transparent {{ $selectedUser && $selectedUser->id == $connection->id ? 'bg-gray-100 dark:bg-surface-dark/40 border-primary' : '' }}" data-user-id="{{ $connection->id }}" data-encrypted-id="{{ encrypt($connection->id) }}" onclick="selectUser('{{ encrypt($connection->id) }}')">
                        <a href="{{ route('profile.view', $connection->id) }}" onclick="event.stopPropagation();" class="relative">
                            <div class="bg-center bg-no-repeat bg-cover rounded-full size-12 {{ $connection->unread_count > 0 ? '' : 'grayscale-[50%]' }}" style='background-image: url("{{ $connection->profile_image ? asset('storage/' . $connection->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($connection->full_name) . '&background=ec3713&color=fff' }}");'></div>
                            @if($connection->unread_count > 0)
                            <div class="absolute bottom-0 right-0 size-3 bg-status-indicator rounded-full border-2 border-white dark:border-background-dark"></div>
                            @endif
                        </a>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-baseline mb-1">
                                <h4 class="text-slate-900 dark:text-text-primary-light font-bold text-sm truncate">
                                    <a href="{{ route('profile.view', $connection->id) }}" onclick="event.stopPropagation();" class="hover:text-primary transition-colors">{{ $connection->full_name }}</a>
                                </h4>
                                <span class="text-xs text-slate-500 dark:text-text-secondary last-message-time" data-user-id="{{ $connection->id }}">
                                    @if($connection->last_message)
                                        {{ \Carbon\Carbon::parse($connection->last_message->created_at)->diffForHumans() }}
                                    @endif
                                </span>
                            </div>
                            <p class="text-slate-600 dark:text-text-secondary text-xs truncate last-message-preview" data-user-id="{{ $connection->id }}">
                                @if($connection->last_message)
                                    {{ Str::limit($connection->last_message->message, 50) }}
                                @else
                                    <span data-translate="Start a conversation...">{{ __('Start a conversation...') }}</span>
                                @endif
                            </p>
                            @if($connection->unread_count > 0)
                            <span class="inline-block mt-1 bg-primary text-white text-[10px] font-bold px-2 py-0.5 rounded-full unread-badge" data-user-id="{{ $connection->id }}">{{ $connection->unread_count }}</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </aside>
            
            <section class="flex-1 flex flex-col bg-gray-50 dark:bg-chat-bg min-w-0 relative" id="chatSection" style="display: {{ $selectedUser ? 'flex' : 'none' }};">
                <div class="h-16 border-b border-gray-200 dark:border-border-dark flex items-center justify-between px-6 bg-white/95 dark:bg-chat-bg/95 backdrop-blur z-10" id="chatHeader">
                    <div class="flex items-center gap-3">
                        <button class="lg:hidden text-slate-500 dark:text-text-secondary hover:text-slate-900 dark:hover:text-text-primary-light mr-2" onclick="closeChat()">
                            <span class="material-symbols-outlined">arrow_back</span>
                        </button>
                        <a href="#" id="chatUserProfileLink" onclick="event.preventDefault(); if(currentUserId) { const userId = atob(currentUserId.split('.')[0]); window.location.href = '/profile/' + userId; }" class="relative">
                            <div class="bg-center bg-no-repeat bg-cover rounded-full size-10" id="chatUserImage" style='background-image: url("");'></div>
                            <div class="absolute bottom-0 right-0 size-2.5 bg-status-indicator rounded-full border-2 border-white dark:border-background-dark"></div>
                        </a>
                        <div>
                            <h3 class="text-slate-900 dark:text-text-primary-light font-bold text-base leading-tight">
                                <a href="#" id="chatUserNameLink" onclick="event.preventDefault(); if(currentUserId) { const userId = atob(currentUserId.split('.')[0]); window.location.href = '/profile/' + userId; }" class="hover:text-primary transition-colors">
                                    <span id="chatUserName"></span>
                                </a>
                            </h3>
                            <p class="text-primary text-xs font-medium flex items-center gap-1" id="chatUserStatus">
                                <span data-translate="Online">{{ __('Online') }}</span> 
                                <span class="size-1 rounded-full bg-primary inline-block"></span>
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <div id="chatVerificationBadge" class="hidden items-center gap-1 bg-gray-100 dark:bg-surface-dark px-3 py-1.5 rounded-full border border-gray-200 dark:border-border-dark">
                            <span class="material-symbols-outlined text-status-indicator icon-sm">verified_user</span>
                            <span class="text-xs font-medium text-slate-600 dark:text-text-secondary" data-translate="Identity Verified">{{ __('Identity Verified') }}</span>
                        </div>
                        <button class="text-slate-500 dark:text-text-secondary hover:text-slate-900 dark:hover:text-text-primary-light p-2 rounded-full hover:bg-gray-100 dark:hover:bg-surface-dark transition-colors">
                            <span class="material-symbols-outlined">more_vert</span>
                        </button>
                    </div>
                </div>
                
                <div class="flex-1 overflow-y-auto p-4 sm:p-6 flex flex-col gap-6" id="chat-container">
                    <div class="flex justify-center my-4">
                        <p class="bg-gray-100 dark:bg-surface-dark/50 border border-gray-200 dark:border-border-dark text-slate-600 dark:text-text-secondary text-xs py-1.5 px-4 rounded-full flex items-center gap-1.5 shadow-sm">
                            <span class="material-symbols-outlined icon-xs">lock</span>
                            <span data-translate="Messages are end-to-end encrypted for your privacy.">{{ __('Messages are end-to-end encrypted for your privacy.') }}</span>
                        </p>
                    </div>
                </div>
                
                <div class="p-4 sm:p-5 bg-white dark:bg-chat-bg border-t border-gray-200 dark:border-border-dark">
                    <div class="flex gap-2 items-center max-w-4xl mx-auto">
                        <div class="flex-1 bg-gray-50 dark:bg-surface-dark rounded-xl border border-gray-200 dark:border-border-dark focus-within:ring-1 focus-within:ring-primary focus-within:border-primary transition-all flex items-center">
                            <button class="flex-none p-2 ml-1 text-slate-500 dark:text-text-secondary hover:text-slate-900 dark:hover:text-text-primary-light rounded-full hover:bg-gray-100 dark:hover:bg-text-primary-light/5 transition-colors" title="Attach file">
                                <span class="material-symbols-outlined">attach_file</span>
                            </button>
                            <textarea id="messageInput" class="w-full bg-transparent text-slate-900 dark:text-text-primary-light border-none focus:ring-0 resize-none py-3 px-2 max-h-32 placeholder-slate-400 dark:placeholder-text-secondary text-sm sm:text-base flex items-center" placeholder="{{ __('Type a message...') }}" data-translate-placeholder="Type a message..." rows="1" style="min-height: 48px;"></textarea>
                            <button class="p-2 mr-1 text-slate-500 dark:text-text-secondary hover:text-slate-900 dark:hover:text-text-primary-light rounded-lg hover:bg-gray-100 dark:hover:bg-text-primary-light/5 transition-colors" title="Add emoji">
                                <span class="material-symbols-outlined icon-md">mood</span>
                            </button>
                        </div>
                        <button id="sendButton" class="flex-none size-11 bg-primary hover:bg-[#D43110] text-white rounded-xl shadow-lg shadow-primary/25 flex items-center justify-center transition-all active:scale-95 group" onclick="sendMessage()">
                            <span class="material-symbols-outlined group-hover:translate-x-0.5 transition-transform">send</span>
                        </button>
                    </div>
                </div>
            </section>
            
            <aside class="w-80 bg-white dark:bg-right-sidebar-bg border-l border-gray-200 dark:border-border-dark hidden xl:flex flex-col overflow-y-auto" id="userInfoSidebar" style="display: {{ $selectedUser ? 'flex' : 'none' }};">
                <div class="p-6 flex flex-col items-center border-b border-gray-200 dark:border-border-dark pb-8">
                    <a href="#" id="userInfoImageLink" onclick="event.preventDefault(); viewFullProfile();" class="relative mb-4 group cursor-pointer">
                        <div class="bg-center bg-no-repeat bg-cover rounded-full size-32 border-4 border-gray-200 dark:border-surface-dark shadow-xl bg-gray-100 dark:bg-surface-dark" id="userInfoImage" style='background-image: url("");'></div>
                        <div class="absolute inset-0 bg-black/40 rounded-full opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <span class="text-white text-xs font-bold" data-translate="View Profile">{{ __('View Profile') }}</span>
                        </div>
                    </a>
                    <h2 class="text-xl font-bold text-slate-900 dark:text-text-primary-light text-center">
                        <a href="#" id="userInfoNameLink" onclick="event.preventDefault(); viewFullProfile();" class="hover:text-primary transition-colors">
                            <span id="userInfoName"></span>
                        </a>
                    </h2>
                    <p class="text-slate-600 dark:text-text-secondary text-sm text-center mt-1" id="userInfoDetails"></p>
                    <div class="mt-4 flex gap-2 w-full justify-center">
                        <span class="inline-flex items-center gap-1 px-4 py-1.5 rounded-full bg-primary/20 text-primary text-xs font-bold uppercase tracking-wider border border-primary/30">
                            <span class="material-symbols-outlined icon-xs fill-current">favorite</span>
                            <span data-translate="Mutual Interest">{{ __('Mutual Interest') }}</span>
                        </span>
                    </div>
                </div>
                <div class="p-6 flex-1">
                    <h4 class="text-slate-600 dark:text-text-secondary font-bold text-xs mb-4 uppercase tracking-wider" data-translate="About">{{ __('About') }}</h4>
                    <div class="space-y-6" id="userInfoAbout">
                    </div>
                    <div class="mt-8 pt-6 border-t border-gray-200 dark:border-border-dark space-y-3">
                        <button class="w-full py-2.5 px-4 rounded-lg bg-gray-100 dark:bg-surface-dark hover:bg-gray-200 dark:hover:bg-border-dark text-slate-900 dark:text-text-primary-light text-sm font-bold transition-colors flex items-center justify-center gap-2 border border-gray-200 dark:border-border-dark" onclick="viewFullProfile()">
                            <span class="material-symbols-outlined icon-base">person</span>
                            <span data-translate="View Full Profile">{{ __('View Full Profile') }}</span>
                        </button>
                        <a href="#" id="reportUserLink" class="w-full py-2.5 px-4 rounded-lg border border-primary/30 text-primary hover:bg-primary/10 text-sm font-bold transition-colors flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined icon-base">flag</span>
                            <span data-translate="Report User">{{ __('Report User') }}</span>
                        </a>
                    </div>
                </div>
            </aside>
            
            @if(!$selectedUser && $connections->count() > 0)
            <div class="flex-1 flex items-center justify-center bg-gray-50 dark:bg-chat-bg lg:hidden" id="emptyChatState">
                <div class="text-center px-4">
                    <span class="material-symbols-outlined text-6xl text-slate-400 dark:text-text-secondary mb-4">chat_bubble_outline</span>
                    <p class="text-slate-600 dark:text-text-secondary text-lg mb-4" data-translate="Select a conversation to start chatting">{{ __('Select a conversation to start chatting') }}</p>
                    <button onclick="showMobileMenu()" class="inline-block bg-primary text-white px-6 py-3 rounded-lg font-bold hover:bg-[#D43110] transition-colors" data-translate="View Conversations">{{ __('View Conversations') }}</button>
                </div>
            </div>
            @elseif($connections->count() == 0)
            <div class="flex-1 flex items-center justify-center bg-gray-50 dark:bg-chat-bg">
                <div class="text-center px-4">
                    <span class="material-symbols-outlined text-6xl text-text-secondary mb-4">chat_bubble_outline</span>
                    <p class="text-text-secondary text-lg mb-4" data-translate="No messages yet">{{ __('No messages yet') }}</p>
                    <a href="{{ route('requests') }}" class="inline-block bg-primary text-white px-6 py-3 rounded-lg font-bold hover:bg-[#D43110] transition-colors" data-translate="View Requests">{{ __('View Requests') }}</a>
                </div>
            </div>
            @endif
        </main>
    </div>

    <script>
        let currentUserId = null;
        let lastMessageId = null;
        let pollInterval = null;
        let isSendingMessage = false;
        const POLL_INTERVAL = 2000; // 2 seconds
        
        // Auto-resize textarea
        const messageInput = document.getElementById('messageInput');
        if (messageInput) {
            messageInput.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = Math.min(this.scrollHeight, 128) + 'px';
            });
            
            messageInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    sendMessage();
                }
            });
        }
        
        function selectUser(encryptedUserId) {
            currentUserId = encryptedUserId;
            lastMessageId = null;
            
            // Update active state
            document.querySelectorAll('.connection-item').forEach(item => {
                item.classList.remove('bg-surface-dark/40', 'border-primary');
            });
            const connectionItem = document.querySelector(`[data-encrypted-id="${encryptedUserId}"]`)?.closest('.connection-item');
            if (connectionItem) {
                connectionItem.classList.add('bg-surface-dark/40', 'border-primary');
            }
            
            // Hide empty state
            const emptyState = document.getElementById('emptyChatState');
            if (emptyState) emptyState.style.display = 'none';
            
            // Show chat section
            document.getElementById('chatSection').style.display = 'flex';
            
            // Show user info sidebar
            const userInfoSidebar = document.getElementById('userInfoSidebar');
            if (userInfoSidebar) {
                userInfoSidebar.style.display = 'flex';
            }
            
            // Load chat first, then start polling
            loadChat(encryptedUserId).then(() => {
                // Start polling only after chat is loaded
                startPolling();
            });
        }
        
        function loadChat(encryptedUserId) {
            return fetch(`/messages/chat/${encodeURIComponent(encryptedUserId)}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    return Promise.reject(data.error);
                }
                
                // Update chat header
                const chatUserImage = document.getElementById('chatUserImage');
                const chatUserName = document.getElementById('chatUserName');
                const userInfoImage = document.getElementById('userInfoImage');
                const userInfoName = document.getElementById('userInfoName');
                const userInfoDetails = document.getElementById('userInfoDetails');
                
                // Update chat header image and name
                const chatUserImageUrl = data.otherUser.profile_image 
                    ? data.otherUser.profile_image 
                    : `https://ui-avatars.com/api/?name=${encodeURIComponent(data.otherUser.full_name || 'User')}&background=ec3713&color=fff`;
                if (chatUserImage) {
                    chatUserImage.style.backgroundImage = `url('${chatUserImageUrl}')`;
                }
                const chatUserNameSpan = document.getElementById('chatUserName');
                if (chatUserNameSpan) {
                    chatUserNameSpan.textContent = data.otherUser.full_name || 'Unknown User';
                }
                // Update profile links
                const chatUserProfileLink = document.getElementById('chatUserProfileLink');
                const chatUserNameLink = document.getElementById('chatUserNameLink');
                if (chatUserProfileLink && data.otherUser.id) {
                    chatUserProfileLink.href = `/profile/${data.otherUser.id}`;
                    chatUserProfileLink.onclick = function(e) {
                        e.preventDefault();
                        window.location.href = `/profile/${data.otherUser.id}`;
                    };
                }
                if (chatUserNameLink && data.otherUser.id) {
                    chatUserNameLink.href = `/profile/${data.otherUser.id}`;
                    chatUserNameLink.onclick = function(e) {
                        e.preventDefault();
                        window.location.href = `/profile/${data.otherUser.id}`;
                    };
                }
                
                // Update right sidebar image and name
                const userInfoImageUrl = data.otherUser.profile_image 
                    ? data.otherUser.profile_image 
                    : `https://ui-avatars.com/api/?name=${encodeURIComponent(data.otherUser.full_name || 'User')}&background=ec3713&color=fff`;
                if (userInfoImage) {
                    userInfoImage.style.backgroundImage = `url('${userInfoImageUrl}')`;
                }
                const userInfoNameSpan = document.getElementById('userInfoName');
                if (userInfoNameSpan) {
                    userInfoNameSpan.textContent = data.otherUser.full_name || 'Unknown User';
                }
                // Update sidebar profile links
                const userInfoImageLink = document.getElementById('userInfoImageLink');
                const userInfoNameLink = document.getElementById('userInfoNameLink');
                if (userInfoImageLink && data.otherUser.id) {
                    userInfoImageLink.href = `/profile/${data.otherUser.id}`;
                    userInfoImageLink.onclick = function(e) {
                        e.preventDefault();
                        window.location.href = `/profile/${data.otherUser.id}`;
                    };
                }
                if (userInfoNameLink && data.otherUser.id) {
                    userInfoNameLink.href = `/profile/${data.otherUser.id}`;
                    userInfoNameLink.onclick = function(e) {
                        e.preventDefault();
                        window.location.href = `/profile/${data.otherUser.id}`;
                    };
                }
                
                let details = [];
                if (data.otherUser.age) details.push(`${data.otherUser.age} yrs`);
                if (data.otherUser.height) details.push(data.otherUser.height);
                if (data.otherUser.city) details.push(data.otherUser.city);
                userInfoDetails.textContent = details.join(' • ') || 'No details available';
                
                // Update user info sidebar
                updateUserInfo(data.otherUser);
                
                // Update verification badge in header
                const verificationBadge = document.getElementById('chatVerificationBadge');
                if (verificationBadge) {
                    if (data.otherUser.verification_status === 'verified') {
                        verificationBadge.classList.replace('hidden', 'flex');
                    } else {
                        verificationBadge.classList.replace('flex', 'hidden');
                    }
                }
                
                // Render messages
                renderMessages(data.messages);
                
                // Scroll to bottom
                scrollToBottom();
                
                // Update last message ID - use the highest ID to prevent fetching already loaded messages
                if (data.messages.length > 0) {
                    const messageIds = data.messages.map(m => m.id);
                    lastMessageId = Math.max(...messageIds);
                } else {
                    lastMessageId = null;
                }
            })
            .catch(error => {
                console.error('Error loading chat:', error);
                return Promise.reject(error);
            });
        }
        
        function renderMessages(messages) {
            const container = document.getElementById('chat-container');
            const currentUser = {{ Auth::id() }};
            
            // Clear existing messages (except encryption notice and date)
            const existingMessages = container.querySelectorAll('.message-item');
            existingMessages.forEach(msg => msg.remove());
            
            let currentDate = null;
            
            messages.forEach(message => {
                const messageDate = new Date(message.created_at);
                const messageDateStr = messageDate.toDateString();
                
                // Add date separator if needed
                if (currentDate !== messageDateStr) {
                    currentDate = messageDateStr;
                    const dateDiv = document.createElement('div');
                    dateDiv.className = 'flex justify-center';
                    dateDiv.innerHTML = `<span class="text-xs font-bold text-text-secondary uppercase tracking-wider">${formatDate(messageDate)}</span>`;
                    container.appendChild(dateDiv);
                }
                
                const isOwn = message.sender_id == currentUser;
                const messageDiv = document.createElement('div');
                messageDiv.className = `message-item flex items-end gap-3 max-w-[85%] ${isOwn ? 'self-end flex-row-reverse' : 'self-start'} group`;
                messageDiv.setAttribute('data-message-id', message.id);
                messageDiv.setAttribute('data-date', messageDateStr);
                
                const profileImage = message.sender.profile_image 
                    ? `{{ asset('storage/') }}/${message.sender.profile_image}`
                    : `https://ui-avatars.com/api/?name=${encodeURIComponent(message.sender.full_name)}&background=ec3713&color=fff`;
                
                messageDiv.innerHTML = `
                    <div class="bg-center bg-no-repeat bg-cover rounded-full size-8 shrink-0 mb-1" style="background-image: url('${profileImage}');"></div>
                    <div class="flex flex-col gap-1 ${isOwn ? 'items-end' : 'items-start'}">
                        ${!isOwn ? `<span class="text-text-secondary text-[11px] ml-1">${message.sender.full_name}, ${formatTime(messageDate)}</span>` : ''}
                        ${isOwn ? `<div class="flex items-center gap-1 mr-1">
                            <span class="text-text-secondary text-[11px]">${formatTime(messageDate)}</span>
                            <span class="material-symbols-outlined icon-xs text-primary">done_all</span>
                        </div>` : ''}
                        <div class="${isOwn ? 'bg-primary text-text-primary-light rounded-2xl rounded-br-sm shadow-md shadow-primary/10' : 'bg-chat-bubble-other border border-border-dark text-text-primary-light rounded-2xl rounded-bl-sm shadow-sm'} px-4 py-3">
                            <p class="text-sm sm:text-base leading-relaxed">${escapeHtml(message.message)}</p>
                        </div>
                    </div>
                `;
                
                container.appendChild(messageDiv);
            });
            
            scrollToBottom();
        }
        
        function sendMessage() {
            if (!currentUserId || !messageInput.value.trim() || isSendingMessage) return;
            
            const message = messageInput.value.trim();
            messageInput.value = '';
            messageInput.style.height = 'auto';
            
            // Set flag to prevent polling during send
            isSendingMessage = true;
            
            // Show typing indicator
            showTypingIndicator();
            
            fetch(`/messages/send/${encodeURIComponent(currentUserId)}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ message: message })
            })
            .then(response => response.json())
            .then(data => {
                hideTypingIndicator();
                isSendingMessage = false;
                
                if (data.message) {
                    // Update lastMessageId immediately to prevent polling from fetching it again
                    lastMessageId = data.message.id;
                    
                    // Append the new message to the chat (appendMessage will check for duplicates)
                    appendMessage(data.message);
                    
                    // Update last message in sidebar
                    updateLastMessage(currentUserId, message);
                }
            })
            .catch(error => {
                hideTypingIndicator();
                isSendingMessage = false;
                console.error('Error sending message:', error);
                alert('Failed to send message. Please try again.');
            });
        }
        
        function appendMessage(message) {
            const container = document.getElementById('chat-container');
            const currentUser = {{ Auth::id() }};
            
            // Check if message already exists (prevent duplicates)
            const existingMessage = container.querySelector(`.message-item[data-message-id="${message.id}"]`);
            if (existingMessage) {
                return; // Message already exists, don't add it again
            }
            
            const messageDate = new Date(message.created_at);
            const messageDateStr = messageDate.toDateString();
            
            // Check if we need a date separator
            const existingMessages = container.querySelectorAll('.message-item[data-message-id]');
            let needsDateSeparator = false;
            if (existingMessages.length > 0) {
                const lastMessage = existingMessages[existingMessages.length - 1];
                const lastMessageDate = lastMessage.getAttribute('data-date');
                if (lastMessageDate !== messageDateStr) {
                    needsDateSeparator = true;
                }
            } else {
                needsDateSeparator = true;
            }
            
            // Add date separator if needed
            if (needsDateSeparator) {
                const dateDiv = document.createElement('div');
                dateDiv.className = 'flex justify-center';
                dateDiv.innerHTML = `<span class="text-xs font-bold text-text-secondary uppercase tracking-wider">${formatDate(messageDate)}</span>`;
                container.appendChild(dateDiv);
            }
            
            const isOwn = message.sender_id == currentUser;
            const messageDiv = document.createElement('div');
            messageDiv.className = `message-item flex items-end gap-3 max-w-[85%] ${isOwn ? 'self-end flex-row-reverse' : 'self-start'} group`;
            messageDiv.setAttribute('data-message-id', message.id);
            messageDiv.setAttribute('data-date', messageDateStr);
            
            const profileImage = message.sender.profile_image 
                ? `{{ asset('storage/') }}/${message.sender.profile_image}`
                : `https://ui-avatars.com/api/?name=${encodeURIComponent(message.sender.full_name)}&background=ec3713&color=fff`;
            
            messageDiv.innerHTML = `
                <div class="bg-center bg-no-repeat bg-cover rounded-full size-8 shrink-0 mb-1" style="background-image: url('${profileImage}');"></div>
                <div class="flex flex-col gap-1 ${isOwn ? 'items-end' : 'items-start'}">
                    ${!isOwn ? `<span class="text-text-secondary text-[11px] ml-1">${message.sender.full_name}, ${formatTime(messageDate)}</span>` : ''}
                    ${isOwn ? `<div class="flex items-center gap-1 mr-1">
                        <span class="text-text-secondary text-[11px]">${formatTime(messageDate)}</span>
                        <span class="material-symbols-outlined icon-xs text-primary">done_all</span>
                    </div>` : ''}
                    <div class="${isOwn ? 'bg-primary text-text-primary-light rounded-2xl rounded-br-sm shadow-md shadow-primary/10' : 'bg-chat-bubble-other border border-border-dark text-text-primary-light rounded-2xl rounded-bl-sm shadow-sm'} px-4 py-3">
                        <p class="text-sm sm:text-base leading-relaxed">${escapeHtml(message.message)}</p>
                    </div>
                </div>
            `;
            
            container.appendChild(messageDiv);
            scrollToBottom();
        }
        
        function startPolling() {
            stopPolling();
            pollInterval = setInterval(() => {
                if (currentUserId) {
                    fetchNewMessages();
                }
            }, POLL_INTERVAL);
        }
        
        function stopPolling() {
            if (pollInterval) {
                clearInterval(pollInterval);
                pollInterval = null;
            }
        }
        
        function fetchNewMessages() {
            if (!currentUserId || lastMessageId === null || isSendingMessage) {
                return; // Don't poll until initial load is complete or while sending
            }
            
            const url = `/messages/new/${encodeURIComponent(currentUserId)}${lastMessageId ? '?last_message_id=' + lastMessageId : ''}`;
            
            fetch(url, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.newMessages && data.newMessages.length > 0) {
                    // Append new messages (appendMessage will check for duplicates)
                    let newMessagesAdded = 0;
                    data.newMessages.forEach(message => {
                        // Check if message already exists before appending
                        const container = document.getElementById('chat-container');
                        const existingMessage = container.querySelector(`.message-item[data-message-id="${message.id}"]`);
                        if (!existingMessage) {
                            appendMessage(message);
                            newMessagesAdded++;
                        }
                    });
                    
                    // Update lastMessageId only if we actually added new messages
                    if (newMessagesAdded > 0) {
                        const newMessageIds = data.newMessages.map(m => m.id);
                        lastMessageId = Math.max(...newMessageIds);
                    }
                }
            })
            .catch(error => {
                console.error('Error fetching new messages:', error);
            });
        }
        
        function showTypingIndicator() {
            const container = document.getElementById('chat-container');
            const typingDiv = document.createElement('div');
            typingDiv.id = 'typing-indicator';
            typingDiv.className = 'flex items-end gap-3 self-start animate-pulse';
            typingDiv.innerHTML = `
                <div class="bg-center bg-no-repeat bg-cover rounded-full size-6 shrink-0 mb-1 opacity-70" style="background-image: url('{{ Auth::user()->profile_image ? asset('storage/' . Auth::user()->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->full_name) . '&background=ec3713&color=fff' }}');"></div>
                <div class="bg-chat-bubble-other border border-border-dark px-4 py-3 rounded-2xl rounded-bl-sm h-10 flex items-center gap-1">
                    <div class="size-1.5 bg-text-secondary rounded-full"></div>
                    <div class="size-1.5 bg-text-secondary rounded-full"></div>
                    <div class="size-1.5 bg-text-secondary rounded-full"></div>
                </div>
            `;
            container.appendChild(typingDiv);
            scrollToBottom();
        }
        
        function hideTypingIndicator() {
            const indicator = document.getElementById('typing-indicator');
            if (indicator) indicator.remove();
        }
        
        function scrollToBottom() {
            const container = document.getElementById('chat-container');
            container.scrollTop = container.scrollHeight;
        }
        
        function formatTime(date) {
            return date.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
        }
        
        function formatDate(date) {
            const today = new Date();
            const yesterday = new Date(today);
            yesterday.setDate(yesterday.getDate() - 1);
            
            const translations = window.translations || {};
            
            if (date.toDateString() === today.toDateString()) {
                return translations['Today'] || 'Today';
            } else if (date.toDateString() === yesterday.toDateString()) {
                return translations['Yesterday'] || 'Yesterday';
            } else {
                return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: date.getFullYear() !== today.getFullYear() ? 'numeric' : undefined });
            }
        }
        
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        function updateLastMessage(userId, message) {
            const preview = document.querySelector(`.last-message-preview[data-user-id="${userId}"]`);
            const time = document.querySelector(`.last-message-time[data-user-id="${userId}"]`);
            if (preview) preview.textContent = message.length > 50 ? message.substring(0, 50) + '...' : message;
            if (time) {
                const translations = window.translations || {};
                time.textContent = translations['Just now'] || 'Just now';
            }
        }
        
        function updateUserInfo(user) {
            const aboutDiv = document.getElementById('userInfoAbout');
            aboutDiv.innerHTML = '';
            
            // Update report link
            const reportLink = document.getElementById('reportUserLink');
            if (reportLink && currentUserId) {
                reportLink.href = `/report/${encodeURIComponent(currentUserId)}`;
            }
            
            if (user.occupation) {
                aboutDiv.innerHTML += `
                    <div class="flex gap-3 items-start">
                        <div class="size-8 rounded-lg bg-surface-dark flex items-center justify-center shrink-0 border border-border-dark/50">
                            <span class="material-symbols-outlined text-text-secondary icon-base">work</span>
                        </div>
                        <div>
                            <p class="text-text-secondary text-[10px] uppercase tracking-wide font-bold mb-0.5">Profession</p>
                            <p class="text-text-primary-light text-sm font-medium">${user.occupation || 'Not specified'}</p>
                        </div>
                    </div>
                `;
            }
            
            if (user.highest_education) {
                aboutDiv.innerHTML += `
                    <div class="flex gap-3 items-start">
                        <div class="size-8 rounded-lg bg-surface-dark flex items-center justify-center shrink-0 border border-border-dark/50">
                            <span class="material-symbols-outlined text-text-secondary icon-base">school</span>
                        </div>
                        <div>
                            <p class="text-text-secondary text-[10px] uppercase tracking-wide font-bold mb-0.5">Education</p>
                            <p class="text-text-primary-light text-sm font-medium">${user.highest_education || 'Not specified'}</p>
                        </div>
                    </div>
                `;
            }
            
            if (user.mother_tongue) {
                aboutDiv.innerHTML += `
                    <div class="flex gap-3 items-start">
                        <div class="size-8 rounded-lg bg-surface-dark flex items-center justify-center shrink-0 border border-border-dark/50">
                            <span class="material-symbols-outlined text-text-secondary icon-base">translate</span>
                        </div>
                        <div>
                            <p class="text-text-secondary text-[10px] uppercase tracking-wide font-bold mb-0.5">Mother Tongue</p>
                            <p class="text-text-primary-light text-sm font-medium">${user.mother_tongue || 'Not specified'}</p>
                        </div>
                    </div>
                `;
            }
        }
        
        function viewFullProfile() {
            if (currentUserId) {
                // Get the actual user ID from the connection item
                const connectionItem = document.querySelector(`[data-encrypted-id="${currentUserId}"]`);
                if (connectionItem) {
                    const userId = connectionItem.getAttribute('data-user-id');
                    if (userId) {
                        window.location.href = `/profile/${userId}`;
                        return;
                    }
                }
            }
        }
        
        function closeChat() {
            document.getElementById('chatSection').style.display = 'none';
            
            // Hide user info sidebar
            const userInfoSidebar = document.getElementById('userInfoSidebar');
            if (userInfoSidebar) {
                userInfoSidebar.style.display = 'none';
            }
            
            // Show connections sidebar on mobile
            if (window.innerWidth < 1024) {
                showMobileMenu();
            }
            
            stopPolling();
            currentUserId = null;
        }
        
        function toggleMobileMenu() {
            const sidebar = document.getElementById('connectionsSidebar');
            const overlay = document.getElementById('mobileConnectionsOverlay');
            const menuToggle = document.getElementById('mobileMenuToggle');
            
            if (sidebar.classList.contains('-translate-x-full')) {
                showMobileMenu();
            } else {
                closeMobileMenu();
            }
        }
        
        function showMobileMenu() {
            const sidebar = document.getElementById('connectionsSidebar');
            const overlay = document.getElementById('mobileConnectionsOverlay');
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        
        function closeMobileMenu() {
            const sidebar = document.getElementById('connectionsSidebar');
            const overlay = document.getElementById('mobileConnectionsOverlay');
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
            document.body.style.overflow = '';
        }
        
        // Close mobile menu when selecting a user
        function selectUser(encryptedUserId) {
            currentUserId = encryptedUserId;
            lastMessageId = null;
            
            // Close mobile menu if open
            if (window.innerWidth < 1024) {
                closeMobileMenu();
            }
            
            // Update active state
            document.querySelectorAll('.connection-item').forEach(item => {
                item.classList.remove('bg-surface-dark/40', 'border-primary');
            });
            const connectionItem = document.querySelector(`[data-encrypted-id="${encryptedUserId}"]`)?.closest('.connection-item');
            if (connectionItem) {
                connectionItem.classList.add('bg-surface-dark/40', 'border-primary');
            }
            
            // Hide empty state
            const emptyState = document.getElementById('emptyChatState');
            if (emptyState) emptyState.style.display = 'none';
            
            // Show chat section
            document.getElementById('chatSection').style.display = 'flex';
            
            // Show user info sidebar
            const userInfoSidebar = document.getElementById('userInfoSidebar');
            if (userInfoSidebar) {
                userInfoSidebar.style.display = 'flex';
            }
            
            // Load chat first, then start polling
            loadChat(encryptedUserId).then(() => {
                // Start polling only after chat is loaded
                startPolling();
            });
        }
        
        // Initialize if user is selected
        @if($selectedUser)
        selectUser('{{ encrypt($selectedUser->id) }}');
        @endif
        
        // Cleanup on page unload
        window.addEventListener('beforeunload', () => {
            stopPolling();
        });
    </script>
    
    <!-- Global Theme Manager -->
    <script src="{{ asset('js/theme.js') }}"></script>
    <!-- Language Switcher -->
    <script src="{{ asset('js/language-switcher.js') }}"></script>
</body>
</html>
