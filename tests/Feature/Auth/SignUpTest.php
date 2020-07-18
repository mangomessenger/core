<?php

namespace Tests\Feature\Auth;

use App\AuthRequest;
use App\User;
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

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_signup_returns_session_on_success()
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

        $this->json('POST', 'auth/signUp', [
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
                    'id' => 1,
                    'name' => 'Donald'
                ]
            ])->assertJsonStructure([
                'access_token',
                'refresh_token',
            ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_signup_requires_payload()
    {
        $this->json('POST', 'auth/signUp')
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
    public function test_signup_returns_auth_request_expired()
    {
        $user = factory(User::class)->make();

        $authRequest = AuthRequest::make([
            'phone_number' => PhoneNumber::make($user->phone_number, $user->country_code)->formatE164(),
            'country_code' => $user->country_code,
            'phone_code_hash' => Hash::make(22222),
            'fingerprint' => Str::random(25),
            'timeout' => 120,
            'is_new' => true,
        ]);

        $this->json('POST', 'auth/signUp', [
            'phone_number' => $authRequest->phone_number,
            'country_code' => $authRequest->country_code,
            'phone_code_hash' => $authRequest->phone_code_hash,
            'name' => 'Donald',
            'phone_code' => 22221,
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

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_signup_returns_phone_number_occupied()
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

        $this->json('POST', 'auth/signUp', [
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

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_signup_returns_phone_code_hash_invalid()
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

        $this->json('POST', 'auth/signUp', [
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

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_signup_returns_phone_code_invalid()
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

        $this->json('POST', 'auth/signUp', [
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
