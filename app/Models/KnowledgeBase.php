<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KnowledgeBase extends Model
{
    protected $guarded = [];

    /**
     * The attribute casting definitions.
     *
     * @var array<string,string>
     */
    protected $casts = [
        'embedding' => 'array',
    ];
}
