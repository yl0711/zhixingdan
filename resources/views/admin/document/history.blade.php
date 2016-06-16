@include('admin/static/header')
<div id="wrapper">
@include('admin/static/leftside')
	<div class="content-r">
		<div class="table-box">
			<div>
				<div class = "table_tit" style="float: left;padding: 15px;"><h1>{{$navigation}}</h1></div>
			</div>
			<div class="table-con">	
				<table>
					<thead>
						<tr>
							<th style="width: 5%;">ID</th>
							<th style="width: 15%;">分类</th>
							<th style="width: 10%;">项目名称</th>
							<th style="width: 10%;">客户名称</th>
							<th style="width: 10%;">项目周期</th>
							<th style="width: 8%;">创建人</th>
							<th style="width: 8%;">修改人</th>
							<th style="width: 10%;">金额</th>
							<th style="width: 10%;">修改时间</th>
							<th >操作</th>
						</tr>
					</thead>
					<tbody id= "dataListTable"　>
					@if(count($history))
						@foreach($history as $item)
						<tr id = "data_{{$item['id']}}" data-id = "{{$item['id']}}" >
							<td class= "_id" >{{$item['id']}}</td>
							<td class= "_name">{{$item['cate1']}}</td>
							<td class= "_name">{{$item['project_name']}}</td>
							<td class= "_name">{{$item['company_name']}}</td>
							<td class= "_name">
								{{$item['starttime']}}<br />至<br />{{$item['endtime']}}
							</td>
							<td class= "_name">{{$userList[$item['created_uid']]['name']}}</td>
							<td class= "_name">@if($item['modify_uid']) {{$userList[$item['modify_uid']]['name']}} @endif</td>
							<td class= "_name">{{$item['money']}}</td>
							<td class= "_name">{{$item['modify_at']}}</td>
							<td align="center">
								<a target="{{$item['id']}}" class="btn_show">预览</a>
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
	$('#btn_add_admin_documents').click(function() {
		window.location.href="{{url('documents/add')}}";
	});
	
	$('a[class="btn_modify"]').click(function() {
		window.location.href="{{url('documents/modify')}}/" + $(this).attr('target');
	});
	
	$('a[class="btn_process"]').click(function() {
		window.location.href="{{url('documents/process')}}/" + $(this).attr('target');
	});
	
	$('a[class="btn_show"]').click(function() {
		window.location.href="{{url('documents/show')}}/" + $(this).attr('target');
	});
	
	$('button[class^="on-off"]').click(function() {
		this_obj = $(this);
		if (confirm('是否要将' + this_obj.attr('_name') + $(this).text().trim())) {
			$.ajax({
				type:"get",
				dataType:"json",
				url: "{{url('documents/status')}}/" + $(this).attr('target'),
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