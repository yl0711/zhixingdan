<!doctype html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>执行单</title>
</head>
<style type="text/css">
	/*=S 初始 */
	html,body,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,form,fieldset,legend,input,label,textarea,p,blockquote,th,td {margin:0;padding:0;}
	body {background-color:#fff;}
	body,textarea {font:400 12px/1.5em Simsun,Microsoft Yahei,Arial;color:#000;}
	a,a:visited {text-decoration:none;color:#333;cursor:pointer;}
	a:hover {text-decoration:underline;color:#0657B2;}
	em,i {font-style:normal;}
	h1,h2,h3,h4,h5,h6,strong {font-weight:700;font-size:100%;}
	label, input, select {vertical-align:middle;}
	img, button {background:none;border:0 none;vertical-align:middle;}
	ol,ul {list-style:none;}
	table,th,td,fieldset,legend {font-weight:400;border:0 none;border-collapse:collapse}
	pre {white-space:pre-wrap;}
	/*=E 初始 */

	/*=S 布局 */
	.fl {float:left;}
	.fr {float:right;}
	.tl {text-align:left;}
	.tr {text-align:right;}
	.tc {text-align:center;}
	/*=E 布局 */

	.f12 {font-size:12px;}
	.f14 {font-size:14px;}
	.f16 {font-size:16px;}
	.fb {font-weight:700;}
	/*=E 文字 */

	/*
        首页样式
    */

	body {
		font-family: DejaVu Sans;
	}
	#header{ margin-bottom: 5px; height: 42px; width: 100%; background:#333; position: fixed;z-index: 1;}
	#wrapper{position: absolute; width: 100%;top:41px;}

	.table-con{width: 100%; overflow: hidden; text-align: center;border-top: 1px solid #e6e6e8;}
	.table-con table{ width: 100%; text-align: center;}
	.table-con table thead{ border-bottom: 1px solid #ddd; font-size: 14px;}
	.table-con table th{ font-weight: 700;height: 50px; border-right: 1px solid #ddd;}
	.table-con table td{ height: 50px; padding: 5px; border-bottom: 1px solid #ddd; border-right: 1px solid #ddd;}
	.table-con table td img{ max-width: 100px;}
	.table-con table select{ height: 24px; display: inline-block; border: none; background: #E2E2E2; font-family: "Microsoft YaHei";}
	.table-con tbody tr:hover{ background: #f1f1f1;}
	.table-con table td span.revise,.table-con table td span.open{ display: inline-block; padding: 0 15px; margin-right: 8px; height: 30px; background: #3472ab; line-height: 30px; font-size: 14px; color: #fff; border-radius: 3px; cursor: pointer;}
	.table-con table td span.open{ background: #37588B;}

	/**
     * 公共部分样式
     */
	.table-con table th,td{text-align: center;}
	.table-con table td img {max-width:100%;}

	/*添加产品*/
	.prodict_edit ul {text-align: left;}
	.prodict_edit ul li { cursor: pointer;display: inline-block ; padding :4px 8px;margin: 5px;border:1px solid #ddd; }

</style>
<body style="font-family: droidsans;" >
<div id="wrapper">
	<div style="width:700px;margin: 0 auto;border-left: 1px solid #ddd; " class="table-con">
				<table class ="prodict_edit">
					<tbody >
						<tr>
							<td class="tr"> 执行单号 :</td>
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
									</tr>
								@if (count($docCost))
									@foreach ($docCost as $item)
									<tr>
										<td>{{$costList[$item['cost_id']]['name']}}</td>
										<td>{{$item['money']}}</td>
										<td>{{$item['intro']}}</td>
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
</body>