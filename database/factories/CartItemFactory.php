<?php

use Faker\Generator as Faker;

$factory->define(App\Models\CartItem::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'product_sku_id' => function () {
            return factory(App\Models\ProductSku::class)->create()->id;
        },
        'amount' => $faker->randomNumber(),
    ];
});
