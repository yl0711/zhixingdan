<table class ="prodict_edit">
	<tbody >
		@if(isset($document) && $document['identifier'])
		<tr>
			<td class="tr"> 执行单号 :</td>
			<td class="tl">{{$document['identifier']}}</td>
		</tr>
		@endif
		<tr>
			<td class="tr"> <span style="color: #FF0000;">*</span> 项目分类 :</td>
			<td class="tl">
				@if (count($gongzuoleibie))
					@foreach($gongzuoleibie as $item)
					<input type="checkbox" id="cate1[{{$item['id']}}]" value="{{$item['id']}}" {{$item['checked']}} />
					<label for="cate1[{{$item['id']}}]">{{$item['name']}}</label>
					@endforeach
				@endif
			</td>
		</tr>
		<tr>
			<td class="tr"> <span style="color: #FF0000;">*</span> 客户名称 :</td>
			<td class="tl">
				<input name="company_name" id="company_name" class="form-control" placeholder="客户名称" 
					@if ($page_type=='modify' || $page_type=='copy') value="{{$document['company_name']}}" @endif>
			</td>
		</tr>
		<tr>
			<td class="tr"> <span style="color: #FF0000;">*</span> 项目名称 :</td>
			<td class="tl">
				<input name="project_name" id="project_name" class="form-control" placeholder="项目名称" 
					@if ($page_type=='modify' || $page_type=='copy') value="{{$document['project_name']}}" @endif>
			</td>
		</tr>
		<tr>
			<td class="tr"> <span style="color: #FF0000;">*</span> 项目开始日期 :</td>
			<td class="tl">
				<input style="width: 150px;" name="starttime" id="starttime" class="form-control" placeholder="项目开始日期" 
					@if ($page_type=='modify' || $page_type=='copy') value="{{$document['starttime']}}" @endif>
				<span id="starttime_warning" style="color: #FF0000;"></span>
			</td>
		</tr>
		<tr>
			<td class="tr"> <span style="color: #FF0000;">*</span> 项目结束日期 :</td>
			<td class="tl">
				<input disabled="disabled" style="width: 150px;" name="endtime" id="endtime" class="form-control" placeholder="项目结束日期" 
					@if ($page_type=='modify' || $page_type=='copy') value="{{$document['endtime']}}" @endif>
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
					@if ($page_type=='modify' || $page_type=='copy') value="{{$document['money']}}" @endif>
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
					@if ($page_type=='modify' || $page_type=='copy') value="{{$document['moneytime']}}" @endif>
				<span id="starttime_warning" style="color: #FF0000;"></span>
			</td>
		</tr>
		<tr>
			<td class="tr"> 成本预算 : </td>
			<td class="tl" style="clear: both;">
				<div id="add-cost">添加</div>
				<table id="cost-list" border="0" cellpadding="0" cellspacing="0" style="clear: both;">
					<tr>
						<td style="width: 30%; font-weight: bold;">选择成本构成项</td>
						<td style="width: 10%; font-weight: bold;">预算</td>
						<td style="width: 40%; font-weight: bold;">说明</td>
						<td style="width: 10%; font-weight: bold;">附件</td>
						<td style="width: 10%; font-weight: bold;">操作</td>
					</tr>
				@if (count($docCost))
					@foreach ($docCost as $item)
					<tr>
						<td>
							{{$costList[$item['cost_id']]['name']}}
							<input type="hidden" id="select_cost_id[]" name="select_cost_id[]" value="{{$item['cost_id']}}" />
						</td>
						<td>
							{{$item['money']}}
							<input type="hidden" id="select_cost_money[]" name="select_cost_money[]" value="{{$item['money']}}" />
						</td>
						<td>
							{{$item['intro']}}
							<input type="hidden" id="select_cost_intro[]" name="select_cost_intro[]" value="{{$item['intro']}}" />
						</td>
						<td>@if ($item['attach_id'] && isset($attach_list[$item['attach_id']]))
						<a href="http://{{config('global.DOMAIN.IMAGE')}}{{$attach_list[$item['attach_id']]}}" target="_blank">查看</a>
						<input type="hidden" id="select_cost_attach[]" name="select_cost_attach[]" value="{{$item['attach_id']}}" />
						@else
						<input type="hidden" id="select_cost_attach[]" name="select_cost_attach[]" value="" />
						@endif</td>
						<td name="delcost">删除</td>
					</tr>
					@endforeach
				@endif
				</table>
			</td>
		</tr>
		<tr>
			<td class="tr"> 项目KPI指标 :</td>
			<td class="tl">
				<textarea id="kpi" name="kpi" rows="10" cols="75">@if($page_type=='modify' || $page_type=='copy'){{$document['kpi']}}@endif</textarea>
			</td>
		</tr>
		<tr>
			<th colspan = "2" >
				<button type="button" id="form_submit" class="btn btn-success" >确认提交</button>
			</th>
		</tr>
	</tbody>
</table>