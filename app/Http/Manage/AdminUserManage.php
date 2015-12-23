<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 15/11/12
 * Time: 上午11:17
 */

namespace App\Http\Manage;

use App\Http\Model\NiusheAdmin\AuthorityModel;
use App\Http\Model\NiusheAdmin\UserGroupModel;
use App\Http\Model\NiusheAdmin\UserModel;

class AdminUserManage
{
    public $error = [];

    private $userModel = null;
    private $userGroupModel = null;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->userGroupModel = new UserGroupModel();
    }

    /**
     * 获取单个管理员信息
     * @param mixed $uid
     * @return array
     */
    public function getUser($uid)
    {
        try {
            return $this->userModel->getByUid($uid);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * 获取管理员列表
     * @param string $name 管理员名, 模糊查询
     * @param int $gid     所属管理组
     * @param int $status  状态
     * @return array
     */
    public function getUserList($name=null, $gid=null, $status=null)
    {
        $groupids = $userGroup = [];
        $userList = $this->userModel->getList($name, $gid, $status)->toarray();

        foreach ($userList['data'] as $key=>$value) {
            $groupids[$value['gid']] = $value['gid'];
        }
        if ($groupids) {
            $data = $this->userGroupModel->getByGid($groupids)->toarray();
            foreach ($data as $value) {
                $userGroup[$value['gid']] = $value;
            }
        }
        return ['userList'=>$userList, 'userGroup'=>$userGroup];
    }

    /**
     * 添加管理员
     * @param array $request
     * @return mixed
     * @throws \Exception
     */
    public function addUser(Array $request)
    {
        if (!isset($request['uname']) || empty($request['uname'])) {
            throw new \Exception('请填写管理员账号');
        }
        if (!empty($this->userModel->getByUname($request['uname']))) {
            throw new \Exception('用户 ' . $request['uname'] . ' 已经存在');
        }
        if (!isset($request['password']) || empty($request['password'])) {
            throw new \Exception('请填写管理员密码');
        }
        if (!isset($request['gid']) || empty($request['gid'])) {
            throw new \Exception('请选择管理员所属用户组');
        }
        $userGroup = $this->userGroupModel->getByGid($request['gid'])->toarray();
        if (empty($userGroup)) {
            throw new \Exception('选择的管理员组不存在');
        }
        if (!isset($request['article_check'])) {
            $request['article_check'] = 1;
        }
        $request['password'] = bcrypt($request['password']);

        return $this->userModel->add($request);
    }

    /**
     * 修改管理员
     * @param array $request
     * @throws \Exception
     */
    public function modifyUser(Array $request)
    {
        try {
            $this->userModel->getByUid($request['uid']);
            $uid = $request['uid'];
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        if (!isset($request['uname']) || empty($request['uname'])) {
            throw new \Exception('请填写管理员账号');
        }
        if ($request['uname'] != $request['olduname'] && !empty($this->userModel->getByUname($request['uname']))) {
            throw new \Exception('用户 ' . $request['uname'] . ' 已经存在');
        }
        if (!isset($request['password']) || empty($request['password'])) {
            //每天密码表示不修改密码
            unset($request['password']);
        } else {
            $request['password'] = bcrypt($request['password']);
        }
        if (!isset($request['article_check'])) {
            $request['article_check'] = 1;
        }
        unset($request['uid']);
        unset($request['olduname']);

        $this->userModel->modify($uid, $request);
    }

    /**
     * 设置管理员状态
     */
    public function stateUser()
    {

    }

    /**
     * 获取指定管理组
     * @param $gid
     * @return mixed
     * @throws \Exception
     */
    public function getUserGroup($gid)
    {
        try {
            return $this->userGroupModel->getByGid($gid);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * 获取管理组列表
     * @param null $name
     * @param null $parentid
     * @param null $status
     * @return mixed
     */
    public function getUserGroupList($name=null, $parentid=null, $status=null)
    {
        return $this->userGroupModel->getList($name, $parentid, $status);
    }

    /**
     * 获取所有开启的管理组
     * @return mixed
     */
    public function getUserGroupAll()
    {
        return $this->userGroupModel->getAll();
    }

    /**
     * 添加管理组
     * @param array $request
     * @return mixed
     * @throws \Exception
     */
    public function addUserGroup(Array $request)
    {
        if (!isset($request['gname']) || empty($request['gname'])) {
            throw new \Exception('请填写管理组名称');
        }
        if (!empty($this->userGroupModel->getByGname($request['gname']))) {
            throw new \Exception('管理组 ' . $request['gname'] . ' 已经存在');
        }
        if (!isset($request['article_check'])) {
            $request['article_check'] = 1;
        }

        return $this->userGroupModel->add($request);
    }

    /**
     * 修改管理组
     * @param array $request
     * @throws \Exception
     */
    public function modifyUserGroup(Array $request)
    {
        try {
            $this->userGroupModel->getByGid($request['gid']);
            $gid = $request['gid'];

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        if (!isset($request['gname']) || empty($request['gname'])) {
            throw new \Exception('请填写管理组名');
        }
        if ($request['gname'] != $request['oldgname'] && !empty($this->userGroupModel->getByGname($request['gname']))) {
            throw new \Exception('管理组 ' . $request['gname'] . ' 已经存在');
        }
        if (!isset($request['article_check'])) {
            $request['article_check'] = 1;
        }
        unset($request['gid']);
        unset($request['oldgname']);

        $this->userGroupModel->modify($gid, $request);
    }

    /**
     * 修改管理组状态
     */
    public function stateUserGroup()
    {

    }

    /**
     * 获取发文是否需要审核的描述信息
     * @param $state 状态码, 1不需要，0需要
     *
     * @return string
     */
    public function getArticleCheck($state)
    {
        if ($state == 1) {
            return '不需要';
        } else {
            return '需要';
        }
    }

    public function getArticleView($state)
    {
        if ($state == 0) {
            return '所有人';
        } else if ($state == 1) {
            return '下级管理组';
        } else {
            return '自己';
        }
    }
}