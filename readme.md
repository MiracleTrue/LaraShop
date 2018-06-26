## 项目部署
```
mv .env.example .env
php artisan key:generate

运行 Laravel Mix
yarn config set registry https://registry.npm.taobao.org
SASS_BINARY_SITE=http://npm.taobao.org/mirrors/node-sass yarn

```
##### 服务器后台运行的服务:
- `npm run watch-poll &`
- `php artisan horizon &`

## .env文件详解:
###### 基础
- APP_NAME=`项目名称`
- APP_ENV=`开发:local 测试:testing 预上线:staging 正式环境: production`
- APP_KEY=`php artisan key:generate 生成`
- APP_DEBUG=`开启Debug:true   关闭Debug:false 生产环境必须关闭`
- APP_LOG_LEVEL=`日志记录的等级默认记录全部 debug 生成环境应该为:error`
- APP_URL=`项目的Url地址  http://www.xxx.com`
- DEBUGER_ENABLE=`是否开启 Debugbar`
## Composer插件:
```
将所有配置文件 publish 出来
php artisan vendor:publish
```

###### 安装 Laravel-ide-helper
```
composer require barryvdh/laravel-ide-helper
添加对应配置到 .gitignore 文件中：
.idea
_ide_helper.php
_ide_helper_models.php
.phpstorm.meta.php

以下命令生成代码对应文档：
php artisan ide-helper:generate
```
###### 安装 Debugbar
```
composer require "barryvdh/laravel-debugbar:~3.1" --dev

生成配置文件，存放位置 config/debugbar.php：
php artisan vendor:publish --provider="Barryvdh\Debugbar\ServiceProvider"

打开 config/debugbar.php，将 enabled 的值设置为：
'enabled' => env('DEBUGER_ENABLE', false),

-.安装 DingoAPI
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

###### Redis 队列驱动器依赖
```
composer require "predis/predis:~1.0"
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