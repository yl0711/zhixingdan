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
            $value['article_check'] = $this->adminUserManage->getArticleCheck($value['article_check']);
            $value['article_view'] = $this->adminUserManage->getArticleView($value['article_view']);
            $list[$value['gid']] = $value;
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
    public function modify(Request $request, $gid)
    {
        if ('POST' == $request->method()) {
            return $this->domodify($request);
        } else {
            try {
                $group = $this->adminUserManage->getUserGroup($gid)->toarray()[0];
            } catch (\Exception $e) {
                echo $e->getMessage();exit;
            }

            $group['article_check_0'] = $group['article_check_1'] = '';
            $group['article_check_' . $group['article_check']] = 'checked="checked"';

            $group['article_view_0'] = $group['article_view_1'] = $group['article_view_2'] = '';
            $group['article_view_' . $group['article_view']] = 'checked="checked"';

            $grouplist = $this->adminUserManage->getUserGroupAll()->toarray();
            foreach ($grouplist as $key=>&$value) {
                if ($value['gid'] == $group['gid']) {
                    unset($grouplist[$key]);
                } elseif ($value['gid'] == $group['parentid']) {
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
    public function state($uid) {

    }
}