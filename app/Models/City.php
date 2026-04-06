<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $table = 'city_master';

    protected $fillable = [
        'name',
        'city_master', // Column name used in SettingsController
        'state_id',
        'status',
        'is_visible',
    ];

    /**
     * Accessor to get city name (handles both 'name' and 'city_master' columns)
     */
    public function getNameAttribute()
    {
        return $this->attributes['city_master'] ?? $this->attributes['name'] ?? null;
    }

    /**
     * Get the state that owns the city.
     */
    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }
}

