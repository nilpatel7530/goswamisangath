<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use HasFactory;

    protected $table = 'education_master';

    protected $fillable = [
        'name',
        'highest_qualification_id',
        'status',
        'is_visible',
    ];

    /**
     * Get the highest qualification that owns the education.
     */
    public function highestQualification()
    {
        return $this->belongsTo(HighestQualification::class, 'highest_qualification_id');
    }
}

