<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 15/12/31
 * Time: 上午11:23
 */

namespace App\Http\Manage;

use App\Http\Model\liuchengdan\ProjectModel;

class ProjectManage
{
    private $projectModel = null;

    public function __construct()
    {
        $this->projectModel = new ProjectModel();
    }

    public function getList($name=null, $status=null)
    {
        return $this->projectModel->getList($name, $status);
    }

    public function add(array $request)
    {
        if (!isset($request['name']) || empty($request['name'])) {
            throw new \Exception('请填写项目名称');
        }
        if (!empty($this->projectModel->getOneByName($request['name'])->toArray())) {
            throw new \Exception('项目 ' . $request['name'] . ' 已经存在');
        }

        return $this->projectModel->add($request);
    }

    public function modify(array $request)
    {
        if (!$this->projectModel->getOneById($request['id'])->toArray())
        {
            throw new \Exception('项目不存在');
        }
        $id = $request['id'];

        if (!isset($request['name']) || empty($request['name'])) {
            throw new \Exception('请填写项目名称');
        }
        if ($request['name'] != $request['oldname'] && !empty($this->projectModel->getOneByName($request['name'])->toArray())) {
            throw new \Exception('项目 ' . $request['name'] . ' 已经存在');
        }
        unset($request['id']);
        unset($request['oldname']);

        try {
            $this->projectModel->modify($id, $request);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}