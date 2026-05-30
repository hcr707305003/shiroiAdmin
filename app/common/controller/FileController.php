<?php
/**
 * User: Shiroi
 * EMail: 707305003@qq.com
 */

namespace app\common\controller;

use think\exception\ValidateException;
use think\facade\Filesystem;
use think\file\UploadedFile;
use think\Request;
use think\response\Json;

class FileController extends CommonBaseController
{
    /**
     * 公共文件上传
     *
     * @param Request $request
     * @return Json|void
     */
    public function upload(Request $request)
    {
        if (!$request->isPost()) {
            return common_error('非法访问');
        }

        $files = $request->file();

        if (empty($files)) {
            return common_error('文件未上传');
        }

        $param = $request->param();

        $field = $param['file_field'] ?? 'file';
        $dir   = $param['file_dir'] ?? 'uploads';

        if (!isset($files[$field])) {
            return common_error('上传字段不存在');
        }

        /** @var UploadedFile $file */
        $file = $files[$field];

        $config = config('filesystem.disks.public');

        /**
         * 服务端自动识别文件类型
         * 禁止客户端传入 file_type
         */
        $fileType = get_file_type($file->getOriginalName());

        /**
         * 白名单校验
         */
        $allowTypes = ['image', 'video', 'file'];

        if (!in_array($fileType, $allowTypes, true)) {
            return common_error('不支持的文件类型');
        }

        if (!isset($config['validate'][$fileType])) {
            return common_error('文件校验规则不存在');
        }

        /**
         * 危险扩展名黑名单
         */
        $denyExt = [
            'php',
            'php3',
            'php4',
            'php5',
            'phtml',
            'phar',
            'cgi',
            'pl',
            'py',
            'jsp',
            'jspx',
            'asp',
            'aspx',
            'sh',
            'bash',
            'exe',
            'dll',
            'com',
            'bat',
            'cmd'
        ];

        $ext = strtolower($file->extension());

        if (in_array($ext, $denyExt, true)) {
            return common_error('禁止上传此类型文件');
        }

        try {
            validate([
                $field => $config['validate'][$fileType]
            ])->check([
                $field => $file
            ]);
        } catch (ValidateException $e) {
            return common_error($e->getMessage());
        }

        $name = Filesystem::putFile($dir, $file);

        $url = str_replace(
            '\\',
            '/',
            $config['url'] . '/' . $name
        );

        return common_success([
            'url'  => $url,
            'name' => $file->getOriginalName(),
            'size' => $file->getSize(),
            'type' => $fileType,
            'mime' => $file->getMime(),
        ]);
    }
}
