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
use App\Http\Manage\UploadManage;
use App\Http\Model\liuchengdan\AttachmentModel;
use App\Http\Model\liuchengdan\CategoryModel;
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
        $status = $request->input('status', 2);
        // 列表类型, list-我创建的, review-我审核的
        $type = $request->input('type', 'list');

        $document = $this->documentsManage->getList($name, $cate1, $status);

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
     * @Authorization 审批
     */
    public function review(Request $request)
    {
        if ('POST' == $request->method()) {
            return $this->doReview($request);
        } else {
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
     * @Authorization 预览
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

        $html = View::make('admin.document.show', compact('document', 'costList', 'docCost', 'attach_list'));
        //return $html;
        return PDF::loadHTML($html, 'UTF-8')->download('document.pdf');
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
            $this->documentsManage->modify($data);
            $this->documentsManage->addDocReview($data['id']);
            return json_encode(['status'=>'success']);
        } catch (\Exception $e) {
            return json_encode(['status'=>'error', 'info'=>$e->getMessage()]);
        }
    }

    private function doReview(Request $request)
    {
        $data = $request->except(['s']);
        try {
            if ('mail' == $data['type']) {
                $this->reviewMail($request);
            } else {
                $this->documentsManage->modifyDocReview($data['id'], $data['doc_id'], $data['type']);
            }
            return json_encode(['status'=>'success']);
        } catch (\Exception $e) {
            return json_encode(['status'=>'error', 'info'=>$e->getMessage()]);
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