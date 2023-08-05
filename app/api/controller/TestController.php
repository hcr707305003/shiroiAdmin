<?php
/**
 * 测试控制器
 */

namespace app\api\controller;

use lishuo\oss\Manager;
use lishuo\oss\storage\StorageConfig;
use think\response\Json;
use app\api\service\TestService;
use app\common\validate\TestValidate;
use app\api\exception\ApiServiceException;
use util\jwt\Example;
use util\safe\SafeCookie;

class TestController extends ApiBaseController
{

    protected array $loginExcept= [
        'api/test/index',
        'api/test/oss',
    ];

    public function oss() {
        //上传的文件
        $file = request()->file('file');

        //实例化（默认阿里云）
        // aliyun  => 阿里云
        // tencent => 腾讯云
        // qiniu   => 七牛云
        $oss = new \app\common\plugin\Oss('aliyun');

        //设置bucket
        $oss = $oss->setBucket('mhjzjt-disk');

//        //列表
//        $list_info = $oss->get(10);
//        dump($list_info);

        //上传
        $put_info = $oss->put('shiroi.png', $file->getPathname());
        dump($put_info);

//        //删除
//        $delete_info = $oss->delete(['shiroi.png']);
//        dump($delete_info);
    }

    /**
     * 列表
     * @param TestService $service
     * @return Json
     */
    public function index(TestService $service): Json
    {
        return api_success();
    }

    /**
     * 添加
     *
     * @param TestValidate $validate
     * @param TestService $service
     * @return Json
     */
    public function add(TestValidate $validate, TestService $service): Json
    {
        $check = $validate->scene('api_add')->check($this->param);
        if (!$check) {
            return api_error($validate->getError());
        }

        $result = $service->createData($this->param);

        return $result ? api_success() : api_error();
    }

    /**
     * 详情
     *
     * @param TestValidate $validate
     * @param TestService $service
     * @return Json
     */
    public function info(TestValidate $validate, TestService $service): Json
    {
        $check = $validate->scene('api_info')->check($this->param);
        if (!$check) {
            return api_error($validate->getError());
        }

        try {

            $result = $service->getDataInfo($this->id);
            return api_success([
                'user_level' => $result,
            ]);

        } catch (ApiServiceException $e) {
            return api_error($e->getMessage());
        }
    }

    /**
     * 修改
     * @param TestService $service
     * @param TestValidate $validate
     * @return Json
     */
    public function edit(TestService $service, TestValidate $validate): Json
    {
        $check = $validate->scene('api_edit')->check($this->param);
        if (!$check) {
            return api_error($validate->getError());
        }

        try {
            $service->updateData($this->id, $this->param);
            return api_success();
        } catch (ApiServiceException $e) {
            return api_error($e->getMessage());
        }
    }

    /**
     * 删除
     * @param TestService $service
     * @param TestValidate $validate
     * @return Json
     */
    public function del(TestService $service, TestValidate $validate): Json
    {
        $check = $validate->scene('api_del')->check($this->param);
        if (!$check) {
            return api_error($validate->getError());
        }

        try {
            $service->deleteData($this->id);
            return api_success();
        } catch (ApiServiceException $e) {
            return api_error($e->getMessage());
        }
    }
}
