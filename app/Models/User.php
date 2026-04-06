<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::created(function (User $user) {
            // Assign free plan to newly created users
            try {
                $membershipService = app(\App\Services\MembershipService::class);
                $membershipService->assignFreePlan($user);
            } catch (\Exception $e) {
                \Log::error('Failed to assign free plan: ' . $e->getMessage());
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'full_name',
        'email',
        'password',
        'profile_image',
        'gender',
        'height',
        'weight',
        'dob',
        'birth_time',
        'birth_place',
        'raashi',
        'naadi',
        'marital_status',
        'mother_tongue',
        'physically_handicap',
        'diet',
        'languages_known',
        'additional_info',
        'highest_education', // VARCHAR field, not foreign key
        'education_details', // VARCHAR field, not foreign key
        'employed_in',
        'occupation', // VARCHAR field, not foreign key
        'annual_income',
        'country', // VARCHAR field, not foreign key
        'state', // VARCHAR field, not foreign key
        'city', // VARCHAR field, not foreign key
        'highest_education_id',
        'education_id',
        'occupation_id',
        'country_id',
        'state_id',
        'city_id',
        'residential_address',
        'residential_country',
        'residential_state',
        'residential_city',
        'working_address',
        'working_country',
        'working_state',
        'working_city',
        'mobile_number',
        'google_id',
        'role',
        'id_proof',
        'verification_status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'dob' => 'date',
        ];
    }

    /**
     * Retrieve the model for bound value.
     *
     * @param  mixed  $value
     * @param  string|null  $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        // Try to decrypt the value first
        try {
            $decrypted = decrypt($value);
            return $this->where($field ?? $this->getRouteKeyName(), $decrypted)->first();
        } catch (\Exception $e) {
            // If decryption fails, try as plain ID (for backward compatibility)
            return $this->where($field ?? $this->getRouteKeyName(), $value)->first();
        }
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @param  string|null  $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveChildRouteBinding($childType, $value, $field)
    {
        try {
            $decrypted = decrypt($value);
            return parent::resolveChildRouteBinding($childType, $decrypted, $field);
        } catch (\Exception $e) {
            return parent::resolveChildRouteBinding($childType, $value, $field);
        }
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKey()
    {
        return encrypt($this->getAttribute($this->getRouteKeyName()));
    }

    /**
     * Get the user's memberships.
     */
    public function memberships(): BelongsToMany
    {
        return $this->belongsToMany(Membership::class, 'user_memberships')
            ->withPivot('visits_used', 'interests_used', 'interest_limit', 'is_active', 'purchased_at', 'expires_at')
            ->withTimestamps();
    }

    /**
     * Get the user's active membership.
     */
    public function activeMembership()
    {
        return $this->belongsToMany(Membership::class, 'user_memberships')
            ->wherePivot('is_active', 1)
            ->withPivot('visits_used', 'interests_used', 'interest_limit', 'is_active', 'purchased_at', 'expires_at')
            ->withTimestamps()
            ->first();
    }
    /**
     * Get the hobbies of the user.
     */
    public function hobbies(): BelongsToMany
    {
        return $this->belongsToMany(Hobby::class);
    }

    /**
     * Get the user's age.
     */
    public function getAgeAttribute()
    {
        return $this->dob ? \Carbon\Carbon::parse($this->dob)->age : 'N/A';
    }

    /**
     * Calculate compatibility percentage between this user and another user.
     * Logic: City (20), Education (25), Mother Tongue (25)
     */
    public function calculateCompatibility(User $otherUser)
    {
        $matchScore = 0;
        $maxScore = 0;

        // City match (20 points)
        if ($this->city && $otherUser->city) {
            $maxScore += 20;
            if (trim(strtolower($this->city)) === trim(strtolower($otherUser->city))) {
                $matchScore += 20;
            }
        }

        // Education match (25 points)
        if ($this->highest_education && $otherUser->highest_education) {
            $maxScore += 25;
            if (trim(strtolower($this->highest_education)) === trim(strtolower($otherUser->highest_education))) {
                $matchScore += 25;
            }
        }

        // Mother tongue match (25 points)
        if ($this->mother_tongue && $otherUser->mother_tongue) {
            $maxScore += 25;
            if (trim(strtolower($this->mother_tongue)) === trim(strtolower($otherUser->mother_tongue))) {
                $matchScore += 25;
            }
        }

        // Calculate percentage
        if ($maxScore > 0) {
            $percentage = round(($matchScore / $maxScore) * 100);
            // Ensure minimum of 40% and maximum of 100%
            return max(40, min(100, $percentage));
        }

        // If no criteria available, default to 50%
        return 50;
    }
}