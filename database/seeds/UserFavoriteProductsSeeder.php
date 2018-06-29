<?php

use Illuminate\Database\Seeder;

class UserFavoriteProductsSeeder extends Seeder
{
    private $min = 1;
    private $max = 3;

    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
//        $products = \App\Models\Product::all()->random($this->max);
//
////        dd($products->toArray());
//
//
//        //获取所有的主表数据，并返回一个集合 Collection。
//        \App\Models\User::all()->each(function (\App\Models\User $user) use ($products) {
////
////            $products->shift()->id;
//
//            dd($products);
//            //对每一个主表数据，产生一个 x - x 的随机数的数据。
//            //同时指定这批数据的 外键 字段统一为当前循环的 ID。
//            factory(\App\Models\UserFavoriteProducts::class, random_int($this->min, $this->max))->create([
//                'user_id' => $user->id,
//                'product_id' => $products->shift()->id,
//            ]);
//        });
    }
}
