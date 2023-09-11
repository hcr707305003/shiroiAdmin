<?php

namespace app\common\plugin;

use app\common\traits\PluginTrait;
use Spatie\PdfToImage\Exceptions\PageDoesNotExist;
use Spatie\PdfToImage\Exceptions\PdfDoesNotExist;

/**
 * 图片操作类
 * User: Shiroi
 * EMail: 707305003@qq.com
 */
class Image
{
    //设置pdf文件
    protected string $pdf = "";

    //默认图片格式
    protected string $image_format = "jpg";

    //设置图片名(会生成page_1.jpg的文件规范)
    protected string $default_image_name = "page_";

    //设置图片质量(1-100)
    protected int $image_quality = 100;

    //设置目录（根目录下）
    protected string $path = "public/uploads";

    use PluginTrait;

    public function __construct()
    {
        $this->path = root_path($this->path);
    }

    /**
     * pdf转image
     * @param int $page (页数,不传默认拿所有)
     * @param bool $isEach (是否遍历 (从 1 到 $page 之间))
     * @example :
     *  1.安装ghostscript => https://ghostscript.com/releases/gsdnld.html
     *  2.安装imagemagick => http://www.imagemagick.org/script/download.php
     *  3.安装php-imagick扩展(php7.4) => https://pecl.php.net/package/imagick/3.6.0/windows
     *  4.安装composer扩展
     *     一. composer require ext-imagick
     *     二. composer require spatie/pdf-to-image
     * @throws PageDoesNotExist|PdfDoesNotExist
     */
    public function pdfToImage(int $page = -1, bool $isEach = true): array
    {
        //存储的数据
        $data = [];
        //设置日期目录
        $path = $this->getDatePath() . uniqid();
        //实例化对象之前加反斜杠，注意pdf文件的路径
        $pdf = new \Spatie\PdfToImage\Pdf($this->pdf);
        //创建目录
        createDir($path);
        //遍历页数
        foreach (range($isEach? 1: $page, ($page > -1)? $page: $pdf->getNumberOfPages()) as $pageNumber) {
            //图片路径
            $image = "{$path}/{$this->default_image_name}{$pageNumber}.{$this->image_format}";
            //写入
            ($pdf
                ->setCompressionQuality($this->image_quality) //设置压缩从0到100的质量
                ->setPage($pageNumber)
                ->saveImage($image)) && ($data[] = $image);
        }
        return $data;
    }

    /**
     * @return string
     */
    public function getPdf(): string
    {
        return $this->pdf;
    }

    /**
     * @param string $pdf
     * @return Image
     */
    public function setPdf(string $pdf): self
    {
        $this->pdf = $pdf;
        return $this;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return Image
     */
    public function setPath(string $path): self
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return int
     */
    public function getImageQuality(): int
    {
        return $this->image_quality;
    }

    /**
     * @param int $image_quality
     * @return Image
     */
    public function setImageQuality(int $image_quality): self
    {
        $this->image_quality = $image_quality;
        return $this;
    }
}