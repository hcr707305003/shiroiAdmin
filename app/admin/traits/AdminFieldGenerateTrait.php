<?php

namespace app\admin\traits;

trait AdminFieldGenerateTrait
{
    /**
     * 过滤不需要的get参数
     * @var array
     */
    protected array $filter_get_param = [
        'limit',
        'page'
    ];

    /**
     * 过滤不需要的post参数
     * @var array
     */
    protected array $filter_post_param = [
        'limit',
        'page'
    ];

    /**
     * 字段条件显示不同内容
     * @var array
     */
    protected array $show_field_conditions = [];

    /**
     * 当前基础显示的字段
     * @var array
     */
    protected array $show_base_field = [];

    /**
     * 当前首页显示的字段
     * @var array
     */
    protected array $show_index_field = [];

    /**
     * 首页显示的字段作用key和value的接收
     * @var array
     */
    protected array $show_index_field_key = [];
    protected array $show_index_field_value = [];

    /**
     * 当前首页显示的input
     * @var array
     */
    protected array $show_index_input = [];

    /**
     * 首页显示的input作用key和value的接收
     * @var array
     */
    protected array $show_index_input_key = [];
    protected array $show_index_input_value = [];

    /**
     * 字段条件显示不同内容
     * @var array
     */
    protected array $show_index_field_conditions = [];

    /**
     * 设置表单类型
     * @var array
     */
    protected array $show_type = [];

    /**
     * 设置新增页表单类型
     * @var array
     */
    protected array $show_index_type = [];

    /**
     * 当前首页的原生html
     * @var array
     */
    protected array $show_index_raw = [];

    /**
     * 默认显示字段
     * @var array
     */
    protected array $show_field = [];

    /**
     * 当前修改页显示的字段
     * @var array
     */
    protected array $show_edit_field = [];

    /**
     * 设置编辑页表单类型
     * @var array
     */
    protected array $show_edit_type = [];

    /**
     * 当前修改页的原生html
     * @var array
     */
    protected array $show_edit_raw = [];

    /**
     * 修改页显示的字段作用key和value的接收
     * @var array
     */
    protected array $show_edit_field_key = [];
    protected array $show_edit_field_value = [];

    /**
     * 字段条件显示不同内容
     * @var array
     */
    protected array $show_edit_field_conditions = [];

    /**
     * 当前新增页显示的字段
     * @var array
     */
    protected array $show_add_field = [];

    /**
     * 设置新增页表单类型
     * @var array
     */
    protected array $show_add_type = [];

    /**
     * 当前新增页的原生html
     * @var array
     */
    protected array $show_add_raw = [];

    /**
     * 新增页显示的字段作用key和value的接收
     * @var array
     */
    protected array $show_add_field_key = [];
    protected array $show_add_field_value = [];

    /**
     * 字段条件显示不同内容
     * @var array
     */
    protected array $show_add_field_conditions = [];

    /**
     * 当前详情页显示的字段
     * @var array
     */
    protected array $show_detail_field = [];

    /**
     * 设置详情页表单类型
     * @var array
     */
    protected array $show_detail_type = [];

    /**
     * 当前详情页的原生html
     * @var array
     */
    protected array $show_detail_raw = [];

    /**
     * 详情页显示的字段作用key和value的接收
     * @var array
     */
    protected array $show_detail_field_key = [];
    protected array $show_detail_field_value = [];

    /**
     * 字段条件显示不同内容
     * @var array
     */
    protected array $show_detail_field_conditions = [];

    /**
     * 标签页设置
     */
    protected array $show_edit_tab = [];
    protected array $show_add_tab = [];
    protected array $show_index_tab = [];

    /**
     * 标签页内容设置
     */
    protected array $show_edit_tab_content = [];
    protected array $show_add_tab_content = [];
    protected array $show_index_tab_content = [];

    /**
     * 转换首页显示的列表和查询字段
     * @return void
     */
    public function convertShowField() {

        foreach (($this->show_index_field = array_merge($this->show_index_field ?: $this->show_field, $this->show_base_field)) as $key=>$value) {
            $this->show_index_field_key[] = is_int($key) ? $value: $key;
            $this->show_index_field_value[] = $value;
        }

        foreach (($this->show_edit_field = array_merge($this->show_edit_field ?: $this->show_field, $this->show_base_field)) as $key=>$value) {
            $this->show_edit_field_key[] = is_int($key) ? $value: $key;
            $this->show_edit_field_value[] = $value;
        }

        foreach (($this->show_add_field = array_merge($this->show_add_field ?: $this->show_field, $this->show_base_field)) as $key=>$value) {
            $this->show_add_field_key[] = is_int($key) ? $value: $key;
            $this->show_add_field_value[] = $value;
        }

        foreach (($this->show_detail_field = array_merge($this->show_detail_field ?: $this->show_field, $this->show_base_field)) as $key=>$value) {
            $this->show_detail_field_key[] = is_int($key) ? $value: $key;
            $this->show_detail_field_value[] = $value;
        }
    }

    /**
     * 转换首页显示的input查询字段
     * @return void
     */
    public function convertShowInput() {
        foreach ($this->show_index_input as $key=>$value) {
            $this->show_index_input_key[] = is_int($key) ? $value: $key;
            $this->show_index_input_value[] = $value;
        }
    }
}