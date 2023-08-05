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
     * 公共的文件上传
     * @param Request $request
     * @return Json|void
     * @example
     *  - URL : /common/file/upload
     *  - ACTION: POST
     *  - FORM_DATA:
     *     - file(File): xxx.png
     */
    public function upload(Request $request)
    {
        if ($request->isPost()) {
            //判断文件是否上传
            $files = $request->file();
            if(empty($files)) {
                return common_error('文件未上传');
            }
            $param = $request->param();
            $field = $param['file_field'] ?? 'file';
            $dir   = $param['file_dir'] ?? 'uploads';
            /** @var UploadedFile $file */
            $file  = $files[$field];
            // 文件类型，默认图片
            $file_type = $param['file_type'] ?? get_file_type($file->getOriginalName());
            // 上传到本地，可自行修改为oss之类的
            $config = config('filesystem.disks.public');

            try {
                validate([$field => $config['validate'][$file_type]])->check($files);
            } catch (ValidateException $e) {
                return common_error($e->getMessage());
            }
            $name = Filesystem::putFile($dir, $file);

            $url = $config['url'] . '/' . $name;
            $url = str_replace("\\", '/', $url);

            return common_success([
                'url'         => $url,
                'name'        => str_replace("\\", '/', $file->getOriginalName()),
                'size'        => $file->getSize(),
                'type'        => $file_type,
                'mime'        => $file->getMime()
            ]);
        }

        return common_error('非法访问');
    }
}