@include('admin/static/header')
<div id="wrapper">
@include('admin/static/leftside')

	<div class="content-r">
		<div class="table-box">
			<div>
				<div class = "table_tit" style="float: left;padding: 15px;"><h1>{{$navigation}}</h1></div>
			</div>
			<div class="search-box">
				<span>{{$project['name']}} 添加团队成员</span>
			</div>
			<div style="width:700px;margin: 0 auto;border-left: 1px solid #ddd; " class="table-con">	
				<table class ="prodict_edit">
					<tbody >
						<tr>
							<td class="tr" style="width: 30%;"> 部门 :</td>
							<td class="tl">
								<select id="department_select" name="department_id">
									<option value="0">请选择部门</option>
								@foreach ($department as $item)
									<option value="{{$item['id']}}">{{$item['name']}}</option>
								@endforeach
								</select>
							</td>
						</tr>
						
						<tr>
							<td class="tr"> 用户 :</td>
							<td class="tl">
								<select id="user_select" name="user_id" disabled="disabled">
									<option value="0">请先选择部门</option>
								</select>
							</td>
						</tr>
						<tr>
							<th colspan = "2" >
								<button type="button" id="submit" class="btn btn-success" >确认提交</button>
							</th>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	@include('admin/static/footer')
</div>
</body>
<script>
userList = eval('({!! $userList !!})');

$(function() {
	$('#department_select').change(function() {
		var department = $(this).val();
		if (0 == department) {
			$("#user_select option[value='0']").attr("selected", true);
			$('#user_select').attr('disabled', true);
		} else {
			select_append = '';
			for (var key in userList[department]) {  
				select_append += '<option value="' + userList[department][key]['id'] + '">' + userList[department][key]['showname'] + '</option>';
			}
			$("#user_select option:gt(0)").remove();
			if (!select_append) {
				$('#user_select').attr('disabled', true);
				alert('这个部门下没有可用用户了');
			} else {
				$("#user_select").append(select_append);
				$('#user_select').attr('disabled', false);
			}
		}
	});
	
	$('#submit').bind('click', function(){
		if (false != check_submit_data()) {
			$.ajax({
				type:"post",
				dataType:"json",
				url: "{{url('project/addmember')}}/{{$project['id']}}",
				data:{
					'user_id':$('#user_select').val()
				},
				async:false,
				success:function($data) {
					if ($data.status == 'error') {
						alert($data.info);
					} else {
						if (confirm('数据提交成功, 是否返回列表页')) {
							window.location.href = "{{url('project/member')}}/{{$project['id']}}";
						}
					}
				}
			});
		}
	});
});

function check_submit_data() {
	if (0 == $('#user_select').val()) {
		alert('还没有选择成员');
		return false;
	}
}
</script>