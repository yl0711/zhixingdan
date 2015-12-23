<table class ="prodict_edit">
	<tbody >
		<tr>
			<td class="tr"> 用户组名 :</td>
			<td class="tl">
				<input name="gname" id="gname" type="text" class="form-control" placeholder="用户组名" @if ($page_type=='modify') value="{{$group['gname']}}" @endif>
			@if ($page_type=='modify')
				<input id="gid" type="hidden" value="{{$group['gid']}}" />
				<input id="oldgname" type="hidden" value="{{$group['gname']}}" />
			@endif
			</td>
		</tr>
		
		<tr>
			<td class="tr"> 上级用户组 :</td>
			<td class="tl">
				<select id="parentid" name="parentid" class = "seachByStatus">
					<option value="0" >无</option>
				@foreach($grouplist as $item)
					<option value="{{$item['gid']}}" {{$item['selected']}}>{{$item['gname']}}</option>
				@endforeach
				</select>
			</td>
		</tr>
		<tr>
			<td class="tr">发文是否审核 :</td>
			<td class="tl">
				<input type="radio" id="article_check_1" name="article_check" value="1" @if ($page_type=='modify') {{$group['article_check_1']}} @else checked="checked" @endif> 
					<label for="article_check_1">不需要</label>&nbsp;
				<input type="radio" id="article_check_0" name="article_check" value="0" @if ($page_type=='modify') {{$group['article_check_0']}} @endif> 
					<label for="article_check_0">需要</label>
			</td>
		</tr>
		
		<tr>
			<td class="tr">内容查看权限 :</td>
			<td class="tl">
				<input type="radio" id="article_view_0" name="article_view" value="0" @if ($page_type=='modify') {{$group['article_view_0']}} @endif> 
					<label for="article_view_0">所有人</label>&nbsp;
				<input type="radio" id="article_view_1" name="article_view" value="1" @if ($page_type=='modify') {{$group['article_view_1']}} @else checked="checked" @endif> 
					<label for="article_view_1">下级管理组</label>
				<input type="radio" id="article_view_2" name="article_view" value="2" @if ($page_type=='modify') {{$group['article_view_2']}} @endif> 
					<label for="article_view_2">自己发的</label>
			</td>
		</tr>
		
		<tr>
			<th colspan = "2" >
				<button type="button" id="admin_user_group_submit" class="btn btn-success" >确认提交  </button>
			</th>
		</tr>
	</tbody>
</table>