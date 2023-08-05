<?php
/**
 * @author yupoxiong<i@yufuping.com>
 */

namespace generate\field;

class SwitchField extends Field
{
    public static string $html = <<<EOF
<div class="form-group row rowSwitch" id="[FIELD_NAME]_switch">
    <label for="[FIELD_NAME]" class="col-md-2 col-form-label" >[FORM_NAME]</label>
    <div class="col-sm-10 col-md-4 formInputDiv">
    <input [field_default] class="input-switch"  id="[FIELD_NAME]" value="1" {if(!isset(\$data) || \$data.status==[ON_STATUS])}checked{/if} type="checkbox" />
    <input class="switch fieldSwitch" id="[FIELD_NAME]_value" placeholder="[FORM_NAME]" name="[FIELD_NAME]" value="{\$data.[FIELD_NAME]|default='[FIELD_DEFAULT]'}" hidden />
    </div>
    <script>
        $('#[FIELD_NAME]').bootstrapSwitch({
            onText: "[ON_TEXT]",
            offText: "[OFF_TEXT]",
            onColor: "success",
            offColor: "danger",
            onSwitchChange: function (event, state) {
                $(event.target).closest('.bootstrap-switch').next().val(state ? '[ON_STATUS]' : '[OFF_STATUS]').change();
                if(typeof get_[FIELD_NAME] === 'function' ) get_[FIELD_NAME](state);
            }
        });
    </script>
</div>\n
EOF;

    public static function create($data): string
    {
        $html = self::$html;

        //switch开的文字
        if (isset($data['on_text'])) {
            $html = str_replace('[ON_TEXT]', $data['on_text'], $html);
        } else {
            $html = str_replace('[ON_TEXT]', '是', $html);
        }
        //switch关的文字
        if (isset($data['off_text'])) {
            $html = str_replace('[OFF_TEXT]', $data['off_text'], $html);
        } else {
            $html = str_replace('[OFF_TEXT]', '否', $html);
        }
        //switch的值
        $status = [1,0];
        $html = str_replace(array('[ON_STATUS]', '[OFF_STATUS]'), $status, $html);

        return str_replace(array('[FORM_NAME]', '[FIELD_NAME]', '[FIELD_DEFAULT]'), array($data['form_name'], $data['field_name'], $data['field_default']), $html);
    }
}