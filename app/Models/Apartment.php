<?php

namespace App\Models;

use App\Enums\ApartmentType;
use App\Models\ApartmentPackage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Apartment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'address',
        'capacity',
        'amenities',
        'slug',
        'type',
        'tags',
        'base_price',
        'active',
    ];

    protected $casts = [
        'amenities' => 'array',
        'tags' => 'array',
        'active' => 'boolean',
        'base_price' => 'decimal:2',
        'type' => ApartmentType::class,
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    public function photosMain()
    {
        return $this->hasMany(Photo::class)->where('is_main', true)->orderBy('position');
    }

    public function photosOther()
    {
        return $this->hasMany(Photo::class)->where('is_main', false)->orderBy('position');
    }

    public function packages()
    {
        return $this->hasMany(ApartmentPackage::class);
    }

    protected static function booted()
    {
        static::creating(function ($apartment) {
            if (empty($apartment->slug) && ! empty($apartment->name)) {
                $apartment->slug = Str::slug($apartment->name);
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
