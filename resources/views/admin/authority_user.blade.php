@include('admin/static/header')
<div id="wrapper">
    @include('admin/static/leftside')

    <div class="content-r">
        <div class="table-box">
        		<div class="search-box">
				<div class = "table_tit" style="float: left;padding: 15px;"><h1>权限管理 --> 权限管理 --> 修改 {{$user['name']}} 权限</h1></div>
			</div>
            <div class="table-con">
                <table>
                    <tbody id= "dataListTable"　>
						@if(count($menu))
						<tr>
							<td class="tl" style="padding-left: 20px;" colspan="4">
								<input type="checkbox" id="select_all" name="select_all"
									@if(isset($authorityList[0]) && 'all' == $authorityList[0]) checked="checked" @endif />
								<label for="select_all">开通所有权限</label>
								&nbsp;&nbsp;
								&bull;勾选后无需在勾选下面的权限列表项，自动会将所有权限关联
							</td>
						</tr>
							@foreach($menu as $parent)
						<tr id = "data_{{$parent['id']}}" data-id = "{{$parent['id']}}" >
							<td class="tl" style="width:12%;">
								{{$parent['aname']}}
							</td>
							<td class="tl" style="width:25%;"></td>
							<td class="tl" style="width:56%;">
								<input type="checkbox" name="authority[{{$parent['id']}}]" 
									id="authority_0_{{$parent['id']}}"
									@if(isset($authorityList[$parent['id']]) || (isset($authorityList[0]) && 'all' == $authorityList[0])) 
										checked="checked" 
									@endif>
									<label for="authority_0_{{$parent['id']}}">查看</label>&nbsp;&nbsp;
							</td>
							<td class="tl" style="width:7%;">
								<input type="checkbox" id="allauthority_0_{{$parent['id']}}" />
								<label for="allauthority_0_{{$parent['id']}}">全选</label>
							</td>
						</tr>
								@foreach($parent['master'] as $master)
						<tr id = "data_{{$master['id']}}" data-id = "{{$master['id']}}" >
							<td class="tl" style="width:12%; padding-left: 20px;">
								{{$master['aname']}}
							</td>
							<td class="tl" style="width:25%;">{{urldecode($master['url'])}}</td>
							<td class="tl" style="width:56%;">
								<input type="checkbox" name="authority[{{$master['id']}}]" 
									id="authority_{{$master['parentid']}}_{{$master['id']}}"
									@if(isset($authorityList[$master['id']]) || (isset($authorityList[0]) && 'all' == $authorityList[0])) 
										checked="checked" 
									@endif>
									<label for="authority_{{$master['parentid']}}_{{$master['id']}}">查看</label>&nbsp;&nbsp;
									@if(isset($master['sub']))
										@foreach($master['sub'] as $opitem)
										<input type="checkbox" name="authority[{{$opitem['id']}}]" 
											id="authority_{{$master['parentid']}}_{{$master['id']}}_{{$opitem['id']}}"
											@if(isset($authorityList[$opitem['id']]) || (isset($authorityList[0]) && 'all' == $authorityList[0])) 
												checked="checked" 
											@endif>
											<label for="authority_{{$master['parentid']}}_{{$master['id']}}_{{$opitem['id']}}">{{$opitem['aname']}}</label>&nbsp;&nbsp;
										@endforeach
									@endif
							</td>
							<td class="tl" style="width:7%;">
								<input type="checkbox" id="allauthority_{{$master['parentid']}}_{{$master['id']}}" />
								<label for="allauthority_{{$master['parentid']}}_{{$master['id']}}">全选</label>
							</td>
						</tr>
								@endforeach
							@endforeach
						@else
						<tr><td colspan="4">无数据</td></tr>
						@endif
						<tr>
							<td colspan="4">
								<input type="button" id="submit" class="btn btn-success" value="确认提交" />
								<input type="button" id="cancel" class="btn btn-success" value="取消操作" />
							</td>
						</tr>
					</tbody>
                </table>
            </div>

        </div>
    </div>
    @include('admin/static/footer')
</div>
</body>

<script>
var usertype = "{{$user['type']}}";
var userid = "{{$user['id']}}";
var username = "{{$user['name']}}";
var authority = '';

