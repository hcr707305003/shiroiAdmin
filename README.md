# shiroi_admin
基于`ThinkPHP6.0`+`AdminLTE3.2`的后台管理系统

## 安装步骤
  - ### 本地安装
    1. ##### 复制`env`文件
        ```shell
        copy .example.env .env   
        ```
    2. ##### 安装依赖
        ```shell
        composer install
        ```

    3. ##### 创建数据库
        使用navicat工具或命令创建数据库，注意编码必须为`utf8mb4`格式，例如：
        ~~~sql
        create database if not exists `shiroi_admin` default character set utf8mb4 collate utf8mb4_unicode_ci;
        ~~~
    4. ##### 修改`env`文件的数据库配置(详细可以看.example.env)
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
    5. ##### 数据库迁移
        ```shell
        #表迁移
        php think migrate:run 
        
        #数据填充
        php think seed:run
        ```
    6. ##### 生成密钥
       ```shell
       #生成APP_KEY
       php think generate:app_key
       
       #生成JWT_KEY
       php think generate:jwt_key
       ```
    
  - ### docker安装 (访问`http://127.0.0.1:9001`)
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
访问`/admin`，默认开发管理员的账号为`develop_admin`，超级管理员的账号为`super_admin`，**对应密码查看迁移命令行输出内容**。

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
    ```phpt
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