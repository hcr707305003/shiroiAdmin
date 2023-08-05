<?php
/**
 * @author yupoxiong<i@yufuping.com>
 */

namespace generate\field;

class File
{

    public static string $html = <<<EOF
<div class="form-group row rowFile">
    <label for="[FIELD_NAME]" class="col-sm-2 col-form-label">[FORM_NAME]</label>
    <div class="col-sm-10 col-md-4 formInputDiv [FIELD_NAME]_file"> 
        <input id="[FIELD_NAME]_file" name="[FIELD_NAME]_file" type="file" class="file-loading" data-initial-preview="{if isset(\$data)}{\$data.[FIELD_NAME]}{/if}">
        <input name="[FIELD_NAME]" id="[FIELD_NAME]" value="[FIELD_VALUE]" hidden placeholder="请上传[FORM_NAME]" class="fieldFile">
        <input name="[FIELD_NAME]_name" hidden id="[FIELD_NAME]_name" value="[FILE_INFO]">
    </div>

    <script>
        initUploadFile('[FIELD_NAME]','','file');
        $(".[FIELD_NAME]_file .file-caption-info").append("[FILE_INFO]");
        $(".[FIELD_NAME]_file .file-size-info").append("[FILE_SIZE]");
       
        
    </script>
</div>\n
EOF;

    public static function create($name,$content,$field,$type, $data): string
    {
        $html = self::$html;
        $size = $content ? (get_remote_file_headers($content)['Content-Length']??0) : 0;
        $alias_content = $data[$field.'_name']?? ($content ? urldecode(basename($content)): $content);
//        dd($alias_content);

        return str_replace(array('[FORM_NAME]', '[FIELD_NAME]', '[FILE_INFO]', '[FILE_SIZE]', '[FIELD_VALUE]'), array($name, $field, $alias_content, $size ? format_size($size) : '', $content), $html);
    }
}
