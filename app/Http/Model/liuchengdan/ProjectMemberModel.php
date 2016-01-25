<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 16/1/11
 * Time: 上午10:05
 */

namespace App\Http\Model\liuchengdan;

use App\Http\Model\BaseModel;

class ProjectMemberModel extends BaseModel
{
    protected $table = 'project_member';

    /**
     * 获取项目成员列表 (含分页)
     *
     * @param $projectId
     * @return array
     */
    public function getMemberListByProjectid($projectId)
    {
        return self::where(['project_id'=>$projectId, 'status'=>1])->get();
    }

    /**
     * 获取项目所有成员 (无分页)
     *
     * @param $projectId
     * @return array
     */
    public function getAllMemberByProjectid($projectId)
    {
        return self::where(['project_id'=>$projectId, 'status'=>1])->get();
    }

    /**
     * 获取某个项目某成员信息
     *
     * @param int   $project_id
     * @param int   $user_id
     * @param boolean   $full   是否需要完整数据, true需要, false不需要, 默认不需要
     * @return mixed $full=true 返回用户数据数组, $full=false 返回boolean值
     */
    public function getProjectOneMember($project_id, $user_id, $full=false)
    {
        $user = self::where(['project_id'=>$project_id, 'user_id'=>$user_id, 'status'=>1])->get()->toArray()[0];
        if (empty($user)) {
            return false;
        }
        if (true == $full) {
            $userModel = new UserModel();
            try {
                $data = $userModel->getByUid($user_id)->toArray()[0];
            } catch (\Exception $e) {
                return false;
            }
            return $data;
        } else {
            return true;
        }
    }

    /**
     * 添加项目成员
     *
     * @param $project_id
     * @param $user_id
     * @param $pm       是否是项目经理, 1是, 0不是
     */
    public function addMember($project_id, $user_id, $pm=0)
    {
        try {
            $data = self::where(['project_id'=>$project_id, 'user_id'=>$user_id])->get()->toArray()[0];
            if ($data) {
                self::where(['project_id'=>$project_id, 'user_id'=>$user_id])->update(['pm'=>$pm, 'status'=>1]);
                $id = $data['id'];
            } else {
                $obj = self::create(['project_id'=>$project_id, 'user_id'=>$user_id, 'pm'=>$pm]);
                $id = $obj->id;
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
        return $id;
    }

    /**
     * 修改项目成员信息
     *
     * @param $projectId
     * @param array $member
     * @return boolean
     */
    public function modifyMember($projectId, array $member)
    {

    }

    /**
     * 修改项目成员状态 (删除或恢复)
     *
     * @param $projectId
     * @param $memberId
     * @param $status  修改状态, 如不指定表示状态反转 (当前1改为0,当前0改为1)
     * @return boolean
     */
    public function modifyMemberStatus($projectId, $memberId, $status=null)
    {

    }
}