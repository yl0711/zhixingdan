<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 16/2/25
 * Time: 上午9:35
 */

namespace App\Http\Manage;


use App\Http\Model\liuchengdan\AreaModel;

class AreaManage
{
    private $areaModel = null;

    public function __construct()
    {
        $this->areaModel = new AreaModel();
    }

    public function getList()
    {
        return $this->areaModel->getList();
    }

    public function getOneById($id)
    {
        return $this->areaModel->getOneById($id);
    }

    public function add(Array $request)
    {
        if (!isset($request['name']) || empty($request['name'])) {
            throw new \Exception('名称不能为空');
        }

        if ($this->areaModel->nameExists($request['name'])) {
            throw new \Exception('名称已经存在');
        }
        $data['name'] = $request['name'];

        return $this->areaModel->add($data);
    }

    public function modify($id, Array $request)
    {
        if (!isset($request['name']) || empty($request['name'])) {
            throw new \Exception('名称不能为空');
        }
        if ($request['oldname']!=$request['name'] && $this->areaModel->nameExists($request['name'])) {
            throw new \Exception('名称已经存在');
        }
        $data['name'] = $request['name'];

        return $this->areaModel->modify($id, $data);
    }

}