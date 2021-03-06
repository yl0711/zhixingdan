<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 15/12/31
 * Time: 上午11:23
 */

namespace App\Http\Manage;

use App\Http\Model\liuchengdan\ProjectMemberModel;
use App\Http\Model\liuchengdan\ProjectModel;
use Exception;
use \Symfony\Component\HttpKernel\Exception\HttpException;

class ProjectManage
{
    private $projectModel = null;

    public function __construct()
    {
        $this->projectModel = new ProjectModel();
    }

    /**
     * 根据项目名称获取项目列表
     *
     * @param strung $name
     * @param int $status
     * @return mixed
     */
    public function getList($name='', $company_id=0, $status=2)
    {
        return $this->projectModel->getList($name, $company_id, $status);
    }

    public function getAll()
    {
        return $this->projectModel->getAll();
    }

    /**
     * 根据项目ID获取单个项目信息
     *
     * @param int $id
     * @param int $status
     */
    public function getOneById($id, $status=1)
    {
        $data = $this->projectModel->getOneById($id, $status);
        if (empty($data->toArray()))
        {
            throw new HttpException('404', '你所访问的项目不存在');
        }
        return $data;
    }

    public function add(array $request)
    {
        if (!isset($request['name']) || empty($request['name'])) {
            throw new Exception('请填写项目名称');
        }
        if (!empty($this->projectModel->getOneByName($request['name'])->toArray())) {
            throw new Exception('项目 ' . $request['name'] . ' 已经存在');
        }
        $request['starttime'] = strtotime($request['starttime']);
        $request['endtime'] = strtotime($request['endtime']);

        return $this->projectModel->add($request);
    }

    /**
     * @param $project_id
     * @param $user_id
     * @param int $pm
     */
    public function addMember($project_id, $user_id, $pm=0)
    {
        $projectMemberModel = new ProjectMemberModel();
        try {
            return $projectMemberModel->addMember($project_id, $user_id, $pm);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function modify(array $request)
    {
        if (!$this->projectModel->getOneById($request['id'])->toArray())
        {
            throw new Exception('项目不存在');
        }
        $id = $request['id'];

        if (!isset($request['name']) || empty($request['name'])) {
            throw new Exception('请填写项目名称');
        }
        if ($request['name'] != $request['oldname'] && !empty($this->projectModel->getOneByName($request['name'])->toArray())) {
            throw new Exception('项目 ' . $request['name'] . ' 已经存在');
        }
        unset($request['id']);
        unset($request['oldname']);

        try {
            $this->projectModel->modify($id, $request);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @param $id
     * @return array
     */
    public function getMemberListByProjectid($id)
    {
        $model = new ProjectMemberModel();
        $memberList = $model->getMemberListByProjectid($id);

        $userList = [];
        if ($memberList) {
            $userids = [];
            foreach ($memberList as $value) {
                $userids[$value['user_id']] = $value['user_id'];
            }
            if ($userids) {
                $manage = new AdminUserManage();
                try {
                    $data = $manage->getUser($userids)->toArray();
                    foreach ($data as $value) {
                        $userList[$value['id']] = $value;
                    }
                } catch (Exception $e) {
                    throw new HttpException('500', $e->getMessage());
                }
            }
        }
        return ['memberList'=>$memberList, 'userList'=>$userList];
    }

    public function setStatus($id)
    {
        try {
            $data = [];
            $project = $this->projectModel->getOneById($id)->toArray()[0];
            $data['status'] = abs(1 - $project['status']);
            $this->projectModel->modify($id, $data);
            return $data['status'];
        } catch (HttpException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * 成员是否存在
     * @param $project_id
     * @param $user_id
     */
    public function userExists($project_id, $user_id)
    {
        $projectMemberModel = new ProjectMemberModel();
        return $projectMemberModel->getProjectOneMember($project_id, $user_id);
    }
}