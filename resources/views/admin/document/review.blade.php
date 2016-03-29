@include('admin/static/header')
<div id="wrapper">
@include('admin/static/leftside')
	<div class="content-r">
		<div class="table-box">
			<div>
				<div class = "table_tit" style="float: left;padding: 15px;"><h1>{{$navigation}}</h1></div>
			</div>
			<div class="table-con" style="text-align: left; padding-top: 5px; padding-left: 10px; padding-bottom: 5px; font-size: 15px;">
				<span><a href="{{url('documents/index')}}">我创建的</a></span>
				<span><a href="{{url('documents/review')}}" style="color: #0000FF;">我审批的</a></span>
			</div>
			<div class="search-box">
				<span>&nbsp;</span>
					<!--每页显示条数-->
					<span class = "pageSizeSpan" >条/页</span>
					<input type="text"  action = "{{url('documents/index')}}/?type=review" class = "pageSize" name = "pageSize"  value="{{$pageSize}}" >
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
							<th style="width: 10%;">当前审批人</th>
							<th >操作</th>
						</tr>
					</thead>
					<tbody id= "dataListTable"　>
					@if($review->count())
						@foreach($review as $item)
						<tr id = "data_{{$item['id']}}" data-id = "{{$item['id']}}" >
							<td class= "_id" >{{$item['doc']['id']}}</td>
							<td class= "_name">{{$item['doc']['identifier']}}</td>
							<td class= "_name">{{$doc_cate1[$item['doc']['id']]}}</td>
							<td class= "_name">{{$item['doc']['project_name']}}</td>
							<td class= "_name">{{$item['doc']['company_name']}}</td>
							<td class= "_name">
								{{$item['doc']['starttime']}}<br />至<br />{{$item['doc']['endtime']}}
							</td>
							<td class= "_name">{{$userList[$item['doc']['pm_id']]['name']}}</td>
							<td class= "_name"></td>
							<td class= "_name">{{$item['doc']['money']}}</td>
							<td class= "_name">{{$userList[$item['now_review_uid']]['name']}}</td>
							<td >
							@if(1 == $item['status'])
								已审批
							@elseif (-1 == $item['status'])
								已拒绝
							@else
								@if(1 == $item['pre_status'])
								<button target="{{$item['id']}}" doc_id = "{{$item['doc']['id']}}" type="button" class="review_ok btn btn-info">审批</button>
								<button target="{{$item['id']}}" doc_id = "{{$item['doc']['id']}}" type="button" class="review_cancel btn btn-danger">拒绝</button>
								@elseif (-1 == $item['pre_status'])
								被之前审批人拒绝
								@else
								之前审批人未审
								@endif
							@endif
							</td>
						</tr>
						@endforeach
					@else
						<tr><td colspan="11">无数据</td></tr>
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
	$('button[class^="review_ok"]').click(function() {
		this_obj = $(this);
		if (confirm('是否要通过此审批')) {
			$.ajax({
				type:"post",
				dataType:"json",
				url: "{{url('documents/review')}}/",
				data:{
					'type': 'ok',
					'id': $(this).attr('target'),
					'doc_id': $(this).attr('doc_id')
				},
				success:function($data) {
					if ($data.status == 'error') {
						alert($data.info);
					} else {
						alert('已审批');
						this_obj.parent().html('已审批');
					}
				}
			});	
		}
	});
	
	$('button[class^="review_cancel"]').click(function() {
		this_obj = $(this);
		if (confirm('是否要拒绝此审批')) {
			$.ajax({
				type:"post",
				dataType:"json",
				url: "{{url('documents/review')}}/",
				data:{
					'type': 'cancel',
					'id': $(this).attr('target'),
					'doc_id': $(this).attr('doc_id')
				},
				success:function($data) {
					if ($data.status == 'error') {
						alert($data.info);
					} else {
						alert('已拒绝');
						this_obj.parent().html('已拒绝');
					}
				}
			});	
		}
	});
});
</script>