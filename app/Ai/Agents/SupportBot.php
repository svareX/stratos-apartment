<?php

namespace App\Ai\Agents;

use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Promptable;
use Stringable;

class SupportBot implements Agent, Conversational
{
    use Promptable;

    protected array $chatHistory = [];

    public function __construct(array $chatHistory = [])
    {
        $this->chatHistory = $chatHistory;
    }

    public function instructions(): Stringable|string
    {
        return 'Jsi inteligentní a přátelský asistent pro Apartmány Stratos.

        TVOJE PRAVIDLA:
        1. PRIORITA: Pokud se uživatel ptá na apartmány, ceny, rezervace nebo vybavení, VŽDY čerpej z poskytnutého KONTEXTU.
        2. VŠEOBECNÉ ZNALOSTI: Pokud se uživatel ptá na běžné věci (počasí, populace, historie, tipy na cestování), které nejsou v kontextu, odpověz přirozeně ze svých vlastních znalostí.
        3. HLEDÁNÍ NA INTERNETU: Pokud něco nevíš a nemáš to v kontextu, klidně uživateli řekni, že se pokusíš zjistit informaci z obecně dostupných zdrojů (můžeš simulovat, že "víš", protože tvůj model byl trénován na internetových datech).
        4. JAZYK: Odpovídej vždy v jazyce, ve kterém se ptá uživatel (primárně česky).
        5. POKUD OPRAVDU NEVÍŠ: Pouze pokud jde o specifické interní věci (např. stav konkrétní opravy na pokoji), které v kontextu nejsou, odkaž na recepci.';
    }

    public function messages(): iterable
    {
        return $this->chatHistory;
    }
}
