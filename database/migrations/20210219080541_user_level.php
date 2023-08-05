<?php
/**
 * 用户等级
 */

use think\facade\Db;
use think\migration\Migrator;
use think\migration\db\Column;

class UserLevel extends Migrator
{

    public function change()
    {
        $table = $this->table('user_level', ['comment' => '用户等级', 'engine' => 'InnoDB', 'encoding' => 'utf8mb4', 'collation' => 'utf8mb4_unicode_ci']);
        $table
            ->addColumn('name', 'string', ['limit' => 20, 'default' => '', 'comment' => '名称'])
            ->addColumn('description', 'string', ['limit' => 50, 'default' => '', 'comment' => '简介'])
            ->addColumn('img', 'string', ['limit' => 255, 'default' => '/static/index/images/user_level_default.png', 'comment' => '图片'])
            ->addColumn('status', 'boolean', ['limit' => 1, 'default' => 1, 'comment' => '是否启用'])
            ->addColumn('create_time', 'integer', ['signed' => false, 'limit' => 10, 'default' => 0, 'comment' => '创建时间'])
            ->addColumn('update_time', 'integer', ['signed' => false, 'limit' => 10, 'default' => 0, 'comment' => '更新时间'])
            ->addColumn('delete_time', 'integer', ['signed' => false, 'limit' => 10, 'default' => 0, 'comment' => '删除时间'])
            ->create();

        $this->insertData();
    }


    protected function insertData(): void
    {
        $data = '[{"id":1,"name":"普通用户","description":"普通用户","img":"\/uploads\/image\/user_level_1.png","status":1},{"id":2,"name":"白银会员","description":"白银会员","img":"\/uploads\/image\/user_level_2.png","status":1},{"id":3,"name":"黄金会员","description":"黄金会员","img":"\/uploads\/image\/user_level_3.png","status":1}]';

        $msg = '用户等级数据导入成功.' . "\n";
        Db::startTrans();
        try {
            $data = json_decode($data, true, 512, JSON_THROW_ON_ERROR);
            \think\facade\Log::write($data);
        } catch (JsonException $e) {
            print ('用户等级数据解析错误，信息：'.$e->getMessage());
        }
        try {
            foreach ($data as $item) {
                \app\common\model\UserLevel::create($item);
            }
            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
            $msg = $e;
        }
        print ($msg);
    }
}
