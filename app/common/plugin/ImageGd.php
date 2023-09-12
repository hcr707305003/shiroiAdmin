<?php
/**
 * User: Shiroi
 * EMail: 707305003@qq.com
 */

namespace app\common\plugin;

use app\common\traits\PluginTrait;
use Exception;

class ImageGd
{
    /** @var string 文本顶部居中 FONT_TOP_CENTER */
    const FONT_TOP_CENTER = 'TOP_CENTER';

    /** @var string 文本底部居中 FONT_BOTTOM_CENTER */
    const FONT_BOTTOM_CENTER = 'BOTTOM_CENTER';

    /** @var string 文本水平垂直居中 FONT_CENTER */
    const FONT_CENTER = 'CENTER';

    /** @var string 文本顶部居左 FONT_TOP_LEFT */
    const FONT_TOP_LEFT = 'TOP_LEFT';

    /** @var string 文本底部居左 FONT_BOTTOM_LEFT */
    const FONT_BOTTOM_LEFT = 'BOTTOM_LEFT';

    /** @var string FONT_LEFT 文本居左 */
    const FONT_LEFT = 'left';

    /** @var string FONT_TOP_RIGHT 文本底部居右 */
    const FONT_TOP_RIGHT = 'TOP_RIGHT';

    /** @var string FONT_BOTTOM_RIGHT 文本底部居右 */
    const FONT_BOTTOM_RIGHT = 'BOTTOM_RIGHT';

    /** @var string FONT_RIGHT 文本居右 */
    const FONT_RIGHT = 'RIGHT';

    //背景图
    protected $bg_image = "";

    //图片大小
    protected int $bg_width = 400;
    protected int $bg_height = 300;

    //图片类型
    protected string $bg_type = 'png';

    protected string $bg_header_type = 'image/png';

    //默认文字大小
    protected int $default_font_size = 24;

    //设置字体库
    protected string $fontFile = 'public/static/common/font/SimHei.ttf';

    //已使用高
    protected int $be_use_height = 0;
    //已使用宽
    protected int $be_use_width = 0;

    //缓存
    protected array $cache = [];

    //过滤数组内容字段
    protected array $filter_content_list_key = [
        'data',
        'content'
    ];

    protected string $path = 'public/uploads';

    use PluginTrait;

    /**
     * @param string|null $bg 设置图片背景
     * @param array $option 背景的参数 ['width' => 400, 'height' => 350 ]
     */
    public function __construct(?string $bg = null, array $option = [])
    {
        //设置保存的路径
        $this->path = root_path($this->path);
        //初始化字体库
        $this->fontFile = root_path() . $this->fontFile;
        //设置图片尺寸
        $this->initScale($option);
        //设置底图
        if($bg) {
            //获取背景图的大小
            $bg_content = file_get_contents($bg);
            $this->bg_image = imagecreatefromstring($bg_content);
            $info = getimagesizefromstring($bg_content);
            $this->bg_width = $option['width'] ?? imagesx($this->bg_image);
            $this->bg_height = $option['height'] ?? imagesy($this->bg_image);
            $this->bg_type = image_type_to_extension($info[2], false);
            $this->bg_header_type = $info['mime'];
            $this->bg_image = imagecreatefromstring(file_get_contents($bg));
        } else {
            //没有图片为背景则设置透明背景图
            $this->bg_image = imagecreatetruecolor($this->bg_width, $this->bg_height);
            imagesavealpha($this->bg_image, true);
            imagefill(
                $this->bg_image,
                0,
                0,
                imagecolorallocatealpha($this->bg_image, 0, 0, 0, 127)
            );
        }
        $this->bg_image = imagescale($this->bg_image, $this->bg_width, $this->bg_height);
    }

    /**
     * 输出图像流
     * @return void
     */
    public function show()
    {
        // 输出图像流到浏览器
        header("Content-Type: {$this->bg_header_type}");
        ("image{$this->bg_type}")($this->bg_image);
        // 释放内存
        imagedestroy($this->bg_image);
        //处理tp debug问题
        die();
    }

    /**
     * 保存
     * @param string|null $filename
     * @return void
     */
    public function save(?string $filename = null)
    {
        ("image{$this->bg_type}")($this->bg_image, $this->createPathIfNotExists($this->getDatePath()) . ($filename ?: uniqid()) . '.' . $this->bg_type);
        // 释放内存
        imagedestroy($this->bg_image);
    }

    public function resource(): string
    {
        // 将图片转换为 base64 编码的字符串
        ob_start();
        ("image{$this->bg_type}")($this->bg_image);
        $image_data = ob_get_contents();
        ob_end_clean();
        // 释放内存
        imagedestroy($this->bg_image);
        return base64_encode($image_data);
    }

