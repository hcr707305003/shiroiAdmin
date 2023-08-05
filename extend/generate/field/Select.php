<?php
/**
 * 列表选择
 * @author yupoxiong<i@yufuping.com>
 */

namespace generate\field;

class Select extends Field
{

    public static string $html = <<<EOF
<div class="form-group row rowSelect">
    <label for="[FIELD_NAME]" class="col-sm-2 col-form-label">[FORM_NAME]</label>
    <div class="col-sm-10 col-md-4 formInputDiv">
        <select name="[FIELD_NAME]" id="[FIELD_NAME]" class="form-control select2bs4 fieldSelect" data-placeholder="请选择[FORM_NAME]">
            <option value=""></option>
            [OPTION_DATA]
        </select>
        [CHANGE_TEXT]
    </div>
    <div class="form-group row rowText" id="[FIELD_NAME]_input" style="display: none;"></div>
    <script>
        $('#[FIELD_NAME]').select2({
            theme: 'bootstrap4'
        });
        
        $('#[FIELD_NAME]_add').click(function () {
            $('#[FIELD_NAME]_input').append('<label for="[FIELD_NAME]" class="col-sm-2 col-form-label">[FORM_NAME]</label><div class="col-sm-10 col-md-4 formInputDiv"><input id="[FIELD_NAME]" name="[FIELD_NAME]" placeholder="请输入[FORM_NAME]" type="text" class="form-control fieldText"><a class="btn btn-default" id="[FIELD_NAME]_change">切换</a></div>');
            $('#[FIELD_NAME]_select').hide();
            $('#[FIELD_NAME]_input').show();
        });
        $("#[FIELD_NAME]_input").on('click', '#[FIELD_NAME]_change', function () {
            $("#[FIELD_NAME]_select").show();
            $("#[FIELD_NAME]_input").hide().html("");
        });
    </script>
</div>\n
EOF;

    public static function create($data): string
    {
        $html = self::$html;
        return str_replace(array('[FORM_NAME]', '[FIELD_NAME]', '[OPTION_DATA]'), array($data['form_name'], $data['field_name'] ?? '', $data['option_data'] ?? ''), $html);
    }

}