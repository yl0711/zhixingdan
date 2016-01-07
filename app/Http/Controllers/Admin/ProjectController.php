<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 15/12/30
 * Time: 下午12:04
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminBaseController;
use App\Http\Manage\CompanyManage;
use App\Http\Manage\ProjectManage;
use App\Http\Model\liuchengdan\CompanyModel;
use App\Http\Model\liuchengdan\ProjectModel;
use App\Http\Model\liuchengdan\UserModel;
use Illuminate\Http\Request;

class ProjectController extends AdminBaseController
{
    private $projectManage = null;

    public function __construct()
    {
        parent::__construct();

        $this->projectManage = new ProjectManage();
    }

    public function index()
    {
        $data = $this->projectManage->getList()->toArray();
        $project = $data['data'];

        $companyids = $pmids = $companyList = $pmList = [];
        foreach ($project as $value)
        {
            if ($value['ompany_id'])
            {
                $companyids[$value['ompany_id']] = $value['ompany_id'];
            }
            if ($value['pm_id'])
            {
                $pmids[$value['pm_id']] = $value['pm_id'];
            }
        }

        if ($companyids)
        {
            $companyModel = new CompanyModel();
            $data = $companyModel->getMoreById($companyids)->toArray();
            foreach ($data as $value)
            {
                $companyList[$value['id']] = $value;
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

        return view('admin.project_list', compact('project', 'companyList', 'pmList'));
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
            foreach ($companyList as &$value)
            {
                $value['selected'] = '';
            }
            return view('admin.project_add', compact('companyList'));
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
                $model = new ProjectModel();
                $project = $model->getOneById($id)->toArray()[0];

                $companyManage = new CompanyManage();
                $companyList = $companyManage->getAll()->toArray();
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
            catch (\Exception $e)
            {
                echo $e->getMessage();exit;
            }
            return view('admin.project_modify', compact('project', 'companyList'));
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
            $this->projectManage->add($request->all());
            return json_encode(['status'=>'success']);
        }
        catch (\Exception $e)
        {
            return json_encode(['status'=>'error', 'info'=>$e->getMessage()]);
        }
    }

    private function doModify(Request $request)
    {
        try {
            $this->projectManage->modify($request->all());
            return json_encode(['status'=>'success']);
        } catch (\Exception $e) {
            return json_encode(['status'=>'error', 'info'=>$e->getMessage()]);
        }
    }
}