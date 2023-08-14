<?php
// 应用公共文件


use think\facade\Config;

if (!function_exists('get_file_type')) {
    /**
     * 根据文件后缀获取文件类型
     * @param string $filename
     * @return int|string
     */
    function get_file_type(string $filename = '')
    {
        $suffix = pathinfo($filename, PATHINFO_EXTENSION);
        $fileType = 'file';
        foreach (config('file_type') as $type => $data) {
            if(in_array($suffix, $data)) {
                return $type;
            }
        }
        return $fileType;
    }
}

if (!function_exists('setting')) {
    /**
     * 设置相关助手函数
     * @param string|array $name
     * @param null $value
     * @return array|bool|mixed|null
     */
    function setting($name = '', $value = null)
    {
        if($name) {
            //文件是否存在，不存在则查数据库
            $settingGroup = Config::get($name);
            if(($settingGroup === null) || ($settingGroup === [])) {
                //获取组信息
                $settingGroup = get_database_setting($name);
            }
            if($settingGroup !== null) {
                return $settingGroup;
            }
        }
        return $value;
    }

}

if (!function_exists('get_database_setting')) {
    /**
     * 获取数据库配置
     * @param $name
     * @param null $value
     * @return array|string
     */
    function get_database_setting($name, $value = null)
    {
        $result = [];
        if($name) {
            if(is_string($name)) $name = explode('.',$name);
            $group = (new app\common\model\SettingGroup)->where('code', $name[0])->findOrEmpty();
            if ($group->isExists()) {
                if(isset($name[1])) {
                    $setting = (new app\common\model\Setting)->where([
                        'code' => $name[1],
                        'setting_group_id' => $group['id']
                    ])->findOrEmpty();
                    if($setting->isExists()) {
                        foreach ($setting->content as $content) {
                            $result[$content['field']] = $content['content'];
                        }

                        if(isset($name[2])) {
                            return $result[$name[2]] ?? $value;
                        }
                    }
                } else {
                    foreach ($group->setting as $setting) {
                        $key_setting = [];
                        foreach ($setting->content as $content) {
                            $key_setting[$content['field']] = $content['content'];
                        }
                        $result[$setting->code] = $key_setting;
                    }
                }
            }
        }
        return $result;
    }
}


if (!function_exists('format_size')) {
    /**
     * 格式化文件大小单位
     * @param $size
     * @param string $delimiter
     * @return string
     */
    function format_size($size, string $delimiter = ''): string
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
        for ($i = 0; $size >= 1024 && $i < 5; $i++) {
            $size /= 1024;
        }
        return round($size, 2) . $delimiter . $units[$i];
    }
}

if(!function_exists('htmlentities_view')){
    /**
     * 封装默认的 htmlentities 函数，避免在php8.1环境中view传入null报错
     * @param mixed $string
     * @return string
     */
    function htmlentities_view($string): string
    {
        return htmlentities((string)$string);
    }
}

if (!function_exists('rand_str')) {
    /**
     * 随机数
     * @param int $len
     * @param int $type (类型
     *      0 => 数字 + 大小写字母
     *      1 => 数字
     *      2 => 大写字母
     *      3 => 小写字母
     *      4 => 数字 + 大写字母
     *      5 => 数字 + 小写字母
     *      6 => 数字 + 特殊符号
     *      7 => 大写字母 + 特殊符号
     *      8 => 小写字母 + 特殊符号
     *      9 => 数字 + 大小写字母 + 特殊符号
     *     10 => 大小写字母 + 特殊符号
     * )
     * @return string
     */
    function rand_str(int $len = 6, int $type = 0): string
    {
        $n1 = [0,1,2,3,4,5,6,7,8,9];
        $n2 = ['a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'];
        $n3 = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
        $n4 = ['~','!','@','#','$','%','^','&','*','(',')','_','-','+','=','{','}','[',']',';',':',"'",'"','|','\\','/','?','<',',','>','.'];
        $data = [];
        switch ($type) {
            case 0:
                $data = array_merge($n1,$n2,$n3);break;
            case 1:
                $data = $n1;break;
            case 2:
                $data = $n3;break;
            case 3:
                $data = $n2;break;
            case 4:
                $data = array_merge($n1,$n3);break;
            case 5:
                $data = array_merge($n1,$n2);break;
            case 6:
                $data = array_merge($n1,$n4);break;
            case 7:
                $data = array_merge($n3,$n4);break;
            case 8:
                $data = array_merge($n2,$n4);break;
            case 9:
                $data = array_merge($n1,$n2,$n3,$n4);break;
            case 10:
                $data = array_merge($n2,$n3,$n4);break;
        }
        $str = "";
        $data_length = count($data);
        for ($i = 0; $i < $len; $i++) {
            $str .= $data[rand(0,$data_length - 1)];
        }
        return $str;
    }
}

