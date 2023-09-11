<?php
/**
 * User: Shiroi
 * EMail: 707305003@qq.com
 */

namespace app\common\traits;

trait PluginTrait
{
    public function getDatePath(): string
    {
        return (property_exists($this, 'path') ? $this->path: public_path()) . date('Ymd/');
    }
}