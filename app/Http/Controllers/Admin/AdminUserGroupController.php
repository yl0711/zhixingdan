<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 15/11/11
 * Time: 下午3:22
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminBaseController;
use App\Http\Manage\AdminUserManage;
use Illuminate\Http\Request;

/**
 * Class AdminUserGroupController
 * @package App\Http\Controllers\Admin
 * @Authorization 权限管理::管理组管理
 */
class AdminUserGroupController extends AdminBaseController
{

    public function index(Request $request)
    {
        $name = $request->input('name', '');
        $parentid = $request->input('parentid', 0);
        $status = $request->input('status', 2);

        $list = $this->adminUserManage->getUserGroupList($name, $parentid, $status);
        $grouplist = $this->adminUserManage->getUserGroupAll();
        if ($grouplist)
        {
            foreach ($grouplist as $value)
            {
                if ($value['id'] == $parentid) {
                    $value['selected'] = 'selected="selected"';
                } else {
                    $value['selected'] = '';
                }
            }
        }

        return view('admin.admin_usergroup.list', compact('list', 'name', 'parentid', 'status', 'grouplist'));
    }

    /**
     * @Authorization 添加
     */
    public function add(Request $request)
    {
        if ('POST' == $request->method()) {
            return $this->doAdd($request);
        } else {
            $grouplist = $this->adminUserManage->getUserGroupAll()->toArray();
            foreach ($grouplist as $key=>&$value) {
                $value['selected'] = '';
            }
            return view('admin.admin_usergroup.add', compact('grouplist'));
        }
    }

    /**
     * @Authorization 修改
     */
    public function modify(Request $request, $id)
    {
        if ('POST' == $request->method()) {
            return $this->doModify($request);
        } else {
            try {
                $group = $this->adminUserManage->getUserGroup($id)->toarray()[0];
            } catch (\Exception $e) {
                echo $e->getMessage();exit;
            }

            $grouplist = $this->adminUserManage->getUserGroupAll()->toarray();
            foreach ($grouplist as $key=>&$value) {
                if ($value['id'] == $group['id']) {
                    unset($grouplist[$key]);
                } elseif ($value['id'] == $group['parentid']) {
                    $value['selected'] = 'selected="selected"';
                } else {
                    $value['selected'] = '';
                }
            }
            return view('admin.admin_usergroup.modify', compact('group', 'grouplist'));
        }
    }

    /**
     * @Authorization 状态变更
     */
    public function modifyStatus($id)
    {
        try {
            $data = $this->adminUserManage->setGroupStatus($id);
            echo json_encode(['status'=>'success', 'data'=>$data]);
        } catch (\Exception $e) {
            echo json_encode(['status'=>'error', 'info'=>$e->getMessage()]);
        }
    }

    private function doAdd(Request $request)
    {
        try {
            $this->adminUserManage->addUserGroup($request->all());
            echo json_encode(['status'=>'success']);
        } catch (\Exception $e) {
            echo json_encode(['status'=>'error', 'info'=>$e->getMessage()]);
        }
    }

    private function doModify(Request $request)
    {
        try {
            $this->adminUserManage->modifyUserGroup($request->all());
            echo json_encode(['status'=>'success']);
        } catch (\Exception $e) {
            echo json_encode(['status'=>'error', 'info'=>$e->getMessage()]);
        }
    }
}