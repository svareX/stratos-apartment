<?php

namespace Database\Seeders;

use App\Models\ApartmentPackage;
use Illuminate\Database\Seeder;

class ApartmentPackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'apartment_id' => 1,
                'name_en' => 'Ski & Relax',
                'name_cs' => 'Lyže a relax',
                'name_de' => 'Ski & Relax',
                'price' => 4500,
                'icon' => '⛷️',
                'features' => [
                    ['en' => '2x daily skipass', 'cs' => '2x celodenní skipas', 'de' => '2x Tages-Skipass'],
                    ['en' => 'Evening sauna access', 'cs' => 'Večerní přístup do sauny', 'de' => 'Abendlicher Saunazugang'],
                    ['en' => 'Late checkout', 'cs' => 'Pozdní odjezd (late checkout)', 'de' => 'Später Check-out'],
                ],
            ],
            [
                'apartment_id' => 1,
                'name_en' => 'Active Weekend',
                'name_cs' => 'Aktivní víkend',
                'name_de' => 'Aktivwochenende',
                'price' => 3800,
                'icon' => '🥾',
                'features' => [
                    ['en' => 'Detailed hiking maps', 'cs' => 'Detailní turistické mapy', 'de' => 'Detaillierte Wanderkarten'],
                    ['en' => 'Energy snack pack', 'cs' => 'Energetický balíček na cestu', 'de' => 'Energie-Snack-Paket'],
                    ['en' => 'Free parking', 'cs' => 'Parkování zdarma', 'de' => 'Kostenloser Parkplatz'],
                ],
            ],
            [
                'apartment_id' => 1,
                'name_en' => 'Family Winter',
                'name_cs' => 'Rodinná zima',
                'name_de' => 'Familienwinter',
                'price' => 5200,
                'icon' => '⛄',
                'features' => [
                    ['en' => 'Sleds included', 'cs' => 'Sáňky v ceně', 'de' => 'Schlitten inklusive'],
                    ['en' => 'Hot chocolate kit', 'cs' => 'Horká čokoláda na večer', 'de' => 'Heiße Schokolade Kit'],
                    ['en' => 'Board games collection', 'cs' => 'Sada deskových her', 'de' => 'Brettspielsammlung'],
                ],
            ],
            [
                'apartment_id' => 2,
                'name_en' => 'Spa & Stay',
                'name_cs' => 'Spa & Stay',
                'name_de' => 'Spa & Stay',
                'price' => 3900,
                'icon' => '🛁',
                'features' => [
                    ['en' => '2x entrance to Therme Laa', 'cs' => '2x vstup do Therme Laa', 'de' => '2x Eintritt in die Therme Laa'],
                    ['en' => 'Welcome wine + candles', 'cs' => 'Uvítací víno + svíčky', 'de' => 'Willkommenswein + Kerzen'],
                    ['en' => 'Late checkout', 'cs' => 'Pozdní odjezd (late checkout)', 'de' => 'Später Check-out'],
                ],
            ],
            [
                'apartment_id' => 2,
                'name_en' => 'Wine Weekend',
                'name_cs' => 'Vinařský víkend',
                'name_de' => 'Weinwochenende',
                'price' => 4100,
                'icon' => '🍇',
                'features' => [
                    ['en' => 'Wine tasting at Bauer', 'cs' => 'Degustace ve Vinařství Bauer', 'de' => 'Weinprobe bei Bauer'],
                    ['en' => 'Local cheese platter', 'cs' => 'Talíř místních sýrů', 'de' => 'Lokale Käseplatte'],
                    ['en' => 'Breakfast ingredients', 'cs' => 'Suroviny na snídani', 'de' => 'Frühstückszutaten'],
                ],
            ],
            [
                'apartment_id' => 2,
                'name_en' => 'Romantic Escape',
                'name_cs' => 'Romantický únik',
                'name_de' => 'Romantische Flucht',
                'price' => 4600,
                'icon' => '🥂',
                'features' => [
                    ['en' => 'Prosecco on arrival', 'cs' => 'Prosecco při příjezdu', 'de' => 'Prosecco bei Ankunft'],
                    ['en' => 'Rose petals', 'cs' => 'Lístky růží', 'de' => 'Rosenblätter'],
                    ['en' => 'Private sauna access', 'cs' => 'Privátní přístup do sauny', 'de' => 'Privater Saunazugang'],
                ],
            ],
        ];

        foreach ($packages as $package) {
            ApartmentPackage::create($package);
        }
    }
}