<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>管理后台</title>
	<link rel="stylesheet" href = "{{ asset('css/admin/bootstrap.min.css') }}" />
	<link rel="stylesheet" href="{{ asset('css/admin/common.css') }}">
	<link rel="stylesheet" href="{{ asset('css/admin/style.css') }}">
	<link rel="stylesheet" href="{{ asset('js/plugin/jquery-ui/jquery-ui.min.css') }}">
</head>
<body >
<div id="header">
@if (isset($admin_user))
	<div class="header-r fr">
		<a style="color: #FFFFFF;" href="{{ url('logout') }}">安全退出</a>
	</div>
	<div class="header-r fr">
		{{ $admin_user['name'] }}，欢迎您！
	</div>
@endif
</div>
@if(Session::has('message'))
<p class="alert">{{ Session::get('message') }}</p>
@endif
