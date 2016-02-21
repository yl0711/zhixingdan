<table class ="prodict_edit">
	<tbody >
		<tr>
			<td class="tr"> 部门名称 :</td>
			<td class="tl">
				<input name="name" id="name" class="form-control" placeholder="部门名称" @if ($page_type=='modify') value="{{$department['name']}}" @endif>
			@if ($page_type=='modify')
				<input id="id" type="hidden" value="{{$department['id']}}" />
				<input id="oldname" type="hidden" value="{{$department['name']}}" />
			@endif
			</td>
		</tr>
		<tr>
			<td class="tr"> 上级部门 :</td>
			<td class="tl">
				<select id="parentid" name="parentid" class = "seachByStatus">
					<option value="0" >请选择</option>
				@foreach($departmentlist as $item)
					<option value="{{$item['id']}}" {{$item['selected']}}>{{$item['name']}}</option>
				@endforeach
				</select>
			</td>
		</tr>
		<tr>
			<th colspan = "2" >
				<button type="button" id="form_submit" class="btn btn-success" >确认提交</button>
			</th>
		</tr>
	</tbody>
</table>