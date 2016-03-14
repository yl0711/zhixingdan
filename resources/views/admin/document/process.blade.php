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
							<td class= "_id" ><a title="部门：{{$department[$item['department_id']]['name']}}；级别：{{$group[$item['group_id']]['name']}}">{{$user[$item['review_uid']]['name']}}</a></td>
							<td class= "_name">@if($item['cost_id']) {{$costlist[$item['cost_id']]['name']}} @endif</td>
							<td class= "_name">{{$item['intro']}}</td>
							<td class= "_name">
								@if(1 == $item['status']) 通过 @elseif(0 == $item['status']) 待审 @else 拒绝 @endif
							</td>
							<td class= "_name">{{$item['review_at']}}</td>
							<td >
							@if(0 == $item['status'])
								<button target="{{$item['id']}}" type="button" class="modify btn btn-info">催促</button>
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
	$('#btn_add_admin_documents').click(function() {
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