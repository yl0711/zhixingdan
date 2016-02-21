<script>
var article_check = article_view = 0;

$(function() {
	$('#admin_user_submit').bind('click', function(){
		if (false != check_submit_data()) {
			$.ajax({
				type:"post",
				dataType:"json",
				url: @if ($page_type=='modify') "{{url('user/modify')}}/{{$user['id']}}" @else "{{url('user/add')}}" @endif,
				data:{
					@if ($page_type=='modify')
					'id':$('#id').val(),
					'oldname':$('#oldname').val(),
					@endif
					'name':$('#name').val(),
					'password':$('#password').val(),
					'email':$('#email').val(),
					'group_id':$('#group_id').val(),
					'department_id':$('#department_id').val()
				},
				async:false,
				success:function($data) {
					if ($data.status == 'error') {
						alert($data.info);
					} else {
						if (confirm('数据提交成功, 是否返回列表页')) {
							window.location.href = "{{url('user/index')}}";
						}
					}
				}
			});
		}
	});
});

function check_submit_data() {
	if (0 == $('#name').val().trim().length) {
		alert('管理员账号不能为空');
		$('#name').focus();
		return false;
	}
	@if ($page_type=='add')
	if (0 == $('#password').val().trim().length) {
		alert('管理员密码不能为空');
		$('#password').focus();
		return false;
	}
	if (0 == $('#group_id').val()) {
		alert('请选择管理员所在管理组');
		return;
	}
	@endif
}
</script>