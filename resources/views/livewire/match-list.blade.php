<div class="flex flex-1">
    <!-- Results Grid Area -->
    <main class="flex-1 p-4 lg:p-8 overflow-x-hidden transition-all duration-500 ease-in-out {{ $showFilters ? 'lg:mr-[340px]' : '' }}">
        <div class="max-w-[1400px] mx-auto flex flex-col h-full">
            
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8 animate-fade-in">
                <div>
                    <h1 class="text-slate-900 dark:text-white text-4xl md:text-5xl font-bold leading-tight tracking-tight mb-2">
                        {{ __('Refine Your Match') }}
                    </h1>
                    <p class="text-slate-600 dark:text-text-muted text-lg transition-all duration-300">
                        <span wire:loading.remove>{{ __('Showing') }} <span class="text-primary font-bold">{{ $users->total() }}</span> {{ __('profiles') }}</span>
                        <span wire:loading class="text-primary font-bold animate-pulse">{{ __('Updating results...') }}</span>
                    </p>
                </div>
                
                <div class="flex items-center gap-3">
                    <select wire:model.live="sort" class="rounded-full border-gray-300 dark:border-border-dark text-sm bg-white dark:bg-surface-dark text-slate-900 dark:text-white focus:border-primary focus:ring-primary">
                        <option value="relevance">{{ __('Relevance') }}</option>
                        <option value="newest">{{ __('Newest First') }}</option>
                        <option value="age_low">{{ __('Age: Low to High') }}</option>
                        <option value="age_high">{{ __('Age: High to Low') }}</option>
                    </select>
                    <button wire:click="toggleFilters" class="flex items-center gap-2 px-4 py-2 rounded-full bg-white dark:bg-surface-dark border border-gray-300 dark:border-border-dark text-slate-900 dark:text-white font-medium hover:bg-primary hover:border-primary hover:text-white transition-colors">
                        <span class="material-symbols-outlined">tune</span> {{ __('Filters') }}
                    </button>
                </div>
            </div>

            <!-- Loading Skeleton -->
            <div wire:loading.block wire:target="genderPref, ageFrom, ageTo, state, city, education, occupation, marital_status, sort" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                @for ($i = 0; $i < 6; $i++)
                    <div class="aspect-[3/4] rounded-[2rem] bg-gray-200 dark:bg-surface-dark animate-pulse"></div>
                @endfor
            </div>

            <!-- Results Grid -->
            <div wire:loading.remove wire:target="genderPref, ageFrom, ageTo, state, city, education, occupation, marital_status, sort" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                @forelse($users as $match)
                    <div class="group relative aspect-square rounded-[2rem] overflow-hidden bg-white dark:bg-surface-dark cursor-pointer shadow-sm hover:shadow-xl transition-all duration-500 hover:-translate-y-1" wire:key="user-{{ $match->id }}">
                        <!-- Image -->
                        <a href="{{ route('profile.view', $match->id) }}" class="absolute inset-0 z-10">
                            <img src="{{ $match->profile_image ? asset('storage/' . $match->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($match->full_name) . '&size=800&background=ec3713&color=fff' }}" 
                                 class="absolute inset-0 size-full object-cover transition-transform duration-700 group-hover:scale-105"
                                 loading="lazy">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent opacity-60 group-hover:opacity-80 transition-opacity duration-500"></div>
                        </a>
                        
                        <!-- Badges -->
                        <div class="absolute top-4 left-4 flex gap-2 z-20">
                            @if($match->verification_status === 'verified')
                            <span class="px-3 py-1 rounded-full bg-black/50 text-white text-[10px] font-bold uppercase tracking-wider backdrop-blur-sm border border-white/10 flex items-center gap-1.5 leading-none shadow-sm">
                                <span class="material-symbols-outlined text-green-400 !text-[14px] translate-y-[1.5px]">verified</span> 
                                <span class="pt-[2px]">{{ __('Verified') }}</span>
                            </span>
                            @endif
                        </div>

                        <!-- Shortlist Button -->
                        <div class="absolute top-4 right-4 z-20">
                            <button wire:click="toggleShortlist({{ $match->id }})" class="size-10 rounded-full bg-white/10 backdrop-blur-md flex items-center justify-center text-white hover:bg-primary hover:text-white transition-all shadow-lg {{ $match->isShortlisted ? 'bg-primary/20' : '' }}">
                                <span class="material-symbols-outlined filled">{{ $match->isShortlisted ? 'favorite' : 'favorite_border' }}</span>
                            </button>
                        </div>

                        <!-- Content -->
                        <div class="absolute bottom-0 left-0 right-0 p-6 flex flex-col gap-1 transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300 z-20 pointer-events-none">
                            <div class="flex items-end justify-between">
                                <h3 class="text-3xl font-bold text-white leading-none">{{ $match->full_name }}, {{ $match->age }}</h3>
                                    {{ $match->matchPercentage }}% <span class="text-xs font-normal text-white/80">{{ __('Score') }}</span>
                            </div>
                            <p class="text-gray-300 font-medium text-sm mt-1">
                                {{ $match->occupation ?? 'N/A' }} • {{ $match->location }}
                            </p>
                            
                            <!-- Action Button (Interactive) -->
                            <div class="pointer-events-auto opacity-0 group-hover:opacity-100 transition-opacity duration-300 delay-75 mt-4">
                                @if($isFreePlan)
                                    <button type="button" onclick="event.preventDefault(); event.stopPropagation(); document.getElementById('upgrade-modal').classList.remove('hidden');" class="w-full h-11 bg-white text-black font-bold rounded-full hover:bg-primary hover:text-white transition-colors flex items-center justify-center gap-2">
                                        <span>{{ __('Send Interest') }}</span>
                                    </button>
                                @else
                                    <button wire:click="sendInterest({{ $match->id }})" class="w-full h-11 bg-white text-black font-bold rounded-full hover:bg-primary hover:text-white transition-colors flex items-center justify-center gap-2">
                                        <span wire:loading.remove wire:target="sendInterest({{ $match->id }})">{{ __('Send Interest') }}</span>
                                        <span wire:loading wire:target="sendInterest({{ $match->id }})" class="material-symbols-outlined animate-spin icon-sm">sync</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full flex flex-col items-center justify-center py-20 text-center">
                        <div class="w-24 h-24 bg-gray-100 dark:bg-surface-dark rounded-full flex items-center justify-center mb-6">
                            <span class="material-symbols-outlined icon-2xl text-slate-400 dark:text-text-muted">search_off</span>
                        </div>
                        <h3 class="text-slate-900 dark:text-white text-xl font-bold mb-2">{{ __('No matches found') }}</h3>
                        <p class="text-slate-600 dark:text-text-muted max-w-md">{{ __('Try adjusting your filters to see more profiles.') }}</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $users->links() }}
            </div>
        </div>
        </main>

    <!-- Filter Sidebar -->
    <aside class="fixed right-0 top-0 h-full w-[340px] bg-white dark:bg-surface-dark border-l border-gray-200 dark:border-border-dark p-6 z-50 transform transition-transform duration-500 ease-in-expo shadow-2xl overflow-y-auto {{ $showFilters ? 'translate-x-0' : 'translate-x-full' }}">
        <div class="flex items-center justify-between mb-8">
            <h3 class="text-xl font-bold text-slate-900 dark:text-white">{{ __('Filters') }}</h3>
            <button wire:click="toggleFilters" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-white/10">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <div class="space-y-8 pb-20">
            <!-- Gender -->
            <div>
                <label class="text-xs font-bold uppercase text-slate-500 mb-3 block">{{ __('Looking For') }}</label>
                <div class="flex gap-2">
                    <button wire:click="$set('genderPref', 'female')" class="flex-1 py-2 rounded-full border {{ $genderPref === 'female' ? 'bg-primary text-white border-primary' : 'border-gray-300 dark:border-border-dark dark:text-white' }}">
                        {{ __('Bride') }}
                    </button>
                    <button wire:click="$set('genderPref', 'male')" class="flex-1 py-2 rounded-full border {{ $genderPref === 'male' ? 'bg-primary text-white border-primary' : 'border-gray-300 dark:border-border-dark dark:text-white' }}">
                        {{ __('Groom') }}
                    </button>
                </div>
            </div>

            <!-- Age Range -->
            <div>
                <div class="flex justify-between items-center mb-3">
                    <label class="text-xs font-bold uppercase text-slate-500">{{ __('Age Range') }}</label>
                    <span class="text-sm font-medium text-slate-900 dark:text-white">{{ $ageFrom }} - {{ $ageTo }} {{ __('Yrs') }}</span>
                </div>
                <div class="flex items-center gap-4">
                    <input type="number" wire:model.live.debounce.500ms="ageFrom" class="w-full h-10 rounded-xl bg-gray-50 dark:bg-surface-dark border border-gray-300 dark:border-border-dark text-slate-900 dark:text-white text-sm px-4 focus:border-primary">
                    <span class="text-slate-400">-</span>
                    <input type="number" wire:model.live.debounce.500ms="ageTo" class="w-full h-10 rounded-xl bg-gray-50 dark:bg-surface-dark border border-gray-300 dark:border-border-dark text-slate-900 dark:text-white text-sm px-4 focus:border-primary">
                </div>
            </div>

            <!-- State -->
            <div x-data="{ open: true }">
                <button @click="open = !open" class="flex items-center justify-between w-full mb-3">
                    <span class="text-xs font-bold uppercase text-slate-500">{{ __('State') }}</span>
                    <span class="material-symbols-outlined text-slate-500 transition-transform" :class="{ 'rotate-180': open }">expand_more</span>
                </button>
                <div x-show="open" class="flex flex-wrap gap-2">
                    @foreach($states as $s)
                    <label class="cursor-pointer">
                        <input type="checkbox" wire:model.live="state" value="{{ $s->name }}" class="peer sr-only">
                        <span class="block px-3 py-1.5 rounded-full border border-gray-300 dark:border-border-dark text-xs text-slate-600 dark:text-text-muted hover:border-primary hover:text-slate-900 dark:hover:text-white peer-checked:bg-primary/20 peer-checked:border-primary peer-checked:text-primary transition-all">{{ $s->name }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- City -->
            @if(count($cities) > 0)
            <div x-data="{ open: true }">
                <button @click="open = !open" class="flex items-center justify-between w-full mb-3">
                    <span class="text-xs font-bold uppercase text-slate-500">{{ __('City') }}</span>
                    <span class="material-symbols-outlined text-slate-500 transition-transform" :class="{ 'rotate-180': open }">expand_more</span>
                </button>
                <div x-show="open" class="flex flex-wrap gap-2">
                    @foreach($cities as $c)
                    @php $cityName = $c->city_master ?? $c->name; @endphp
                    @if($cityName)
                    <label class="cursor-pointer">
                        <input type="checkbox" wire:model.live="city" value="{{ $cityName }}" class="peer sr-only">
                        <span class="block px-3 py-1.5 rounded-full border border-gray-300 dark:border-border-dark text-xs text-slate-600 dark:text-text-muted hover:border-primary hover:text-slate-900 dark:hover:text-white peer-checked:bg-primary/20 peer-checked:border-primary peer-checked:text-primary transition-all">{{ $cityName }}</span>
                    </label>
                    @endif
                    @endforeach
                </div>
            </div>
            @endif


            
             <!-- Marital Status -->
            <div x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center justify-between w-full mb-3">
                    <span class="text-xs font-bold uppercase text-slate-500">{{ __('Marital Status') }}</span>
                    <span class="material-symbols-outlined text-slate-500 transition-transform" :class="{ 'rotate-180': open }">expand_more</span>
                </button>
                <div x-show="open" class="flex flex-wrap gap-2">
                    @foreach($maritalStatuses as $status)
                    <label class="cursor-pointer">
                        <input type="checkbox" wire:model.live="marital_status" value="{{ $status }}" class="peer sr-only">
                        <span class="block px-3 py-1.5 rounded-full border border-gray-300 dark:border-border-dark text-xs text-slate-600 dark:text-text-muted hover:border-primary hover:text-slate-900 dark:hover:text-white peer-checked:bg-primary/20 peer-checked:border-primary peer-checked:text-primary transition-all">{{ __($status) }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- Education -->
            @if(count($highestQualifications) > 0)
            <div x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center justify-between w-full mb-3">
                    <span class="text-xs font-bold uppercase text-slate-500">{{ __('Education') }}</span>
                    <span class="material-symbols-outlined text-slate-500 transition-transform" :class="{ 'rotate-180': open }">expand_more</span>
                </button>
                <div x-show="open" class="flex flex-wrap gap-2">
                    @foreach($highestQualifications as $q)
                    <label class="cursor-pointer">
                        <input type="checkbox" wire:model.live="education" value="{{ $q->name }}" class="peer sr-only">
                        <span class="block px-3 py-1.5 rounded-full border border-gray-300 dark:border-border-dark text-xs text-slate-600 dark:text-text-muted hover:border-primary hover:text-slate-900 dark:hover:text-white peer-checked:bg-primary/20 peer-checked:border-primary peer-checked:text-primary transition-all">{{ $q->name }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Occupation -->
            @if(count($occupations) > 0)
            <div x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center justify-between w-full mb-3">
                    <span class="text-xs font-bold uppercase text-slate-500">{{ __('Occupation') }}</span>
                    <span class="material-symbols-outlined text-slate-500 transition-transform" :class="{ 'rotate-180': open }">expand_more</span>
                </button>
                <div x-show="open" class="flex flex-wrap gap-2">
                    @foreach($occupations as $o)
                    <label class="cursor-pointer">
                        <input type="checkbox" wire:model.live="occupation" value="{{ $o->name }}" class="peer sr-only">
                        <span class="block px-3 py-1.5 rounded-full border border-gray-300 dark:border-border-dark text-xs text-slate-600 dark:text-text-muted hover:border-primary hover:text-slate-900 dark:hover:text-white peer-checked:bg-primary/20 peer-checked:border-primary peer-checked:text-primary transition-all">{{ $o->name }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Mother Tongue -->
            @if(count($motherTongues) > 0)
            <div x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center justify-between w-full mb-3">
                    <span class="text-xs font-bold uppercase text-slate-500">{{ __('Mother Tongue') }}</span>
                    <span class="material-symbols-outlined text-slate-500 transition-transform" :class="{ 'rotate-180': open }">expand_more</span>
                </button>
                <div x-show="open" class="flex flex-wrap gap-2">
                    @foreach($motherTongues as $mt)
                    <label class="cursor-pointer">
                        <input type="checkbox" wire:model.live="mother_tongue" value="{{ $mt->name }}" class="peer sr-only">
                        <span class="block px-3 py-1.5 rounded-full border border-gray-300 dark:border-border-dark text-xs text-slate-600 dark:text-text-muted hover:border-primary hover:text-slate-900 dark:hover:text-white peer-checked:bg-primary/20 peer-checked:border-primary peer-checked:text-primary transition-all">{{ $mt->name }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </aside>
</div>
