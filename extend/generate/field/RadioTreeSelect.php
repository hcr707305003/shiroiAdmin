<?php

namespace generate\field;

class RadioTreeSelect
{
    public static string $html = <<<EOF
<style>
    .[FIELD_NAME]_tree_multiselect .selections{
        width: 100%!important;
    }
    .[FIELD_NAME]_tree_multiselect .title{
        background-color: white!important;
        color: black!important;
    }
</style>
<div class="form-group row rowMultiSelect">
    <label for="[FIELD_NAME]" class="col-sm-2 col-form-label">[FORM_NAME]</label>
    <div class="col-sm-10 col-md-6 formInputDiv [FIELD_NAME]_tree_multiselect">
        <select id="[FIELD_NAME]" multiple="multiple">
            [OPTION_DATA]
        </select>
        <script type="text/javascript">
          $("#[FIELD_NAME]").treeMultiselect({
            searchable: true,
            hideSidePanel: true,
            allowBatchSelection: false,
            startCollapsed: false
          });
          var arr = "[ID_STR]".split(',');
          $(function (){
                $(".[FIELD_NAME]_tree_multiselect").find("input[type='checkbox']").each(function(){
                    $(this).attr("type", 'radio').attr("name", "[FIELD_NAME]")
                });
                $(".[FIELD_NAME]_tree_multiselect .collapse-section").each(function() {
                    $(this).after(`<input type='radio' name='[FIELD_NAME]'>`);
                })
                $(".[FIELD_NAME]_tree_multiselect input[type='radio']").each(function(key) {
                    console.log(arr);
                    $(this).val(arr[key])
                    if(arr[key] == "[DEFAULT_VALUE]") {
                      $(this).attr('checked', 'checked');
                    }
                })
          });
        </script>
    </div>
</div>\n
EOF;

    public static function create($type, $name, $field, $content, $option, $type_arr): string
    {
//        dd($option, $type_arr['{{is_show_parent}}']);
        $html = self::$html;
        $str = "";
        if($is_show_parent = ($type_arr['{{is_show_parent}}']??1)) $arr = ['0'];
        $func = function ($result, $node, $node_str = '') use (&$func, &$arr, &$str, $field) {
            if(is_array($result)) foreach ($result as $menu) {
                $arr[] = $menu['id'];
                //追加参数
                if(isset($menu['son'])) {
                    $func($menu['son'], $node+1, $node_str? ($node_str . '/' . $menu['title']): $menu['title']);
                } else {
                    $str .= "<option data-section='{$node_str}'>{$menu['title']}</option>";
                }
            }
        };

        $func($option, 0, ($is_show_parent? '顶级':''));
//        dd($type, $name, $field, $content, $option);
        return str_replace(array('[FORM_NAME]', '[FIELD_NAME]', '[OPTION_DATA]', '[ID_STR]', '[DEFAULT_VALUE]'), array($name, $field, $str, implode(',',$arr), $content), $html);
    }
}