<?php

use Illuminate\Database\Seeder;

use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Auth\Database\Role;
use Encore\Admin\Auth\Database\Permission;
use Encore\Admin\Auth\Database\Menu;

/**
 * Class AdminTablesSeeder
 */
class AdminTablesSeeder extends Seeder
{
    /*自定义添加的权限*/
    private $custom_permissions =
        [
            [
                'name' => '用户管理',
                'slug' => 'users',
                'http_method' => '',
                'http_path' => "/users",
            ],
            [
                'name' => '商品管理',
                'slug' => 'products',
                'http_method' => '',
                'http_path' => "/products",
            ],
            [
                'name' => '订单管理',
                'slug' => 'orders',
                'http_method' => '',
                'http_path' => "/orders",
            ],
        ];

    /*自定义添加的菜单*/
    private $custom_menus =
        [
            [
                'parent_id' => 0,
                'order' => 1,
                'title' => '用户管理',
                'icon' => 'fa-list',
                'uri' => '/users',
            ],
            [
                'parent_id' => 0,
                'order' => 1,
                'title' => '商品管理',
                'icon' => 'fa-list',
                'uri' => '/products',
            ],
            [
                'parent_id' => 0,
                'order' => 1,
                'title' => '订单管理',
                'icon' => 'fa-list',
                'uri' => '/orders',
            ],
        ];

    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        // create a user.
        Administrator::truncate();
        Administrator::create([
            'username' => 'admin',
            'password' => bcrypt('admin'),
            'name' => '超级管理员',
        ]);

        // create a role.
        Role::truncate();
        Role::create([
            'name' => '超级管理员',
            'slug' => 'administrator',
        ]);

        // add role to user.
        Administrator::first()->roles()->save(Role::first());

        //create a permission
        Permission::truncate();
        $permissions = [
            [
                'name' => '所有权限',
                'slug' => '*',
                'http_method' => '',
                'http_path' => '*',
            ],
            [
                'name' => '首页',
                'slug' => 'dashboard',
                'http_method' => 'GET',
                'http_path' => '/',
            ],
            [
                'name' => '登录',
                'slug' => 'auth.login',
                'http_method' => '',
                'http_path' => "/auth/login\r\n/auth/logout",
            ],
            [
                'name' => '个人设置',
                'slug' => 'auth.setting',
                'http_method' => 'GET,PUT',
                'http_path' => '/auth/setting',
            ],
            [
                'name' => '系统管理',
                'slug' => 'auth.management',
                'http_method' => '',
                'http_path' => "/auth/roles\r\n/auth/permissions\r\n/auth/menu\r\n/auth/logs",
            ]
        ];
        $permissions = array_merge($permissions, $this->custom_permissions);
        Permission::insert($permissions);

        Role::first()->permissions()->save(Permission::first());

        // add default menus.
        Menu::truncate();
        $menus = [
            [
                'parent_id' => 0,
                'order' => 1,
                'title' => '首页',
                'icon' => 'fa-bar-chart',
                'uri' => '/',
            ],
            [
                'parent_id' => 0,
                'order' => 2,
                'title' => '系统管理',
                'icon' => 'fa-tasks',
                'uri' => '',
            ],
            [
                'parent_id' => 2,
                'order' => 3,
                'title' => '管理员',
                'icon' => 'fa-users',
                'uri' => 'auth/users',
            ],
            [
                'parent_id' => 2,
                'order' => 4,
                'title' => '角色',
                'icon' => 'fa-user',
                'uri' => 'auth/roles',
            ],
            [
                'parent_id' => 2,
                'order' => 5,
                'title' => '权限',
                'icon' => 'fa-ban',
                'uri' => 'auth/permissions',
            ],
            [
                'parent_id' => 2,
                'order' => 6,
                'title' => '菜单',
                'icon' => 'fa-bars',
                'uri' => 'auth/menu',
            ],
            [
                'parent_id' => 2,
                'order' => 7,
                'title' => '操作日志',
                'icon' => 'fa-history',
                'uri' => 'auth/logs',
            ],
        ];
        $menus = array_merge($menus, $this->custom_menus);
        Menu::insert($menus);

        // add role to menu.
        Menu::find(2)->roles()->save(Role::first());
    }
}