if (!function_exists('small_mount_to_underline')) {
    /**
     * 小驼峰转下划线
     * @param string $value
     * @return string
     */
    function small_mount_to_underline(string $value): string
    {
        return strtolower(preg_replace('/(?<=[a-z])([A-Z])/', '_$1', $value));
    }
}


if (!function_exists('underline_to_small_mount')) {
    /**
     * 下划线转小驼峰
     * @param string $value
     * @param bool $is_capitalize (首字母是否大写)
     * @return string
     */
    function underline_to_small_mount(string $value, bool $is_capitalize = false): string
    {
        $value = ltrim(str_replace(" ", "", ucwords('_'.str_replace('_'," ", strtolower($value)))), '_');
        return $is_capitalize ? ucwords($value): $value;
    }
}

if(!function_exists('both_field_exists')) {
    /**
     * 判断文本是否在(头部|尾部|当前文本)存在
     * @param string $string (文本内容)
     * @param string $subString （是否存在该字段）
     * @param int $type (0=>不指定头部或者尾部, 1=>头部, 2=>尾部)
     * @return array
     */
    function both_field_exists(string $string, string $subString, int $type = 0): array
    {
        $bool = false;
        $cut_content = $string;
        if ($type == 0) {
            $bool = mb_strpos($string,$subString);
            if($bool) {
                $cut_content = str_replace($subString,'',$string);
            }
        } elseif ($type == 1) {
            $bool = mb_substr($string, 0, mb_strlen($subString)) === $subString;
            if($bool) {
                $cut_content = mb_substr($string,mb_strlen($subString),(mb_strlen($string)-mb_strlen($subString)));
            }
        } elseif ($type == 2) {
            $bool = mb_substr($string, mb_strpos($string, $subString)) === $subString;
            if($bool) {
                $cut_content = mb_substr($string,0,mb_strpos($string, $subString));
            }
        }
        return compact('bool','cut_content');
    }
}

if (!function_exists('curl_http')) {
    /**
     * 数据请求
     * @param string $url
     * @param string $method
     * @param array|string $data
     * @param array $headers
     * @param array $cookies
     * @param int $seconds
     * @return Curl
     */
    function  curl_http(string $url = "", string $method = "get", $data = [], array $headers = [], array $cookies = [], int $seconds = 10): Curl
    {
        //实例化
        $curl = new Curl();
        $method = strtolower($method);

        //根据不同的请求方式处理数据
        switch ($method) {
            case "get":
                $curl->get($url);
                break;
            case "post":
                $curl->post($url,$data);
                break;
            case "download":
                $curl->download($url,$data);
        }
        //设置超时时间
        $curl->setTimeout($seconds);
        //默认设置头信息
        if($headers) $curl->setHeaders($headers);

        //默认设置cookies
        if($cookies) $curl->setCookies($cookies);

        return $curl;
    }
}

