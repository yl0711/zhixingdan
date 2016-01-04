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
