## 项目部署
```
//Linux服务器环境要求
支持Laravel 5.5 | PHP7.1 | MySql5.7 | Redis3.2
Composer | Git客户端 | crond服务

//安装
composer install

//crond服务
crontab -e 添加
* * * * * php /{项目绝对路径根目录}/artisan schedule:run >> /dev/null 2>&1    保存
crontab -u root -l   查看

//配置 env
mv .env.example .env
php artisan key:generate

//静态资源软链接
sudo php  artisan storage:link

//运行 Laravel Mix
yarn config set registry https://registry.npm.taobao.org
SASS_BINARY_SITE=http://npm.taobao.org/mirrors/node-sass yarn

//生产环境数据数据迁移
php artisan migrate:refresh
php artisan db:seed --class=AdminTablesSeeder

//测试环境数据数据迁移(含测试数据)
php artisan migrate:refresh --seed

//后台菜单和权限修改
database\seeds\AdminTablesSeeder.php 中修改后
php artisan admin:make UsersController --model=App\\Models\\User
php artisan db:seed --class=AdminTablesSeeder
```
##### 服务器后台运行的服务:
- `npm run watch-poll &`
- `php artisan horizon &`

## 常用 artisan 命令
```
//创建模型 & 数据填充 & 控制器
php artisan make:model Models/{模型名称} -mf         //模型 & 工厂
php artisan make:seeder {模型名称}Seeder             //数据填充名称一般为模型复数名
php artisan make:controller {控制器名称}Controller   //控制器名称一般为模型复数名

//创建验证器
php artisan make:request {验证器名称}Request

//创建任务
php artisan make:job {任务名称}

//创建事件
php artisan make:event {事件名称}

//创建监听器
php artisan make:listener UpdateProductSoldCount --event=OrderPaid

//创建通知类
php artisan make:notification OrderPaidNotification

//创建授权策略类
php artisan make:policy {模型名称}Policy   //OrderPolicy

//将所有配置文件 publish 出来
php artisan vendor:publish

//重命名工厂文件之后需要执行 ，否则会找不到对应的工厂文件。
composer dumpautoload

//清除配置文件缓存
php artisan config:cache
```

## .env文件详解:
###### 基础
- APP_NAME=`项目名称`
- APP_ENV=`开发:local 测试:testing 预上线:staging 正式环境: production`
- APP_KEY=`php artisan key:generate 生成`
- APP_DEBUG=`开启Debug:true   关闭Debug:false 生产环境必须关闭`
- APP_LOG_LEVEL=`日志记录的等级默认记录全部 debug 生成环境应该为:error`
- APP_URL=`项目的Url地址  http://www.xxx.com`
- DEBUGBAR_ENABLED=`是否开启 Debugbar`
## Composer 已安装插件:
###### 安装 Laravel-ide-helper IDE & 模型注释助手
```
composer require barryvdh/laravel-ide-helper
添加对应配置到 .gitignore 文件中：
.idea
_ide_helper.php
_ide_helper_models.php
.phpstorm.meta.php

//IDE助手:
php artisan ide-helper:generate

//模型注释助手:
composer require doctrine/dbal
php artisan ide-helper:models
```
###### 安装 Debugbar
```
composer require "barryvdh/laravel-debugbar:~3.1" --dev

生成配置文件，存放位置 config/debugbar.php：
php artisan vendor:publish --provider="Barryvdh\Debugbar\ServiceProvider"

打开 config/debugbar.php，将 enabled 的值设置为：
'enabled' => env('DEBUGBAR_ENABLED', false),
```

###### 安装 中文语言包
```
composer require overtrue/laravel-lang

然后修改系统语言，将原本的值 en 改成 zh-CN：
config/app.php
'locale' => 'zh-CN',
```

###### encore/laravel-admin 扩展包
```
composer require encore/laravel-admin "1.5.*"

php artisan vendor:publish --provider="Encore\Admin\AdminServiceProvider"
php artisan admin:install

1. 创建控制器
Laravel-Admin 的控制器创建方式与普通的控制器创建方式不太一样，要用 admin:make 来创建：

$ php artisan admin:make UsersController --model=App\\Models\\User

其中 --model=App\\Models\\User 代表新创建的这个控制器是要对 App\Models\User 这个模型做增删改查。
```
###### Redis 队列驱动器依赖
```
composer require "predis/predis:~1.0"

//创建队列失败表
php artisan queue:failed-table
```

