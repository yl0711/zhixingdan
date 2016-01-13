<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 15/11/12
 * Time: 上午11:17
 */

namespace App\Http\Manage;

use App\Http\Model\liuchengdan\DepartmentModel;
use App\Http\Model\liuchengdan\UserModel;

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
        else
        {
            if ($this->nameExists($data['name']))
            {
                throw new \Exception('部门名称已经存在, 请重新填写');
            }
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

    public function modify(array $data)
    {
        if (!isset($data['id']) || empty($data['id']))
        {
            throw new \Exception('参数错误 #');
        }
        if (!isset($data['name']) || empty($data['name']))
        {
            throw new \Exception('部门名称不能为空');
        }
        else
        {
            if ($data['name'] != $data['oldname'])
            {
                if ($this->nameExists($data['name']))
                {
                    throw new \Exception('部门名称已经存在, 请重新填写');
                }
            }
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
            $id = $data['id'];
            unset($data['id'], $data['oldname']);
            $this->departmentModel->modifyById($id, $data);
        }
        catch (\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }
    }

    public function setStatus($id)
    {
        try {
            if ($this->departmentModel->getCount(['parentid'=>$id])) {
                throw new \Exception('当前部门下有子部门, 请先移除这些子部门再关闭');
            }
            $userModel = new UserModel();
            if ($userModel->getCount(['department_id'=>$id])) {
                throw new \Exception('当前用户组下存在用户, 请先移除再关闭');
            }

            $data = [];
            $department = $this->departmentModel->getOneById($id)->toArray()[0];
            $data['status'] = abs(1 - $department['status']);
            $this->departmentModel->modifyById($id, $data);
            return $data['status'];
        } catch (HttpException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function nameExists($name)
    {
        $exists = $this->departmentModel->getOneByName($name)->toArray();
        if ($exists)
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }
}