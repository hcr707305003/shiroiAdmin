<?php
/**
 * User: Shiroi
 * EMail: 707305003@qq.com
 */

namespace app\admin\controller;

use app\common\plugin\FormDesign;
use app\common\plugin\TableHandle;
use Exception;

class FormDesignController extends AdminBaseController
{
    /**
     * @return string
     * @throws Exception
     */
    public function index(): string
    {
        return $this->fetch();
    }

    /**
     * 根据设计表单动态生成表（此功能危险性比较高）
     * @return string
     * @throws Exception
     */
    public function design(): string
    {
        if (request()->isPost()) {
            //处理成表单流程的方式
            $data = [];
            $default = function($field, $options = [],$value = 1) {
                if ($field['field_options'] ?? []) foreach (($field['field_options']['options'] ?? []) as $option) {
                    $options[] = [
                        'name' => $option['label'],
                        'value' => $value++,
                        'checked' =>$option['checked']
                    ];
                }
                return $options ?: '';
            };
            foreach (input('fields', []) as $field) {
                $data[] = [
                    'type' => $field['field_type'],
                    'field' => $field['cid'],
                    'required' => $field['required'],
                    'name' => $field['label'],
                    'default' => $default($field)
                ];
            }
            //todo 该插件只支持text,radio,file等 (后面会完善)
            $data = FormDesign::getInstance()->generate($data);
            dump($data);
            //表名
            $tableHandle = new TableHandle('test_table');
            //动态生成数据库表
            $tableHandle->generate($data);
            die();
        }
        return $this->fetch();
    }
}