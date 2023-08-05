<?php

use think\response\Json;

if (!function_exists('common_success')) {
    /**
     * 操作成功
     * @param mixed $data
     * @param string $msg
     * @param int $code
     * @param bool $isExit
     * @return Json
     */
    function common_success(array $data = [], string $msg = 'success', int $code = 200, bool $isExit = false): Json
    {
        return common_result($msg, $data, $code, $isExit);
    }
}

if (!function_exists('common_error')) {
    /**
     * 操作失败
     * @param string $msg
     * @param mixed $data
     * @param int $code
     * @param bool $isExit
     * @return Json|void
     */
    function common_error(string $msg = 'fail', $data = [], int $code = 500, bool $isExit = false): Json
    {
        return common_result($msg, $data, $code, $isExit);
    }
}

if (!function_exists('common_result')) {
    /**
     * 返回json结果
     * @param string $msg
     * @param mixed $data
     * @param int $code
     * @param bool $isExit (是否直接终止)
     * @return Json|void
     */
    function common_result(string $msg = 'fail', $data = [], int $code = 500, bool $isExit = false): Json
    {
        if (is_array($data) && empty($data)) {
            $data = (object)$data;
        }
        $header = [];
        // http code是否同步业务code
        $http_code = config('api.response.http_code_sync') ? $code : 200;

        $json = [
            'code' => $code,
            'msg'  => $msg,
            'data' => $data,
        ];
        if($isExit) {
            echo json_encode($json);exit();
        } else {
            return json($json, $http_code, $header);
        }
    }
}