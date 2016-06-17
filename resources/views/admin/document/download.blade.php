<!doctype html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>管理后台</title>
	<link rel="stylesheet" href = "{{ asset('css/admin/bootstrap.min.css') }}" />
	<link rel="stylesheet" href="{{ asset('css/admin/common.css') }}">
	<link rel="stylesheet" href="{{ asset('css/admin/style.css') }}">
	<link rel="stylesheet" href="{{ asset('js/plugin/jquery-ui/jquery-ui.min.css') }}">
</head>
<body >
<div id="wrapper">
	<div class="content-r">
		<div class="table-box">
			<div class="search-box">
				<div class = "table_tit" style="float: left;"><h1></h1></div>
			</div>
			<div style="width:900px;margin: 0 auto;border-left: 1px solid #ddd; " class="table-con">	
				<table class ="prodict_edit">
					<tbody >
						<tr>
							<td class="tr"> <span style="color: #FF0000;">*</span> 项目分类 :</td>
							<td class="tl">{{$document['cate1_name']}}</td>
						</tr>
						<tr>
							<td class="tr"> <span style="color: #FF0000;">*</span> 客户名称 :</td>
							<td class="tl">{{$document['company_name']}}</td>
						</tr>
						<tr>
							<td class="tr"> <span style="color: #FF0000;">*</span> 项目名称 :</td>
							<td class="tl">{{$document['project_name']}}</td>
						</tr>
						<tr>
							<td class="tr"> <span style="color: #FF0000;">*</span> 项目开始日期 :</td>
							<td class="tl">{{$document['starttime']}}</td>
						</tr>
						<tr>
							<td class="tr"> <span style="color: #FF0000;">*</span> 项目结束日期 :</td>
							<td class="tl">{{$document['endtime']}}</td>
						</tr>
						<tr>
							<td class="tr"> <span style="color: #FF0000;">*</span> 项目负责人 :</td>
							<td class="tl">{{$document['pm']}}</td>
						</tr>
						<tr>
							<td class="tr"> 合同状态 :</td>
							<td class="tl">@if($document['issign']==1) 已签 @else 未签 @endif</td>
						</tr>
						<tr>
							<td class="tr"> <span style="color: #FF0000;">*</span> 金额 :</td>
							<td class="tl">{{$document['money']}}</td>
						</tr>
						<tr>
							<td class="tr"> <span style="color: #FF0000;">*</span> 项目对接人 :</td>
							<td class="tl">{{$document['author']}}</td>
						</tr>
						<tr>
							<td class="tr"> <span style="color: #FF0000;">*</span> 回款日期 :</td>
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
					</tbody>
				</table>
			</div>
		</div>
	</div>
<script src="{{ asset('js/admin/jquery-1.8.3.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}" ></script>
<script src="{{ asset('js/admin/base.js') }}"></script>
<script src="{{ asset('js/plugin/jquery-ui/jquery-ui.min.js') }}"></script>
</div>
</body>