<?php

namespace Tests\Feature\Message;

use App\Chat;
use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Tests\TestCase;

class SendMessageTest extends TestCase
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
    public function test_send_message_returns_204_on_success()
    {
        $user = factory(User::class)->create();

        $token = auth()->login($user);

        $this->withHeader('Authorization', "Bearer $token")
            ->json('POST', 'messages/', [
                'message' => Str::random(25),
                'peer' => [
                    'destination_id' => 1,
                    'chat_type' => 'TYPE_USER'
                ]
            ])
            ->assertStatus(201);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_send_message_returns_chat_type_invalid()
    {
        $user = factory(User::class)->create();

        $token = auth()->login($user);

        $this->withHeader('Authorization', "Bearer $token")
            ->json('POST', 'messages/', [
                'message' => Str::random(25),
                'peer' => [
                    'destination_id' => 1,
                    'chat_type' => 'Any'
                ]
            ])
            ->assertStatus(400)
            ->assertJson([
                'type' => 'CHAT_TYPE_INVALID',
            ])->assertJsonStructure([
                'type',
                'message',
                'status',
            ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_send_message_returns_destination_invalid()
    {
        $user = factory(User::class)->create();

        $token = auth()->login($user);

        $this->withHeader('Authorization', "Bearer $token")
            ->json('POST', 'messages/', [
                'message' => Str::random(25),
                'peer' => [
                    'destination_id' => 5,
                    'chat_type' => 'TYPE_USER'
                ]
            ])
            ->assertStatus(400)
            ->assertJson([
                'type' => 'DESTINATION_INVALID',
            ])->assertJsonStructure([
                'type',
                'message',
                'status',
            ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_send_message_requires_payload()
    {
        $user = factory(User::class)->create();

        $token = auth()->login($user);

        $this->withHeader('Authorization', "Bearer $token")
            ->json('POST', 'messages/', [])
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

        $this->json('POST', 'messages/', [
            'message' => Str::random(25),
            'peer' => [
                'destination_id' => 1,
                'chat_type' => 'TYPE_USER'
            ]
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
