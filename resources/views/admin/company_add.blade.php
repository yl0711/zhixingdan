@include('admin/static/header')
<div id="wrapper">
@include('admin/static/leftside')
	<div class="content-r">
		<div class="table-box">
			<div class="search-box">
				<div class = "table_tit" style="float: left;padding: 15px;"><h1>权限管理 --> 部门管理 --> 添加</h1></div>
			</div>
			<div style="width:700px;margin: 0 auto;border-left: 1px solid #ddd; " class="table-con">	
				@include('admin/module/company_submit_form', ['page_type'=>'add'])
			</div>
		</div>
	</div>
	@include('admin/static/footer')
</div>
</body>

@include('admin/module/company_submit_script', ['page_type'=>'add'])