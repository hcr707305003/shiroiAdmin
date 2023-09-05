<?php
/**
 * User: Shiroi
 * EMail: 707305003@qq.com
 */

namespace app\command\socket;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use Workerman\Worker;

class SocketServer extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('all model socket server')
            ->addArgument('action', Argument::OPTIONAL, "start|stop|restart|reload|status|connections", 'start')
            ->addOption('mode', 'm', Option::VALUE_OPTIONAL, 'Run the workerman server in daemon mode.')
            ->setDescription('all model socket server: php think socket:run start --mode d');
    }

    protected function execute(Input $input, Output $output)
    {
        // 指令输出
        $action = $input->getArgument('action');
        $mode = $input->getOption('mode');
        // 重新构造命令行参数,以便兼容workerman的命令
        global $argv;
        $argv = [];
        array_unshift($argv, 'think', $action);
        if ($mode == 'd') {
            $argv[] = '-d';
        } else if ($mode == 'g') {
            $argv[] = '-g';
        }

        //开启的所有服务
        new \app\admin\socket\Server(env('admin.socket_port', 1111));
        new \app\api\socket\Server(env('api.socket_port', 2222));
        new \app\index\socket\Server(env('index.socket_port', 3333));
        //执行所有服务
        Worker::runAll();
    }
}