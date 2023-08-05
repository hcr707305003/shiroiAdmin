<?php

namespace app\common\plugin;

/**
 * 表单设计类
 * User: Shiroi
 * EMail: 707305003@qq.com
 * @example
 *  - 案例一（生成一个）
 */
class FormDesign
{
    const UPLOAD_URL = '/common/file/upload';

    private static ?FormDesign $form = null;

    //富文本上传地址
    private string $UploadUrl = self::UPLOAD_URL;

    //图片上传地址
    private string $ImageUploadUrl = self::UPLOAD_URL;

    //文件上传地址
    private string $FileUploadUrl = self::UPLOAD_URL;

    private array $formType = [
        'text', //文本框
        'textarea', //多行文本框
        'number', // 数字框
        'radio', //单选框
        'checkbox', //多选框
        'select', //下拉框
        'multiple_select', //多选下拉框
        'time', //时间
        'time_range', //时间范围
        'date', //日期框
        'date_range', //日期范围
        'switch', //开关
        'score', //评分
        'color_picker', //颜色选择器
        'slider', //滑块
        'html', //html 文本
        'button', //按钮
        'split_line', //分割线
        'image', //图片
        'file', //文件
        'rich_text', //富文本
    ];

    //所有属性
    private array $property = [
        'field', //字段|唯一名称
        'align', //对齐 （left => 左, center => 中, right => 右）
        'default', //默认值
        'mix_limit', //最小长度
        'max_limit', //最大长度
        'placeholder', //占位内容
        'required', //是否必填 (默认:否, 否=>0, 是=>1)
        'required_hint', //必填校验提示 (如果不传则默认内容：该字段为必填项)
        'is_read', //是否只读 (默认:否, 否=>0, 是=>1)
        'is_disable', //是否禁用 (默认:否, 否=>0, 是=>1)
        'is_hidden', //是否隐藏 (默认:否, 否=>0, 是=>1)
        'remark', // 说明文本

        //左侧名称
        'name', //字段名称
        'name_width', //字段名称宽度
        'name_show', //字段名称是否展示 (默认:是, 否=>0, 是=>1)

        //数字框
        'unit', //单位
        'digit', //小数点后几位
        'is_capital', //是否大写 (默认:否, 否=>0, 是=>1)
        'capital_text', //大写文本

        //时间组件
        'timestamp', //时间戳
        'time_format', //时间格式

        //评分
        'max_score', //最大评分值
        'low_score_limit', //低分界限值
        'high_score_limit', //高分界限值
        'is_half_select', //是否允许半选 (默认:否, 否=>0, 是=>1)

        //滑块
        'min_value', //最小值
        'max_value', //最大值
        'step_value', //增减步长
        'is_scope_choose', //是否范围选择 (默认:否, 否=>0, 是=>1)

        //分割线
        'width', //宽
        'height', //高

        //图片|文件
        'upload_url', //上传地址
        'is_multiple_choose', //是否多选 (默认:否, 否=>0, 是=>1)
        'file_type', //上传文件类型
        'max_size', //上传限制文件大小,默认10m（单位：kb）
    ];

    //设置默认属性
    private array $defaultProperty = [
        'field', //字段|唯一名称
        'align', //对齐 （left => 左, center => 中, right => 右）
        'default', //默认值
        'is_read', //是否只读 (默认:否, 否=>0, 是=>1)
        'is_disable', //是否禁用 (默认:否, 否=>0, 是=>1)
        'is_hidden', //是否隐藏 (默认:否, 否=>0, 是=>1)
        'name', //字段名称
        'name_width', //字段名称宽度
        'name_show' => 1, //字段名称是否展示 (默认:是, 否=>0, 是=>1)
    ];

    //表单类型->文本框
    private array $text = [
        'mix_limit', //最小长度
        'max_limit', //最大长度
        'placeholder', //占位内容
        'required', //是否必填 (默认:否, 否=>0, 是=>1)
        'required_hint', //必填校验提示 (如果不传则默认内容：该字段为必填项)
    ];

    //表单类型->多行文本框
    private array $textarea = [
        'mix_limit', //最小长度
        'max_limit' => 200, //最大长度
        'placeholder', //占位内容
        'required', //是否必填 (默认:否, 否=>0, 是=>1)
        'required_hint', //必填校验提示 (如果不传则默认内容：该字段为必填项)
    ];

