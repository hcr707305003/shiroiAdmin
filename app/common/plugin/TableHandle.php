<?php
/**
 * User: Shiroi
 * EMail: 707305003@qq.com
 */

namespace app\common\plugin;

use Phinx\Db\Adapter\{AdapterFactory, AdapterInterface, MysqlAdapter};
use DateTime;
use Exception;
use Phinx\Db\Table;
use think\db\{BaseQuery, ConnectionInterface, exception\DbException};
use think\facade\Db;

/**
 * 动态创建表
 */
class TableHandle
{
    /** @var array|string[][] 表单类型 */
    protected array $typeArr = [
        'integer' => [
            'date',
            'time'
        ],
        'string' => [
            'text',
            'checkbox',
            'multiple_select',
            'color_picker'
        ],
        'text' => [
            'textarea',
            'image',
            'file',
            'html',
            'rich_text'
        ],
        'decimal' => [
            'number',
            'score'
        ],
        'boolean' => [
            'select',
            'radio',
            'switch'
        ]
    ];

    /** @var array|string[][] 数据库类型 */
    protected array $sqlTypeArr = [
        'integer' => [
            'int'
        ],
        'string' => [
            'varchar'
        ],
        'text' => [
            'text'
        ],
        'decimal' => [
            'decimal'
        ],
        'boolean' => [
            'tinyint'
        ]
    ];

    /** @var array|string[] 忽略字段（用于更新字段时进行过滤） */
    protected array $ignoreColumns = [
        'id',
        'create_time',
        'update_time',
        'delete_time'
    ];

    /** @var array|array[] 默认字段 */
    protected array $defaultColumns = [
        'create_time' => [
            'type' => 'integer',
            'option' => ['limit' => 10, 'default' => 0, 'comment' => '创建时间']
        ],
        'update_time' => [
            'type' => 'integer',
            'option' => ['limit' => 10, 'default' => 0, 'comment' => '更新时间']
        ],
        'delete_time' => [
            'type' => 'integer',
            'option' => ['limit' => 10, 'default' => 0, 'comment' => '删除时间']
        ]
    ];

    /** @var array|string[] $dateType 时间格式对应的key */
    protected array $dateType = [
        'date' => 'Y-m-d H:i:s',
        'day' => 'Y-m-d',
        'month' => 'Y-m',
        'year' => 'Y'
    ];

    /** @var array|string[] $mysqlDateType mysql时间格式对应key */
    protected array $mysqlDateType = [
        'date' => '%Y-%m-%d %H:%i:%s',
        'day' => '%Y-%m-%d',
        'month' => '%Y-%m',
        'year' => '%Y'
    ];

    /** @var string $createTime 创建时间字段 */
    protected string $createTime = 'create_time';

    /** @var string $updateTime 更新时间字段 */
    protected string $updateTime = 'update_time';

    /** @var string $deleteTime 删除时间字段 */
    protected string $deleteTime = 'delete_time';

    /** @var TableHandle|null 静态调用类 */
    protected static ?TableHandle $tableHandle = null;

    protected ?FormDesign $formDesign = null;

    /** @var BaseQuery 基础表模型 */
    protected BaseQuery $mode;

    /** @var string 需要操作的表 */
    protected string $tableName = '';

    /** @var int 默认表id */
    protected int $tableId = 0;

    /** @var string $primaryKey 主键 */
    protected string $primaryKey = 'id';

    /** @var string 连接库 */
    protected string $connection = 'mysql'; //设置连接库，用于创建基础表

    /**
     * @param string $tableName (表名)
     */
    public function __construct(string $tableName = '')
    {
        if($tableName) {
            //实例化基础表
            $this->mode = $this->dbConfig()->name($tableName);
            //设置表名
            $this->tableName = $tableName;
        }

        $this->formDesign = FormDesign::getInstance();
    }

    /**
     * 静态调用
     * @param $tableName
     * @return TableHandle|null
     */
    public static function getInstance($tableName): ?TableHandle
    {
        if(self::$tableHandle == null){
            self::$tableHandle = new TableHandle($tableName);
        }
        return self::$tableHandle;
    }

