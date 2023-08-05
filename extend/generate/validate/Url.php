<?php
/**
 * url
 * @author shiroi <707305003@qq.com>
 */
declare (strict_types=1);

namespace generate\validate;

class Url extends Rule
{
    protected string $name = 'url';
    protected string $msg = '必须为正确的网址';
}