    //表单类型->数字框
    private array $number = [
        'unit', //单位
        'is_capital', //是否大写 (默认:否, 否=>0, 是=>1)
        'capital_text', //大写文本
        'mix_limit', //最小长度
        'max_limit', //最大长度
        'placeholder', //占位内容
        'required', //是否必填 (默认:否, 否=>0, 是=>1)
        'required_hint', //必填校验提示 (如果不传则默认内容：该字段为必填项)
    ];

    //表单类型->单选框
    private array $radio = [
        'default' => [
            [
                'value' => 1,
                'name' => 'radio 1',
                'checked' => true
            ],
            [
                'value' => 2,
                'name' => 'radio 2'
            ],
            [
                'value' => 3,
                'name' => 'radio 3'
            ],
        ]
    ];

    //表单类型->多选框
    private array $checkbox = [
        'default' => [
            [
                'value' => 1,
                'name' => 'check 1',
                'checked' => true
            ],
            [
                'value' => 2,
                'name' => 'check 2'
            ],
            [
                'value' => 3,
                'name' => 'check 3'
            ],
        ]
    ];

    //表单类型->下拉框
    private array $select = [
        'default' => [
            [
                'value' => 1,
                'name' => 'select 1',
                'selected' => true
            ],
            [
                'value' => 2,
                'name' => 'select 2'
            ],
            [
                'value' => 3,
                'name' => 'select 3'
            ],
        ]
    ];

    //表单类型->多选下拉框
    private  array $multiple_select = [
        'default' => [
            [
                'value' => 1,
                'name' => 'select 1',
                'selected' => true
            ],
            [
                'value' => 2,
                'name' => 'select 2'
            ],
            [
                'value' => 3,
                'name' => 'select 3'
            ],
        ]
    ];

    //表单类型->时间
    private array $time = [
        'timestamp',
        'time_format' => 'HH:mm:ss', //时间格式
    ];

    //表单类型->时间范围
    private array $time_range = [
        'default' => [
            [
                'value' => '',
                'timestamp' => 0
            ],
            [
                'value' => '',
                'timestamp' => 0
            ]
        ],
        'time_format' => 'HH:mm:ss', //时间格式
    ];

    //表单类型->日期
    private array $date = [
        'timestamp',
        'time_format' => 'YYYY-MM-DD', //时间格式
    ];

    //表单类型->日期范围
    private array $date_range = [
        'default' => [
            [
                'value' => '',
                'timestamp' => 0
            ],
            [
                'value' => '',
                'timestamp' => 0
            ]
        ],
        'time_format' => 'YYYY-MM-DD', //时间格式
    ];

    //表单类型->开关
    private array $switch = [
        'default' => [
            [
                'value' => '开启',
                'checked' => true
            ],
            [
                'value' => '关闭',
            ]
        ]
    ];

    //表单类型->评分
    private array $score = [
        'default' => 0, //默认0
        'max_score' => 5, //最大评分值
        'low_score_limit' => 2, //低分界限值
        'high_score_limit' => 4, //高分界限值
        'is_half_select', //是否允许半选 (默认:否, 否=>0, 是=>1)
    ];

    //表单类型->颜色选择器
    private array $color_picker = [

    ];

    //表单类型->滑块
    private array $slider = [
        'default' => 0, //默认0
        //如果`是否范围选择`为是的情况下，default值则为数组格式
//        'default' => [
//            [
//                'value' => 0,
//            ],
//            [
//                'value' => 0,
//            ],
//        ],
        'min_value' => 0, //最小值
        'max_value' => 100, //最大值
        'step_value' => 1, //增减步长
        'is_scope_choose', //是否范围选择 (默认:否, 否=>0, 是=>1)
    ];

    //表单类型->html 文本
    private array $html = [

    ];

    //表单类型->按钮
    private array $button = [
        '!name', //排除字段名称
        '!name_width', //排除字段名称宽度
        '!name_show', //排除字段名称是否展示
        '!is_read', //排除是否只读
    ];

    //表单类型->分割线
    private array $split_line = [
        'width' => '100%', //宽 默认100%
        'height' => '1px', //高 默认1px
        '!name', //排除字段名称
        '!name_width', //排除字段名称宽度
        '!name_show', //排除字段名称是否展示
        '!is_read', //排除是否只读
        '!is_disable', //排除是否禁用
    ];

