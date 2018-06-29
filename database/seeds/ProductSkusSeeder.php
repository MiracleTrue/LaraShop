<?php

use Illuminate\Database\Seeder;

class ProductSkusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        //获取所有的主表数据，并返回一个集合 Collection。
        \App\Models\Product::all()->each(function (\App\Models\Product $product) {
            //对每一个主表数据，产生一个 x - x 的随机数的数据。
            //同时指定这批数据的 外键 字段统一为当前循环的 ID。
            $skus = factory(\App\Models\ProductSku::class, random_int(2, 4))->create(['product_id' => $product->id]);


            // 找出价格最低的 SKU 价格，把商品价格设置为该价格
            $product->update(['price' => $skus->min('price')]);
        });
    }
}
