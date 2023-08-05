<?php
/**
 * IP地址
 * @author shiroi <707305003@qq.com>
 */

declare (strict_types=1);

namespace generate\validate;

class Ip extends Rule
{
    protected string $name = 'ip';
    protected string  $msg = '必须为IP地址';
}
