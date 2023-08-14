<?php

namespace app\common\traits;

use EasyWeChat\Payment\Application as Payment;
use EasyWeChat\MiniProgram\Application as MiniProgram;
use EasyWeChat\OfficialAccount\{
    Application as OfficialAccounts,
    Application as WebApplication,
};
use app\common\plugin\{WechatMiniProgram, WechatOfficialAccounts, WechatWebApplication};

trait WechatTrait
{
    /** @var WechatMiniProgram|null $wechatMiniProgram 微信小程序实例化类 */
    protected ?WechatMiniProgram $wechatMiniProgram = null;

    /** @var MiniProgram|null $wechat_mini_program 微信小程序工厂 */
    protected ?MiniProgram $wechat_mini_program = null;

    /** @var Payment|null $wechat_mini_program_pay 微信小程序支付工厂 */
    protected ?Payment $wechat_mini_program_pay = null;

    /** @var WechatOfficialAccounts|null $wechatOfficialAccounts 微信公众号实例化类 */
    protected ?WechatOfficialAccounts $wechatOfficialAccounts = null;

    /** @var OfficialAccounts|null $wechat_official_accounts 微信公众号工厂 */
    protected ?OfficialAccounts $wechat_official_accounts = null;

    /** @var Payment|null $wechat_official_accounts_pay 微信公众号支付工厂 */
    protected ?Payment $wechat_official_accounts_pay = null;

    /** @var WechatWebApplication|null $wechatWebApplication 微信网站应用实例化类 */
    protected ?WechatWebApplication $wechatWebApplication = null;

    /** @var WebApplication|null $wechat_web_application 微信网站应用工厂 */
    protected ?WebApplication $wechat_web_application = null;

    /** @var Payment|null $wechat_web_application_pay 微信网站应用支付工厂 */
    protected ?Payment $wechat_web_application_pay = null;

    /**
     * 初始化微信（小程序、公众号）
     * @return void
     */
    protected function initWechat() {
        //初始化小程序
        $this->initWechatMiniProgram();
        //初始化公众号
        $this->initWechatOfficialAccounts();
        //初始化网站应用
        $this->initWechatWebApplication();
    }

    /**
     * 初始化微信小程序的工厂
     */
    protected function initWechatMiniProgram()
    {
        //实例化
        $this->wechatMiniProgram = $wechatMiniProgram = new WechatMiniProgram;
        //微信工厂
        $this->wechat_mini_program = $wechatMiniProgram->create();
        //微信小程序支付
        $this->wechat_mini_program_pay = $wechatMiniProgram->createPay();
    }

    /**
     * 初始化微信公众号的工厂
     */
    protected function initWechatOfficialAccounts()
    {
        //实例化
        $this->wechatOfficialAccounts = $wechatOfficialAccounts = new WechatOfficialAccounts;
        //微信公众号工厂
        $this->wechat_official_accounts = $wechatOfficialAccounts->create();
        //微信公众号支付
        $this->wechat_official_accounts_pay = $wechatOfficialAccounts->createPay();
    }

    /**
     * 初始化微信网站应用的工厂
     */
    protected function initWechatWebApplication()
    {
        //实例化
        $this->wechatWebApplication = $wechatWebApplication = new WechatWebApplication;
        //微信公众号工厂
        $this->wechat_web_application = $wechatWebApplication->create();
        //微信公众号支付
        $this->wechat_web_application_pay = $wechatWebApplication->createPay();
    }
}