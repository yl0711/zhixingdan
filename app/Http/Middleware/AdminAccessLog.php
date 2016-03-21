<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 16/1/20
 * Time: 下午8:14
 */

namespace App\Http\Middleware;

use App\Http\Model\liuchengdan\AdminLogModel;
use Route;
use Closure;
use Illuminate\Contracts\Auth\Guard;

class AdminAccessLog
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
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->adminAuth = $auth;
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
        // 当前访问页面对应权限信息, 返回内容如: App\Http\Controllers\Admin\AdminUserController@index
        $currentRouteAction = Route::currentRouteAction();
        // 拆分 controller 和 method
        list($controller, $method) = explode('@', $currentRouteAction);
        $reflectionClass = new \ReflectionClass($controller);
        $namespace = $reflectionClass->getNamespaceName();
        $controller = str_replace($namespace . '\\', '', $controller);

        if ($this->adminAuth->guest()) {
            $admin_id = 0;
        } else {
            $admin = $this->adminAuth->user();
            $admin_id = $admin->id;
        }

        $data = [
            'controller' => $controller,
            'method' => $method,
            'namespace' => $namespace,
            'url' => $request->url(),
            'ip' => $request->getClientIp(),
            'get' => $request->server('QUERY_STRING'),
            'post' => json_encode($_POST),
            'cookie' => json_encode($request->cookie()),
            'headers' => json_encode($request->header()),
            'response_code' => intval($request->server('REDIRECT_STATUS')),
            'admin_id' => $admin_id,
            'laravel_session' => isset($request->cookie()['laravel_session']) ? $request->cookie()['laravel_session'] : '',
            'http_user_agent' => $request->server('HTTP_USER_AGENT'),
        ];

        $adminLogModel = new AdminLogModel();
        $adminLogModel->add($data);

        $response = $next($request);

        return $response;
    }
}