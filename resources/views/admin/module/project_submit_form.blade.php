<table class ="prodict_edit">
	<tbody >
		<tr>
			<td class="tr"> 项目名称 :</td>
			<td class="tl">
				<input name="name" id="name" class="form-control" placeholder="项目名称" @if ($page_type=='modify') value="{{$project['name']}}" @endif>
			@if ($page_type=='modify')
				<input id="id" type="hidden" value="{{$project['id']}}" />
				<input id="oldname" type="hidden" value="{{$project['name']}}" />
			@endif
			</td>
		</tr>
		<tr>
			<td class="tr"> 所属供应商 :</td>
			<td class="tl">
				<select id="company_id" name="company_id" class = "seachByStatus">
					<option value="0" >请选择</option>
				@foreach($companyList as $item)
					<option value="{{$item['id']}}" {{$item['selected']}}>{{$item['name']}}</option>
				@endforeach
				</select>
			</td>
		</tr>
		<tr>
			<td class="tr"> 项目经理 :</td>
			<td class="tl">
				<input name="pm_id" id="pm_id" style="width: 100px;" class="form-control" placeholder="项目经理ID" 
					@if ($page_type=='modify') value="{{$project['pm_id']}}" @endif>
				<span id="pm_name"></span>
			</td>
		</tr>
		<tr>
			<td class="tr"> 项目开始时间 :</td>
			<td class="tl">
				<input name="starttime" id="starttime" class="form-control" placeholder="项目开始时间" 
					@if ($page_type=='modify') value="{{$project['starttime']}}" @endif>
			</td>
		</tr>
		<tr>
			<td class="tr"> 项目结束时间 :</td>
			<td class="tl">
				<input name="endtime" id="endtime" class="form-control" placeholder="项目结束时间" 
					@if ($page_type=='modify') value="{{$project['endtime']}}" @endif>
			</td>
		</tr>
		<tr>
			<th colspan = "2" >
				<button type="button" id="form_submit" class="btn btn-success" >确认提交</button>
			</th>
		</tr>
	</tbody>
</table>