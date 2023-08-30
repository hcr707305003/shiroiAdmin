<?php

namespace app\common\plugin;

/**
 * 抖音操作类
 */
class TikTok
{
    protected string $code = "bytedance.tiktok";

    protected string $url = "https://developer.toutiao.com/";

    protected array $headers = [];

    protected string $client_secret = '';

    protected string $client_key = '';

    protected string $appid = '';

    protected string $appsecret = '';

    //默认配置组
    protected array $config = [];

    public function __construct() {
        $setting = setting($this->code);
        if($setting && is_array($setting)) foreach ($setting as $property => $value) {
            if (property_exists(self::class,$property)) {
                $this->config[$property] = $this->{$property} = $value;
            }
        }
    }

    /**
     * todo 只实现了这些接口，可自行拓展
     * @var array|string[]
     */
    protected array $path = [
        //获取用户授权调用凭证
        'access_token' => 'https://open.douyin.com/oauth/access_token/',
        //刷新用户授权调用凭证
        'refresh_token' => 'https://open.douyin.com/oauth/refresh_token/',
        //刷新授权调用凭证
        'renew_refresh_token' => 'https://open.douyin.com/oauth/renew_refresh_token/',
        //getAccessToken
        'get_access_token' => 'api/apps/v2/token',
        //code2Session
        'code2Session' => 'api/apps/v2/jscode2session'
    ];

    /**
     * code2Session
     * @param array $data = [
     *   'code' => $code,
     *   'anonymous_code' => $anonymous_code
     * ]
     * @notice code 和 anonymous_code 至少要有一个
     * @url https://developer.open-douyin.com/docs/resource/zh-CN/mini-app/develop/server/log-in/code-2-session
     * @return bool|string
     */
    public function code2Session(array $data = [])
    {
        return $this->postJson($this->path['code2Session'], [
            'appid' => $this->appid,
            'secret' => $this->appsecret,
            'anonymous_code' => $data['anonymous_code'] ?? '',
            'code' => $data['code'] ?? '',
        ]);
    }

    /**
     * getAccessToken
     * @url https://developer.open-douyin.com/docs/resource/zh-CN/mini-app/develop/server/interface-request-credential/get-access-token
     * @return bool|string
     */
    public function getAccessToken()
    {
        return $this->httpPost($this->path['get_access_token'], [
            'appid' => $this->appid,
            'appsecret' => $this->appsecret,
            'grant_type' => 'client_credential'
        ]);
    }

    /**
     * 获取用户授权调用凭证
     * @param $code (code，小程序)
     * @url https://developer.open-douyin.com/docs/resource/zh-CN/mini-app/develop/server/interface-request-credential/user-authorization/get-user-access-token
     * @return bool|string
     */
    public function accessToken($code) {
        return $this->postJson($this->path['access_token'], [
            'code' => $code,
            'client_secret' => $this->client_secret,
            'grant_type' => 'authorization_code',
            'client_key' => $this->client_key,
        ]);
    }

    /**
     * 刷新用户授权调用凭证
     * @param $refresh_token (填写通过 access_token 获取到的 refresh_token 参数)
     * @url https://developer.open-douyin.com/docs/resource/zh-CN/mini-app/develop/server/interface-request-credential/user-authorization/refresh-user-access-token
     * @return bool|string
     */
    public function refreshToken($refresh_token) {
        return $this->httpPost($this->path['refresh_token'], [
            'client_key' => $this->client_key,
            'grant_type' => 'refresh_token',
            'refresh_token' => $refresh_token
        ], [
            'Content-Type' => 'application/x-www-form-urlencoded'
        ]);
    }

    /**
     * 刷新授权调用凭证
     * @param $refresh_token (填写通过/oauth/access_token/ 获取到的 refresh_token 参数)
     * @url https://developer.open-douyin.com/docs/resource/zh-CN/mini-app/develop/server/interface-request-credential/user-authorization/refresh-user-token
     * @return bool|string
     */
    public function renewRefreshToken($refresh_token) {
        return $this->httpPost($this->path['renew_refresh_token'], [
            'client_key' => $this->client_key,
            'refresh_token' => $refresh_token
        ], [
            'Content-Type' => 'application/x-www-form-urlencoded'
        ]);
    }

    /**
     * 解密
     * @param $encryptedData
     * @param $sessionKey
     * @param $iv
     * @return false|mixed|string
     */
    public function decrypt($encryptedData, $sessionKey, $iv) {
        $decipher = openssl_decrypt( base64_decode($encryptedData), 'aes-128-cbc', base64_decode($sessionKey), OPENSSL_RAW_DATA, base64_decode($iv) );
        return is_json($decipher) ? to_array($decipher): $decipher;
    }

    public function httpGet($path, array $params = [], array $headers = []) {
        return $this->request($this->isLinkOrPath($path) ? $path: $this->url . $path, 'get', $params, $headers);
    }

    public function httpPost($path, array $params = [], array $headers = []) {
        return $this->request($this->isLinkOrPath($path) ? $path: $this->url . $path, 'post', http_build_query($params), $headers);
    }

    public function postFormData($path, array $params = [], array $headers = []) {
        return $this->request($this->isLinkOrPath($path) ? $path: $this->url . $path, 'post', $params, $headers);
    }

    public function postJson($path, array $params = [], array $headers = []) {
        return $this->request($this->isLinkOrPath($path) ? $path: $this->url . $path, 'post', json_encode($params), array_merge($headers, ['Content-Type' => 'application/json']));
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;
        return $this;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function setHeader($key, $value): self {
        $this->headers[$key] = $value;
        return $this;
    }

    private function request(string $url, string $method = 'GET', $params = [], array $headers = []) {
        if((strtolower($method) == 'get') && !empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if(strtolower($method) == 'post') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->buildHeaders($headers));
        $response = curl_exec($ch);
        curl_close($ch);

        return is_json($response) ? to_array($response): $response;
    }

    private function buildHeaders(array $additionalHeaders = []): array
    {
        $formattedHeaders = [];
        foreach (array_merge($this->headers, $additionalHeaders) as $key => $value) {
            $formattedHeaders[] = $key . ': ' . $value;
        }
        return $formattedHeaders;
    }

    private function isLinkOrPath($url): bool
    {
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            $parsedUrl = parse_url($url);
            if (isset($parsedUrl['scheme']) && isset($parsedUrl['host'])) {
                return true;
            }
        }
        return false;
    }
}