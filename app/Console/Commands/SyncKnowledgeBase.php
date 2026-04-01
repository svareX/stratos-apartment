<?php

namespace App\Console\Commands;

use App\Models\Apartment;
use App\Models\FrequentlyAskedQuestion;
use App\Models\KnowledgeBase;
use App\Models\ContactSettings;
use App\Models\HomepageSettings;
use App\Models\Hike;
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
        $contactText .= "Adresa: " . str_replace("\n", ", ", $contact->address_cs) . "\n";
        $contactText .= "Sociální sítě: " . collect($contact->socials)->pluck('url')->implode(', ');
        $this->storeInKb($contactText, 'contact', $contact->id);

        // 2. APARTMÁNY, CENY A BALÍČKY
        $apartments = Apartment::where('active', true)->with(['packages'])->get();
        foreach ($apartments as $apt) {
            $aptText = "APARTMÁN: {$apt->name}\nTyp: {$apt->type->value}\n";
            $aptText .= "Adresa: {$apt->address}\nKapacita: {$apt->capacity} osoby\n";
            $aptText .= "Základní cena: {$apt->base_price} Kč/noc\nPoplatek za úklid: {$apt->cleaning_fee} Kč\n";
            $aptText .= "Vybavení: " . implode(', ', $apt->amenities ?? []) . "\n";
            $aptText .= "Popis: {$apt->description}\n";

            foreach ($apt->packages as $pkg) {
                $aptText .= "BALÍČEK: {$pkg->name_cs} za {$pkg->price} Kč. Obsahuje: " . implode(', ', $pkg->translated_features) . "\n";
            }
            $this->storeInKb($aptText, 'apartment', $apt->id);

            // 3. OBSAZENOST (Kalendář pro AI)
            $res = $apt->reservations()->where('check_out', '>=', now())->get();
            if ($res->isNotEmpty()) {
                $occText = "TERMÍNY OBSAZENOSTI PRO {$apt->name}:\n";
                foreach ($res as $r) {
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
            $hikeText .= $hike->is_for_families ? "Vhodné pro rodiny s dětmi." : "Náročnější trasa.";
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
                $slidesText .= "- " . ($slide['title_cs'] ?? '') . ": " . ($slide['subtitle_cs'] ?? '') . "\n";
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
