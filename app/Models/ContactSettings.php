<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class ContactSettings extends Model
{
    use HasTranslations;

    protected $table = 'contact_settings';

    protected $fillable = [
        'email',
        'phone',
        'vat',
        'address_en',
        'address_cs',
        'address_de',
        'socials',
    ];

    protected $casts = [
        'socials' => 'array',
    ];

    protected $appends = ['address'];

    public function getAddressAttribute()
    {
        return $this->getTranslatedAttribute('address');
    }

    public static function current()
    {
        return self::first() ?? self::create([
            'email' => 'info@apartmanstratos.cz',
            'phone' => '+420 732 558 978',
            'vat' => '21681902',
            'address_en' => "Ramzová 345\nOstružná, 788 25\nCzech Republic",
            'address_cs' => "Ramzová 345\nOstružná, 788 25\nČeská republika",
            'address_de' => "Ramzová 345\nOstružná, 788 25\nTschechische Republik",
            'socials' => [
                ['platform' => 'FB', 'name' => 'Facebook', 'url' => 'https://www.facebook.com/apartmanstratos'],
                ['platform' => 'IG', 'name' => 'Instagram', 'url' => 'https://www.instagram.com/apartmanstratos/'],
            ],
        ]);
    }
}
