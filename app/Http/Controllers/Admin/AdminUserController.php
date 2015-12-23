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
 * Class UserController
 * @package App\Http\Controllers\Admin
 * @Authorization 权限管理::管理员管理
 * @beforeAuthorization AdminUserGroupController
 */
class AdminUserController extends AdminBaseController
{

    public function index()
    {
        $data = $this->adminUserManage->getUserList();
        $userGroup = $data['userGroup'];
        $userList = $data['userList']['data'];
        foreach ($userList as &$value) {
            if (-1 == $value['article_check']) {
                $value['article_check'] = $this->adminUserManage->getArticleCheck($userGroup[$value['gid']]['article_check']);
            } else {
                $value['article_check'] = $this->adminUserManage->getArticleCheck($value['article_check']);
            }
            if (-1 == $value['article_view']) {
                $value['article_view'] = $this->adminUserManage->getArticleView($userGroup[$value['gid']]['article_view']);
            } else {
                $value['article_view'] = $this->adminUserManage->getArticleView($value['article_view']);
            }
        }

        return view('admin.admin_user_list', compact('userList', 'userGroup'));
    }

    /**
     * @Authorization 添加
     */
    public function add(Request $request)
    {
        if ('POST' == $request->method()) {
            try {
                $this->adminUserManage->addUser($request->all());
                echo json_encode(['status'=>'success']);
            } catch (\Exception $e) {
                echo json_encode(['status'=>'error', 'info'=>$e->getMessage()]);
            }
        } else {
            $user = [];
            $group = $this->adminUserManage->getUserGroupAll()->toarray();
            foreach ($group as &$value) {
                $value['selected'] = '';
            }
            return view('admin.admin_user_add', compact('user', 'group'));
        }
    }

    /**
     * @Authorization 修改信息
     */
    public function modify(Request $request, $uid)
    {
        if ('POST' == $request->method()) {
            return $this->domodify($request);
        } else {
            try {
                $user = $this->adminUserManage->getUser($uid)->toarray()[0];
            } catch (\Exception $e) {
                echo $e->getMessage();exit;
            }

            $user['article_check_0'] = $user['article_check_1'] = '';
            if (-1 != $user['article_check']) {
                $user['article_check_' . $user['article_check']] = 'checked="checked"';
            }

            $user['article_view_0'] = $user['article_view_1'] = $user['article_view_2'] = '';
            if (-1 != $user['article_view']) {
                $user['article_view_' . $user['article_view']] = 'checked="checked"';
            }

            $group = $this->adminUserManage->getUserGroupAll()->toarray();
            foreach ($group as &$value) {
                if ($value['gid'] == $user['gid']) {
                    $value['selected'] = 'selected="selected"';
                } else {
                    $value['selected'] = '';
                }
            }

            return view('admin.admin_user_modify', compact('user', 'group'));
        }

    }

    public function domodify(Request $request)
    {
        $this->adminUserManage->modifyUser($request->all());
        echo json_encode(['status'=>'success']);
    }

    /**
     * @Authorization 修改状态
     */
    public function state($uid)
    {

    }
}