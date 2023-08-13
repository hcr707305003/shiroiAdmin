<?php
/**
 * 前台用户
 */

use think\facade\Db;
use think\migration\Migrator;
use think\migration\db\Column;

class User extends Migrator
{

    public function change()
    {
        $table = $this->table('user', ['comment' => '用户', 'engine' => 'InnoDB', 'encoding' => 'utf8mb4', 'collation' => 'utf8mb4_unicode_ci']);
        $table
            ->addColumn('user_level_id', 'integer', ['signed' => false, 'limit' => 10, 'default' => 1, 'comment' => '用户等级'])
            ->addColumn('username', 'string', ['limit' => 30, 'default' => '', 'comment' => '账号'])
            ->addColumn('password', 'string', ['limit' => 255, 'default' => '', 'comment' => '密码'])
            ->addColumn('mobile', 'string', ['limit' => 11, 'default' => '', 'comment' => '手机号'])
            ->addColumn('nickname', 'string', ['limit' => 20, 'default' => '', 'comment' => '昵称'])
            ->addColumn('avatar', 'string', ['limit' => 255, 'default' => '/static/index/images/avatar.png', 'comment' => '头像'])
            ->addColumn('status', 'boolean', ['signed' => false, 'limit' => 1, 'default' => 1, 'comment' => '是否启用'])
            ->addColumn('create_time', 'integer', ['signed' => false, 'limit' => 10, 'default' => 0, 'comment' => '创建时间'])
            ->addColumn('update_time', 'integer', ['signed' => false, 'limit' => 10, 'default' => 0, 'comment' => '更新时间'])
            ->addColumn('delete_time', 'integer', ['signed' => false, 'limit' => 10, 'default' => 0, 'comment' => '删除时间'])
            ->create();

        $this->insertData();
    }

    protected function insertData(): void
    {
        $data = '[{"id":1,"user_level_id":1,"username":"putong","password":"putong","mobile":"","nickname":"putong","avatar":"\/uploads\/image\/user.png","status":0},{"id":2,"user_level_id":2,"username":"baiyin","password":"baiyin","mobile":"","nickname":"baiyin","avatar":"\/uploads\/image\/user.png","status":0},{"id":3,"user_level_id":3,"username":"huangjin","password":"huangjin","mobile":"","nickname":"huangjin","avatar":"\/uploads\/image\/user.png","status":0}]';

        $msg = '测试用户数据导入成功.' . "\n";
        Db::startTrans();
        try {
            $data = json_decode($data, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            print ('用户数据解析错误，信息：'.$e->getMessage());
        }
        try {
            foreach ($data as $item) {
                \app\common\model\User::create($item);
            }
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            $msg = $e->getMessage();
        }
        print ($msg);
    }

}
