<script>
$(function() {
	$('#form_submit').bind('click', function(){
		if (false != check_submit_data()) {
			$.ajax({
				type:"post",
				dataType:"json",
				url: @if ($page_type=='modify') "{{url('documents/modify')}}/{{$document['id']}}" @else "{{url('documents/add')}}" @endif,
				data:{
					@if ($page_type=='modify')
					'id':$('#id').val(),
					'oldname':$('#oldname').val(),
					@endif
					'name':$('#name').val(),
					'company_id':$('#company_id').val(),
					'project_id':$('#project_id').val(),
					'created_uid':{{$admin_user['id']}},
				},
				async:false,
				success:function($data) {
					if ($data.status == 'error') {
						alert($data.info);
					} else {
						if (confirm('数据提交成功, 是否返回列表页')) {
							window.location.href = "{{url('documents/index')}}";
						}
					}
				}
			});
		}
	});
});

function check_submit_data() {
	if (0 == $('#company_id').val()) {
		alert('请选择所属供应商');
		return;
	}
	if (0 == $('#project_id').val()) {
		alert('请选择所属项目');
		return;
	}
	if (0 == $('#name').val().trim().length) {
		alert('部门名称不能为空');
		$('#name').focus();
		return false;
	}
}
</script>