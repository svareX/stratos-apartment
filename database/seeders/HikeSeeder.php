<?php

namespace Database\Seeders;

use App\Enums\HikeDifficulty;
use App\Models\Hike;
use Illuminate\Database\Seeder;

class HikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hikes = [
            [
                'apartment_id' => 1,
                'name_en' => 'Rejvíz Circuit',
                'name_cs' => 'Okruh Rejvíz',
                'name_de' => 'Rejvíz-Rundweg',
                'distance_tx_en' => '↑ 150 m',
                'distance_tx_cs' => '↑ 150 m',
                'distance_tx_de' => '↑ 150 m',
                'description_en' => 'A beautiful and easy walk suitable for everyone.',
                'description_cs' => 'Krásná a nenáročná procházka vhodná pro všechny.',
                'description_de' => 'Ein schöner und leichter Spaziergang, der für alle geeignet ist.',
                'difficulty' => HikeDifficulty::Easy->value,
                'length' => 8.00,
                'is_for_families' => true,
            ],
            [
                'apartment_id' => 1,
                'name_en' => 'The Jeseníky Ridge',
                'name_cs' => 'Hřeben Jeseníků',
                'name_de' => 'Jeseníky-Kamm',
                'distance_tx_en' => '↑ 150 m',
                'distance_tx_cs' => '↑ 150 m',
                'distance_tx_de' => '↑ 150 m',
                'description_en' => 'Enjoy stunning views along the main ridge.',
                'description_cs' => 'Užijte si úžasné výhledy podél hlavního hřebene.',
                'description_de' => 'Genießen Sie atemberaubende Aussichten entlang des Hauptkamms.',
                'difficulty' => HikeDifficulty::Medium->value,
                'length' => 8.00,
                'is_for_families' => true,
            ],
            [
                'apartment_id' => 1,
                'name_en' => 'Praděd from Ramzová',
                'name_cs' => 'Praděd z Ramzové',
                'name_de' => 'Praděd von Ramzová',
                'distance_tx_en' => '↑ 150 m',
                'distance_tx_cs' => '↑ 150 m',
                'distance_tx_de' => '↑ 150 m',
                'description_en' => 'A challenging hike to the highest peak in the area.',
                'description_cs' => 'Náročný výšlap na nejvyšší vrchol v okolí.',
                'description_de' => 'Eine anspruchsvolle Wanderung zum höchsten Gipfel der Region.',
                'difficulty' => HikeDifficulty::Hard->value,
                'length' => 8.00,
                'is_for_families' => true,
            ],
        ];

        foreach ($hikes as $hike) {
            Hike::create($hike);
        }
    }
}
