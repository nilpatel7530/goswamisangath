<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HighestQualification extends Model
{
    use HasFactory;

    protected $table = 'highest_qualification_master';

    protected $fillable = [
        'name',
        'status',
        'is_visible',
    ];

    /**
     * Get the education details for the highest qualification.
     */
    public function educations()
    {
        return $this->hasMany(Education::class, 'highest_qualification_id');
    }
}

