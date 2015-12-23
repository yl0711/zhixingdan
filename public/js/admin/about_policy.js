/**
 * 关于牛社
 * @author:Riven
 * @date:20151217
 */
$(function(){

	// 点击修改按钮
	$('#dataListTable').on('click','.modify',function(){
		// 调用模态框
		modalView('show' ,true);
		addHtml();
		var oTr = $(this).closest('tr'),
			dataId = oTr.attr('data-id'),
			content = oTr.attr('content'),
			modifier = oTr.attr('modifier'),
			position = oTr.attr('position'),
			title = oTr.attr('title');
		$('.btn_edit_submit').attr('id',dataId);
		$('.prodict_edit').attr({
			'title':title,
			'content':content,
			'modifier':modifier,
			'position':position
		});
		// 编辑器赋值
		$('.title input').val(title);
		editorRichTextArea.setData(content);

	});

	// console.log(Router.officialdocs_modify.url);
});

var policyView = {
	modifyFn:function(that,id,position,title,admin_uname_modify,content){
		Loading('show');
		ajax_request(that,Router.officialdocs_modify.url,'post',{
			id:id,
			title:title,
			content:content,
		},function(that,msg){
			var data = msg.data;
			// console.log(msg.data);
			if (msg.status && msg.status==1) {
 				// 增加一条数据
				Loading('hide');
				GhostMsg(msg.info);
				modalView('hide' ,true);
				policyView.addData(id,position,title,admin_uname_modify,content);
			}else{
				Loading('hide');
				return GhostMsg("error:"+ msg.info),$('.btn_edit_submit').attr('data-modify','modify');
			};

		});
	},
	addData:function(id,position,title,admin_uname_modify,content){
		var html = '<td class="id">'+id+'</td>\
				<td class="position">'+position+'</td>\
				<td class="title">'+title+'</td>\
				<td class="time">刚刚</td>\
				<td class="modifier">'+admin_uname_modify+'</td>\
				<td class="operation">\
					<button type="button" class="modify btn btn-success" data-boolean ="ture">修改</button>\
				</td>'
		$('#'+id).html(html);
		$('#'+id).attr({
			'title':title,
			'content':content,
			'position':position,
			'modifier':admin_uname_modify
		});
		// console.log($('#'+id).attr('content'),$('#'+id).attr('title'))
	},
	submitData:function(id,position,admin_uname_modify){
		var title = $('.title input').val(),
			content = editorRichTextArea.getData();
		policyView.modifyFn(this,id,position,title,admin_uname_modify,content);
	}
};

var addHtml = function(){
 	var html;
 	if (!html) {
 		html = '';
 		html='<div id="edit" style="max-width:700px; margin:0 auto; border-left:1px solid #ddd;" class="table-con">\
				<table class="prodict_edit"><thead><tr><th colspan="2">文案基本信息</th></tr></thead>\
				<tbody><tr><td style="min-width:70px;">*标题</td><td class="title"><input value="" class="form-control" placeholder="添加标题" /></td></tr>\
				<tr><td>*详情</td><td class="_intro"><textarea name="RichTextArea"></textarea></td></tr>\
				<tr><th colspan= "2"><p style="line-height:30px;"><span id="msg_error" style="font-size:10px;" class="warning"></span></p><button class="btn_edit_submit btn btn-success">确认提交</button></th></tr>\
				</tbody></table></div>';
		$('.modal-body').html(html);
		// 调用富文本编辑器
	    editorRichTextArea = CKEDITOR.replace('RichTextArea', {/*toolbar : 'MyToolbar'*/});
	    $('.btn_edit_submit').on('click',function(){
	    	var id = $(this).attr('id'),
	    		oTable = $(this).closest('.prodict_edit'),
	    		position = oTable.attr('position'),
	    		admin_uname_modify = oTable.attr('modifier');
	    	policyView.submitData(id,position,admin_uname_modify);
	    })
 	};
}