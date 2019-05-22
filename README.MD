# LARAVEL-ACTION-LOG

#### 项目介绍

基于ORM模型的操作记录工具包

```
注意：由于使用的orm模型事件，因此在批量更新的时候是不会触发操作日志记录的。
```


#### 安装说明

1. 安装接口包
    
    `composer require sureyee/laravel-action-log`
    
2. 运行 `php artisan vendor:publish` 发布配置项文件

#### 使用说明

1. 在 `actionlog.php` 中配置需要监听的模型
    ```php
    return [
        'watching' => [
            \App\Models\User::class
        ]
    ];
    ```
2. 在注册的模型中添加 `trait`
    ```php
    use Sureyee\ActionLog\Traits\ActionLogAble;   
    
    class User extends Authenticatable
    {
        use Notifiable, HasApiTokens, SoftDeletes, HasRolesAndAbilities, ActionLogAble;
    }
    ```

#### 排除字段

如果有些冗余字段不需要进行监听则可以加入到`$excepts`数组中，在更新操作时，会忽略该字段的值，如果只有忽略值更新，则不会进行记录。
```php
   class User extends Authenticatable
   {
        protected $excepts = ['updated_at', 'created_at'];
   }
```