    /**
     * 获取时间区间
     * @param string $startTime 开始时间
     * @param string|null $endTime 结束时间 (默认当前天，起效类型:day)
     * @param string $type 类型 （day=>天 mouth=>月 year=>年）
     * @return array
     * @throws Exception
     */
    public function getTimeZones(string $startTime, string $endTime = null, string $type = 'mouth'): array
    {
        // 初始化结果数组
        $timestamps = [];

        // 设置开始时间和结束时间
        $startDateTime = (new DateTime($startTime))->setTime(0, 0);
        $endDateTime = (new DateTime($endTime ?: date('Y-m-d H:i:s')))->setTime(23,59,59);

        // 循环计算每天的开始时间和结束时间的时间戳
        while ($startDateTime <= $endDateTime) {
            $date = $startDateTime->format($this->dateType[$type]);
            //开始时间戳
            $startTimestamp = $startDateTime->setTime(0, 0)->getTimestamp();
            //结束时间戳
            if($type == 'mouth') $startDateTime->modify('last day of this month');
            if($type == 'year') $startDateTime->modify('last day of December');
            $endTimestamp = $startDateTime->setTime(23, 59, 59)->getTimestamp();
            // 添加到结果数组
            $timestamps[$date] = [
                'start' => $startTimestamp,
                'end' => $endTimestamp
            ];

            switch ($type){
                case 'day':
                    $startDateTime->modify('+1 day');
                    break;
                case 'mouth':
                    $startDateTime->modify('first day of next month');
                    break;
                case 'year':
                    $startDateTime->modify('+1 year')->modify('first day of January');
                    break;
            }
        }
        return $timestamps;
    }

    /**
     * 根据表单设计类生成表
     * @param array $formContent
     * @param string $comment 表注释
     * @param true $isUpdateAll 是否更新全字段 (true => 是, false => 否)
     * @return void
     */
    public function generate(array $formContent = [], string $comment = '', bool $isUpdateAll = true)
    {
        $table = (new Table($this->tableName))
            ->setAdapter($this->setAdapter())
            ->setOptions([
                'comment' => $comment ?: $this->tableName,
                'engine' => 'InnoDB',
                'encoding' => 'utf8mb4'
            ]);
        $columns = [];
        $formColumns = [];
        foreach ($formContent as $content) {
            $formColumns[$content['field']] = $content;
            $columns[$content['field']] = [
                'type' => $this->getFieldType($content),
                'option' => array_merge(
                    $this->getOption($content),
                    $this->getOption($content, 'comment'),
                    $this->getOption($content, 'limit')
                )
            ];
        }

        if($this->tableExists()) {
            //获取所有字段
            $existsColumns = $this->tableColumn();
            //删除id主键字段
            foreach ($this->ignoreColumns as $i) {
                if(isset($existsColumns[$i])) unset($existsColumns[$i]);
            }
            //获取差集字段(新增字段)
            foreach ($columns as $field => $column) {
                $method = $isUpdateAll ? 'changeColumn': 'addColumn';
                if($isUpdateAll) {
                    //全字段更新
                    if(!isset($existsColumns[$field])) $method = 'addColumn';
                } else {
                    //只更新不存在的字段
                    if(isset($existsColumns[$field])) {
                        if($column['type'] != $this->getFieldType($existsColumns[$field], $this->sqlTypeArr)) {
                            $method = 'changeColumn';
                        } else {
                            continue;
                        }
                    }
                }
                //更新或新增字段
                $table->{$method}($field, $this->getFieldType($formColumns[$field]), array_merge(
                    $this->getOption($formColumns[$field]),
                    $this->getOption($formColumns[$field], 'comment'),
                    $this->getOption($formColumns[$field], 'limit'),
                    $this->getOption('id', 'after')
                ));
            }
            //获取差集字段(删除字段)
            foreach (array_diff(array_keys($existsColumns), array_keys($columns)) as $field) {
                $table->removeColumn($field);
            }
            $table->update();
        } else {
            foreach (array_merge($columns, $this->defaultColumns) as $field => $column) {
                $table = $table->addColumn($field, $column['type'], $column['option']);
            }
            $table->create();
        }
    }

