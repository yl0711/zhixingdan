@include('admin/static/header')
<div id="wrapper">
@include('admin/static/leftside')
	<div class="content-r">
		<div class="table-box">
			<div>
				<div class = "table_tit" style="float: left;padding: 15px;"><h1>{{$navigation}}</h1></div>
			</div>
			<div class="table-con" style="text-align: left; padding-top: 5px; padding-left: 10px; padding-bottom: 5px; font-size: 15px;">
				<span><a href="{{url('documents/index')}}" style="color: #0000FF;">我创建的</a></span>
				<span><a href="{{url('documents/review')}}">我审批的</a></span>
			</div>
			<div class="search-box">
				<span>&nbsp;</span>
				<form action = "{{url('documents/index')}}" id = "form_seach" name = "form_seach" method="post" >
					<input style="width: 150px;" type="text" name="name" placeholder="输入名称" value="{{$name}}" />
					<select id="cate1" name="cate1" class = "seachByStatus">
						<option value="0" >选择工作类别</option>
					@if (count($gongzuoleibie))
						@foreach($gongzuoleibie as $item)
						<option value="{{$item['id']}}" {{$item['selected']}}>{{$item['name']}}</option>
						@endforeach
					@endif
					</select>
					<select  class = "seachByStatus" name="status">
						<option value="2" @if($status==2) selected @endif>全部</option>
						<option value="1" @if($status==1) selected @endif>已打开</option>
						<option value="0" @if($status==0) selected @endif>已关闭</option>
					</select>
					<button class = "btn_seach" onclick="form_seach.submit();">查询</button>
					<!--每页显示条数-->
					<span class = "pageSizeSpan" >条/页</span>
					<input type="text"  action = "{{url('documents/index')}}/?name={{$name}}&status={{$status}}&cate1＝{{$cate1}}" class = "pageSize" name = "pageSize"  value="{{$pageSize}}" >
				</form>	
				<div class="fr top-r">
					<i class="add-ico" id = "btn_add_admin_documents" >添加执行单 </i>
				</div>
			</div>
			<div class="table-con">	
				<table>
					<thead>
						<tr>
							<th style="width: 5%;">ID</th>
							<th style="width: 10%;">编号</th>
							<th style="width: 15%;">分类</th>
							<th style="width: 10%;">项目名称</th>
							<th style="width: 10%;">客户名称</th>
							<th style="width: 10%;">项目周期</th>
							<th style="width: 8%;">负责人</th>
							<th style="width: 10%;">状态</th>
							<th style="width: 10%;">金额</th>
							<th >操作</th>
						</tr>
					</thead>
					<tbody id= "dataListTable"　>
					@if($document->count())
						@foreach($document as $item)
						<tr id = "data_{{$item['id']}}" data-id = "{{$item['id']}}" >
							<td class= "_id" >{{$item['id']}}</td>
							<td class= "_name">{{$item['identifier']}}</td>
							<td class= "_name">{{$item['cate1']}}</td>
							<td class= "_name">{{$item['project_name']}}</td>
							<td class= "_name">{{$item['company_name']}}</td>
							<td class= "_name">
								{{$item['starttime']}}<br />至<br />{{$item['endtime']}}
							</td>
							<td class= "_name">{{$userList[$item['pm_id']]['name']}}</td>
							<td class= "_name">@if(1==$item['issign']) 已签 @else 未签 @endif</td>
							<td class= "_name">{{$item['money']}}</td>
							<td >
							@if(1 == $item['status'])
								<button target="{{$item['id']}}" type="button" class="modify btn btn-info">修改</button>
								<button target="{{$item['id']}}" type="button" class="process btn btn-success">流程</button>
								<!--<button target="{{$item['id']}}" _name="{{$item['name']}}" type="button" class="on-off btn btn-danger">将此单作废</button>-->
							@else
								已作废
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
			{!! $document->appends(['name'=>$name, 'status'=>$status, 'cate1'=>$cate1, 'pageSize'=>$pageSize])->render() !!}
			@endif
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
	
	$('button[class^="modify"]').click(function() {
		window.location.href="{{url('documents/modify')}}/" + $(this).attr('target');
	});
	
	$('button[class^="process"]').click(function() {
		window.location.href="{{url('documents/process')}}/" + $(this).attr('target');
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