<table class ="prodict_edit">
	<tbody >
		<tr>
			<td class="tr"> 用户组名 :</td>
			<td class="tl">
				<input name="name" id="name" type="text" class="form-control" placeholder="用户组名" @if ($page_type=='modify') value="{{$group['name']}}" @endif>
			@if ($page_type=='modify')
				<input id="id" type="hidden" value="{{$group['id']}}" />
				<input id="oldname" type="hidden" value="{{$group['name']}}" />
			@endif
			</td>
		</tr>
		
		<tr>
			<td class="tr"> 上级用户组 :</td>
			<td class="tl">
				<select id="parentid" name="parentid" class = "seachByStatus">
					<option value="0" >无</option>
				@foreach($grouplist as $item)
					<option value="{{$item['id']}}" {{$item['selected']}}>{{$item['name']}}</option>
				@endforeach
				</select>
			</td>
		</tr>
		<tr>
			<th colspan = "2" >
				<button type="button" id="admin_user_group_submit" class="btn btn-success" >确认提交  </button>
			</th>
		</tr>
	</tbody>
</table>