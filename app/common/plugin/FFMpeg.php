<?php

namespace app\common\plugin;

use app\common\traits\PluginTrait;

/**
 * FFMPEG操作类
 * @tip 如果使用容器化会导致性能不佳(请谨慎使用)
 * User: Shiroi
 * EMail: 707305003@qq.com
 */
class Ffmpeg
{
    protected string $source = '';

    protected string $path = 'public/uploads';

    use PluginTrait;

    protected \FFMpeg\FFMpeg $ffmpeg;

    public function __construct($source = '')
    {
        //设置资源
        $this->source = $source;
        //实例化ffmpeg
        $this->ffmpeg = \FFMpeg\FFMpeg::create();
        //设置路径
        $this->path = root_path($this->path);
    }

    /**
     * 获取视频中第几秒的图片
     * @param int $seconds (单位：秒)
     * @param string|null $saveName (保存的名称)
     * @param string $format (保存的文件名后缀)
     * @return string|null
     */
    public function getImageFormSeconds(int $seconds = 0, ?string $saveName = null, string $format = "jpg"): ?string
    {
        if ($this->sourceExist()) {
            //保存的路径
            $path = $this->createPathIfNotExists($this->getDatePath());
            //打开资源
            $video = $this->ffmpeg->open($this->source);
            $video
                ->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds($seconds))
                ->save($resultName = $path . ($saveName ?: uniqid()) . '.' . $format);
            return $resultName;
        }
        return null;
    }

    /**
     * 在视频中设置水印
     * @param $watermark
     * @param string|null $saveName
     * @param array $option = [
     *    'overlay' => '(main_w-overlay_w-10)/2:(main_h-overlay_h-10)/2', //居中
     *    'alpha' => '0.5' //透明度50%
     * ]
     * @return bool|string
     */
    public function setWatermarkToVideo($watermark, ?string $saveName = null, array $option = [
        'overlay' => '(main_w-overlay_w-10)/2:(main_h-overlay_h-10)/2',
        'alpha' => '0.5'
    ])
    {
        if($this->sourceExist()) {
            //获取ffmpeg路径
            $ffmpeg_path = $this->ffmpeg->getFFMpegDriver()->getProcessBuilderFactory()->getBinary();
            //保存的路径
            $path = $this->createPathIfNotExists($this->getDatePath());
            //保存的名称
            $resultName = $path . ($saveName ?: uniqid()) . '.mp4';
            //执行命令
            exec("{$ffmpeg_path} -y -i {$this->source} -i {$watermark} -filter_complex \"[1:v]lut=a=val*".($option['alpha'] ?? '0.5')."[watermark];[v][watermark]overlay=".($option['overlay'] ?? '10:10')."\" -c:a copy {$resultName}");
            return !file_exists($resultName) ?: $resultName;
        }
        return false;
    }

    /**
     * 手动设置ffmpeg配合相关 (默认获取环境变量，需手动设置则可以使用以下案例)
     * @param array $config
     * @return Ffmpeg
     * @example
     * [
     *    'ffmpeg.binaries'  => '/usr/bin/ffmpeg',
     *    'ffprobe.binaries' => '/usr/bin/ffprobe',
     *    'timeout'          => 3600, // The timeout for the underlying process
     *    'ffmpeg.threads'   => 12,   // The number of threads that FFMpeg should use
     * ];
     */
    public function setConfig(array $config = []): Ffmpeg
    {
        $this->ffmpeg = \FFMpeg\FFMpeg::create($config);
        return $this;
    }

    public function getFfmpeg(): \FFMpeg\FFMpeg
    {
        return $this->ffmpeg;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function setSource(string $source): self
    {
        $this->source = $source;
        return $this;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;
        return $this;
    }

    protected function createPathIfNotExists($path)
    {
        if(!file_exists($path)) createDir($path);
        return $path;
    }

    protected function sourceExist(): bool
    {
        return $this->source && file_exists($this->source);
    }
}