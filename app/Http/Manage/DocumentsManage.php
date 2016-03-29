<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 16/1/20
 * Time: 下午4:50
 */

namespace App\Http\Manage;

use App\Http\Model\liuchengdan\AttachmentModel;
use App\Http\Model\liuchengdan\DepartmentModel;
use App\Http\Model\liuchengdan\DocumentModifyLogModel;
use App\Http\Model\liuchengdan\DocumentReviewModel;
use App\Http\Model\liuchengdan\DocumentsModel;
use App\Http\Model\liuchengdan\GroupModel;
use App\Http\Model\liuchengdan\UserModel;
use Exception;

class DocumentsManage
{
    private $documentModel = null;
    private $costManage = null;
    private $docReviewModel = null;
    private $docModifyLogModel = null;
    private $attachmentModel = null;

    public function __construct()
    {
        $this->documentModel = new DocumentsModel();
        $this->costManage = new CostManage();
        $this->docReviewModel = new DocumentReviewModel();
        $this->docModifyLogModel = new DocumentModifyLogModel();
        $this->attachmentModel = new AttachmentModel();
    }

    /**
     * 根据项目名称获取项目列表
     *
     * @param strung $name
     * @param int $status
     * @return mixed
     */
    public function getList($name='', $cate1=0, $status=2)
    {
        return $this->documentModel->getList($name, $cate1, $status);
    }

    public function getAll()
    {
        return $this->documentModel->getAll();
    }

    /**
     * 根据项目ID获取单个项目信息
     *
     * @param int $id
     * @param int $status
     */
    public function getOneById($id, $status=2)
    {
        $data = $this->documentModel->getOneById($id, $status);

        if (empty($data->toArray())) {
            throw new Exception('你所访问的内容不存在', '404');
        }
        return $data;
    }

