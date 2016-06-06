<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 15/11/12
 * Time: 上午11:17
 */

namespace App\Http\Manage;

use App\Http\Model\liuchengdan\AuthorityModel;
use App\Http\Model\liuchengdan\GroupModel;
use App\Http\Model\liuchengdan\UserModel;

class AdminUserManage
{
    public $error = [];

    private $userModel = null;
    private $groupModel = null;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->groupModel = new GroupModel();
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
    public function getUserList($name='', $group_id=0, $department_id=0, $area=[], $status=2)
    {
        return $this->userModel->getList($name, $group_id, $department_id, $status);
    }

    public function getAllUser($status=1)
    {
        return $this->userModel->getAll($status);
    }

    public function getUserById($id)
    {
        if (is_array($id)) {
            return UserModel::whereIn('id', $id)->get();
        } else {
            return UserModel::where('id', $id)->get();
        }
    }

    public function getParentUser($uid)
    {
        $ids = [];
        $parent_user = $this->userModel->where('id', $uid)->select('parent_user')->get()->toArray();

        while ($parent_user && isset($parent_user[0]['parent_user']) && $parent_user[0]['parent_user']) {
            $ids[] = $parent_user[0]['parent_user'];
            $parent_user = $this->userModel->where('uid', $uid)->select('parent_user')->get()->toArray();
        }
        return $ids;
    }

    public function getBranchUser($uid)
    {
        $ids = $branch_ids = [];
        $branch_user = $this->userModel->where('parent_user', $uid)->select('id')->get()->toArray();

        while ($branch_user) {
            $branch_ids = [];
            foreach ($branch_user as $item) {
                $ids[] = $item['id'];
                $branch_ids[] = $item['id'];
            }
            $branch_user = $this->userModel->whereIn('parent_user', $branch_ids)->select('id')->get()->toArray();
        }
        $ids = array_unique($ids);
        return $ids;
    }

    /**
     * 添加管理员
     * @param array $request
     * @return mixed
     * @throws \Exception
     */
    public function addUser(Array $request)
    {
        if (!isset($request['name']) || empty($request['name'])) {
            throw new \Exception('请填写账号');
        }
        if (!empty($this->userModel->getByUname($request['name']))) {
            throw new \Exception('用户 ' . $request['name'] . ' 已经存在');
        }
        if (!isset($request['password']) || empty($request['password'])) {
            throw new \Exception('请填写密码');
        }
        if (!isset($request['email']) || empty($request['email'])) {
            throw new \Exception('请填写email');
        }
        if (!isset($request['group_id']) || empty($request['group_id'])) {
            throw new \Exception('请选择所属用户组');
        }
        $userGroup = $this->groupModel->getByGid($request['group_id'])->toarray();
        if (empty($userGroup)) {
            throw new \Exception('选择的用户组不存在');
        }
        if (!isset($request['department_id']) || empty($request['department_id'])) {
            throw new \Exception('请选择所属部门');
        }
        if (!isset($request['area_id']) || empty($request['area_id'])) {
            throw new \Exception('请选择所在区域');
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
            $this->userModel->getByUid($request['id']);
            $uid = $request['id'];
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        if (!isset($request['name']) || empty($request['name'])) {
            throw new \Exception('请填写账号');
        }
        if ($request['name'] != $request['oldname'] && !empty($this->userModel->getByUname($request['name']))) {
            throw new \Exception('用户 ' . $request['name'] . ' 已经存在');
        }
        if (!isset($request['password']) || empty($request['password'])) {
            //每天密码表示不修改密码
            unset($request['password']);
        } else {
            $request['password'] = bcrypt($request['password']);
        }

        if (!isset($request['email']) || empty($request['email'])) {
            throw new \Exception('请填写email');
        }
        if (!isset($request['group_id']) || empty($request['group_id'])) {
            throw new \Exception('请选择所属用户组');
        }
        $userGroup = $this->groupModel->getByGid($request['group_id'])->toarray();
        if (empty($userGroup)) {
            throw new \Exception('选择的用户组不存在');
        }
        if (!isset($request['department_id']) || empty($request['department_id'])) {
            throw new \Exception('请选择所属部门');
        }
        if (!isset($request['area_id']) || empty($request['area_id'])) {
            throw new \Exception('请选择所在区域');
        }

        unset($request['id']);
        unset($request['oldname']);

        try {
            $this->userModel->modify($uid, $request);
        } catch (\Exception $e) {
            throw new $e;
        }
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
            return $this->groupModel->getByGid($gid);
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
    public function getUserGroupList($name='', $parentid=0, $status=2)
    {
        return $this->groupModel->getList($name, $parentid, $status);
    }

    /**
     * 获取所有开启的管理组
     * @return mixed
     */
    public function getUserGroupAll($status=1)
    {
        return $this->groupModel->getAll($status);
    }

    /**
     * 添加管理组
     * @param array $request
     * @return mixed
     * @throws \Exception
     */
    public function addUserGroup(Array $request)
    {
        if (!isset($request['name']) || empty($request['name'])) {
            throw new \Exception('请填写管理组名称');
        }
        if (!empty($this->groupModel->getByGname($request['name']))) {
            throw new \Exception('管理组 ' . $request['name'] . ' 已经存在');
        }

        return $this->groupModel->add($request);
    }

    /**
     * 修改管理组
     * @param array $request
     * @throws \Exception
     */
    public function modifyUserGroup(Array $request)
    {
        try {
            $this->groupModel->getByGid($request['id']);
            $gid = $request['id'];

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        if (!isset($request['name']) || empty($request['name'])) {
            throw new \Exception('请填写管理组名');
        }
        if ($request['name'] != $request['oldname'] && !empty($this->groupModel->getByGname($request['name']))) {
            throw new \Exception('管理组 ' . $request['name'] . ' 已经存在');
        }
        unset($request['id']);
        unset($request['oldname']);

        $this->groupModel->modify($gid, $request);
    }

    /**
     * 修改管理员状态
     */
    public function setUserStatus($id)
    {
        try {
            $data = [];
            $department = $this->userModel->getByUid($id)->toArray()[0];
            $data['status'] = abs(1 - $department['status']);
            $this->userModel->modify($id, $data);
            return $data['status'];
        } catch (HttpException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * 修改管理组状态
     */
    public function setGroupStatus($id)
    {
        try {
            if ($this->groupModel->getCount(['parentid'=>$id])) {
                throw new \Exception('当前用户组存在下级用户组, 请先移除再关闭');
            }
            if ($this->userModel->getCount(['group_id'=>$id])) {
                throw new \Exception('当前用户组下存在用户, 请先移除再关闭');
            }

            $data = [];
            $department = $this->groupModel->getByGid($id)->toArray()[0];
            $data['status'] = abs(1 - $department['status']);
            $this->groupModel->modify($id, $data);
            return $data['status'];
        } catch (HttpException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function updateParentUser($id, $parentid)
    {
        return UserModel::where('id', $id)->update(['parent_user'=>$parentid]);
    }

    /**
     * 用户权限转移
     *
     * @param $id
     * @param $parentid
     */
    public function transferUser($id, $parentid)
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