    /**
     * 追加内容
     * @param string|array $content
     * @param array $option
     * @param string $type (类型: image:图片 text:文本)
     * @example 所有参数
     *  $array = [
     *    'opacity' => 50, //透明度
     *  ];
     * @return ImageGD
     */
    public function appendContent($content , array $option = [], string $type = "text"): ImageGD
    {
        $is_many = false;
        $contentList = [];
        if(is_array($content)) {
            //只能在列表外实现多个同行操作
            $is_many = (($option['display'] ?? '') == 'inline_block');
            foreach ($content as $k => $v) {
                $contentList[] = $this->drawContentParam(array_merge(compact('type'), $option, $v), !$k);
            }
        } else {
            $contentList[] = $this->drawContentParam(array_merge(compact('content', 'type'), $option));
        }
        $this->calculateDirection($contentList, $is_many);

        return $this;
    }

    /**
     * 计算方向
     * @return void
     */
    private function calculateDirection($contentList, bool $is_many = false)
    {
        if($is_many && $contentList) {
            $c_width = imagesx($this->bg_image);
            $r_width = 0;

            foreach ($contentList as $v) {
                $c_width -= $v['width'];
                $r_width += $v['width'];
            }

            //计算第一个内容的位置
            switch ($contentList[0]['direction']) {
                case 'center':
                    $width = $contentList[0]['x'] = $c_width / 2;
                    break;
                case 'right':
                    $width = imagesx($this->bg_image) - $r_width;
                    break;
                default:
                    $width = 0;
            }
            $height = $contentList[0]['y'];

            foreach ($contentList as $v) {
                if($v) {
                    if($v['type'] == 'text') {
                        $v['y'] = $height + $v['height'];
                        $v['x'] = $width;
                    } else {
                        $v['x'] = $width;
                        $v['y'] = $height;
                    }
                    $this->drawContent($v);
                    $width += $v['width'];
                }
            }
        } else {
            foreach ($contentList as $v) $this->drawContent($v);
        }
    }

    /**
     * 追加数组内容
     * @param array $contentList
     * @return ImageGD
     */
    public function appendContentList(array $contentList = []): ImageGd
    {
        foreach ($contentList['data'] as $data) {
            $this->appendContent($data['content'], $this->initializerContentListParam(array_merge($contentList, $data)));
        }
        return $this;
    }

    /**
     * 设置尺寸
     * @param array $option
     * @return ImageGD
     */
    public function setScale(array $option = []): ImageGD
    {
        $this->initScale($option);
        // 调整底图尺寸
        $this->bg_image = imagescale($this->bg_image, $option['width'] ?? $this->bg_width, $option['height'] ?? $this->bg_height);
        return $this;
    }

    public function getFontFile(): string
    {
        return $this->fontFile;
    }

    public function setFontFile(string $fontFile): self
    {
        $this->fontFile = $fontFile;
        return $this;
    }

    /**
     * 绘制内容
     * @param $param
     * @return void
     */
    private function drawContent($param)
    {
        if($param['type'] == 'image') {
            imagecopymerge($this->bg_image, $param['target_image'], $param['x'], $param['y'], 0, 0, $param['width'], $param['height'], $param['opacity'] ?? 100);
//            imagecopy($this->bg_image, $param['target_image'], $param['x'], $param['y'], 0, 0, $param['width'], $param['height']);
        }
        if($param['type'] == 'text') {
            imagettftext($this->bg_image, $param['size'], 0, $param['x'], $param['y'], imagecolorallocate($this->bg_image, $param['red'], $param['green'], $param['blue']), $this->fontFile, $param['content']);
        }
    }

    /**
     * 生成绘制内容参数
     * @param $content
     * @param bool $is_use
     * @return array|null
     */
    private function drawContentParam($content, bool $is_use = true): ?array
    {
        switch ($content['type']) {
            case 'image':
                $param = $this->writeImageParam($content['content'], $content);
                break;
            case 'text':
            default:
                $param = $this->writeTextParam($content['content'], $content);
        }
        //追加padding
        if ($is_use) $this->be_use_height += $content['padding_bottom'] ?? 0;
        //返回数据
        return $param;
    }

    /**
     * 写入文字参数
     * @param $text
     * @param $option
     * @return array|null
     */
    private function writeTextParam($text, $option): array
    {
        //默认rgb为白色
        list($red, $green, $blue) = $this->fontRgb($option['color'] ?? [255,255,255]);
        //计算文本
        $textPosition = $this->calculateTextPoint($text, $option);
        return  [
            'type' => 'text',
            'content' => $text,
            'size' => $option['size'] ?? $this->default_font_size,
            'x' => $textPosition['x'],
            'y' => $textPosition['y'],
            'width' => $textPosition['width'],
            'height' => $textPosition['height'],
            'red' => $red,
            'green' => $green,
            'blue' => $blue,
            'direction' => $textPosition['direction']
        ];
    }

