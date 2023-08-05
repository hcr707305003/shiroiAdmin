<?php
/**
 * 列表多项选择
 * @author yupoxiong<i@yufuping.com>
 */

namespace generate\field;

class MultiSelect extends Field
{
    public static string $html = <<<EOF
<div class="form-group row rowMultiSelect">
    <label for="[FIELD_NAME]" class="col-sm-2 col-form-label">[FORM_NAME]</label>
    <div class="col-sm-10 col-md-4 formInputDiv">
        <select name="[FIELD_NAME][]" id="[FIELD_NAME]" data-placeholder="请选择[FORM_NAME]" class="form-control fieldMultiSelect" multiple="multiple">
            <option value=""></option>
             [OPTION_DATA]
        </select>
    </div>

    <script>
        $('#[FIELD_NAME]').select2();
    </script>
</div>\n
EOF;

    public static string $add_info = <<<EOF
<a title="添加新数据" data-toggle="tooltip" class="btn btn-primary btn-sm " href="[ADD_URL]">
                    <i class="fa fa-plus"></i>
                </a>
EOF;

    public static function parseUrl($url)
    {
        //解析url参数
        parse_str(parse_url($url)['query']??'',$param_arr);
        //拼接默认参数
        $param_arr['id'] = input('id');
        //返回url
        return parse_url($url)['path'] . '?' . http_build_query($param_arr);
    }

    public static function create($data): string
    {
        $html = self::$html;
        return str_replace(array('[FORM_NAME]', '[FIELD_NAME]', '[OPTION_DATA]'), array($data['form_name'], $data['field_name'] ?? '', $data['option_data'] ?? ''), $html);
    }

    public static function modify($name,$form)
    {
        $add_url = "";
        $add_show = false;
        if(is_array($name)) {
            //设置名称
            $form_name = $name['{{title}}']??'';
            if(isset($name['{{add_show}}'])) {
                $add_show = $name['{{add_show}}'];
            }
            if(isset($name['{{add_url}}'])) {
                $add_url = $name['{{add_url}}'];
                //解析url参数
                $add_url = self::parseUrl($add_url);
            }

        } else {
            $form_name = $name;
        }
        return str_replace(array('[FORM_NAME]', '[ADD]', '[ADD_URL]'), array($form_name,($add_show ? self::$add_info: ''),$add_url), $form);
    }
}