    //表单类型->图片
    private array $image = [
        'upload_url', //设置默认的图片上传地址
        'min_value' => 0, //最小上传数
        'max_value' => 5, //最大上传数
        'is_multiple_choose', //是否多选 (默认:否, 否=>0, 是=>1)
        //上传文件类型
        'file_type' => [
            'jpg',
            'jpeg',
            'png',
        ],
        'max_size' => 10 * 1028, //上传限制文件大小,默认10m（单位：kb）
    ];

    //表单类型->文件
    private array $file = [
        'upload_url', //设置默认的图片上传地址
        'min_value' => 0, //最小上传数
        'max_value' => 5, //最大上传数
        'is_multiple_choose', //是否多选 (默认:否, 否=>0, 是=>1)
        //上传文件类型
        'file_type' => [
            'doc',
            'docx',
            'xls',
            'xlsx',
            'pdf',
        ],
        'max_size' => 10 * 1028, //上传限制文件大小,默认10m（单位：kb）
    ];

    //表单类型->富文本
    private array $rich_text = [
        'upload_url', //设置默认的图片上传地址
    ];

    protected array $data = [];

    public static function getInstance(): ?FormDesign
    {
        if(self::$form == null){
            self::$form = new FormDesign();
        }
        return self::$form;
    }

    /**
     * 表单生成
     * @param array|string $content
     * @param array $option
     * @return array
     */
    public function generate($content = [], array $option = []): array
    {
        //处理数据
        $arr = [];
        if(is_string($content) && in_array($content, $this->formType)) {
            $arr[] = array_merge(['type' => $content], $option);
        }
        if(is_array($content)) {
            foreach ($content as $type => $singleOption) {
                $type = is_string($type) ? $type: ($singleOption['type'] ?? '');
                if(in_array($type, $this->formType)) {
                    $arr[] = array_merge($singleOption, compact('type'), $option);
                }
            }
        }
        //数据处理
        foreach ($arr as $key => $item) {
            $option = [];
            foreach (array_merge($this->defaultProperty, $this->{$item['type']} ?? []) as $property => $value) {
                $option[is_int($property) ? $value : $property] = is_int($property) ? '': $value;
            }
            //处理数据
            foreach ($item = array_merge($option, $item) as $property => $value) if (method_exists(self::class, $property)) {
                $item[$property] = $this->{$property}($value, $item);
            } else {
                $unsetResult = both_field_exists($property, '!', 1);
                if ($unsetResult['bool'] && isset($item[$unsetResult['cut_content']])) {
                    unset($item[$unsetResult['cut_content']], $item[$property]);
                }

                //关闭未知属性值删除
//                unset($item[$property]);
            }
            //每个表单字段都有一个唯一id
            $field = (isset($item['field']) && $item['field']) ? $item['field']: $item['type'];
            $item['field'] = $this->field($field, ($item['field'] ?? '') ? 1: 0);
            $arr[$key] = $item;
        }
        return $arr;
    }

    /**
     * 获取表单内容
     * @param array $content (表单内容)
     * @param array $extractParam (提取的内容)
     * @param string|array $show_type (显示的类型 all=>所有,text,number,image,file,textarea...)
     * @param int $offset (从下标0开始)
     * @param int|null $length (截取的长度)
     * @param bool $read_empty (是否读空)
     * @param string $read_empty_field (根据指定的属性来判断读空操作)
     * @return array
     */
    public function content(array $content = [], array $extractParam = [], $show_type = 'all', int $offset = 0, ?int $length = null, bool $read_empty = true, string $read_empty_field = 'default'): array
    {
        //提取的参数
        $extract_param_data = [];
        //公共参数
        $common_extract = [];
        //是否自身
        $is_only = false;
        //提取所有公共参数
        if (count($extractParam) == count($extractParam, 1)) {
            $common_extract['all'] = $extractParam;$is_only = true;
        } else foreach ($extractParam as $k => $v) if(is_string($k)) {
            $common_extract[$k] = $v;
        }

        foreach ($content as $k => $v) {
            //优先级 自身参数 > 类型参数 > 公共参数
            $param = $is_only ? $common_extract['all']: ($extractParam[$k] ?? $common_extract[$v['type']] ?? $common_extract['all'] ?? array_keys($v));
            $init = false;
            if(is_string($show_type)) {
                if(($show_type == 'all') || ($show_type == $v['type'])) $init = true;
            } elseif (is_array($show_type)) {
                if(in_array($v['type'], $show_type)) $init = true;
            }
            if($init) foreach ($param as $f => $p) {
                $extract_param_data[$k][$p] = $v[is_int($f) ? $p: $f] ?? '';
            }
        }

        //只读有值的
        if(!$read_empty) foreach ($extract_param_data as $k => $v) {
            if(isset($v[$read_empty_field]) && empty($v[$read_empty_field])) unset($extract_param_data[$k]);
        }

        return array_slice($extract_param_data, $offset, $length);
    }

