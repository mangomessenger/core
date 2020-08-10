<?php

namespace Tests\Feature\Auth;

use App\Models\AuthRequest;
use App\Services\Auth\SessionService;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Propaganistas\LaravelPhone\PhoneNumber;
use Tests\TestCase;

class RefreshTokensTest extends TestCase
{
    use DatabaseMigrations, RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function test_refreshtoken_returns_tokens_on_success()
    {
        $user = factory(User::class)->create();

        $sessionService = app()->make(SessionService::class);

        $session = $sessionService->create([
            'user_id' => $user->id,
            'fingerprint' => Str::random(15),
            'expires_in' => Carbon::now()->addDays(1),
        ]);

        $this->json('POST', 'auth/refresh-tokens', [
            'refresh_token' => $session->refresh_token,
            'fingerprint' => $session->fingerprint,
        ])
            ->assertStatus(201)
            ->assertJsonStructure([
                'access_token',
                'refresh_token',
            ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function test_refreshtoken_returns_fingerprint_invalid()
    {
        $user = factory(User::class)->create();

        $sessionService = app()->make(SessionService::class);

        $session = $sessionService->create([
            'user_id' => $user->id,
            'fingerprint' => Str::random(15),
            'expires_in' => Carbon::now()->addDays(1),
        ]);

        $this->json('POST', 'auth/refresh-tokens', [
            'refresh_token' => $session->refresh_token,
            'fingerprint' => $session->fingerprint . Str::random(5),// Making fingerprint not correct
        ])
            ->assertStatus(400)
            ->assertJson([
                'type' => 'FINGERPRINT_INVALID',
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
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function test_refreshtoken_returns_refresh_token_invalid()
    {
        $user = factory(User::class)->create();

        $sessionService = app()->make(SessionService::class);

        $session = $sessionService->create([
            'user_id' => $user->id,
            'fingerprint' => Str::random(15),
            'expires_in' => Carbon::now()->addDays(1),
        ]);

        $this->json('POST', 'auth/refresh-tokens', [
            'refresh_token' => $session->refresh_token . Str::random(3), // Making refresh token not correct
            'fingerprint' => $session->fingerprint,
        ])
            ->assertStatus(400)
            ->assertJson([
                'type' => 'REFRESH_TOKEN_INVALID',
            ])->assertJsonStructure([
                'type',
                'message',
                'status',
            ]);
    }

    public function test_signin_requires_payload()
    {
        $this->json('POST', 'auth/sign-in')
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
