<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 15/11/11
 * Time: 下午3:21
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminBaseController;
use Illuminate\Http\Request;
use Route;

/**
 * Class AdminUserController
 * @package App\Http\Controllers\Admin
 * @Authorization 权限管理::管理员管理
 */
class AdminUserController extends AdminBaseController
{

    public function index()
    {
        $data = $this->adminUserManage->getUserList();
        $userGroup = $data['userGroup'];
        $userList = $data['userList']['data'];

        return view('admin.admin_user_list', compact('userList', 'userGroup'));
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
            $group = $this->adminUserManage->getUserGroupAll()->toarray();
            foreach ($group as &$value)
            {
                $value['selected'] = '';
            }
            return view('admin.admin_user_add', compact('user', 'group'));
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
                $user = $this->adminUserManage->getUser($id)->toarray()[0];
            }
            catch (\Exception $e)
            {
                echo $e->getMessage();exit;
            }
            $group = $this->adminUserManage->getUserGroupAll()->toarray();
            foreach ($group as &$value)
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

            return view('admin.admin_user_modify', compact('user', 'group'));
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
            $this->adminUserManage->addUser($request->all());
            return json_encode(['status'=>'success']);
        }
        catch (\Exception $e)
        {
            return json_encode(['status'=>'error', 'info'=>$e->getMessage()]);
        }
    }

    private function doModify(Request $request)
    {
        $this->adminUserManage->modifyUser($request->all());
        return json_encode(['status'=>'success']);
    }
}