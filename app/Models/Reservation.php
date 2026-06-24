<?php

namespace App\Models;

/**
 * @property-read Apartment|null $apartment
 * @property-read User|null $user
 * @property-read ApartmentPackage|null $apartmentPackage
 * @property int $total
 * @property int $id
 * @property Carbon|null $check_in
 * @property Carbon|null $check_out
 * @property float|null $price
 * @property float|null $package_price
 */

use App\Enums\ReservationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

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
        'status' => ReservationStatus::class,
        'package_price' => 'decimal:2',
        'external_last_synced_at' => 'datetime',
    ];

    public function apartment(): BelongsTo
    {
        return $this->belongsTo(Apartment::class);
    }

    public function apartmentPackage(): BelongsTo
    {
        return $this->belongsTo(ApartmentPackage::class, 'apartment_package_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
