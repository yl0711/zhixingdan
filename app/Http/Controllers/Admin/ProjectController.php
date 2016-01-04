<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 15/12/30
 * Time: 下午12:04
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminBaseController;
use App\Http\Manage\ProjectManage;
use App\Http\Model\liuchengdan\ProjectModel;
use App\Http\Requests\Request;

class ProjectController extends AdminBaseController
{
    private $projectManage = null;

    public function __construct()
    {
        parent::__construct();

        $this->projectManage = new ProjectManage();
    }

    public function index()
    {
        $data = $this->projectManage->getList()->toArray();

        return view('admin.project_list', compact('data'));
    }

    /**
     * @Authorization 添加
     */
    public function add(Request $request)
    {
        if ('POST' == $request->method())
        {
            return $this->doAdd($request);
        }
        else
        {
            return view('admin.project_add');
        }
    }

    /**
     * @Authorization 修改信息
     */
    public function modify(Request $request, $id)
    {
        if ('POST' == $request->method())
        {
            return $this->doModify($request);
        }
        else
        {
            try
            {
                $model = new ProjectModel();
                $company = $model->getOneById($id)->toArray()[0];
            }
            catch (\Exception $e)
            {
                echo $e->getMessage();exit;
            }
            return view('admin.project_modify', compact('company'));
        }
    }

    /**
     * @Authorization 修改状态
     */
    public function modifyStatus($id)
    {

    }

    private function doAdd(Request $request)
    {
        try
        {
            $this->projectManage->add($request->all());
            return json_encode(['status'=>'success']);
        }
        catch (\Exception $e)
        {
            return json_encode(['status'=>'error', 'info'=>$e->getMessage()]);
        }
    }

    private function doModify(Request $request)
    {
        try {
            $this->projectManage->modify($request->all());
            return json_encode(['status'=>'success']);
        } catch (\Exception $e) {
            return json_encode(['status'=>'error', 'info'=>$e->getMessage()]);
        }
    }
}