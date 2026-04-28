<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'apartment_id',
        'user_id',
        'check_in',
        'check_out',
        'price',
        'status',
        'booking_source',
        'external_booking_id',
        'apartment_package_id',
        'package_price',
        'external_last_synced_at',
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
        'price' => 'decimal:2',
        'status' => \App\Enums\ReservationStatus::class,
        'package_price' => 'decimal:2',
        'external_last_synced_at' => 'datetime',
    ];

    public function apartment()
    {
        return $this->belongsTo(Apartment::class);
    }

    public function apartmentPackage()
    {
        return $this->belongsTo(ApartmentPackage::class, 'apartment_package_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
