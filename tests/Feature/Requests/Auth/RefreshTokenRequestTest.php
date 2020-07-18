<?php

namespace Tests\Feature\Requests\Auth;

use App\Http\Requests\Auth\LogoutRequest;
use App\Http\Requests\Auth\RefreshTokenRequest;
use Illuminate\Support\Str;
use Tests\TestCase;

class RefreshTokenRequestTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->validator = app()->get('validator');

        $this->rules = (new RefreshTokenRequest())->rules();
    }

    public function validationProvider()
    {
        return [
            'request_should_fail_when_no_refresh_token_is_provided' => [
                'passed' => false,
                'data' => [
                    'fingerprint' => Str::random(15),
                ]
            ],
            'request_should_fail_when_no_fingerprint_is_provided' => [
                'passed' => false,
                'data' => [
                    'refresh_token' => Str::random(25),
                ]
            ],
            'request_should_fail_when_fingerprint_has_less_than_10_characters' => [
                'passed' => false,
                'data' => [
                    'refresh_token' => Str::random(25),
                    'fingerprint' => Str::random(9),
                ]
            ],
            'request_should_fail_when_fingerprint_has_more_than_255_characters' => [
                'passed' => false,
                'data' => [
                    'refresh_token' => Str::random(25),
                    'fingerprint' => Str::random(256),
                ]
            ],
            'request_should_pass_when_correct_data_is_provided' => [
                'passed' => true,
                'data' => [
                    'refresh_token' => Str::random(25),
                    'fingerprint' => Str::random(15),
                ]
            ]
        ];
    }
}
