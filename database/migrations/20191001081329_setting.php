<?php
/**
 * 设置
 */

use think\facade\Db;
use think\migration\Migrator;
use think\migration\db\Column;

class Setting extends Migrator
{

    public function change(): void
    {
        $table = $this->table('setting', ['comment' => '设置', 'engine' => 'InnoDB', 'encoding' => 'utf8mb4', 'collation' => 'utf8mb4_unicode_ci']);
        $table
            ->addColumn('setting_group_id', 'integer', ['limit' => 10, 'default' => 0, 'comment' => '所属分组'])
            ->addColumn('name', 'string', ['limit' => 50, 'default' => '', 'comment' => '名称'])
            ->addColumn('description', 'string', ['limit' => 100, 'default' => '', 'comment' => '描述'])
            ->addColumn('code', 'string', ['limit' => 50, 'default' => '', 'comment' => '代码'])
            ->addColumn('content', 'text', [ 'comment' => '设置配置及内容'])
            ->addColumn('sort_number', 'integer', ['limit' => 10, 'default' => 1000, 'comment' => '排序'])
            ->addColumn('create_time', 'integer', ['limit' => 10, 'default' => 0, 'comment' => '创建时间'])
            ->addColumn('update_time', 'integer', ['limit' => 10, 'default' => 0, 'comment' => '更新时间'])
            ->addColumn('delete_time', 'integer', ['limit' => 10, 'default' => 0, 'comment' => '删除时间'])
            ->create();

        $this->insertData();
    }


    protected function insertData(): void
    {
        $data = '[{"id":1,"setting_group_id":1,"name":"基本设置","description":"后台的基本信息设置","code":"base","content":[{"name":"后台名称","field":"name","type":"text","content":"shiroiAdmin后台系统","option":""},{"name":"后台简称","field":"short_name","type":"text","content":"shiroiAdmin","option":""},{"name":"后台作者","field":"author","type":"text","content":"shiroi","option":""},{"name":"作者网站","field":"website","type":"text","content":"https://hcr707305003.github.io/","option":""},{"name":"后台版本","field":"version","type":"text","content":"0.1","option":""},{"name":"后台LOGO","field":"logo","type":"image","content":"\/static\/admin\/images\/logo.png","option":""}],"sort_number":1000},{"id":2,"setting_group_id":1,"name":"登录设置","description":"后台登录相关设置","code":"login","content":[{"name":"登录token验证","field":"token","type":"switch","content":"1","option":""},{"name":"验证码","field":"captcha","type":"select","content":"1","option":"0||不开启\r\n1||图形验证码\r\n2||滑动验证"},{"name":"登录背景","field":"background","type":"image","content":"\/static\/admin\/images\/login-default-bg.jpg","option":""},{"name":"极验ID","field":"geetest_id","type":"text","content":"66cfc0f309e368364b753dad7d2f67f2","option":""},{"name":"极验KEY","field":"geetest_key","type":"text","content":"99750f86ec232c997efaff56c7b30cd3","option":""},{"name":"登录重试限制","field":"login_limit","type":"switch","content":"0","option":"0||否\r\n1||是"},{"name":"限制最大次数","field":"login_max_count","type":"number","content":"5","option":""},{"name":"禁止登录时长(小时)","field":"login_limit_hour","type":"number","content":"2","option":""}],"sort_number":1000},{"id":3,"setting_group_id":1,"name":"安全设置","description":"安全相关配置","code":"safe","content":[{"name":"加密key","field":"admin_key","type":"text","content":"89ce3272dc949fc3698fe7108d1dbe37","option":""},{"name":"SessionKeyUid","field":"store_uid_key","type":"text","content":"admin_user_id","option":""},{"name":"SessionKeySign","field":"store_sign_key","type":"text","content":"admin_user_sign","option":""},{"name":"后台用户密码强度检测","field":"password_check","type":"switch","content":"0","option":"0||关闭\r\n1||开启"},{"name":"密码安全强度等级","field":"password_level","type":"select","content":"2","option":"1||简单密码\r\n2||中等密码\r\n3||复杂密码"},{"name":"单设备登录","field":"one_device_login","type":"switch","content":"0","option":"0||关闭\r\n1||开启"},{"name":"CSRFToken检测","field":"check_token","type":"switch","content":"1","option":""},{"name":"CSRFToken验证方法","field":"check_token_action_list","type":"multi_select","content":"add,edit,del,import,profile,update","option":"add||添加\r\nedit||修改\r\ndel||删除\r\nimport||导入\r\nprofile||修改资料\r\nupdate||更新"}],"sort_number":1000}]';


        $msg = '配置导入成功.' . "\n";
        Db::startTrans();
        try {
            $data = json_decode($data, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            print ('配置数据解析错误，信息：'.$e->getMessage());
        }
        try {
            foreach ($data as $item) {
                \app\common\model\Setting::create($item);
            }
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            $msg = $e->getMessage();
        }
        print ($msg);
    }
}