if (!function_exists('auth_code')) {
    /**
     * @param string $string (字符串)
     * @param string $operation (DECODE=>解密 ENCODE=>加密)
     * @param string $key (密匙 默认平台building)
     * @param int $expiry (失效期) 秒:单位
     * @example
     * 加密: $encode = auth_code('测试','ENCODE','building',30) //加密的字符串为:测试 加密类型:ENCODE 密钥为项目名:building 失效期为:30秒
     * 解密: auth_code($encode,'DECODE','building') //解密的字符串为:$encode 加密类型:ENCODE 密钥为项目名:building
     * @return false|string
     */
    function auth_code(string $string, string $operation = 'DECODE', string $key = '', int $expiry = 0)
    {
        // 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙
        $CKeyLength = 4;
        // 密匙
        $key = md5($key);
        // 密匙a会参与加解密
        $keyA = md5(substr($key, 0, 16));
        // 密匙b会用来做数据完整性验证
        $keyB = md5(substr($key, 16, 16));
        // 密匙c用于变化生成的密文
        $keyC = ($operation == 'DECODE' ? substr($string, 0, $CKeyLength) : substr(md5(microtime()), -$CKeyLength));
        // 参与运算的密匙
        $cryptKey = $keyA . md5($keyA . $keyC);
        $key_length = strlen($cryptKey);
        // 明文，前10位用来保存时间戳，解密时验证数据有效性，10到26位用来保存$keyB(密匙b)，
        //解密时会通过这个密匙验证数据完整性
        // 如果是解码的话，会从第$CKeyLength位开始，因为密文前$CKeyLength位保存 动态密匙，以保证解密正确
        $string = $operation == 'DECODE' ? base64_decode(substr($string, $CKeyLength)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyB), 0, 16) . $string;
        $string_length = strlen($string);
        $result = '';
        $box = range(0, 255);
        $rnDKey = array();
        // 产生密匙簿
        for ($i = 0; $i <= 255; $i++) {
            $rnDKey[$i] = ord($cryptKey[$i % $key_length]);
        }
        // 用固定的算法，打乱密匙簿，增加随机性，好像很复杂，实际上对并不会增加密文的强度
        for ($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rnDKey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
        // 核心加解密部分
        for ($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            // 从密匙簿得出密匙进行异或，再转成字符
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }
        if ($operation == 'DECODE') {
            // 验证数据有效性，请看未加密明文的格式
            if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyB), 0, 16))
                return substr($result, 26);
            else
                return '';
        } else {
            // 把动态密匙保存在密文里，这也是为什么同样的明文，生产不同密文后能解密的原因
            // 因为加密后的密文可能是一些特殊字符，复制过程可能会丢失，所以用base64编码
            return $keyC . str_replace('=', '', base64_encode($result));
        }
    }
}

if (!function_exists('rand_number')) {

    /**
     * 生成数字唯一id方法
     * @return int|string
     */
    function rand_number()
    {
        $return_arr = array();
        $return_arr[] = mt_rand(10000000,99999999);
        return array_flip(array_flip($return_arr))[0];
    }
}

if (!function_exists('get_between_time')) {
    /**
     * 转化时间差显示
     * @param $startTime
     * @param $endTime
     * @param int $type (1=>时间格式 2=>时间戳格式)
     * @return array
     */
    function get_between_time($startTime, $endTime, int $type = 1): array
    {
        if($type == 1) {
            $startTime = strtotime($startTime);
            $endTime = strtotime($endTime);
        }
        //计算两个日期之间的时间差
        $diff = abs($endTime - $startTime);
        //转换时间差的格式
        $year = floor($diff / (365*60*60*24));
        $month = floor(($diff - $year * 365*60*60*24) / (30*60*60*24));
        $day = floor(($diff - $year * 365*60*60*24 - $month*30*60*60*24)/ (60*60*24));
        $hour = floor(($diff - $year * 365*60*60*24 - $month*30*60*60*24 - $day*60*60*24) / (60*60));
        $minute = floor(($diff - $year * 365*60*60*24 - $month*30*60*60*24 - $day*60*60*24 - $hour*60*60)/ 60);
        $second = floor(($diff - $year * 365*60*60*24 - $month*30*60*60*24 - $day*60*60*24 - $hour*60*60 - $minute*60));
        $total_day = floor($diff/3600/24);
        return compact('year','month','day','hour','minute','second','total_day');
    }
}

if (!function_exists('get_table_column')) {
    /**
     * 获取表的所有字段
     * @param string $table_name
     * @throws Exception
     * @return array
     */
    function get_table_column(string $table_name): array
    {
        $columnArr = [];
        foreach (\think\facade\Db::query("DESC {$table_name}") as $v) {
            $type = "";
            preg_replace_callback('/^(\w+)/',function ($matches) use (&$type) {
                $type = $matches[0];
            },$v['Type']);
            $columnArr[$v['Field']] = [
                'field' => $v['Field'],
                'type' => $type,
                'default' => $v['Default']
            ];
        }
        return $columnArr;
    }
}

