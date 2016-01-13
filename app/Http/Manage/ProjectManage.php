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
    public function getList($name=null, $status=null)
    {
        return $this->projectModel->getList($name, $status);
    }

    /**
     * 根据项目ID获取单个项目信息
     *
     * @param int $id
     * @param int $status
     */
    public function getOneById($id, $status=null)
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

    public function getMemberListByProjectid($id)
    {
        $model = new ProjectMemberModel();
        $memberList = $model->getMemberListByProjectid($id)->toArray();
        if ($memberList && $memberList['data'])
        {
            $memberList = $memberList['data'];
        }
        else
        {
            $memberList = [];
        }

        $userList = [];
        if ($memberList)
        {
            $userids = [];
            foreach ($memberList as $value)
            {
                $userids[$value['ser_id']] = $value['ser_id'];
            }
            if ($userids)
            {
                $manage = new AdminUserManage();
                try
                {
                    $data = $manage->getUser($userids)->toArray();
                    foreach ($data as $value)
                    {
                        $userList[$value['id']] = $value;
                    }
                }
                catch (Exception $e)
                {
                    throw new HttpException('500', $e->getMessage());
                }
            }
        }
        return ['memberList'=>$memberList, 'userList'=>$userList];
    }
}