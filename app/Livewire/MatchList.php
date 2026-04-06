<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MatchList extends Component
{
    use WithPagination;

    // Filter Properties
    public $genderPref;
    public $ageFrom = 24;
    public $ageTo = 32;
    public $state = [];
    public $city = [];
    public $education = [];
    public $occupation = [];
    public $marital_status = [];
    public $mother_tongue = [];
    public $sort = 'relevance';
    
    // UI State
    public $showFilters = false;
    public $isFreePlan = false;

    // Query String Configuration
    protected $queryString = [
        'genderPref' => ['except' => ''],
        'ageFrom' => ['except' => 24],
        'ageTo' => ['except' => 32],
        'state' => ['except' => []],
        'city' => ['except' => []],
        'education' => ['except' => []],
        'occupation' => ['except' => []],
        'marital_status' => ['except' => []],
        'mother_tongue' => ['except' => []],
        'sort' => ['except' => 'relevance'],
    ];

    public function mount()
    {
        $user = Auth::user();
        
        // Fetch membership
        $membership = DB::table('user_memberships')
            ->join('memberships', 'user_memberships.membership_id', '=', 'memberships.id')
            ->where('user_memberships.user_id', $user->id)
            ->where('user_memberships.is_active', 1)
            ->select('memberships.name')
            ->first();
            
        $this->isFreePlan = !$membership || $membership->name === 'Free';
        
        // Set default gender preference logic from PageController
        if (!request()->has('gender_pref')) {
            $this->genderPref = $user->gender === 'male' ? 'female' : 'male';
        } else {
            $this->genderPref = request('gender_pref');
        }
    }

    public function toggleFilters()
    {
        $this->showFilters = !$this->showFilters;
    }

    public function updatedState()
    {
        // Reset city when state changes
        $this->city = [];
        // We could verify if selected cities belong to selected states, but for now just keeping selection or clearing could count
        // Just keeping it simple
        $this->resetPage();
    }

    // Actions
    public function sendInterest($userId)
    {
        if ($this->isFreePlan) {
            session()->flash('error', 'You cannot send interest with a Free Plan. Upgrade your membership to connect with other members.');
            return;
        }

        $targetUser = User::find($userId);
        if ($targetUser) {
            // Check if interest already exists
            $existing = DB::table('user_interests')
                ->where('sender_id', Auth::id())
                ->where('receiver_id', $userId)
                ->exists();
                
            if (!$existing) {
                DB::table('user_interests')->insert([
                    'sender_id' => Auth::id(),
                    'receiver_id' => $userId,
                    'status' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                // Create notification
                // Assuming CreateNotifications trait usage or direct DB insert
                DB::table('notifications')->insert([
                    'user_id' => $userId,
                    'type' => 'interest_received',
                    'data' => json_encode([
                        'message' => Auth::user()->full_name . ' sent you an interest.',
                        'sender_id' => Auth::id(),
                        'sender_name' => Auth::user()->full_name,
                        'sender_image' => Auth::user()->profile_image,
                    ]),
                    'is_read' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            
            // Log activity
            try {
                DB::table('user_activities')->insert([
                    'user_id' => Auth::id(),
                    'activity' => 'Sent interest to ' . $targetUser->full_name . '.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } catch (\Exception $e) {
                // Table might not exist
            }
            
            // Dispatch browser event for UI feedback
            $this->dispatch('interest-sent', userId: $userId);
        }
    }

    public function toggleShortlist($userId)
    {
        $user = Auth::user();
        
        $exists = DB::table('user_shortlists')
            ->where('user_id', $user->id)
            ->where('shortlisted_user_id', $userId)
            ->exists();
            
        if ($exists) {
            DB::table('user_shortlists')
                ->where('user_id', $user->id)
                ->where('shortlisted_user_id', $userId)
                ->delete();
            $isShortlisted = false;
        } else {
            DB::table('user_shortlists')->insert([
                'user_id' => $user->id,
                'shortlisted_user_id' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $isShortlisted = true;
        }
        
        // Log activity
        try {
            DB::table('user_activities')->insert([
                'user_id' => Auth::id(),
                'activity' => ($isShortlisted ? 'Added ' : 'Removed ') . User::find($userId)->full_name . ($isShortlisted ? ' to' : ' from') . ' shortlist.',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Table might not exist
        }
        
        $this->dispatch('shortlist-updated', userId: $userId, isShortlisted: $isShortlisted);
    }

    public function render()
    {
        $user = Auth::user();
        $query = User::query()
            ->where('id', '!=', $user->id)
            ->where('role', '!=', 'admin');

        // Apply Filters
        if ($this->genderPref) {
            $query->where('gender', $this->genderPref);
        }

        if ($this->ageFrom) {
            $query->where(function($q) {
                $q->whereRaw('TIMESTAMPDIFF(YEAR, dob, CURDATE()) >= ?', [$this->ageFrom])
                  ->orWhereNull('dob');
            });
        }
        if ($this->ageTo) {
            $query->where(function($q) {
                $q->whereRaw('TIMESTAMPDIFF(YEAR, dob, CURDATE()) <= ?', [$this->ageTo])
                  ->orWhereNull('dob');
            });
        }

        if (!empty($this->state)) {
            $query->where(function ($q) {
                foreach ($this->state as $state) {
                    $q->orWhere('state', 'like', '%' . trim($state) . '%');
                }
            });
        }

        if (!empty($this->education)) {
            $query->whereIn('highest_education', $this->education);
        }

        if (!empty($this->occupation)) {
            $query->whereIn('occupation', $this->occupation);
        }

        if (!empty($this->marital_status)) {
            $query->whereIn('marital_status', $this->marital_status);
        }

        if (!empty($this->city)) {
            $query->whereIn('city', $this->city);
        }

        if (!empty($this->mother_tongue)) {
            $query->whereIn('mother_tongue', $this->mother_tongue);
        }

        // Get Data
        $allUsers = $query->get();

        // Process Compatibility & Metadata
        $allUsers->each(function ($match) use ($user) {
            // Calculate compatibility
            $match->matchPercentage = $user->calculateCompatibility($match);
            
            // Location string
            $match->location = trim(($match->city ?? '') . ($match->city && $match->country ? ', ' : '') . ($match->country ?? ''));

            // Shortlist Status
            $match->isShortlisted = DB::table('user_shortlists')
                ->where('user_id', $user->id)
                ->where('shortlisted_user_id', $match->id)
                ->exists();
        });

        // Sort Data
        $sortedUsers = $allUsers;
        if ($this->sort === 'newest') {
            $sortedUsers = $allUsers->sortByDesc('created_at');
        } elseif ($this->sort === 'age_low') {
            $sortedUsers = $allUsers->sortBy('age');
        } elseif ($this->sort === 'age_high') {
            $sortedUsers = $allUsers->sortByDesc('age');
        } else {
            // Default 'relevance'
            $sortedUsers = $allUsers->sortByDesc('matchPercentage');
        }

        // Paginate manually
        $page = $this->getPage(); // Get current page from Livewire trait
        $perPage = 12;
        $items = $sortedUsers->forPage($page, $perPage);
        
        $users = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $sortedUsers->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        // Fetch Master Data for Filters
        $states = DB::table('state_master')->where('status', 'active')->orderBy('name')->get();
        
        // Dynamic Cities based on selected states
        $cities = collect();
        if (!empty($this->state)) {
            $stateIds = DB::table('state_master')
                ->whereIn('name', $this->state)
                ->pluck('id');
                
            if($stateIds->isNotEmpty()) {
                $cities = DB::table('city_master')
                    ->whereIn('state_id', $stateIds)
                    ->where('status', 'active')
                    ->orderBy('name')
                    ->get();
            }
        }

        $masterData = [
            'states' => $states,
            'cities' => $cities,
            'occupations' => DB::table('occupation_master')->where('status', 'active')->get(),
            'motherTongues' => DB::table('mothertongue_master')->where('status', 'active')->get(),
            'maritalStatuses' => ['Unmarried', 'Divorced', 'Widowed', 'Awaiting Divorce'],
            'highestQualifications' => DB::table('highest_qualification_master')->where('status', 'active')->get(),
        ];

        return view('livewire.match-list', [
            'users' => $users,
        ] + $masterData)
        ->layout('layouts.user')
        ->title('Advanced Search & Filter - GoswamiSangath');
    }
}
