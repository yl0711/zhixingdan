@include('admin/static/header')
<div id="wrapper">
@include('admin/static/leftside')

	<div class="content-r">
		<div class="table-box">
			<div class="search-box">
				<div class = "table_tit" style="float: left;padding: 15px;"><h1>{{$navigation}}</h1></div>
			</div>
			<div style="width:700px;margin: 0 auto;border-left: 1px solid #ddd; " class="table-con">	
				@include('admin/company/module/submit_form', ['page_type'=>'modify'])
			</div>
		</div>
	</div>
	@include('admin/static/footer')
</div>
</body>

@include('admin/company/module/submit_script', ['page_type'=>'modify'])