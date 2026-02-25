<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apartment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'address',
        'capacity',
        'amenities',
        'photos',
        'base_price',
        'active',
    ];

    protected $casts = [
        'amenities' => 'array',
        'photos' => 'array',
        'active' => 'boolean',
        'base_price' => 'decimal:2',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
