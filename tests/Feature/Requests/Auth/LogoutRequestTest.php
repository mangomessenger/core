<?php

namespace Tests\Feature\Requests\Auth;

use App\Http\Requests\Auth\LogoutRequest;
use Illuminate\Support\Str;
use Tests\TestCase;

class LogoutRequestTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->validator = app()->get('validator');

        $this->rules = (new LogoutRequest())->rules();
    }

    public function validationProvider()
    {
        return [
            'request_should_fail_when_no_refresh_token_is_provided' => [
                'passed' => false,
                'data' => [
                ]
            ],
            'request_should_pass_when_correct_data_is_provided' => [
                'passed' => true,
                'data' => [
                    'refresh_token' => Str::random(25),
                ]
            ]
        ];
    }
}
