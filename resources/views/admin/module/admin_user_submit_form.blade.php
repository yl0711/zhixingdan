<table class ="prodict_edit">
	<tbody >
		<tr>
			<td class="tr"> 账号 :</td>
			<td class="tl">
				<input name="uname" id="uname" class="form-control" placeholder="账号" @if ($page_type=='modify') value="{{$user['uname']}}" @endif>
			@if ($page_type=='modify')
				<input id="uid" type="hidden" value="{{$user['uid']}}" />
				<input id="olduname" type="hidden" value="{{$user['uname']}}" />
			@endif
			</td>
		</tr>
		
		<tr>
			<td class="tr"> 密码 :</td>
			<td class="tl">
				<input name="password" id="password" class="form-control" placeholder="@if ($page_type=='modify') 如密码不变请不要填写 @else 密码 @endif">
			</td>
		</tr>
		
		<tr>
			<td class="tr"> Email :</td>
			<td class="tl">
				<input name="email" id="email" class="form-control" placeholder="email" @if ($page_type=='modify') value="{{$user['email']}}" @endif>
			</td>
		</tr>
		
		<tr>
			<td class="tr"> 用户组 :</td>
			<td class="tl">
				<select id="gid" name="gid" class = "seachByStatus">
					<option value="0" >请选择</option>
				@foreach($group as $item)
					<option value="{{$item['gid']}}" {{$item['selected']}}>{{$item['gname']}}</option>
				@endforeach
				</select>
			</td>
		</tr>
		<tr>
			<td class="tr">发文是否审核 :</td>
			<td class="tl">
				<input type="radio" id="article_check" name="article_check" value="-1" @if ($page_type=='add' || $user['article_check'] == -1) checked="checked" @endif> 
					<label for="article_check">继承管理组</label>&nbsp;
				<input type="radio" id="article_check_1" name="article_check" value="1" @if ($page_type=='modify') {{$user['article_check_1']}} @endif> 
					<label for="article_check_1">不需要</label>&nbsp;
				<input type="radio" id="article_check_0" name="article_check" value="0" @if ($page_type=='modify') {{$user['article_check_0']}} @endif> 
					<label for="article_check_0">需要</label>
			</td>
		</tr>
		
		<tr>
			<td class="tr">内容查看权限 :</td>
			<td class="tl">
				<input type="radio" id="article_view" name="article_view" value="-1" @if ($page_type=='add' || $user['article_view'] == -1) checked="checked" @endif> 
					<label for="article_view">继承管理组</label>&nbsp;
				<input type="radio" id="article_view_0" name="article_view" value="0" @if ($page_type=='modify') {{$user['article_view_0']}} @endif> 
					<label for="article_view_0">所有人</label>&nbsp;
				<input type="radio" id="article_view_1" name="article_view" value="1" @if ($page_type=='modify') {{$user['article_view_1']}} @endif> 
					<label for="article_view_1">下级管理组</label>&nbsp;
				<input type="radio" id="article_view_2" name="article_view" value="2" @if ($page_type=='modify') {{$user['article_view_2']}} @endif> 
					<label for="article_view_2">自己发的</label>
			</td>
		</tr>
		
		<tr>
			<th colspan = "2" >
				<button type="button" id="admin_user_submit" class="btn btn-success" >确认提交</button>
			</th>
		</tr>
	</tbody>
</table>