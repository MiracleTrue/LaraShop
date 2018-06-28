<?php

use Illuminate\Database\Seeder;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {

        $products = factory(\App\Models\Product::class, 10)->create()->each(function ($products, $index) {
            $products->skus()->save(factory(\App\Models\ProductSku::class)->make());
            $products->skus()->save(factory(\App\Models\ProductSku::class)->make());
            $products->skus()->save(factory(\App\Models\ProductSku::class)->make());
        });

    }
}
