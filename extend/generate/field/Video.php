<?php
/**
 * 上传单图
 * @author yupoxiong<i@yufuping.com>
 */

namespace generate\field;

class Video
{
    public static string $html = <<<EOF
<div class="form-group row rowVideo">
    <label for="[FIELD_NAME]" class="col-sm-2 col-form-label">[FORM_NAME]</label>
    <div class="col-sm-10 col-md-4 formInputDiv [FIELD_NAME]_file"> 
        <input id="[FIELD_NAME]_file" name="[FIELD_NAME]_file" type="file" class="file-loading" accept="video/*" data-initial-preview="[FIELD_DEFAULT]">
        <input name="[FIELD_NAME]" id="[FIELD_NAME]" value="[FIELD_DEFAULT]" hidden placeholder="请上传[FORM_NAME]" class="fieldVideo">
    </div>
    
    <script>
        initUploadVideo('[FIELD_NAME]');
    </script>
</div>\n  
EOF;

    public static function create($name,$content,$field,$type, $data): string
    {
        $html = self::$html;
        return str_replace(array('[FORM_NAME]', '[FIELD_NAME]', '[FIELD_DEFAULT]'), array($name, $field, $content), $html);
    }
}