if (!function_exists('createDir')) {
    /**
     * 创建目录
     * @param $dir
     * @return bool
     */
    function createDir($dir): bool
    {
        return is_dir($dir) or (createDir(dirname($dir)) and mkdir($dir, 0777));
    }
}

if (!function_exists('removeDir')) {
    /**
     * 删除目录
     * @param $dir
     * @return void
     */
    function removeDir($dir) {
        if (file_exists($dir)) {
            if ($dir_handle = @opendir($dir)) {
                while ($filename = readdir($dir_handle)) {
                    if ($filename != '.' && $filename != '..') {
                        $subFile = $dir . "/" . $filename;
                        if (is_dir($subFile)) {
                            removeDir($subFile);
                        }
                        if (is_file($subFile)) {
                            unlink($subFile);
                        }
                    }
                }
                closedir($dir_handle); //关闭目录资源
                rmdir($dir); //删除空目录
            }
        }
    }
}

if (!function_exists('createTree')) {
    /**
     * 创建树型结构
     * @param $array
     * @param string $childKey
     * @return array
     */
    function createTree($array, string $childKey = 'son'): array
    {
        //第一步 构造数据
        $items = [];
        foreach($array as $value){
            $items[$value['id']] = $value;
        }
        //第二部 遍历数据 生成树状结构
        $tree = [];
        foreach($items as $key => $value){
            if(isset($items[$value['parent_id']])){
                $items[$value['parent_id']][$childKey][] = &$items[$key];
                if(isset($items[$value['parent_id']][$childKey])) {
                    $items[$value['parent_id']][$childKey] = array_values($items[$value['parent_id']][$childKey]);
                }
            }else{
                $tree[] = &$items[$key];
            }
        }
        return $tree;
    }
}

if (!function_exists('getExt')) {
    /**
     * 获取文件名后缀(没有后缀则返回空字符串)
     * @param $filename (网络文件 本地路径文件)
     * @return string
     */
    function getExt($filename): string
    {
        //解析文件名或者url获取不带参数的url路径
        return strtolower(pathinfo(parse_url($filename)['path'] ?? '')['extension'] ?? '');
    }
}

if (!function_exists('consoleArrayToTable')) {
    /**
     * 控制台数组转成可视化表格供查看
     * @param $data (数组)
     * @example
     * 输出：
     * $data = [
     *      ['id' => 1, 'name' => 'shiroi'],
     *      ['id' => 2, 'name' => '卢本伟']
     * ];
     * 结果：
     * ┌────┬────────────────────┐
     * │ ID │         NAME       │
     * ├────┼────────────────────┤
     * │ 1  │ shiroi             │
     * │ 2  │ 卢本伟              │
     * └────┴────────────────────┘
     * @return string
     */
    function consoleArrayToTable($data): string
    {
        return (new \MathieuViossat\Util\ArrayToTextTable($data))->getTable();
    }
}

if (!function_exists('is_timestamp')) {
    /**
     * 判断是不是时间戳
     * @param int $timestamp
     * @return false|int
     */
    function is_timestamp(int $timestamp)
    {
        if (strtotime(date('Y-m-d H:i:s', $timestamp)) === $timestamp) {
            return $timestamp;
        } else {
            return false;
        }
    }
}

if (!function_exists('is_datetime')) {
    /**
     * 判断是不是日期时间
     * @param string $datetime
     * @return false|int
     */
    function is_datetime(string $datetime)
    {
        return strtotime($datetime);
    }
}

