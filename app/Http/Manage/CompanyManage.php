<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 15/12/31
 * Time: 上午11:23
 */

namespace App\Http\Manage;

use App\Http\Model\liuchengdan\CompanyModel;

class CompanyManage
{
    private $companyModel = null;

    public function __construct()
    {
        $this->companyModel = new CompanyModel();
    }

    public function getList($name='', $status=2)
    {
        return $this->companyModel->getList($name, $status);
    }

    public function getAll()
    {
        return $this->companyModel->getAll();
    }

    public function add(array $request)
    {
        if (!isset($request['name']) || empty($request['name'])) {
            throw new \Exception('请填写供应商名称');
        }
        if (!empty($this->companyModel->getOneByName($request['name'])->toArray())) {
            throw new \Exception('供应商 ' . $request['name'] . ' 已经存在');
        }

        return $this->companyModel->add($request);
    }

    public function modify(array $request)
    {
        if (!$this->companyModel->getOneById($request['id'])->toArray())
        {
            throw new \Exception('供应商不存在');
        }
        $id = $request['id'];

        if (!isset($request['name']) || empty($request['name'])) {
            throw new \Exception('请填写供应商名称');
        }
        if ($request['name'] != $request['oldname'] && !empty($this->companyModel->getOneByName($request['name'])->toArray())) {
            throw new \Exception('供应商 ' . $request['name'] . ' 已经存在');
        }
        unset($request['id']);
        unset($request['oldname']);

        try {
            $this->companyModel->modify($id, $request);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function setStatus($id)
    {
        try {
            $data = [];
            $company = $this->companyModel->getOneById($id)->toArray()[0];
            $data['status'] = abs(1 - $company['status']);
            $this->companyModel->modify($id, $data);
            return $data['status'];
        } catch (HttpException $e) {
            throw new \Exception($e->getMessage());
        }
    }
}