@include('admin/static/header')
<div id="wrapper">
@include('admin/static/leftside')
	<div class="content-r">
		<div class="table-box">
			<div class="search-box">
				<span>&nbsp;</span>
				<form action = "{{url('company/index')}}" id = "form_seach" name = "form_seach" method="post" >
					<button class = "btn_seach" onclick="form_seach.submit();">查询</button>
				</form>	
				<div class="fr top-r">
					<i class="add-ico" id = "btn_add_admin_company" >添加供应商 </i>
				</div>
			</div>
			<div class="table-con">	
				<table>
					<thead>
						<tr>
							<th style="width: 5%;">ID</th>
							<th style="width: 10%;">供应商名称</th>
							<th style="width: 15%;">地址</th>
							<th style="width: 15%;">联系人</th>
							<th style="width: 15%;">联系电话</th>
							<th style="width: 15%;">Email</th>
							<th style="width: 15%;">主页</th>
							<th style="width: 20%;" class = "timedate">最后修改时间</th>
							<th >操作</th>
						</tr>
					</thead>
					<tbody id= "dataListTable"　>
					@if(count($companyList))
						@foreach($companyList as $item)
						<tr id = "data_{{$item['id']}}" data-id = "{{$item['id']}}" >
							<td class= "_id" >{{$item['id']}}</td>
							<td class= "_name">{{$item['name']}}</td>
							<td class= "_name">{{$item['addr']}}</td>
							<td class= "_name">{{$item['person']}}</td>
							<td class= "_name">
								座机：{{$item['phone']}}<br />
								手机：{{$item['mobile']}}
							</td>
							<td class= "_name">{{$item['email']}}</td>
							<td class= "_name">{{$item['homepage']}}</td>
							<td >{{$item['updated_at']}}</td>
							<td >
								<a id="modify" href="{{url('company/modify')}}/{{$item['id']}}" target="_self">修改</a>&nbsp;
								<a id="project" target="_self">项目</a>&nbsp;
								<a id="status" href="{{url('company/status')}}/{{$item['id']}}">
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
	$('#btn_add_admin_company').click(function() {
		window.location.href="{{url('company/add')}}";
	});
});
</script>