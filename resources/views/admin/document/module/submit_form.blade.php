<table class ="prodict_edit">
	<tbody >
		<tr>
			<td class="tr"> <span style="color: #FF0000;">*</span> 项目分类 :</td>
			<td class="tl">
				<select id="gongzuoleibie" name="gongzuoleibie" class = "seachByStatus">
					<option value="0" >选择工作类别</option>
				@if (count($gongzuoleibie))
					@foreach($gongzuoleibie as $item)
					<option value="{{$item['id']}}" {{$item['selected']}}>{{$item['name']}}</option>
					@endforeach
				@endif
				</select>
				&nbsp;
				<select id="gongzuofenxiang" name="gongzuofenxiang" class = "seachByStatus">
					<option value="0" >选择工作分项</option>
				@if (count($gongzuofenxiang))
					@foreach($gongzuofenxiang as $item)
					<option value="{{$item['id']}}" {{$item['selected']}}>{{$item['name']}}</option>
					@endforeach
				@endif
				</select>
				&nbsp;
				<select id="gongzuoxiangmu" name="gongzuoxiangmu" class = "seachByStatus">
					<option value="0" >选择工作项目</option>
				@if (count($gongzuoxiangmu))
					@foreach($gongzuoxiangmu as $item)
					<option value="{{$item['id']}}" {{$item['selected']}}>{{$item['name']}}</option>
					@endforeach
				@endif
				</select>
			</td>
		</tr>
		<tr>
			<td class="tr"> <span style="color: #FF0000;">*</span> 客户名称 :</td>
			<td class="tl">
				<input name="company_name" id="company_name" class="form-control" placeholder="客户名称" 
					@if ($page_type=='modify') value="{{$document['company_name']}}" @endif>
			</td>
		</tr>
		<tr>
			<td class="tr"> <span style="color: #FF0000;">*</span> 项目名称 :</td>
			<td class="tl">
				<input name="project_name" id="project_name" class="form-control" placeholder="项目名称" 
					@if ($page_type=='modify') value="{{$document['project_name']}}" @endif>
			</td>
		</tr>
		<tr>
			<td class="tr"> <span style="color: #FF0000;">*</span> 项目开始日期 :</td>
			<td class="tl">
				<input style="width: 150px;" name="starttime" id="starttime" class="form-control" placeholder="项目开始日期" 
					@if ($page_type=='modify') value="{{$document['starttime']}}" @endif>
				<span id="starttime_warning" style="color: #FF0000;"></span>
			</td>
		</tr>
		<tr>
			<td class="tr"> <span style="color: #FF0000;">*</span> 项目结束日期 :</td>
			<td class="tl">
				<input disabled="disabled" style="width: 150px;" name="endtime" id="endtime" class="form-control" placeholder="项目结束日期" 
					@if ($page_type=='modify') value="{{$document['endtime']}}" @endif>
				<span style="color: #FF0000;"></span>
			</td>
		</tr>
		<tr>
			<td class="tr"> <span style="color: #FF0000;">*</span> 项目负责人 :</td>
			<td class="tl">
				<select id="pm_id" name="pm_id" class = "seachByStatus">
					<option value="0" >请选择</option>
				@if (count($userList))
					@foreach($userList as $item)
					<option value="{{$item['id']}}" {{$item['pm_selected']}}>{{$item['name']}}</option>
					@endforeach
				@endif
				</select>
			</td>
		</tr>
		<tr>
			<td class="tr"> 合同状态 :</td>
			<td class="tl">
				<select id="issign" name="issign" class = "seachByStatus">
					<option value="0" {{$issign_selected[0]}}>未签</option>
					<option value="1" {{$issign_selected[1]}}>已签</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="tr"> <span style="color: #FF0000;">*</span> 金额 :</td>
			<td class="tl">
				<input name="money" id="money" class="form-control" placeholder="金额"
				    style="width: 200px;"
					@if ($page_type=='modify') value="{{$document['money']}}" @endif>
			</td>
		</tr>
		<tr>
			<td class="tr"> <span style="color: #FF0000;">*</span> 项目对接人 :</td>
			<td class="tl">
				<select id="author_id" name="author_id" class = "seachByStatus">
					<option value="0" >请选择</option>
				@if (count($userList))
					@foreach($userList as $item)
					<option value="{{$item['id']}}" {{$item['author_selected']}}>{{$item['name']}}</option>
					@endforeach
				@endif
				</select>
			</td>
		</tr>
		<tr>
			<td class="tr"> <span style="color: #FF0000;">*</span> 回款日期 :</td>
			<td class="tl">
				<input style="width: 150px;" name="moneytime" id="moneytime" class="form-control" placeholder="回款日期" 
					@if ($page_type=='modify') value="{{$document['moneytime']}}" @endif>
				<span id="starttime_warning" style="color: #FF0000;"></span>
			</td>
		</tr>
		<tr>
			<td class="tr"> 成本预算 :</td>
			<td class="tl" style="clear: both;">
				<table border="0" cellpadding="0" cellspacing="0" style="clear: both;">
					<tr>
						<td style="text-align: left;">选择成本构成项</td>
						<td style="text-align: left;">说明</td>
						<td style="text-align: left;">预算</td>
					</tr>
				@for ($i = 0; $i < 5; $i++)
				    <tr>
						<td style="text-align: left;">
							<select id="cost_select_{{$i}}" name="cost_select_{{$i}}">
							@if(isset($docCost[$i]) && isset($docCost[$i]['cost_id'])) 
								<option value="{{$docCost[$i]['cost_id']}}" selected="selected">{{$costList[$docCost[$i]['cost_id']]['name']}}</option>
							@endif
							</select>
						</td>
						<td><input style="width: 150px;" id="cost_intro_{{$i}}" name="cost_intro_{{$i}}" 
							@if(isset($docCost[$i]) && isset($docCost[$i]['intro'])) value="{{$docCost[$i]['intro']}}" @endif /></td>
						<td><input style="width: 100px;" id="cost_money_{{$i}}" name="cost_money_{{$i}}" 
							@if(isset($docCost[$i]) && isset($docCost[$i]['money'])) value="{{$docCost[$i]['money']}}" @endif /></td>
					</tr>
				@endfor
				</table>
			</td>
		</tr>
		<tr>
			<td class="tr"> 项目KPI指标 :</td>
			<td class="tl">
				<textarea id="kpi" name="kpi" rows="10" cols="75">@if($page_type=='modify'){{$document['kpi']}}@endif</textarea>
			</td>
		</tr>
		<tr>
			<th colspan = "2" >
				<button type="button" id="form_submit" class="btn btn-success" >确认提交</button>
			</th>
		</tr>
	</tbody>
</table>