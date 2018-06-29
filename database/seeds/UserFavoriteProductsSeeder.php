<?php

use Illuminate\Database\Seeder;

class UserFavoriteProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        //获取所有的主表数据，并返回一个集合 Collection。
        \App\Models\User::all()->each(function (\App\Models\User $user) {

            $products = \App\Models\Product::all()->random(random_int(3, 10));

            foreach ($products as $key => $item)
            {
                factory(\App\Models\UserFavoriteProducts::class)->create([
                    'user_id' => $user->id,
                    'product_id' => $item->id,
                ]);
            }
        });
    }
}
