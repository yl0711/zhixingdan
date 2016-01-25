<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 16/1/18
 * Time: 下午6:17
 */

namespace app\Http\Controllers\Admin;

use App\Http\Controllers\AdminBaseController;
use App\Http\Requests\Request;

class DocumentsController extends AdminBaseController
{
    private $documentsManage = null;

    public function index(Request $request)
    {
        $this->documentsManage;
    }

    /**
     * @Authorization 添加
     */
    public function add(Request $request)
    {
        if ('post' == $request->method()) {
            try {
                $this->doAdd($request);
            } catch (\Exception $e) {
                return json_encode(['status'=>'error', 'info'=>$e->getMessage()]);
            }
        } else {

        }
    }

    /**
     * @Authorization 修改
     */
    public function modify(Request $request, $id)
    {
        if ('post' == $request->method()) {
            try {
                $this->doModify($request, $id);
            } catch (\Exception $e) {
                return json_encode(['status'=>'error', 'info'=>$e->getMessage()]);
            }
        } else {

        }
    }

    /**
     * @Authorization 修改状态
     */
    public function modifyStatus(Request $request, $id)
    {

    }

    /**
     * @Authorization 审批
     */
    public function review(Request $request, $id)
    {

    }

    public function check(Request $request, $id)
    {

    }

    private function doAdd(Request $request)
    {

    }

    private function doModify(Request $request, $id)
    {

    }
}