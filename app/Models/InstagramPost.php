<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstagramPost extends Model
{
    protected $fillable = [
        'instagram_id',
        'image_url',
        'url',
        'caption',
        'posted_at',
    ];

    protected $casts = [
        'posted_at' => 'datetime',
    ];
}
