@include('admin/static/header')
<div id="wrapper">
@include('admin/static/leftside')
	<div class="content-r">
		<div class="table-box">
			<div>
				<div class = "table_tit" style="float: left;padding: 15px;"><h1>{{$navigation}}</h1></div>
			</div>
			<div class="table-con">	
				<table>
					<tbody id= "dataListTable"　>
						<tr id = "data_email_host">
							<td class= "_id" >是否发送邮件通知</td>
							<td style="text-align: left;" class= "_name">
								<input type="radio" id="email_open_1" name="email_open" value="1" {{$setting['email_open'][1]}}><label for="email_open_1">是</label>
								<input type="radio" id="email_open_0" name="email_open" value="0" {{$setting['email_open'][0]}}><label for="email_open_0">否</label>
							</td>
						</tr>
						<tr id = "data_email_host">
							<td class= "_id" >系统邮箱服务器地址</td>
							<td style="text-align: left;" class= "_name">
								<input type="text" id="email_host" name="email_host" style="width: 300px;" value="@if(isset($setting['email_host']) && $setting['email_host']){{$setting['email_host']}}@endif" />
							</td>
						</tr>
						<tr id = "data_email_port">
							<td class= "_id" >系统邮箱端口</td>
							<td style="text-align: left;" class= "_name">
								<input type="text" id="email_port" name="email_port" style="width: 300px;" value="@if(isset($setting['email_port']) && $setting['email_port']){{$setting['email_port']}}@endif" />
							</td>
						</tr>
						<tr id = "data_email_user">
							<td class= "_id" >系统邮箱账号</td>
							<td style="text-align: left;" class= "_name">
								<input type="text" id="email_user" name="email_user" style="width: 300px;" value="@if(isset($setting['email_user']) && $setting['email_user']){{$setting['email_user']}}@endif" />
							</td>
						</tr>
						<tr id = "data_email_pwd">
							<td class= "_id" >系统邮箱密码</td>
							<td style="text-align: left;" class= "_name">
								<input type="text" id="email_pwd" name="email_pwd" style="width: 300px;" value="@if(isset($setting['email_pwd']) && $setting['email_pwd']){{$setting['email_pwd']}}@endif" />
							</td>
						</tr>
						<tr id = "data_email_name">
							<td class= "_id" >系统邮箱显示名</td>
							<td style="text-align: left;" class= "_name">
								<input type="text" id="email_name" name="email_name" style="width: 300px;" value="@if(isset($setting['email_name']) && $setting['email_name']){{$setting['email_name']}}@endif" />
							</td>
						</tr>
						<tr id = "data_email_user">
							<td class= "_id" >CEO审批比例</td>
							<td style="text-align: left;" class= "_name">
								<input type="text" id="ceo_check_value" name="ceo_check_value" value="@if(isset($setting['ceo_check_value']) && $setting['ceo_check_value']){{$setting['ceo_check_value']}}@endif" /> %
							</td>
						</tr>
						<tr id = "data_ceo_user">
							<td class= "_id" >指定审批CEO用户ID</td>
							<td style="text-align: left;" class= "_name">
								<input type="text" id="ceo_userid" name="ceo_userid" value="@if(isset($setting['ceo_userid']) && $setting['ceo_userid']){{$setting['ceo_userid']}}@endif" />
							</td>
						</tr>
						<tr><td colspan="2"><input type="button" id="submit" value="提交" /></td></tr>
					</tbody>
				</table>
			</div>
 		</div>
		<!--//网页备注-->	
	</div>
@include('admin/static/footer')
</div>
</body>
<script>
$(function() {
	$('#submit').click(function() {
		$.ajax({
			type:"post",
			dataType:"json",
			url: "{{url('setting/setSys')}}",
			data:{
				'email_host':$('#email_host').val(),
				'email_port':$('#email_port').val(),
				'email_user':$('#email_user').val(),
				'email_pwd':$('#email_pwd').val(),
				'email_name':$('#email_name').val(),
				'email_open':$('input:radio[name=email_open]:checked').val(),
				'ceo_check_value':$('#ceo_check_value').val(),
				'ceo_userid':$('#ceo_userid').val()
			},
			async:false,
			success:function($data) {
				if ($data.status == 'error') {
					alert($data.info);
				} else {
					alert('参数更新成功');
				}
			}
		});	
	});
});
</script>