@include('admin/static/header')
<div id="wrapper">
@include('admin/static/leftside')

	<div class="content-r">
		<div class="table-box">
			<div class="search-box">
				<div class = "table_tit" style="float: left;padding: 15px;"><h1>{{$navigation}}</h1></div>
			</div>
			<div style="width:700px;margin: 0 auto;border-left: 1px solid #ddd; " class="table-con">	
				<table class ="prodict_edit">
					<tbody >
						<tr>
							<td class="tr"> 账号 :</td>
							<td class="tl">{{$user['name']}}</td>
						</tr>
						<tr>
							<td class="tr"> 所属管理组 :</td>
							<td class="tl">{{$userGroup['name']}}</td>
						</tr>
						<tr>
							<td class="tr"> 直属上级 :</td>
							<td class="tl">
								<select id="parent">
									<option value="0">选择用户的直属上级</option>
								@foreach ($userList as $item)
									<option value="{{$item['id']}}">@if($item['group_id'] && isset($grouplist[$item['group_id']])) 【{{$grouplist[$item['group_id']]}}】 @endif {{$item['name']}}</option>
								@endforeach
								</select>
							</td>
						</tr>
						<tr>
							<th colspan = "2" >
								<button type="button" id="admin_user_submit" class="btn btn-success" >确认提交</button>
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
var area_id = '';

$(function() {
	$('#admin_user_submit').bind('click', function(){
		if (false != check_submit_data()) {
			$.ajax({
				type:"post",
				dataType:"json",
				url: "{{url('user/parent')}}/{{$user['id']}}",
				data:{
					'parent':$('#parent').val()
				},
				async:false,
				success:function($data) {
					if ($data.status == 'error') {
						alert($data.info);
					} else {
						if (confirm('数据提交成功')) {
							window.location.href = "{{url('user/index')}}/";
						}
					}
				}
			});
		}
	});
});

function check_submit_data() {
	if ($('#parent').val() == 0) {
		alert('请选择你的直属上级');
		return false;
	}
}
</script>