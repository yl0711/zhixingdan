<script>
$(function() {
	$('#form_submit').bind('click', function(){
		if (false != check_submit_data()) {
			$.ajax({
				type:"post",
				dataType:"json",
				url: @if ($page_type=='modify') "{{url('department/modify')}}/{{$department['id']}}" @else "{{url('department/add')}}" @endif,
				data:{
					@if ($page_type=='modify')
					'id':$('#id').val(),
					'oldname':$('#oldname').val(),
					'oldalias':$('#oldalias').val(),
					@endif
					'name':$('#name').val(),
					'parentid':$('#parentid').val(),
					'alias':$('#alias').val()
				},
				async:false,
				success:function($data) {
					if ($data.status == 'error') {
						alert($data.info);
					} else {
						if (confirm('数据提交成功, 是否返回列表页')) {
							window.location.href = "{{url('department/index')}}";
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
	if (0 == $('#alias').val().trim().length) {
		alert('部门缩写不能为空');
		$('#alias').focus();
		return false;
	}
}
</script>