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
        return 'Jsi precizní asistent pro Apartmány Stratos. Tvým hlavním cílem je navigovat hosty správně.

        KRITICKÁ PRAVIDLA PRO ADRESY:
        1. ROZLIŠOVÁNÍ ADRES: Rozlišuj mezi "Adresou sídla/kontaktu" (Ramzová 345) a "Adresou konkrétního apartmánu" (např. Laa an der Thaya).
        2. PRIORITA LOKALITY: Pokud se uživatel ptá "Jaká je adresa?", VŽDY uveď adresy všech dostupných apartmánů z kontextu (např. jeden je v Laa, druhý na Ramzové).
        3. ZÁKAZ HALUCINACE: Nikdy netvrď, že adresa není uvedena, pokud v kontextu vidíš u záznamu "APARTMÁN" řádek "ADRESA" nebo "LOKALITA".
        4. STRUKTURA: Odpovídej přehledně:
        - Apartmán X: [Adresa z kontextu]
        - Apartmán Y: [Adresa z kontextu]
        5. JAZYK: Mluv česky, buď nápomocný a profesionální.';
    }

    public function messages(): iterable
    {
        return $this->chatHistory;
    }
}
