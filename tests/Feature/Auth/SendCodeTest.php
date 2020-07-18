<?php

namespace Tests\Feature\Auth;

use App\AuthRequest;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Propaganistas\LaravelPhone\PhoneNumber;
use Tests\TestCase;

class SendCodeTest extends TestCase
{
    use DatabaseMigrations;

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
    public function test_sendcode_returns_auth_request_on_success()
    {
        $payload = [
            'phone_number' => "093{$this->randomNumber(7)}",
            'country_code' => 'UA',
            'fingerprint' => Str::random(25),
        ];

        $this->json('POST', 'auth/sendCode', $payload)
            ->assertStatus(201)
            ->assertJson([
                'phone_number' => PhoneNumber::make($payload['phone_number'], $payload['country_code'])->formatE164(),
                'country_code' => $payload['country_code'],
                'is_new' => true,
                'timeout' => 120,
            ])->assertJsonStructure([
                'phone_code_hash',
            ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_sendcode_deletes_previous_attempt()
    {
        $authRequest = factory(AuthRequest::class)->create();

        $this->json('POST', 'auth/sendCode', [
            'phone_number' => $authRequest->phone_number,
            'country_code' => $authRequest->country_code,
            'fingerprint' => Str::random(25),
        ])
            ->assertStatus(201)
            ->assertJson([
                'phone_number' => $authRequest->phone_number,
                'country_code' => $authRequest->country_code,
                'is_new' => true,
                'timeout' => 120,
            ])->assertJsonStructure([
                'phone_code_hash',
            ]);

        $this->assertNull(AuthRequest::find($authRequest->id));
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_sendcode_requires_payload()
    {
        $this->json('POST', 'auth/sendCode')
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
