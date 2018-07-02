<?php

use Faker\Generator as Faker;

$factory->define(App\Models\CartItem::class, function (Faker $faker) {
    return [
        'amount' => $faker->randomNumber(1),
    ];
});
