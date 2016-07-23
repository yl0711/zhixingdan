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
							@if(2 == $item['status'])
								已审批
							@elseif (-2 == $item['status'])
								<span style="color: #ff0000">已拒绝</span>
							@elseif (0 != $item['status'])
								待审批
							@endif
							</td>
							<td class= "_name">{{$item['review_at']}}</td>
							<td >
						@if(-1!=$document['status'] && -2!=$document['status'])
							@if(1 == $item['status'])
								@if($item['review_uid'] == $admin_user['id'])
								<!-- 当前待审的阶段：我是审批人，这里是审核功能 -->
								<button target="{{$item['id']}}" doc_id = "{{$item['document_id']}}" type="button" class="review_ok btn btn-info">通过</button>
								<button target="{{$item['id']}}" doc_id = "{{$item['document_id']}}" type="button" class="review_cancel btn btn-info">拒绝</button>
								@elseif($admin_user['id'] == $document['created_uid'])
								<!-- 当前待审的阶段：我是发布人，这里是催单功能 -->
								<button target="{{$item['id']}}" doc_id = "{{$item['document_id']}}" type="button" class="review_mail btn btn-info">催促</button>
								@endif
							@elseif(2 == $item['status'] && $item['cost_id'])
									<button target="{{$item['id']}}" doc_id = "{{$item['document_id']}}" type="button" class="review_cancel btn btn-info">驳回</button>
							@endif
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
		if ($(this).attr('target') && $(this).attr('doc_id')) {
			modalView('show' ,true, '审批');
			$('.modal-body').load("{{url('documents/review')}}/?id=" + $(this).attr('target') + '&doc_id=' + $(this).attr('doc_id') + '&review_type=2');
		} else {
			alert('参数异常');return false;
		}
		/*
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
		}*/
	});
	
	$('button[class^="review_cancel"]').click(function() {
		if ($(this).attr('target') && $(this).attr('doc_id')) {
			modalView('show' ,true, '审批');
			$('.modal-body').load("{{url('documents/review')}}/?id=" + $(this).attr('target') + '&doc_id=' + $(this).attr('doc_id') + '&review_type=-2');
		} else {
			alert('参数异常');return false;
		}
		/*
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
		}*/

	});
});

function docmentsReviewCallback(data) {
	if (data.status == 'error') {
		alert(data.info);
	} else {
		alert('完成审批');
		window.location.href = '{{url("documents/process")}}/{{$id}}';
	}
}
</script>