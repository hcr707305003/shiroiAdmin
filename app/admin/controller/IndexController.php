<?php
/**
 * 首页控制器
 * @author shiroi <707305003@qq.com>
 */

declare (strict_types=1);

namespace app\admin\controller;

use Exception;

class IndexController extends AdminBaseController
{
    /**
     * 后台首页
     * @return string
     * @throws Exception
     */
    public function index(): string
    {
        return  $this->fetch();
    }
}