<?php
/**
 * User: Shiroi
 * EMail: 707305003@qq.com
 */

namespace app\common\controller;

use EasyWeChat\Kernel\Exceptions\BadRequestException;
use EasyWeChat\Kernel\Exceptions\InvalidArgumentException;
use EasyWeChat\Kernel\Exceptions\InvalidConfigException;
use ReflectionException;
use Symfony\Component\HttpFoundation\Response;
use think\facade\Cache;
use think\response\Json;
use app\common\plugin\{WechatPayment, WechatMiniProgram, WechatOfficialAccounts, WechatWebApplication};
use app\common\traits\WechatTrait;
use GuzzleHttp\Exception\GuzzleException;
use Overtrue\Socialite\Exceptions\AuthorizeFailedException;

class TestController extends CommonBaseController
{
    use WechatTrait;

    public function __construct()
    {
        //初始化微信工厂
        $this->initWechat();
    }

    public function testCloud()
    {
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
     * 测试微信 (需初始化微信工厂)
     * @return void
     */
    public function testWechat()
    {
        //小程序
        dump($this->wechat_mini_program);
        dump($this->wechat_mini_program_pay);

        //公众号
        dump($this->wechat_official_accounts);
        dump($this->wechat_official_accounts_pay);

        //网站应用
        dump($this->wechat_web_application);
        dump($this->wechat_web_application_pay);
    }

    /**
     * 测试微信小程序（包含应用工厂和对应应用的支付工厂）
     * @return void
     */
    public function testWechatMiniProgram()
    {
        //实例化
        $wechatMiniProgram = new WechatMiniProgram;
        //微信小程序工厂
        $wechat_mini_program = $wechatMiniProgram->create();
        //微信小程序支付
        $wechat_mini_program_pay = $wechatMiniProgram->createPay();
        dd($wechat_mini_program,$wechat_mini_program_pay);
    }

    /**
     * 测试微信公众号（包含应用工厂和对应应用的支付工厂）
     * @return void
     */
    public function testWechatOfficialAccounts()
    {
        //实例化
        $wechatOfficialAccounts = new WechatOfficialAccounts;
        //微信公众号工厂
        $wechat_official_accounts = $wechatOfficialAccounts->create();
        //微信公众号支付
        $wechat_official_accounts_pay = $wechatOfficialAccounts->createPay();
        dd($wechat_official_accounts,$wechat_official_accounts_pay);
    }

    /**
     * 测试微信网站应用（包含应用工厂和对应应用的支付工厂）
     * @return void
     */
    public function testWechatWebApplication()
    {
        //实例化
        $wechatWebApplication = new WechatWebApplication;
        //微信网站应用工厂
        $wechat_web_application = $wechatWebApplication->create();
        //微信网站应用支付
        $wechat_web_application_pay = $wechatWebApplication->createPay();
        dd($wechat_web_application,$wechat_web_application_pay);
    }

    /**
     * 扫码识别`网站应用`并`授权`登录
     */
    public function scanLogin1($id = "qrCode", $href = '')
    {
        //定义参数
        list($appid,$secret,$response_type,$scope,$redirect_uri) = array_values($this->wechatWebApplication->getAllConfig());
        $state = uuid();
        //todo 测试用回调
        $redirect_uri = request()->domain() . "/common/test/scanLoginRedirect";
        $config = json_encode(compact('id','appid','scope','redirect_uri','href','state'));
        echo <<<EOF
        <html><head><meta charset="UTF-8">
        <meta name="viewport"content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge"><title>商家后台登录</title></head>
        <body>
        <div id="qrCode"></div>
        <div id="{$id}"></div>
        <script src="https://res.wx.qq.com/connect/zh_CN/htmledition/js/wxLogin.js"></script>
        <script>
        window.onload=function(){
            var obj = new WxLogin({$config});
        }
        </script>
EOF;
    }

    /**
     * 扫码识别`网站应用`并`授权`登录的回调
     * @throws GuzzleException|AuthorizeFailedException
     */
    public function scanLoginRedirect()
    {
        $user = $this->wechat_web_application->oauth->userFromCode(input('code'));
        dd($user);
    }

    /**
     * 扫码关注公众号
     * @return void
     */
    public function scanLogin2()
    {
        // 生成随机数
        $unique = md5(password_hash(uniqid(true), PASSWORD_BCRYPT));
        // 二维码有效期600秒
        $expiry = 600;
        $qrcode = $this->wechat_official_accounts->qrcode;
        $data = $qrcode->temporary($unique, $expiry);
        // 这个值保存下来，待会查询扫码状态可以用到
        $ticket = $data['ticket'];
        // 二维码的地址
        $url = $qrcode->url($ticket);
        //请求地址
        $redirect_uri = request()->domain() . "/common/test/isScanLogin";
        var_dump($data);
        echo <<<EOF
        <html>
            <head>
                <meta charset="UTF-8">
                <meta name="viewport"content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
                <meta http-equiv="X-UA-Compatible" content="ie=edge">
                <title>商家后台登录</title>
            </head>
            <body>
                <img src="{$url}">
            </body>
            <script src="https://cdn.bootcdn.net/ajax/libs/jquery/3.6.4/jquery.js"></script>
            <script>
                var isScan = setInterval(function () {
                    $.post("{$redirect_uri}",{
                        'ticket': "{$ticket}"
                    },function(data){
                        console.log(data);
                        //扫码成功则删除定时器
                        if(data.data.scan) {
                            clearInterval(isScan);
                        }
                    });
                }, 2000);
            </script>
        </html>
EOF;
    }

    /**
     * 这里模拟公众号入口
     * @return Response
     * @throws BadRequestException
     * @throws InvalidArgumentException
     * @throws InvalidConfigException
     * @throws ReflectionException
     */
    public function OfficialAccountsServer(): Response
    {
        $server = $this->wechat_official_accounts->server;
        //返回验证消息给微信服务器
        $server->push(function ($message) {
            if($message['MsgType'] == 'event'){
                switch (strtolower($message['Event'])){
                    case 'scan':
                    case 'subscribe':
                        //关注或者扫码则写入到缓存中
                        cache($message['Ticket'], $message['FromUserName'], 600);
                        break;
                    default:
                        break;
                }
            }
        });
        $response = $server->serve();
        // 将响应输出
        $response->send();
        return $response;
    }

    /**
     * 是否扫码登录
     */
    public function isScanLogin(): Json
    {
        if($ticket = input('ticket')) {
            if($openid = cache($ticket)) {
                cache($ticket, null); //设置为空
                //业务
                return common_success([
                    'scan' => true,
                    'openid' => $openid
                ], '扫码关注公众号成功~');
            }
        }
        return common_success(['scan' => false]);
    }

    /**
     * 测试微信商户支付
     * @return void
     */
    public function testWechatMerchant()
    {
        //实例化
        $wechatMiniProgram = new WechatPayment;
        //微信网站应用工厂
        $wechat_mini_program = $wechatMiniProgram->setAppId('aaa')->create();
        dd($wechat_mini_program);
    }
}