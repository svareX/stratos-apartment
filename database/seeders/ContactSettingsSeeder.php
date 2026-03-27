<?php

namespace Database\Seeders;

use App\Models\ContactSettings;
use Illuminate\Database\Seeder;

class ContactSettingsSeeder extends Seeder
{
    public function run(): void
    {
        ContactSettings::current();
    }
}
