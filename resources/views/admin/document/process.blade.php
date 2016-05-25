@include('admin/static/header')
<div id="wrapper">
@include('admin/static/leftside')
	<div class="content-r">
		<div class="table-box">
			<div>
				<div class = "table_tit" style="float: left;padding: 15px;"><h1>{{$navigation}}</h1></div>
			</div>
			<div class="search-box">
				<span>{{$document['project_name']}}</span>
			</div>
			<div class="table-con">	
				<table>
					<thead>
						<tr>
							<th style="width: 10%;">审批人</th>
							<th style="width: 15%;">成本构成项</th>
							<th style="width: 25%;">说明</th>
							<th style="width: 10%;">状态</th>
							<th style="width: 10%;">审批时间</th>
							<th >操作</th>
						</tr>
					</thead>
					<tbody id= "dataListTable"　>
					@if($review->count())
						@foreach($review as $item)
						<tr id = "data_{{$item['id']}}" data-id = "{{$item['id']}}" >
							<td class= "_id" ><a title="部门：{{$department[$user[$item['review_uid']]['department_id']]['name']}}；级别：{{$group[$user[$item['review_uid']]['group_id']]['name']}}">{{$user[$item['review_uid']]['name']}}</a></td>
							<td class= "_name">@if($item['cost_id']) {{$costlist[$item['cost_id']]['name']}} @endif</td>
							<td class= "_name">{{$item['intro']}}</td>
							<td class= "_name">
								@if(1 == $item['status'])
								已审批
							@elseif (-1 == $item['status'])
								已拒绝
							@else
								待审批
							@endif
							</td>
							<td class= "_name">{{$item['review_at']}}</td>
							<td >
							@if(0 == $item['status'])
								@if($item['review_uid'] == $admin_user['id'])
								<button target="{{$item['id']}}" doc_id = "{{$item['document_id']}}" type="button" class="review_ok btn btn-info">通过</button>
								<button target="{{$item['id']}}" doc_id = "{{$item['document_id']}}" type="button" class="review_cancel btn btn-info">拒绝</button>
								@else
									@if($admin_user['id'] == $item['created_uid'])
								<button target="{{$item['id']}}" doc_id = "{{$item['document_id']}}" type="button" class="review_mail btn btn-info">催促</button>
									@endif
								@endif
							@elseif(1 == $item['status'])
								审核通过
							@elseif(-1 == $item['status'])
								被拒绝
							@elseif(-2 == $item['status'])
								之前审批人未审
							@endif
							</td>
						</tr>
						@endforeach
					@else
						<tr><td colspan="9">无数据</td></tr>
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
	$('button[class^="review_mail"]').click(function() {
		this_obj = $(this);
		$.ajax({
				type:"post",
				dataType:"json",
				url: "{{url('documents/review')}}/",
				data:{
					'type': 'mail',
					'id': $(this).attr('target'),
					'doc_id': $(this).attr('doc_id')
				},
				success:function($data) {
					if ($data.status == 'error') {
						alert($data.info);
					} else {
						alert('邮件已发出，请等待审批');
					}
				}
			});	
	});
	
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