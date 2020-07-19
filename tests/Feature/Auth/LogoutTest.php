<?php

namespace Tests\Feature\Auth;

use App\AuthRequest;
use App\Services\Auth\SessionService;
use App\Session;
use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Propaganistas\LaravelPhone\PhoneNumber;
use Tests\TestCase;

class LogoutTest extends TestCase
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
     * @throws BindingResolutionException
     */
    public function test_logout_returns_204_on_success()
    {
        $user = factory(User::class)->create();

        $sessionService = app()->make(SessionService::class);

        $session = $sessionService->create([
            'user_id' => $user->id,
            'fingerprint' => Str::random(15),
            'expires_in' => Carbon::now()->addDays(1),
        ]);

        $this->json('POST', 'auth/logout', [
            'refresh_token' => $session->refresh_token,
        ])
            ->assertStatus(204);
    }

    public function test_logout_requires_payload()
    {
        $this->json('POST', 'auth/logout')
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
}
