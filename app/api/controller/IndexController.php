<?php
/**
 *
 * @author shiroi <707305003@qq.com>
 */

declare (strict_types=1);

namespace app\api\controller;

use think\response\Json;

class IndexController extends ApiBaseController
{
    public function index(): Json
    {
        return api_success();
    }
}
