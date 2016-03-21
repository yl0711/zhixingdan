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
        // 列表类型, list-我创建的, review-我审核的
        $type = $request->input('type', 'list');

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

        return view('admin.document.list', compact('name', 'cate1', 'cate2', 'cate3', 'status', 'type',
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
            $issign_selected = ['', ''];

            $category = $this->categoryModel->getAll();
            $gongzuoleibie = $gongzuofenxiang = $gongzuoxiangmu = [];
            foreach ($category as $value) {
                $value['selected'] = '';
                $value['checked'] = '';
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
                    if ($value['id'] == $this->admin_user['id']) {
                        $value['pm_selected'] = 'selected="selected"';
                        $value['author_selected'] = 'selected="selected"';
                    } else {
                        $value['pm_selected'] = '';
                        $value['author_selected'] = '';
                    }
                }
            } else {
                $userList = [];
            }

            $costList = $this->costManage->getBaseAll()->toArray();
            if ($costList) {
                $costList_json = json_encode($costList);
            } else {
                $costList_json = json_encode([]);
            }
            $docCost = [];
            $docCost_json = json_encode([]);

            return view('admin.document.add', compact('costList', 'costList_json', 'docCost', 'docCost_json', 'userList', 'issign_selected', 'gongzuoleibie', 'gongzuofenxiang', 'gongzuoxiangmu'));
        }
    }

    /**
     * @Authorization 修改
     */
    public function modify(Request $request, $id)
    {
        try {
            $document = $this->documentsManage->getOneById($id)->toArray()[0];
        } catch (Exception $e) {
            abort($e->getCode(), $e->getMessage());
        }

        if ('POST' == $request->method()) {
            return $this->doModify($request, $id);
        } else {
            $issign_selected = ['', ''];
            $issign_selected[$document['issign']] = 'selected="selected"';

            $category = $this->categoryModel->getAll();
            $gongzuoleibie = $gongzuofenxiang = $gongzuoxiangmu = [];
            foreach ($category as $value) {
                $value['selected'] = '';
                $value['checked'] = '';
                switch ($value['type']) {
                    case '1':
                        if ($value['id'] == $document['cate1']) {
                            $value['selected'] = 'selected="selected"';
                            $value['checked'] = 'checked="checked"';
                        }
                        $gongzuoleibie[$value['id']] = $value;
                        break;
                    case '2':
                        if ($value['id'] == $document['cate2']) {
                            $value['selected'] = 'selected="selected"';
                            $value['checked'] = 'checked="checked"';
                        }
                        $gongzuofenxiang[$value['id']] = $value;
                        break;
                    case '3':
                        if ($value['id'] == $document['cate3']) {
                            $value['selected'] = 'selected="selected"';
                            $value['checked'] = 'checked="checked"';
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

            $costList_data = $this->costManage->getBaseAll()->toArray();
            if ($costList_data) {
                $costList_json = json_encode($costList_data);
                foreach ($costList_data as $value) {
                    $costList[$value['id']] = $value;
                }
            } else {
                $costList_json = json_encode([]);
                $costList = [];
            }
            $docCost = $this->costManage->getDocStructureById($id)->toArray();
            if ($docCost) {
                $docCost_json = json_encode($docCost);
            } else {
                $docCost_json = json_encode([]);
            }

            return view('admin.document.modify', compact('document', 'costList', 'costList_json', 'docCost', 'docCost_json', 'userList', 'issign_selected', 'gongzuoleibie', 'gongzuofenxiang', 'gongzuoxiangmu'));
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
    public function review(Request $request)
    {
        if ('POST' == $request->method()) {
            return $this->doReview($request);
        } else {
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

            $data = $this->adminUserManage->getAllUser();
            foreach ($data as $item) {
                $userList[$item['id']] = $item;
            }

            $return = $this->documentsManage->getDocReviewByUserID($this->admin_user['id']);
            //var_dump($return);
            $review = $return['review'];
            $cost = $return['cost'];

            return view('admin.document.review', compact('review', 'cost', 'userList', 'gongzuoleibie', 'gongzuofenxiang', 'gongzuoxiangmu'));
        }
    }

    /**
     * @Authorization 流程
     */
    public function process($id)
    {
        try {
            $document = $this->documentsManage->getOneById($id)->toArray()[0];
        } catch (Exception $e) {
            abort($e->getCode(), $e->getMessage());
        }
        $data = $this->documentsManage->getDocReviewByDocID($id);
        $review = $data['review'];
        $department = $data['department'];
        $user = $data['user'];
        $group = $data['group'];

        $data = $this->costManage->getBaseAll()->toArray();
        foreach ($data as $item) {
            $costlist[$item['id']] = $item;
        }

        return view('admin.document.process', compact('document', 'review', 'department', 'user', 'group', 'costlist'));
    }

    public function check(Request $request, $id)
    {

    }

    private function doAdd(Request $request)
    {
        try {
            $id = $this->documentsManage->add($request->all());
            $this->documentsManage->addDocReview($id);

            return json_encode(['status'=>'success']);
        } catch (\Exception $e) {
            return json_encode(['status'=>'error', 'info'=>$e->getMessage()]);
        }
    }

    private function doModify(Request $request)
    {
        try {
            $id = $this->documentsManage->modify($request->all());
            $this->documentsManage->addDocReview($request->all()['id']);
            return json_encode(['status'=>'success']);
        } catch (\Exception $e) {
            return json_encode(['status'=>'error', 'info'=>$e->getMessage()]);
        }
    }

    private function doReview(Request $request)
    {
        $data = $request->all();
        try {
            $this->documentsManage->modifyDocReview($data['id'], $data['doc_id'], $data['type']);
            return json_encode(['status'=>'success']);
        } catch (\Exception $e) {
            return json_encode(['status'=>'error', 'info'=>$e->getMessage()]);
        }

    }
}