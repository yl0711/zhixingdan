<script>
$(function() {
	$('#form_submit').bind('click', function(){
		if (false != check_submit_data()) {
			$.ajax({
				type:"post",
				dataType:"json",
				url: @if ($page_type=='modify') "{{url('company/modify')}}/{{$company['id']}}" @else "{{url('company/add')}}" @endif,
				data:{
					@if ($page_type=='modify')
					'id':$('#id').val(),
					'oldname':$('#oldname').val(),
					@endif
					'name':$('#name').val(),
					'parentid':$('#parentid').val(),
				},
				async:false,
				success:function($data) {
					if ($data.status == 'error') {
						alert($data.info);
					} else {
						if (confirm('数据提交成功, 是否返回列表页')) {
							window.location.href = "{{url('company/index')}}";
						}
					}
				}
			});
		}
	});
});

function check_submit_data() {
	if (0 == $('#name').val().trim().length) {
		alert('部门名称不能为空');
		$('#name').focus();
		return false;
	}
}
</script>