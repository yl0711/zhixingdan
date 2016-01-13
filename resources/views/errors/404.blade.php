<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>404错误</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no" />
	<style type="text/css">
		body{ margin:0; padding:0; background:#efefef; font-family:Georgia, Times, Verdana, Geneva, Arial, Helvetica, sans-serif; }
		div#mother{ margin:0 auto; width:943px; height:572px; position:relative; }
		div#errorBox{ background: url({{ asset('images/h5/404_bg.png') }}) no-repeat top left;  }
		div#errorText{ color:#39351e; padding:146px 0 0 446px }
		div#errorText p{ width:303px; font-size:14px; line-height:26px; }
		div.link{ /*background:#f90;*/ height:50px; width:145px; float:left; }
		div#home{ margin:20px 0 0 444px;}
		div#contact{ margin:20px 0 0 25px;}
		h1{ font-size:40px; margin-bottom:35px; }
	</style>
</head>
	<body>
		<div id="mother">
			<div id="errorBox">
				<div id="errorText">
					<h1>@if (isset($exception) && !empty($exception->getMessage()))
						{{$exception->getMessage()}}
					@else
						Sorry..该资源没有找到！
					@endif</h1>
					<p>
						如果该资源对你很重要，请与管理员联系。
					</p>

					<p>
						<a href="#" onclick="history.go(-1);">点击返回</a>
					</p>
				</div>
			</div>
		</div>
	</body>
</html>