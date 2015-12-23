@include('admin/static/header')
	<div id="login">
		<div class="login-box">
			<dl class="login-title">
				<dt><img src="{{ asset('images/admin/head-img.png') }}" alt=""></dt>
				<dd><h1>用户登录</h1></dd>
			@if(Session::has('loginerr'))
				<dd style="color: #ff0000; padding-top: 10px;">{{ Session::get('loginerr') }}</dd>
			@endif
			</dl>
			<form method="post" action="{{url('login')}}">
				<input type="hidden" name="backurl" value="" />
				<dl class="form-box">
					<dt></dt>
					<dd><input type="text" name="uname" id="uname" placeholder="账号"></dd>
				</dl>
				<dl class="form-box">
					<dt></dt>
					<dd><input type="password" name="password" id="password" placeholder="密码"></dd>
				</dl>
				<div class="login-button">
					<input type="submit" value="登录">
				</div>
			</form>
		</div>
	</div>
</body>
@include('admin/static/footer')
<script>
$(function() {
	$(form).submit(function() {
		if (!checkFormData()) {
			return false;
		}
	});
});

functon checkFormData() {
	if (0 == $('#uname').val().trim().length) {
		alert('请填写你的管理员账号');
		return false;
	}
	if (0 == $('#password').val().trim().length) {
		alert('请填写你的管理员密码');
		return false;
	}
}
</script>