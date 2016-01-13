<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 15/11/10
 * Time: 下午3:33
 */

namespace App\Http\Controllers;


abstract class ApiBaseController extends Controller
{
	protected $adminUserManage = null;

	protected $pageSize;

	public function __construct()
    {

    }

}