<!doctype html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>管理后台</title>
</head>
<style type="text/css">
	/*
*@Description: 后台基础样式
*@Author:      Riven
*@Update:      Riven(2015-09-08)
*/

	/*=S HTML5 */
	article, aside, details, figcaption, figure, footer, header, hgroup, nav, section {display:block;}
	audio, canvas, video {display:inline-block;*display:inline;*zoom:1;}
	audio:not([controls]) {display:none;}
	[hidden] {display:none;}
	/*=E HTML5 */

	/*=S 初始 */
	/*
    html {overflow-y:scroll;filter:progid:DXImageTransform.Microsoft.BasicImage(grayscale=1);-webkit-filter: grayscale(100%);}
    */
	html,body,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,form,fieldset,legend,input,label,textarea,p,blockquote,th,td {margin:0;padding:0;}
	body {background-color:#fff;}
	body,textarea {font:400 12px/1.5em Simsun,Microsoft Yahei,Arial;color:#333;}
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
	.layout {margin:0 auto;width:980px;}
	.iblock {display:inline-block;*display:inline;zoom:1;vertical-align:middle;}
	.clearall {zoom:1;}
	.clearall:after {content:".";display:block;clear:both;height:0;visibility:hidden;}
	.pipe {padding:0 5px;font-style:normal;color:#d5d5d5;}
	.vm {vertical-align:middle;}
	.fl {float:left;}
	.fr {float:right;}
	.tl {text-align:left;}
	.tr {text-align:right;}
	.tc {text-align:center;}
	.mt10 {margin-top:10px !important;}
	.mb10 {margin-bottom:10px;}
	.fix-ie6 {position:absolute;top:0;left:0;width:999px;height:999px;z-index:-1;background:none;border:0 none;_filter:Alpha(opacity=0);}
	/*=E 布局 */

	/*=S 文字 */
	.color1 {color:#334F67;} /* 深蓝 */
	.color2 {color:#8CA226;} /* 绿色 */
	.color3 {color:#FC6D02;} /* 橙色 */

	.color6 {color:#666;} /* 灰色 */
	.color9 {color:#999;} /* 浅灰 */
	.placeholder {color:#999 !important;} /* 输入框默认颜色 */
	:-moz-placeholder {color:#999 !important;}
	::-webkit-input-placeholder {color:#999 !important;}

	.yahei {font:700 16px/1.5 "Microsoft YaHei";}
	.arial {font-family:Arial;}
	.f12 {font-size:12px;}
	.f14 {font-size:14px;}
	.f16 {font-size:16px;}
	.fb {font-weight:700;}
	/*=E 文字 */

	/*=S 链接 */
	.link0 a, .link0 a:visited {color:#0657B2;} /* 蓝色 -> 橙色 */
	.link0 a:hover {color:#FC6D02;}

	.link1 a, .link1 a:visited, .link1 a:hover {color:#334F67;} /* 深蓝 -> 深蓝 */
	.link2 a, .link2 a:visited, .link2 a:hover {color:#8CA226;} /* 绿色 -> 绿色 */
	.link3 a, .link3 a:visited {color:#FC6D02;} /* 橙色 -> 蓝色 */
	.link3 a:hover {color:#0657B2;}

	.link6 a, .link6 a:visited {color:#666;} /* 灰色 -> 灰色 */
	.link6 a:hover {color:#0657B2;}
	.link9 a, .link9 a:visited {color:#999;} /* 浅灰 -> 浅灰 */
	.link9 a:hover {color:#0657B2;}
	/*=E 链接 */

	/*=S 表单 */
	.txt,.txtarea {padding:3px;outline:none;font-size:12px;line-height:14px;color:#333;border:1px solid #ddd;border-radius:3px;}
	.txt {height:14px;}
	.txt-large, .txt-middle {font-size:14px;}
	.txt-large {height:22px;line-height:22px;}
	.txt-middle {height:18px;line-height:18px;}
	.txt:hover, .txt:focus, .txtarea:hover, .txtarea:focus {border-color:#7DBDE2;box-shadow:0 0 5px #7DBDE2;}
	.error, .error:hover, .error:focus {border-color:#FFABAB;box-shadow: 0 0 5px #FFABAB;}
	.checkbox, .radio {width:13px;height:13px;}
	/*=E 表单 */

	/*
    *@Description: 后台样式
*@Author:      Riven
*@Update:      Riven(2015-09-08)
*/

	/*
        登录样式
    */

	#login{ position: relative; margin: 0 auto; padding: 10px; width: 500px; background: #fff;  overflow: hidden;}
	.login-box{ min-height: 400px; border: 2px solid #7397D1; overflow: hidden;}
	.login-title{ padding: 40px 0px; text-align: center; overflow: hidden;}
	.login-title dt{ margin-bottom: 15px;}
	.login-title dd{ overflow: hidden;}
	.login-title dd h1{ font-size: 26px; color: #37588B; font-weight: 700; line-height: 30px; overflow: hidden;}
	.form-box{ margin: 0 auto 20px; position: relative; padding: 15px 10px 15px 33px; width: 55%; border: 1px solid #ddd; overflow: hidden;}
	.form-box dt{ position: absolute; left: 6px; background: url("../../images/admin/email.png") no-repeat center; width: 20px; height: 20px;}
	.form-box dd{ overflow: hidden;}
	.form-box dd input{ border: none; outline: none;-webkit-appearance:none; font-size: 15px;}
	.login-button{ margin: 0 auto; width: 64%; height: 47px; line-height: 47px; background: #37588B; overflow: hidden;}
	.login-button input{ display: block; width: 100%; line-height: 47px; text-align: center; outline: none; color: #fff; font-size: 22px; background: transparent; border: none;}


	/*
        首页样式
    */

	body {
		font-family: DejaVu Sans;
	}
	#header{ margin-bottom: 5px; height: 42px; width: 100%; background:#333; position: fixed;z-index: 1;}

	#wrapper{position: absolute; width: 100%;top:41px;}
	.slide-l{position: fixed; width: 200px;height:100%; background: #EAEAEA; border-bottom: 1px solid #D8D7D7; overflow:scroll;}
	.slide-l ul{ overflow: hidden;}
	.slide-item{ position: relative; text-indent: 40px; overflow: hidden;}
	.slide-item i{ position: absolute; left: 152px; top: 15px; width: 12px; height: 12px; background: url("../../images/admin/upanddown.png") no-repeat;}
	.slide-item i.up{ background: url("../../images/admin/upanddown.png") no-repeat 0 -12px;}
	.slide-item h3{ padding: 5px 40px 5px 0; line-height: 34px; font-size: 16px; background: url('../../images/admin/icon_7.png') no-repeat 18px -32px; color: #333; border-bottom: 1px solid #C7C7C7;	box-shadow: 0 -1px 0 #fff inset;}
	.slide-item ol{ display: none; background: #fff; border-bottom: 1px solid #ddd; padding: 8px 0;}
	.slide-item li{ line-height: 34px;}
	.slide-item li a{ display: block; padding: 5px 0; font-size: 14px; }
	.slide-item li a:hover{ background: #F9F9F9; text-decoration: none;}
	.slide-item li a.on{ color: #0657B2;background: #eee; }

	.content-r{width:100%;padding: 0 10px 20px 210px; overflow:hidden;}
	.table-box{  background: #F7F7F7; border: 1px solid #e6e6e8; overflow: hidden;}
	.search-box{ width: 100%;  padding-left:13px; height: 44px; background: #e6e6e8;overflow: hidden;z-index: 3;}
	.search-box span{ float: left; padding-left: 25px; background: url("../../images/admin/img2.jpg") no-repeat 0 center; line-height: 44px; font-size: 14px;}
	.search-box input{ float: left; margin:  9px 0px 0 0px; width: 200px; height: 24px; padding: 0 5px; border: 1px solid #ddd; border-radius: 2px; background: #f5f5f5;}
	.search-box select{ float: left; margin: 9px 10px 0 10px; height: 24px; line-height: 24px; border: 1px solid #ddd; background: #f5f5f5;}
	.search-box button{ float: left; margin-top: 10px; height: 22px; border: 1px solid #999; background: #F1F0F0;}
	.search-box .pageSize {width:70px;text-align:left ;background:none;margin-left: -70px;}
	.search-box .pageSizeSpan {width:72px ;text-align:right ; margin: 9px 0 0 0px;background:#f5f5f5;padding: 0;padding-right:5px;display: inline-block; height: 24px;line-height: 24px;}

	.top-r{}
	.draft-ico{background: #AD4844;color: #fff;padding:5px;display: none;}
	.add-ico{ position: relative; display: inline-block; border-radius: 3px; margin: 5px 5px 0 0; height: 30px; padding-right: 10px; font-size: 14px; text-indent: 30px; line-height: 30px; background: #333; color: #fff; cursor: pointer;}
	.add-ico:after{content: ""; width: 3px; height: 11px; background: #fff; position: absolute; left: 16px; top: 10px;}
	.add-ico:before{ content: ""; width: 11px; height: 3px; background: #fff; position: absolute; left: 12px; top: 14px;}
	.table-con{width: 100%; overflow: hidden; text-align: center;border-top: 1px solid #e6e6e8;}
	.table-con table{ width: 100%; text-align: center;}
	.table-con table thead{ border-bottom: 1px solid #ddd; font-size: 14px;}
	.table-con table th{ font-weight: 700;height: 50px; border-right: 1px solid #ddd;}
	.table-con table td{ height: 50px; padding: 5px; border-bottom: 1px solid #ddd; border-right: 1px solid #ddd;}
	.table-con table td img{ max-width: 100px;}
	.table-con .text{ display: inline-block; height: 20px; width: 40px; text-align: center; background: #f9f9f9; border: 1px solid #ddd;}
	.table-con table select{ height: 24px; display: inline-block; border: none; background: #E2E2E2; font-family: "Microsoft YaHei";}
	.table-con tbody tr:hover{ background: #f1f1f1;}
	.table-con table td span.revise,.table-con table td span.open{ display: inline-block; padding: 0 15px; margin-right: 8px; height: 30px; background: #3472ab; line-height: 30px; font-size: 14px; color: #fff; border-radius: 3px; cursor: pointer;}
	.table-con table td span.open{ background: #37588B;}
	.page{ height: 44px; line-height: 44px; background: #e6e6e8; overflow: hidden; text-align: right;}
	.page a{ padding: 0 6px; display: inline-block; height: 20px;line-height: 20px; background: #F9F9F9; border-radius: 4px;}
	.page a:hover{ text-decoration: none; background: #D4D4D4;}
	.page a.current{ background: #D4D4D4;}
	.page .all{ padding: 0 10px;}
	.page input{ margin: 0 5px; padding: 0 3px; width: 25px; height: 20px; display: inline-block; vertical-align: middle; position: relative; top: -1px; background: #fafafa; border: 1px solid #ddd;}
	.page button{ margin-right: 20px; background: #fafafa; line-height: 20px; font-weight: 700; position: relative; top: -1px; border-radius: 3px; border: 1px solid #ddd; cursor: pointer;}

	.revise-box{ padding-top: 20px; background: #eee; overflow: hidden;}
	.revise-box dl{ padding: 0 0 10px 20px; overflow: hidden;}
	.revise-box dt{ float: left; margin-right: 5px; line-height: 28px;}
	.revise-box dd{ float: left;}
	.revise-box dd input.text{ display: block; width: 200px; height:28px; line-height: 28px; border: 1px solid #D2D2D2; background: #fff;}
	.revise-box dd input[type=file]{ display: none;}
	.revise-box dd span{width: 128px; height: 103px; display: block; background: url("../../images/admin/img-up.png") no-repeat; }
	.revise-box dd textarea{ width: 200px; height: 100px; border: 1px solid #D2D2D2; padding: 5px;}
	.revise-box .sure{ margin: 0 0 20px 50px; width: 100px; background: #1E67AB; height: 34px; line-height: 34px; color: #fff; text-align: center; font-size: 16px; border-radius: 5px; cursor: pointer;}

	/**
     * 公共部分样式
     *
     */


	.i-warning{color:#eea236}
	.status_color_0{color:red;text-decoration:line-through}
	#loading {display:block;position:absolute;z-index:9999;top:35%;left:48%;width:8%;border-radius:10px; background:rgba(0,0,0,.8);}
	#loading img {margin:10%;width:80%;-webkit-animation:loading 2s infinite ;}
	@-webkit-keyframes loading{
		from{-webkit-transform:rotate(0deg)}
		to{-webkit-transform:rotate(360deg)}
	}

	table  .min_img {min-width:50px;max-height: 60px; background: url(../../images/admin/thumbnail.png) no-repeat center;background-size :auto 100%;  }
	table  .timedate{width:86px;}
	.add_img{min-width:60px;min-height:60px; background: url(../../images/admin/iconfont-shangchuantupian.png) no-repeat center;background-size :auto 100%;  }
	#header .logo ,.logo h1{float: left;color: #fff;line-height:42px ;margin-left: 10px;}
	#header .header-r{margin: 10px; color: #fff;}
	#header .header-r ul a{color: #fff;}
	#header .header-r ul  a:hover{text-decoration: none;}
	#header .header-r ul li {display: inline;padding:5px 12px ; cursor: pointer;margin-right: 5px;}
	#header .header-r ul li:hover,.cur{background:#31b0d5 ;}
	.table-con table th,td{text-align: center;}
	.table-con table td img {max-width:100%;}
	.slide-item{cursor: pointer;}
	.slide-item h3{line-height: 2;}
	.slide-item li {line-height: 24px;}
	.back-ico{ display: inline-block; border-radius: 3px; margin: 5px 5px 0 0; height: 30px; font-size: 14px; padding: 0 10px; line-height: 30px; position: relative; background: #333; color: #fff; cursor: pointer;}


	/*
     * chenshao
     * 产品库
     * */



	/*添加产品*/
	.mymode{cursor: pointer;    display: inline-block;padding: 4px 8px;margin: 5px;background: #eee;}
	.prodict_edit ul {text-align: left;}
	.prodict_edit ul li { cursor: pointer;display: inline-block ; padding :4px 8px;margin: 5px;border:1px solid #ddd; }
	.prodict_edit .dynamicDiv{ display: none; width:90%;margin-left:5%; background:#dff0d8;;border: 1px solid #468847; }
	.prodict_edit .dynamicDiv ul li {border:1px solid #d6e9c6;cursor: pointer;}
	.prodict_edit .dynamicDiv .seach_list{background: #fff;}
	.prodict_edit .dynamicDiv .EN_serch li{color: #468847; border:0;border-left:1px solid #b6d69b; margin: 0;padding:2px 8px;float: LEFT;}
	.prodict_edit .dynamicDiv .EN_serch .current{background: #fff;}
	.prodict_edit .dynamicDiv .seach_list {padding: 8px;max-height: 400px ; overflow-y: scroll; }

	/*产品标签 关键字*/
	.keywords{padding:4% 10%;border: 1px solid #333;position: relative;}
	.slide:before {z-index: 999999; border :0;border-left:1px solid #333;border-top:1px solid #333; content: "";background:#fff;color:#fff;position: absolute;display: block; top:-6px; right:40px; width:10px;height:10px;
		transform: rotate(45deg);
		-ms-transform: rotate(45deg);		/* IE 9 */
		-webkit-transform: rotate(45deg);	/* Safari and Chrome */
		-o-transform: rotate(45deg);		/* Opera */
		-moz-transform: rotate(45deg);		/* Firefox */
	}

	.keywords input{padding:5px;margin-left:20px;}
	.keywords .reload{ display: inline-block; width:50px; height:50px; line-height:50px;text-align: center; border-radius: 60px;position: absolute;top:-20px;left:-20px;background:#fff;border:1px dashed #246575}
	.keywords h3{width:80%;max-width:1180px;padding: 20px ;margin: 0 auto; color:#246575;border:1px dashed #ddd;background:#fdfdfd ;}
	.keywords h3 span{padding:5px;color:#02a1c9;font-size:15px;cursor:pointer;margin:5px;border:1px solid #02a1c9;float:right;}
	.keywords h3 span:hover{background:#02a1c9;color:#fff}
	.keywords .datalist{display: block;position: relative; margin-top:40px; padding: 20px ;border:1px solid #ddd;float:left;width:100%;}
	.keywords .datalist span.current {background:#fff;padding-right:10px;margin-right:10px;}
	.keywords .datalist span.current:before {opacity:1;}
	.keywords .datalist span {position:relative; padding:5px;font-size:15px;cursor:pointer;margin:5px;float:left;}
	.keywords .datalist .on span {color:#02a1c9;border:1px solid #02a1c9;}
	.keywords .datalist .off span {color:#555;border:1px solid #555;}
	.keywords .datalist span:before {border-radius: 60px;display:block;position:absolute;margin:auto;right:-10px;top:-10px;width:25px;height:25px;text-align:center;z-index:10;opacity:0;}
	.keywords .datalist .on span:before{content:"关";border:2px solid #fff;background:#02a1c9;color:#fff;}
	.keywords .datalist .off span:before{content:"启";border:2px solid #fff;background:#02a1c9;color:#fff;}

	.recommend{ text-align: center;}
	.r-channel{ text-align: left; padding: 0 0 10px 10px;}
	.r-channel h3{ font-size: 14px; padding: 10px 10px 10px 0;}
	.prodict_edit .r-channel input{ top: -2px;}


	/*活动样式*/
	.start_time .form-control,.end_time .form-control{ display: inline-block; width: 65px; text-align: center;}
	.start_time,.end_time{ text-align: left;}
	.start_time > span,.end_time > span{ padding: 0 10px;}
	/*求购样式*/
	.id_num{ display: inline-block; width: 60px; margin: 0 10px 0 5px; text-align: center;}
</style>
<body style="font-family: DejaVu Sans;" >
<div id="wrapper">
	<div style="width:900px;margin: 0 auto;border-left: 1px solid #ddd; " class="table-con">
				<table class ="prodict_edit">
					<tbody >
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
					</tbody>
				</table>
			</div>
</div>
</body>