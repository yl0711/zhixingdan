<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 15/12/25
 * Time: 上午11:43
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminBaseController;
use App\Http\Manage\DepartmentManage;
use App\Http\Model\liuchengdan\DepartmentModel;
use Illuminate\Http\Request;

/**
 * Class AdminDepartmentController
 * @package App\Http\Controllers\Admin
 */
class AdminDepartmentController extends AdminBaseController
{
    private $departmentManage = null;

    public function __construct()
    {
        parent::__construct();

        $this->departmentManage = new DepartmentManage();
    }

    public function index(Request $request)
    {
        $name = $request->input('name', '');
        $parentid = $request->input('parentid', 0);
        $status = $request->input('status', 2);
        $list = $this->departmentManage->getList($name, $status, $parentid);

        $departmentlist = $this->departmentManage->getListByStatus(1);
        if ($departmentlist)
        {
            foreach ($departmentlist as $value)
            {
                if ($value['id'] == $parentid) {
                    $value['selected'] = 'selected="selected"';
                } else {
                    $value['selected'] = '';
                }
            }
        }

        return view('admin.department.list', compact('list', 'departmentlist', 'name', 'parentid', 'status'));
    }

    /**
     * @Authorization 添加部门
     */
    public function add(Request $request)
    {
        if ('POST' == $request->method())
        {
            return $this->doAdd($request);
        }
        else
        {
            $departmentlist = $this->departmentManage->getListByStatus(1);
            if ($departmentlist)
            {
                foreach ($departmentlist as $key=>&$value)
                {
                    $value['selected'] = '';
                }
            }
            return view('admin.department.add', compact('departmentlist'));
        }
    }

    /**
     * @Authorization 修改部门
     */
    public function modify(Request $request, $id)
    {
        if ('POST' == $request->method())
        {
            return $this->doModify($request);
        }
        else
        {
            $model = new DepartmentModel();
            try
            {
                $department = $model->getOneById($id)->toArray()[0];
            }
            catch (\Exception $e)
            {
                echo $e->getMessage();exit;
            }
            $departmentlist = $model->getAll()->toArray();

            foreach ($departmentlist as $key=>&$value)
            {
                if ($value['id'] == $id)
                {
                    unset($departmentlist[$key]);
                }
                elseif ($value['id'] == $department['parentid'])
                {
                    $value['selected'] = 'selected="selected"';
                }
                else
                {
                    $value['selected'] = '';
                }
            }
            return view('admin.department.modify', compact('department', 'departmentlist'));
        }
    }

    /**
     * @Authorization 修改状态
     */
    public function modifyStatus($id)
    {
        try {
            $data = $this->departmentManage->setStatus($id);
            echo json_encode(['status'=>'success', 'data'=>$data]);
        } catch (\Exception $e) {
            echo json_encode(['status'=>'error', 'info'=>$e->getMessage()]);
        }
    }

    private function doAdd(Request $request)
    {
        try
        {
            $data = $request->except(['s']);
            $this->departmentManage->add($data);
            return json_encode(['status'=>'success']);
        }
        catch (\Exception $e)
        {
            return json_encode(['status'=>'error', 'info'=>$e->getMessage()]);
        }
    }

    private function doModify(Request $request)
    {
        try
        {
            $data = $request->except(['s']);
            $this->departmentManage->modify($data);
            return json_encode(['status'=>'success']);
        }
        catch (\Exception $e)
        {
            return json_encode(['status'=>'error', 'info'=>$e->getMessage()]);
        }
    }

}