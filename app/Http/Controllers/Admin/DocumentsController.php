<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 16/1/18
 * Time: 下午6:17
 */

namespace app\Http\Controllers\Admin;

use App\Http\Controllers\AdminBaseController;
use App\Http\Manage\CostManage;
use App\Http\Manage\DocumentsManage;
use App\Http\Model\liuchengdan\CategoryModel;
use Illuminate\Http\Request;
use Exception;

class DocumentsController extends AdminBaseController
{
    private $documentsManage = null;
    private $categoryModel = null;
    private $costManage = null;

    public function __construct()
    {
        parent::__construct();

        $this->documentsManage = new DocumentsManage();
        $this->categoryModel = new CategoryModel();
        $this->costManage = new CostManage();
    }

    public function index(Request $request)
    {
        $name = $request->input('name', '');
        $cate1 = $request->input('cate1', 0);
        $cate2 = $request->input('cate2', 0);
        $cate3 = $request->input('cate3', 0);
        $status = $request->input('status', 2);

        $document = $this->documentsManage->getList($name, $cate1, $cate2, $cate3, $status);

        $category = $this->categoryModel->getAll();
        $gongzuoleibie = $gongzuofenxiang = $gongzuoxiangmu = [];
        foreach ($category as $value) {
            $value['selected'] = '';
            switch ($value['type']) {
                case '1':
                    if ($value['id'] == $cate1) {
                        $value['selected'] = 'selected="selected"';
                    }
                    $gongzuoleibie[$value['id']] = $value;
                    break;
                case '2':
                    if ($value['id'] == $cate2) {
                        $value['selected'] = 'selected="selected"';
                    }
                    $gongzuofenxiang[$value['id']] = $value;
                    break;
                case '3':
                    if ($value['id'] == $cate3) {
                        $value['selected'] = 'selected="selected"';
                    }
                    $gongzuoxiangmu[$value['id']] = $value;
                    break;
            }
        }

        $data = $this->adminUserManage->getAllUser();
        foreach ($data as $item) {
            $userList[$item['id']] = $item;
        }

        return view('admin.document.list', compact('name', 'cate1', 'cate2', 'cate3', 'status',
            'gongzuoleibie', 'gongzuofenxiang', 'gongzuoxiangmu', 'document', 'userList'));
    }

    /**
     * @Authorization 添加
     */
    public function add(Request $request)
    {
        if ('POST' == $request->method()) {
            return $this->doAdd($request);
        } else {
            $status_selected = ['', ''];

            $category = $this->categoryModel->getAll();
            $gongzuoleibie = $gongzuofenxiang = $gongzuoxiangmu = [];
            foreach ($category as $value) {
                $value['selected'] = '';
                switch ($value['type']) {
                    case '1':
                        $gongzuoleibie[$value['id']] = $value;
                        break;
                    case '2':
                        $gongzuofenxiang[$value['id']] = $value;
                        break;
                    case '3':
                        $gongzuoxiangmu[$value['id']] = $value;
                        break;
                }
            }

            $userList = $this->adminUserManage->getAllUser();
            if ($userList) {
                foreach ($userList as $value) {
                    $value['pm_selected'] = '';
                    $value['author_selected'] = '';
                }
            } else {
                $userList = [];
            }

            $costList = $this->costManage->getBaseList()->toArray();
            if ($costList) {
                $costList = $costList['data'];
            }
            var_dump(json_encode($costList));

            return view('admin.document.add', compact('costList', 'userList', 'status_selected', 'gongzuoleibie', 'gongzuofenxiang', 'gongzuoxiangmu'));
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
                $document = $this->documentsManage->getOneById($id)->toArray()[0];
            } catch (Exception $e) {
                abort($e->getCode(), $e->getMessage());
            }

            $status_selected = ['', ''];
            $status_selected[$document['status']] = 'selected="selected"';

            $category = $this->categoryModel->getAll();
            $gongzuoleibie = $gongzuofenxiang = $gongzuoxiangmu = [];
            foreach ($category as $value) {
                $value['selected'] = '';
                switch ($value['type']) {
                    case '1':
                        if ($value['id'] == $document['cate1']) {
                            $value['selected'] = 'selected="selected"';
                        }
                        $gongzuoleibie[$value['id']] = $value;
                        break;
                    case '2':
                        if ($value['id'] == $document['cate2']) {
                            $value['selected'] = 'selected="selected"';
                        }
                        $gongzuofenxiang[$value['id']] = $value;
                        break;
                    case '3':
                        if ($value['id'] == $document['cate3']) {
                            $value['selected'] = 'selected="selected"';
                        }
                        $gongzuoxiangmu[$value['id']] = $value;
                        break;
                }
            }

            $userList = $this->adminUserManage->getAllUser();
            if ($userList) {
                foreach ($userList as $value) {
                    if ($value['id'] == $document['pm_id']) {
                        $value['pm_selected'] = 'selected="selected"';
                    } else {
                        $value['pm_selected'] = '';
                    }
                    if ($value['id'] == $document['author_id']) {
                        $value['author_selected'] = 'selected="selected"';
                    } else {
                        $value['author_selected'] = '';
                    }
                }
            } else {
                $userList = [];
            }

            return view('admin.document.modify', compact('document', 'userList', 'status_selected', 'gongzuoleibie', 'gongzuofenxiang', 'gongzuoxiangmu'));
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
        try
        {
            $this->documentsManage->modify($request->all());
            return json_encode(['status'=>'success']);
        }
        catch (\Exception $e)
        {
            return json_encode(['status'=>'error', 'info'=>$e->getMessage()]);
        }
    }
}