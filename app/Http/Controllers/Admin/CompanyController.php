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
use App\Http\Model\liuchengdan\CompanyModel;
use Illuminate\Http\Request;

class CompanyController extends AdminBaseController
{
    private $companyManage = null;

    public function __construct()
    {
        parent::__construct();

        $this->companyManage = new CompanyManage();
    }

    public function index()
    {
        $data = $this->companyManage->getList()->toArray();
        $companyList = $data['data'];

        return view('admin.company_list', compact('companyList'));
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
            return view('admin.company_add');
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
                $model = new CompanyModel();
                $company = $model->getOneById($id)->toArray()[0];
            }
            catch (\Exception $e)
            {
                echo $e->getMessage();exit;
            }
            return view('admin.company_modify', compact('company'));
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
            $this->companyManage->add($request->all());
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
            $this->companyManage->modify($request->all());
            return json_encode(['status'=>'success']);
        } catch (\Exception $e) {
            return json_encode(['status'=>'error', 'info'=>$e->getMessage()]);
        }
    }

}