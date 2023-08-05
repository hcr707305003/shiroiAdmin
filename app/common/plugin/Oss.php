<?php
/**
 * User: Shiroi
 * EMail: 707305003@qq.com
 */

namespace app\common\plugin;

use app\common\model\Setting;
use lishuo\oss\exception\ConfigException;
use lishuo\oss\exception\NonsupportStorageTypeException;
use lishuo\oss\Manager;
use lishuo\oss\response\DeleteResponse;
use lishuo\oss\response\PutResponse;
use lishuo\oss\storage\ICloudStorage;
use lishuo\oss\storage\StorageConfig;
use OSS\Core\OssException;

class Oss
{
    /**
     * 云存储厂商 (默认：阿里云)
     * @var string $type
     * @example
     *  - aliyun  => 阿里云
     *  - tencent => 腾讯云
     *  - qiniu   => 七牛云
     */
    protected string $type = 'aliyun';

    /**
     * 云存储商类型列表
     * @var array TYPE_LIST
     */
    const TYPE_LIST = [
        'aliyun' => 'aliyun_oss', //阿里云
        'tencent' => 'tencent_cos', //腾讯云
        'qiniu' => 'qiniuyun', //七牛云
    ];

    //默认配置字段
    protected array $config_field = [
        'appId',
        'appKey',
        'region'
    ];

    //默认配置组
    protected array $config = [];

    /** @var Oss|null $oss 实例化oss类 */
    protected static ?Oss $oss = null;

    /** @var string $appId 应用id */
    protected string $appId;

    /** @var string $appKey 应用密钥 */
    protected string $appKey;

    /** @var string $region 地区(endpoint) */
    protected string $region;

    /** @var string $bucket */
    protected string $bucket;

    protected ICloudStorage $storage;

    //配置
    protected array $setting  = [];

    /**
     * @throws NonsupportStorageTypeException
     * @throws ConfigException
     * @throws OssException
     * @var string $type
     * @example
     *  - aliyun  => 阿里云
     *  - tencent => 腾讯云
     *  - qiniu   => 七牛云
     */
    public function __construct(string $type = '')
    {
        $this->setting = (new Setting())->where(['code' => self::TYPE_LIST[$type ?: $this->type] ?? ''])->findOrEmpty()->toArray();
        foreach ($this->setting['content'] ?? [] as $param) {
            if(property_exists(self::class,$param['field']) && in_array($param['field'],$this->config_field))
                $this->config[$param['field']] = $this->{$param['field']} = $param['content'];
        }

        //实例化云存储
        $this->storage = Manager::storage($type ?: $this->type)
        ->init(new StorageConfig($this->appId, $this->appKey, $this->region));
    }

    /**
     * 文件列表查询
     * @param int $limit 查询条目数
     * @param string $delimiter 要分隔符分组结果
     * @param string $prefix 指定key前缀查询
     * @param string $marker 标明本次列举文件的起点
     * @return mixed
     * @throws OssException
     */
    public function get(int $limit, string $delimiter = '', string $prefix = '', string $marker = '') {
        return $this->storage->get($limit,$delimiter,$prefix,$marker);
    }

    /**
     * 单文件上传
     * @param string $key 指定唯一的文件key
     * @param string $path 包含扩展名的完整文件路径
     * @return PutResponse
     * @throws OssException|ConfigException
     */
    public function put(string $key, string $path): PutResponse {
        return $this->storage->put($key,$path);
    }

    /**
     * 分块文件上传
     * @param string $key 指定唯一的文件key
     * @param string $path 包含扩展名的完整文件路径
     * @param int|null $partSize 指定块大小
     * @return PutResponse
     * @throws OssException|ConfigException
     */
    public function putPart(string $key, string $path, int $partSize = null): PutResponse {
        return $this->storage->putPart($key,$path,$partSize);
    }

    /**
     * 删除指定key的文件
     * @param array $keys 待删除的key数组
     * @return DeleteResponse
     */
    public function delete(array $keys): DeleteResponse {
        return $this->storage->delete($keys);
    }

    /**
     * @throws NonsupportStorageTypeException
     * @throws ConfigException
     * @throws OssException
     */
    public static function getInstance($type = ''): Oss
    {
        if(self::$oss == null) {
            self::$oss = new Oss($type);
        }
        return self::$oss;
    }

    /**
     * @return string
     */
    public function getBucket(): string
    {
        return $this->bucket;
    }

    /**
     * @param string $bucket
     * @return Oss
     */
    public function setBucket(string $bucket): Oss
    {
        $this->bucket = $bucket;
        $this->storage = $this->storage->bucket($bucket);
        return $this;
    }
}