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
        /* WithFaker trait doesn't work in the dataProvider */
        $faker = Factory::create( Factory::DEFAULT_LOCALE);

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
                    'phone_number' => '931231234',
                    'country_code' => 'UA',
                ]
            ],
            'request_should_fail_when_no_country_code_is_provided' => [
                'passed' => false,
                'data' => [
                    'phone_number' => '9312312342',
                    'fingerprint' => Str::random(15),
                ]
            ],
            'request_should_fail_when_phone_number_is_invalid' => [
                'passed' => false,
                'data' => [
                    'phone_number' => '9312312342',
                    'country_code' => 'UA',
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
            'request_should_pass_when_data_is_provided' => [
                'passed' => true,
                'data' => [
                    'phone_number' => '931231234',
                    'country_code' => 'UA',
                    'fingerprint' => Str::random(10),
                ]
            ]
        ];
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
