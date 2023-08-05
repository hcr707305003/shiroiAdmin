<?php
/**
 * 后台角色验证器
 * @author shiroi <707305003@qq.com>
 */

declare (strict_types=1);

namespace app\admin\validate;

class AdminRoleValidate extends AdminBaseValidate
{
    protected $rule = [
        'name|名称'        => 'require',
        'description|介绍' => 'require',
        'rules|权限'       => 'require',
    ];

    protected $scene = [
        'admin_add'  => ['name', 'description'],
        'admin_edit' => ['name', 'description'],
    ];
}
