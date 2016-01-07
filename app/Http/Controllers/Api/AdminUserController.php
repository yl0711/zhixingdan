<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 16/1/7
 * Time: 上午11:21
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;
use App\Http\Manage\AdminUserManage;

class AdminUserController extends ApiBaseController
{
    private $adminUserManage = null;

    public function __construct()
    {
        parent::__construct();

        $this->adminUserManage = new AdminUserManage();
    }

    public function getUser($id)
    {
        try
        {
            $data = $this->adminUserManage->getUser($id)->toArray()[0];
            return json_encode(['status'=>'success', 'data'=>$data]);
        }
        catch (\Exception $e)
        {
            return json_encode(['status'=>'error', 'info'=>$e->getMessage()]);
        }
    }
}