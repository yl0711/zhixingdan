<script>
/**
 *  设置日期选择控件，用于选择开始和结束日期
 */
var date = new Date();

$( "#starttime" ).datepicker({
	dateFormat: "yy-mm-dd",
	changeMonth: true,
    changeYear: true,
    minDate: '-2y',
    maxDate: '+2y'
});

$( "#endtime" ).datepicker({
	dateFormat: "yy-mm-dd",
	changeMonth: true,
    changeYear: true,
    minDate: '-2y',
    maxDate: '+2y'
});

$( "#moneytime" ).datepicker({
	dateFormat: "yy-mm-dd",
	changeMonth: true,
    changeYear: true,
    minDate: '-2y',
    maxDate: '+2y'
});

$( "#starttime" ).datepicker( "option", "onClose", function(dateText, inst) {
	if ('' == dateText) {
		$( "#endtime" ).attr('disabled', true);
		$( "#endtime" ).val('');
		$('#starttime_warning').text('请选择项目开始时间');
	} else {
		$( "#endtime" ).attr('disabled', false);
		$('#starttime_warning').text('');
		// 开始日期选择完毕后，设置结束日期控件属性
		// 首先结束日期不能早于开始日期
		$( "#endtime" ).datepicker( "option", "minDate", $( "#starttime" ).val());
		var starttime_tmp = $( "#starttime" ).val().split('-');
	}
} );

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
					'modify_uid':{{$admin_user['id']}},
					@else
					'created_uid':{{$admin_user['id']}},
					@endif
					'name':$('#name').val(),
					'company_id':$('#company_id').val(),
					'project_id':$('#project_id').val(),
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