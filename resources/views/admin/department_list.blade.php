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
					<select name="search_type">
						<option value="name">部门</option>
						<option value="parent_name">上级部门</option>
					</select>
					<input type="text" name="search_input" />
					<button class = "btn_seach" onclick="form_seach.submit();">查询</button>
				</form>	
				<div class="fr top-r">
					<i class="add-ico" id = "btn_add" >添加部门 </i>
				</div>
			</div>
			<div class="table-con">	
				<table>
					<thead>
						<tr>
							<th style="width: 10%;">ID</th>
							<th style="width: 20%;">部门名称</th>
							<th style="width: 20%;">上级部门</th>
							<th style="width: 20%;" class = "timedate">最后修改时间</th>
							<th >操作</th>
						</tr>
					</thead>
					<tbody id= "dataListTable"　>
					@if(count($list))
						@foreach($list as $item)
						<tr id = "data_{{$item['id']}}" data-id = "{{$item['id']}}" >
							<td class= "_id" >{{$item['id']}}</td>
							<td class= "_name">{{$item['name']}}</td>
							<td >@if ($item['parentid'] > 0) {{$list[$item['parentid']]['name']}} @endif&nbsp;</td>
							<td >{{$item['updated_at']}}</td>
							<td >
								<a id="modify" href="{{url('department/modify')}}/{{$item['id']}}" target="_self">修改</a>&nbsp;
								<a id＝"authority" href="{{url('authority/department')}}/{{$item['id']}}" target="_self">权限</a>&nbsp;
							@if ($item['status'] == 1)
								<a id="status" href="{{url('department/status')}}/{{$item['id']}}">停用</a>
							@else
								<a id="status" href="{{url('department/status')}}/{{$item['id']}}">启用</a>
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
	$('#btn_add').click(function() {
		window.location.href="{{url('department/add')}}";
	});
});
</script>