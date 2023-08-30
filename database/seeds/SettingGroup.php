<?php

use think\migration\Seeder;

class SettingGroup extends Seeder
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
        $data = [
            [
                "id" => 1,
                "module" => "admin",
                "name" => "后台设置",
                "description" => "后台管理方面的设置",
                "code" => "admin",
                "sort_number" => 1000,
                "icon" => "fa fa-adjust",
                "auto_create_menu" => 1,
                "auto_create_file" => 0,
                "is_forced_update" => false, //seed配置- 选填 - 是否覆盖更新原有数据
            ],
            [
                "id" => 2,
                "module" => "index",
                "name" => "前台设置",
                "description" => "前台方面的设置",
                "code" => "index",
                "sort_number" => 1000,
                "icon" => "fa fa-list",
                "auto_create_menu" => 0,
                "auto_create_file" => 0,
                "is_forced_update" => false, //seed配置- 选填 - 是否覆盖更新原有数据
            ],
            [
                "id" => 3,
                "module" => "cloud",
                "name" => "对象存储设置",
                "description" => "对象存储方面的设置",
                "code" => "cloud",
                "sort_number" => 1000,
                "icon" => "fa fa-cloud",
                "auto_create_menu" => 0,
                "auto_create_file" => 0,
                "is_forced_update" => false, //seed配置- 选填 - 是否覆盖更新原有数据
            ],
            [
                "id" => 4,
                "module" => "wechat",
                "name" => "微信设置",
                "description" => "微信方面的设置",
                "code" => "wechat",
                "sort_number" => 1000,
                "icon" => "fa fa-comment",
                "auto_create_menu" => 0,
                "auto_create_file" => 0,
                "is_forced_update" => false, //seed配置- 选填 - 是否覆盖更新原有数据
            ],
            [
                "id" => 5,
                "module" => "config",
                "name" => "基本设置",
                "description" => "前后台方面的基本设置",
                "code" => "config",
                "sort_number" => 1000,
                "icon" => "fa fa-wrench",
                "auto_create_menu" => 0,
                "auto_create_file" => 0,
                "is_forced_update" => false, //seed配置- 选填 - 是否覆盖更新原有数据
            ],
            [
                "id" => 6,
                "module" => "bytedance",
                "name" => "字节设置",
                "description" => "字节跳动的基本设置",
                "code" => "bytedance",
                "sort_number" => 1000,
                "icon" => "fa fa-tumblr",
                "auto_create_menu" => 0,
                "auto_create_file" => 0,
                "is_forced_update" => false, //seed配置- 选填 - 是否覆盖更新原有数据
            ],
        ];

        foreach ($data as $item) {
            $moder = new \app\common\model\SettingGroup();
            $Setting = $moder->findOrEmpty($item['id']);

            if (($item['is_forced_update'] ?? false) && $Setting->isExists()) {
                $moder::update($item);
            } elseif($Setting->isEmpty()) {
                $moder::create($item);
            }

        }
    }
}