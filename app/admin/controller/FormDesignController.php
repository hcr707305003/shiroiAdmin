<?php
/**
 * User: Shiroi
 * EMail: 707305003@qq.com
 */

namespace app\admin\controller;

class FormDesignController extends AdminBaseController
{
    public function index(): string
    {
        return  $this->fetch();
    }
}