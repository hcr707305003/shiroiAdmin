<?php
/**
 * 设置表单操作
 * @author yupoxiong<i@yufuping.com>
 */
namespace app\admin\traits;

use generate\field\Field;

trait AdminSettingForm
{
    protected function getFieldTable($type, $name, $field, $content, $option, $url_condition) {
        $type_arr = $type;
        if(is_array($type) && isset($type['{{type}}'])) {
            $type = $type['{{type}}'];
            unset($type_arr['{{type}}']);
        }
        /** @var Field $fieldClass */
        $fieldClass = '\\generate\\field\\' . parse_name($type === 'switch' ? 'switch_field' : $type, 1, true);
        switch ($type) {
            case  'number':
                $uuid = time().str_pad(rand(0,999),3,'0',STR_PAD_LEFT);
                $form = $fieldClass::$td_html;
                $form = str_replace("[FIELD_DEFAULT]", $content,$form);
                $form = str_replace("[FIELD_NAME]", "{$field}_{$uuid}",$form);
                $form = str_replace("[FORM_NAME]", $name,$form);
                $form = str_replace("[ID]", $content,$form);
                $content = str_replace("[URL_DEFAULT]", $url_condition,$form);
                break;
            case 'image':
            case 'color_select':
            case 'color':
                $form = $fieldClass::$td_html;
                $content = str_replace("[FIELD_NAME]", $content,$form);
                break;
            case 'switch':
            case 'select':
            $content = $option;
                break;
            default:
                break;
        }
        return $content;
    }
    protected function getFieldForm($type, $name, $field, $content, $option, $url_condition='', $key = '', $data = [])
    {
        $type_arr = $type;
        if(is_array($type) && isset($type['{{type}}'])) {
            $type = $type['{{type}}'];
            unset($type_arr['{{type}}']);
        } else if(is_string($type)) {
            $type_arr = explode('|',$type);
            $type = reset($type_arr);
        }

        /** @var Field $fieldClass */
        $fieldClass = '\\generate\\field\\' . parse_name($type === 'switch' ? 'switch_field' : $type, 1, true);
        $form       = $fieldClass::$html;
        $form_value = "{\$data.[FIELD_NAME]|default='[FIELD_DEFAULT]'}";
        switch ($type) {
            case 'switch':
                if($option && is_array($option)) {
                    $status = array_keys($option);
                    $values = array_values($option);
                } else {
                    $status = [1,0];
                    $values = ['是', '否'];
                }
                if(!in_array($content,$status)) {
                    $content = reset($status);
                }
                $form = str_replace("[field_default]",((int)array_search($content,$status) ? '' : 'checked'),$form);
                $form = str_replace(array('[ON_TEXT]', '[OFF_TEXT]'), $values, $form);
                $form = str_replace(array('[ON_STATUS]', '[OFF_STATUS]'), $status, $form);
                $form = str_replace(array($form_value, '[FIELD_NAME]', '[FORM_NAME]',), array($content, $field, $name,), $form);
                break;
            case 'select':
                $option_html = '';
                if(is_array($option)) foreach ($option as $key => $item) {
                    $select = '';
                    if ($content == $key) {
                        $select = 'selected';
                    }
                    $option_html .= '<option value="' . $key . '" ' . $select . '>' . $item . '</option>';
                }
                if(is_string($option)) foreach (explode("\r\n", $option) as $v) {
                    if($v) {
                        list($key,$item) = explode('||', $v);
                        $select = '';
                        if($content == $key) {
                            $select = 'selected';
                        }
                        $option_html .= '<option value="' . $key . '" ' . $select . '>' . $item . '</option>';
                    }
                }
                $form = str_replace('[OPTION_DATA]', $option_html, $form);
                $form = str_replace(array($form_value, '[FIELD_NAME]', '[FORM_NAME]',), array($content, $field, $name,), $form);
                if(in_array('text',$type_arr)) {
                    $form = str_replace('[CHANGE_TEXT]', '<a class="btn btn-default" id="'.$field.'_add" >新增</a>', $form);
                } else {
                    $form = str_replace('[CHANGE_TEXT]', '', $form);
                }

                break;
            case 'multi_select':
                $option_html = '';
                if(is_array($content)) {
                    foreach ($content as $c) {
                        $option_html .= '<option value="' . $c['name'] . '" selected>' . $c['name'] . '</option>';
                    }
                } else {
                    if(is_string($option)){
                        $option      = explode("\r\n", $option);
                    }
                    if(is_string($content)){
                        $content = explode(',',$content);
                    }
                    foreach ($option as $item) {
                        $option_key_value = explode('||', $item);
                        $select = '';
                        if(is_array($content)) {
                            if (in_array($option_key_value[0], $content, false)) {
                                $select = 'selected';
                            }
                        } else {
                            if ($option_key_value[0] == $content) {
                                $select = 'selected';
                            }
                        }
                        $option_html .= '<option value="' . $option_key_value[0] . '" ' . $select . '>' . $option_key_value[1] . '</option>';
                    }
                }

                $form    = str_replace('[OPTION_DATA]', $option_html, $form);
                $content = '';
                $form = str_replace(array($form_value, '[FIELD_NAME]',), array($content, $field,), $form);
                $form = ('\\generate\\field\\' . parse_name($type, 1, true))::modify($name,$form);
                break;
            case 'image':
                $search1 = "{if isset(\$data)}{\$data.[FIELD_NAME]}{/if}";
                $form    = str_replace(array($search1), array($content), $form);
                break;
            case 'editor':
                $search1 = "{\$data.[FIELD_NAME]|raw|default='[FIELD_DEFAULT]'}";

                $search2  = "{\$data.[FIELD_NAME]|raw|default='<p>[FIELD_DEFAULT]</p>'}";
                if($content===''){
                    $content = '<p></p>';
                }else if(strpos($content,'<p>')!==0){
                    $content = '<p>'.$content.'<p/>';
                }

                $form = str_replace(array($search1, $search2), array($content, $content), $form);

                break;
            case 'map':
                $position = is_string($content) ? explode(',', $content) : $content;
                $lng      = $position[0] ?? 117;
                $lng      = $lng > 180 || $lng < -180 ? 117 : $lng;

                $lat = $position[1] ?? 36;
                $lat = $lat < -90 || $lat > 90 ? 36 : $lat;

                $search1 = "{\$data.[FIELD_NAME_LNG]|default='117'}";
                $search2 = "{\$data.[FIELD_NAME_LAT]|default='36'}";
                $search3 = 'name="[FIELD_NAME_LNG]"';
                $search4 = 'name="[FIELD_NAME_LAT]"';

                $search5 = 'id="[FIELD_NAME_LNG]"';
                $search6 = 'id="[FIELD_NAME_LAT]"';
                $search7 = "$('#[FIELD_NAME_LNG]')";
                $search8 = "$('#[FIELD_NAME_LAT]')";

                $replace4 = $replace3 = 'name="' . $field . '[]"';
                $replace5 = 'id="' . $field . '_lng"';
                $replace6 = 'id="' . $field . '_lat"';
                $replace7 = "$('#" . $field . "_lng')";
                $replace8 = "$('#" . $field . "_lat')";

                $search9 = '[FIELD_NAME_LNG]';


                $form = str_replace(
                    array($search1, $search2, $search3, $search4, $search5, $search6, $search7, $search8, $search9),
                    array($lng, $lat, $replace3, $replace4, $replace5, $replace6, $replace7, $replace8, $field),
                    $form);

                $content = '';
                break;
            case 'radio_tree_select':
                $form = ('\\generate\\field\\' . parse_name($type, 1, true))::create($type, $name, $field, $content, $option, $type_arr);
                break;
            case 'video':
            case 'file':
                $form = ('\\generate\\field\\' . parse_name($type, 1, true))::create($name,$content,$field,$type, $data);
                break;
            case 'color':
            case 'icon':
            case 'number':
            case 'hidden':
            case 'datetime_range':
            case 'time_range':
            case 'disable_text':
            case 'textarea':
            case 'text':
            case 'time':
            case 'year_month':
            case 'date':
            case 'datetime':
            case 'multi_file':
            case 'multi_image':
            case 'line':
            case 'title':
            default:
                break;
        }

        return str_replace(array($form_value, '[FIELD_NAME]', '[FORM_NAME]',), array($content, $field, $name,), $form);

    }

}