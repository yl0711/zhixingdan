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
							<td class="tl">
								<input name="name" id="name" class="form-control" placeholder="账号" @if ($page_type=='modify') value="{{$user['name']}}" @endif>
							@if ($page_type=='modify')
								<input id="id" type="hidden" value="{{$user['id']}}" />
								<input id="oldname" type="hidden" value="{{$user['name']}}" />
							@endif
							</td>
						</tr>
						
						<tr>
							<td class="tr"> 密码 :</td>
							<td class="tl">
								<input name="password" id="password" class="form-control" placeholder="@if ($page_type=='modify') 如密码不变请不要填写 @else 密码 @endif">
							</td>
						</tr>
						
						<tr>
							<td class="tr"> Email :</td>
							<td class="tl">
								<input name="email" id="email" class="form-control" placeholder="email" @if ($page_type=='modify') value="{{$user['email']}}" @endif>
							</td>
						</tr>
						
						<tr>
							<td class="tr"> 用户组 :</td>
							<td class="tl">
								<select id="group_id" name="group_id" class = "seachByStatus">
									<option value="0" >请选择</option>
								@foreach($group as $item)
									<option value="{{$item['id']}}" {{$item['selected']}}>{{$item['name']}}</option>
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
var article_check = article_view = 0;

$(function() {
	$('#admin_user_submit').bind('click', function(){
		if (false != check_submit_data()) {
			$.ajax({
				type:"post",
				dataType:"json",
				url: @if ($page_type=='modify') "{{url('user/modify')}}/{{$user['id']}}" @else "{{url('user/add')}}" @endif,
				data:{
					@if ($page_type=='modify')
					'id':$('#id').val(),
					'oldname':$('#oldname').val(),
					@endif
					'name':$('#name').val(),
					'password':$('#password').val(),
					'email':$('#email').val(),
					'group_id':$('#group_id').val(),
				},
				async:false,
				success:function($data) {
					if ($data.status == 'error') {
						alert($data.info);
					} else {
						if (confirm('数据提交成功, 是否返回列表页')) {
							window.location.href = "{{url('user/index')}}";
						}
					}
				}
			});
		}
	});
});

function check_submit_data() {
	if (0 == $('#name').val().trim().length) {
		alert('管理员账号不能为空');
		$('#name').focus();
		return false;
	}
	@if ($page_type=='add')
	if (0 == $('#password').val().trim().length) {
		alert('管理员密码不能为空');
		$('#password').focus();
		return false;
	}
	if (0 == $('#group_id').val()) {
		alert('请选择管理员所在管理组');
		return;
	}
	@endif
}
</script>