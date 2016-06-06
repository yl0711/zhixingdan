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
use App\Http\Model\liuchengdan\UserModel;
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
        $arealist = $grouplist = $departmentlist = $parentids = $parentlist = [];

        $data = $this->areaManage->getList();
        foreach ($data as $value) {
            $arealist[$value['id']] = $value['name'];
        }

        $userList = $this->adminUserManage->getUserList($name, $group_id, $department_id, [], $status);
        foreach ($userList as $value) {
            $value['area_id'] = explode(',', trim($value['area_id'], ','));
            if ($value['parent_user']) {
                $parentids[] = $value['parent_user'];
            }
        }

        if ($parentids) {
            $data = $this->adminUserManage->getUserById($parentids);
            foreach ($data as $value) {
                $parentlist[$value['id']] = $value['name'];
            }
        }

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

        return view('admin.admin_user.list', compact('userList', 'arealist', 'grouplist', 'departmentlist', 'parentlist', 'name', 'group_id', 'department_id', 'status'));
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
            foreach ($area as $value)
            {
                $value['checked'] = '';
            }

            $superadmin_checked = ['checked="checked"', ''];

            return view('admin.admin_user.add', compact('user', 'group', 'department', 'area', 'superadmin_checked'));
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

            $superadmin_checked = ['', ''];
            if (1 == $this->admin_user['superadmin']) {
                $superadmin_checked[$user['superadmin']] = 'checked="checked"';
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
            $user_area = explode(',', trim($user['area_id'], ','));
            $area = $this->areaManage->getList();
            foreach ($area as $value)
            {
                if (in_array($value['id'], $user_area)) {
                    $value['checked'] = 'checked="checked"';
                } else {
                    $value['checked'] = '';
                }
            }

            return view('admin.admin_user.modify', compact('user', 'group', 'department', 'area', 'superadmin_checked'));
        }
    }

    /**
     * @Authorization 直属上级设置
     */
    public function parentUser(Request $request, $id)
    {
        try {
            $user = $this->adminUserManage->getUser($id)->toArray()[0];
        }  catch (\Exception $e) {
            abort('404', $e->getMessage());
        }

        if ('POST' == $request->method()) {
            try {
                $this->adminUserManage->updateParentUser($id, $request->all()['parent']);
                echo json_encode(['status'=>'success']);
            } catch (\Exception $e) {
                echo json_encode(['status'=>'error', 'info'=>$e->getMessage()]);
            }
        } else {
            $parentids = [];
            $group = $userGroup = $this->adminUserManage->getUserGroup($user['group_id'])->toarray()[0];
            while ($group['parentid'] > 0) {
                $group = $this->adminUserManage->getUserGroup($group['parentid'])->toarray()[0];
                $parentids[] = $group['id'];
            }

            if (!$parentids) {
                abort('404', '用户 ' . $user['name'] . ' 所在区域或部门已经没有人级别比他高了!');
            }

            //$userList = $this->adminUserManage->getUserList('', $parentids, $user['department_id'], explode(',', $user['area_id']));
            $userList = $this->adminUserManage->getUserList('', $parentids, $user['department_id'], explode(',', $user['area_id']));
            if (!$userList->count()) {
                abort('404', '用户 ' . $user['name'] . ' 所在区域或部门没有其他用户了!');
            }

            $data = $this->adminUserManage->getUserGroupAll();
            foreach ($data as $value) {
                $grouplist[$value['id']] = $value['name'];
            }
            return view('admin.admin_user.parent', compact('user', 'userGroup', 'userList', 'grouplist'));
        }
    }

    /**
     * @Authorization 权限转移
     */
    public function transferUser(Request $request, $id)
    {
        try {
            $user = $this->adminUserManage->getUser($id)->toArray()[0];
        }  catch (\Exception $e) {
            abort('404', $e->getMessage());
        }

        if ('POST' == $request->method()) {
            try {
                $this->adminUserManage->transferUser($id, $request->all()['transfer']);
                echo json_encode(['status'=>'success']);
            } catch (\Exception $e) {
                echo json_encode(['status'=>'error', 'info'=>$e->getMessage()]);
            }
        } else {
            $userGroup = $this->adminUserManage->getUserGroup($user['group_id'])->toarray()[0];

            $userList = $this->adminUserManage->getUserList();

            $data = $this->adminUserManage->getUserGroupAll();
            foreach ($data as $value) {
                $grouplist[$value['id']] = $value['name'];
            }
            return view('admin.admin_user.transfer', compact('user', 'userGroup', 'userList', 'grouplist'));
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
            $data = $request->except(['s']);
            $id = $this->adminUserManage->addUser($data)->id;
            return json_encode(['status'=>'success', 'data'=>['id'=>$id]]);
        }
        catch (\Exception $e)
        {
            return json_encode(['status'=>'error', 'info'=>$e->getMessage()]);
        }
    }

    private function doModify(Request $request, $id)
    {
        $data = $request->except(['s']);
        $this->adminUserManage->modifyUser($data);
        return json_encode(['status'=>'success', 'data'=>['id'=>$id]]);
    }
}