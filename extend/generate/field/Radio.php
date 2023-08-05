<?php

namespace generate\field;

class Radio
{
    public static string $html = <<<EOF
<div class="form-group row rowMultiSelect">
    <label for="[FIELD_NAME]" class="col-sm-2 col-form-label">[FORM_NAME]</label>
    <div class="col-sm-10 col-md-4 formInputDiv">
        <div class="radio-list" style="display: flex; align-content: space-around; flex-direction: row;">
            [RADIO_LIST]
        </div>
    </div>
</div>\n
EOF;

    public static function create($type, $name, $field, $content, $option): string
    {
        $radio = "";
        if(is_array($option)) foreach ($option as $k => $v) {
            if($k == $content) {
                $ischeck = 'checked="checked"';
            } else {
                $ischeck = '';
            }
            $radio .= <<<EOF
<div class="radio-inline" style="margin-right: 10px;">
    <label class="radio">
       <input type="radio" value="{$k}" {$ischeck} name="{$field}" data-title="{$v}" />{$v}
    </label>
</div>
EOF;
        }
        return str_replace(array('[FORM_NAME]', '[FIELD_NAME]', '[RADIO_LIST]'), array($name, $field, $radio), self::$html);
    }
}