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

abstract class ApiBaseController extends Controller
{
	protected $adminUserManage = null;

	protected $pageSize;

	public function __construct()
    {

    }

}