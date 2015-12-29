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
     *
     * @param array $where  索引是条件字段, 值是对应字段的值, 这里不进行字段筛选过滤
     */
    public function getList(array $where = [])
    {
        $query = self::orderBy('created_at', 'desc');

        if ($where) {
            $query = $query->where($where);
        }
        return $query->paginate(config('global.PAGE_SIZE'));
    }

    /**
     * 返回全部, 此处只对数据显示状态进行过滤
     *
     * @param int $status
     */
    public function getAll($status=1)
    {

    }

    /**
     * 根据ID获取一条数据
     *
     * @param int $id
     */
    public function getOneById($id)
    {
        return self::where(['id'=>$id])->get();
    }

    /**
     * 根据一组ID获取多条数据
     *
     * @param array $ids
     */
    public function getMoreById(array $ids)
    {

    }

    /**
     * 根据名字获取一条数据, 此处是精确查找
     *
     * @param string $name
     */
    public function getOneByName($name)
    {

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

    }

}