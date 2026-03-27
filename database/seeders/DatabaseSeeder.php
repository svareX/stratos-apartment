<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\ApartmentPackageSeeder;
use Database\Seeders\ContactSettingsSeeder;
use Database\Seeders\PlaceSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Stepan Svarc',
            'email' => 'svarc@crowdigital.cz',
            'password' => bcrypt('123'),
        ]);

        $this->call([
            ApartmentSeeder::class,
            FAQSeeder::class,
            PlaceSeeder::class,
            HikeSeeder::class,
            ApartmentPackageSeeder::class,
            ContactSettingsSeeder::class,
        ]);
    }
}