    /**
     * 根据原始表单进行填充(根据原始表单数据为依据，进行填充或补齐)
     * @param array $original_data (原始表单数据)
     * @param array|object $data (数据)
     * @param array $extractParam (提取的内容)
     * @return array|false|mixed|object
     */
    public function fillFormByOriginal(array $original_data = [], $data = [], array $extractParam = [])
    {
        $newData = [];
        if(($is_one_dimensional_array = $this->is_one_dimensional_array($data)))
            $newData[] = $data;
        else
            $newData = $data;

        foreach ($newData as $k => $v) {
            //筛选原始数据
            $approve_form = [];
            foreach ($v['approve_form'] as $vs) {
                $approve_form[$vs['field']] = $vs;
            }
            $newData[$k]['approve_form'] = $approve_form;

            //处理数据
            $new_form = [];
            foreach ($original_data as $ks => $vs) {
                if(isset($newData[$k]['approve_form'][$vs['field']])) {
                    $new_form[$ks] = $newData[$k]['approve_form'][$vs['field']];
                } else {
                    $new_form[$ks] = $vs;
                }

                //提取字段
                if($extractParam) {
                    $extractForm = [];
                    foreach ($extractParam as $f => $p) {
                        $extractForm[$p] = $new_form[$ks][is_int($f) ? $p: $f];
                    }
                    $new_form[$ks] = $extractForm;
                }
            }
            $newData[$k]['approve_form'] = $new_form;
        }

        return $is_one_dimensional_array ? reset($newData): $newData;
    }

    /**
     * @return string
     */
    public function getImageUploadUrl(): string
    {
        return $this->ImageUploadUrl;
    }

    /**
     * @param string $ImageUploadUrl
     */
    public function setImageUploadUrl(string $ImageUploadUrl): void
    {
        $this->ImageUploadUrl = $ImageUploadUrl;
    }

    /**
     * @return string
     */
    public function getFileUploadUrl(): string
    {
        return $this->FileUploadUrl;
    }

    /**
     * @param string $FileUploadUrl
     */
    public function setFileUploadUrl(string $FileUploadUrl): void
    {
        $this->FileUploadUrl = $FileUploadUrl;
    }

    protected function type($value)
    {
        return $value;
    }

    /**
     * 唯一名称
     * @param string $type
     * @param int|bool|string $is_suffix (是否后缀)
     * @param string $value
     * @return string
     */
    protected function field(string $type = 'text', $is_suffix = 1, string $value = ''): string
    {
        return $type . ($is_suffix ? '': ('_' . ($value ?: str_replace('-', '', uuid()))));
    }

    /**
     * 对齐
     * @param string $value
     * @return string
     */
    protected function align(string $value = ''): string
    {
        return $value ?: 'left';
    }

    /**
     * 默认值
     * @return string|array
     */
    protected function default($value = '', $data = [])
    {
        $method = substr(__METHOD__, strrpos(__METHOD__, '::') + 2);
        return $this->{$data['type'] . '_' . $method}($value, $data, $method);
    }

    /**
     * 最小长度
     * @param int|string $value
     * @return integer
     */
    protected function mix_limit($value = 0): int
    {
        return intval($value);
    }

    /**
     * 最大长度
     * @param int|string $value
     * @return integer
     */
    protected function max_limit($value = 9999): int
    {
        return intval($value);
    }

    /**
     * 占位内容
     * @param $value
     * @return mixed
     */
    protected function placeholder($value)
    {
        return $value;
    }