$(function(){
	$("#submit").click(function() {
		if (confirm('是否确认提交对【' + username + '】权限的修改')) {
			makeParam();
			if (!authority && false == $(":checkbox[id=select_all]").prop('checked')) {
				alert('你还没有选择【' + username + '】的权限');
				return false;
			}
			$.ajax({
				type: "POST",
				url: window.location.href,
				data: {
					usertype: usertype, 
					userid: userid, 
					username: username, 
					authority: authority, 
					select_all: $(":checkbox[id=select_all]").prop('checked') ? 1 : 0
					},
				dataType: "json",
				success: function(data){
					if ('error' == data.status) {
						alert(data.info);
					} else {
						alert('【' + username + '】的权限修改成功');
					}
				}
			});
			return false;
		}
	});
	
	$('#cancel').bind('click', function() {
		if ('user' == usertype) {
			window.location.href = "{{url('user/index')}}";
		} else if ('group' == usertype) {
			window.location.href = "{{url('group/index')}}";
		}
	});
	
	$(":checkbox[id^=authority_]").bind("click", function(){
		var ischeck = $(this).prop('checked');
		var id = $(this).attr('id');
		var id_arr = id.split('_');
		var authorityName = $(this).parent().parent().children(":eq(0)").text().trim();
		
		if (0 == id_arr[1]) {
			/*
			 * 权限目录取消查看权限后，其下面所有功能都不允许使用，都将删除权限
			 * 产品库管理的权限ID＝authority_0_1，厂商管理的权限ID＝authority_1_3*，产品库管理的查看被勾掉后，厂商管理里面所有的勾也都去掉
			 */
			if (false == ischeck) {
				if (0 < $(":checkbox[id^=authority_" + id_arr[2] + "_]:checked").length) {
					if (confirm('如果取消【' + authorityName + '】的查看权限，其下面所有子功能的权限都将取消，是否继续？')) {
						$(":checkbox[id^=authority_" + id_arr[2] + "_]").prop('checked', false);
					} else {
						$(this).prop('checked', true);
					}
				}
			}
		} else if ("undefined" == typeof(id_arr[3])) {
			/*
			 * 同理，功能没有查看权限，下面所有的添加、删除也都不允许操作
			 */
			if (false == ischeck) {
				if (0 < $(":checkbox[id^=authority_" + id_arr[1] + "_" + id_arr[2] + "_]:checked").length) {
					if (confirm('如果取消【' + authorityName + '】的查看权限，其下面所有子功能的权限都将取消，是否继续？')) {
						$(":checkbox[id^=authority_" + id_arr[1] + "_" + id_arr[2] + "_]").prop('checked', false);
					} else {
						$(this).prop('checked', true);
					}
				}
			} else {
				if (false == $(":checkbox[id=authority_0_" + id_arr[1] + "]").prop('checked')) {
					var parentAuthorityName = $(":checkbox[id=authority_0_" + id_arr[1] + "]").parent().parent().children(":eq(0)").text().trim();
					$(this).prop('checked', false);
					alert('请先将【' + authorityName + '】功能所在组【' + parentAuthorityName + '】的查看权限勾上！');
				}
			}
		} else if (true == ischeck) {
			if (false == $(":checkbox[id=authority_" + id_arr[1] + "_" + id_arr[2] + "]").prop('checked')) {
				var parentAuthorityName = $(":checkbox[id=authority_" + id_arr[1] + "_" + id_arr[2] + "]").parent().parent().children(":eq(0)").text().trim();
				$(this).prop('checked', false);
				alert('请先将其所在功能页面【' + parentAuthorityName + '】的查看权限勾上！');
			}
		}
 	});
 	
 	$(":checkbox[id^=allauthority_]").bind('click', function() {
 		var ischeck = $(this).prop('checked');
		var id = $(this).attr('id');
		var id_arr = id.split('_');
		
		if (true == ischeck) {
			if (0 == id_arr[1]) {
				$(":checkbox[id=authority_0_" + id_arr[2] + "]").prop('checked', true);
				$(":checkbox[id^=authority_" + id_arr[2] + "_]").prop('checked', true);
			} else {
				$(":checkbox[id^=authority_" + id_arr[1] + "_" + id_arr[2] + "]").prop('checked', true);
			}
		}
 	});
});

function makeParam() {
	authority = '';
	$(":checkbox[name^=authority]:checked").each(function() {
		authority += $(this).attr('name').replace('authority[', '').replace(']', '') + ',';
	});
	if (authority) {
		authority = authority.substr(0, authority.length - 1);
	}
}
</script>