<script>
var article_check = article_view = 0;

$(function() {
	$('#admin_user_group_submit').bind('click', function(){
		if (false != check_submit_data()) {
			$.ajax({
				type:"post",
				dataType:"json",
				url:@if ($page_type=='modify') "{{url('usergroup/modify')}}" @else "{{url('usergroup/add')}}" @endif ,
				data:{
					'gid':$('#gid').val(),
					'gname':$('#gname').val(),
				@if ($page_type=='modify')
					'oldgname':$('#oldgname').val(),
				@endif
					'parentid':$('#parentid').val(),
					'article_check':article_check,
					'article_view':article_view,
				},
				async:false,
				success:function($data) {
					if ($data.status == 'error') {
						alert($data.info);
					} else {
						if (confirm('数据提交成功, 是否返回列表页')) {
							window.location.href = "{{url('usergroup/index')}}";
						}
					}
				}
			});
		}
	});
});

function check_submit_data() {
	if (0 == $('#gname').val().trim().length) {
		alert('用户组名称不能为空');
		$('#gname').focus();
		return false;
	}
	article_check = $(":radio[name=article_check]:checked").val();
	article_view = $(":radio[name=article_view]:checked").val();
}
</script>