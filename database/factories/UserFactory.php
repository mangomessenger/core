<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use Propaganistas\LaravelPhone\PhoneNumber;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    $randomNumber = \Tests\TestCase::randomNumber(7);
    return [
        'name' => Str::random(10),
        'phone_number' => PhoneNumber::make("093{$randomNumber}", 'UA')->formatE164(),
        'country_code' => 'UA',
    ];
});
