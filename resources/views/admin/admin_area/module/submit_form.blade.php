<table class ="prodict_edit">
	<tbody >
		<tr>
			<td class="tr"> 名称 :</td>
			<td class="tl">
				<input name="name" id="name" class="form-control" placeholder="名称" 
					@if ($page_type=='modify') value="{{$area['name']}}" @endif>
			@if ($page_type=='modify')
				<input id="id" type="hidden" value="{{$area['id']}}" />
				<input id="oldname" type="hidden" value="{{$area['name']}}" />
			@endif
			</td>
		</tr>
		<tr>
			<th colspan = "2" >
				<button type="button" id="admin_area_submit" class="btn btn-success" >确认提交</button>
			</th>
		</tr>
	</tbody>
</table>