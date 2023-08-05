<?php
/**
 * 设置模型
 * @author yupoxiong<i@yupoxiong.com>
 */

namespace app\common\model;

use JsonException;
use think\model\concern\SoftDelete;
use think\model\relation\BelongsTo;

/**
 * Class Setting
 * @package app\common\model
 * @property int $id ID
 * @property int $name 名称
 * @property string $code 代码
 * @property array $content 设置内容
 * @property int $setting_group_id 所属分组ID
 */
class Setting extends CommonBaseModel
{
    use SoftDelete;

    protected $name = 'setting';
    protected $autoWriteTimestamp = true;

    protected $json = [
        'content'
    ];

    public array $noDeletionIds = [
        1, 2, 3, 4, 5,
    ];

    //可搜索字段
    public array $searchField = ['name', 'description', 'code',];

    protected $jsonAssoc = true;

    //关联设置分组
    public function settingGroup(): BelongsTo
    {
        return $this->belongsTo(SettingGroup::class);
    }
}
