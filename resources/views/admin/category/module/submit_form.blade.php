<table class ="prodict_edit">
	<tbody >
		<tr>
			<td class="tr"> <span style="color: #FF0000;">*</span> 名称 :</td>
			<td class="tl">
				<input name="name" id="name" class="form-control" placeholder="名称" style="width: 300px;"  
					@if ($page_type=='modify') value="{{$category['name']}}" @endif>
			@if ($page_type=='modify')
				<input id="id" type="hidden" value="{{$category['id']}}" />
				<input id="oldname" type="hidden" value="{{$category['name']}}" />
			@endif
			</td>
		</tr>
		<tr>
			<td class="tr"> <span style="color: #FF0000;">*</span> 类型 :</td>
			<td class="tl">
				<select id="type" name="type" class = "seachByStatus">
					<option value="0" >请选择</option>
					<option value="1" {{$category_type[1]}}>工作类别</option>
					<option value="2" {{$category_type[2]}}>工作分项</option>
					<option value="3" {{$category_type[3]}}>工作项目</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="tr"> 审批人 :</td>
			<td class="tl">
				<select id="userid" name="userid" class = "seachByStatus">
					<option value="0" >请选择</option>
				@if (count($userList))
					@foreach($userList as $item)
					<option value="{{$item['id']}}" {{$item['selected']}}>{{$item['name']}}</option>
					@endforeach
				@endif
				</select>
			</td>
		</tr>
		<tr>
			<td class="tr"> 说明(请控制在200字以内) :</td>
			<td class="tl">
				<textarea id="intro" name="intro" rows="5" cols="80">@if ($page_type=='modify') {{$category['intro']}} @endif</textarea>
			</td>
		</tr>
		<tr>
			<th colspan = "2" >
				<button type="button" id="form_submit" class="btn btn-success" >确认提交</button>
			</th>
		</tr>
	</tbody>
</table>