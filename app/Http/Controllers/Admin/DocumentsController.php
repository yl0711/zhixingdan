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
use App\Http\Manage\CostManage;
use App\Http\Manage\DocumentsManage;
use App\Http\Manage\UploadManage;
use App\Http\Model\liuchengdan\AttachmentModel;
use App\Http\Model\liuchengdan\CategoryModel;
use App\Http\Model\liuchengdan\DocumentReviewModel;
use App\Http\Model\liuchengdan\DocumentsModel;
use Illuminate\Http\Request;
use Exception;
use View;
use PDF;
use Mail;

class DocumentsController extends AdminBaseController
{
    private $documentsManage = null;
    private $categoryModel = null;
    private $costManage = null;
    private $userManage = null;

    public function __construct()
    {
        parent::__construct();

        $this->documentsManage = new DocumentsManage();
        $this->categoryModel = new CategoryModel();
        $this->costManage = new CostManage();
        $this->userManage = new AdminUserManage();
    }

    public function index(Request $request)
    {
        $name = $request->input('name', '');
        $cate1 = $request->input('cate1', 0);
        $status = $request->input('status', 0);
        // 列表类型, list-我创建的, review-我审核的
        $type = $request->input('type', 'list');

        // 超级管理员和超级观察者用户可以查看所有执行单, 其他用户只能查看自己和其下属创建的执行单
        if (!$this->admin_user['superadmin'] && !$this->admin_user['superwatch']) {
            $branch_ids = $this->userManage->getBranchUser($this->admin_user['id']);
            array_push($branch_ids, $this->admin_user['id']);
        } else {
            $branch_ids = [];
        }

        $document = $this->documentsManage->getList($name, $cate1, $status, $branch_ids);

        $category = $this->categoryModel->getAll();
        $gongzuoleibie = [];
        foreach ($category as $value) {
            $value['selected'] = '';
            if ($value['type'] == 1) {
                if ($value['id'] == $cate1) {
                    $value['selected'] = 'selected="selected"';
                }
                $gongzuoleibie[$value['id']] = $value;
            }
        }

        $data = $this->adminUserManage->getAllUser();
        foreach ($data as $item) {
            $userList[$item['id']] = $item;
        }

        foreach ($document as $key=>$value) {
            if ($value['cate1']) {
                $tmp_id = explode(',', trim($value['cate1'], ','));
                $tpm_arr = [];
                foreach ($tmp_id as $id) {
                    $tpm_arr[] = $gongzuoleibie[$id]['name'];
                }
                if ($tpm_arr) {
                    $value['cate1'] = implode('; ', $tpm_arr);
                }
            } else {
                $value['cate1'] = '';
            }
        }

        return view('admin.document.list', compact('name', 'cate1', 'status', 'type', 'gongzuoleibie', 'document', 'userList'));
    }

