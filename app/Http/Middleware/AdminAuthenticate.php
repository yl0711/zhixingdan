<?php

namespace App\Http\Middleware;

use App\Http\Manage\AdminAuthorityManage;
use App\Http\Manage\AdminUserManage;
use Auth;
use Config;
use Route;
use Closure;
use ReflectionClass;
use Illuminate\Contracts\Auth\Guard;

class AdminAuthenticate
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $adminAuth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct()
    {
        $this->adminAuth = auth::class;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->adminAuth->guest()) {
            return $this->responseErr($request);
        }

        // 获取管理员信息
        $admin_user = $this->adminAuth->get()->toArray();
        // 权限\发文审核\浏览权限没有设置时继承所属管理组
        $adminUserManage = new AdminUserManage();
        $admin_user_group = $adminUserManage->getUserGroup($admin_user['gid'])->toArray()[0];
        if (empty($admin_user['authority'])) {
            $admin_user['authority'] = $admin_user_group['authority'];
        }
        if (-1 == $admin_user['article_check']) {
            $admin_user['article_check'] = $admin_user_group['article_check'];
        }
        if (-1 == $admin_user['article_view']) {
            $admin_user['article_view'] = $admin_user_group['article_view'];
        }

        if (empty($admin_user['authority'])) {
            return $this->responseErr($request, '对不起, 您的账号没有后台操作权限');
        }

        $adminAuthorityManage = new AdminAuthorityManage();
        // 当前访问页面对应权限信息, 返回内容如: App\Http\Controllers\Admin\AdminUserController@index
        $currentRouteAction = Route::currentRouteAction();
        // 拆分 controller 和 method
        list($controller, $method) = explode('@', $currentRouteAction);
        // method 方法是index 置为空, 在库里 index 方法默认为首页, 不记录方法名
        if ('index' == $method) {
            $method = '';
        }
        // 声明一个反射类, 对类进行解析
        $reflectionClass = new ReflectionClass($controller);
        $className = str_replace($reflectionClass->getNamespaceName().'\\', '', $reflectionClass->getName());
        if ('AuthController' != $className) {
            // 权限表里是否有此页面
            $data = $adminAuthorityManage->getAuthorityByController($className, $method)->toArray();
            if (!$data) {
                return $this->responseErr($request, '对不起, 您所访问的功能以关闭 '.$className.'->'.$method, false);
            }
            // 访问页面是否在用户权限列表中
            if ('all' != $admin_user['authority']) {
                if (!in_array($data[0]['id'], explode(',', $admin_user['authority']))) {
                    return $this->responseErr($request, '对不起, 您的账号没有此功能操作权限', false);
                }
            }
            Config::set('global.CONSTANTS_ADMIN_CURRENT_AUTHORITY', $data);
        }

        // 获取用户权限列表
        $authorityid = 'all' == $admin_user['authority'] ? '' : explode(',', $admin_user['authority']);
        $admin_user_authority = $adminAuthorityManage->getList($authorityid);
        if (empty($admin_user_authority)) {
            return $this->responseErr($request, '对不起, 您的账号没有后台操作权限');
        }

        Config::set('global.CONSTANTS_ADMIN_USER', $admin_user);
        Config::set('global.CONSTANTS_ADMIN_USER_GROUP', $admin_user_group);
        Config::set('global.CONSTANTS_ADMIN_USER_AUTHORITY', $admin_user_authority);

        view()->share('admin_user', $admin_user);
        view()->share('admin_user_authority', $admin_user_authority);

        return $next($request);
    }

    private function responseErr($request, $msg='', $logout=true)
    {
        $logout && $this->adminAuth->logout();
        if ($request->ajax()) {
            !$msg && $msg = 'Unauthorized';
            return response(json_encode(['status'=>'error', 'info'=>$msg]));
        } else {
            if ($logout) {
                return redirect()->guest('login')->with('loginerr', $msg);
            } else {
                return redirect()->to('/');
            }
        }
    }
}
