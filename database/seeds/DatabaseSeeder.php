<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        $this->call(AdminTablesSeeder::class);

        //用户
        $this->call(UsersSeeder::class);
        $this->call(UserAddressesSeeder::class);

        //商品
        $this->call(ProductsSeeder::class);
        $this->call(ProductSkusSeeder::class);

        //收藏
        $this->call(UserFavoriteProductsSeeder::class);

        //购物车
        $this->call(CartItemsSeeder::class);

        //优惠券
        $this->call(CouponCodesSeeder::class);

    }
}
