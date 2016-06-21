<script>
var area_id = '';

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
					'department_id':$('#department_id').val(),
					'parent_user':$('#parent_user').val(),
					'area_id':area_id,
					'superadmin':$('input:radio[name=superadmin]:checked').val(),
					'superwatch':$('input:radio[name=superwatch]:checked').val()
				},
				async:false,
				success:function($data) {
					if ($data.status == 'error') {
						alert($data.info);
					} else {
						if (confirm('数据提交成功, 选择用户直属上级')) {
							window.location.href = "{{url('user/parent')}}/" + $data.data.id;
						}
					}
				}
			});
		}
	});
});

function check_submit_data() {
	area_id = '';
	$("input[name='area_id']").each(function(){
        if($(this).attr("checked"))
            area_id += $(this).val() + ',';
    });
    if (area_id) {
    		area_id = ',' + area_id;
    }
	
	if (0 == $('#name').val().trim().length) {
		alert('账号不能为空');
		$('#name').focus();
		return false;
	}
	if (0 == $('#email').val()) {
		alert('Email不能为空');
		return;
	}
	if (0 == $('#group_id').val()) {
		alert('请选择所在用户组');
		return;
	}
	if (0 == $('#department_id').val()) {
		alert('请选择所在部门');
		return;
	}
	if ('' == area_id) {
		alert('请选择所在区域');
		return;
	}
	@if ($page_type=='add')
	if (0 == $('#password').val().trim().length) {
		alert('管理员密码不能为空');
		$('#password').focus();
		return false;
	}
	@endif
}
</script>