    public function add(array $request)
    {
        $this->check_submit_data($request);

        $cost_select = $request['cost_select'];
        $cost_intro = $request['cost_intro'];
        $cost_money = $request['cost_money'];
        $cost_attach = $request['cost_attach'];
        $request['cate1'] = ',' . implode(',', $request['cate1']) . ',';

        unset($request['cost_select'], $request['cost_intro'], $request['cost_money'], $request['cost_attach']);

        try {
            $id = $this->documentModel->add($request);
            $cost_num = 0;
            foreach ($cost_select as $key=>$value) {
                if (0 == $value) continue;

                $cost = $this->costManage->getBaseOneById($value)->toArray()[0];
                if ($cost['review_user']) {
                    $review_user = $cost['review_user'];
                    $review = 0;
                } else {
                    $review_user = 0;
                    $review = 1;
                }
                $data = [
                    'document_id' => $id,
                    'cost_id' => $value,
                    'attach_id' => $cost_attach[$key],
                    'money' => $cost_money[$key],
                    'intro' => $cost_intro[$key],
                    'review' => $review,
                    'review_user' => $review_user
                ];
                if ($cost_money[$key] > 0) $cost_num += $cost_money[$key];
                $document_cost_id = $this->costManage->addDocStructure($data);

                if ($cost_attach[$key]) {
                    $this->attachmentModel->modify($cost_attach[$key], [
                        'document_cost_id' => $document_cost_id,
                        'document_id' => $id,
                        'cost_id' => $value,
                    ]);
                }
            }

            if ($cost_num > 0) {
                $this->documentModel->modify($id, ['cost_num'=>$cost_num]);
            }

            return $id;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 编辑执行单, 编辑方式为原执行单作废生成一个新的, 在修改日志里添加记录将新旧执行单关联便于查询
     *
     * @param array $request
     * @return mixed
     * @throws Exception
     */
    public function modify(array $request)
    {
        if (!$this->documentModel->getOneById($request['id'])->toArray()) {
            throw new Exception('选择的单据不存在');
        }
        $old_id = $request['id'];
        unset($request['id']);

        try {
            $id = $this->add($request);
            // 旧执行单设为作废
            $this->documentModel->modify($old_id, ['status' => 0]);
            // 修改记录中关联到此执行单的都重新关联到新的
            $this->docModifyLogModel->modify(['new_id' => $old_id], ['new_id' => $id]);
            // 添加新的修改记录
            $this->docModifyLogModel->add(['old_id' => $old_id, 'new_id' => $id, 'created_uid' => $request['created_uid']]);
            return $id;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getDocReviewByDocID($docId)
    {
        $review_uid = $department_id = $group_id = $return = [];

        $return['review'] = $return['user'] = $return['department'] = $return['group'] = [];

        $return['review'] = $this->docReviewModel->getListByDocID($docId);
        foreach ($return['review'] as $item) {
            $item->review_uid && $review_uid[] = $item->review_uid;
        }

        if ($review_uid) {
            $data = UserModel::whereIn('id', $review_uid)->get();
            foreach ($data as $item) {
                $return['user'][$item['id']] = $item;
                $item->department_id && $department_id[] = $item->department_id;
                $item->group_id && $group_id[] = $item->group_id;
            }
        }

        if ($department_id) {
            $data = DepartmentModel::whereIn('id', $department_id)->get();
            foreach ($data as $item) {
                $return['department'][$item['id']] = $item;
            }
        }

        if ($group_id) {
            $data = GroupModel::whereIn('id', $group_id)->get();
            foreach ($data as $item) {
                $return['group'][$item['id']] = $item;
            }
        }

        return $return;
    }

    public function getDocReviewByUserID($userid)
    {
        $cost_id = $return = [];

        $return['review'] = $return['cost'] = [];

        $return['review'] = $this->docReviewModel->getListByUserID($userid);
        foreach ($return['review'] as $item) {
            // 审批状态，1通过，-1拒绝，0待审
            $item->pre_status = 1;
            $item->now_review_uid = $userid;
            if (0 == $item->status) {
                $docList = $this->docReviewModel->getListByDocID($item->document_id);
                foreach ($docList as $item1) {
                    if ($item1->level < $item->level && 1 != $item1->status) {
                        $item->pre_status = $item1->status;
                        $item->now_review_uid = $item1->review_uid;
                        break;
                    }
                }
            }
            $item->doc = $this->getOneById($item->document_id)->toArray()[0];
            $item->cost_id && $cost_id[] = $item->cost_id;
        }

        if ($cost_id) {
            $data = $this->costManage->getBaseMoreById($cost_id);
            foreach ($data as $item) {
                $return['cost'][$item['id']] = $item;
            }
        }

        return $return;
    }

    /**
     * 添加执行单审批记录
     *
     * @param $id
     */
    public function addDocReview($docId)
    {
        $cost_review_user = $review_user = $review_group = [];
        $doc = $this->getOneById($docId)->toArray()[0];

        // 计算成本和费用比例, 根据此比例设置不同的审批级别
        $ratio = ($doc['cost_num'] / $doc['money']) * 100;
        if ($ratio > 40) {
            $review_group = 4;
        } else if ($ratio > 30) {
            $review_group = 3;
        } else if ($ratio > 20) {
            $review_group = 2;
        } else {
            $review_group = 1;
        }
        // 成本构成项是否有专门审批人
        $cost = $this->costManage->getDocStructureById($docId)->toArray();
        foreach ($cost as $value) {
            if ($value['review_user']) {
                $cost_review_user[] = [
                    'review_user' => $value['review_user'],
                    'cost_id' => $value['cost_id']
                ];
            }
        }
        // 创建人用户组, 便于设置审批上级
        $user = UserModel::where('id', $doc['created_uid'])->get()->toArray()[0];

        $cost_review_level = 0;
        $level = 1;
        // 根据审批上级, 循环生成审批人
        while ($user['group_id'] < $review_group && $user['parent_user'] > 0) {
            $user = UserModel::where('id', $user['parent_user'])->get()->toArray()[0];
            if ($user['group_id'] == 4) {
                $cost_review_level = $level;
                $level++;
            }
            $review_user[] = [
                'document_id' => $docId,
                'level' => $level,
                'review_uid' => $user['id'],
                'department_id' => $user['department_id'],
                'group_id' => $user['group_id'],
                'cost_id' => 0,
            ];
            $level++;
        }
        // 将成本构成项的审批人加入其中
        if (!$cost_review_level) $cost_review_level = $level;
        if ($cost_review_user) {
            foreach ($cost_review_user as $value) {
                $user = UserModel::where('id', $value['review_user'])->get()->toArray()[0];
                $review_user[] = [
                    'document_id' => $docId,
                    'level' => $cost_review_level,
                    'review_uid' => $user['id'],
                    'department_id' => $user['department_id'],
                    'group_id' => $user['group_id'],
                    'cost_id' => $value['cost_id'],
                ];
            }
        }
        // 入库
        if ($review_user) {
            foreach ($review_user as $value) {
                $this->docReviewModel->add($value);
            }
        }
        return;
    }

    public function modifyDocReview($id, $docId, $review_type)
    {
        if ('ok' == $review_type) {
            return DocumentReviewModel::where('id', $id)->where('document_id', $docId)->update(['status'=>1]);
        } else if ('cancel' == $review_type) {
            return DocumentReviewModel::where('id', $id)->where('document_id', $docId)->update(['status'=>0]);
        } else {
            throw new Exception('审批操作类型错误');
        }
    }


    public function setStatus($id)
    {
        try {
            $data = [];
            $project = $this->documentModel->getOneById($id)->toArray()[0];
            $data['status'] = abs(1 - $project['status']);
            $this->documentModel->modify($id, $data);
            return $data['status'];
        } catch (Exception $e) {
            throw $e;
        }
    }

    private function check_submit_data($request)
    {
        if (empty($request['cate1'])) {
            throw new Exception('项目分类没有选择');
        }
        if (empty($request['company_name'])) {
            throw new Exception('请填写客户名称');
        }
        if (empty($request['project_name'])) {
            throw new Exception('请填写项目名称');
        }
        if (empty($request['starttime'])) {
            throw new Exception('请设置项目开始日期');
        }
        if (empty($request['endtime'])) {
            throw new Exception('请设置项目结束日期');
        }
        if (empty($request['pm_id'])) {
            throw new Exception('请选择项目负责人');
        }
        if (empty($request['money'])) {
            throw new Exception('请填写金额');
        }
        if (!is_numeric($request['money'])) {
            throw new Exception('金额应为数字');
        }
        if (empty($request['author_id'])) {
            throw new Exception('请选择项目对接人');
        }
        if (empty($request['moneytime'])) {
            throw new Exception('请设置项目回款日期');
        }
    }
}