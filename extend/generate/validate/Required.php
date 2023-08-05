<?php
/**
 * 非空
 * @author shiroi <707305003@qq.com>
 */

declare (strict_types=1);

namespace generate\validate;

class Required extends Rule
{

    protected string $name = 'required';
    protected string $msg = '不能为空';
}
