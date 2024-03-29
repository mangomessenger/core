<?php

namespace Tests\Feature\Auth;

use App\Models\AuthRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Propaganistas\LaravelPhone\PhoneNumber;
use Tests\TestCase;

class SignUpTest extends TestCase
{
    use DatabaseMigrations, RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate:fresh');
    }

    public function test_signup_returns_session_on_success()
    {
        $authRequest = factory(AuthRequest::class)->create();

        $this->json('POST', 'auth/register', [
            'phone_number' => $authRequest->phone_number,
            'country_code' => $authRequest->country_code,
            'phone_code_hash' => $authRequest->phone_code_hash,
            'name' => 'Donald',
            'phone_code' => 22222,
            'terms_of_service_accepted' => true,
        ])
            ->assertStatus(201)
            ->assertJson([
                'user' => [
                    'name' => 'Donald'
                ]
            ])->assertJsonStructure([
                'tokens' =>[
                    'access_token',
                    'refresh_token'
                ],
            ]);
    }

    public function test_signup_requires_payload()
    {
        $this->json('POST', 'auth/register')
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

    public function test_signup_returns_auth_request_expired()
    {
        $authRequest = factory(AuthRequest::class)->make();

        $this->json('POST', 'auth/register', [
            'phone_number' => $authRequest->phone_number,
            'country_code' => $authRequest->country_code,
            'phone_code_hash' => $authRequest->phone_code_hash,
            'name' => 'Donald',
            'phone_code' => 22222,
            'terms_of_service_accepted' => true,
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

    public function test_signup_returns_phone_number_occupied()
    {
        $user = factory(User::class)->create();

        $authRequest = AuthRequest::create([
            'phone_number' => PhoneNumber::make($user->phone_number, $user->country_code)->formatE164(),
            'country_code' => $user->country_code,
            'phone_code_hash' => Hash::make(22222),
            'fingerprint' => Str::random(25),
            'is_new' => true,
        ]);

        $this->json('POST', 'auth/register', [
            'phone_number' => $authRequest->phone_number,
            'country_code' => $authRequest->country_code,
            'phone_code_hash' => $authRequest->phone_code_hash,
            'name' => 'Donald',
            'phone_code' => 22222,
            'terms_of_service_accepted' => true,
        ])
            ->assertStatus(400)
            ->assertJson([
                'type' => 'PHONE_NUMBER_OCCUPIED',
            ])->assertJsonStructure([
                'type',
                'message',
                'status',
            ]);
    }

    public function test_signup_returns_phone_code_hash_invalid()
    {
        $authRequest = factory(AuthRequest::class)->create();

        $this->json('POST', 'auth/register', [
            'phone_number' => $authRequest->phone_number,
            'country_code' => $authRequest->country_code,
            'phone_code_hash' => $authRequest->phone_code_hash . Str::random(5),
            'name' => 'Donald',
            'phone_code' => 22222,
            'terms_of_service_accepted' => true,
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

    public function test_signup_returns_phone_code_invalid()
    {
        $authRequest = factory(AuthRequest::class)->create();

        $this->json('POST', 'auth/register', [
            'phone_number' => $authRequest->phone_number,
            'country_code' => $authRequest->country_code,
            'phone_code_hash' => $authRequest->phone_code_hash,
            'name' => 'Donald',
            'phone_code' => 22221,
            'terms_of_service_accepted' => true,
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
