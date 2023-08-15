<?php
/**
 *
 * @author shiroi <707305003@qq.com>
 */

declare (strict_types=1);


namespace app\index\service;


use app\common\model\User;
use app\index\exception\IndexServiceException;
use think\facade\Cache;
use think\Model;

class AuthService extends IndexBaseService
{
    protected string $limitKeyPrefix = 'index_login_count_';

    protected User $model;

    public function __construct()
    {
        $this->model = new User();
    }

    /**
     * 用户登录
     * @param $username
     * @param $password
     * @return User|array|Model
     * @throws IndexServiceException
     */
    public function login($username,$password)
    {

        $user = $this->model->where('username' ,'=', $username)->findOrEmpty();

        if ($user->isEmpty()) {
            throw new IndexServiceException('用户不存在');
        }

        if (!password_verify($password, base64_decode($user->password))) {
            throw new IndexServiceException('密码错误');
        }

        if ((int)$user->status !== 1) {
            throw new IndexServiceException('用户被冻结');
        }
        return $user;
    }

    /**
     * 用户注册
     * @param $username
     * @param $password
     * @return User|Model
     * @throws IndexServiceException
     */
    public function register($username,$password)
    {
        //判断用户名是否被占用
        $user = $this->model->where('username' ,'=', $username)->findOrEmpty();

        if($user->isExists()) {
            throw new IndexServiceException('用户已存在，请用其他用户名注册~');
        }

        return $this->model->create([
            'username' => $username,
            'nickname' => $username,
            'password' => $password,
        ]);
    }

    /**
     * 检测登录限制
     * @throws IndexServiceException
     */
    public function checkLoginLimit(): bool
    {
        $loginConfig = setting('index.login');
        if ($loginConfig['login_limit'] ?? 0) {
            // 最大错误次数
            $max_count        = $loginConfig['login_max_count'] ?? 5;
            $login_limit_hour = $loginConfig['login_limit_hour'] ?? 2;
            // 缓存key
            $cache_key  = $this->limitKeyPrefix . md5(request()->ip());
            $have_count = cache($cache_key);
            if ($have_count >= $max_count) {
                throw new IndexServiceException('连续' . $max_count . '次登录失败，请' . $login_limit_hour . '小时后再试');
            }
            return true;
        }
        return true;
    }

    /**
     * 设置登录限制
     * @return bool
     */
    public function setLoginLimit(): bool
    {
        $loginConfig = setting('index.login');
        if ($loginConfig['login_limit'] ?? 0) {
            // 最大错误次数
            $login_limit_hour = $loginConfig['login_limit_hour'] ?? 2;
            // 缓存key
            $cache_key = $this->limitKeyPrefix . md5(request()->ip());
            if (Cache::has($cache_key)) {
                Cache::inc($cache_key);
                return true;
            }
            Cache::set($cache_key, 1, $login_limit_hour * 3600);
        }
        return true;
    }
}