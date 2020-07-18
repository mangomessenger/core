<?php

namespace Tests\Feature\Requests\Auth;

use App\Http\Requests\Auth\SignUpRequest;
use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;
use Tests\TestCase;

class SignUpRequestTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations;

    /** @var array $rules */
    private $rules;

    /** @var Validator $validator */
    private $validator;

    public function setUp(): void
    {
        parent::setUp();

        $this->validator = app()->get('validator');

        $this->rules = (new SignUpRequest())->rules();
    }

    public function validationProvider()
    {
        /* WithFaker trait doesn't work in the dataProvider */
        $faker = Factory::create( Factory::DEFAULT_LOCALE);

        return [
            'request_should_fail_when_no_phone_number_is_provided' => [
                'passed' => false,
                'data' => [
                    'country_code' => 'UA',
                    'name' => Str::random(25),
                    'phone_code_hash' => Str::random(25),
                    'phone_code' => $faker->randomNumber(5),
                    'terms_of_service_accepted' => true,
                ]
            ],
            'request_should_fail_when_no_country_code_is_provided' => [
                'passed' => false,
                'data' => [
                    'phone_number' => "093{$faker->randomNumber(7)}",
                    'name' => Str::random(25),
                    'phone_code_hash' => Str::random(25),
                    'phone_code' => $faker->randomNumber(5),
                    'terms_of_service_accepted' => true,
                ]
            ],
            'request_should_fail_when_no_name_is_provided' => [
                'passed' => false,
                'data' => [
                    'phone_number' => "093{$faker->randomNumber(7)}",
                    'country_code' => 'UA',
                    'phone_code_hash' => Str::random(25),
                    'phone_code' => $faker->randomNumber(5),
                    'terms_of_service_accepted' => true,
                ]
            ],
            'request_should_fail_when_no_phone_code_hash_is_provided' => [
                'passed' => false,
                'data' => [
                    'phone_number' => "093{$faker->randomNumber(7)}",
                    'country_code' => 'UA',
                    'name' => Str::random(25),
                    'phone_code' => $faker->randomNumber(5),
                    'terms_of_service_accepted' => true,
                ]
            ],
            'request_should_fail_when_no_phone_code_is_provided' => [
                'passed' => false,
                'data' => [
                    'phone_number' => "093{$faker->randomNumber(7)}",
                    'country_code' => 'UA',
                    'name' => Str::random(25),
                    'phone_code_hash' => Str::random(25),
                    'terms_of_service_accepted' => true,
                ]
            ],
            'request_should_fail_when_no_terms_of_service_accepted_is_provided' => [
                'passed' => false,
                'data' => [
                    'phone_number' => "093{$faker->randomNumber(7)}",
                    'country_code' => 'UA',
                    'name' => Str::random(25),
                    'phone_code_hash' => Str::random(25),
                    'phone_code' => $faker->randomNumber(5),
                ]
            ],
            'request_should_fail_when_phone_number_is_invalid' => [
                'passed' => false,
                'data' => [
                    'phone_number' => "093{$faker->randomNumber(5)}",
                    'country_code' => 'UA',
                    'name' => Str::random(25),
                    'phone_code_hash' => Str::random(25),
                    'phone_code' => $faker->randomNumber(5),
                    'terms_of_service_accepted' => true,
                ]
            ],
            'request_should_fail_when_phone_number_is_not_numeric' => [
                'passed' => false,
                'data' => [
                    'phone_number' => "093{$faker->randomNumber(6)}s",
                    'country_code' => 'UA',
                    'name' => Str::random(25),
                    'phone_code_hash' => Str::random(25),
                    'phone_code' => $faker->randomNumber(5),
                    'terms_of_service_accepted' => true,
                ]
            ],
            'request_should_fail_when_phone_country_code_is_invalid' => [
                'passed' => false,
                'data' => [
                    'phone_number' => "093{$faker->randomNumber(7)}",
                    'country_code' => Str::random(5),
                    'name' => Str::random(25),
                    'phone_code_hash' => Str::random(25),
                    'phone_code' => $faker->randomNumber(5),
                    'terms_of_service_accepted' => true,
                ]
            ],
            'request_should_fail_when_name_has_more_than_100_characters' => [
                'passed' => false,
                'data' => [
                    'phone_number' => "093{$faker->randomNumber(7)}",
                    'country_code' => 'UA',
                    'name' => Str::random(101),
                    'phone_code_hash' => Str::random(25),
                    'phone_code' => $faker->randomNumber(5),
                    'terms_of_service_accepted' => true,
                ]
            ],
            'request_should_fail_when_phone_code_hash_has_more_than_255_characters' => [
                'passed' => false,
                'data' => [
                    'phone_number' => "093{$faker->randomNumber(7)}",
                    'country_code' => 'UA',
                    'name' => Str::random(25),
                    'phone_code_hash' => Str::random(256),
                    'phone_code' => $faker->randomNumber(5),
                    'terms_of_service_accepted' => true,
                ]
            ],
            'request_should_fail_when_phone_code_is_more_than_5_digits' => [
                'passed' => false,
                'data' => [
                    'phone_number' => "093{$faker->randomNumber(7)}",
                    'country_code' => 'UA',
                    'name' => Str::random(25),
                    'phone_code_hash' => Str::random(25),
                    'phone_code' => $faker->randomNumber(6),
                    'terms_of_service_accepted' => true,
                ]
            ],
            'request_should_fail_when_phone_code_is_less_than_5_digits' => [
                'passed' => false,
                'data' => [
                    'phone_number' => "093{$faker->randomNumber(7)}",
                    'country_code' => 'UA',
                    'name' => Str::random(25),
                    'phone_code_hash' => Str::random(25),
                    'phone_code' => $faker->randomNumber(4),
                    'terms_of_service_accepted' => true,
                ]
            ],
            'request_should_fail_when_terms_of_service_are_not_accepted' => [
                'passed' => false,
                'data' => [
                    'phone_number' => "093{$faker->randomNumber(7)}",
                    'country_code' => 'UA',
                    'name' => Str::random(25),
                    'phone_code_hash' => Str::random(25),
                    'phone_code' => $faker->randomNumber(5),
                    'terms_of_service_accepted' => false,
                ]
            ],
            'request_should_pass_when_correct_data_is_provided' => [
                'passed' => true,
                'data' => [
                    'phone_number' => "093{$faker->randomNumber(7)}",
                    'country_code' => 'UA',
                    'name' => Str::random(25),
                    'phone_code_hash' => Str::random(25),
                    'phone_code' => $faker->randomNumber(5),
                    'terms_of_service_accepted' => true,
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
