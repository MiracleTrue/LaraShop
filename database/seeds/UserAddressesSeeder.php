<?php

use Illuminate\Database\Seeder;

class UserAddressesSeeder extends Seeder
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
            //对每一个主表数据，产生一个 x - x 的随机数的数据。
            //同时指定这批数据的 外键 字段统一为当前循环的 ID。
            factory(\App\Models\UserAddress::class, random_int(1, 3))->create(['user_id' => $user->id]);
        });
    }
}
