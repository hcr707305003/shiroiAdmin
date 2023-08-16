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
     * 验证注册和登录
     */
    protected function verify(string $scene, $config, Request $request, AuthService $service, UserValidate $validate)
    {
        $param = $request->param();
        //是否开启了登录token验证
        if(($config['login_config']['token'] ?? 0) == 1) {
            //验证登录token
            if(($param['__token__'] ?? '') !== session('__token__')) {
                return index_error('请刷新重试', URL_RELOAD);
            }
        }
        try {
            // 验证码形式，0为不验证，1为图形验证码，2为极验
            $captcha = (int)$config['login_config']['captcha'];

            //图形验证码
            if (($captcha === 1) && !captcha_check($param['captcha'] ?? '')) {
                return index_error('验证码错误');
            }

            $validate->scene("index_{$scene}")->failException(true)->check($param);

            // 检查单设备登录
            $service->checkLoginLimit();

            $user = $service->{$scene}($param['username'], $param['password']);
            self::authLogin($user,(bool)($param['remember']??false));
            return index_success('登录成功','index/index',$user);
        } catch (IndexServiceException $e) {
            // 记录错误次数
            $service->setLoginLimit();
            return index_error($e->getMessage());
        }
    }

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
        $geetest_config = setting('config.geetest');
        //登录逻辑
        if($request->isPost()){
            return $this->verify('login',compact('login_config','geetest_config'),$request,$service,$validate);
        }

        $this->assign([
            'login_config' => array_merge($login_config,$geetest_config),
            'geetest_id'   => $geetest_config['geetest_id'] ?? '',
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
        $geetest_config = setting('config.geetest');

        if ($request->isPost()) {
            return $this->verify('register',compact('login_config','geetest_config'),$request,$service,$validate);
        }

        $this->assign([
            'login_config' => array_merge($login_config,$geetest_config),
            'geetest_id'   => $geetest_config['geetest_id'] ?? '',
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
        $geetest_config  = setting('config.geetest');
        $geeTest = new GeeTest($geetest_config['geetest_id'], $geetest_config['geetest_key']);

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