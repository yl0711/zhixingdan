<?php
/**
 * [自定义函数扩展库]
 */
if (!function_exists('class_method_to_url')) {

	/**
	 * [p 打印输出函数]
	 * @param  [type] $var [description]
	 * @return [type]      [description]
	 */
    function class_method_to_url($domain, $class, $method)
    {
		$url = URL::action('\\' . $class.'@'.$method, []);
		$url_tmp = str_replace('http://' . $domain . '/', '', $url);
		list($controller, $action) = explode('/', $url_tmp);
		return url($controller . '/' . $action);
    }
}

if (!function_exists('zhixingdan_code')) {
	/**
	 * 执行单号（审批后生成）：Z-BD-20150909-093N
	Z：执行单
	BD：部门编号，部门管理是绑定上，项目负责人是哪个部门，这里就是哪个部门标识
	20150909：填写的时间
	093：自增序号
	N：大区，华南、华北大区
	 */
	function zhixingdan_code($departmentCode, $createdTime, $id, $areaCode)
	{
		$code = 'Z-' . $departmentCode . '-' . $createdTime . '-' . str_pad($id, 5, "0", STR_PAD_LEFT) . $areaCode;
		return $code;
	}
}
