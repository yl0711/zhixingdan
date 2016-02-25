<script>
var costlist = '<?php echo addslashes($costList) ?>';
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
					'modify_uid':{{$admin_user['id']}},
					@else
					'created_uid':{{$admin_user['id']}},
					@endif
					'company_name':$('#company_name').val(),
					'project_name':$('#project_name').val(),
					'cate1':$('#gongzuoleibie').val(),
					'cate2':$('#gongzuofenxiang').val(),
					'cate3':$('#gongzuoxiangmu').val(),
					'starttime':$('#starttime').val(),
					'endtime':$('#endtime').val(),
					'pm_id':$('#pm_id').val(),
					'status':$('#status').val(),
					'money':$('#money').val(),
					'author_id':$('#author_id').val(),
					'moneytime':$('#moneytime').val(),
					
					'kpi':$('#kpi').val()
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
	if (0 == $('#gongzuoleibie').val() || 0 == $('#gongzuofenxiang').val() || 0 == $('#gongzuoxiangmu').val()) {
		alert('项目分类必须全部选择');
		return false;
	}
	if (0 == $('#company_name').val().trim().length) {
		alert('请填写客户名称');
		$('#company_name').focus();
		return false;
	}
	if (0 == $('#project_name').val().trim().length) {
		alert('请填写项目名称');
		$('#project_name').focus();
		return false;
	}
	if (0 == $('#starttime').val().trim().length || '0000-00-00' == $('#starttime').val().trim().length) {
		alert('请设置项目开始日期');
		$('#starttime').focus();
		return false;
	}
	if (0 == $('#endtime').val().trim().length || '0000-00-00' == $('#endtime').val().trim().length) {
		alert('请设置项目结束日期');
		$('#endtime').focus();
		return false;
	}
	if (0 == $('#pm_id').val()) {
		alert('请选择项目负责人');
		return false;
	}
	if (0 == $('#money').val().trim().length) {
		alert('请填写金额');
		$('#money').focus();
		return false;
	}
	if (0 == $('#money').val()) {
		alert('金额不能为0');
		$('#money').focus();
		return false;
	}
	if (isNaN($('#money').val())) {
		alert('金额应为数字');
		$('#money').focus();
		return false;
	}
	if (0 == $('#author_id').val()) {
		alert('请选择项目对接人');
		return false;
	}
	if (0 == $('#moneytime').val().trim().length || '0000-00-00' == $('#moneytime').val().trim().length) {
		alert('请设置项目回款日期');
		$('#moneytime').focus();
		return false;
	}
}
</script>