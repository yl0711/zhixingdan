@include('admin/static/header')
<div id="wrapper">
@include('admin/static/leftside')
	
	<div class="content-r">
		<div class="table-box">
			<div class="search-box">
				<span>&nbsp;</span>
				<form action = "{{url('user/index')}}" id = "form_seach" name = "form_seach" method="post" >
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
							<th style="width: 15%;">发文是否需要审核</th>
							<th style="width: 15%;">内容查看权限</th>
							<th style="width: 20%;" class = "timedate">最后修改时间</th>
							<th >操作</th>
						</tr>
					</thead>
					<tbody id= "dataListTable"　>
						@if(count($userList))
						@foreach($userList as $item)
						<tr id = "data_{{$item['uid']}}" data-id = "{{$item['uid']}}" >
							<td class= "_id" >{{$item['uid']}}</td>
							<td class= "_name">{{$item['uname']}}</td>
							<td >{{$userGroup[$item['gid']]['gname']}}&nbsp;</td>
							<td >{{$item['article_check']}}</td>
							<td >{{$item['article_view']}}</td>
							<td >{{$item['updated_at']}}</td>
							<td >
								<a id="modify" href="{{url('user/modify')}}/{{$item['uid']}}" target="_self">修改</a>&nbsp;
								<a id＝"authority" href="{{url('authority/user')}}/{{$item['uid']}}" target="_self">权限</a>&nbsp;
							@if ($item['state'] == 1)
								<a id="state" href="{{url('user/state')}}/{{$item['uid']}}">停用</a>
							@else
								<a id="state" href="{{url('user/state')}}/{{$item['uid']}}">启用</a>
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
});
</script>