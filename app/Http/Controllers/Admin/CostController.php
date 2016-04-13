<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 16/2/20
 * Time: 上午10:05
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminBaseController;
use App\Http\Manage\CostManage;
use Illuminate\Http\Request;

class CostController extends AdminBaseController
{
    private $costManage = null;

    public function __construct()
    {
        parent::__construct();

        $this->costManage = new CostManage();
    }

    public function index(Request $request)
    {
        $name = $request->input('name', '');
        $status = $request->input('status', 2);

        $costList = $this->costManage->getBaseList($name, $status);

        $data = $this->adminUserManage->getAllUser();
        foreach ($data as $item) {
            $userList[$item['id']] = $item;
        }

        return view('admin.cost.list', compact('costList', 'userList', 'name', 'status'));
    }

    /**
     * @Authorization 添加
     */
    public function add(Request $request)
    {
        if ('POST' == $request->method()) {
            return $this->doAdd($request);
        } else {
            $userList = $this->adminUserManage->getAllUser();
            if ($userList) {
                foreach ($userList as &$value) {
                    $value['selected'] = '';
                }
            } else {
                $userList = [];
            }

            return view('admin.cost.add', compact('userList'));
        }
    }

    /**
     * @Authorization 修改
     */
    public function modify(Request $request, $id)
    {
        if ('POST' == $request->method()) {
            return $this->doModify($request, $id);
        } else {
            try {
                $cost = $this->costManage->getBaseOneById($id)->toArray()[0];
            } catch (HttpException $e) {
                abort($e->getStatusCode(), $e->getMessage());
            }

            $userList = $this->adminUserManage->getAllUser();
            if ($userList) {
                foreach ($userList as $value) {
                    if ($value['id'] == $cost['review_user']){
                        $value['selected'] = 'selected="selected"';
                    } else {
                        $value['selected'] = '';
                    }
                }
            } else {
                $userList = [];
            }

            return view('admin.cost.modify', compact('cost', 'userList'));
        }
    }

    /**
     * @Authorization 状态变更
     */
    public function modifyStatus(Request $request, $id)
    {

    }


    private function doAdd(Request $request)
    {
        try {
            $data = $request->except(['s']);
            $this->costManage->addBase($data);
            return json_encode(['status'=>'success']);
        } catch (\Exception $e) {
            return json_encode(['status'=>'error', 'info'=>$e->getMessage()]);
        }
    }

    private function doModify($request, $id)
    {
        try {
            $data = $request->except(['s']);
            $this->costManage->modifyBase($data);
            return json_encode(['status'=>'success']);
        } catch (\Exception $e) {
            return json_encode(['status'=>'error', 'info'=>$e->getMessage()]);
        }
    }
}