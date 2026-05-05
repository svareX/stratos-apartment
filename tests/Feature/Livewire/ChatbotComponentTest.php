<?php

namespace Tests\Feature\Livewire;

use App\Livewire\Chatbot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ChatbotComponentTest extends TestCase
{
    use RefreshDatabase;

    public function test_initial_state_and_message_submission()
    {
        $component = Livewire::test(Chatbot::class)
            ->assertSet('messages', [])
            ->assertSet('isOpen', false);

        // set as open and simulate a message submission without triggering external reCAPTCHA
        $component->set('isOpen', true)
            ->set('messageCount', 1)
            ->set('userInput', 'Hello')
            ->call('sendMessage')
            ->assertSet('isOpen', true)
                ->assertSet('messages', function ($m) {
                    return is_array($m) && count($m) > 0;
                });
    }
}
