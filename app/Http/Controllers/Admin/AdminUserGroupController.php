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

    public function index()
    {
        $list = [];
        $data = $this->adminUserManage->getUserGroupList()->toArray();
        foreach ($data as $value) {
            $list[$value['id']] = $value;
        }
        return view('admin.admin_usergroup_list', compact('list'));
    }

    /**
     * @Authorization 添加
     */
    public function add(Request $request)
    {
        if ('POST' == $request->method()) {
            return $this->doadd($request);
        } else {
            $grouplist = $this->adminUserManage->getUserGroupAll()->toarray();
            foreach ($grouplist as $key=>&$value) {
                $value['selected'] = '';
            }
            return view('admin.admin_usergroup_add', compact('grouplist'));
        }
    }

    public function doadd(Request $request)
    {
        try {
            $this->adminUserManage->addUserGroup($request->all());
            echo json_encode(['status'=>'success']);
        } catch (\Exception $e) {
            echo json_encode(['status'=>'error', 'info'=>$e->getMessage()]);
        }
    }

    /**
     * @Authorization 修改
     */
    public function modify(Request $request, $id)
    {
        if ('POST' == $request->method()) {
            return $this->domodify($request);
        } else {
            try {
                $group = $this->adminUserManage->getUserGroup($gid)->toarray()[0];
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
            return view('admin.admin_usergroup_modify', compact('group', 'grouplist'));
        }
    }

    public function domodify(Request $request)
    {
        try {
            $this->adminUserManage->modifyUserGroup($request->all());
            echo json_encode(['status'=>'success']);
        } catch (\Exception $e) {
            echo json_encode(['status'=>'error', 'info'=>$e->getMessage()]);
        }
    }

    /**
     * @Authorization 删除
     */
    public function state($id) {

    }
}