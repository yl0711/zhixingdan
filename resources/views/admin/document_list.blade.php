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
				<form action = "{{url('documents/index')}}" id = "form_seach" name = "form_seach" method="post" >
					<input style="width: 150px;" type="text" name="name" placeholder="输入名称" value="{{$name}}" />
					<select id="company_id" name="company_id" class = "seachByStatus">
						<option value="0" >选择供应商</option>
						@foreach($companyList as $item)
						<option value="{{$item['id']}}" {{$item['selected']}}>{{$item['name']}}</option>
						@endforeach
					</select>
					<select id="project_id" name="project_id" class = "seachByStatus">
						<option value="0" >选择项目</option>
						@foreach($projectList as $item)
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
					<input type="text"  action = "{{url('documents/index')}}/?name={{$name}}&status={{$status}}&company_id＝{{$company_id}}&project_id={{$project_id}}" class = "pageSize" name = "pageSize"  value="{{$pageSize}}" >
				</form>	
				<div class="fr top-r">
					<i class="add-ico" id = "btn_add_admin_project" >添加执行单 </i>
				</div>
			</div>
			<div class="table-con">	
				<table>
					<thead>
						<tr>
							<th style="width: 5%;">ID</th>
							<th style="width: 10%;">名称</th>
							<th style="width: 15%;">所属供应商</th>
							<th style="width: 10%;">所属项目</th>
							<th style="width: 10%;">提交时间</th>
							<th style="width: 10%;">提交人</th>
							<th style="width: 8%;">审批状态</th>
							<th style="width: 10%;">当前审批人</th>
							<th style="width: 10%;" class = "timedate">最后修改时间</th>
							<th >操作</th>
						</tr>
					</thead>
					<tbody id= "dataListTable"　>
					@if($document->count())
						@foreach($document as $item)
						<tr id = "data_{{$item['id']}}" data-id = "{{$item['id']}}" >
							<td class= "_id" >{{$item['id']}}</td>
							<td class= "_name">{{$item['name']}}</td>
							<td class= "_name">{{$companyList[$item['company_id']]['name']}}</td>
							<td class= "_name">{{$projectList[$item['project_id']]['name']}}</td>
							<td class= "_name">{{$item['created_at']}}</td>
							<td class= "_name">@if ($item['created_uid']) {{$userList[$item['created_uid']]['name']}} @endif</td>
							<td class= "_name"></td>
							<td class= "_name">{{$item['updated_uid']}}</td>
							<td >{{$item['updated_at']}}</td>
							<td >
								<button target="{{$item['id']}}" type="button" class="modify btn btn-info">修改</button>
								<button target="{{$item['id']}}" type="button" class="process btn btn-success">流程</button>
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
			@if($document->count())
			{!! $document->appends(['name'=>$name, 'status'=>$status, 'company_id'=>$company_id, 'project_id'=>$project_id, 'pageSize'=>$pageSize])->render() !!}
			@endif
 		</div>
		<!--//网页备注-->	
	</div>
@include('admin/static/footer')
</div>
</body>
<script>
$(function() {
	$('#btn_add_admin_project').click(function() {
		window.location.href="{{url('documents/add')}}";
	});
	
	$('button[class^="modify"]').click(function() {
		window.location.href="{{url('documents/modify')}}/" + $(this).attr('target');
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