    /**
     * 保存表单数据
     * @param array $formContent
     * @param array $data 存储的数据
     * @return false|int|string
     * @throws DbException
     */
    public function save(array $formContent = [], array $data = [])
    {
        //设置表单更新时间
        $data[$this->updateTime] = time();
        //设置字段内容
        foreach ($formContent as $content) {
            $data[$content['field']] = $this->getOption($content, 'save');
        }
        if($this->tableExists()) {
            //获取表字段
            $columns = $this->tableColumn();
            //只保存数据库存在的字段
            foreach ($data as $k => $v) {
                if(!isset($columns[$k])) unset($data[$k]);
            }
            if($this->tableId) {
                $info = $this->mode->where($this->primaryKey, $this->tableId)->findOrEmpty();
            }
            //创建
            if(empty($info ?? []) || empty($this->tableId)) {
                //设置表单创建时间
                $data[$this->createTime] = time();
                //新增并返回插入id
                return $this->tableId = $this->mode->insertGetId($data);
            } else { //更新
                //更新数据
                return $this->mode->where($this->primaryKey, $this->tableId)->update($data) ? $this->tableId: 0;
            }
        }
        return false;
    }

    /**
     * 获取选项，分发到不同的表单类型（用于生成数据库结构）
     * @param $option
     * @param string $method
     * @return string|array|array[]
     */
    public function getOption($option, string $method = 'default')
    {
        if(is_array($option)) {
            if(method_exists(self::class,$option['type'])) {
                return $this->{$option['type']}($option, $method);
            }
        }
        return [$method => $option];
    }

    protected function text($content, string $method)
    {
        switch ($method) {
            case 'default':
                return [$method => $content['default'] ?? ''];
            case 'comment':
                return [$method => $content['name'] ?? ''];
            case 'limit':
                return [$method => 255];
            case 'save':
                return $content['default'] ?? '';
        }
        return [];
    }

    protected function color_picker($content, string $method)
    {
        switch ($method) {
            case 'default':
                return [$method => $content['default'] ?? ''];
            case 'comment':
                return [$method => $content['name'] ?? ''];
            case 'limit':
                return [$method => 255];
            case 'save':
                return $content['default'] ?? '';
        }
        return [];
    }

    protected function textarea($content, string $method)
    {
        switch ($method) {
            case 'default':
                return [];
            case 'comment':
                return [$method => $content['name'] ?? ''];
            case 'limit':
                return ['null' => true, $method => MysqlAdapter::TEXT_REGULAR];
            case 'save':
                return $content['default'] ?? '';
        }
        return [];
    }

    protected function number($content, string $method)
    {
        switch ($method) {
            case 'default':
                return [$method => floatval($content['default'] ?? 0)];
            case 'comment':
                return [$method => $content['name'] ?? ''];
            case 'limit':
                return ['precision' => 15, 'scale' => 4];
            case 'save':
                return floatval($content['default'] ?? 0);
        }
        return [];
    }

    protected function score($content, string $method)
    {
        switch ($method) {
            case 'default':
                return [$method => floatval($content['default'] ?? 0)];
            case 'comment':
                return [$method => $content['name'] ?? ''];
            case 'limit':
                return ['precision' => 15, 'scale' => 4];
            case 'save':
                return floatval($content['default'] ?? 0);
        }
        return [];
    }

    protected function select($content, string $method)
    {
        switch ($method) {
            case 'default':
                foreach ($content['default'] ?? [] as $select) {
                    if($select['checked'] ?? false) {
                        return [$method => $select['value']];
                    }
                }
                return [$method => 0];
            case 'comment':
                $comment = [];
                foreach ($content['default'] ?? [] as $select) {
                    $comment[] = "{$select['value']}=>{$select['name']}";
                }
                return [$method => $content['name'] . ':' . implode(',', $comment)];
            case 'limit':
                return ['signed' => false, $method => 1];
            case 'save':
                $value = 0;
                foreach ($content['default'] ?? [] as $select) {
                    if($select['checked'] ?? false) $value = $select['value'];
                }
                return $value;
        }
        return [];
    }

    protected function radio($content, string $method)
    {
        switch ($method) {
            case 'default':
                foreach ($content['default'] ?? [] as $select) {
                    if($select['checked'] ?? false) {
                        return [$method => $select['value']];
                    }
                }
                return [$method => 0];
            case 'comment':
                $comment = [];
                foreach ($content['default'] ?? [] as $select) {
                    $comment[] = "{$select['value']}=>{$select['name']}";
                }
                return [$method => $content['name'] . ':' . implode(',', $comment)];
            case 'limit':
                return ['signed' => false, $method => 1];
            case 'save':
                $value = 0;
                foreach ($content['default'] ?? [] as $select) {
                    if($select['checked'] ?? false) $value = $select['value'];
                }
                return $value;
        }
        return [];
    }

