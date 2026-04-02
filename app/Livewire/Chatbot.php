<?php

namespace App\Livewire;

use App\Ai\Agents\SupportBot;
use App\Models\KnowledgeBase;
use Exception;
use Illuminate\Support\Facades\Log;
use Laravel\Ai\Embeddings;
use Laravel\Ai\Enums\Lab;
use Livewire\Component;
use Livewire\Attributes\On;

class Chatbot extends Component
{
    public bool $isOpen = false;
    public string $userInput = '';
    public array $messages = [];

    public function sendMessage(): void
    {
        if (empty(trim($this->userInput))) return;

        $currentInput = $this->userInput;
        $this->userInput = '';

        $this->messages[] = ['role' => 'user', 'content' => $currentInput];

        $this->messages[] = [
            'role' => 'assistant',
            'content' => '',
            'should_type' => true,
            'is_typing' => true
        ];

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

            $agent = new SupportBot();

            $response = $agent->prompt(
                "Kontext z databáze:\n{$contextData}\n\nOtázka uživatele: {$input}",
                provider: Lab::Groq,
                model: 'llama-3.3-70b-versatile',
                timeout: 60,
            );

            $lastIndex = count($this->messages) - 1;
            $this->messages[$lastIndex]['content'] = $response->text;

        } catch (Exception $e) {
            Log::error('AI Support Bot Error: ' . $e->getMessage());

            $lastIndex = count($this->messages) - 1;
            $this->messages[$lastIndex]['content'] = __('I\'m sorry, but I\'m currently unable to connect to the server. Please try again in a few minutes.');
            $this->messages[$lastIndex]['is_typing'] = false;
        }
    }

    public function render()
    {
        return view('livewire.chatbot');
    }
}
