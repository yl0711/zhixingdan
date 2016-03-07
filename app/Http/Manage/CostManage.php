<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 16/2/20
 * Time: 上午10:13
 */

namespace App\Http\Manage;

use App\Http\Model\liuchengdan\BaseCostStructureModel;
use App\Http\Model\liuchengdan\DocumentCostStructureModel;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CostManage
{
    private $baseCostStructureModel = null;
    private $documentCostStructureModel = null;

    public function __construct()
    {
        $this->baseCostStructureModel = new BaseCostStructureModel();
        $this->documentCostStructureModel = new DocumentCostStructureModel();
    }

    public function getBaseList($name='', $status=2)
    {
        return $this->baseCostStructureModel->getList($name, $status);
    }

    /**
     * 根据项目ID获取单个项目信息
     *
     * @param int $id
     * @param int $status
     */
    public function getBaseOneById($id, $status=1)
    {
        $data = $this->baseCostStructureModel->getOneById($id, $status);
        if (empty($data->toArray())) {
            throw new HttpException('404', '你所访问的内容不存在');
        }
        return $data;
    }

    public function addBase(array $request)
    {
        if (!isset($request['name']) || empty($request['name'])) {
            throw new \Exception('请填写名称');
        }
        if (!empty($this->baseCostStructureModel->getOneByName($request['name'])->toArray())) {
            throw new \Exception($request['name'] . ' 已经存在');
        }

        return $this->baseCostStructureModel->add($request);
    }

    public function modifyBase(array $request)
    {
        if (!$this->baseCostStructureModel->getOneById($request['id'])->toArray()) {
            throw new Exception('数据不存在');
        }
        $id = $request['id'];

        if (!isset($request['name']) || empty($request['name'])) {
            throw new \Exception('请填写名称');
        }
        if ($request['name'] != $request['oldname'] && !empty($this->baseCostStructureModel->getOneByName($request['name'])->toArray())) {
            throw new \Exception($request['name'] . ' 已经存在');
        }

        unset($request['id'], $request['oldname']);

        try {
            $this->baseCostStructureModel->modify($id, $request);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function getDocStructureById($docID)
    {
        return $this->documentCostStructureModel->getByDocID($docID);
    }

    public function addDocStructure(array $data)
    {
        return $this->documentCostStructureModel->add($data);
    }

    public function modifyDocStructure(array $request, $doc_id)
    {

    }
}