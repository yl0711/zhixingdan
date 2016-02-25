<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 16/1/20
 * Time: 下午4:50
 */

namespace App\Http\Manage;

use App\Http\Model\liuchengdan\DocumentsModel;
use Exception;

class DocumentsManage
{
    private $documentModel = null;

    public function __construct()
    {
        $this->documentModel = new DocumentsModel();
    }

    /**
     * 根据项目名称获取项目列表
     *
     * @param strung $name
     * @param int $status
     * @return mixed
     */
    public function getList($name='', $company_id=0, $project_id=0, $status=2)
    {
        return $this->documentModel->getList($name, $company_id, $project_id, $status);
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

        return $this->documentModel->add($request);
    }

    public function modify(array $request)
    {
        if (!$this->documentModel->getOneById($request['id'])->toArray()) {
            throw new Exception('选择的单据不存在');
        }
        $id = $request['id'];

        $this->check_submit_data($request);

        unset($request['id']);

        try {
            $this->documentModel->modify($id, $request);
        } catch (Exception $e) {
            throw $e;
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
        if (empty($request['cate1']) || empty($request['cate2']) || empty($request['cate3'])) {
            throw new Exception('项目分类必须全部选择');
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