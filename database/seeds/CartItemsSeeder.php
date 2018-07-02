<?php

use Illuminate\Database\Seeder;

class CartItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //获取所有的主表数据，并返回一个集合 Collection。
        \App\Models\User::all()->each(function (\App\Models\User $user) {

            $product_skus = \App\Models\ProductSku::all()->random(random_int(3, 10));

            foreach ($product_skus as $key => $item)
            {
                factory(\App\Models\CartItem::class)->create([
                    'user_id' => $user->id,
                    'product_sku_id' => $item->id,
                ]);
            }
        });
    }
}
