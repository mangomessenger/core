<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Propaganistas\LaravelPhone\PhoneNumber;

$factory->define(\App\Models\AuthRequest::class, function (Faker $faker) {
    $randomNumber = \Tests\TestCase::randomNumber(7);
    return [
        'phone_number' => PhoneNumber::make("093{$randomNumber}", 'UA')->formatE164(),
        'country_code' => 'UA',
        'phone_code_hash' => Hash::make(22222),
        'fingerprint' => Str::random(25),
        'is_new' => true,
    ];
});