    /**
     * 是否必填 (默认:否, 否=>0, 是=>1)
     * @param string|bool|int $value
     * @return bool|int|mixed|string
     */
    protected function required($value = 0)
    {
        return $this->_property_convert_type($value, 'bool');
    }

    /**
     * 必填校验提示 (如果不传则默认内容：该字段为必填项)
     * @param string $value
     * @return string
     */
    protected function required_hint(string $value = ''): string
    {
        return $value ?: '该字段为必填项';
    }

    /**
     * 是否只读 (默认:否, 否=>0, 是=>1)
     * @param string|bool|int $value
     * @return bool|int|mixed|string
     */
    protected function is_read($value = 0)
    {
        return $this->_property_convert_type($value, 'bool');
    }

    /**
     * 是否禁用 (默认:否, 否=>0, 是=>1)
     * @param string|bool|int $value
     * @return bool|int|mixed|string
     */
    protected function is_disable($value = 0)
    {
        return $this->_property_convert_type($value, 'bool');
    }

    /**
     * 是否隐藏 (默认:否, 否=>0, 是=>1)
     * @param string|bool|int $value
     * @return bool|int|mixed|string
     */
    protected function is_hidden($value = 0)
    {
        return $this->_property_convert_type($value, 'bool');
    }

    /**
     * 字段名称
     * @param string $value
     * @return string
     */
    protected function name(string $value): string
    {
        return $value;
    }

    /**
     * 字段名称宽度
     * @param int|string $value
     * @return int
     */
    protected function name_width($value): int
    {
        return $value ?: 80;
    }

    /**
     * 字段名称是否展示 (默认:是, 否=>0, 是=>1)
     * @param string|bool|int $value
     * @return bool|int|mixed|string
     */
    protected function name_show($value = 0)
    {
        return $this->_property_convert_type($value, 'bool');
    }

    /**
     * 适用于时间和日期表单
     * @return void
     */
    protected function timestamp($value, $data)
    {
        return $value;
    }

    /**
     * 时间格式
     * @return void
     */
    protected function time_format($value)
    {
        return $value;
    }

    /**
     * 最大评分值
     * @param $value
     * @return int
     */
    protected function max_score($value): int
    {
        return intval($value);
    }

    /**
     * 低分界限值
     * @param $value
     * @return int
     */
    protected function low_score_limit($value): int
    {
        return intval($value);
    }

    /**
     * 高分界限值
     * @param $value
     * @return int
     */
    protected function high_score_limit($value): int
    {
        return intval($value);
    }

    /**
     * 是否允许半选 (默认:否, 否=>0, 是=>1)
     * @param string|bool|int $value
     * @return bool|int|mixed|string
     */
    protected function is_half_select($value = 0)
    {
        return $this->_property_convert_type($value, 'bool');
    }

    /**
     * 最小值
     * @param $value
     * @return int
     */
    protected function min_value($value): int
    {
        return intval($value);
    }

    /**
     * 最大值
     * @param $value
     * @return int
     */
    protected function max_value($value): int
    {
        return intval($value);
    }

    /**
     * 增减步长
     * @param $value
     * @return int
     */
    protected function step_value($value): int
    {
        return intval($value);
    }

    /**
     * 是否范围选择 (默认:否, 否=>0, 是=>1)
     * @param string|bool|int $value
     * @return bool|int|mixed|string
     */
    protected function is_scope_choose($value = 0)
    {
        return $this->_property_convert_type($value, 'bool');
    }

    /**
     * 宽
     * @return string|int
     */
    protected function width($value)
    {
        return $value;
    }

    /**
     * 高
     * @return string|int
     */
    protected function height($value)
    {
        return $value;
    }

    /**
     * 上传地址
     * @param $value
     * @param $data
     * @return string
     */
    protected function upload_url($value, $data): string
    {
        switch ($data['type']) {
            case 'image':
                $value = $value ?: request()->domain() . $this->ImageUploadUrl;
                break;
            case 'file':
                $value = $value ?: request()->domain() . $this->FileUploadUrl;
                break;
            case 'rich_text':
                $value = $value ?: request()->domain() . $this->UploadUrl;
                break;
        }
        return $value;
    }

