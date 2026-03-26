<?php

namespace Database\Seeders;

use App\Models\Apartment;
use Illuminate\Database\Seeder;

class ApartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $apartments = [
            [
                'name' => 'Apartment Stratos Ramzová',
                'slug' => 'apartment-stratos-ramzova',
                'description' => 'Your base in the heart of the Jeseníky Mountains. Apartment for 2–6 people right in the Ramzová resort. The ski slope is right outside the door, Priessnitz Spa is 13 km away; hiking trails start right at your doorstep.',
                'address' => 'Ramzová, 788 11 Ostružná, Czechia',
                'capacity' => 6,
                'base_price' => 1500.00,
                'active' => true,
                'tags' => [
                    'Mountains',
                    'Skiing',
                    'Trails',
                    'Families with children',
                ],
                'amenities' => [
                    'Private kitchen' => 'Yes',
                    'Fast WiFi + Netflix' => 'Yes',
                    'Free parking' => 'Yes',
                    'Skis at the door' => 'Yes',
                    'Dog friendly' => 'Yes',
                ],
            ],
            [
                'name' => 'Apartment Stratos Laa',
                'slug' => 'apartment-stratos-laa',
                'description' => 'Wellness escape steps from Therme Laa. Apartment for 2 – 4 people in Laa an der Thaya. Therme Laa is 5 minutes away, Vienna is an hour’s drive, and vineyards are visible from the window.',
                'address' => 'Feldstraße 4, 2136 Laa an der Thaya, Rakousko',
                'capacity' => 4,
                'base_price' => 1800.00,
                'active' => true,
                'tags' => [
                    'Wellness',
                    'Romantic',
                    'Vineyards',
                    'Couples',
                ],
                'amenities' => [
                    'Private kitchen' => 'Yes',
                    'Fast WiFi + Netflix' => 'Yes',
                    'Free parking' => 'Yes',
                    'Thermals nearby' => 'Yes',
                    'Dog friendly' => 'Yes',
                ],
            ],
        ];

        foreach ($apartments as $apartment) {
            Apartment::create($apartment);
        }
    }
}
