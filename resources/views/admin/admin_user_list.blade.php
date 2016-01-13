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
							<th style="width: 20%;" class = "timedate">最后修改时间</th>
							<th >操作</th>
						</tr>
					</thead>
					<tbody id= "dataListTable"　>
					@if(count($userList))
						@foreach($userList as $item)
						<tr id = "data_{{$item['id']}}" data-id = "{{$item['id']}}" >
							<td class= "_id" >{{$item['id']}}</td>
							<td class= "_name">{{$item['name']}}</td>
							<td >{{$userGroup[$item['group_id']]['name']}}&nbsp;</td>
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