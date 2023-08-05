<?php
declare (strict_types = 1);

namespace app\command;

use app\common\model\AdminMenu;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\facade\Db;

class CreateBackgroundView extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('create:admin_background_view')
            ->addOption('name', null, Option::VALUE_REQUIRED, '模块名称')
            ->addOption('module', null, Option::VALUE_REQUIRED, '模块命名')
            ->setDescription('一键创建后台视图模块');
    }

    /**
     * 命令：php think create:admin_backgroup_view --name=企业 --module=company
     * @param Input $input
     * @param Output $output
     * @return void
     */
    protected function execute(Input $input, Output $output)
    {
        if(!$name = $input->getOption('name')) {
            $output->writeln('请传name值,举例：php think create:admin_background_view --name=企业 --module=company');exit();
        }

        if(!$module = $input->getOption('module')) {
            $output->writeln('请传module值,举例：php think create:admin_background_view --name=企业 --module=company');exit();
        }

        //创建数据
//        $this->createData($name,$module);
        //创建视图
        $this->createView($module);
    }

    //创建数据（默认列表,增，删，改）
    public function createData(string $name = '', string $module = '') {
        Db::startTrans();
        try {
            //创建父级菜单
            $parent = AdminMenu::create([
                'id' => null,
                'parent_id' => 0,
                'name' => $name.'管理',
                'url' => "admin/{$module}/index",
                'icon' => 'fas fa-user',
                'is_show' => 1,
                'is_top' => 0,
                'sort_number' => 1000,
                'log_method' => '不记录',
            ]);
            //创建子级菜单
            $son = [
                ['id' => null, 'parent_id' => $parent->id, 'name' => '添加'.$name, 'url' => "admin/{$module}/add", 'icon' => 'fas fa-plus', 'is_show' => 0, 'is_top' => 0, 'sort_number' => 1000, 'log_method' => 'POST',
                ],
                ['id' => null, 'parent_id' => $parent->id, 'name' => '修改'.$name, 'url' => "admin/{$module}/edit", 'icon' => 'fas fa-pencil', 'is_show' => 0, 'is_top' => 0, 'sort_number' => 1000, 'log_method' => 'POST',
                ],
                ['id' => null, 'parent_id' => $parent->id, 'name' => $name.'详情', 'url' => "admin/{$module}/detail", 'icon' => 'fas fa-info', 'is_show' => 0, 'is_top' => 0, 'sort_number' => 1000, 'log_method' => 'GET',
                ],
                ['id' => null, 'parent_id' => $parent->id, 'name' => '删除'.$name, 'url' => "admin/{$module}/del", 'icon' => 'fas fa-trash', 'is_show' => 0, 'is_top' => 0, 'sort_number' => 1000, 'log_method' => 'POST']
            ];
            foreach ($son as $item) {
                AdminMenu::create($item);
            }
            dump("创建数据成功~(详细请查看：admin_menu表)");

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            dump($e->getMessage());
        }
    }

    //创建视图（默认列表增，删，改，查）页面
    public function createView(string $module = '') {
        //公共视图
        $admin_template = $this->defaultViewPath('admin_template');
        //创建视图
        $create_template = $this->defaultViewPath($module);

        if(!file_exists($create_template) || !is_dir($create_template)) {
            mkdir($create_template);
        }
        $dir = scandir($admin_template);
        foreach ($dir as $v) {
            if($v != '.' && $v != '..') {
                copy($admin_template.$v,$create_template.$v);
            }
        }
        dump("创建视图成功~");
    }

    //设置默认视图目录
    private function defaultViewPath(string $module = ''): string
    {
        return app_path('admin' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . $module);
    }
}
