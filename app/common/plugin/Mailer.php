<?php
/**
 * User: Shiroi
 * EMail: 707305003@qq.com
 */

namespace app\common\plugin;

use mailer\lib\Config;
use mailer\lib\Exception;

class Mailer
{
    /** @var string $code 代码 */
    private string $code = "config.email";

    /** @var string $driver 邮件驱动, 支持 smtp|sendmail|mail 三种驱动 */
    protected string $driver = "smtp";

    /** @var string $host SMTP服务器地址 */
    protected string $host = "smtp.qq.com";

    /** @var string $port SMTP服务器端口号,一般为25 */
    protected string $port = "465";

    /** @var string $addr 发件邮箱地址 */
    protected string $addr = "";

    /** @var string $pass 发件邮箱密码 */
    protected string $pass = "";

    /** @var string $name 发件邮箱名称 */
    protected string $name = "";

    /** @var string $content_type 默认文本内容 text/html|text/plain */
    protected string $content_type = 'text/html';

    /** @var string $charset 默认字符集 */
    protected string $charset = 'utf-8';

    /** @var string $security 加密方式 null|ssl|tls, QQ邮箱必须使用ssl */
    protected string $security = 'ssl';

    /** @var string $sendmail 不适用 sendmail 驱动不需要配置 */
    protected string $sendmail = '/usr/sbin/sendmail -bs';

    /** @var bool $debug 开启debug模式会直接抛出异常, 记录邮件发送日志 */
    protected bool $debug = true;

    /** @var string $left_delimiter 模板变量替换左定界符*/
    protected string $left_delimiter = '{';

    /** @var string $right_delimiter 模板变量替换右定界符 */
    protected string $right_delimiter = '}';

    /** @var string $log_driver 日志驱动类, 可选, 如果启用必须实现静态 public static function write($content, $level = 'debug') 方法 */
    protected string $log_driver = '';

    /** @var string $log_path 日志路径, 可选, 不配置日志驱动时启用默认日志驱动, 默认路径是 /public/tp-mailer/log, 要保证该目录有可写权限, 最好配置自己的日志路径 */
    protected string $log_path = 'tp-mailer';

    /** @var string $embed 邮件中嵌入图片元数据标记 */
    protected string $embed = 'embed:';

    /** @var array|bool|mixed|string|null $setting 配置组 */
    protected array $setting  = [];

    /** @var array $config 默认配置组 */
    protected array $config = [];

    /** @var array|string[] $config_field 默认配置字段 */
    protected array $config_field = [
        'driver',
        'host',
        'port',
        'addr',
        'pass',
        'name',
    ];

    /** @var \mailer\lib\Mailer|null $mailer mail生产类 */
    protected ?\mailer\lib\Mailer $mailer = null;

    public function __construct($config = [])
    {
        if (is_array($this->setting = setting($this->code))) foreach ($this->setting as $k => $v) {
            if (property_exists(self::class,$k)) {
                if(in_array($k,$this->config_field)) {
                    $this->config[$k] = $this->{$k} = $v;
                }
            }
        }
        $this->config = [
            'driver'          => $config['driver'] ?? $this->driver,
            'host'            => $config['host'] ?? $this->host,
            'port'            => $config['port'] ?? $this->port,
            'addr'            => $config['addr'] ?? $this->addr,
            'pass'            => $config['pass'] ?? $this->pass,
            'name'            => $config['name'] ?? $this->name,
            'content_type'    => $config['content_type'] ?? $this->content_type,
            'charset'         => $config['charset'] ?? $this->charset,
            'security'        => $config['security'] ?? $this->security,
            'sendmail'        => $config['sendmail'] ?? $this->sendmail,
            'debug'           => $config['debug'] ?? $this->debug,
            'left_delimiter'  => $config['left_delimiter'] ?? $this->left_delimiter,
            'right_delimiter' => $config['right_delimiter'] ?? $this->right_delimiter,
            'log_driver'      => $config['log_driver'] ?? $this->log_driver,
            'log_path'        => $config['log_path'] ?? public_path($this->log_path),
            'embed'           => $config['embed'] ?? $this->embed,
        ];

        Config::init($this->config);
        $this->mailer = \mailer\lib\Mailer::instance();
    }

    /**
     * 发送邮箱
     * @param string $toMail 收到的邮箱
     * @param string $title 收到的标题
     * @return Mailer
     */
    public function generate(string $toMail, string $title = ''): self
    {
        $this->mailer = $this->mailer
            ->to($toMail)
            ->subject($title);
        return $this;
    }

