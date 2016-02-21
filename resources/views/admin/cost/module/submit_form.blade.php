<table class ="prodict_edit">
	<tbody >
		<tr>
			<td class="tr"> <span style="color: #FF0000;">*</span> 名称 :</td>
			<td class="tl">
				<input name="name" id="name" class="form-control" placeholder="名称" style="width: 300px;"  
					@if ($page_type=='modify') value="{{$cost['name']}}" @endif>
			@if ($page_type=='modify')
				<input id="id" type="hidden" value="{{$cost['id']}}" />
				<input id="oldname" type="hidden" value="{{$cost['name']}}" />
			@endif
			</td>
		</tr>
		<tr>
			<td class="tr"> 审批人 :</td>
			<td class="tl">
				<select id="review_user" name="review_user" class = "seachByStatus">
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
				<textarea id="intro" name="intro" rows="5" cols="80">@if ($page_type=='modify') {{$cost['intro']}} @endif</textarea>
			</td>
		</tr>
		<tr>
			<th colspan = "2" >
				<button type="button" id="form_submit" class="btn btn-success" >确认提交</button>
			</th>
		</tr>
	</tbody>
</table>