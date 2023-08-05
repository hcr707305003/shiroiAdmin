<?php

use think\migration\Seeder;

class Setting extends Seeder
{
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
        $data =  [
            [
                "id" => 1,
                "setting_group_id" => 1,
                "name" => "基本设置",
                "description" => "后台的基本信息设置",
                "code" => "base",
                "content" => [
                    [
                        "name" => "后台名称",
                        "field" => "name",
                        "type" => "text",
                        "content" => "XX后台系统",
                        "option" => ""
                    ],
                    [
                        "name" => "后台简称",
                        "field" => "short_name",
                        "type" => "text",
                        "content" => "后台",
                        "option" => ""
                    ],
                    [
                        "name" => "后台作者",
                        "field" => "author",
                        "type" => "text",
                        "content" => "xx科技",
                        "option" => ""
                    ],
                    [
                        "name" => "作者网站",
                        "field" => "website",
                        "type" => "text",
                        "content" => "#",
                        "option" => ""
                    ],
                    [
                        "name" => "后台版本",
                        "field" => "version",
                        "type" => "text",
                        "content" => "0.1",
                        "option" => ""
                    ],
                    [
                        "name" => "后台LOGO",
                        "field" => "logo",
                        "type" => "image",
                        "content" => "\/static\/admin\/images\/logo.png",
                        "option" => ""
                    ]
                ],
                "is_forced_update" => false, //seed配置- 选填 - 是否覆盖更新原有数据
                "sort_number" => 1000
            ],
            [
                "id" => 2,
                "setting_group_id" => 1,
                "name" => "登录设置",
                "description" => "后台登录相关设置",
                "code" => "login",
                "content" => [
                    [
                        "name" => "登录token验证",
                        "field" => "token",
                        "type" => "switch",
                        "content" => "1",
                        "option" => ""
                    ],
                    [
                        "name" => "验证码",
                        "field" => "captcha",
                        "type" => "select",
                        "content" => "1",
                        "option" => "0||不开启\r\n1||图形验证码\r\n2||滑动验证"
                    ],
                    [
                        "name" => "登录背景",
                        "field" => "background",
                        "type" => "image",
                        "content" => "\/static\/admin\/images\/login-default-bg.jpg",
                        "option" => ""
                    ],
                    [
                        "name" => "极验ID",
                        "field" => "geetest_id",
                        "type" => "text",
                        "content" => "66cfc0f309e368364b753dad7d2f67f2",
                        "option" => ""
                    ],
                    [
                        "name" => "极验KEY",
                        "field" => "geetest_key",
                        "type" => "text",
                        "content" => "99750f86ec232c997efaff56c7b30cd3",
                        "option" => ""
                    ],
                    [
                        "name" => "登录重试限制",
                        "field" => "login_limit",
                        "type" => "switch",
                        "content" => "0",
                        "option" => "0||否\r\n1||是"
                    ],
                    [
                        "name" => "限制最大次数",
                        "field" => "login_max_count",
                        "type" => "number",
                        "content" => "5",
                        "option" => ""
                    ],
                    [
                        "name" => "禁止登录时长(小时)",
                        "field" => "login_limit_hour",
                        "type" => "number",
                        "content" => "2",
                        "option" => ""
                    ]
                ],
                "is_forced_update" => false, //seed配置- 选填 - 是否覆盖更新原有数据
                "sort_number" => 1000
            ],
            [
                "id" => 3,
                "setting_group_id" => 1,
                "name" => "安全设置",
                "description" => "安全相关配置",
                "code" => "safe",
                "content" => [
                    [
                        "name" => "加密key",
                        "field" => "admin_key",
                        "type" => "text",
                        "content" => "89ce3272dc949fc3698fe7108d1dbe37",
                        "option" => ""
                    ],
                    [
                        "name" => "SessionKeyUid",
                        "field" => "store_uid_key",
                        "type" => "text",
                        "content" => "admin_user_id",
                        "option" => ""
                    ],
                    [
                        "name" => "SessionKeySign",
                        "field" => "store_sign_key",
                        "type" => "text",
                        "content" => "admin_user_sign",
                        "option" => ""
                    ],
                    [
                        "name" => "后台用户密码强度检测",
                        "field" => "password_check",
                        "type" => "switch",
                        "content" => "0",
                        "option" => "0||关闭\r\n1||开启"
                    ],
                    [
                        "name" => "密码安全强度等级",
                        "field" => "password_level",
                        "type" => "select",
                        "content" => "2",
                        "option" => "1||简单密码\r\n2||中等密码\r\n3||复杂密码"
                    ],
                    [
                        "name" => "单设备登录",
                        "field" => "one_device_login",
                        "type" => "switch",
                        "content" => "0",
                        "option" => "0||关闭\r\n1||开启"
                    ],
                    [
                        "name" => "CSRFToken检测",
                        "field" => "check_token",
                        "type" => "switch",
                        "content" => "1",
                        "option" => ""
                    ],
                    [
                        "name" => "CSRFToken验证方法",
                        "field" => "check_token_action_list",
                        "type" => "multi_select",
                        "content" => "add,edit,del,import,profile,update",
                        "option" => "add||添加\r\nedit||修改\r\ndel||删除\r\nimport||导入\r\nprofile||修改资料\r\nupdate||更新"
                    ]
                ],
                "is_forced_update" => false, //seed配置- 选填 - 是否覆盖更新原有数据
                "sort_number" => 1000
            ],
            [
                "id" => 4,
                "setting_group_id" => 2,
                "name" => "阿里云OSS",
                "description" => "阿里云OSS配置",
                "code" => "aliyun_oss",
                "content" => [
                    [
                        "name" => "appId",
                        "field" => "appId",
                        "type" => "text",
                        "content" => "",
                        "option" => "appId"
                    ],
                    [
                        "name" => "appKey",
                        "field" => "appKey",
                        "type" => "text",
                        "content" => "",
                        "option" => "appKey"
                    ],
                    [
                        "name" => "region",
                        "field" => "region",
                        "type" => "text",
                        "content" => "",
                        "option" => "region地区，例：http://oss-cn-shenzhen.aliyuncs.com"
                    ]
                ],
                "is_forced_update" => false, //seed配置- 选填 - 是否覆盖更新原有数据
                "sort_number" => 1000
            ],
            [
                "id" => 5,
                "setting_group_id" => 2,
                "name" => "腾讯云cos",
                "description" => "腾讯云cos配置",
                "code" => "tencent_cos",
                "content" => [
                    [
                        "name" => "appId",
                        "field" => "appId",
                        "type" => "text",
                        "content" => "",
                        "option" => "appId"
                    ],
                    [
                        "name" => "appKey",
                        "field" => "appKey",
                        "type" => "text",
                        "content" => "",
                        "option" => "appKey"
                    ],
                    [
                        "name" => "region",
                        "field" => "region",
                        "type" => "text",
                        "content" => "",
                        "option" => "region地区(可选),例：xxx.cos.ap-guangzhou.myqcloud.com"
                    ]
                ],
                "is_forced_update" => false, //seed配置- 选填 - 是否覆盖更新原有数据
                "sort_number" => 1000
            ],
            [
                "id" => 6,
                "setting_group_id" => 2,
                "name" => "七牛云",
                "description" => "七牛云配置",
                "code" => "qiniuyun",
                "content" => [
                    [
                        "name" => "appId",
                        "field" => "appId",
                        "type" => "text",
                        "content" => "",
                        "option" => "appId"
                    ],
                    [
                        "name" => "appKey",
                        "field" => "appKey",
                        "type" => "text",
                        "content" => "",
                        "option" => "appKey"
                    ]
                ],
                "is_forced_update" => false, //seed配置- 选填 - 是否覆盖更新原有数据
                "sort_number" => 1000
            ],
        ];

        foreach ($data as $item) {
            $Setting = new \app\common\model\Setting();
            $Setting = $Setting->find($item['id']);

            if (($item['is_forced_update'] ?? false) && $Setting){
                \app\common\model\Setting::update($item);
            }elseif(!$Setting){
               \app\common\model\Setting::create($item);
            }

        }
    }
}
