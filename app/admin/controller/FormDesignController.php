<?php
/**
 * User: Shiroi
 * EMail: 707305003@qq.com
 */

namespace app\admin\controller;

use Exception;

class FormDesignController extends AdminBaseController
{
    /**
     * @return string
     * @throws Exception
     */
    public function index(): string
    {
        return $this->fetch();
    }

    public function design(): string
    {
        return $this->fetch();
    }
}