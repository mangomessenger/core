<?php

namespace Tests\Feature\Message;

use App\Chat;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Tests\TestCase;

class GetChatsTest extends TestCase
{
    use DatabaseMigrations, RefreshDatabase;

    public function setUp(): void
    {
        $this->markTestSkipped('Skipped test');

        parent::setUp();
        Artisan::call('migrate:fresh');
    }

    public function test_get_chats_returns_204_on_success()
    {
        $user = factory(User::class)->create();

        $token = auth()->login($user);

        $this->withHeader('Authorization', "Bearer $token")
            ->json('GET', 'chats/',)
            ->assertStatus(200)
            ->assertJsonStructure([]);
    }

    public function test_send_message_needs_token()
    {
        $this->json('GET', 'chats/', [
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
