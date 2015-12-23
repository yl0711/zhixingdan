<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 15/11/11
 * Time: 下午3:34
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminBaseController;
use Auth;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends AdminBaseController
{

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    public function index()
    {
        foreach ($this->admin_user_authority as $value) {
            foreach ($value['master'] as $value1) {
                @header("HTTP/1.1 301 Moved Permanently");
                @header('Status: 301 Moved Permanently');
                @header("Location:" . urldecode($value1['url']));
                exit;
            }
        }
    }

    public function login()
    {
        if (Auth::admin()->guest()) {
            return view('admin.login');
        } else {
            return redirect()->to('/');
        }
    }

    public function dologin(Request $request)
    {
        $data = $request->all();
        $validator = $this->validator($data);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return redirect()->back()->with('loginerr', implode(' ', $errors))->withInput();
        }

        if (Auth::admin()->attempt(['uname'=>$data['uname'], 'password'=>$data['password']], true)){
            if (!empty($data['backurl'])) {
                return Redirect()->to($data['backurl'])->with('message', '成功登录');
            } else {
                return redirect()->to('/')->with('message', '成功登录');
            }
        } else {
            return redirect()->back()->with('message', '用户名或密码不正确')->withInput();
        }
    }

    public function logout()
    {
        Auth::admin()->logout();

        return redirect()->to('login')->with('message', '你已经退出登录');
    }

    public function validator(array $data)
    {
        return Validator::make($data, [
            'uname' => 'required|max:255',
            'password' => 'required|min:6',
        ]);
    }
}