    /**
     * @Authorization 添加
     */
    public function add(Request $request)
    {
        if (!$this->admin_user['department_id']) {
            abort(400, '用户没有部门, 不能创建执行单');
        }
        if ('POST' == $request->method()) {
            return $this->doAdd($request);
        } else {
            $issign_selected = ['', ''];

            $category = $this->categoryModel->getAll();
            $gongzuoleibie = [];
            foreach ($category as $value) {
                $value['selected'] = '';
                $value['checked'] = '';
                if ($value['type'] == 1) {
                    $gongzuoleibie[$value['id']] = $value;
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
            $docCost = [];

            return view('admin.document.add', compact('costList', 'docCost', 'userList', 'issign_selected', 'gongzuoleibie'));
        }
    }

    /**
     * @Authorization 修改
     */
    public function modify(Request $request, $id)
    {
        try {
            $document = $this->documentsManage->getOneById($id)->toArray()[0];
            if (!$document['old_id']) {
                $document['old_id'] = $document['id'];
            }
        } catch (Exception $e) {
            abort($e->getCode(), $e->getMessage());
        }

        $doc_cate1 = explode(',', trim($document['cate1'], ','));
        if ('POST' == $request->method()) {
            return $this->doModify($request, $id);
        } else {
            $issign_selected = ['', ''];
            $issign_selected[$document['issign']] = 'selected="selected"';

            $category = $this->categoryModel->getAll();
            $gongzuoleibie = [];
            foreach ($category as $value) {
                $value['checked'] = '';
                if ($value['type'] == 1) {
                    if (in_array($value['id'], $doc_cate1)) {
                        $value['checked'] = 'checked="checked"';
                    }
                    $gongzuoleibie[$value['id']] = $value;
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
                foreach ($costList_data as $value) {
                    $costList[$value['id']] = $value;
                }
            } else {
                $costList = [];
            }
            $docCost = $this->costManage->getDocStructureById($id)->toArray();
            $attach_ids = $attach_list = [];
            foreach ($docCost as $item) {
                if ($item['attach_id']) {
                    $attach_ids[] = $item['attach_id'];
                }
            }

            if ($attach_ids) {
                $attachment = AttachmentModel::whereIn('id', $attach_ids)->get();
                if ($attachment->count()) {
                    foreach ($attachment as $item) {
                        $attach_list[$item['id']] = $item['path'];
                    }
                }
            }

            return view('admin.document.modify', compact('document', 'costList', 'docCost', 'userList', 'attach_list', 'issign_selected', 'gongzuoleibie'));
        }
    }

    /**
     * @Authorization 修改状态
     */
    public function modifyStatus(Request $request, $id)
    {

    }

    /**
     * @Authorization 执行单审批
     */
    public function review(Request $request)
    {
        $id = intval($request->input('id', 0));
        $doc_id = intval($request->input('doc_id', 0));
        $type = intval($request->input('type', ''));
        $review_type = intval($request->input('review_type', 0));
        if (!$id || !$doc_id) {
            abort('400', '参数错误');
        }
        try {
            $document = $this->documentsManage->getOneById($doc_id)->toArray()[0];
        } catch (Exception $e) {
            abort($e->getCode(), $e->getMessage());
        }
        if ('mail' == $type){
            /**
             * @TODO 需要定义邮件格式
             */
        }

        if ('POST' == $request->method()) {
            $result = $this->doReview($request);
            echo "<script>parent.docmentsReviewCallback({$result})</script>";
        } else {
            return view('admin.document.module.reviewInfo', compact('id', 'doc_id', 'document', 'review_type'));
        }
    }

    /**
     * @Authorization 我审批的列表
     */
    public function myReviewList(Request $request)
    {
        $category = $this->categoryModel->getAll();
        $gongzuoleibie = [];
        foreach ($category as $value) {
            $value['selected'] = '';
            if ($value['type'] == 1) {
                $gongzuoleibie[$value['id']] = $value;
            }
        }

        $data = $this->adminUserManage->getAllUser();
        foreach ($data as $item) {
            $userList[$item['id']] = $item;
        }

        $return = $this->documentsManage->getDocReviewByUserID($this->admin_user['id']);
        $review = $return['review'];
        $cost = $return['cost'];

        $doc_cate1 = [];
        foreach ($review as $key=>$value) {
            $doc_cate1_str = '';
            if ($value['doc']['cate1']) {
                $tmp_id = explode(',', trim($value['doc']['cate1'], ','));
                $tpm_arr = [];
                foreach ($tmp_id as $id) {
                    $tpm_arr[] = $gongzuoleibie[$id]['name'];
                }
                if ($tpm_arr) {
                    $doc_cate1_str = implode('; ', $tpm_arr);
                }
            }
            $doc_cate1[$value['doc']['id']] = $doc_cate1_str;
        }

        return view('admin.document.review', compact('review', 'cost', 'userList', 'doc_cate1', 'gongzuoleibie'));
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

        return view('admin.document.process', compact('id', 'document', 'review', 'department', 'user', 'group', 'costlist'));
    }

    /**
     * @Authorization 预览
     */
    public function show($id)
    {
        try {
            $document = $this->documentsManage->getOneById($id)->toArray()[0];
        } catch (Exception $e) {
            abort($e->getCode(), $e->getMessage());
        }
        $document['cate1_name'] = '';
        $document['pm'] = '';
        $document['author'] = '';

        $doc_cate1 = explode(',', trim($document['cate1'], ','));

        $category = $this->categoryModel->getAll();
        $gongzuoleibie = [];
        foreach ($category as $value) {
            if ($value['type'] == 1) {
                if (in_array($value['id'], $doc_cate1)) {
                    $gongzuoleibie[] = $value['name'];
                }
            }
        }
        if ($gongzuoleibie) {
            $document['cate1_name'] = implode(', ', $gongzuoleibie);
        }

        $userList = $this->adminUserManage->getAllUser();
        if ($userList) {
            foreach ($userList as $value) {
                if ($value['id'] == $document['pm_id']) {
                    $document['pm'] = $value['name'];
                }
                if ($value['id'] == $document['author_id']) {
                    $document['author'] = $value['name'];
                }
            }
        }

        $costList_data = $this->costManage->getBaseAll()->toArray();
        if ($costList_data) {
            foreach ($costList_data as $value) {
                $costList[$value['id']] = $value;
            }
        } else {
            $costList = [];
        }
        $docCost = $this->costManage->getDocStructureById($id)->toArray();
        $attach_ids = $attach_list = [];
        foreach ($docCost as $item) {
            if ($item['attach_id']) {
                $attach_ids[] = $item['attach_id'];
            }
        }

        if ($attach_ids) {
            $attachment = AttachmentModel::whereIn('id', $attach_ids)->get();
            if ($attachment->count()) {
                foreach ($attachment as $item) {
                    $attach_list[$item['id']] = $item['path'];
                }
            }
        }

        return view('admin.document.show', compact('document', 'costList', 'docCost', 'attach_list'));
    }

    /**
     * @Authorization 修改记录
     */
    public function history($id)
    {
        try {
            $document = $this->documentsManage->getOneById($id)->toArray()[0];
        } catch (Exception $e) {
            abort($e->getCode(), $e->getMessage());
        }
        if ($document['old_id'] > 0) {
            $history = DocumentsModel::where(function($query) use($document){
                $query->where('old_id', $document['old_id'])->orWhere('id', $document['old_id']);
            })->where('id', '!=', $id)->get()->toArray();

            $category = $this->categoryModel->getAll();
            $gongzuoleibie = [];
            foreach ($category as $value) {
                if ($value['type'] == 1) {
                    $gongzuoleibie[$value['id']] = $value;
                }
            }

            $data = $this->adminUserManage->getAllUser();
            foreach ($data as $item) {
                $userList[$item['id']] = $item;
            }

            foreach ($history as $key=>$value) {
                if ($value['cate1']) {
                    $tmp_id = explode(',', trim($value['cate1'], ','));
                    $tpm_arr = [];
                    foreach ($tmp_id as $id) {
                        $tpm_arr[] = $gongzuoleibie[$id]['name'];
                    }
                    if ($tpm_arr) {
                        $history[$key]['cate1'] = implode('; ', $tpm_arr);
                    }
                } else {
                    $history[$key]['cate1'] = '';
                }
            }
        } else {
            $history = [];
        }
        return view('admin.document.history', compact('document', 'history', 'userList'));
    }

    /**
     * @Authorization 审批记录
     */
    public function reviewLog()
    {
        try {
            $document = $this->documentsManage->getOneById($id)->toArray()[0];
        } catch (Exception $e) {
            abort($e->getCode(), $e->getMessage());
        }
    }

    /**
     * @Authorization 下载
     */
    public function download($id)
    {
        try {
            $document = $this->documentsManage->getOneById($id)->toArray()[0];
        } catch (Exception $e) {
            abort($e->getCode(), $e->getMessage());
        }
        $document['cate1_name'] = '';
        $document['pm'] = '';
        $document['author'] = '';

        $doc_cate1 = explode(',', trim($document['cate1'], ','));

        $category = $this->categoryModel->getAll();
        $gongzuoleibie = [];
        foreach ($category as $value) {
            if ($value['type'] == 1) {
                if (in_array($value['id'], $doc_cate1)) {
                    $gongzuoleibie[] = $value['name'];
                }
            }
        }
        if ($gongzuoleibie) {
            $document['cate1_name'] = implode(', ', $gongzuoleibie);
        }

        $userList = $this->adminUserManage->getAllUser();
        if ($userList) {
            foreach ($userList as $value) {
                if ($value['id'] == $document['pm_id']) {
                    $document['pm'] = $value['name'];
                }
                if ($value['id'] == $document['author_id']) {
                    $document['author'] = $value['name'];
                }
            }
        }

        $costList_data = $this->costManage->getBaseAll()->toArray();
        if ($costList_data) {
            foreach ($costList_data as $value) {
                $costList[$value['id']] = $value;
            }
        } else {
            $costList = [];
        }
        $docCost = $this->costManage->getDocStructureById($id)->toArray();
        $attach_ids = $attach_list = [];
        foreach ($docCost as $item) {
            if ($item['attach_id']) {
                $attach_ids[] = $item['attach_id'];
            }
        }

        if ($attach_ids) {
            $attachment = AttachmentModel::whereIn('id', $attach_ids)->get();
            if ($attachment->count()) {
                foreach ($attachment as $item) {
                    $attach_list[$item['id']] = $item['path'];
                }
            }
        }
        //return view('admin.document.download', compact('document', 'costList', 'docCost', 'attach_list'));

        $html = View::make('admin.document.download', compact('document', 'costList', 'docCost', 'attach_list'));
        return $html;
        //return PDF::loadHTML($html, 'utf-8')->download('document.pdf');

    }

    /**
     * @Authorization 设置成本构成
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function docmentsCost(Request $request)
    {
        if ('POST' == $request->method()) {
            \Debugbar::disable();

            $data = $request->except(['s']);
            if (!$data['cost_id']) {
                $result = json_encode(['status'=>'error', 'info'=>'没有选择成本构成项']);
            } else {
                try {
                    $costInfo = $this->costManage->getBaseOneById($data['cost_id'])->toArray();
                    $uploadManage = new UploadManage();
                    $fileArr = $uploadManage->upload_file($request, 'attachment');

                    /*
                    $costManage = new CostManage();
                    $costManage->addDocStructure([
                        'cost_id' => $data['cost_select'],
                        'money' => $data['money'],
                        'intro' => $data['intro'],
                    ]);
                    */
                    $result = json_encode(['status'=>'success', 'info'=>'', 'data'=>[
                        'cost_id' => $data['cost_id'],
                        'cost_name' => $costInfo[0]['name'],
                        'money' => $data['money'],
                        'intro' => $data['intro'],
                        'attach' => $fileArr,
                    ]]);
                } catch (Exception $e) {
                    $result = json_encode(['status'=>'error', 'info'=>$e->getMessage()]);
                }
            }
            echo "<script>parent.docmentsCostCallback({$result})</script>";
        } else {
            $cost = $this->costManage->getBaseAll()->toArray();
            return view('admin.document.module.cost', compact('cost'));
        }
    }

    public function check(Request $request, $id)
    {

    }

    /**
     * @Authorization 复制
     */
    public function copy(Request $request, $id)
    {
        if ('POST' == $request->method()) {
            return $this->doAdd($request);
        } else {
            try {
                $document = $this->documentsManage->getOneById($id)->toArray()[0];
            } catch (Exception $e) {
                abort($e->getCode(), $e->getMessage());
            }
            $doc_cate1 = explode(',', trim($document['cate1'], ','));

            $issign_selected = ['', ''];
            $issign_selected[$document['issign']] = 'selected="selected"';

            $category = $this->categoryModel->getAll();
            $gongzuoleibie = [];
            foreach ($category as $value) {
                $value['checked'] = '';
                if ($value['type'] == 1) {
                    if (in_array($value['id'], $doc_cate1)) {
                        $value['checked'] = 'checked="checked"';
                    }
                    $gongzuoleibie[$value['id']] = $value;
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
                foreach ($costList_data as $value) {
                    $costList[$value['id']] = $value;
                }
            } else {
                $costList = [];
            }
            $docCost = $this->costManage->getDocStructureById($id)->toArray();
            $attach_ids = $attach_list = [];
            foreach ($docCost as $item) {
                if ($item['attach_id']) {
                    $attach_ids[] = $item['attach_id'];
                }
            }

            if ($attach_ids) {
                $attachment = AttachmentModel::whereIn('id', $attach_ids)->get();
                if ($attachment->count()) {
                    foreach ($attachment as $item) {
                        $attach_list[$item['id']] = $item['path'];
                    }
                }
            }

            return view('admin.document.copy', compact('document', 'costList', 'docCost', 'userList', 'attach_list', 'issign_selected', 'gongzuoleibie'));
        }
    }

    /**
     * 直接拒绝执行单
     *
     * @param Request $request
     * @param $id
     */
    public function reviewCancel(Request $request, $id)
    {
        try {
            $document = $this->documentsManage->getOneById($id)->toArray()[0];
        } catch (Exception $e) {
            abort($e->getCode(), $e->getMessage());
        }

        if ('POST' == $request->method()) {
            $result = $this->doReviewCancel($request);
            echo "<script>parent.docmentsReviewCancelCallback({$result})</script>";
        } else {
            return view('admin.document.module.reviewCancelInfo', compact('id'));
        }
    }

    private function doReviewCancel(Request $request)
    {
        try {
            $data = $request->except(['s']);
            $id = $data['id'];
            $intro = $data['intro'];

            /*
             * 审批表里有这个人, 直接更新此条记录, 如果没有此人则插入一条新的显示在最上面
             */
            $review = DocumentReviewModel::where(['document_id'=>$id, 'review_uid'=>$this->admin_user['id'], 'status'=>1])->get()->toArray();
            if ($review) {
                $reviewid = $review[0]['id'];
            } else {
                $reviewid = 0;
            }
            if ($reviewid) {
                $this->documentsManage->modifyDocReview($reviewid, $id, -2, $this->admin_user['id'], $intro, 1);
            } else {
                $list = DocumentReviewModel::where(['document_id'=>$id, 'status'=>1])->orderBy('level', 'desc')->get()->toArray();
                $addData = [
                    'document_id' => $id,
                    'level' => 1,
                    'review_uid' => $this->admin_user['id'],
                    'department_id' => $this->admin_user['department_id'],
                    'group_id' => $this->admin_user['group_id'],
                    'cost_id' => 0,
                    'intro' => $intro,
                    'isAdmin' => 1,
                    'status' => -2,
                    'real_review_uid' => $this->admin_user['id'],
                    'real_department_id' => $this->admin_user['department_id'],
                    'real_group_id' => $this->admin_user['group_id'],
                    'review_at' => date('Y-m-d H:i:s', config('global.REQUEST_TIME'))
                ];
                if ($list) {
                    $addData['level'] = $list[0]['level'];
                }
                DocumentReviewModel::create($addData);
            }

            return json_encode(['status'=>'success']);
        } catch (\Exception $e) {
            return json_encode(['status'=>'error', 'info'=>$e->getMessage()]);
        }
    }

    private function doAdd(Request $request)
    {
        try {
            $data = $request->except(['s']);
            $id = $this->documentsManage->add($data);
            $this->documentsManage->addDocReview($id);

            return json_encode(['status'=>'success']);
        } catch (\Exception $e) {
            return json_encode(['status'=>'error', 'info'=>$e->getMessage()]);
        }
    }

    private function doModify(Request $request)
    {
        try {
            $data = $request->except(['s']);
            $id = $this->documentsManage->modify($data);
            $this->documentsManage->addDocReview($id);
            return json_encode(['status'=>'success']);
        } catch (\Exception $e) {
            return json_encode(['status'=>'error', 'info'=>$e->getMessage()]);
        }
    }

    private function doReview(Request $request)
    {
        $id = intval($request->input('id', 0));
        $doc_id = intval($request->input('doc_id', 0));
        $review_type = $request->input('review_type', 0);
        $reiew_intro = $request->input('reiew_intro', '');

        if (2 != $review_type && -2 != $review_type) {
            return json_encode(['status'=>'error', 'code'=>400, 'info'=>'没有选择审批状态类型']);
        }
        try {
            $this->documentsManage->modifyDocReview($id, $doc_id, $review_type, $this->admin_user['id'], $reiew_intro);

            return json_encode(['status'=>'success']);
        } catch (\Exception $e) {
            return json_encode(['status'=>'error', 'code'=>$e->getCode(), 'info'=>$e->getMessage()]);
        }
    }

    /**
     * 发送审批提醒邮件
     * @param Request $request
     */
    private function reviewMail(Request $request)
    {
        $data = ['email'=>config('mail.from.address'), 'name'=>config('mail.from.name')];

        try {
            $document = $this->documentsManage->getOneById($request->input('doc_id'))->toArray()[0];
        } catch (Exception $e) {
            abort($e->getCode(), $e->getMessage());
        }
        $document['cate1_name'] = '';
        $document['pm'] = '';
        $document['author'] = '';

        $doc_cate1 = explode(',', trim($document['cate1'], ','));

        $category = $this->categoryModel->getAll();
        $gongzuoleibie = [];
        foreach ($category as $value) {
            if ($value['type'] == 1) {
                if (in_array($value['id'], $doc_cate1)) {
                    $gongzuoleibie[] = $value['name'];
                }
            }
        }
        if ($gongzuoleibie) {
            $document['cate1_name'] = implode(', ', $gongzuoleibie);
        }

        $userList = $this->adminUserManage->getAllUser();
        if ($userList) {
            foreach ($userList as $value) {
                if ($value['id'] == $document['pm_id']) {
                    $document['pm'] = $value['name'];
                }
                if ($value['id'] == $document['author_id']) {
                    $document['author'] = $value['name'];
                }
            }
        }

        $costList_data = $this->costManage->getBaseAll()->toArray();
        if ($costList_data) {
            foreach ($costList_data as $value) {
                $costList[$value['id']] = $value;
            }
        } else {
            $costList = [];
        }
        $docCost = $this->costManage->getDocStructureById($request->input('id'))->toArray();
        $attach_ids = $attach_list = [];
        foreach ($docCost as $item) {
            if ($item['attach_id']) {
                $attach_ids[] = $item['attach_id'];
            }
        }

        if ($attach_ids) {
            $attachment = AttachmentModel::whereIn('id', $attach_ids)->get();
            if ($attachment->count()) {
                foreach ($attachment as $item) {
                    $attach_list[$item['id']] = $item['path'];
                }
            }
        }

        $data['document'] = $document;
        $data['costList'] = $costList;
        $data['docCost'] = $docCost;
        $data['attach_list'] = $attach_list;
        $data['activationcode'] = '123456';

        Mail::send('email.document_review_mail', $data, function($message) use($data)
        {
            $message->to($data['email'], $data['name'])->subject('欢迎注册我们的网站，请激活您的账号！');
        });
    }
}