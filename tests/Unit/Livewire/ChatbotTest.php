<?php

namespace Tests\Unit\Livewire;

use App\Livewire\Chatbot;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Mockery;
use Tests\TestCase;

class ChatbotTest extends TestCase
{
    use RefreshDatabase;

    public function test_send_message_recaptcha_fails_appends_error_message()
    {
        $comp = new Chatbot;
        $comp->userInput = 'hello';

        Http::fake([
            'https://www.google.com/recaptcha/*' => Http::response(['success' => false, 'score' => 0.1], 200),
        ]);

        $comp->sendMessage('bad-token');

        $this->assertNotEmpty($comp->messages);
        $last = end($comp->messages);
        $this->assertEquals('assistant', $last['role']);
        $this->assertArrayHasKey('is_typing', $last);
    }

    public function test_send_message_success_adds_user_and_assistant_placeholder()
    {
        $comp = new Chatbot;
        $comp->userInput = 'hi there';

        Http::fake([
            'https://www.google.com/recaptcha/*' => Http::response(['success' => true, 'score' => 0.9], 200),
        ]);

        $comp->sendMessage('good-token');

        $this->assertEquals(2, count($comp->messages));
        $this->assertEquals('user', $comp->messages[0]['role']);
        $this->assertEquals('assistant', $comp->messages[1]['role']);
        $this->assertTrue($comp->messages[1]['is_typing']);
        $this->assertEquals(1, $comp->messageCount);
    }

    public function test_generate_response_exception_sets_apology()
    {
        // Mock Embeddings static chain
        $embMock = Mockery::mock('alias:Laravel\\Ai\\Embeddings');
        $chain = new class
        {
            public function dimensions($d)
            {
                return $this;
            }

            public function generate($lab, $model)
            {
                return (object) ['embeddings' => [[0.1, 0.2]]];
            }
        };
        $embMock->shouldReceive('for')->andReturn($chain);

        // Overload SupportBot to throw on prompt
        $botMock = Mockery::mock('overload:App\\Ai\\Agents\\SupportBot');
        $botMock->shouldReceive('prompt')->andThrow(new Exception('AI down'));

        $comp = new Chatbot;
        // Prepare messages so last index exists
        $comp->messages = [['role' => 'user', 'content' => 'q'], ['role' => 'assistant', 'content' => '', 'is_typing' => true]];

        $comp->generateResponse('question');

        $last = end($comp->messages);
        $this->assertStringContainsString('Omlouvám se', $last['content']);
        $this->assertFalse($last['is_typing']);
    }

    public function test_generate_response_success_updates_message_content()
    {
        $embMock = Mockery::mock('alias:Laravel\\Ai\\Embeddings');
        $chain = new class
        {
            public function dimensions($d)
            {
                return $this;
            }

            public function generate($lab, $model)
            {
                return (object) ['embeddings' => [[0.1, 0.2]]];
            }
        };
        $embMock->shouldReceive('for')->andReturn($chain);

        $botMock = Mockery::mock('overload:App\\Ai\\Agents\\SupportBot');
        $botMock->shouldReceive('prompt')->andReturn((object) ['text' => 'This is a helpful reply']);

        $comp = new Chatbot;
        $comp->messages = [['role' => 'user', 'content' => 'q'], ['role' => 'assistant', 'content' => '', 'is_typing' => true]];

        $comp->generateResponse('hello');

        $last = end($comp->messages);
        $this->assertEquals('This is a helpful reply', $last['content']);
        $this->assertArrayHasKey('is_typing', $last);
        $this->assertTrue($last['is_typing']);
    }
}
