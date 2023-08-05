<?php
/**
 *
 * @author shiroi <707305003@qq.com>
 */

use app\admin\listener\AdminUserLogin;
use app\admin\listener\AdminUserLogout;

return [
    'bind' => [

    ],

    'listen' => [
        // AdminUserLogin
        'AdminUserLogin'  => [
            AdminUserLogin::class,
        ],
        'AdminUserLogout' => [
            AdminUserLogout::class,
        ],
        'AppInit'         => [],
        'HttpRun'         => [],
        'HttpEnd'         => [],
        'LogLevel'        => [],
        'LogWrite'        => [],
    ],

    'subscribe' => [
    ],
];
