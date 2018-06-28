<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Product::class, function (Faker $faker) {

    // 现在时间
    $now = \Carbon\Carbon::now()->toDateTimeString();

    // 随机取一个月以内的时间
    $updated_at = $faker->dateTimeThisMonth();
    // 传参为生成最大时间不超过，创建时间永远比更改时间要早
    $created_at = $faker->dateTimeThisMonth($updated_at);

    return [
        'title' => $faker->company . '-' . $faker->randomNumber(1),
        'description' => '商品详情',
        'image' => $faker->imageUrl(),
        'on_sale' => true,
        'rating' => $faker->randomFloat(1, 3, 5),
        'sold_count' => $faker->randomNumber(3),
        'review_count' => $faker->randomNumber(3),
        'price' => $faker->randomFloat(2, 10, 9000),
        'created_at' => $created_at,
        'updated_at' => $updated_at,
    ];
});
