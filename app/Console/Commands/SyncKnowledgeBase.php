<?php

namespace App\Console\Commands;

use App\Models\Apartment;
use App\Models\ContactSettings;
use App\Models\FrequentlyAskedQuestion;
use App\Models\Hike;
use App\Models\HomepageSettings;
use App\Models\KnowledgeBase;
use App\Models\Place;
use Illuminate\Console\Command;
use Laravel\Ai\Embeddings;
use Laravel\Ai\Enums\Lab;

class SyncKnowledgeBase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'knowledge:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync the knowledge base with the latest apartment data.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Zahajuji kompletní synchronizaci znalostí...');

        // 1. KONTAKTY A FIRMA
        $contact = ContactSettings::current();
        $contactText = "KONTAKTNÍ INFORMACE:\n";
        $contactText .= "E-mail: {$contact->email}\nTelefon: {$contact->phone}\nDIČ/VAT: {$contact->vat}\n";
        $contactText .= 'Adresa: '.str_replace("\n", ', ', $contact->address_cs)."\n";
        $contactText .= 'Sociální sítě: '.collect($contact->socials)->pluck('url')->implode(', ');
        $this->storeInKb($contactText, 'contact', $contact->id);

        // 2. APARTMÁNY, CENY A BALÍČKY
        $apartments = Apartment::where('active', true)->with(['packages'])->get();
        foreach ($apartments as $apt) {
            $aptText = "=== INFORMACE O KONKRÉTNÍM UBYTOVÁNÍ ===\n";
            $aptText .= "NÁZEV APARTMÁNU: {$apt->name}\n";
            $aptText .= "PŘESNÁ ADRESA PRO NAVIGACI: {$apt->address}\n"; // Zde je tvůj sloupec address
            $aptText .= "Kapacita: {$apt->capacity} osoby\n";
            $aptText .= "Cena: {$apt->base_price} Kč/noc\n";
            $aptText .= "Popis: {$apt->description}\n";
            $aptText .= "Poplatek za úklid: {$apt->cleaning_fee} Kč (účtován pokud je délka pobytu kratší než {$apt->days_for_cleaning_fee} dní)\n";
            $aptText .= 'Vybavení: '.(is_array($apt->amenities) ? implode(', ', $apt->amenities) : '')."\n";
            $tags = is_array($apt->tags) ? array_filter($apt->tags, 'is_scalar') : [];
            $aptText .= 'Tagy: '.implode(', ', $tags)."\n";

            foreach ($apt->packages()->get() as $pkg) {
                /** @var \App\Models\ApartmentPackage $pkg */
                $features = is_array($pkg->translated_features) ? implode(', ', $pkg->translated_features) : (is_array($pkg->features) ? implode(', ', $pkg->features) : '');
                $aptText .= "BALÍČEK: {$pkg->name_cs} — cena: {$pkg->price} Kč. Obsahuje: {$features}\n";

                $pkgDescription = $pkg->description_cs ?? $pkg->description ?? '';
                $pkgText = "=== BALÍČEK APARTMÁNU ===\n";
                $pkgText .= "NÁZEV: {$pkg->name_cs}\n";
                $pkgText .= "CENA: {$pkg->price} Kč\n";
                if (! empty($pkgDescription)) {
                    $pkgText .= "POPIS: {$pkgDescription}\n";
                }
                $pkgText .= "OBSAHUJE: {$features}\n";
                $pkgText .= "PATRÍ K APARTMÁNU: {$apt->name} (ID: {$apt->id})\n";

                $this->storeInKb($pkgText, 'apartment_package', $pkg->id);
            }

            $this->storeInKb($aptText, 'apartment', $apt->id);

            // 3. OBSAZENOST (Kalendář pro AI)
            $res = $apt->reservations()->where('check_out', '>=', now())->get();
            if ($res->isNotEmpty()) {
                $occText = "TERMÍNY OBSAZENOSTI PRO {$apt->name}:\n";
                foreach ($res as $r) {
                    /** @var \App\Models\Reservation $r */
                    $occText .= "- Obsazeno: {$r->check_in->format('d.m.Y')} až {$r->check_out->format('d.m.Y')}\n";
                }
                $this->storeInKb($occText, 'occupancy', $apt->id);
            }
        }

        // 4. FAQ
        foreach (FrequentlyAskedQuestion::where('is_active', true)->get() as $faq) {
            $this->storeInKb("ČASTÝ DOTAZ: {$faq->question_cs}\nODPOVĚĎ: {$faq->answer_cs}", 'faq', $faq->id);
        }

        // 5. VÝLETY A OKOLÍ
        foreach (Hike::all() as $hike) {
            $hikeText = "VÝLET/TRASA: {$hike->name_cs}\nPopis: {$hike->description_cs}\n";
            $hikeText .= "Délka: {$hike->length} km, Obtížnost: {$hike->difficulty->value}\n";
            $hikeText .= $hike->is_for_families ? 'Vhodné pro rodiny s dětmi.' : 'Náročnější trasa.';
            $this->storeInKb($hikeText, 'hike', $hike->id);
        }

        foreach (Place::all() as $place) {
            $this->storeInKb("ZAJÍMAVÉ MÍSTO: {$place->name_cs}\nPopis: {$place->description_cs}\nVzdálenost: {$place->distance_text_cs}", 'place', $place->id);
        }

        // 6. HOMEPAGE SLOGANY
        $home = HomepageSettings::first();
        if ($home && $home->hero_slides) {
            $slidesText = "MARKETINGOVÉ SLOGANY A INFO:\n";
            foreach ($home->hero_slides as $slide) {
                $slidesText .= '- '.($slide['title_cs'] ?? '').': '.($slide['subtitle_cs'] ?? '')."\n";
            }
            $this->storeInKb($slidesText, 'homepage', $home->id);
        }

        $this->info('Všechna data byla úspěšně nahrána do hlavy bota!');
    }

    private function storeInKb(string $content, string $type, $id)
    {
        $embedding = Embeddings::for([$content])
            ->dimensions(768)
            ->generate(Lab::Gemini, 'gemini-embedding-001');

        KnowledgeBase::updateOrCreate(
            ['source_type' => $type, 'source_id' => $id],
            [
                'content' => $content,
                'embedding' => $embedding->embeddings[0],
            ]
        );
    }
}
