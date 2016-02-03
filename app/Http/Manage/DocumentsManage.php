<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 16/1/20
 * Time: 下午4:50
 */

namespace App\Http\Manage;

use App\Http\Model\liuchengdan\DocumentsModel;
use Exception;

class DocumentsManage
{
    private $documentModel = null;

    public function __construct()
    {
        $this->documentModel = new DocumentsModel();
    }

    /**
     * 根据项目名称获取项目列表
     *
     * @param strung $name
     * @param int $status
     * @return mixed
     */
    public function getList($name='', $company_id=0, $project_id=0, $status=2)
    {
        return $this->documentModel->getList($name, $company_id, $project_id, $status);
    }

    public function getAll()
    {
        return $this->documentModel->getAll();
    }

    /**
     * 根据项目ID获取单个项目信息
     *
     * @param int $id
     * @param int $status
     */
    public function getOneById($id, $status=1)
    {
        $data = $this->documentModel->getOneById($id, $status);
        if (empty($data->toArray()))
        {
            throw new HttpException('404', '你所访问的内容不存在');
        }
        return $data;
    }

    public function add(array $request)
    {
        if (!isset($request['name']) || empty($request['name'])) {
            throw new Exception('请填写名称');
        }
        if (!isset($request['company_id']) || empty($request['company_id'])) {
            throw new Exception('请选择供应商');
        }
        if (!isset($request['project_id']) || empty($request['project_id'])) {
            throw new Exception('请选择项目');
        }
        if (!empty($this->documentModel->getOneByName($request['name'])->toArray())) {
            throw new Exception($request['name'] . ' 已经存在');
        }

        return $this->documentModel->add($request);
    }

    public function modify(array $request)
    {
        if (!$this->documentModel->getOneById($request['id'])->toArray())
        {
            throw new Exception('项目不存在');
        }
        $id = $request['id'];

        if (!isset($request['name']) || empty($request['name'])) {
            throw new Exception('请填写项目名称');
        }
        if ($request['name'] != $request['oldname'] && !empty($this->documentModel->getOneByName($request['name'])->toArray())) {
            throw new Exception('项目 ' . $request['name'] . ' 已经存在');
        }
        unset($request['id']);
        unset($request['oldname']);

        try {
            $this->documentModel->modify($id, $request);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }


    public function setStatus($id)
    {
        try {
            $data = [];
            $project = $this->documentModel->getOneById($id)->toArray()[0];
            $data['status'] = abs(1 - $project['status']);
            $this->documentModel->modify($id, $data);
            return $data['status'];
        } catch (HttpException $e) {
            throw new \Exception($e->getMessage());
        }
    }
}