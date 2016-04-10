<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 16/2/24
 * Time: 下午10:17
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminBaseController;
use App\Http\Manage\AreaManage;
use Illuminate\Http\Request;

class AdminAreaController extends AdminBaseController
{
    private $areaManage = null;

    public function __construct()
    {
        parent::__construct();

        $this->areaManage = new AreaManage();
    }

    public function index()
    {
        $areaList = $this->areaManage->getList();

        return view('admin.admin_area.list', compact('areaList'));
    }

    /**
     * @Authorization 添加
     */
    public function add(Request $request)
    {
        if ('POST' == $request->method()) {
            return $this->doAdd($request);
        } else {

            return view('admin.admin_area.add');
        }
    }

    /**
     * @Authorization 修改
     */
    public function modify(Request $request, $id)
    {
        if ('POST' == $request->method()) {
            unset($request->all()['s']);
            return $this->doModify($request, $id);
        } else {
            $area = $this->areaManage->getOneById($id)->toArray()[0];
            return view('admin.admin_area.modify', compact('area'));
        }
    }

    private function doAdd(Request $request)
    {
        try {
            unset($request->all()['s']);
            $this->areaManage->add($request->all());
            return json_encode(['status'=>'success']);
        } catch (\Exception $e){
            return json_encode(['status'=>'error', 'info'=>$e->getMessage()]);
        }
    }

    private function doModify(Request $request, $id)
    {
        try {
            unset($request->all()['s']);
            $this->areaManage->modify($id, $request->all());
            return json_encode(['status'=>'success']);
        } catch (\Exception $e){
            return json_encode(['status'=>'error', 'info'=>$e->getMessage()]);
        }
    }
}