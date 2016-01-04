@include('admin/static/header')
<div id="wrapper">
@include('admin/static/leftside')
	<div class="content-r">
		<div class="table-box">
			<div class="search-box">
				<span>&nbsp;</span>
				<form action = "{{url('project/index')}}" id = "form_seach" name = "form_seach" method="post" >
					<button class = "btn_seach" onclick="form_seach.submit();">查询</button>
				</form>	
				<div class="fr top-r">
					<i class="add-ico" id = "btn_add_admin_project" >添加项目 </i>
				</div>
			</div>
			<div class="table-con">	
				<table>
					<thead>
						<tr>
							<th style="width: 5%;">ID</th>
							<th style="width: 10%;">项目名称</th>
							<th style="width: 15%;">所属供应商</th>
							<th style="width: 15%;">项目经理</th>
							<th style="width: 15%;">开始时间</th>
							<th style="width: 15%;">结束时间</th>
							<th style="width: 20%;" class = "timedate">最后修改时间</th>
							<th >操作</th>
						</tr>
					</thead>
					<tbody id= "dataListTable"　>
					@if(count($projectList))
						@foreach($projectList as $item)
						<tr id = "data_{{$item['id']}}" data-id = "{{$item['id']}}" >
							<td class= "_id" >{{$item['id']}}</td>
							<td class= "_name">{{$item['name']}}</td>
							<td class= "_name">{{$item['company_name']}}</td>
							<td class= "_name">{{$item['pm_name']}}</td>
							<td class= "_name">{{$item['starttime']}}</td>
							<td class= "_name">{{$item['endtime']}}</td>
							<td >{{$item['updated_at']}}</td>
							<td >
								<a id="modify" href="{{url('project/modify')}}/{{$item['id']}}" target="_self">修改</a>&nbsp;
								<a id="member" target="_self">项目成员</a>&nbsp;
								<a id="status" href="{{url('project/status')}}/{{$item['id']}}">
							@if ($item['status'] == 1)
								停用
							@else
								启用
							@endif
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
	$('#btn_add_admin_project').click(function() {
		window.location.href="{{url('project/add')}}";
	});
});
</script>