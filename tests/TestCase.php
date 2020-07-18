<?php

namespace Tests;

use App\Http\Requests\Auth\SendCodeRequest;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Validation\Validator;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /** @var SendCodeRequest */
    protected $rules;

    /** @var Validator */
    protected $validator;

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
