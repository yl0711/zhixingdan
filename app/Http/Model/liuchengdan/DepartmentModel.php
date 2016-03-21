<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 15/12/25
 * Time: 下午5:34
 */

namespace App\Http\Model\liuchengdan;

use App\Http\Model\BaseModel;

class DepartmentModel extends BaseModel
{
    protected $table = 'base_department';

    /**
     * 根据指定条件返回部门数据列表
     */
    public function getList($name='', $status=2, $parentid=0)
    {
        $query = self::orderBy('created_at', 'desc');

        if (!empty($name)) {
            $query = $query->where('name', 'like', "%{$name}%");
        }
        if (2 != $status) {
            $query = $query->where('status', $status);
        }
        if (0 < $parentid) {
            $query = $query->where('parentid', $parentid);
        }
        return $query->paginate(config('global.PAGE_SIZE'));
    }

    public function getCount(array $where = [])
    {
        return self::where($where)->count();
    }

    /**
     * 返回全部, 此处只对数据显示状态进行过滤
     *
     * @param int $status
     */
    public function getAll($status=1)
    {
        return self::where('status', $status)->get();
    }

    /**
     * 根据ID获取一条数据
     *
     * @param int $id
     */
    public function getOneById($id)
    {
        return self::where('id', $id)->get();
    }

    /**
     * 根据一组ID获取多条数据
     *
     * @param array $ids
     */
    public function getMoreById(array $ids)
    {
        return self::whereIn('id', $ids)->get();
    }

    /**
     * 根据名字获取一条数据, 此处是精确查找
     *
     * @param string $name
     */
    public function getOneByName($name, $id=0)
    {
        $query = self::where('name', $name);
        if ($id) {
            $query = $query->where('id', '<>', $id);
        }
        return $query->get();
    }

    /**
     * 根据缩写获取一条数据, 此处是精确查找
     *
     * @param string $name
     */
    public function getOneByElias($alias, $id=0)
    {
        $query = self::where('alias', $alias);
        if ($id) {
            $query = $query->where('id', '<>', $id);
        }
        return $query->get();
    }

    public function add(array $data)
    {
        try {
            $obj = self::create($data);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
        return $obj->id;
    }

    /**
     * 修改指定ID的内容
     *
     * @param int $id
     * @param array $data
     */
    public function modifyById($id, array $data)
    {
        try {
            return self::where('id', $id)->update($data);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

}