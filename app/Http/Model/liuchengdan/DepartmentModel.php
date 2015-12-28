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
    public function getList(array $where)
    {

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