<script>
$(function() {
	$('#admin_user_group_submit').bind('click', function(){
		if (false != check_submit_data()) {
			$.ajax({
				type:"post",
				dataType:"json",
				url:@if ($page_type=='modify') "{{url('usergroup/modify')}}/{{$group['id']}}" @else "{{url('usergroup/add')}}" @endif ,
				data:{
					'id':$('#id').val(),
					'name':$('#name').val(),
				@if ($page_type=='modify')
					'oldname':$('#oldname').val(),
				@endif
					'parentid':$('#parentid').val(),
				},
				async:false,
				success:function($data) {
					if ($data.status == 'error') {
						alert($data.info);
					} else {
						if (confirm('数据提交成功, 是否返回列表页')) {
							window.location.href = "{{url('usergroup/index')}}";
						}
					}
				}
			});
		}
	});
});

function check_submit_data() {
	if (0 == $('#name').val().trim().length) {
		alert('用户组名称不能为空');
		$('#name').focus();
		return false;
	}
}
</script>