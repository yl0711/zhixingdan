<script>
/**
 *  设置日期选择控件，用于选择开始和结束日期
 */
var date = new Date();

$( "#starttime" ).datepicker({
	dateFormat: "yy-mm-dd",
	changeMonth: true,
    changeYear: true,
    minDate: 0,
	yearRange: date.getFullYear() + ":" + parseInt(date.getFullYear()+10)
});

$( "#endtime" ).datepicker({
	dateFormat: "yy-mm-dd",
	changeMonth: true,
    changeYear: true
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
		$( "#endtime" ).datepicker( "option", "yearRange", starttime_tmp[1] + ":" + parseInt(starttime_tmp[1]+10));
	}
} );

$(function() {
	$('#form_submit').bind('click', function(){
		if (false != check_submit_data()) {
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
});

function check_submit_data() {
	if (0 == $('#name').val().trim().length) {
		alert('项目名称不能为空');
		$('#name').focus();
		return false;
	}
	if (0 == $('#company_id').val().trim().length) {
		alert('没有设置项目所属供应商');
		return false;
	}
	if (0 == $('#pm_id').val().trim().length) {
		alert('没有设置项目经理');
		return false;
	}
	if (0 == $('#starttime').val().trim().length) {
		alert('没有设置项目的开始日期');
		return false;
	}
	if (0 == $('#endtime').val().trim().length) {
		alert('没有设置项目的结束日期');
		return false;
	}
}
</script>