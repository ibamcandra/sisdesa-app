<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    //
    protected $fillable = [
        'name',
        'location',
        'type',
        'image_path',
        'url',
        'script_code',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
