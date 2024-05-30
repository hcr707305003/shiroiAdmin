# shiroi_admin
基于`ThinkPHP6.0`+`AdminLTE3.2`的后台管理系统

## 相似项目
 [shiroiWebman-基于当前项目提升速度](https://github.com/hcr707305003/shiroiWebman)

## 安装步骤
  - ### 复制`env`文件
  ```shell
  copy .example.env .env   
  ```
  - ### 安装依赖
  ```shell
  composer install
  ```
  - ### 生成密钥
   ```shell
   #生成APP_KEY
   php think generate:app_key
 
   #生成JWT_KEY
   php think generate:jwt_key
   ```
  - ### 本地安装
    - ##### 创建数据库
      使用navicat工具或命令创建数据库，注意编码必须为`utf8mb4`格式，例如：
      ~~~sql
      create database if not exists `shiroi_admin` default character set utf8mb4 collate utf8mb4_unicode_ci;
      ~~~
    - ##### 修改`env`文件的数据库配置(详细可以看.example.env)
      - 如果是docker连接的话,这里是镜像名(shiroi-admin-mysql)或者是局域网ip
      - 可自定义数据库数据库配置，如果为local，则引入的是LOCAL_DATABASE项，如果为docker，则引入的是DOCKER_DATABASE(可以自定义数据库连接，对应需要配置相关数据库配置)
      ```ini
       #默认空，默认连接下面DATABASE数据库配置
       [ENVIRONMENT]
       active = 
       #active = docker #例：如果为docker，则连接DOCKER_DATABASE配置
 
       [DATABASE]
       TYPE=mysql
       HOSTNAME=127.0.0.1
       DATABASE=数据库名称
       USERNAME=数据库用户名
       PASSWORD=数据库密码
       HOSTPORT=3306
       CHARSET=utf8mb4
       DEBUG=false
 
       #注意：使用docker容器名称，使用php think 运行脚本是会报连接数据库错误（可以使用局域网ip(`ipconfig` | `ifconfig`)处理这个错误）
       [DOCKER_DATABASE]
       TYPE=mysql
       HOSTNAME=shiroi-admin-mysql
       DATABASE=shiroi_admin
       USERNAME=root
       PASSWORD=root
       HOSTPORT=3306
       CHARSET=utf8mb4
       DEBUG=true
      ```
    - ##### 数据库迁移
        ```shell
        #表迁移
        php think migrate:run 
    
        #数据填充
        php think seed:run
        ```
  - ### docker安装 (访问`http://localhost:9001`)
    ```
    //启动
    docker-compose --env-file ./docker/.env up -d --build

    //停止
    docker-compose --env-file ./docker/.env down

    //清除未使用的镜像缓存
    docker system prune -af

    //重新build镜像
    docker build --cache-from shiroi-admin-web:latest .
    ```


**注意事项**
运行迁移命令的时候会生成2个用户，开发管理员（`develop_admin`），超级管理（`super_admin`），为了防止部分开发者安全意识薄弱，上线后不修改默认超级管理员账号密码，导致后台被入侵，所以当前版本后台密码会随机生成，在运行迁移命令的时候命令行中会显示生成的密码，请自行复制使用。

## 配置Web根目录URL重写
将`public`目录配置为web根目录，然后配置URL重写规则，具体可参考 [ThinkPHP6.0完全开发手册](https://www.kancloud.cn/manual/thinkphp6_0/1037488) URL访问模块

## 访问后台
访问`/admin`，默认开发管理员的账号为`develop_admin`，超级管理员的账号为`super_admin`，密码默认`123456`，**对应密码查看迁移命令行输出内容**。

## 重置管理员密码
```shell
//设置用户1密码为123456（默认uid为1）
php think reset:admin_password --uid=1 123456
``` 
## 额外功能
  - ### 后台 
    #### 1. 一键创建后台视图模块(默认创建到`/app/admin/view/company`,这里的`company`对应的是`module`名)
    `php think create:admin_background_view --name=企业 --module=company`
    
    #### 2. 根据一键创建后台视图快速生成增删改查页面
    案例：`app/admin/controller/Test1Controller.php`

    #### 3. 腾讯云cos、阿里云oss、七牛云 (列表、上传、删除)插件
     - 修改插件配置
       1. (方法一) 后台->设置中心->前台设置(修改云存储商的配置后)再使用改插件
       2. (方法二) 修改 `database/seeds/Setting.php` 文件后（注意： `"is_forced_update" => true`才会覆盖原配置），执行`php think seed:run -s Setting` 
    
     - 使用插件  
    ```php
    //上传的文件
    $file = request()->file('file');
    
    //实例化（默认阿里云）
    // aliyun  => 阿里云
    // tencent => 腾讯云
    // qiniu   => 七牛云
    $oss = new \app\common\plugin\Oss('aliyun');
    
    //设置bucket
    $oss = $oss->setBucket('mhjzjt-disk');
    
    //列表
    $list_info = $oss->get(10);
    dump($list_info);
    
    //上传
    $put_info = $oss->put('shiroi.png', $file->getPathname());
    dump($put_info);
    
    //删除
    $delete_info = $oss->delete(['shiroi.png']);
    dump($delete_info);
    ```

    #### 4. 抖音插件(未完善)
    ```php
    //实例化抖音api类
    $tiktok = new \app\common\plugin\TikTok();
    
    //获取抖音session （code或anonymous_code任意传一个）
    $data = $tiktok->code2Session(request()->only(['code', 'anonymous_code']));
    
    //通过session_key 和 前端传的encryptedData、iv进行解密，获取手机号信息
    if(($encryptedData = input('encryptedData')) && ($iv = input('iv'))) {
        if(($mobileData = $tiktok->decrypt($encryptedData,$data['data']['session_key'], $iv)) && is_array($mobileData)) {
                dump($mobileData);
            }
        }
    ```
    
    #### 5. 微信插件(小程序、公众号、网站应用、商户支付等)
    ```php
    //接收的参数
    $param = request()->post(['code', 'invite_code']);
    //实例化
    $wechatMiniProgram = new \app\common\plugin\WechatMiniProgram;
    //微信小程序工厂
    $wechat_mini_program = $wechatMiniProgram->create();
    //微信小程序支付
    $wechat_mini_program_pay = $wechatMiniProgram->createPay();
    //获取用户信息
    $data = $wechat_mini_program->auth->session($param['code']);
    dump($data);
    ```
    
    #### 6. 邮箱插件
    ```php
    dump((new \app\common\plugin\Mailer())
            //要发送给的邮箱以及标题
            ->generate('xxxxxx@qq.com', '哈哈哈哈')
            //要发送的内容
            ->setContent('<img src="{image}"/>图片测试', [
                'embed:image' => public_path('uploads/image') . 'user.png' //本地文件路径 embed:image => {image}
            ])
            //要发送的单行文本
            ->setLine('这是一行文本')
            ->send());
    ```
    
    #### 7. 日志系统
     - 访问浏览器 `http://域名/log_view`即可
     - 具体使用方式 [shiroi/think-log-viewer](https://packagist.org/packages/shiroi/think-log-viewer)

    #### 8. socket服务
    - 本地运行 
      ```php
      //运行所有服务(window下无法运行所有服务，默认运行第一个服务)
      php think socket:run start
    
      //运行admin模块服务
      php think admin_socket:run start
    
      //运行api模块服务
      php think api_socket:run start
    
      //运行index模块服务
      php think index_socket:run start    
      ```

    - docker 运行
    
        修改`docker/supervisor/supervisord.conf`文件
        ```config
        [supervisord]
        nodaemon=true
        
        #开启所有服务
        [program:all_socket]
        command=php think socket:run start
        directory=/var/www/html
        stdout_logfile=/var/www/html/supervisor/log/all_socket.log
        
        #开启admin模块服务
        #[program:admin_socket]
        #command=php think admin_socket:run start --mode d
        #directory=/var/www/html
        #stdout_logfile=/var/www/html/supervisor/log/admin_socket.log
        
        #开启api模块服务
        #[program:api_socket]
        #command=php think api_socket:run start --mode d
        #directory=/var/www/html
        #stdout_logfile=/var/www/html/supervisor/log/api_socket.log
        
        #开启index模块服务
        #[program:index_socket]
        #command=php think index_socket:run start --mode d
        #directory=/var/www/html
        #stdout_logfile=/var/www/html/supervisor/log/index_socket.log
        ```

    #### 9. ffmpeg流媒体
    ```php
    //设置文件
    $source = public_path() . 'test.mp4';
    //实例化ffmpeg类
    $ffmpeg = new \app\common\plugin\Ffmpeg();
    //获取视频第几秒的图片
    //dump($ffmpeg->getImageFormSeconds(10));
    //在视频中设置水印
    dump($ffmpeg->setWatermarkToVideo(public_path() . 'test.png'));
    ```
    具体使用方式 [php-ffmpeg/php-ffmpeg](https://packagist.org/packages/php-ffmpeg/php-ffmpeg)

    #### 10. 图片gd库调用类
    ```php
    //写入垂直居中文字、并设置颜色rgb(32, 40, 136)
    (new \app\common\plugin\ImageGd())
        ->appendContent('hello', [
            'position' => ImageGD::FONT_CENTER,
            'color' => [32, 40, 136]
        ])
        ->show();
    
    
    //写入图片并设置居中
    (new \app\common\plugin\ImageGd())
        //网路图片和本地图片皆可
        ->appendContent(public_path() . 'test.png', [
            'position' => ImageGD::FONT_CENTER,
            'width' => 200,
            'height' => 150,
        ], 'image')
        ->show();
    
    //写入图片和文字并设置同行居中
    (new \app\common\plugin\ImageGd())
        //网路图片和本地图片皆可
        ->appendContent([
            [
                'type' => 'image',
                'content' => public_path() . 'test.png',
                'width' => 200,
                'height' => 150
            ],
            [
                'content' => ' hello', //想实现间隔可以空格分离
                'color' => [32, 40, 136],
                'size' => 30
            ]
        ], [
            'position' => ImageGD::FONT_CENTER,
            'display' => 'inline_block',
        ])
        ->show();
    
    
    //设置背景图并设置文字
    //获取背景图
    $bg = 'https://img.xjh.me/img/63083657_p0.jpg';
    //设置背景图
    (new \app\common\plugin\ImageGd($bg))->appendContent('你好', [
        'position' => ImageGD::FONT_CENTER,
        'color' => [32, 40, 136],
        'size' => 30
    ])->show();
    ```

    #### 11. class动态创建类
    ```php
    //实例化
    (new \app\common\plugin\ClassHandle())
        //设置默认set方法内容
        ->setSetContent([
            'return' => 'void', //是否保留返回值
            'content' => '$this->{property} = ${property};' //设置默认set方法内容
        ])
        //设置默认get方法内容
        ->setGetContent([
            'argument' => false, //是否保留参数
            'return' => '{type}', //是否保留返回值
            'content' => 'return $this->{property};' //设置默认get方法内容
        ])
        //设置方法
        ->setMethod('method_1', [
            //设置参数 name、age
            'name' => [
                'default' => 'shiroi',
            ],
            'age' => 'int'
        ], [
            'description' => '这是一个测试方法', //设置方法注释
            'content' => 'echo "姓名：{$name}，年龄：{$age}";' //设置方法内容
        ])
        //设置属性
        ->setProperty('property_1', 'string', '', [
            'description' => '这是个属性', //设置属性描述
            'access' => 'private' //设置属性访问权限
        ], [
            //设置自定义方法
            'test' => [
                'description' => '这是一个testProperty_1方法', //设置方法描述
                'argument' => false, //是否保留参数
                'return' => 'self', //是否保留返回值
                'content' => 'return $this;', //设置方法内容
                'access' => 'private' //设置方法访问权限
            ],
            //设置get set方法
            'get',
            'set'
        ])
        //设置类名以及命名空间
        ->create('Test1Controller', 'app/common/controller');
    ```
    
    #### 12. 反射类获取类的属性、方法、注释等 （[来源-shiroi/think-reflection-annotation](https://packagist.org/packages/shiroi/think-reflection-annotation)）
    ```php
    //实例化反射类
    $factory = \Shiroi\ThinkReflectionAnnotation\reflection\Factory::getInstance($class);
    //反射类(类的详情、包含私有方法、私有属性)
    var_dump($factory->getClassSubject());
    
    //反射类的方法
    var_dump($factory->getMethodsSubject());
    
    //反射类的属性
    var_dump($factory->getPropertiesSubject());
    
    //反射类的doc
    var_dump($factory->getClassDocComment());
    
    //反射类的方法doc
    var_dump($factory->getMethodsDocComment());
    ```
