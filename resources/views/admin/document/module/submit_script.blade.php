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

@if ($page_type=='modify')
$("#endtime").attr('disabled', false);
// 开始日期选择完毕后，设置结束日期控件属性
// 首先结束日期不能早于开始日期
$( "#endtime" ).datepicker( "option", "minDate", $( "#starttime" ).val());
@endif

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
	$('#add-cost').on('click',function(){
		modalView('show' ,true, '添加成本构成');
		$('.modal-body').load("{{url('documents/cost')}}");
	});
	
	$('#form_submit').bind('click', function(){
		if (false != check_submit_data()) {
			$.ajax({
				type:"post",
				dataType:"json",
				url: @if ($page_type=='modify') "{{url('documents/modify')}}/{{$document['id']}}" @else "{{url('documents/add')}}" @endif,
				data:{
					@if ($page_type=='modify')
					'id':{{$document['id']}},
					@endif
					'created_uid':{{$admin_user['id']}},
					'company_name':$('#company_name').val(),
					'project_name':$('#project_name').val(),
					'cate1':get_cate1(),
					'starttime':$('#starttime').val(),
					'endtime':$('#endtime').val(),
					'pm_id':$('#pm_id').val(),
					'issign':$('#issign').val(),
					'money':$('#money').val(),
					'author_id':$('#author_id').val(),
					'moneytime':$('#moneytime').val(),
					'cost_select':get_cost_select(),
					'cost_intro':get_cost_intro(),
					'cost_money':get_cost_money(),
					'cost_attach':get_cost_attach(),
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

function docmentsCostCallback(data) {
	if (data.status == 'error') {
		alert(data.info);
	} else {
		var html = '<tr>';
		html += '<td>'+data.data.cost_name+'<input type="hidden" id="select_cost_id[]" name="select_cost_id[]" value="'+data.data.cost_id+'" /></td>';
		html += '<td>'+data.data.money+'<input type="hidden" id="select_cost_money[]" name="select_cost_money[]" value="'+data.data.money+'" /></td>';
		html += '<td>'+data.data.intro+'<input type="hidden" id="select_cost_intro[]" name="select_cost_intro[]" value="'+data.data.intro+'" /></td>';
		if (data.data.attach) {
			html += '<td><a href="'+data.data.attach.imageUrl+'" target="_blank">查看</a><input type="hidden" id="select_cost_attach[]" name="select_cost_attach[]" value="'+data.data.attach.attach_id+'" /></td>';
		} else {
			html += '<td>无<input type="hidden" id="select_cost_attach[]" name="select_cost_attach[]" value="" /></td>';
		}
		html += '<td name="delcost">删除</td>';
		html += '</tr>';
		$('#cost-list').append(html);
		del_cost_data();
		modalView('hide');
	}
}

function del_cost_data() {
	$('td[name=delcost]').unbind('click');
	$('td[name=delcost]').click(function() {
		$(this).parent().remove();
	});
}

function get_cate1() {
	var value = new Array();
	$('[id^="cate1"]').each(function() {
		if ($(this).prop('checked') == true) {
			value.push($(this).val());
		}
	});
	return value;
}

function get_cost_select() {
	var value = new Array();
	$('[id^="select_cost_id"]').each(function() {
		value.push($(this).val());
	});
	return value;
}
function get_cost_intro() {
	var value = new Array();
	$('[id^="select_cost_intro"]').each(function() {
		value.push($(this).val());
	});
	return value;
}
function get_cost_money() {
	var value = new Array();
	$('[id^="select_cost_money"]').each(function() {
		value.push($(this).val());
	});
	return value;
}
function get_cost_attach() {
	var value = new Array();
	$('[id^="select_cost_attach"]').each(function() {
		value.push($(this).val());
	});
	return value;
}

function check_submit_data() {
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