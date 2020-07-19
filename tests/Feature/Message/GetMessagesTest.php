<?php

namespace Tests\Feature\Message;

use App\Chat;
use App\Message;
use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Tests\TestCase;

class GetMessagesTest extends TestCase
{
    use DatabaseMigrations, RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate:fresh');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_messages_returns_204_on_success()
    {
        $user = factory(User::class)->create();
        $chat = factory(Chat::class)->create();
        $message = factory(Message::class)->create();

        $token = auth()->login($user);

        $this->withHeader('Authorization', "Bearer $token")
            ->json('GET', 'messages/', [
                "chat_id" => 1,
                "message_id" => 1
            ])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_messages_requires_payload()
    {
        $user = factory(User::class)->create();

        $token = auth()->login($user);

        $this->withHeader('Authorization', "Bearer $token")
            ->json('GET', 'messages/', [])
            ->assertStatus(422)
            ->assertJson([
                'type' => 'INVALID_PAYLOAD',
            ])->assertJsonStructure([
                'type',
                'message',
                'errors',
                'status',
            ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_send_message_needs_token()
    {
        $user = factory(User::class)->create();
        $chat = factory(Chat::class)->create();
        $message = factory(Message::class)->create();

        $this->json('GET', 'messages/', [
            "chat_id" => 1,
            "message_id" => 1
        ])
            ->assertStatus(401)
            ->assertJson([
                'type' => 'TOKEN_ABSENT',
            ])->assertJsonStructure([
                'type',
                'message',
                'status',
            ]);
    }
}
