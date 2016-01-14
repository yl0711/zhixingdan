<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 15/12/30
 * Time: 下午12:04
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminBaseController;
use App\Http\Manage\AdminUserManage;
use App\Http\Manage\CompanyManage;
use App\Http\Manage\ProjectManage;
use App\Http\Model\liuchengdan\CompanyModel;
use App\Http\Model\liuchengdan\UserModel;
use Illuminate\Http\Request;
use \Symfony\Component\HttpKernel\Exception\HttpException;

class ProjectController extends AdminBaseController
{
    private $projectManage = null;

    public function __construct()
    {
        parent::__construct();

        $this->projectManage = new ProjectManage();
    }

    public function index(Request $request)
    {
        $name = $request->input('name', '');
        $company_id = $request->input('company_id', 0);
        $status = $request->input('status', 2);
        $project = $this->projectManage->getList($name, $company_id, $status);

        $pmids = $pmList = [];
        foreach ($project as $value)
        {
            if ($value['pm_id'])
            {
                $pmids[$value['pm_id']] = $value['pm_id'];
            }
        }
        if ($pmids)
        {
            $userModel = new UserModel();
            $data = $userModel->getByUid($pmids);
            foreach ($data as $value)
            {
                $pmList[$value['id']] = $value;
            }
        }

        $companyModel = new CompanyModel();
        $data = $companyModel->getAll();
        foreach ($data as $value)
        {
            if ($value['id'] == $company_id)
            {
                $value['selected'] = 'selected="selected"';
            }
            else
            {
                $value['selected'] = '';
            }
            $companyList[$value['id']] = $value;
        }

        return view('admin.project_list', compact('project', 'companyList', 'pmList', 'name', 'company_id', 'status'));
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
            $companyManage = new CompanyManage();
            $companyList = $companyManage->getAll()->toArray();
            if ($companyList)
            {
                foreach ($companyList as &$value)
                {
                    $value['selected'] = '';
                }
            }
            else
            {
                $companyList = [];
            }

            $adminUserManage = new AdminUserManage();
            $userList = $adminUserManage->getAllUser();
            if ($userList)
            {
                foreach ($userList as &$value)
                {
                    $value['selected'] = '';
                }
            }
            else
            {
                $userList = [];
            }

            return view('admin.project_add', compact('companyList', 'userList'));
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
            return $this->viewModify($id);
        }
    }

    /**
     * @Authorization 成员管理
     *
     * @param Request $request
     * @param $id
     */
    public function member($id)
    {
        try
        {
            $project = $this->projectManage->getOneById($id)->toArray()[0];
        }
        catch (HttpException $e)
        {
            abort('404', $e->getMessage(), ['HTTP_REFERER'=>url('project/index')]);
        }
        if (0 == $project['status'])
        {
            abort('404', '你访问的项目已经关闭, 请先启动再设置', ['HTTP_REFERER'=>url('project/index')]);
        }
        $data = $this->projectManage->getMemberListByProjectid($id);
        $memberList = $data['memberList'];
        $userList = $data['userList'];

        return view('admin.project_member_list', compact('project', 'memberList', 'userList'));
    }

    /**
     * @Authorization 添加成员
     *
     * @param Request $request
     * @param $id
     */
    public function addMember(Request $request, $id)
    {
        if ('POST' == $request->getMethod())
        {
            return $this->doAddMember($request, $id);
        }
        else
        {
            try
            {
                $project = $this->projectManage->getOneById($id)->toArray()[0];
            }
            catch (HttpException $e)
            {
                abort($e->getStatusCode(), $e->getMessage());
            }
            return view('admin.project_member_add', compact('project'));
        }
    }

    /**
     * @Authorization 修改状态
     */
    public function modifyStatus($id)
    {
        try {
            $data = $this->projectManage->setStatus($id);
            echo json_encode(['status'=>'success', 'data'=>$data]);
        } catch (\Exception $e) {
            echo json_encode(['status'=>'error', 'info'=>$e->getMessage()]);
        }
    }

    private function doAdd(Request $request)
    {
        try
        {
            $this->projectManage->add($request->all());
            return json_encode(['status'=>'success']);
        }
        catch (\Exception $e)
        {
            return json_encode(['status'=>'error', 'info'=>$e->getMessage()]);
        }
    }

    private function viewModify($id)
    {
        try
        {
            $project = $this->projectManage->getOneById($id)->toArray()[0];
        }
        catch (HttpException $e)
        {
            abort($e->getStatusCode(), $e->getMessage());
        }

        $companyManage = new CompanyManage();
        $companyList = $companyManage->getAll()->toArray();
        if ($companyList)
        {
            foreach ($companyList as &$value)
            {
                if ($value['id'] == $project['company_id'])
                {
                    $value['selected'] = 'selected="selected"';
                }
                else
                {
                    $value['selected'] = '';
                }
            }
        }
        else
        {
            $companyList = [];
        }

        $adminUserManage = new AdminUserManage();
        $userList = $adminUserManage->getAllUser();
        if ($userList)
        {
            foreach ($userList as &$value)
            {
                if ($value['id'] == $project['pm_id'])
                {
                    $value['selected'] = 'selected="selected"';
                }
                else
                {
                    $value['selected'] = '';
                }
            }
        }
        else
        {
            $userList = [];
        }
        return view('admin.project_modify', compact('project', 'companyList', 'userList'));
    }

    private function doModify(Request $request)
    {
        try
        {
            $this->projectManage->modify($request->all());
            return json_encode(['status'=>'success']);
        }
        catch (\Exception $e)
        {
            return json_encode(['status'=>'error', 'info'=>$e->getMessage()]);
        }
    }

    private function doAddMember(Request $request, $id)
    {

    }
}