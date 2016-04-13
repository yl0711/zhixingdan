<?php
/**
 * 权限管理
 * Created by PhpStorm.
 * User: yanglei
 * Date: 15/10/29
 * Time: 上午11:18
 */

namespace App\Http\Controllers\Admin;

use Log;
use Exception;
use ReflectionClass;
use ReflectionMethod;
use App\Http\Controllers\AdminBaseController;
use App\Http\Manage\AdminAuthorityManage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

/**
 * Class AdminAuthorityController
 * @package App\Http\Controllers\Admin
 * @Authorization 权限管理::权限管理
 */
class AdminAuthorityController extends AdminBaseController
{

    public $adminAuthorityManage = null;

    public function __init()
    {
        $this->adminAuthorityManage = new AdminAuthorityManage();
    }

    public function index()
    {
        $data = $this->adminAuthorityManage->getList();

        return view('admin.authority.list', compact('data'));
    }

    /**
     * @param $uid
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     * @Authorization 管理员权限设置
     */
    public function userAuthority(Request $request, $uid)
    {
        if ('POST' == $request->method()) {
            return $this->modifyAuthority($request);
        }

        $user = $this->adminUserManage->getUser($uid)->toArray()[0];
        $user['type'] = 'user';

        if (!$user['authority']) {
            $group = $this->adminUserManage->getUserGroup($user['group_id'])->toArray()[0];
            $user['authority'] = $group['authority'];
        }

        $authorityList = [];
        if ('all' == $user['authority']) {
            $authorityList = ['all'];
        } else {
            $data = explode(',', $user['authority']);
            foreach ($data as $value) {
                $authorityList[$value] = $value;
            }
        }

        $menu = $this->adminAuthorityManage->getList();

        return view('admin.authority.user', compact('menu', 'authorityList', 'user'));
    }

    /**
     * @param $gid
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     * @Authorization 管理组权限设置
     */
    public function groupAuthority(Request $request, $gid)
    {
        if ('POST' == $request->method()) {
            return $this->modifyAuthority($request);
        }

        $user = $this->adminUserManage->getUserGroup($gid)->toArray()[0];
        $user['type'] = 'group';

        $authorityList = [];
        if ('all' == $user['authority']) {
            $authorityList = ['all'];
        } else {
            $data = explode(',', $user['authority']);
            foreach ($data as $value) {
                $authorityList[$value] = $value;
            }
        }

        $menu = $this->adminAuthorityManage->getList();

        return view('admin.authority.user', compact('menu', 'authorityList', 'user'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return string
     * @Authorization 管理组权限设置
     */
    public function modifyAuthority(Request $request)
    {
        try {
            $data = $request->except(['s']);
            $this->adminAuthorityManage->modifyAuthority($data);
        } catch (\Exception $e) {
            return json_encode(['status'=>'error', 'info'=>$e->getMessage()]);
        }
        return json_encode(['status'=>'success']);
    }

    /**
     * @Authorization 刷新列表
     * @return string
     */
    public function refreshList()
    {
        $list = ['parent'=>[], 'master'=>[], 'sub'=>[]];

        // 读取 App\Http\Controllers\Admin 目录, 获取里面所有 Controller 类
        $admin_authority = config('admin_authority');
        $parentOrder = 0;
        foreach ($admin_authority as $parent => $classArr) {
            $list['parent'][$parent] = ['name'=>$parent, 'order' => $parentOrder];
            $masterOrder = 0;
            foreach ($classArr as $master => $class) {
                $ReflectionClass = new ReflectionClass($class);
                $filenameArr = explode(config('global.DS'), $ReflectionClass->getFileName());
                $filename = end($filenameArr);
                list($classname) = explode('.', $filename);

                try {
                    $indexUrl = URL::action('\\'.$class . '@index');
                    $list['master'][$classname] = [
                        'name'  => $master,
                        'filename'  => $filename,
                        'classname' => $classname,
                        'methodname' => 'index',
                        'url'   => $indexUrl,
                        'parent' => $parent,
                        'order' => $masterOrder,
                    ];
                    $masterOrder++;
                } catch (Exception $e) {
                    Log::error($e->getMessage() . ' (' . $e->getLine() . ')');
                    continue;
                }

                foreach ($ReflectionClass->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
                    $ReflectionMethod = new ReflectionMethod($class, $method->getName());
                    //有 Authorization 注释项的方法会加入到权限列表中
                    preg_match('/@Authorization (.*)/', $ReflectionMethod->getDocComment(), $Authorization);

                    if ($Authorization) {
                        $subname = trim($Authorization[1]);
                        try {
                            $url = URL::action('\\' . $class.'@'.$method->getName(), []);
                            $url_tmp = str_replace('http://' . config('global.DOMAIN')['ADMIN'] . '/', '', $url);
                            list($controller, $action) = explode('/', $url_tmp);

                            $list['sub'][$classname.'|'.$method->getName()] = [
                                'name' => $subname,
                                'filename' => $filename,
                                'classname' => $classname,
                                'methodname' => $method->getName(),
                                'url' => url($controller . '/' . $action),
                                'parent' => $parent,
                                'master' => $classname,
                            ];
                        } catch (\Exception $e) {
                            Log::error($e->getMessage() . ' (' . $e->getLine() . ')');
                        }
                    }
                }
            }
            $parentOrder++;
        }

        if ($list) {
            try {
                $this->adminAuthorityManage->createAuthorityList($list);
            } catch (\Exception $e) {
                return json_encode(['status'=>'error', 'info'=>$e->getMessage()]);
            }
        }
        return json_encode(['status'=>'success']);
    }

}