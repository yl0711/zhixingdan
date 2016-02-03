<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 16/1/18
 * Time: 下午6:17
 */

namespace app\Http\Controllers\Admin;

use App\Http\Controllers\AdminBaseController;
use App\Http\Manage\CompanyManage;
use App\Http\Manage\DocumentsManage;
use App\Http\Manage\ProjectManage;
use Illuminate\Http\Request;

class DocumentsController extends AdminBaseController
{
    private $documentsManage = null;

    public function __construct()
    {
        parent::__construct();

        $this->documentsManage = new DocumentsManage();
    }

    public function index(Request $request)
    {
        $name = $request->input('name', '');
        $company_id = $request->input('company_id', 0);
        $project_id = $request->input('project_id', 0);
        $status = $request->input('status', 2);

        $this->documentsManage;

        $companyManage = new CompanyManage();
        $companyList = $companyManage->getAll();
        foreach ($companyList as $item) {
            if ($item['id'] == $company_id) {
                $item['selected'] = 'selected="selected"';
            } else {
                $item['selected'] = '';
            }
        }

        $projectManage = new ProjectManage();
        $projectList = $projectManage->getAll();
        foreach ($projectList as $item) {
            if ($item['id'] == $project_id) {
                $item['selected'] = 'selected="selected"';
            } else {
                $item['selected'] = '';
            }
        }

        return view('admin.document_list', compact('name', 'company_id', 'project_id', 'status', 'companyList'));
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