@include('admin/static/header')
<div id="wrapper">
@include('admin/static/leftside')
	
	<div class="content-r">
		<div class="table-box">
			<div class="search-box">
				<span>&nbsp;</span>
				<form action = "{{url('usergroup/index')}}" id = "form_seach" name = "form_seach" method="post" >
					<button class = "btn_seach" onclick="form_seach.submit();">查询</button>
				</form>	
				<div class="fr top-r">
					<i class="add-ico" id = "btn_add_admin_usergroup" > 添加管理组 </i>
				</div>
			</div>
			<div class="table-con">	
				<table>
					<thead>
						<tr>
							<th style="width: 5%;">ID</th>
							<th style="width: 10%;">管理组</th>
							<th style="width: 15%;">上级管理组</th>
							<th style="width: 20%;" class = "timedate">最后修改时间</th>
							<th >操作</th>
						</tr>
					</thead>
					<tbody id= "dataListTable"　>
						@if (count($list))
						@foreach ($list as $item)
						<tr id = "data_{{$item['id']}}" data-id = "{{$item['id']}}" >
							<td class= "_id" >{{$item['id']}}</td>
							<td class= "_name">{{$item['name']}}</td>
							<td >@if ($item['parentid'] > 0) {{$list[$item['parentid']]['name']}} @endif</td>
							<td >{{$item['updated_at']}}</td>
							<td >
								<a id="modify" href="{{url('usergroup/modify')}}/{{$item['id']}}" target="_self">修改</a>&nbsp;
								<a id＝"authority" href="{{url('authority/group')}}/{{$item['id']}}" target="_self">权限</a>&nbsp;
							@if ($item['status'] == 1)
								<a id="state" href="{{url('usergroup/state')}}/{{$item['id']}}">停用</a>
							@else
								<a id="state" href="{{url('usergroup/state')}}/{{$item['id']}}">启用</a>
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
	$('#btn_add_admin_usergroup').click(function() {
		window.location.href="{{url('usergroup/add')}}";
	});
});
</script>