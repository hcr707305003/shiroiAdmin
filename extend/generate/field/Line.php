<?php

namespace generate\field;

class Line extends Field
{

    public static string $html = <<<EOF
<div class="form-group text-center">
    <hr color=[FORM_NAME] SIZE=1>
</div>\n
EOF;

    public static function create($data): string
    {
        return  str_replace(
            array('[FORM_NAME]'),
            array($data['form_name']),
            self::$html);
    }

}