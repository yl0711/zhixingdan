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
				<form action = "{{url('project/member')}}" id = "form_seach" name = "form_seach" method="post" >
					<button class = "btn_seach" onclick="form_seach.submit();">查询</button>
				</form>	
				<div class="fr top-r">
					<i class="add-ico" id = "btn_add_project_member" >添加组员 </i>
				</div>
			</div>
			<div class="table-con">	
				<table>
					<thead>
						<tr>
							<th style="width: 5%;">成员ID</th>
							<th style="width: 15%;">成员名称</th>
							<th style="width: 15%;">项目名称</th>
							<th style="width: 15%;">项目身份</th>
							<th style="width: 15%;">加入日期</th>
							<th style="width: 10%;" class = "timedate">最后修改时间</th>
							<th >操作</th>
						</tr>
					</thead>
					<tbody id= "dataListTable"　>
					@if(count($memberList))
						@foreach($memberList as $item)
						<tr id = "data_{{$item['id']}}" data-id = "{{$item['id']}}" >
							<td class= "_id" >{{$item['user_id']}}</td>
							<td class= "_name">{{$userList[$item['user_id']]['name']}}</td>
							<td class= "_name">{{$project['name']}}</td>
							<td class= "_name">
								@if (1 == $item['pm']) 项目经理 @else 普通成员 @endif
							</td>
							<td class= "_name">{{$item['created_at']}}</td>
							<td >{{$item['updated_at']}}</td>
							<td >
								<a id="modify" href="{{url('project/modify')}}/{{$item['id']}}" target="_self">修改</a>&nbsp;
								<a id="status" href="{{url('project/status')}}/{{$item['id']}}">
								@if ($item['status'] == 1) 停用 @else 启用 @endif
								</a>
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
	$('#btn_add_project_member').click(function() {
		window.location.href = "{{url('project/addmember')}}/{{$project['id']}}";
	});
});
</script>