<?php
/**
 * User: Shiroi
 * EMail: 707305003@qq.com
 */

namespace app\common\plugin;

use EasyWeChat\Factory;

class WechatPayment
{
    //配置组
    protected array $setting  = [];

    //系统code
    protected string $code = "wechat.wechat_payment";

    //app_id
    protected string $app_id;

    //mch_id
    protected string $mch_id;

    //key(API 密钥)
    protected string $key;

    //cert_path
    protected string $cert_path;

    //key_path
    protected string $key_path;

    //notify_url(回调地址)
    protected string $notify_url;

    //默认配置组
    protected array $config = [];

    //默认支付配置字段
    protected array $config_field = [
        'app_id',
        'mch_id',
        'key',
        'cert_path',
        'key_path',
        'notify_url'
    ];

    /**
     * 初始化配置信息
     */
    public function __construct() {
        if (is_array($this->setting = setting($this->code))) foreach ($this->setting as $k => $v) {
            if (property_exists(self::class,$k)) {
                //证书需要补齐路径
                if($k == 'cert_path' || $k == 'key_path') {
                    $v = public_path() . $v;
                }
                //回调地址需要补齐
                if($k == 'notify_url') {
                    $v = request()->domain() . $v;
                }

                if(in_array($k,$this->config_field)) {
                    $this->config[$k] = $this->{$k} = $v;
                }
            }
        }
    }

    /**
     * 构造工厂
     */
    public function create(array $config = []): \EasyWeChat\Payment\Application
    {
        return Factory::payment($config?:$this->config);
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
     * @return WechatPayment
     */
    public function setAppId(string $app_id): self
    {
        $this->app_id = $this->config['app_id'] = $app_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getMchId(): string
    {
        return $this->mch_id;
    }

    /**
     * @param string $mch_id
     * @return WechatPayment
     */
    public function setMchId(string $mch_id): self
    {
        $this->mch_id = $this->config['mch_id'] = $mch_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     * @return WechatPayment
     */
    public function setKey(string $key): self
    {
        $this->key = $this->config['key'] = $key;
        return $this;
    }

    /**
     * @return string
     */
    public function getCertPath(): string
    {
        return $this->cert_path;
    }

    /**
     * @param string $cert_path
     * @return WechatPayment
     */
    public function setCertPath(string $cert_path): self
    {
        $this->cert_path = $this->config['cert_path'] = $cert_path;
        return $this;
    }

    /**
     * @return string
     */
    public function getKeyPath(): string
    {
        return $this->key_path;
    }

    /**
     * @param string $key_path
     * @return WechatPayment
     */
    public function setKeyPath(string $key_path): self
    {
        $this->key_path = $this->config['key_path'] = $key_path;
        return $this;
    }

    /**
     * @return string
     */
    public function getNotifyUrl(): string
    {
        return $this->notify_url;
    }

    /**
     * @param string $notify_url
     * @return WechatPayment
     */
    public function setNotifyUrl(string $notify_url): self
    {
        $this->notify_url = $this->config['notify_url'] = $notify_url;
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
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
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
}