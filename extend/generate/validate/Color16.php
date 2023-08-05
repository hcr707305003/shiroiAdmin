<?php
/**
 * 16进制颜色代码
 * @author shiroi <707305003@qq.com>
 */

declare (strict_types=1);

namespace generate\validate;

class Color16 extends Rule
{
    protected string $name = 'color16';

    protected string  $msg = '必须为十六进制颜色代码';
}
