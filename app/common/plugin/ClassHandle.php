<?php

namespace app\common\plugin;

/**
 * 动态创建类
 * User: Shiroi
 * EMail: 707305003@qq.com
 */
class ClassHandle
{
    protected string $path = 'app\controller';

    //默认命名空间
    protected string $namespace = 'app\admin\controller';

    //方法数组
    protected array $methods = [];

    //属性数组
    protected array $property = [];

    protected array $config = [
        'method_comment_spaces' => 4, //方法注解空格数
        'spaces' => "\x20"
    ];

    protected array $setContent = [
        'return' => 'self',
        'content' => '$this->{property} = ${property};
        return $this;'
    ];

    protected array $getContent = [
        'argument' => false, //是否保留参数
        'return' => '{type}',
        'content' => 'return $this->{property};'
    ];

    public function __construct()
    {
        $this->path = root_path($this->path);
    }

    /**
     * 创建类
     * @param string $className 类名
     * @param string $nameSpace 命名空间
     * @return void
     */
    public function create(string $className, string $nameSpace)
    {
        //设置的命名空间
        $this->namespace = str_replace(['/'],'\\',$nameSpace);
        //设置的目录
        $this->path = root_path(str_replace(['\\', ' '],'/',$nameSpace));
        //遍历所有方法
        $methods = implode("\n\n", $this->methods);
        //遍历所有属性
        $property = implode("\n\n", $this->property);
        // 动态创建类文件的内容
        $classContent = <<<php
<?php
namespace {$this->namespace};

/**
 * User: Shiroi
 * EMail: 707305003@qq.com
 * CreateTime: {$this->getCreateTime()}
 */
class {$className} {
{$property}\n\n
{$methods}
}
php;
        // 动态创建类文件
        if (!file_exists($fileName = $this->path . $className . '.php')) {
            file_put_contents($fileName, $classContent);
        }
    }

    /**
     * 创建方法
     * @param string|null $methodName 方法名
     * @param array $params 参数 $params = [
     *    'name' => 'string', //默认string
     *    'age' => 'int' //默认int
     * ]
     * @return $this
     */
    public function setMethod(string $methodName = null, array $params = [], array $content = []): self
    {
        //内容
        $methodContent = $content['content'] ?? '';
        //返回值(默认无返回值)
        $returnValue = $content['return'] ?? 'void';
        //请求的参数
        $param = ($content['argument'] ?? true) ? $this->generateParams($params): '';
        //注解生成
        $comment = $this->generateMethodComment($params, $content['argument'] ?? true);
        //设置是否是私有方法还是公有方法
        $access = $content['access'] ?? 'public';
        $this->methods[$methodName] = <<<php
{$comment}
    {$access} function {$methodName}($param): {$returnValue} {
        {$methodContent}
    }
php;
        return $this;
    }

    /**
     * 创建属性
     * @param string|null $propertyName 属性名
     * @param string $propertyType 属性类型
     * @param string|bool|array|object|int|float $propertyValue 属性值
     * @param array $getSetName get set方法名
     * @return $this
     */
    public function setProperty(string $propertyName, string $propertyType = 'string', $propertyValue = null, array $option = [], array $getSetName = []): self
    {
        //设置的属性名私有还是公有
        $access = $option['access'] ?? 'public';
        //默认值
        $defaultPropertyValue = $this->generateParam($propertyType, ['default' => $propertyValue, 'type' => $propertyType]);
        //通过get set方法获取
        foreach ($getSetName as $key => $value) {
            //加载内容
            if(!is_int($key) && is_array($value)) {
                $loadContent = $value;
            } else {
                $loadContent = property_exists($this, "{$value}Content") ? $this->{"{$value}Content"}: [];
            }
            //内容内处理静态变量的
            foreach ($loadContent as $k => $v) {
                if(is_string($v))
                    $loadContent[$k] = str_replace(['{type}', '{property}'], [$propertyType, $propertyName], $v);
            }
            //设置方法
            $this->setMethod((is_int($key) ? $value: $key) . ucfirst($propertyName), [
                $propertyName => [
                    'type' => $propertyType,
                    'default' => $propertyValue,
                    'is_default' => false
                ]
            ], $loadContent);
        }
        //属性
        $this->property[$propertyName] = <<<php
    /**
     * @var {$propertyType} 
     */
    {$access} {$propertyType} \${$propertyName} = {$defaultPropertyValue};
php;
        return $this;
    }

    private function generateParams(array $params): string
    {
        foreach ($params as $key => $value) {
            //类型
            $type = (is_array($value) && isset($value['type'])) ? $value['type']: (is_array($value) ? 'string': $value);
            //是否默认值
            $is_default = (is_array($value) && isset($value['is_default'])) ? $value['is_default']: true;
            //设置参数
            $params[$key] = $type . ' $' . $key . ($is_default ? (' = ' . $this->generateParam($type, $value)): '');
        }
        return implode(', ', $params);
    }

    private function generateMethodComment(array $params, bool $argument = true): string
    {
        //设置注解的空格数
        $space = '';
        $method_comment_spaces = $this->config['method_comment_spaces'] ?? 4;
        while ($method_comment_spaces--) $space .= $this->config['spaces'] ?? "\x20";
        $comment = "{$space}/**\r";
        $commentParam = "";
        //遍历所有参数
        foreach ($params as $key => $value) {
            //类型
            $type = (is_array($value) && isset($value['type'])) ? $value['type']: (is_array($value) ? 'string': $value);
            //是否默认值
            $is_default = (is_array($value) && isset($value['is_default'])) ? $value['is_default']: true;
            //默认值
            $default = ($is_default ? $this->generateParam($type, $value): '');
            //设置参数
            $commentParam .= "{$space} * @param {$type} \${$key} {$default}\r";
        }
        $comment .= ($argument? $commentParam: '') . "{$space} * @return void\r{$space} */";
        return $comment;
    }

    /**
     * @param $type
     * @param $value
     * @return mixed|string|null
     */
    private function generateParam($type, $value)
    {
        //默认值
        switch ($type) {
            case 'string':
                $default = isset($value['default']) ? "'{$value['default']}'" : "''";
                break;
            case 'int':
                $default = $value['default'] ?? '0';
                break;
            case 'array':
                $default = isset($value['default']) ? $this->arrayToStringDefaultParam($value['default']) : '[]';
                break;
            case 'bool':
                $default = isset($value['default']) ? ($value['default'] ? 'true' : 'false') : 'false';
                break;
            case 'float':
                $default = $value['default'] ?? '0.0';
                break;
            default:
                $default = $value['default'] ?? null;
                break;
        }
        return $default;
    }

    private function arrayToStringDefaultParam(array $params): string
    {
        return str_replace(array("\r", "\n", "\t", " "), "", var_export($params, true));
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

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function setNamespace(string $namespace): self
    {
        $this->namespace = $namespace;
        return $this;
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    public function setConfig(array $config): self
    {
        $this->config = $config;
        return $this;
    }

    public function getSetContent(): array
    {
        return $this->setContent;
    }

    public function setSetContent(array $setContent): self
    {
        $this->setContent = $setContent;
        return $this;
    }

    public function getGetContent(): array
    {
        return $this->getContent;
    }

    public function setGetContent(array $getContent): self
    {
        $this->getContent = $getContent;
        return $this;
    }

    private function getCreateTime()
    {
        return date('Y-m-d H:i:s');
    }
}