    /**
     * 写入图片参数
     * @param $image_path
     * @param $option
     * @return array|null
     */
    private function writeImageParam($image_path, $option): ?array
    {
        // 加载要添加的图片
        if($image_path && ($image = $this->loadImage($image_path))) {
            //计算图片位置
            $imagePosition = $this->calculateTextPoint($image_path, $option);
            //图片缩放
            $target_image = $this->cache[] = imagecreatetruecolor($imagePosition['width'], $imagePosition['height']); //1.创建画布
            $color = imagecolorallocate($target_image,255,255,255); //2.上色
            imagecolortransparent($target_image,$color); //3.设置透明色
            imagefill($target_image,0,0,$color);//4.填充透明色
            imagecopyresampled($target_image, $image, 0, 0, 0, 0, $imagePosition['width'], $imagePosition['height'], imagesx($image), imagesy($image));
            return array_merge($option, [
                'type' => 'image',
                'content' => $image_path,
                'target_image' => $target_image,
                'x' => $imagePosition['x'],
                'y' => $imagePosition['y'],
                'width' => $imagePosition['width'],
                'height' => $imagePosition['height'],
                'direction' => $imagePosition['direction'],
            ]);
        }
        return null;
    }

    /**
     * 加载图片资源
     * @param $image_path
     * @return false|resource|null
     */
    private function loadImage($image_path)
    {
        try {
            if($image_path) {
                //获取图片信息
                $image_info = getimagesize($image_path);
                // 判断图片类型并加载
                switch ($image_info[2]) {
                    case IMAGETYPE_JPEG:
                        $image = imagecreatefromjpeg($image_path);
                        break;
                    case IMAGETYPE_PNG:
                        $image = imagecreatefrompng($image_path);
                        break;
                    case IMAGETYPE_GIF:
                        $image = imagecreatefromgif($image_path);
                        break;
                }
            }

        } catch (Exception $exception) {
            return null;
        }
        return $image ?? null;
    }

    /**
     * 计算文字位置 (默认居左)
     * @param string $content
     * @param array $option
     * @return array['x' => xxx, 'y' => yyy, 'width' => www, 'height' => hhh ]
     */
    private function calculateTextPoint(string $content, array $option = []): array
    {
        //字体的尺寸
        $text_size = $option['size'] ?? $this->default_font_size;
        if($option['type'] == 'image') {
            // 获取本地图片的尺寸
            list($content_width, $content_height) = getimagesize($content);
            //计算百分比
            $bgMinSize = min($this->bg_height, $this->bg_width);
            //图片宽度
            $content_width = $option['width'] ?? $content_width;
            //图片高度
            $content_height = $option['height'] ?? $content_height;
            //判断是否百分比
            $isPerWidth = $this->both_field_exists($content_width, '%', 2);
            if($isPerWidth['bool']) {
                $content_width = $bgMinSize * $isPerWidth['cut_content'] / 100;
            }
            $isPerHeight = $this->both_field_exists($content_height, '%', 2);
            if($isPerHeight['bool']) {
                $content_height = $bgMinSize * $isPerWidth['cut_content'] / 100;
            }
            $text_y = $this->be_use_height = $this->be_use_height + ($option['padding_top'] ?? 0);
            //行数追加
            if(($option['display'] ?? '') != 'inline_block') $this->be_use_height += $content_height;
        } else {
            //计算文本自身的宽高
            $text_box = imagettfbbox($text_size, 0, $this->fontFile, $content);
            //文本宽度
            $content_width = $text_box[2] - $text_box[0];
            //文本高度
            $content_height = $text_box[3] - $text_box[5];
            $text_y = $this->be_use_height = $this->be_use_height + $content_height + ($option['padding_top'] ?? 0);
        }
        //设置默认x,y坐标
        $text_x =  0;
        //计算位置
        switch ($option['position'] ?? 'default') {
            case ImageGD::FONT_TOP_CENTER:
                $direction = 'center';
                $text_x =  (imagesx($this->bg_image) - $content_width) / 2;
                break;
            case ImageGD::FONT_BOTTOM_CENTER:
                $direction = 'center';
                $text_x =  (imagesx($this->bg_image) - $content_width) / 2;
                $text_y = imagesy($this->bg_image) - ($content_height / 2);
                break;
            case ImageGD::FONT_CENTER:
                $direction = 'center';
                $text_x =  (imagesx($this->bg_image) - $content_width) / 2;
                $text_y = (imagesy($this->bg_image) - $content_height + 50) / 2;
                break;
            case ImageGD::FONT_BOTTOM_LEFT:
                $direction = 'left';
                $text_y = imagesy($this->bg_image) - ($content_height / 2);
                break;
            case ImageGD::FONT_LEFT:
                $direction = 'left';
                $text_y = (imagesy($this->bg_image) - $content_height + 50) / 2;
                break;
            case ImageGD::FONT_TOP_RIGHT:
                $direction = 'right';
                $text_x =  imagesx($this->bg_image) - $content_width;
                break;
            case ImageGD::FONT_BOTTOM_RIGHT:
                $direction = 'right';
                $text_x =  imagesx($this->bg_image) - $content_width;
                $text_y = imagesy($this->bg_image) - ((50 - $content_height) / 2);
                break;
            case ImageGD::FONT_RIGHT:
                $direction = 'right';
                $text_x =  imagesx($this->bg_image) - $content_width;
                $text_y = (imagesy($this->bg_image) - $content_height + 50) / 2;
                break;
        }
        return ['x' => $text_x + ($option['padding_left'] ?? 0) - ($option['padding_right'] ?? 0), 'y' => $text_y, 'width' => $option['width'] ?? $content_width, 'height' => $option['height'] ?? $content_height, 'direction' => $direction ?? 'left'];
    }

