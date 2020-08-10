<?php

namespace Tests\Feature\Auth;

use App\Models\AuthRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Propaganistas\LaravelPhone\PhoneNumber;
use Tests\TestCase;

class SignInTest extends TestCase
{
    use DatabaseMigrations, RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }
    public function test_signin_returns_session_on_success()
    {
        $user = factory(User::class)->create();

        $authRequest = AuthRequest::create([
            'phone_number' => PhoneNumber::make($user->phone_number, $user->country_code)->formatE164(),
            'country_code' => $user->country_code,
            'phone_code_hash' => Hash::make(22222),
            'fingerprint' => Str::random(25),
            'timeout' => 120,
            'is_new' => true,
        ]);

        $this->json('POST', 'auth/sign-in', [
            'phone_number' => $authRequest->phone_number,
            'country_code' => $authRequest->country_code,
            'phone_code_hash' => $authRequest->phone_code_hash,
            'phone_code' => 22222,
        ])
            ->assertStatus(201)
            ->assertJson([
                'user' => [
                    'id' => 1,
                    'name' => $user->name,
                ]
            ])->assertJsonStructure([
                'tokens' =>[
                    'access_token',
                    'refresh_token'
                ],
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

    public function test_signin_returns_auth_request_expired()
    {
        $user = factory(User::class)->create();

        $authRequest = AuthRequest::make([
            'phone_number' => PhoneNumber::make($user->phone_number, $user->country_code)->formatE164(),
            'country_code' => $user->country_code,
            'phone_code_hash' => Hash::make(22222),
            'fingerprint' => Str::random(25),
            'timeout' => 120,
            'is_new' => true,
        ]);

        $this->json('POST', 'auth/sign-in', [
            'phone_number' => $authRequest->phone_number,
            'country_code' => $authRequest->country_code,
            'phone_code_hash' => $authRequest->phone_code_hash,
            'phone_code' => 22222,
        ])
            ->assertStatus(400)
            ->assertJson([
                'type' => 'AUTH_REQUEST_EXPIRED',
            ])->assertJsonStructure([
                'type',
                'message',
                'status',
            ]);
    }

    public function test_signin_returns_phone_number_unoccupied()
    {
        $user = factory(User::class)->make();

        $authRequest = AuthRequest::create([
            'phone_number' => PhoneNumber::make($user->phone_number, $user->country_code)->formatE164(),
            'country_code' => $user->country_code,
            'phone_code_hash' => Hash::make(22222),
            'fingerprint' => Str::random(25),
            'timeout' => 120,
            'is_new' => true,
        ]);

        $this->json('POST', 'auth/sign-in', [
            'phone_number' => $authRequest->phone_number,
            'country_code' => $authRequest->country_code,
            'phone_code_hash' => $authRequest->phone_code_hash,
            'phone_code' => 22222,
        ])
            ->assertStatus(400)
            ->assertJson([
                'type' => 'PHONE_NUMBER_UNOCCUPIED',
            ])->assertJsonStructure([
                'type',
                'message',
                'status',
            ]);
    }

    public function test_signin_returns_phone_code_hash_invalid()
    {
        $user = factory(User::class)->create();

        $authRequest = AuthRequest::create([
            'phone_number' => PhoneNumber::make($user->phone_number, $user->country_code)->formatE164(),
            'country_code' => $user->country_code,
            'phone_code_hash' => Hash::make(22222),
            'fingerprint' => Str::random(25),
            'timeout' => 120,
            'is_new' => true,
        ]);

        $this->json('POST', 'auth/sign-in', [
            'phone_number' => $authRequest->phone_number,
            'country_code' => $authRequest->country_code,
            'phone_code_hash' => $authRequest->phone_code_hash . Str::random(5),
            'phone_code' => 22222,
        ])
            ->assertStatus(400)
            ->assertJson([
                'type' => 'PHONE_CODE_HASH_INVALID',
            ])->assertJsonStructure([
                'type',
                'message',
                'status',
            ]);
    }

    public function test_signin_returns_phone_code_invalid()
    {
        $user = factory(User::class)->create();

        $authRequest = AuthRequest::create([
            'phone_number' => PhoneNumber::make($user->phone_number, $user->country_code)->formatE164(),
            'country_code' => $user->country_code,
            'phone_code_hash' => Hash::make(22222),
            'fingerprint' => Str::random(25),
            'timeout' => 120,
            'is_new' => true,
        ]);

        $this->json('POST', 'auth/sign-in', [
            'phone_number' => $authRequest->phone_number,
            'country_code' => $authRequest->country_code,
            'phone_code_hash' => $authRequest->phone_code_hash,
            'phone_code' => 22221,
        ])
            ->assertStatus(400)
            ->assertJson([
                'type' => 'PHONE_CODE_INVALID',
            ])->assertJsonStructure([
                'type',
                'message',
                'status',
            ]);
    }
}