    protected function switch($content, string $method)
    {
        switch ($method) {
            case 'default':
                foreach ($content['default'] ?? [] as $select) {
                    if($select['checked'] ?? false) {
                        return [$method => $select['value']];
                    }
                }
                return [$method => 0];
            case 'comment':
                $comment = [];
                foreach ($content['default'] ?? [] as $select) {
                    $comment[] = "{$select['value']}=>{$select['name']}";
                }
                return [$method => $content['name'] . ':' . implode(',', $comment)];
            case 'limit':
                return ['signed' => false, $method => 1];
            case 'save':
                $value = 0;
                foreach ($content['default'] ?? [] as $select) {
                    if($select['checked'] ?? false) $value = $select['value'];
                }
                return $value;
        }
        return [];
    }

    protected function checkbox($content, string $method)
    {
        switch ($method) {
            case 'default':
                $default = [];
                foreach ($content['default'] ?? [] as $select) {
                    if($select['checked'] ?? false) $default[] = $select['value'];
                }
                return [$method => implode(',', $default)];
            case 'comment':
                $comment = [];
                foreach ($content['default'] ?? [] as $select) {
                    $comment[] = "{$select['value']}=>{$select['name']}";
                }
                return [$method => $content['name'] . ':' . implode(',', $comment)];
            case 'limit':
                return [$method => 255];
            case 'save':
                $value = [];
                foreach ($content['default'] ?? [] as $select) {
                    if($select['checked'] ?? false) $value[] = $select['value'];
                }
                return implode(',', $value);
        }
        return [];
    }

    protected function multiple_select($content, string $method)
    {
        switch ($method) {
            case 'default':
                $default = [];
                foreach ($content['default'] ?? [] as $select) {
                    if($select['checked'] ?? false) $default[] = $select['value'];
                }
                return [$method => implode(',', $default)];
            case 'comment':
                $comment = [];
                foreach ($content['default'] ?? [] as $select) {
                    $comment[] = "{$select['value']}=>{$select['name']}";
                }
                return [$method => $content['name'] . ':' . implode(',', $comment)];
            case 'limit':
                return [$method => 255];
            case 'save':
                $value = [];
                foreach ($content['default'] ?? [] as $select) {
                    if($select['checked'] ?? false) $value[] = $select['value'];
                }
                return implode(',', $value);
        }
        return [];
    }

    protected function date($content, string $method)
    {
        switch ($method) {
            case 'default':
                return [$method => ($content['default'] ?? 0) ? strtotime($content['default']): 0];
            case 'comment':
                return [$method => $content['name'] ?? ''];
            case 'limit':
                return ['signed' => false, $method => 10];
            case 'save':
                return ($content['default'] ?? 0) ? strtotime($content['default']): 0;
        }
        return [];
    }

    protected function time($content, string $method)
    {
        switch ($method) {
            case 'default':
            return [$method => ($content['default'] ?? 0) ? strtotime($content['default']): 0];
            case 'comment':
                return [$method => $content['name'] ?? ''];
            case 'limit':
                return ['signed' => false, $method => 10];
            case 'save':
                return ($content['default'] ?? 0) ? strtotime($content['default']): 0;
        }
        return [];
    }

    protected function image($content, string $method)
    {
        switch ($method) {
            case 'default':
                return [];
            case 'comment':
                return [$method => $content['name'] ?? ''];
            case 'limit':
                return ['null' => true, $method => MysqlAdapter::TEXT_REGULAR];
            case 'save':
                return json_encode($content['default']);
        }
        return [];
    }

    protected function file($content, string $method)
    {
        switch ($method) {
            case 'default':
                return [];
            case 'comment':
                return [$method => $content['name'] ?? ''];
            case 'limit':
                return ['null' => true, $method => MysqlAdapter::TEXT_REGULAR];
            case 'save':
                return json_encode($content['default']);
        }
        return [];
    }

    protected function html($content, string $method)
    {
        switch ($method) {
            case 'default':
                return [];
            case 'comment':
                return [$method => $content['name'] ?? ''];
            case 'limit':
                return ['null' => true, $method => MysqlAdapter::TEXT_REGULAR];
            case 'save':
                return json_encode($content['default']);
        }
        return [];
    }

