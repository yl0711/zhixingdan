<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 15/11/10
 * Time: 下午3:33
 */

namespace App\Http\Controllers;

use App\Http\Manage\AdminUserManage;
use Validator;
use Request;
use Config;
use Route;

abstract class AdminBaseController extends Controller
{
	protected $adminUserManage = null;

	protected $admin_user = null;
	protected $admin_user_group = null;
	protected $admin_user_authority = null;
	protected $admin_current_authority = null;

	protected $article_view_uid = 0;

	protected $pageSize;

	public function __construct()
    {
		$this->adminUserManage = new AdminUserManage();

		$this->admin_user = config('global.CONSTANTS_ADMIN_USER');
		$this->admin_user_group = config('global.CONSTANTS_ADMIN_USER_GROUP');
		$this->admin_user_authority = config('global.CONSTANTS_ADMIN_USER_AUTHORITY');

		if (config('global.CONSTANTS_ADMIN_CURRENT_AUTHORITY')) {
			$parentid = config('global.CONSTANTS_ADMIN_CURRENT_AUTHORITY')[0]['parentid'];
			$masterid = config('global.CONSTANTS_ADMIN_CURRENT_AUTHORITY')[0]['masterid'];
			$currentid = config('global.CONSTANTS_ADMIN_CURRENT_AUTHORITY')[0]['id'];

			if (0 == $masterid) {
				$data = $this->admin_user_authority[$parentid]['master'][$currentid];
			} else {
				$data = $this->admin_user_authority[$parentid]['master'][$masterid];
			}

			$this->admin_current_authority[$data['url']] = [
				'id' => $data['id'],
				'aname' => $data['aname'],
				'filename' => $data['filename'],
				'classname' => $data['classname'],
				'methodname' => $data['methodname'],
				'master' => [],
				'parent' => [
					'id' => $this->admin_user_authority[$parentid]['id'],
					'aname' => $this->admin_user_authority[$parentid]['aname'],
				],
			];

			if (isset($data['sub']) && !empty($data['sub'])){
				foreach ($data['sub'] as $value) {
					$this->admin_current_authority[$value['url']] = [
						'id' => $value['id'],
						'aname' => $value['aname'],
						'filename' => $value['filename'],
						'classname' => $value['classname'],
						'methodname' => $value['methodname'],
						'master' => $this->admin_current_authority[$data['url']],
						'parent' => [],
					];
				}
			}

			$navigation = navigation(Route::currentRouteAction());
			view()->share('navigation', $navigation);
			view()->share('admin_current_authority', $this->admin_current_authority);
		}

		Config::set('global.PAGE_SIZE', Request::input('pageSize', config('global.PAGE_SIZE')));
		$this->pageSize = config('global.PAGE_SIZE');
		view()->share('pageSize', $this->pageSize);
		if (method_exists($this, '__init')) $this->__init();
    }

	/**
	 * [validation 表单验证]
	 * @Author        ningziwei
	 * @param         [array]        $data     [提交数据]
	 * @param         [array]        $rules    [验证规则]
	 * @return        [string]                 [json]
	 */
	protected function validation($data, $rules)
	{

		$validator = Validator::make($data, $rules);
		if ($validator->fails()) {
			$errors = $validator->errors()->all();
			$res['status'] = 0;
			$res['info'] = implode('<br/><br/>', $errors);
			echo json_encode($res);die;
		}
	}


	protected function upload($request)
    {
        $upload = new \App\Http\Manage\UploadManage();
        $result = $upload->upload_file($request);
        //$upload->createThumb($result['dbPath']);
        $result = json_encode($result);

   
        return "<script>parent.upImgCallback({$result})</script>";
    }

    protected function editorUpload($request)
    {
        $upload = new \App\Http\Manage\UploadManage();
        $multi= $request->input('multi',0);
        $inputName = $multi ? 'upfile' : 'upload';
        $result = $upload->upload_file($request, $inputName);
        $url = 'http://'.$result['imageUrl'];
        if ($multi) {
        	return json_encode(['state'=>'SUCCESS','url'=>$url]);
        } else {
        	return "<script>window.parent.CKEDITOR.tools.callFunction(1, '".$url."', '');</script>";
        }
    }

	/**
	 * 生成面包屑导航
	 *
	 * @param $RouteAction 使用 Route::currentRouteAction() 获取的路由
	 */
	protected function navigation($RouteAction)
	{
		$navigation = [];
		list($class, $method) = explode('@', $RouteAction);
		$currentUrl = class_method_to_url(config('global.DOMAIN')['ADMIN'], $class, $method);
		$current = $this->admin_current_authority[$currentUrl];
		if ($current['parent']) {
			$navigation[] = $current['parent'];
			unset($current['parent']);
			$navigation[] = $current;
		} else if ($current['master']) {
			$navigation[] = $current['master']['parent'];
			unset($current['master']['parent']);
			$navigation[] = $current['master'];
			unset($current['master']);
			$navigation[] = $current;
		} else {
			$navigation[] = $current;
		}

		return $navigation;
	}
}