<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {

        $users = factory(\App\Models\User::class, 5)->create()->each(function ($user, $index) {
            $user->addresses()->save(factory(\App\Models\UserAddress::class)->make());
            $user->addresses()->save(factory(\App\Models\UserAddress::class)->make());

        });

//        单独处理第一个用户的数据
        $user = \App\Models\User::find(1);
        $user->name = 'Miracle';
        $user->email = '120007700@qq.com';
        $user->password = bcrypt('123456');
        $user->save();

    }
}
