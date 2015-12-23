/*  
*@Description: 报名列表
*@Author:      Riven  
*@Update:      Riven(2015-11-12)  
*/ 

$(function(){
	// console.log(modifyMemberStatusAction,seachByNameAction);
	// 修改操作状态
	/*$('.delete').on('click',function(){
		var trBox = $(this).parent().parent(),
			dataId = trBox.attr('data-id');
		// 调用接口
		ajax_request(this, modifyMemberStatusAction, 'post', {id:dataId,status:-1}, function (_this,msg){
			if (msg.status && msg.status==1) {
				trBox.hide();
			};
		});
	});*/

	// 调用下拉查询方法
	$('.seachByStatus').on('change',function(){
		var val = $(this).val();
		memberView.selectSearch(val);
	});
	
})

var memberView = {
	// 下拉查询（全部/删除）
	selectSearch:function(val){
		location.href = seachByStatusAction + "/"+ val+ "?pageSize=" +  $('.pageSize').val() ;;
	}
}