    /**
     * 是否多选 (默认:否, 否=>0, 是=>1)
     * @param string|bool|int $value
     * @return bool|int|mixed|string
     */
    protected function is_multiple_choose($value = 0)
    {
        return $this->_property_convert_type($value, 'bool');
    }

    /**
     * 上传文件类型
     * @param $value
     * @return array
     */
    protected function file_type($value): array
    {
        return is_string($value) ? array_filter(explode(',', $value)): $value;
    }

    /**
     * 说明文本
     * @param string $value
     * @return string
     */
    protected function remark(string $value = ''): string
    {
        return $value;
    }

    /**
     * 单位
     * @param string $value
     * @return string
     */
    protected function unit(string $value = ''): string
    {
        return $value;
    }

    /**
     * 小数位数
     * @param string|integer $value
     * @return int
     */
    protected function digit($value = ''): int
    {
        return intval($value);
    }

    /**
     * 是否大写 (默认:否, 否=>0, 是=>1)
     * @param string|bool|int $value
     * @return bool|int|mixed|string
     */
    protected function is_capital($value = 0)
    {
        return $this->_property_convert_type($value, 'bool');
    }

    /**
     * 大写文本
     * @param string $value
     * @param $data
     * @return string
     */
    protected function capital_text(string $value, $data): string
    {
        if (($data['is_capital'] ?? 0) && empty($value)) {
            $value = $this->number_convert_2_cn($data['default'], false, true);
        }
        return $value;
    }

    /**
     * 类型装换
     * @param $value
     * @param string $type
     * @return bool|int|mixed|string
     */
    private function _property_convert_type($value, string $type = 'int')
    {
        switch ($type) {
            case 'int': case 'integer':
            return intval($value);
            case 'string':case 'str':
            return strval($value);
            case 'bool':case 'boolean':
            return boolval($value);
        }
        return $value;
    }

    /**
     * 判断是否是一维数据
     * @param $data
     * @return bool
     */
    private function is_one_dimensional_array($data): bool
    {
        if(is_object($data)) {
            $data = json_decode(json_encode($data), true);
        }
        if(isset($data[0])) {
            return false;
        }
        return true;
    }

    /**
     * 转换大写金额
     * @param $num
     * @param bool $mode (是否设置为千百万读法 true=>千百万读法 false=>纯读法)
     * @param bool $sim (是否设置为繁体字, true=>简体  false=>繁体)
     * @return string
     */
    private function number_convert_2_cn($num, bool $mode = true, bool $sim = true): string
    {
        if(!is_numeric($num)) return '';
        $char  = $sim ? array('零','一','二','三','四','五','六','七','八','九')
            : array('零','壹','贰','叁','肆','伍','陆','柒','捌','玖');
        $unit  = $sim ? array('','十','百','千','','万','亿','兆')
            : array('','拾','佰','仟','','萬','億','兆');
        $retrieval = (explode('.', $num)[0] ?? '') ? ($mode ? '元':(isset(explode('.', $num)[1]) ? '点': '')): (isset(explode('.', $num)[1]) ? '点': '');
        //小数部分
        if(strpos($num, '.')){
            list($num,$dec) = explode('.', $num);
            $dec = strval(round($dec,2));
            if($mode){
                $retrieval .= (!isset($dec[0]) ?: "{$char[$dec[0]]}角") . (!isset($dec[1]) ?: "{$char[$dec[1]]}分");
            }else{
                for($i = 0,$c = strlen($dec);$i < $c;$i++) {
                    $retrieval .= $char[$dec[$i]];
                }
            }
        }
        //整数部分
        $str = $mode ? strrev(intval($num)) : strrev($num);
        for($i = 0,$c = strlen($str);$i < $c;$i++) {
            $out[$i] = $char[$str[$i]];
            if($mode){
                $out[$i] .= $str[$i] != '0'? $unit[$i%4] : '';
                if($i>1 and $str[$i]+$str[$i-1] == 0){
                    $out[$i] = '';
                }
                if($i%4 == 0){
                    $out[$i] .= $unit[4+floor($i/4)];
                }
            }
        }
        return join('',array_reverse($out)) . $retrieval;
    }

    public function __call($name, $arguments)
    {
        switch (array_pop($arguments)) {
            case 'default':
                return $arguments[0];
            default:
                return $arguments;
        }
    }
}