    /**
     * 初始化数组内容参数
     * @param array $contentList
     * @return array
     */
    private function initializerContentListParam(array $contentList): array
    {
        return array_filter($contentList, function ($val, $key) {
            return !in_array($key, $this->filter_content_list_key);
        }, ARRAY_FILTER_USE_BOTH);
    }

    /**
     * 设置图片尺寸
     * @return void
     */
    private function initScale(array $option = [])
    {
        //设置宽
        $this->bg_width = $option['width'] ?? $this->bg_width;
        //设置高
        $this->bg_height = $option['height'] ?? $this->bg_height;
    }

    /**
     * 设置文字rgb
     * @param int[]|string|array $color
     * @return array
     */
    private function fontRgb($color): array
    {
        if(is_string($color)) {
            if($rgb1 = $this->hex2rgb($color)) $rgb = $rgb1;
        }
        if(is_array($color)) $rgb = $color;

        return [
            $rgb[0] ?? 255,
            $rgb[1] ?? 255,
            $rgb[2] ?? 255,
        ];
    }

    /**
     * 将颜色转换成rgb
     * @param $color
     * @return array|false
     */
    private function hex2rgb($color) {
        $hexColor = str_replace('#', '', $color);
        $lens = strlen($hexColor);
        if ($lens != 3 && $lens != 6) {
            return false;
        }
        $newColor = '';
        if ($lens == 3) {
            for ($i = 0; $i < $lens; $i++) {
                $newColor .= $hexColor[$i] . $hexColor[$i];
            }
        } else {
            $newColor = $hexColor;
        }
        $hex = str_split($newColor, 2);
        $rgb = [];
        foreach ($hex as $vls) {
            $rgb[] = hexdec($vls);
        }
        return $rgb;
    }

    /**
     * 判断文本是否在(头部|尾部|当前文本)存在
     * @param string $string (文本内容)
     * @param string $subString （是否存在该字段）
     * @param int $type (0=>不指定头部或者尾部, 1=>头部, 2=>尾部)
     * @return array
     */
    private function both_field_exists(string $string, string $subString, int $type = 0): array
    {
        $bool = false;
        $cut_content = $string;
        if ($type == 0) {
            $bool = mb_strpos($string,$subString);
            if($bool) {
                $cut_content = str_replace($subString,'',$string);
            }
        } elseif ($type == 1) {
            $bool = mb_substr($string, 0, mb_strlen($subString)) === $subString;
            if($bool) {
                $cut_content = mb_substr($string,mb_strlen($subString),(mb_strlen($string)-mb_strlen($subString)));
            }
        } elseif ($type == 2) {
            $bool = mb_substr($string, mb_strpos($string, $subString)) === $subString;
            if($bool) {
                $cut_content = mb_substr($string,0,mb_strpos($string, $subString));
            }
        }
        return compact('bool','cut_content');
    }

    private function isRemoteFile($file): bool
    {
        if (substr($file, 0, 7) === 'http://' || substr($file, 0, 8) === 'https://') {
            return true;
        }
        return false;
    }

    private function createPathIfNotExists($path)
    {
        if(!file_exists($path)) createDir($path);
        return $path;
    }
}