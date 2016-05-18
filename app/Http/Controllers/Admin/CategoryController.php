<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 16/2/14
 * Time: 下午5:29
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminBaseController;
use App\Http\Manage\CategoryManage;
use App\Http\Model\liuchengdan\CategoryModel;
use Illuminate\Http\Request;

class CategoryController extends AdminBaseController
{
    private $categoryManage = null;

    public function __construct()
    {
        parent::__construct();

        $this->categoryManage = new CategoryManage();
    }

    public function index(Request $request)
    {
        $name = $request->input('name', '');
        $type = $request->input('type', 0);
        $status = $request->input('status', 2);

        $categoryList = $this->categoryManage->getList($name, $type, $status);

        return view('admin.category.list', compact('name', 'status', 'type', 'categoryList'));
    }

    /**
     * @Authorization 添加
     */
    public function add(Request $request)
    {
        if ('POST' == $request->method()) {
            $data = $request->except(['s']);
            return $this->doAdd($data);
        } else {
            $category_type = ['','','',''];

            $userList = $this->adminUserManage->getAllUser();
            if ($userList) {
                foreach ($userList as $value) {
                    $value['selected'] = '';
                }
            } else {
                $userList = [];
            }

            return view('admin.category.add', compact('category_type', 'userList'));
        }
    }

    /**
     * @Authorization 修改
     */
    public function modify(Request $request, $id)
    {
        if ('POST' == $request->method()) {
            $data = $request->except(['s']);
            return $this->doModify($data, $id);
        } else {
            $category = $this->categoryManage->getOneByID($id)->toArray();
            if (!$category) {
                abort('404', '内容不存在');
            }
            $category = $category[0];
            $category_type = ['','','',''];
            $category_type[$category['type']] = 'selected="selected"';

            $userList = $this->adminUserManage->getAllUser();
            if ($userList) {
                foreach ($userList as $value) {
                    if ($value['id'] == $category['userid']) {
                        $value['selected'] = 'selected="selected"';
                    } else {
                        $value['selected'] = '';
                    }
                }
            } else {
                $userList = [];
            }

            return view('admin.category.modify', compact('category', 'category_type', 'userList'));
        }
    }

    /**
     * @Authorization 状态变更
     */
    public function modifyStatus(Request $request, $id)
    {
        $data = $request->all();
        if (!isset($data['status'])) {
            return json_encode(['status'=>'error', 'info'=>'参数错误']);
        }
        try {
            CategoryModel::where('id', $id)->update(['status'=>$data['status']]);
            return json_encode(['status'=>'success']);
        } catch (\Exception $e) {
            return json_encode(['status'=>'error', 'info'=>$e->getMessage()]);
        }
    }

    private function doAdd(array $data)
    {
        try  {
            $this->categoryManage->add($data);
            return json_encode(['status'=>'success']);
        } catch (\Exception $e) {
            return json_encode(['status'=>'error', 'info'=>$e->getMessage()]);
        }
    }

    private function doModify(array $data, $id)
    {
        try  {
            $this->categoryManage->add($data);
            return json_encode(['status'=>'success']);
        } catch (\Exception $e) {
            return json_encode(['status'=>'error', 'info'=>$e->getMessage()]);
        }
    }
}