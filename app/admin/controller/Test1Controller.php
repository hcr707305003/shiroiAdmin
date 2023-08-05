<?php
/**
 * 测试控制器 (案例2)
 */

namespace app\admin\controller;

use app\admin\traits\AdminSettingForm;
use app\admin\traits\ControllerTrait;

class Test1Controller extends AdminBaseController
{
    //引入公共控制器类相关的trait
    use ControllerTrait;
    // 引入form相关trait
    use AdminSettingForm;

    protected array $show_index_input = [
        'username' => "用户名",
        'mobile' => '手机号',
        'user_level_id' => [
            'title' => '用户等级',
            'type' => 'select',
            'option' => []
        ],
    ];

    protected array $show_index_field = [
        'id' => 'ID',
        'avatar' => '头像',
        'username' => '用户名',
        'nickname' => '昵称',
        'mobile' => '手机号',
        'user_level_id' => '用户等级',
        'status' => '是否启用',
    ];

    protected array $show_field = [
        'avatar' => '头像',
        'username' => '用户名',
        'nickname' => '昵称',
        'mobile' => '手机号',
        'user_level_id' => '用户等级',
        'status' => '是否启用',
        'slide' => '相册',
        'content' => '内容'
    ];

    protected array $show_type = [
//        'id' => 'hidden',
        'avatar' => 'image',
        'user_level_id' => 'select',
//        'slide' => 'multi_image',
        'slide' => 'multi_file',
        'status' => 'switch',
        'content' => 'editor'
    ];

    protected array $show_index_field_conditions = [
        'status' => [
            0 => "<a style='color: #da2323;'>禁用</a>",
            1 => "<a style='color: #2fff00;'>启用</a>",
        ]
    ];

    //实现tab标签
//    protected array $show_edit_tab = [
//        1 => 'tab_1',
//        2 => 'tab_2',
//        3 => 'tab_3',
//        4 => 'tab_4',
//    ];
//
//    protected array $show_edit_tab_content = [
//        1 => [
//            'id',
//            'avatar',
//        ],
//        2 => [
//            'username',
//            'nickname',
//        ],
//        3 => [
//            'mobile',
//            'user_level_id',
//        ],
//        4 => [
//            'status',
//            'create_time',
//        ]
//    ];

    protected static string $service = 'app\admin\service\TestService';

    protected static string $userLevelService = 'app\admin\service\UserLevelService';

    protected function postParam(): array
    {
        foreach (self::$userLevelService::getLists([],['id', 'name'])->toArray() as $v) {
            $this->show_index_input['user_level_id']['option'][$v['id']] = $this->show_field_conditions['user_level_id'][$v['id']] = $v['name'];
        }
        return request()->param();
    }

    //获取get参数(如果不接收参数，建议`return [];`，否则会影响到pjax)
    protected function getParam(): array
    {
        return $this->filterParam(request()->get(['username', 'mobile', 'user_level_id']), 'get');
    }
}