    protected function rich_text($content, string $method)
    {
        switch ($method) {
            case 'default':
                return [];
            case 'comment':
                return [$method => $content['name'] ?? ''];
            case 'limit':
                return ['null' => true, $method => MysqlAdapter::TEXT_REGULAR];
            case 'save':
                return json_encode($content['default']);
        }
        return [];
    }

    protected function getFieldType($option = [], $typeArr = []): string
    {
        $type = 'string';
        foreach ($typeArr ?: $this->typeArr as $k => $v) {
            if(in_array($option['type'], $v)) {
                $type = $k;break;
            }
        }
        return $type;
    }

    protected function tableExists($name = ''): bool
    {
        return (bool)$this->dbHandle("show tables like '". ($name ?: $this->tableName) . "'");
    }

    protected function tableColumn(): array
    {
        $columnArr = [];
        foreach ($this->dbHandle("DESC {$this->tableName}") as $v) {
            $type = "";
            preg_replace_callback('/^(\w+)/',function ($matches) use (&$type) {
                $type = $matches[0];
            },$v['Type']);
            $columnArr[$v['Field']] = [
                'field' => $v['Field'],
                'type' => $type,
                'default' => $v['Default']
            ];
        }
        return $columnArr;
    }

    public function dbHandle($sql)
    {
        return $this->dbConfig()->query($sql);
    }

    public function dbConfig(): ConnectionInterface
    {
        return Db::connect($this->getConnection());
    }

    private function setAdapter(): AdapterInterface
    {
        $options = $this->getDbConfig();
        $adapter = AdapterFactory::instance()->getAdapter($options['adapter'], $options);
        if ($adapter->hasOption('table_prefix') || $adapter->hasOption('table_suffix')) {
            $adapter = AdapterFactory::instance()->getWrapper('prefix', $adapter);
        }
        return $adapter;
    }

    /**
     * @return string
     */
    public function getTableName(): string
    {
        return $this->tableName;
    }

    /**
     * @param mixed|string $tableName
     * @return TableHandle
     */
    public function setTableName($tableName): self
    {
        $this->tableName = $tableName;
        //实例化基础表
        $this->mode = $this->dbConfig()->name($tableName);
        return $this;
    }

    /**
     * 获取数据库配置
     * @return array
     */
    protected function getDbConfig(): array
    {
        $config = config("database.connections.{$this->getConnection()}");

        return [
            'adapter'      => explode(',', $config['type'])[0],
            'host'         => explode(',', $config['hostname'])[0],
            'name'         => explode(',', $config['database'])[0],
            'user'         => explode(',', $config['username'])[0],
            'pass'         => explode(',', $config['password'])[0],
            'port'         => explode(',', $config['hostport'])[0],
            'charset'      => explode(',', $config['charset'])[0],
            'table_prefix' => explode(',', $config['prefix'])[0],
        ];
    }

    /**
     * @return string
     */
    public function getConnection(): string
    {
        return $this->connection;
    }

    /**
     * @param string $connection
     */
    protected function setConnection(string $connection): void
    {
        $this->connection = $connection;
    }

    /**
     * @return BaseQuery
     */
    public function getMode(): BaseQuery
    {
        return $this->mode;
    }

    /**
     * @param BaseQuery $mode
     * @return TableHandle
     */
    public function setMode(BaseQuery $mode): self
    {
        $this->mode = $mode;
        return $this;
    }

    /**
     * 刷新模型操作
     * @return $this
     */
    public function flushMode(): self
    {
        $this->mode = $this->dbConfig()->name($this->tableName);
        return $this;
    }

    /**
     * @return int
     */
    public function getTableId(): int
    {
        return $this->tableId;
    }

    /**
     * @param int $tableId
     * @return TableHandle
     */
    public function setTableId(int $tableId): self
    {
        $this->tableId = $tableId;
        return $this;
    }

    /**
     * @return string
     */
    public function getPrimaryKey(): string
    {
        return $this->primaryKey;
    }

    /**
     * @param string $primaryKey
     * @return TableHandle
     */
    public function setPrimaryKey(string $primaryKey): self
    {
        $this->primaryKey = $primaryKey;
        return $this;
    }
}