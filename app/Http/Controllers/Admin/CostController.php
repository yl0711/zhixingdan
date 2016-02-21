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

    public function index()
    {

        return view('admin.costList');
    }

    /**
     * @Authorization 添加
     */
    public function add(Request $request)
    {

    }

    /**
     * @Authorization 修改
     */
    public function modify(Request $request, $id)
    {

    }

    /**
     * @Authorization 状态变更
     */
    public function modifyStatus(Request $request, $id)
    {

    }
}