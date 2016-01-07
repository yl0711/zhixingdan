<script>
$(function() {
	var check_form = 0;
	
	$('#form_submit').bind('click', function(){
		if (false != check_submit_data() && 0 == check_form) {
			$.ajax({
				type:"post",
				dataType:"json",
				url: @if ($page_type=='modify') "{{url('project/modify')}}/{{$project['id']}}" @else "{{url('project/add')}}" @endif,
				data:{
					@if ($page_type=='modify')
					'id':$('#id').val(),
					'oldname':$('#oldname').val(),
					@endif
					'name':$('#name').val(),
					'company_id':$('#company_id').val(),
					'pm_id':$('#pm_id').val(),
					'starttime':$('#starttime').val(),
					'endtime':$('#endtime').val(),
				},
				async:false,
				success:function($data) {
					if ($data.status == 'error') {
						alert($data.info);
					} else {
						if (confirm('数据提交成功, 是否返回列表页')) {
							window.location.href = "{{url('project/index')}}";
						}
					}
				}
			});
		}
	});
	
	$('#pm_id').blur(function() {
		if ($(this).val() == 0) {
			$("#pm_name").css("color","#FF0000");
			$('#pm_name').text('请填写项目经理ID');
			check_form = 1;
		} else if (isNaN($(this).val())) {
			$("#pm_name").css("color","#FF0000");
			$('#pm_name').text('项目经理ID应该是数字');
			check_form = 1;
		} else {
			$.ajax({
				type:"get",
				dataType:"json",
				url: "{{url('get/user')}}/" + $(this).val(),
				async:false,
				success:function($data) {
					if ($data.status == 'error') {
						$("#pm_name").css("color","#FF0000");
						$('#pm_name').text($data.info);
						check_form = 1;
					} else {
						$("#pm_name").css("color","#000000");
						$('#pm_name').text($data.data.name);
						check_form = 0;
					}
				}
			});
		}
	});
});

function check_submit_data() {
	if (0 == $('#name').val().trim().length) {
		alert('项目名称不能为空');
		$('#name').focus();
		return false;
	}
}
</script>