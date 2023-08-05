<?php
/**
 * User: Shiroi
 * EMail: 707305003@qq.com
 */

namespace app\admin\traits;

trait ServiceTrait
{
    public static function getLists($where = [],$field = '*',$order = [])
    {
        return self::$model::where($where)->field($field)->order($order)->select();
    }

    public static function getIndex($where = [], $field = [], $paginate = [], $order = [])
    {
        return static::$model::scope('where', $where)->where($where)->field($field)->order($order)->paginate($paginate);
    }

    public static function getInfo($where = [],$order = [])
    {
        return static::$model::scope('where', $where)->where($where)->order($order)->findOrEmpty();
    }

    public static function create($data = [])
    {
        return static::$model::create($data);
    }

    public static function update($where = [],$update = [])
    {
        return self::$model::scope('update',$where,$update);
    }

    public static function delete($where = [], $isDelete = true)
    {
        return static::$model::where($where)->findOrEmpty()->force($isDelete)->delete();
    }

    public static function inc($where = [], $field ='', $value=1)
    {
        return static::$model::where($where)->Inc($field, $value)->update();
    }

    public static function dec($where = [], $field ='', $value=1)
    {
        return static::$model::where($where)->Dec($field, $value)->update();
    }

    public static function getTotal($where = [])
    {
        return static::$model::where($where)->count();
    }

    public static function value($where = [], $value = '')
    {
        return static::$model::where($where)->value($value);
    }
}