/**
 * 用户管理
 * @author:Riven
 * @date:20151221
 */

$(function(){
	// 修改开启关闭状态
	$('#dataListTable').on('click','.status_btn',function(){
		var data_status = $(this).attr('data-status'),
			dataId = $(this).closest('tr').attr('data-id');
		switch (data_status){
			case '1':
				$(this).html('开启').addClass('btn-success').removeClass('btn-danger');
				$(this).attr('data-status',0);
				statusView.openState(this,dataId,0);
			break;
			case '0':
				$(this).html('关闭').addClass('btn-danger').removeClass('btn-success');
				$(this).attr('data-status',1);
				statusView.openState(this,dataId,1);
			break;
		};
	});
	$('#dataListTable').on('click','.btn-info',function(){
		var data_status = $(this).attr('data-status'),
			dataId = $(this).closest('tr').attr('data-id');
			// alert(data_status)
		switch (data_status){
			case '-1':
				$(this).html('恢复');
				$(this).attr('data-status',1);
				statusView.deleteState(this,dataId,-1);
			break;
		};
	});
})
// 修改状态
var statusView = {
	openState:function(that,dataId,status){
		// loading
		Loading('show');
		ajax_request(that, Router.member.modifystatus, 'post',{
			id:dataId,
			status:status
		}, function (that,msg){
			if (msg.status && msg.status==1) {
				var statusAttr = $(that).attr('data-status');
				switch (statusAttr){
					case '1':
						$(that).html('关闭').addClass('btn-danger').removeClass('btn-success');
					break;

					case '0':
						$(that).html('开启').addClass('btn-success').removeClass('btn-danger');
					break;
				};
				/*if (statusAttr == -1) {
					$(that).html("已删除")
				};*/
				Loading('hide');
				GhostMsg(msg.info);
			}else{
				Loading('hide');
				return GhostMsg("error:"+ msg.info);
			};
		});
	},
	deleteState:function(that,dataId,status){
		// loading加载
		Loading('show');
		ajax_request(that, Router.member.modifystatus, 'post', {
			id:dataId,
			status:status
		}, function (that,msg){
			if (msg.status && msg.status==1) {
				var statusAttr = $(that).attr('data-status');
				// alert(statusAttr)
				if (statusAttr == -1) {
					$(that).html('删除');
				}/*else if(statusAttr == 1){
					$(that).html('恢复');
				}*/;
				Loading('hide');
				GhostMsg(msg.info);
			}else{
				Loading('hide');
				return GhostMsg("error:"+ msg.info);
				
			};
		});
	}
} 


