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
 * @Authorization 部门管理::管理组管理
 */
class AdminDepartmentController extends AdminBaseController
{
    private $departmentManage = null;

    public function __construct()
    {
        parent::__construct();

        $this->departmentManage = new DepartmentManage();
    }

    public function index()
    {
        $list = [];
        $data = $this->departmentManage->getList();
        foreach ($data as $value)
        {
            $list[$value['id']] = $value;
        }
        return view('admin.admin_department_list', compact('list'));
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
            return view('admin.admin_department_add', compact('departmentlist'));
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
            return view('admin.admin_department_modify', compact('department', 'departmentlist'));
        }
    }

    public function modifyStatus($id)
    {

    }

    private function doAdd(Request $request)
    {
        try
        {
            $this->departmentManage->add($request->all());
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
            $this->departmentManage->modify($request->all());
            return json_encode(['status'=>'success']);
        }
        catch (\Exception $e)
        {
            return json_encode(['status'=>'error', 'info'=>$e->getMessage()]);
        }
    }

}