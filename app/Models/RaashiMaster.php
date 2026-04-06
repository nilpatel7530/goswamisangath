<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RaashiMaster extends Model
{
    use HasFactory;

    protected $table = 'raashi_master';

    protected $fillable = [
        'name',
        'status',
    ];
}
