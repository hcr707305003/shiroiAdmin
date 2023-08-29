<?php
/**
 * User: Shiroi
 * EMail: 707305003@qq.com
 */

namespace app\admin\controller;

class FormDesignController extends AdminBaseController
{
    /**
     * todo 暂时有问题，影响pjax刷新
     * @return string
     * @throws \Exception
     */
    public function index(): string
    {
        return $this->fetch();
    }
}