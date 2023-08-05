<?php
/**
 * api 中间件
 * @author shiroi <707305003@qq.com>
 */

$config = [];
if(env('api.allow_cross_domain')){
    $config = [
        \app\api\middleware\AllowCrossDomain::class
    ];
}

return $config;