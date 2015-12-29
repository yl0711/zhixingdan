<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 15/11/12
 * Time: 上午11:17
 */

namespace App\Http\Manage;

use App\Http\Model\liuchengdan\DepartmentModel;

class DepartmentManage
{
    public $error = [];

    private $departmentModel = null;

    public function __construct()
    {
        $this->departmentModel = new DepartmentModel();
    }

    public function getList()
    {
        return $this->departmentModel->getList();
    }

    public function getListByStatus($status=1)
    {
        return $this->departmentModel->getList(['status'=>$status]);
    }

    public function add(array $data)
    {
        if (!isset($data['name']) || empty($data['name']))
        {
            throw new \Exception('部门名称不能为空');
        }
        if (isset($data['parentid']) && !empty($data['parentid']))
        {
            $parent = $this->departmentModel->getOneById($data['parentid'])->toArray()[0];
            if (!$parent)
            {
                throw new \Exception('选择的上级部门不存在');
            }
            if (0 === $parent['status'])
            {
                throw new \Exception('选择的上级部门不可用');
            }
        }
        try
        {
            $this->departmentModel->add($data);
        }
        catch (\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }
    }

    public function modify()
    {

    }

}