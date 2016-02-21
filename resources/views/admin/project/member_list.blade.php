@include('admin/static/header')
<div id="wrapper">
@include('admin/static/leftside')
	<div class="content-r">
		<div class="table-box">
			<div>
				<div class = "table_tit" style="float: left;padding: 15px;"><h1>{{$navigation}}</h1></div>
			</div>
			<div class="search-box">
				<span>{{$project['name']}} 团队列表</span>
				<div class="fr top-r">
					<i class="add-ico" id = "btn_return" >返回项目列表 </i>
					<i class="add-ico" id = "btn_manage_member" >添加团队成员 </i>
				</div>
			</div>
			<div class="table-con">	
				<table>
					<thead>
						<tr>
							<th style="width: 15%;">用户</th>
							<th style="width: 15%;">部门</th>
							<th style="width: 15%;">用户组</th>
							<th style="width: 15%;">角色</th>
							<th style="width: 15%;">加入日期</th>
							<th >操作</th>
						</tr>
					</thead>
					<tbody id= "dataListTable"　>
					@if(count($memberList))
						@foreach($memberList as $item)
						<tr id = "data_{{$item['id']}}" data-id = "{{$item['id']}}" >
							<td class= "_name">{{$userList[$item['user_id']]['name']}}</td>
							<td class= "_name">{{$department[$item['user_id']]['name']}}</td>
							<td class= "_name">{{$userGroup[$item['user_id']]['name']}}</td>
							<td class= "_name">@if (1 == $item['pm']) 项目经理 @else 普通成员 @endif</td>
							<td >{{$item['created_at']}}</td>
							<td >
								<button target="{{$item['id']}}" type="button" class="delete btn btn-info">删除</button>
							</td>
						</tr>
						@endforeach
					@else
						<tr><td colspan="10">无数据</td></tr>
					@endif
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
	$('#btn_return').click(function() {
		window.location.href = "{{url('project/index')}}";
	});
	
	$('#btn_manage_member').click(function() {
		window.location.href = "{{url('project/addmember')}}/{{$project['id']}}";
	});
});
</script>