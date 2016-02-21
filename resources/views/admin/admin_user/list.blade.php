@include('admin/static/header')
<div id="wrapper">
@include('admin/static/leftside')
	<div class="content-r">
		<div class="table-box">
			<div>
				<div class = "table_tit" style="float: left;padding: 15px;"><h1>{{$navigation}}</h1></div>
			</div>
			<div class="search-box">
				<span>&nbsp;</span>
				<form action = "{{url('user/index')}}" id = "form_seach" name = "form_seach" method="post" >
					<input style="width: 150px;" type="text" name="name" placeholder="输入名称" value="{{ $name}}" />
					<select id="group_id" name="group_id" class = "seachByStatus">
						<option value="0" >选择管理组</option>
						@foreach($grouplist as $item)
						<option value="{{$item['id']}}" {{$item['selected']}}>{{$item['name']}}</option>
						@endforeach
					</select>
					<select id="department_id" name="department_id" class = "seachByStatus">
						<option value="0" >选择部门</option>
						@foreach($departmentlist as $item)
						<option value="{{$item['id']}}" {{$item['selected']}}>{{$item['name']}}</option>
						@endforeach
					</select>
					<select  class = "seachByStatus" name="status">
						<option value="2" @if($status==2) selected @endif >全部</option>
						<option value="1" @if($status==1) selected @endif >已打开</option>
						<option value="0" @if($status==0) selected @endif>已关闭</option>
					</select>
					<button class = "btn_seach" onclick="form_seach.submit();">查询</button>
					<!--每页显示条数-->
					<span class = "pageSizeSpan" >条/页</span>
					<input type="text"  action = "{{url('user/index')}}/?name={{$name}}&status={{$status}}&group_id＝{{$group_id}}&department_id＝{{$department_id}}" class = "pageSize" name = "pageSize"  value="{{$pageSize}}" >
					<button class = "btn_seach" onclick="form_seach.submit();">查询</button>
				</form>	
				<div class="fr top-r">
					<i class="add-ico" id = "btn_add_admin_user" >添加管理员 </i>
				</div>
			</div>
			<div class="table-con">	
				<table>
					<thead>
						<tr>
							<th style="width: 5%;">ID</th>
							<th style="width: 10%;">管理员</th>
							<th style="width: 15%;">所属管理组</th>
							<th style="width: 15%;">所属部门</th>
							<th style="width: 20%;" class = "timedate">最后修改时间</th>
							<th >操作</th>
						</tr>
					</thead>
					<tbody id= "dataListTable"　>
					@if($userList->count())
						@foreach($userList as $item)
						<tr id = "data_{{$item['id']}}" data-id = "{{$item['id']}}" >
							<td class= "_id" >{{$item['id']}}</td>
							<td class= "_name">{{$item['name']}}</td>
							<td >@if (0 < $item['group_id']) {{$grouplist[$item['group_id']]['name']}} @endif</td>
							<td >@if (0 < $item['department_id']) {{$departmentlist[$item['department_id']]['name']}} @endif</td>
							<td >{{$item['updated_at']}}</td>
							<td >
								<button target="{{$item['id']}}" type="button" class="modify btn btn-info">修改</button>
								<button target="{{$item['id']}}" type="button" class="authority btn btn-success" >权限</button>
								@if(1 == $item['status'])
								<button target="{{$item['id']}}" _name="{{$item['name']}}" type="button" class="on-off btn btn-danger">关闭</button>
								@else
								<button target="{{$item['id']}}" _name="{{$item['name']}}" type="button" class="on-off btn btn-warning">开启</button>
								@endif
							</td>
						</tr>
						@endforeach
					@else
						<tr><td colspan="10">无数据</td></tr>
					@endif
					</tbody>
				</table>
			</div>
			@if($userList->count())
			{!! $userList->appends(['name'=>$name, 'status'=>$status, 'group_id'=>$group_id, 'department_id'=>$department_id, 'pageSize'=>$pageSize])->render() !!}
			@endif
 		</div>
		<!--//网页备注-->	
	</div>
@include('admin/static/footer')
</div>
</body>
<script>
$(function() {
	$('#btn_add_admin_user').click(function() {
		window.location.href="{{url('user/add')}}";
	});
	
	$('button[class^="modify"]').click(function() {
		window.location.href="{{url('user/modify')}}/" + $(this).attr('target');
	});
	
	$('button[class^="authority"]').click(function() {
		window.location.href="{{url('authority/user')}}/" + $(this).attr('target');
	});
	
	$('button[class^="on-off"]').click(function() {
		this_obj = $(this);
		if (confirm('是否要将' + this_obj.attr('_name') + $(this).text().trim())) {
			$.ajax({
				type:"get",
				dataType:"json",
				url: "{{url('user/status')}}/" + $(this).attr('target'),
				async:false,
				success:function($data) {
					if ($data.status == 'error') {
						alert($data.info);
					} else {
						alert(this_obj.attr('_name') + '状态修改成功');
						if (1 == $data.data) {
							this_obj.text('关闭');
							this_obj.removeClass("btn-warning");
							this_obj.addClass("btn-danger");
						} else {
							this_obj.text('开启');
							this_obj.removeClass("btn-danger");
							this_obj.addClass("btn-warning");
						}
					}
				}
			});	
		}
	});
});
</script>