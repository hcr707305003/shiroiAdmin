<?php
/**
 * 前台登录退出
 */

namespace app\index\controller;

use app\index\exception\IndexServiceException;
use app\common\validate\UserValidate;
use app\index\service\AuthService;
use think\captcha\facade\Captcha;
use think\Response;
use think\response\Json;
use think\response\Redirect;
use think\Request;
use Exception;
use util\geetest\GeeTest;

class AuthController extends IndexBaseController
{
    protected array $loginExcept=[
        'index/auth/login',
        'index/auth/register',
        'index/auth/captcha',
        'index/auth/geetest',
    ];

    /**
     * 登录
     * @param Request $request
     * @param AuthService $service
     * @param UserValidate $validate
     * @return string|Response|Redirect
     * @throws Exception
     */
    public function login(Request $request, AuthService $service, UserValidate $validate)
    {
        $login_config = setting('index.login');
        //登录逻辑
        if($request->isPost()){
            $param = $request->param();

            try {
                // 验证码形式，0为不验证，1为图形验证码，2为极验
                $captcha = (int)$login_config['captcha'];

                if (($captcha === 1) && !captcha_check($param['captcha'])) {
                    return index_error('验证码错误');
                }

                $check = $validate->scene('index_login')->check($param);
                if (!$check) {
                    return index_error($validate->getError());
                }

                $user = $service->login($param['username'], $param['password']);
                self::authLogin($user,(bool)($param['remember']??false));
                return index_success('登录成功','index/index',$user);
            } catch (IndexServiceException $e) {
                return  index_error($e->getMessage());
            }
        }

        $this->assign([
            'login_config' => $login_config,
            'geetest_id'   => $login_config['geetest_id'] ?? '',
        ]);

        return $this->fetch();
    }

    /**
     * 注册
     * @param Request $request
     * @param AuthService $service
     * @param UserValidate $validate
     * @return string|Response|Redirect
     * @throws Exception
     */
    public function register(Request $request, AuthService $service, UserValidate $validate)
    {
        $login_config = setting('index.login');
        //登录逻辑
        if($request->isPost()){
            $param = $request->param();

            try {
                // 验证码形式，0为不验证，1为图形验证码，2为极验
                $captcha = (int)$login_config['captcha'];

                if (($captcha === 1) && !captcha_check($param['captcha'])) {
                    return index_error('验证码错误');
                }

                $check = $validate->scene('index_register')->check($param);
                if (!$check) {
                    return index_error($validate->getError());
                }

                $user = $service->register($param['username'], $param['password']);
                self::authLogin($user,(bool)($param['remember']??false));
                return index_success('登录成功','index/index',$user);
            } catch (IndexServiceException $e) {
                return index_error($e->getMessage());
            }
        }

        $this->assign([
            'login_config' => $login_config,
            'geetest_id'   => $login_config['geetest_id'] ?? '',
        ]);

        return $this->fetch();
    }

    /**
     * 图形验证码
     * @return Response
     */
    public function captcha(): Response
    {
        return Captcha::create();
    }

    /**
     * 极验初始化
     * @param Request $request
     * @return Redirect|Response
     */
    public function geetest(Request $request)
    {
        $config  = setting('index.login');
        $geeTest = new GeeTest($config['geetest_id'], $config['geetest_key']);

        $ip = $request->ip();
        $ug = $request->header('user-agent');
        $data = array(
            'gt_uid'      => md5($ip . $ug),
            'client_type' => 'web',
            'ip_address'  => $ip,
        );

        try {
            $status = $geeTest->preProcess($data);
        } catch (Exception $e) {
            $status = 0;
        }

        session('gt_server', $status);
        session('gt_uid', $data['gt_uid']);

        return index_success((string)$status, URL_CURRENT, $geeTest->getResponse());
    }

    /**
     * 退出
     * @return Redirect
     */
    public function logout(): Redirect
    {
        self::authLogout();
        return redirect(url('index/auth/login'));
    }
}