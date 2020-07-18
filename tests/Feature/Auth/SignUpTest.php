<?php

namespace Tests\Feature\Auth;

use App\AuthRequest;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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
        $authRequest = AuthRequest::create([
            'phone_number' => "093{$this->randomNumber(7)}",
            'country_code' => 'UA',
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
}
