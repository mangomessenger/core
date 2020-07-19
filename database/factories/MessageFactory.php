<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Message::class, function (Faker $faker) {
    return [
        'chat_id' => 1,
        'user_id' => 1,
        'message' => \Illuminate\Support\Str::random(25),
    ];
});
