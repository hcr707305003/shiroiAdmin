<?php
/**
 * User: Shiroi
 * EMail: 707305003@qq.com
 */

namespace app\admin\service;

use app\admin\traits\ServiceTrait;

class UserLevelService extends AdminBaseService
{
    use ServiceTrait;

    public static string $model = 'app\admin\model\UserLevel';
}