if (!function_exists('num_to_word')) {
    /**
     * 数字转换成中文
     * @param $num
     * @param bool $mode (是否设置为千百万读法 true=>千百万读法 false=>纯读法)
     * @param bool $sim (是否设置为繁体字, true=>简体  false=>繁体)
     * @return string
     */
    function num_to_word($num, bool $mode = true, bool $sim = true): string
    {
        if(!is_numeric($num)) return '';
        $char  = $sim ? array('零','一','二','三','四','五','六','七','八','九')
            : array('零','壹','贰','叁','肆','伍','陆','柒','捌','玖');
        $unit  = $sim ? array('','十','百','千','','万','亿','兆')
            : array('','拾','佰','仟','','萬','億','兆');
        $retrieval = (explode('.', $num)[0] ?? '') ? ($mode ? '元':'点'): '点';
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
}

if (!function_exists('is_json')) {
    /**
     * 校验json字符串
     */
    function is_json($data): bool
    {
        if (empty($data)) return false;

        try {
            //校验json格式
            json_decode($data, true);
            return JSON_ERROR_NONE === json_last_error();
        } catch (\Exception $e) {
            return false;
        }
    }
}

if (!function_exists('to_array')) {
    /**
     * json字符串转换数组
     */
    function to_array($data)
    {
        if(is_json($data)) {
            $dataInfo = json_decode($data, true, JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES);
        }
        return $dataInfo ?? $data;
    }
}

if (!function_exists('to_json')) {
    /**
     * json数组转字符串
     */
    function to_json($data)
    {
        if(is_array($data)) {
            $dataInfo = json_encode($data, JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES);
        }
        return $dataInfo ?? $data;
    }
}

if (!function_exists('get_remote_file_headers')) {
    /**
     * 获取远程连接的头信息
     * @param $url
     * @return array
     */
    function get_remote_file_headers($url): array
    {
        $headerArr = [];
        if($url && check_url($url)) {
            $headers = get_headers($url);
            foreach ($headers as $v) {
                if (($arr = explode(':', $v, 2)) && (count($arr) == 2)) {
                    $headerArr[trim($arr[0])] = trim($arr[1]);
                }
            }
        }
        return $headerArr;
    }
}

if (!function_exists('check_url')) {
    /**
     * 检测网址连接是否可用
     **/
    function check_url($url): bool
    {
        if (@fopen($url, 'r')==false) {
            return false;
        }
        return true;
    }
}

if(!function_exists('array_value_max')) {
    /**
     * 统计二维数组值
     * @param array $data
     * @return array
     */
    function array_value_sum(array $data = []): array
    {
        $arr = [];
        foreach ($data as $v) {
            if (is_array($v)) foreach ($v as $ks => $vs) {
                @$arr[$ks] = bcadd($arr[$ks], $vs, 4);
            }
        }
        return $arr;
    }
}

if(!function_exists('array_value_calculate')) {
    /**
     * 两个一维数组计算
     * @param array $arr1
     * @param array $arr2
     * @param string $condition
     * @return array
     */
    function array_value_calculate(array $arr1 = [], array $arr2 = [], string $condition = '+'): array
    {
        foreach ($arr1 as $k => $v) {
            switch ($condition) {
                case '+': case 'add':
                $arr1[$k] = bcadd($v, $arr2[$k] ?? 0, 4);
                break;
                case 'sub': case '-': case 'subtract':
                $arr1[$k] = bcsub($v, $arr2[$k] ?? 0, 4);
                break;
                case 'mul': case '*':  case 'multiply':
                $arr1[$k] = bcmul($v, $arr2[$k] ?? 0, 4);
                break;
                case 'div': case '/': case 'divide':
                $arr1[$k] = bcdiv($v, $arr2[$k] ?? 0, 4);
                break;
                default:
                    $arr1[$k] = bcadd($v, $arr2[$k] ?? 0, 4);
            }
        }
        return $arr1;
    }
}

if(!function_exists('uuid')) {
    /**
     * 生成uuid
     * @return string
     */
    function uuid(): string
    {
        $chars = md5(uniqid(mt_rand(), true));
        return substr ( $chars, 0, 8 ) . '-'
            . substr ( $chars, 8, 4 ) . '-'
            . substr ( $chars, 12, 4 ) . '-'
            . substr ( $chars, 16, 4 ) . '-'
            . substr ( $chars, 20, 12 );
    }
}

if(!function_exists('curl_http_post_json')) {

    function curl_http_post_json($url, $data, $header = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge([
            'Content-Type:application/json',
            'cache-control:no-cache'
        ], $header));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
}