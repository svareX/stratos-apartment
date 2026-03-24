<?php

namespace Database\Seeders;

use App\Models\FrequentlyAskedQuestion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FAQSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            [
                'question_cs' => 'Jak probíhá check-in a check-out?',
                'question_en' => 'How does check-in and check-out work?',
                'question_de' => 'Wie funktioniert der Check-in und Check-out?',
                'answer_cs' => 'Příjezd je možný od 15:00, odjezd prosíme do 10:00. U obou apartmánů využíváme systém self-check-in pomocí kódového zámku, instrukce obdržíte před příjezdem.',
                'answer_en' => 'Check-in is available from 3:00 PM, and we kindly ask you to check out by 10:00 AM. Both apartments use a self-check-in system with a code lock; instructions will be sent before arrival.',
                'answer_de' => 'Der Check-in ist ab 15:00 Uhr möglich, der Check-out bis 10:00 Uhr. Beide Apartments verfügen über ein Self-Check-in-System mit Code-Schloss; die Anleitung erhalten Sie vor der Anreise.',
                'position' => 1,
            ],
            [
                'question_cs' => 'Jsou apartmány vhodné pro rodiny a psy?',
                'question_en' => 'Are the apartments suitable for families and dogs?',
                'question_de' => 'Sind die Apartments für Familien und Hunde geeignet?',
                'answer_cs' => 'Ano, jsme dog-friendly! Vaši čtyřnozí přátelé jsou vítáni bez příplatku. Pro rodiny máme připravené pohodlné zázemí, v Jeseníkách oceníte blízkost sjezdovky a v Laa zase dětský svět v termálech.',
                'answer_en' => 'Yes, we are dog-friendly! Your four-legged friends are welcome at no extra charge. We have comfortable facilities for families; in Jeseníky you will love the nearby ski slopes, and in Laa, the children\'s world at the thermal baths.',
                'answer_de' => 'Ja, wir sind hundefreundlich! Ihre vierbeinigen Freunde sind ohne Aufpreis willkommen. Für Familien bieten wir eine komfortable Ausstattung; in Jeseníky schätzen Sie die Nähe zur Piste, in Laa die Kinderwelt in der Therme.',
                'position' => 2,
            ],
            [
                'question_cs' => 'Jak daleko jsou termální lázně v Laa an der Thaya?',
                'question_en' => 'How far are the thermal baths in Laa an der Thaya?',
                'question_de' => 'Wie weit ist es zur Therme Laa an der Thaya?',
                'answer_cs' => 'Apartmán v Laa se nachází jen 5 minut chůze od vyhlášených termálních lázní Therme Laa. V okolí najdete také malebné vinice a místní pivovar Hubertus.',
                'answer_en' => 'The apartment in Laa is located just a 5-minute walk from the famous Therme Laa. In the surroundings, you will also find picturesque vineyards and the local Hubertus brewery.',
                'answer_de' => 'Das Apartment in Laa liegt nur 5 Gehminuten von der berühmten Therme Laa entfernt. In der Umgebung finden Sie auch malerische Weinberge und die örtliche Hubertus-Brauerei.',
                'position' => 3,
            ],
            [
                'question_cs' => 'Jaké aktivity nabízí okolí v Jeseníkách?',
                'question_en' => 'What activities does the Jeseníky area offer?',
                'question_de' => 'Welche Aktivitäten bietet die Umgebung in Jeseníky?',
                'answer_cs' => 'Náš apartmán v Ramzové je přímo u sjezdovky. V zimě ideální pro lyžaře, v létě pro bikery a turisty. Lanovka na Šerák je v docházkové vzdálenosti a Priessnitzovy lázně v Jeseníku jsou jen 13 km daleko.',
                'answer_en' => 'Our apartment in Ramzová is right by the ski slope. Ideal for skiers in winter, and for bikers and hikers in summer. The cable car to Šerák is within walking distance, and the Priessnitz Spa in Jeseník is only 13 km away.',
                'answer_de' => 'Unser Apartment in Ramzová liegt direkt an der Skipiste. Ideal für Skifahrer im Winter sowie für Biker und Wanderer im Sommer. Die Seilbahn zum Šerák ist fußläufig erreichbar, und das Priessnitz-Heilbad in Jeseník ist nur 13 km entfernt.',
                'position' => 4,
            ],
            [
                'question_cs' => 'Je u apartmánů k dispozici parkování?',
                'question_en' => 'Is parking available at the apartments?',
                'question_de' => 'Gibt es Parkplätze bei den Apartments?',
                'answer_cs' => 'Ano, u obou apartmánů máte vyhrazené soukromé parkovací stání zdarma přímo u objektu.',
                'answer_en' => 'Yes, for both apartments, you have a reserved private parking space for free directly at the property.',
                'answer_de' => 'Ja, für beide Apartments steht Ihnen ein kostenloser privater Parkplatz direkt am Gebäude zur Verfügung.',
                'position' => 5,
            ],
        ];

        foreach ($faqs as $faq) {
            FrequentlyAskedQuestion::create($faq);
        }
    }
}
