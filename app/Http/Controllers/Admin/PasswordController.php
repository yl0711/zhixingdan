<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 16/8/3
 * Time: 下午10:08
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\AdminBaseController;
use App\Http\Model\liuchengdan\UserModel;
use Illuminate\Http\Request;
use Auth;

class PasswordController extends AdminBaseController
{

    public function index(){
        return view('admin.admin_user.password');
    }

    /**
     * @Authorization 修改密码
     *
     * @param Request $request
     */
    public function modify(Request $request){
        $new_pass = $request->input('new_pass', '');
        $new_pass_confirm = $request->input('new_pass_confirm', '');

        if (empty($new_pass)){
            return json_encode(['status'=>'error', 'info'=>'请填写新密码']);
        }
        if (empty($new_pass_confirm)){
            return json_encode(['status'=>'error', 'info'=>'请填写新密码确认']);
        }
        if (strcmp($new_pass, $new_pass_confirm)){
            return json_encode(['status'=>'error', 'info'=>'两次输入的密码不一致']);
        }

        try {
            UserModel::where('id', $this->admin_user['id'])->update(['password'=>bcrypt($new_pass)]);
            return json_encode(['status'=>'success']);
        } catch (\Exception $e){
            return json_encode(['status'=>'error', 'info'=>$e->getMessage()]);
        }
    }
}