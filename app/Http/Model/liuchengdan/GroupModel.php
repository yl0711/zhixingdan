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
     * @param int    $status     管理组状态, 1-启用，0-停用，-1-删除
     * @return mixed
     */
    public function getList($name=null, $parentid=null, $status=null)
    {
        $query = self::orderBy('id', 'asc');
        if (!is_null($name))
        {
            $query = $query->where('name', 'like', '%'.$name.'%');
        }
        if (!is_null($parentid))
        {
            $query = $query->where('parentid', $parentid);
        }
        if (!is_null($status))
        {
            $query = $query->where('status', $status);
        }

        return $query->paginate(config('global.PAGE_SIZE'));
    }

    /**
     * 获取所有管理组列表
     * @return mixed
     */
    public function getAll($status=1)
    {
        return self::where('status', $status)->orderBy('id', 'asc')->get();
    }

    /**
     * 获取指定ID的管理组
     * @param  mixed $id 可以是数组或单个ID
     * @return mixed
     */
    public function getByGid($id)
    {
        if (is_array($id))
        {
            return self::whereIn('id', $id)->get();
        }
        elseif (is_numeric($id))
        {
            return self::where('id', $id)->get();
        }
        else
        {
            throw new \Exception(__CLASS__ . '->' . __FUNCTION__ . ': 参数不符合规范, ' . implode(',', func_get_args()));
        }
    }

    /**
     * 获取指定名字的管理组
     * @param $name
     * @return mixed
     */
    public function getByGname($name)
    {
        return self::where('name', $name)->get()->toarray();
    }

    public function add(Array $data)
    {
        try
        {
            $obj = self::create($data);
        }
        catch (\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }
        return $obj->id;
    }

    public function modify($id, Array $data)
    {
        self::where('id', $id)->update($data);
    }

    public function state($id)
    {

    }
}