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
				<div class="fr top-r">
					<i class="add-ico" id = "btn_add_admin_area" >添加区域 </i>
				</div>
			</div>
			<div class="table-con">	
				<table>
					<thead>
						<tr>
							<th style="width: 10%;">ID</th>
							<th style="width: 40%;">区域名称</th>
							<th style="width: 15%;">本区人数</th>
							<th >操作</th>
						</tr>
					</thead>
					<tbody id= "dataListTable"　>
					@if(count($areaList))
						@foreach($areaList as $item)
						<tr id = "data_{{$item['id']}}" data-id = "{{$item['id']}}" >
							<td class= "_id" >{{$item['id']}}</td>
							<td class= "_name">{{$item['name']}}</td>
							<td >{{$item['person']}}</td>
							<td >
								<button target="{{$item['id']}}" type="button" class="modify btn btn-info">修改</button>
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
	$('#btn_add_admin_area').click(function() {
		window.location.href="{{url('area/add')}}";
	});
	
	$('button[class^="modify"]').click(function() {
		window.location.href="{{url('area/modify')}}/" + $(this).attr('target');
	});

});
</script>