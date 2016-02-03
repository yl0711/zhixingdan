<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 16/1/18
 * Time: 下午6:17
 */

namespace app\Http\Controllers\Admin;

use App\Http\Controllers\AdminBaseController;
use App\Http\Manage\AdminUserManage;
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

        $document = $this->documentsManage->getList($name, $company_id, $project_id, $status);

        $companyManage = new CompanyManage();
        $data = $companyManage->getAll();
        foreach ($data as $item) {
            if ($item['id'] == $company_id) {
                $item['selected'] = 'selected="selected"';
            } else {
                $item['selected'] = '';
            }
            $companyList[$item['id']] = $item;
        }

        $projectManage = new ProjectManage();
        $data = $projectManage->getAll();
        foreach ($data as $item) {
            if ($item['id'] == $project_id) {
                $item['selected'] = 'selected="selected"';
            } else {
                $item['selected'] = '';
            }
            $projectList[$item['id']] = $item;
        }

        $adminUserManage = new AdminUserManage();
        $data = $adminUserManage->getAllUser();
        foreach ($data as $item) {
            $userList[$item['id']] = $item;
        }

        return view('admin.document_list', compact('name', 'company_id', 'project_id', 'status', 'document',
            'companyList', 'projectList', 'userList'));
    }

    /**
     * @Authorization 添加
     */
    public function add(Request $request)
    {
        if ('POST' == $request->method()) {
            return $this->doAdd($request);
        } else {
            $companyManage = new CompanyManage();
            $companyList = $companyManage->getAll();
            foreach ($companyList as $item) {
                $item['selected'] = '';
            }

            $projectManage = new ProjectManage();
            $projectList = $projectManage->getAll();
            foreach ($projectList as $item) {
                $item['selected'] = '';
            }

            return view('admin.document_add', compact('companyList', 'projectList'));
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
            return view('admin.document_modify');
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

    /**
     * @Authorization 流程
     */
    public function process($id)
    {

    }

    public function check(Request $request, $id)
    {

    }

    private function doAdd(Request $request)
    {
        try
        {
            $this->documentsManage->add($request->all());
            return json_encode(['status'=>'success']);
        }
        catch (\Exception $e)
        {
            return json_encode(['status'=>'error', 'info'=>$e->getMessage()]);
        }
    }

    private function doModify(Request $request, $id)
    {

    }
}