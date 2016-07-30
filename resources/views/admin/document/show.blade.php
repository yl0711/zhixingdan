@include('admin/static/header')
<div id="wrapper">
@include('admin/static/leftside')

	<div class="content-r">
		<div class="table-box">
			<div class="search-box">
				<div class = "table_tit" style="float: left;padding: 15px;"><h1>{{$navigation}}</h1></div>
			</div>
			<div style="width:900px;margin: 0 auto;border-left: 1px solid #ddd; " class="table-con">	
				<table class ="prodict_edit">
					<tbody >
						<tr>
							<td class="tr" width="150px"> 执行单号 :</td>
							<td class="tl">{{$document['identifier']}}</td>
						</tr>
						<tr>
							<td class="tr"> 项目分类 :</td>
							<td class="tl">{{$document['cate1_name']}}</td>
						</tr>
						<tr>
							<td class="tr"> 客户名称 :</td>
							<td class="tl">{{$document['company_name']}}</td>
						</tr>
						<tr>
							<td class="tr"> 项目名称 :</td>
							<td class="tl">{{$document['project_name']}}</td>
						</tr>
						<tr>
							<td class="tr"> 项目开始日期 :</td>
							<td class="tl">{{$document['starttime']}}</td>
						</tr>
						<tr>
							<td class="tr"> 项目结束日期 :</td>
							<td class="tl">{{$document['endtime']}}</td>
						</tr>
						<tr>
							<td class="tr"> 项目负责人 :</td>
							<td class="tl">{{$document['pm']}}</td>
						</tr>
						<tr>
							<td class="tr"> 合同状态 :</td>
							<td class="tl">@if($document['issign']==1) 已签 @else 未签 @endif</td>
						</tr>
						<tr>
							<td class="tr"> 金额 :</td>
							<td class="tl">{{$document['money']}}</td>
						</tr>
						<tr>
							<td class="tr"> 项目对接人 :</td>
							<td class="tl">{{$document['author']}}</td>
						</tr>
						<tr>
							<td class="tr"> 回款日期 :</td>
							<td class="tl">{{$document['moneytime']}}</td>
						</tr>
						<tr>
							<td class="tr"> 成本预算 : </td>
							<td class="tl" style="clear: both;">
								<table id="cost-list" border="0" cellpadding="0" cellspacing="0" style="clear: both;">
									<tr>
										<td style="width: 30%; font-weight: bold;">选择成本构成项</td>
										<td style="width: 10%; font-weight: bold;">预算</td>
										<td style="width: 40%; font-weight: bold;">说明</td>
										<td style="width: 10%; font-weight: bold;">附件</td>
									</tr>
								@if (count($docCost))
									@foreach ($docCost as $item)
									<tr>
										<td>{{$costList[$item['cost_id']]['name']}}</td>
										<td>{{$item['money']}}</td>
										<td>{{$item['intro']}}</td>
										<td>@if ($item['attach_id'] && isset($attach_list[$item['attach_id']]))
										<a href="http://{{config('global.DOMAIN.IMAGE')}}{{$attach_list[$item['attach_id']]}}" target="_blank">查看</a>
										@endif</td>
									</tr>
									@endforeach
								@endif
								</table>
							</td>
						</tr>
						<tr>
							<td class="tr"> 项目KPI指标 :</td>
							<td class="tl">{{$document['kpi']}}</td>
						</tr>
						
						<tr>
							<th colspan = "2" >
								<button type="button" id="download" class="btn btn-success">下载</button>
							</th>
						</tr>
						
					</tbody>
				</table>
			</div>
		</div>
	</div>
	@include('admin/static/footer')
</div>
</body>
<script>
$(function() {
	$('#download').click(function() {
		window.location.href="{{url('documents/download')}}/{{$document['id']}}";
	});

});

function docmentsReviewCancelCallback(data) {
	if (data.status == 'error') {
		alert(data.info);
	} else {
		alert('已拒绝此执行单');
		window.location.href = '{{url("documents/show")}}/{{$document["id"]}}';
	}
}
</script>