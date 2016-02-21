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
}