<?php

namespace Tests\Feature\Requests\Auth;

use App\Http\Requests\Auth\SendCodeRequest;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;
use Tests\TestCase;

class SendCodeRequestTest extends TestCase
{
    /** @var SendCodeRequest */
    private $rules;

    /** @var Validator */
    private $validator;

    public function setUp(): void
    {
        parent::setUp();

        $this->validator = app()->get('validator');

        $this->rules = (new SendCodeRequest())->rules();
    }

    public function validationProvider()
    {
        return [
            'request_should_fail_when_no_phone_number_is_provided' => [
                'passed' => false,
                'data' => [
                    'fingerprint' => Str::random(15),
                ]
            ],
            'request_should_fail_when_no_fingerprint_is_provided' => [
                'passed' => false,
                'data' => [
                    'phone_number' => "093{$this->randomNumber(7)}",
                    'country_code' => 'UA',
                ]
            ],
            'request_should_fail_when_no_country_code_is_provided' => [
                'passed' => false,
                'data' => [
                    'phone_number' => "093{$this->randomNumber(7)}",
                    'fingerprint' => Str::random(15),
                ]
            ],
            'request_should_fail_when_phone_number_is_invalid' => [
                'passed' => false,
                'data' => [
                    'phone_number' => "093{$this->randomNumber(5)}",
                    'country_code' => 'UA',
                    'fingerprint' => Str::random(15),
                ]
            ],
            'request_should_fail_when_country_code_is_invalid' => [
                'passed' => false,
                'data' => [
                    'phone_number' => "093{$this->randomNumber(7)}",
                    'country_code' => Str::random(5),
                    'fingerprint' => Str::random(15),
                ]
            ],
            'request_should_fail_when_fingerprint_has_less_than_10_characters' => [
                'passed' => false,
                'data' => [
                    'fingerprint' => Str::random(5)
                ]
            ],
            'request_should_fail_when_fingerprint_has_more_than_255_characters' => [
                'passed' => false,
                'data' => [
                    'fingerprint' => Str::random(256),
                ]
            ],
            'request_should_pass_when_correct_data_is_provided' => [
                'passed' => true,
                'data' => [
                    'phone_number' => "093{$this->randomNumber(7)}",
                    'country_code' => 'UA',
                    'fingerprint' => Str::random(10),
                ]
            ]
        ];
    }

    /**
     * @param int $digits
     * @return int
     */
    function randomNumber(int $digits) {
        $min = pow(10, $digits - 1);
        $max = pow(10, $digits) - 1;
        return mt_rand($min, $max);
    }

    /**
     * @test
     * @dataProvider validationProvider
     * @param bool $shouldPass
     * @param array $mockedRequestData
     */
    public function validation_results_as_expected($shouldPass, $mockedRequestData)
    {
        $this->assertEquals(
            $shouldPass,
            $this->validate($mockedRequestData)
        );
    }

    protected function validate($mockedRequestData)
    {
        return $this->validator
            ->make($mockedRequestData, $this->rules)
            ->passes();
    }
}
