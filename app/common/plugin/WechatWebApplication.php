<?php
/**
 * User: Shiroi
 * EMail: 707305003@qq.com
 */

namespace app\common\plugin;

use EasyWeChat\Factory;

class WechatWebApplication
{
    //配置组
    protected array $setting  = [];

    //系统code
    protected string $code = "wechat.wechat_web_application";

    //app_id
    protected string $app_id;

    //secret
    protected string $secret;

    //response_type
    protected string $response_type;

    //scopes
    protected string $scopes;

    //callback(回调地址)
    protected string $callback;

    //默认配置组
    protected array $config = [];

    //默认配置字段
    protected array $config_field = [
        'app_id',
        'secret',
        'response_type',
        'scopes',
        'callback'
    ];

    //全部配置
    protected array $all_config = [];

    public function __construct($code = '')
    {
        if (is_array($this->setting = setting($this->code))) foreach ($this->setting as $k => $v) {
            if (property_exists(self::class,$k)) {
                //回调地址需要补齐
                if($k == 'callback') {
                    $v = request()->domain() . $v;
                }

                if(in_array($k,$this->config_field)) {
                    if(($k == 'scopes') || ($k == 'callback')) {
                        $this->config['oauth'][$k] = $this->{$k} = $v;
                    } else {
                        $this->config[$k] = $this->{$k} = $v;
                    }
                }
                $this->all_config[$k] = $v;
            }
        }
    }

    public function create(array $config = []): \EasyWeChat\OfficialAccount\Application
    {
        return Factory::officialAccount($config ?: $this->config);
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
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * @return array
     */
    public function getAllConfig(): array
    {
        return $this->all_config;
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
     * @return WechatWebApplication
     */
    public function setAppId(string $app_id): self
    {
        $this->app_id = $app_id;
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
     * @return WechatWebApplication
     */
    public function setSecret(string $secret): self
    {
        $this->secret = $secret;
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
     * @return WechatWebApplication
     */
    public function setResponseType(string $response_type): self
    {
        $this->response_type = $response_type;
        return $this;
    }

    /**
     * @return string
     */
    public function getScopes(): string
    {
        return $this->scopes;
    }

    /**
     * @param string $scopes
     * @return WechatWebApplication
     */
    public function setScopes(string $scopes): self
    {
        $this->scopes = $scopes;
        return $this;
    }

    /**
     * @return string
     */
    public function getCallback(): string
    {
        return $this->callback;
    }

    /**
     * @param string $callback
     * @return WechatWebApplication
     */
    public function setCallback(string $callback): self
    {
        $this->callback = $callback;
        return $this;
    }

    /**
     * @return array
     */
    public function getSetting(): array
    {
        return $this->setting;
    }

    /**
     * @return array|string[]
     */
    public function getConfigField(): array
    {
        return $this->config_field;
    }
}