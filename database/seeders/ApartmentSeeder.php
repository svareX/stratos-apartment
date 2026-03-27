<?php

namespace Database\Seeders;

use App\Models\Apartment;
use Illuminate\Database\Seeder;

class ApartmentSeeder extends Seeder
{
    public function run(): void
    {
        $apartments = [
            [
                'id' => 1,
                'name' => 'Ramzová / Jeseníky',
                'description' => 'Your base in the heart of the Jeseníky Mountains. Apartment for 2–6 people right in the Ramzová resort. The ski slope is right outside the door, Priessnitz Spa is 13 km away; hiking trails start right at your doorstep.',
                'address' => 'Ramzová, 788 11 Ostružná, Czechia',
                'capacity' => 6,
                'amenities' => [
                    'Free WiFi' => 'Yes',
                    'Free private parking' => 'Yes',
                    'Pet friendly' => 'Yes',
                    'Fully equipped kitchen' => 'Yes',
                    'Ski-to-door access' => 'Yes',
                    'Mountain view' => 'Yes',
                    'Flat-screen TV' => 'Yes',
                ],
                'base_price' => 1500.00,
                'cleaning_fee' => 480.00,
                'days_for_cleaning_fee' => 4,
                'active' => true,
                'type' => 'mountains',
                'slug' => 'apartment-stratos-ramzova',
                'tags' => [
                    ['value' => 'Mountains'],
                    ['value' => 'Skiing'],
                    ['value' => 'Trails'],
                    ['value' => 'Families with children'],
                ],
            ],
            [
                'id' => 2,
                'name' => 'Apartment Stratos Laa',
                'description' => 'Wellness escape steps from Therme Laa. Apartment for 2 – 4 people in Laa an der Thaya. Therme Laa is 5 minutes away, Vienna is an hour’s drive, and vineyards are visible from the window.',
                'address' => 'Feldstraße 4, 2136 Laa an der Thaya, Rakousko',
                'capacity' => 4,
                'amenities' => [
                    'Free WiFi' => 'Yes',
                    'Free private parking' => 'Yes',
                    'Pet friendly' => 'Yes',
                    'Fully equipped kitchen' => 'Yes',
                    'Air conditioning' => 'Yes',
                    'Flat-screen TV' => 'Yes',
                ],
                'base_price' => 1800.00,
                'cleaning_fee' => 600.00,
                'days_for_cleaning_fee' => 3,
                'active' => true,
                'type' => 'vineyards',
                'slug' => 'apartment-stratos-laa',
                'tags' => [
                    ['value' => 'Wellness'],
                    ['value' => 'Romantic'],
                    ['value' => 'Vineyards'],
                    ['value' => 'Couples'],
                ],
            ],
        ];

        foreach ($apartments as $apartment) {
            Apartment::updateOrCreate(
                ['id' => $apartment['id']],
                $apartment
            );
        }
    }
}
