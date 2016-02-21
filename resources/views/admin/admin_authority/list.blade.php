@include('admin/static/header')
<div id="wrapper">
    @include('admin/static/leftside')
    <div class="content-r">
        <div class="table-box">
        		<div>
				<div class = "table_tit" style="float: left;padding: 15px;"><h1>{{$navigation}}</h1></div>
			</div>
            <div class="search-box">
                <div class="fr top-r">
                    <i class="add-ico" id = "btn_refresh_list" > 刷新列表 </i>
                </div>
            </div>
            <div class="table-con">
                <table>
                    <tbody id= "dataListTable"　>
				@if(count($data))
					@foreach($data as $parent)
						<tr id = "data_{{$parent['id']}}" data-id = "{{$parent['id']}}" >
							<td class="tl" style="width:12%;">{{$parent['aname']}}</td>
							<td class="tl" style="width:25%;"></td>
							<td class="tl" style="width:63%;">&bull;查看&nbsp;&nbsp;</td>
						</tr>
						@foreach($parent['master'] as $master)
						<tr id = "data_{{$master['id']}}" data-id = "{{$master['id']}}" >
							<td class="tl" style="width:12%; padding-left: 20px;">{{$master['aname']}}</td>
							<td class="tl" style="width:25%;">{{urldecode($master['url'])}}</td>
							<td class="tl" style="width:63%;">
								&bull;查看&nbsp;&nbsp;
								@if(isset($master['sub']))
									@foreach($master['sub'] as $item)
										&bull;{{$item['aname']}}&nbsp;&nbsp;
									@endforeach
								@endif
							</td>
						</tr>
						@endforeach
					@endforeach
				@else
						<tr><td colspan="10">无数据</td></tr>
				@endif
					</tbody>
                </table>
            </div>
        </div>
    </div>
    @include('admin/static/footer')
</div>
</body>

<script>
$(function(){
	$("#btn_refresh_list").bind("click", function(){
		refresh_list();
 	});
});

function refresh_list() {
	$.getJSON("{{url('authority/refresh')}}", [], function(data) {
		if (data.status == 'success') {
			alert('更新成功');
			window.location.reload();
		} else {
			alert(data.info);
		}
	});
}
</script>