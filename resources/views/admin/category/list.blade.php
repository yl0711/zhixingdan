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
				<form action = "{{url('category/index')}}" id = "form_seach" name = "form_seach" method="post" >
					<input style="width: 150px;" type="text" name="name" placeholder="输入名称" value="{{ $name}}" />
					<select  class = "seachByStatus" name="type">
						<option value="0" @if($type==0) selected @endif >选择项目类型</option>
						<option value="1" @if($type==1) selected @endif >工作类别</option>
						<option value="2" @if($type==2) selected @endif>工作分项</option>
						<option value="3" @if($type==3) selected @endif>工作项目</option>
					</select>
					<select  class = "seachByStatus" name="status">
						<option value="2" @if($status==2) selected @endif >全部</option>
						<option value="1" @if($status==1) selected @endif >已打开</option>
						<option value="0" @if($status==0) selected @endif>已关闭</option>
					</select>
					<button class = "btn_seach" onclick="form_seach.submit();">查询</button>
					<!--每页显示条数-->
					<span class = "pageSizeSpan" >条/页</span>
					<input type="text"  action = "{{url('category/index')}}/?name={{$name}}&status={{$status}}&type={{$type}}" class = "pageSize" name = "pageSize"  value="{{$pageSize}}" >
				</form>	
				<div class="fr top-r">
					<i class="add-ico" id = "btn_add_admin_category" >添加项目类型 </i>
				</div>
			</div>
			<div class="table-con">	
				<table>
					<thead>
						<tr>
							<th style="width: 5%;">ID</th>
							<th style="width: 10%;">名称</th>
							<th style="width: 10%;">类型</th>
							<th style="width: 25%;">说明</th>
							<th style="width: 12%;">指定审批人</th>
							<th style="width: 15%;" class = "timedate">最后修改时间</th>
							<th >操作</th>
						</tr>
					</thead>
					<tbody id= "dataListTable"　>
					@if($categoryList->count())
						@foreach($categoryList as $item)
						<tr id = "data_{{$item['id']}}" data-id = "{{$item['id']}}" >
							<td class= "_id" >{{$item['id']}}</td>
							<td class= "_name">{{$item['name']}}</td>
							<td class= "_name">{{$item['typeStr']}}</td>
							<td class= "_name">{{$item['intro']}}</td>
							<td class= "_name">{{$item['person']}}</td>
							<td >{{$item['updated_at']}}</td>
							<td >
								<button target="{{$item['id']}}" type="button" class="modify btn btn-info">修改</button>
							@if(1 == $item['status'])
								<button target="{{$item['id']}}" data="0" _name="{{$item['name']}}" type="button" class="on-off btn btn-danger">关闭</button>
							@else
								<button target="{{$item['id']}}" data="1" _name="{{$item['name']}}" type="button" class="on-off btn btn-warning">开启</button>
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
			@if($categoryList->count())
			{!! $categoryList->appends(['name'=>$name, 'status'=>$status, 'pageSize'=>$pageSize])->render() !!}
			@endif
 		</div>
		<!--//网页备注-->	
	</div>
@include('admin/static/footer')
</div>
</body>
<script>
$(function() {
	$('#btn_add_admin_category').click(function() {
		window.location.href="{{url('category/add')}}";
	});
	
	$('button[class^="modify"]').click(function() {
		window.location.href="{{url('category/modify')}}/" + $(this).attr('target');
	});
	
	$('button[class^="on-off"]').click(function() {
		this_obj = $(this);
		if (confirm('是否要将' + this_obj.attr('_name') + $(this).text().trim())) {
			$.ajax({
				type:"get",
				dataType:"json",
				url: "{{url('category/status')}}/" + $(this).attr('target') + '/?status=' + this_obj.attr('data'),
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