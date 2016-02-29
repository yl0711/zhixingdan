<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 15/11/11
 * Time: 下午3:21
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminBaseController;
use App\Http\Manage\AreaManage;
use App\Http\Manage\DepartmentManage;
use Illuminate\Http\Request;

/**
 * Class AdminUserController
 * @package App\Http\Controllers\Admin
 * @Authorization 权限管理::管理员管理
 */
class AdminUserController extends AdminBaseController
{
    private $departmentManage = null;
    private $areaManage = null;

    public function __construct()
    {
        parent::__construct();

        $this->departmentManage = new DepartmentManage();
        $this->areaManage = new AreaManage();
    }

    public function index(Request $request)
    {
        $name = $request->input('name', '');
        $group_id = $request->input('group_id', 0);
        $department_id = $request->input('department_id', 0);
        $status = $request->input('status', 2);

        $userList = $this->adminUserManage->getUserList($name, $group_id, $department_id, [], $status);
        $data = $this->adminUserManage->getUserGroupAll();
        foreach ($data as $value) {
            if ($value['id'] == $group_id) {
                $value['selected'] = 'selected="selected"';
            } else {
                $value['selected'] = '';
            }
            $grouplist[$value['id']] = $value;
        }

        $data = $this->departmentManage->getListByStatus();
        foreach ($data as $value) {
            if ($value['id'] == $department_id) {
                $value['selected'] = 'selected="selected"';
            } else {
                $value['selected'] = '';
            }
            $departmentlist[$value['id']] = $value;
        }

        return view('admin.admin_user.list', compact('userList', 'grouplist', 'departmentlist', 'name', 'group_id', 'department_id', 'status'));
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
            $user = [];
            $group = $this->adminUserManage->getUserGroupAll();
            foreach ($group as $value)
            {
                $value['selected'] = '';
            }
            $department = $this->departmentManage->getListByStatus();
            foreach ($department as $value)
            {
                $value['selected'] = '';
            }
            $area = $this->areaManage->getList();
            foreach ($department as $value)
            {
                $value['selected'] = '';
            }

            return view('admin.admin_user.add', compact('user', 'group', 'department', 'area'));
        }
    }

    /**
     * @Authorization 修改信息
     */
    public function modify(Request $request, $id)
    {
        if ('POST' == $request->method())
        {
            return $this->doModify($request, $id);
        }
        else
        {
            try
            {
                $user = $this->adminUserManage->getUser($id)->toarray()[0];
            }
            catch (\Exception $e)
            {
                abort('404', $e->getMessage());
            }
            $group = $this->adminUserManage->getUserGroupAll();
            foreach ($group as $value)
            {
                if ($value['id'] == $user['group_id'])
                {
                    $value['selected'] = 'selected="selected"';
                }
                else
                {
                    $value['selected'] = '';
                }
            }
            $department = $this->departmentManage->getListByStatus();
            foreach ($department as $value)
            {
                if ($value['id'] == $user['department_id'])
                {
                    $value['selected'] = 'selected="selected"';
                }
                else
                {
                    $value['selected'] = '';
                }
            }
            $user_area = explode(',', $user['area_id']);
            $area = $this->areaManage->getList();
            foreach ($department as $value)
            {
                if (in_array($value['id'], $user_area)) {
                    $value['selected'] = 'selected="selected"';
                } else {
                    $value['selected'] = '';
                }
            }

            return view('admin.admin_user.modify', compact('user', 'group', 'department', 'area'));
        }
    }

    /**
     * @Authorization 直属上级设置
     */
    public function parentUser(Request $request, $id)
    {
        try {
            $user = $this->adminUserManage->getUser($id)->toarray()[0];
        }  catch (\Exception $e) {
            abort('404', $e->getMessage());
        }

        if ('POST' == $request->method()) {

        } else {



            $userList = $this->adminUserManage->getUserList('', 0, $user['department_id'], explode(',', $user['area_id']));
            if (!$userList->count()) {
                abort('404', '没有与你同区域\同部门的用户');
            }
            return view('admin.admin_user.parent', compact('user', 'userList'));
        }
    }

    /**
     * @Authorization 修改状态
     */
    public function modifyStatus($id)
    {
        try {
            $data = $this->adminUserManage->setUserStatus($id);
            echo json_encode(['status'=>'success', 'data'=>$data]);
        } catch (\Exception $e) {
            echo json_encode(['status'=>'error', 'info'=>$e->getMessage()]);
        }
    }

    private function doAdd(Request $request)
    {
        try
        {
            $id = $this->adminUserManage->addUser($request->all())->id;
            return json_encode(['status'=>'success', 'data'=>['id'=>$id]]);
        }
        catch (\Exception $e)
        {
            return json_encode(['status'=>'error', 'info'=>$e->getMessage()]);
        }
    }

    private function doModify(Request $request, $id)
    {
        $this->adminUserManage->modifyUser($request->all());
        return json_encode(['status'=>'success', 'data'=>['id'=>$id]]);
    }
}