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
        return self::where(['project_id'=>$projectId, 'status'=>1])->paginate(config('global.PAGE_SIZE'));
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
     * 某用户是否为项目成员
     *
     * @param $projectId
     * @param $memberId
     * @return array
     */
    public function memberExists($projectId, $memberId)
    {

    }

    /**
     * 添加项目成员
     *
     * @param $projectId
     * @param array $member
     * @return boolean
     */
    public function addMember($projectId, array $member)
    {

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