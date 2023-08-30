<?php

use think\migration\Seeder;
use think\facade\Db;
use \app\common\model\{AdminMenu, AdminRole};

class AdminMenus extends Seeder
{
    protected AdminMenu $adminMenu;

    protected AdminRole $adminRole;

    public function getSeedData(): array
    {
        return [
            [
                "name" => "后台首页",
                "url" => "admin/index/index",
                "icon" => "fas fa-home",
                "is_show" => 1,
                "is_top" => 1,
                "sort_number" => 1,
                "log_method" => "不记录",
            ],
            [
                "name" => "系统管理",
                "url" => "admin/system/manage",
                "icon" => "fas fa-desktop",
                "is_show" => 1,
                "is_top" => 1,
                "sort_number" => 2,
                "log_method" => "不记录",
                "Sons" => [
                    [
                        "name" => "用户管理",
                        "url" => "admin/admin_user/index",
                        "icon" => "fas fa-user",
                        "is_show" => 1,
                        "is_top" => 0,
                        "sort_number" => 3,
                        "log_method" => "不记录",
                        "Sons" => [
                            [
                                "name" => "添加用户",
                                "url" => "admin/admin_user/add",
                                "icon" => "fas fa-plus",
                                "is_show" => 0,
                                "is_top" => 0,
                                "sort_number" => 4,
                                "log_method" => "POST"
                            ],
                            [
                                "name" => "修改用户",
                                "url" => "admin/admin_user/edit",
                                "icon" => "fas fa-edit",
                                "is_show" => 0,
                                "is_top" => 0,
                                "sort_number" => 5,
                                "log_method" => "POST"
                            ],
                            [
                                "name" => "删除用户",
                                "url" => "admin/admin_user/del",
                                "icon" => "fas fa-close",
                                "is_show" => 0,
                                "is_top" => 0,
                                "sort_number" => 6,
                                "log_method" => "POST"
                            ],
                        ]
                    ],
                    [
                        "name" => "角色管理",
                        "url" => "admin/admin_role/index",
                        "icon" => "fas fa-user-friends",
                        "is_show" => 1,
                        "is_top" => 0,
                        "sort_number" => 7,
                        "log_method" => "不记录",
                        "Sons" => [
                            [
                                "name" => "添加角色",
                                "url" => "admin/admin_role/add",
                                "icon" => "fas fa-plus",
                                "is_show" => 0,
                                "is_top" => 0,
                                "sort_number" => 8,
                                "log_method" => "POST"
                            ],
                            [
                                "name" => "修改角色",
                                "url" => "admin/admin_role/edit",
                                "icon" => "fas fa-edit",
                                "is_show" => 0,
                                "is_top" => 0,
                                "sort_number" => 9,
                                "log_method" => "POST"
                            ],
                            [
                                "name" => "删除角色",
                                "url" => "admin/admin_role/del",
                                "icon" => "fas fa-close",
                                "is_show" => 0,
                                "is_top" => 0,
                                "sort_number" => 10,
                                "log_method" => "POST"
                            ],
                            [
                                "name" => "角色授权",
                                "url" => "admin/admin_role/access",
                                "icon" => "fas fa-key",
                                "is_show" => 0,
                                "is_top" => 0,
                                "sort_number" => 11,
                                "log_method" => "POST"
                            ],
                        ]
                    ],
                    [
                        "name" => "菜单管理",
                        "url" => "admin/admin_menu/index",
                        "icon" => "fas fa-align-justify",
                        "is_show" => 1,
                        "is_top" => 0,
                        "sort_number" => 12,
                        "log_method" => "不记录",
                        "Sons" => [
                            [
                                "name" => "添加菜单",
                                "url" => "admin/admin_menu/add",
                                "icon" => "fas fa-plus",
                                "is_show" => 0,
                                "is_top" => 0,
                                "sort_number" => 13,
                                "log_method" => "POST"
                            ],
                            [
                                "name" => "修改菜单",
                                "url" => "admin/admin_menu/edit",
                                "icon" => "fas fa-edit",
                                "is_show" => 0,
                                "is_top" => 0,
                                "sort_number" => 14,
                                "log_method" => "POST"
                            ],
                            [
                                "name" => "删除菜单",
                                "url" => "admin/admin_menu/del",
                                "icon" => "fas fa-close",
                                "is_show" => 0,
                                "is_top" => 0,
                                "sort_number" => 15,
                                "log_method" => "POST"
                            ],
                        ]
                    ],
                    [
                        "name" => "操作日志",
                        "url" => "admin/admin_log/index",
                        "icon" => "fas fa-keyboard",
                        "is_show" => 1,
                        "is_top" => 0,
                        "sort_number" => 16,
                        "log_method" => "不记录",
                        "Sons" => [
                            [
                                "name" => "查看操作日志详情",
                                "url" => "admin/admin_log/view",
                                "icon" => "fas fa-search-plus",
                                "is_show" => 0,
                                "is_top" => 0,
                                "sort_number" => 17,
                                "log_method" => "不记录",
                            ]
                        ]
                    ],
                    [
                        "name" => "个人资料",
                        "url" => "admin/admin_user/profile",
                        "icon" => "fas fa-smile",
                        "is_show" => 1,
                        "is_top" => 0,
                        "sort_number" => 18,
                        "log_method" => "POST",
                    ],
                    [
                        "name" => "开发管理",
                        "url" => "admin/develop/manager",
                        "icon" => "fas fa-code",
                        "is_show" => 1,
                        "is_top" => 0,
                        "sort_number" => 32,
                        "log_method" => "不记录",
                        "Sons" => [
                            [
                                "name" => "代码生成",
                                "url" => "admin/generate/index",
                                "icon" => "fas fa-file-code",
                                "is_show" => 1,
                                "is_top" => 0,
                                "sort_number" => 33,
                                "log_method" => "不记录",
                            ],
                            [
                                "name" => "设置配置",
                                "url" => "admin/develop/setting",
                                "icon" => "fas fa-cogs",
                                "is_show" => 1,
                                "is_top" => 0,
                                "sort_number" => 34,
                                "log_method" => "不记录",
                                "Sons" => [
                                    [
                                        "name" => "设置管理",
                                        "url" => "admin/setting/index",
                                        "icon" => "fas fa-cog",
                                        "is_show" => 1,
                                        "is_top" => 0,
                                        "sort_number" => 35,
                                        "log_method" => "不记录",
                                        "Sons" => [
                                            [
                                                "name" => "添加设置",
                                                "url" => "admin/setting/add",
                                                "icon" => "fas fa-plus",
                                                "is_show" => 0,
                                                "is_top" => 0,
                                                "sort_number" => 36,
                                                "log_method" => "POST",
                                            ],
                                            [
                                                "name" => "修改设置",
                                                "url" => "admin/setting/edit",
                                                "icon" => "fas fa-pencil",
                                                "is_show" => 0,
                                                "is_top" => 0,
                                                "sort_number" => 37,
                                                "log_method" => "POST",
                                            ],
                                            [
                                                "name" => "删除设置",
                                                "url" => "admin/setting/del",
                                                "icon" => "fas fa-trash",
                                                "is_show" => 0,
                                                "is_top" => 0,
                                                "sort_number" => 38,
                                                "log_method" => "POST",
                                            ],
                                        ]
                                    ],
                                    [
                                        "name" => "设置分组管理",
                                        "url" => "admin/setting_group/index",
                                        "icon" => "fas fa-list",
                                        "is_show" => 1,
                                        "is_top" => 0,
                                        "sort_number" => 39,
                                        "log_method" => "不记录",
                                        "Sons" => [
                                            [
                                                "name" => "添加设置分组",
                                                "url" => "admin/setting_group/add",
                                                "icon" => "fas fa-plus",
                                                "is_show" => 0,
                                                "is_top" => 0,
                                                "sort_number" => 40,
                                                "log_method" => "POST",
                                            ],
                                            [
                                                "name" => "修改设置分组",
                                                "url" => "admin/setting_group/edit",
                                                "icon" => "fas fa-pencil",
                                                "is_show" => 0,
                                                "is_top" => 0,
                                                "sort_number" => 41,
                                                "log_method" => "POST",
                                            ],
                                            [
                                                "name" => "删除设置分组",
                                                "url" => "admin/setting_group/del",
                                                "icon" => "fas fa-trash",
                                                "is_show" => 0,
                                                "is_top" => 0,
                                                "sort_number" => 42,
                                                "log_method" => "POST",
                                            ],
                                        ]
                                    ]
                                ],
                            ],
                            [
                                "name" => "数据维护",
                                "url" => "admin/database/table",
                                "icon" => "fas fa-database",
                                "is_show" => 1,
                                "is_top" => 0,
                                "sort_number" => 49,
                                "log_method" => "不记录",
                                "Sons" => [
                                    [
                                        "name" => "查看表详情",
                                        "url" => "admin/database/view",
                                        "icon" => "fas fa-eye",
                                        "is_show" => 0,
                                        "is_top" => 0,
                                        "sort_number" => 50,
                                        "log_method" => "不记录",
                                    ],
                                    [
                                        "name" => "优化表",
                                        "url" => "admin/database/optimize",
                                        "icon" => "fas fa-refresh",
                                        "is_show" => 0,
                                        "is_top" => 0,
                                        "sort_number" => 51,
                                        "log_method" => "POST",
                                    ],
                                    [
                                        "name" => "修复表",
                                        "url" => "admin/database/repair",
                                        "icon" => "fas fa-circle-o-notch",
                                        "is_show" => 0,
                                        "is_top" => 0,
                                        "sort_number" => 52,
                                        "log_method" => "POST",
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            [
                "name" => "用户管理",
                "url" => "admin/user/manage",
                "icon" => "fas fa-users",
                "is_show" => 1,
                "is_top" => 0,
                "sort_number" => 19,
                "log_method" => "不记录",
                "Sons" => [
                    [
                        "name" => "用户管理",
                        "url" => "admin/user/index",
                        "icon" => "fas fa-user",
                        "is_show" => 1,
                        "is_top" => 0,
                        "sort_number" => 20,
                        "log_method" => "不记录",
                        "Sons" => [
                            [
                                "name" => "添加用户",
                                "url" => "admin/user/add",
                                "icon" => "fas fa-plus",
                                "is_show" => 0,
                                "is_top" => 0,
                                "sort_number" => 21,
                                "log_method" => "POST",
                            ],
                            [
                                "name" => "修改用户",
                                "url" => "admin/user/edit",
                                "icon" => "fas fa-pencil",
                                "is_show" => 0,
                                "is_top" => 0,
                                "sort_number" => 22,
                                "log_method" => "POST",
                            ],
                            [
                                "name" => "删除用户",
                                "url" => "admin/user/del",
                                "icon" => "fas fa-trash",
                                "is_show" => 0,
                                "is_top" => 0,
                                "sort_number" => 23,
                                "log_method" => "POST",
                            ],
                            [
                                "name" => "启用用户",
                                "url" => "admin/user/enable",
                                "icon" => "fas fa-circle",
                                "is_show" => 0,
                                "is_top" => 0,
                                "sort_number" => 24,
                                "log_method" => "POST",
                            ],
                            [
                                "name" => "禁用用户",
                                "url" => "admin/user/disable",
                                "icon" => "fas fa-circle",
                                "is_show" => 0,
                                "is_top" => 0,
                                "sort_number" => 25,
                                "log_method" => "POST",
                            ]
                        ]
                    ],
                    [
                        "name" => "用户等级管理",
                        "url" => "admin/user_level/index",
                        "icon" => "fas fa-th-list",
                        "is_show" => 1,
                        "is_top" => 0,
                        "sort_number" => 26,
                        "log_method" => "不记录",
                        "Sons" => [
                            [
                                "name" => "添加用户等级",
                                "url" => "admin/user_level/add",
                                "icon" => "fas fa-plus",
                                "is_show" => 0,
                                "is_top" => 0,
                                "sort_number" => 27,
                                "log_method" => "POST",
                            ],
                            [
                                "name" => "修改用户等级",
                                "url" => "admin/user_level/edit",
                                "icon" => "fas fa-pencil",
                                "is_show" => 0,
                                "is_top" => 0,
                                "sort_number" => 28,
                                "log_method" => "POST",
                            ],
                            [
                                "name" => "删除用户等级",
                                "url" => "admin/user_level/del",
                                "icon" => "fas fa-trash",
                                "is_show" => 0,
                                "is_top" => 0,
                                "sort_number" => 29,
                                "log_method" => "POST",
                            ],
                            [
                                "name" => "启用用户等级",
                                "url" => "admin/user_level/enable",
                                "icon" => "fas fa-circle",
                                "is_show" => 0,
                                "is_top" => 0,
                                "sort_number" => 30,
                                "log_method" => "POST",
                            ],
                            [
                                "name" => "禁用用户等级",
                                "url" => "admin/user_level/disable",
                                "icon" => "fas fa-circle",
                                "is_show" => 0,
                                "is_top" => 0,
                                "sort_number" => 31,
                                "log_method" => "POST",
                            ]
                        ]
                    ]
                ]
            ],
            [
                "name" => "设置中心",
                "url" => "admin/setting/center",
                "icon" => "fas fa-cogs",
                "is_show" => 1,
                "is_top" => 0,
                "sort_number" => 43,
                "log_method" => "不记录",
                "Sons" => [
                    [
                        "name" => "所有配置",
                        "url" => "admin/setting/all_setting",
                        "icon" => "fas fa-list",
                        "is_show" => 1,
                        "is_top" => 0,
                        "sort_number" => 44,
                        "log_method" => "不记录",
                    ],
                    [
                        "name" => "后台设置",
                        "url" => "admin/setting/admin_setting",
                        "icon" => "fas fa-adjust",
                        "is_show" => 1,
                        "is_top" => 0,
                        "sort_number" => 45,
                        "log_method" => "不记录",
                    ],
                    [
                        "name" => "前台设置",
                        "url" => "admin/setting/index_setting",
                        "icon" => "fas fa-user",
                        "is_show" => 1,
                        "is_top" => 0,
                        "sort_number" => 45,
                        "log_method" => "不记录",
                    ],
                    [
                        "name" => "对象存储设置",
                        "url" => "admin/setting/cloud_setting",
                        "icon" => "fas fa-cloud",
                        "is_show" => 1,
                        "is_top" => 0,
                        "sort_number" => 45,
                        "log_method" => "不记录",
                    ],
                    [
                        "name" => "微信设置",
                        "url" => "admin/setting/wechat_setting",
                        "icon" => "fas fa-comment",
                        "is_show" => 1,
                        "is_top" => 0,
                        "sort_number" => 45,
                        "log_method" => "不记录",
                    ],
                    [
                        "name" => "基本设置",
                        "url" => "admin/setting/config_setting",
                        "icon" => "fas fa-wrench",
                        "is_show" => 1,
                        "is_top" => 0,
                        "sort_number" => 45,
                        "log_method" => "不记录",
                    ],
                    [
                        "name" => "字节设置",
                        "url" => "admin/setting/bytedance",
                        "icon" => "fa fa-tumblr",
                        "is_show" => 1,
                        "is_top" => 0,
                        "sort_number" => 45,
                        "log_method" => "不记录",
                    ],
                    [
                        "name" => "设置详情",
                        "url" => "admin/setting/info",
                        "icon" => "fas fa-info",
                        "is_show" => 0,
                        "is_top" => 0,
                        "sort_number" => 47,
                        "log_method" => "不记录",
                    ],
                    [
                        "name" => "更新设置",
                        "url" => "admin/setting/update",
                        "icon" => "fas fa-pencil",
                        "is_show" => 0,
                        "is_top" => 0,
                        "sort_number" => 46,
                        "log_method" => "POST",
                    ],
                ]
            ],
            [
                "name" => "通用操作",
                "url" => "admin/common/option",
                "icon" => "fas fa-list",
                "is_show" => 0,
                "is_top" => 0,
                "sort_number" => 53,
                "log_method" => "不记录",
                "Sons" => [
                    [
                        "name" => "表单上传文件",
                        "url" => "admin/file/upload",
                        "icon" => "fas fa-cloud-upload-alt",
                        "is_show" => 0,
                        "is_top" => 0,
                        "sort_number" => 54,
                        "log_method" => "不记录",
                    ],
                    [
                        "name" => "编辑器上传文件",
                        "url" => "admin/file/editor",
                        "icon" => "fas fa-upload",
                        "is_show" => 0,
                        "is_top" => 0,
                        "sort_number" => 55,
                        "log_method" => "不记录",
                    ],
                ]
            ],
            [
                //TODO 用于测试（记得删除）
                "name" => "测试案例",
                "url" => "admin/common/option",
                "icon" => "fas fa-list",
                "is_show" => 1,
                "is_top" => 0,
                "sort_number" => 1000,
                "log_method" => "不记录",
                "Sons" => [
                    [
                        "name" => "测试案例（一）",
                        "url" => "admin/test/index",
                        "icon" => "fas fa-list",
                        "is_show" => 1,
                        "is_top" => 0,
                        "sort_number" => 1000,
                        "log_method" => "不记录",
                        "Sons" => [
                            [
                                "name" => "添加测试",
                                "url" => "admin/test/add",
                                "icon" => "fas fa-plus",
                                "is_show" => 0,
                                "is_top" => 0,
                                "sort_number" => 1000,
                                "log_method" => "POST",
                            ],
                            [
                                "name" => "修改测试",
                                "url" => "admin/test/edit",
                                "icon" => "fas fa-pencil",
                                "is_show" => 0,
                                "is_top" => 0,
                                "sort_number" => 1000,
                                "log_method" => "POST",
                            ],
                            [
                                "name" => "删除测试",
                                "url" => "admin/test/del",
                                "icon" => "fas fa-trash",
                                "is_show" => 0,
                                "is_top" => 0,
                                "sort_number" => 1000,
                                "log_method" => "POST",
                            ],
                            [
                                "name" => "启用测试",
                                "url" => "admin/test/enable",
                                "icon" => "fas fa-circle",
                                "is_show" => 0,
                                "is_top" => 0,
                                "sort_number" => 1000,
                                "log_method" => "POST",
                            ],
                            [
                                "name" => "禁用测试",
                                "url" => "admin/test/disable",
                                "icon" => "fas fa-circle",
                                "is_show" => 0,
                                "is_top" => 0,
                                "sort_number" => 1000,
                                "log_method" => "POST",
                            ]
                        ]
                    ],
                    [
                        "name" => "测试案例（二）",
                        "url" => "admin/test1/index",
                        "icon" => "fas fa-list",
                        "is_show" => 1,
                        "is_top" => 0,
                        "sort_number" => 1000,
                        "log_method" => "不记录",
                        "Sons" => [
                            [
                                "name" => "添加测试",
                                "url" => "admin/test1/add",
                                "icon" => "fas fa-plus",
                                "is_show" => 0,
                                "is_top" => 0,
                                "sort_number" => 1000,
                                "log_method" => "POST",
                            ],
                            [
                                "name" => "修改测试",
                                "url" => "admin/test1/edit",
                                "icon" => "fas fa-pencil",
                                "is_show" => 0,
                                "is_top" => 0,
                                "sort_number" => 1000,
                                "log_method" => "POST",
                            ],
                            [
                                "name" => "删除测试",
                                "url" => "admin/test1/del",
                                "icon" => "fas fa-trash",
                                "is_show" => 0,
                                "is_top" => 0,
                                "sort_number" => 1000,
                                "log_method" => "POST",
                            ],
                            [
                                "name" => "启用测试",
                                "url" => "admin/test1/enable",
                                "icon" => "fas fa-circle",
                                "is_show" => 0,
                                "is_top" => 0,
                                "sort_number" => 1000,
                                "log_method" => "POST",
                            ],
                            [
                                "name" => "禁用测试",
                                "url" => "admin/test1/disable",
                                "icon" => "fas fa-circle",
                                "is_show" => 0,
                                "is_top" => 0,
                                "sort_number" => 1000,
                                "log_method" => "POST",
                            ]
                        ]
                    ],
                    [
                        "name" => "测试案例（三）",
                        "url" => "admin/form_design/index",
                        "icon" => "fas fa-list",
                        "is_show" => 1,
                        "is_top" => 0,
                        "sort_number" => 1000,
                        "log_method" => "不记录",
                        "Sons" => [
                            [
                                "name" => "表单设计页",
                                "url" => "admin/form_design/design",
                                "icon" => "fa-pencil-alt",
                                "is_show" => 0,
                                "is_top" => 0,
                                "sort_number" => 1000,
                                "log_method" => "不记录",
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }

    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $this->adminMenu = new AdminMenu();
        $this->adminRole = new AdminRole();
        $data = self::getSeedData();
        //后台角色列表
        $AdminRole = $this->adminRole->field('id,url')->select();

        //初始化hash值
        $AdminMenuList = $this->adminMenu->field('id,hash,name,url')->select();
        foreach ($AdminMenuList as $v){
            if (empty($v['hash'])){
                $v->hash = md5($v['name'].$v['url']);
                $v->save();
            }
        }
        $AdminRoleUrls = [];
        //角色旧菜单数据
        foreach ($AdminRole as $v) {
            $AdminRoleUrls[$v['id']] = array_column($this->adminMenu->whereIn('id', $v['url'])->field('id,hash,name,url')->select()->toArray(), 'hash');
        }
        //截断表
        Db::query('truncate table ' . $this->adminMenu->getTable());
        self::menuSeed($data, 0);
        //新菜单数据
        foreach ($AdminRoleUrls as $k => $v) {
            if ($k == 1) { //角色id=1, 填充所有菜单url权限
                $NewAdminMenu = $this->adminMenu->field('id')->select()->toArray();
                $url_str = implode(',', array_column($NewAdminMenu, 'id'));
                $this->adminRole->where(['id' => $k])->update(['url' => $url_str]);
            } else {
                $NewAdminMenu = $this->adminMenu->field('id,hash,name,url')->whereIn('hash', $v)->select()->toArray();
                $url_str = implode(',', array_column($NewAdminMenu, 'id'));
                $this->adminRole->where(['id' => $k])->update(['url' => $url_str]);
            }
            dump("角色ID-{$k}. {$url_str}");
        }
    }

    public function menuSeed($menu = [], $id = 0)
    {
        $time = time();
        try {
            foreach ($menu as $k => $v) {
                $Sons = $v['Sons'] ?? [];
                $v['parent_id'] = (int)$id;
                $v['hash'] = md5($v['name'].$v['url']);
                $v['create_time'] = $time;
                $v['update_time'] = $time;
                unset($v['Sons']);

                $menu_id = $this->adminMenu->replace()->insertGetId($v);
                if (!empty($Sons)) {
                    self::menuSeed($Sons, $menu_id);
                }
            }
        } catch (\Throwable $e) {
            dd('line:' . $e->getLine() . ',message:' . $e->getMessage());
        }
    }
}