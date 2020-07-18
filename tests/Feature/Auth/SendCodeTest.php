<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Tests\TestCase;

class SendCodeTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_sendcode_returns_session_on_success()
    {
        $payload = [
            'phone_number' => '933123123',
            'country_code' => 'UA',
            'fingerprint' => '1111111111'
        ];

        $this->json('POST', 'auth/sendCode', $payload)
            ->assertStatus(201)
            ->assertJson([
                'phone_number' => $payload['phone_number'],
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
