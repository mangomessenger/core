<?php

namespace Tests\Feature\Requests\Message;

use App\Models\DirectChat;
use App\Http\Requests\Message\GetMessagesRequest;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class GetMessagesRequestTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        $this->markTestSkipped('Skipped test');

        parent::setUp();

        $this->validator = app()->get('validator');

        $this->rules = (new GetMessagesRequest())->rules();

        factory(User::class)->create();
        factory(DirectChat::class)->create();
        factory(Message::class)->create();
    }

    public function validationProvider()
    {
        return [
            'request_should_fail_when_no_chat_id_is_provided' => [
                'passed' => false,
                'data' => [
                ]
            ],
            'request_should_fail_when_invalid_chat_id_is_provided' => [
                'passed' => false,
                'data' => [
                    "chat_id" => 1337,
                ]
            ],
            'request_should_fail_when_invalid_message_id_is_provided' => [
                'passed' => false,
                'data' => [
                    "chat_id" => 1,
                    "message_id" => 1337,
                ]
            ],
            'request_should_pass_when_message_id_is_provided' => [
                'passed' => true,
                'data' => [
                    "chat_id" => 1,
                    "message_id" => 1,
                ]
            ],
            'request_should_pass_when_correct_data_is_provided' => [
                'passed' => true,
                'data' => [
                    "chat_id" => 1
                ]
            ]
        ];
    }
}
