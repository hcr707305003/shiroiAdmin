<?php
/**
 * User: Shiroi
 * EMail: 707305003@qq.com
 */

namespace app\common\plugin;

use EasyWeChat\Factory;

class WechatOfficialAccounts
{
//配置组
    protected array $setting  = [];

    //系统code
    protected string $code = "wechat.wechat_official_accounts";

    //app_id
    protected string $app_id;

    //appsecret
    protected string $secret;

    //response_type
    protected string $response_type = 'array';

    //token
    protected string $token;

    //aes_key
    protected string $aes_key;

    //默认配置组
    protected array $config = [];

    //默认配置字段
    protected array $config_field = [
        'app_id',
        'secret',
        'response_type',
        'token',
        'aes_key'
    ];

    /**
     * 初始化配置信息
     */
    public function __construct() {
        if (is_array($this->setting = setting($this->code))) foreach ($this->setting as $k => $v) {
            if (property_exists(self::class,$k)) {
                if(in_array($k,$this->config_field)) {
                    $this->config[$k] = $this->{$k} = $v;
                }
            }
        }
    }

    /**
     * 构造工厂
     */
    public function create(array $config = []): \EasyWeChat\OfficialAccount\Application
    {
        return Factory::officialAccount($config?:$this->config);
    }

    /**
     * 构造支付
     */
    public function createPay(): \EasyWeChat\Payment\Application
    {
        return (new WechatPayment)->setAppId($this->app_id)->create();
    }

    /**
     * @return array
     */
    public function getSetting(): array
    {
        return $this->setting;
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * @return array|string[]
     */
    public function getConfigField(): array
    {
        return $this->config_field;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getAppId(): string
    {
        return $this->app_id;
    }

    /**
     * @param string $app_id
     * @return WechatOfficialAccounts
     */
    public function setAppId(string $app_id): self
    {
        $this->app_id = $this->config['app_id'] = $app_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getSecret(): string
    {
        return $this->secret;
    }

    /**
     * @param string $secret
     * @return WechatOfficialAccounts
     */
    public function setSecret(string $secret): self
    {
        $this->secret = $this->config['secret'] = $secret;
        return $this;
    }

    /**
     * @return string
     */
    public function getResponseType(): string
    {
        return $this->response_type;
    }

    /**
     * @param string $response_type
     * @return WechatOfficialAccounts
     */
    public function setResponseType(string $response_type): self
    {
        $this->response_type = $this->config['response_type'] = $response_type;
        return $this;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     * @return WechatOfficialAccounts
     */
    public function setToken(string $token): self
    {
        $this->token = $this->config['token'] = $token;
        return $this;
    }

    /**
     * @return string
     */
    public function getAesKey(): string
    {
        return $this->aes_key;
    }

    /**
     * @param string $aes_key
     * @return WechatOfficialAccounts
     */
    public function setAesKey(string $aes_key): self
    {
        $this->aes_key = $this->config['aes_key'] = $aes_key;
        return $this;
    }
}