    /**
     * 发送内容
     * @param string $content
     * @param array $embed
     * @return Mailer
     */
    public function setContent(string $content, array $embed = []): self
    {
        $this->mailer = $this->mailer->html($content, $embed);
        return $this;
    }

    /**
     * 发送内容（一行文本）
     * @param string $content
     * @return $this
     */
    public function setLine(string $content): self
    {
        $this->mailer = $this->mailer->line($content);
        return $this;
    }

    /**
     * 发送
     * @return bool|int
     * @throws Exception
     */
    public function send()
    {
        return $this->mailer->send();
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
    public function getDriver(): string
    {
        return $this->driver;
    }

    /**
     * @param string $driver
     * @return Mailer
     */
    public function setDriver(string $driver): self
    {
        $this->driver = $driver;
        return $this;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @param string $host
     * @return Mailer
     */
    public function setHost(string $host): self
    {
        $this->host = $host;
        return $this;
    }

    /**
     * @return string
     */
    public function getPort(): string
    {
        return $this->port;
    }

    /**
     * @param string $port
     * @return Mailer
     */
    public function setPort(string $port): self
    {
        $this->port = $port;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddr(): string
    {
        return $this->addr;
    }

    /**
     * @param string $addr
     * @return Mailer
     */
    public function setAddr(string $addr): self
    {
        $this->addr = $addr;
        return $this;
    }

    /**
     * @return string
     */
    public function getPass(): string
    {
        return $this->pass;
    }

    /**
     * @param string $pass
     * @return Mailer
     */
    public function setPass(string $pass): self
    {
        $this->pass = $pass;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Mailer
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getContentType(): string
    {
        return $this->content_type;
    }

    /**
     * @param string $content_type
     * @return Mailer
     */
    public function setContentType(string $content_type): self
    {
        $this->content_type = $content_type;
        return $this;
    }

    /**
     * @return string
     */
    public function getCharset(): string
    {
        return $this->charset;
    }

    /**
     * @param string $charset
     * @return Mailer
     */
    public function setCharset(string $charset): self
    {
        $this->charset = $charset;
        return $this;
    }

    /**
     * @return string
     */
    public function getSecurity(): string
    {
        return $this->security;
    }

    /**
     * @param string $security
     * @return Mailer
     */
    public function setSecurity(string $security): self
    {
        $this->security = $security;
        return $this;
    }

    /**
     * @return string
     */
    public function getSendmail(): string
    {
        return $this->sendmail;
    }

    /**
     * @param string $sendmail
     * @return Mailer
     */
    public function setSendmail(string $sendmail): self
    {
        $this->sendmail = $sendmail;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDebug(): bool
    {
        return $this->debug;
    }

    /**
     * @param bool $debug
     * @return Mailer
     */
    public function setDebug(bool $debug): self
    {
        $this->debug = $debug;
        return $this;
    }

    /**
     * @return string
     */
    public function getLeftDelimiter(): string
    {
        return $this->left_delimiter;
    }

    /**
     * @param string $left_delimiter
     * @return Mailer
     */
    public function setLeftDelimiter(string $left_delimiter): self
    {
        $this->left_delimiter = $left_delimiter;
        return $this;
    }

    /**
     * @return string
     */
    public function getRightDelimiter(): string
    {
        return $this->right_delimiter;
    }

    /**
     * @param string $right_delimiter
     * @return Mailer
     */
    public function setRightDelimiter(string $right_delimiter): self
    {
        $this->right_delimiter = $right_delimiter;
        return $this;
    }

    /**
     * @return string
     */
    public function getLogDriver(): string
    {
        return $this->log_driver;
    }

    /**
     * @param string $log_driver
     * @return Mailer
     */
    public function setLogDriver(string $log_driver): self
    {
        $this->log_driver = $log_driver;
        return $this;
    }

    /**
     * @return string
     */
    public function getLogPath(): string
    {
        return $this->log_path;
    }

    /**
     * @param string $log_path
     * @return Mailer
     */
    public function setLogPath(string $log_path): self
    {
        $this->log_path = $log_path;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmbed(): string
    {
        return $this->embed;
    }

    /**
     * @param string $embed
     * @return Mailer
     */
    public function setEmbed(string $embed): self
    {
        $this->embed = $embed;
        return $this;
    }

    /**
     * @return array|bool|mixed|string|null
     */
    public function getSetting()
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

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * @param array $config
     * @return Mailer
     */
    public function setConfig(array $config): self
    {
        $this->config = $config;
        return $this;
    }
}