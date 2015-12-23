<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 15/11/12
 * Time: 下午4:16
 */

namespace App\Http\Model\liuchengdan;

use App\Http\Model\BaseModel;

class GroupModel extends BaseModel
{
    protected $table = 'base_group';

    /**
     * 获取管理组列表(管理组列表页用)
     * @link url('usergroup/index')
     * @param string $name      管理组名
     * @param int    $parentid  上级管理组ID
     * @param int    $state     管理组状态, 1-启用，0-停用，-1-删除
     * @return mixed
     */
    public function getList($name=null, $parentid=null, $state=null)
    {
        if (!is_null($name)) {
            self::where('gname', 'like', '%'.$name.'%');
        }
        if (!is_null($parentid)) {
            self::where('parentid', $parentid);
        }
        if (!is_null($state)) {
            self::where('state', $state);
        }

        return self::orderBy('created_at', 'desc')->get();
    }

    /**
     * 获取所有管理组列表
     * @return mixed
     */
    public function getAll()
    {
        return self::where('state', 1)->orderBy('gname', 'desc')->get();
    }

    /**
     * 获取指定ID的管理组
     * @param  mixed $gid 可以是数组或单个ID
     * @return mixed
     */
    public function getByGid($gid)
    {
        if (is_array($gid)) {
            return self::whereIn('gid', $gid)->get();
        } elseif (is_numeric($gid)) {
            return self::where('gid', $gid)->get();
        } else {
            throw new \Exception(__CLASS__ . '->' . __FUNCTION__ . ': 参数不符合规范, ' . implode(',', func_get_args()));
        }
    }

    /**
     * 获取指定名字的管理组
     * @param $gname
     * @return mixed
     */
    public function getByGname($gname)
    {
        return self::where('gname', $gname)->get()->toarray();
    }

    public function add(Array $data)
    {
        try {
            $obj = self::create($data);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
        return $obj->id;
    }

    public function modify($gid, Array $data)
    {
        self::where('gid', $gid)->update($data);
    }

    public function state($gid)
    {

    }
}