<?php

namespace Tests\Feature\Requests\Message;

use App\Chat;
use App\Http\Requests\Message\GetMessagesRequest;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Str;
use Tests\TestCase;

class SendMessageRequest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        $this->markTestSkipped('Skipped test');

        parent::setUp();

        $this->validator = app()->get('validator');

        $this->rules = (new \App\Http\Requests\Message\SendMessageRequest())->rules();

        factory(User::class)->create();
        factory(Chat::class)->create();
    }

    public function validationProvider()
    {
        return [
            'request_should_fail_when_no_message_is_provided' => [
                'passed' => false,
                'data' => [
                    "peer" => [
                        "destination_id" => 1,
                        "chat_type" => "Any"
                    ]
                ]
            ],
            'request_should_fail_when_no_peer_is_provided' => [
                'passed' => false,
                'data' => [
                    "message" => Str::random(25),
                ]
            ],
            'request_should_fail_when_no_peer_destination_id_is_provided' => [
                'passed' => false,
                'data' => [
                    "message" => Str::random(25),
                    "peer" => [
                        "chat_type" => "Any"
                    ]
                ]
            ],
            'request_should_fail_when_no_peer_chat_type_is_provided' => [
                'passed' => false,
                'data' => [
                    "message" => Str::random(25),
                    "peer" => [
                        "chat_type" => "Any"
                    ]
                ]
            ],
            'request_should_fail_when_destination_id_is_not_integer' => [
                'passed' => false,
                'data' => [
                    "message" => Str::random(25),
                    "peer" => [
                        "destination_id" => "Test",
                        "chat_type" => "Any"
                    ]
                ]
            ],
            'request_should_fail_when_peer_is_not_array' => [
                'passed' => false,
                'data' => [
                    "message" => Str::random(350),
                    "peer" => "Test"
                ]
            ],
            'request_should_fail_when_message_size_is_more_than_300' => [
                'passed' => false,
                'data' => [
                    "message" => Str::random(350),
                    "peer" => [
                        "destination_id" => "Test",
                        "chat_type" => "Any"
                    ]
                ]
            ],
            'request_should_pass_when_correct_data_is_provided' => [
                'passed' => true,
                'data' => [
                    "message" => Str::random(25),
                    "peer" => [
                        "destination_id" => 1,
                        "chat_type" => "Any"
                    ]
                ]
            ]
        ];
    }
}
