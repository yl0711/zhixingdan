<table class ="prodict_edit">
	<tbody >
		<tr>
			<td class="tr"> 所属供应商 :</td>
			<td class="tl">
				<select id="company_id" name="company_id" class = "seachByStatus">
					<option value="0" >请选择</option>
				@if (count($companyList))
					@foreach($companyList as $item)
					<option value="{{$item['id']}}" {{$item['selected']}}>{{$item['name']}}</option>
					@endforeach
				@endif
				</select>
			</td>
		</tr>
		<tr>
			<td class="tr"> 所属项目 :</td>
			<td class="tl">
				<select id="project_id" name="project_id" class = "seachByStatus">
					<option value="0" >请选择</option>
				@if (count($projectList))
					@foreach($projectList as $item)
					<option value="{{$item['id']}}" {{$item['selected']}}>{{$item['name']}}</option>
					@endforeach
				@endif
				</select>
			</td>
		</tr>
		<tr>
			<td class="tr"> 名称 :</td>
			<td class="tl">
				<input name="name" id="name" class="form-control" placeholder="名称" @if ($page_type=='modify') value="{{$document['name']}}" @endif>
			@if ($page_type=='modify')
				<input id="id" type="hidden" value="{{$document['id']}}" />
				<input id="oldname" type="hidden" value="{{$document['name']}}" />
			@endif
			</td>
		</tr>
		<tr>
			<th colspan = "2" >
				<button type="button" id="form_submit" class="btn btn-success" >确认提交</button>
			</th>
		</tr>
	</tbody>
</table>