###### Horizon 是 Laravel 生态圈里的一员，为 Laravel Redis 队列提供了一个漂亮的仪表板，允许我们很方便地查看和管理 Redis 队列任务执行的情况。
```
使用 Composer 安装：
composer require "laravel/horizon:~1.0"
安装完成后，使用 vendor:publish Artisan 命令发布相关文件：
php artisan vendor:publish --provider="Laravel\Horizon\HorizonServiceProvider"
分别是配置文件 config/horizon.php 和存放在 public/vendor/horizon 文件夹中的 CSS 、JS 等页面资源文件。

Horizon 是一个监控程序，需要常驻运行，我们可以通过以下命令启动：
php artisan horizon
安装了 Horizon 以后，我们将使用 horizon 命令来启动队列系统和任务监控，无需使用 queue:listen。
```

##### Alipay 和 WeChat 的 laravel 支付扩展包
```
composer require yansongda/pay

wget -O config/pay.php https://github.com/yansongda/laravel-pay/raw/master/config/pay.php

.env 文件里面配置

// alipay 配置
ALI_APP_ID=
ALI_PUBLIC_KEY=
ALI_PRIVATE_KEY=

// wechat 配置
WECHAT_APP_ID=
WECHAT_MINIAPP_ID=
WECHAT_APPID=
WECHAT_MCH_ID=
WECHAT_KEY=

//实例化
        Pay::alipay(config('pay.alipay'))->web([
            'out_trade_no' => $order->no, // 订单编号，需保证在商户端不重复
            'total_amount' => $order->total_amount, // 订单金额，单位元，支持小数点后两位
            'subject' => '支付 Laravel Shop 的订单：' . $order->no, // 订单标题
        ]);
```

##
## Composer 常用插件:

###### 安装 DingoAPI
```
你必须在你的项目中修改 composer.json 文件并且运行 composer update 命令来加载这个包的最新版本。
"require": {
    "dingo/api": "2.0.0-alpha1"
}
php artisan vendor:publish --provider="Dingo\Api\Provider\LaravelServiceProvider"
```

###### 导航的 Active 状态
```
composer require "hieu-le/active:~3.5"
函数:
function active_class($condition, $activeClass = 'active', $inactiveClass = '')
使用:
{{ active_class((if_route('category.show') && if_route_param('category', 1))) }}
```

###### 安装 HTMLPurifier for Laravel 5 ( XSS攻击 用户提交数据过滤器)
```
使用 Composer 安装：
composer require "mews/purifier:~2.0"
命令行下运行
php artisan vendor:publish --provider="Mews\Purifier\PurifierServiceProvider"
```

###### 图片验证码扩展包 mews/captcha
```
使用 Composer 安装：
composer require "mews/captcha:~2.0"
运行以下命令生成配置文件 config/captcha.php：
php artisan vendor:publish --provider='Mews\Captcha\CaptchaServiceProvider'
```

###### 安装 Guzzle HTTP 请求依赖包
```
composer require "guzzlehttp/guzzle:~6.3"
```

###### 安装 PinYin 基于 CC-CEDICT 词典的中文转拼音工具，是一套优质的汉字转拼音解决方案。
```
composer require "overtrue/pinyin:~3.0"
```

###### 使用 Laravel-permission 扩展包,权限和角色控制
```
composer require "spatie/laravel-permission:~2.7"
生成数据库迁移文件：
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="migrations"
php artisan migrate
生成配置信息：
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="config"
数据表：
roles —— 角色的模型表；
permissions —— 权限的模型表；
model_has_roles —— 模型与角色的关联表，用户拥有什么角色在此表中定义，一个用户能拥有多个角色；
role_has_permissions —— 角色拥有的权限关联表，如管理员拥有查看后台的权限都是在此表定义，一个角色能拥有多个权限；
model_has_permissions —— 模型与权限关联表，一个模型能拥有多个权限。
```

###### 用户切换工具 sudo-su
```
composer require "viacreative/sudo-su:~1.1"
添加 Provider :
app/Providers/AppServiceProvider.php
    public function register()
    {
        if (app()->isLocal()) {
            $this->app->register(\VIACreative\SudoSu\ServiceProvider::class);
        }
    }
发布资源文件:
php artisan vendor:publish --provider="VIACreative\SudoSu\ServiceProvider"
resources/views/layouts/app.blade.php
    @if (app()->isLocal())
        @include('sudosu::user-selector')
    @endif
```

###### 安装 easy-sms
```
composer require "overtrue/easy-sms"
php artisan make:provider EasySmsServiceProvider
教程:https://laravel-china.org/courses/laravel-advance-training-5.5/791/sms-provider
```