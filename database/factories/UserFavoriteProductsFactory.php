<?php

use Faker\Generator as Faker;

$factory->define(App\Models\UserFavoriteProducts::class, function (Faker $faker) {
    // 现在时间
    $now = \Carbon\Carbon::now()->toDateTimeString();

    // 随机取一个月以内的时间
    $updated_at = $faker->dateTimeThisMonth();
    // 传参为生成最大时间不超过，创建时间永远比更改时间要早
    $created_at = $faker->dateTimeThisMonth($updated_at);

//    return [
//        'created_at' => $created_at,
//        'updated_at' => $updated_at,
//    ];
    return [
//        'user_id' => $faker->randomNumber(),
//        'product_id' => $faker->randomNumber(),
    ];
});
