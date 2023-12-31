<?php
// +----------------------------------------------------------------------
// | 控制台配置
// +----------------------------------------------------------------------

return [
    // 指令定义
    'commands' => [
        // 初始化env文件
        'init:env'             => \app\command\InitEnv::class,
        // 生成新的app_key
        'generate:app_key'     => \app\command\GenerateAppKey::class,
        // 生成新的jwt_key
        'generate:jwt_key'     => \app\command\GenerateJwtKey::class,
        // 重置后台管理员密码
        'reset:admin_password' => \app\command\ResetAdminPassword::class,
        // 一键创建后台视图模块
        'create:admin_background_view' => \app\command\CreateBackgroundView::class,



        //socket
        //所有模块socket服务
        'socket:run' => \app\command\socket\SocketServer::class,
        //admin模块的socket服务
        'admin_socket:run' => \app\command\socket\AdminSocketServer::class,
        //api的模块的socket服务
        'api_socket:run' => \app\command\socket\ApiSocketServer::class,
        //index模块的socket服务
        'index_socket:run' => \app\command\socket\IndexSocketServer::class,
    ],
];
