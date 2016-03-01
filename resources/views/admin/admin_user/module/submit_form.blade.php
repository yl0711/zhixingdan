<table class ="prodict_edit">
	<tbody >
		<tr>
			<td class="tr"> 账号 :</td>
			<td class="tl">
				<input name="name" id="name" class="form-control" placeholder="账号" @if ($page_type=='modify') value="{{$user['name']}}" @endif>
			@if ($page_type=='modify')
				<input id="id" type="hidden" value="{{$user['id']}}" />
				<input id="oldname" type="hidden" value="{{$user['name']}}" />
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
				<select id="group_id" name="group_id" class = "seachByStatus">
					<option value="0" >请选择</option>
				@foreach($group as $item)
					<option value="{{$item['id']}}" {{$item['selected']}}>{{$item['name']}}</option>
				@endforeach
				</select>
			</td>
		</tr>
		<tr>
			<td class="tr"> 部门 :</td>
			<td class="tl">
				<select id="department_id" name="department_id" class = "seachByStatus">
					<option value="0" >请选择</option>
				@foreach($department as $item)
					<option value="{{$item['id']}}" {{$item['selected']}}>{{$item['name']}}</option>
				@endforeach
				</select>
			</td>
		</tr>
		<tr>
			<td class="tr"> 区域 :</td>
			<td class="tl">
				@foreach($area as $item)
				<input type="checkbox" id="area_{{$item['id']}}" name="area_id" value="{{$item['id']}}" {{$item['checked']}} />
				<label for="area_{{$item['id']}}">{{$item['name']}}</label>
				@endforeach
			</td>
		</tr>
		<tr>
			<td class="tr"> 直属上级 :</td>
			<td class="tl">
				<select id="parent_user" name="parent_user" class = "seachByStatus">
					<option value="0" >请选择</option>
				</select>
			</td>
		</tr>
		<tr>
			<th colspan = "2" >
				<button type="button" id="admin_user_submit" class="btn btn-success" >确认提交</button>
			</th>
		</tr>
	</tbody>
</table>