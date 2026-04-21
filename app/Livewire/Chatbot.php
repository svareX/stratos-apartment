<?php

namespace App\Livewire;

use App\Ai\Agents\SupportBot;
use App\Models\KnowledgeBase;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Laravel\Ai\Embeddings;
use Laravel\Ai\Enums\Lab;
use Livewire\Attributes\On;
use Livewire\Component;

class Chatbot extends Component
{
    public bool $isOpen = false;

    public string $userInput = '';

    public array $messages = [];

    public int $messageCount = 0;

    public function sendMessage(?string $recaptchaToken = null): void
    {
        if (empty(trim($this->userInput))) {
            return;
        }

        if ($this->messageCount === 0 || $this->messageCount % 5 === 0) {
            if (! $recaptchaToken) {
                return;
            }

            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => config('services.recaptcha.secret_key'),
                'response' => $recaptchaToken,
            ]);

            if (! $response->json('success') || $response->json('score') < 0.5) {
                $this->messages[] = [
                    'role' => 'assistant',
                    'content' => __('Ověření proti spamu selhalo. Pokud jste člověk, zkuste stránku obnovit.'),
                    'should_type' => true,
                    'is_typing' => true,
                ];
                $this->dispatch('scroll-to-bottom');

                return;
            } else {
                Log::info('reCAPTCHA hodnoceni:', $response->json());
            }
        }

        $currentInput = $this->userInput;
        $this->userInput = '';

        $this->messages[] = ['role' => 'user', 'content' => $currentInput];

        $this->messages[] = [
            'role' => 'assistant',
            'content' => '',
            'should_type' => true,
            'is_typing' => true,
        ];

        $this->messageCount++;

        $this->dispatch('scroll-to-bottom');

        $this->dispatch('generate-ai-response', input: $currentInput);
    }

    #[On('generate-ai-response')]
    public function generateResponse(string $input): void
    {
        try {
            $embeddingResponse = Embeddings::for([$input])
                ->dimensions(768)
                ->generate(Lab::Gemini, 'gemini-embedding-001');

            $contextData = KnowledgeBase::query()
                ->whereVectorSimilarTo('embedding', $embeddingResponse->embeddings[0])
                ->limit(10)
                ->get()
                ->pluck('content')
                ->implode("\n---\n");

            $agent = new SupportBot;

            $locale = app()->getLocale();

            $response = $agent->prompt(
                "Jazyk webu (locale): {$locale}\nKontext z databáze:\n{$contextData}\n\nOtázka uživatele: {$input}",
                provider: Lab::Groq,
                model: 'llama-3.3-70b-versatile',
                timeout: 60,
            );

            $lastIndex = count($this->messages) - 1;
            $this->messages[$lastIndex]['content'] = $response->text;

        } catch (Exception $e) {
            Log::error('AI Support Bot Error: '.$e->getMessage());

            $lastIndex = count($this->messages) - 1;
            $this->messages[$lastIndex]['content'] = __('Omlouvám se, ale momentálně se mi nepodařilo spojit se serverem. Zkuste to prosím za chvíli.');
            $this->messages[$lastIndex]['is_typing'] = false;
        }
    }

    public function render()
    {
        return view('livewire.chatbot');
    }
}
