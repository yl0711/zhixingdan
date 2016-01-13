<table class ="prodict_edit">
	<tbody >
		<tr>
			<td class="tr"> 项目名称 :</td>
			<td class="tl">
				<input style="width: 200px;" name="name" id="name" class="form-control" placeholder="项目名称" @if ($page_type=='modify') value="{{$project['name']}}" @endif>
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
				@if (count($companyList))
					@foreach($companyList as $item)
					<option value="{{$item['id']}}" {{$item['selected']}}>{{$item['name']}}</option>
					@endforeach
				@endif
				</select>
			</td>
		</tr>
		<tr>
			<td class="tr"> 项目经理 :</td>
			<td class="tl">
				<select id="pm_id" name="pm_id" class = "seachByStatus">
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
			<td class="tr"> 项目开始日期 :</td>
			<td class="tl">
				<input style="width: 150px;" name="starttime" id="starttime" class="form-control" placeholder="项目开始日期" 
					@if ($page_type=='modify') value="{{$project['starttime']}}" @endif>
				<span id="starttime_warning" style="color: #FF0000;"></span>
			</td>
		</tr>
		<tr>
			<td class="tr"> 项目结束日期 :</td>
			<td class="tl">
				<input disabled="disabled" style="width: 150px;" name="endtime" id="endtime" class="form-control" placeholder="项目结束日期" 
					@if ($page_type=='modify') value="{{$project['endtime']}}" @endif>
				<span style="color: #FF0000;"></span>
			</td>
		</tr><tr>
			<td class="tr"> 项目描述 :</td>
			<td class="tl">
				<textarea id="intro" name="intro" style="width: 500px; height: 200px;">@if ($page_type=='modify'){{$project['intro']}}@endif</textarea>
			</td>
		</tr>
		<tr>
			<th colspan = "2" >
				<button type="button" id="form_submit" class="btn btn-success" >确认提交</button>
			</th>
		</tr>
	</tbody>
</table>