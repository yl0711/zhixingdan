/*  
*@Description: 求购列表
*@Author:      Riven  
*@Update:      Riven(2015-11-12)  
*/ 

$(function(){
	// 点击添加按钮
	$('.add-ico').on('click',function(){
		// 调用模态框
		modalView('show' ,true);
		addHtml();
		saleBuyView.resetHtml();
	});

	/*修改点赞数*/
	baseInit.byModifyStatusAction('like_count',modifyStatusAction);

	// 修改权重
	$('#dataListTable').on('change','.weight input',function(){
		var reg = /[^\d]/g,
			weightVal = $(this).val();
		if (weightVal == weightVal.replace(reg)) {
			ajax_request(this,modifyStatusAction,'post',{
				id:$(this).closest("tr").attr("data-id"),
				recommend:$(this).val()
			},function(_this,msg){
				GhostMsg(msg.info);
			});
		}else{
			alert('请填写数字');
		};
	});
	// 点击修改按钮
	$('#dataListTable').on('click','.modify',function(){
		// 调用模态框
		modalView('show' ,true);
		addHtml();
		var dataId = $(this).closest('tr').attr('data-id'),
			recommend = $(this).parent().siblings('.weight').find('.recommend').val();
		console.log(recommend);
		$('.btn_edit_submit').attr({
			'data-modify':'modify',
			'id':dataId,
			'recommend':recommend
		});
		saleBuyView.modifyBefore(this,dataId);
		// alert($('.id_num').attr('good_id'));
		
		
	});

	// 修改关闭开启状态
	$('.on-off').live('click',function(){
		var statusAttr = $(this).attr('data-status'),
			dataId = $(this).closest('tr').attr('data-id'),
			status = null;
		switch (statusAttr){
			case '1':
				$(this).attr('data-status',0);
				$(this).html('开启');
				$(this).addClass('btn-warning').removeClass('btn-danger')
				status = 0;
				saleBuyView.openState(this,dataId,status);
			break;
			case '0':
				$(this).attr('data-status',1);
				$(this).html('关闭');
				$(this).addClass('btn-danger').removeClass('btn-warning')
				status = 1;
				saleBuyView.openState(this,dataId,status);
			break;
		};
	});

	// 修改删除还原状态
	$('.btn-info').live('click',function(){
		var statusAttr = $(this).attr('data-status'),
			dataId = $(this).closest('tr').attr('data-id');
		if (statusAttr == -1) {
			$(this).html('还原');
			$(this).attr('data-status',1);
			$(this).next('.on-off').hide();
			saleBuyView.deleteState(this,dataId,statusAttr);
		}else if(statusAttr == 1){
			$(this).html('删除');
			$(this).attr('data-status',-1);
			$(this).next('.on-off').show();
			saleBuyView.deleteState(this,dataId,statusAttr);
		};	
	});

	// 搜索名字
	$('.btn_seach').click(function(){
		var searVal = $('.seachByName').val();
		var location =  seachByNameAction + "?name="+ searVal;
		$('#form_seach').attr('action',location);
	});

	// 下拉搜索
	$('.seachByStatus').on('change',function(){
		var optionVal = $(this).val();
		// console.log(optionVal);
		location.href = seachByNameAction + '/' + optionVal;
	});

	//替换交易成功内容 
	$('#dataListTable tr').each(function(){
		// console.log($(this).find('.trading').html());
		var tradingHtml = $(this).find('.trading').html(),
			publisherHtml = $(this).find('.publisher').html(),
			last_restPersonHtml = $(this).find('.last_restPerson').html();
		if (tradingHtml == '0') {
			$(this).find('.trading').html('未交易');
		}else{
			$(this).find('.trading').html('已交易');
		};
		if (publisherHtml == '0') {
			$(this).find('.publisher').html('暂无发布人');
		};
		if (last_restPersonHtml == '') {
			$(this).find('.last_restPerson').html('暂无修改人');
		};

	});
});


var saleBuyView = {
	// 初始化添加默认值
	resetHtml:function(){
		$('.title input,.brief textarea,.start_time input,.end_time input,.p-number input,.address input').val('');
		$("#edit .add_img").attr("src" ,"/images/admin/iconfont-shangchuantupian.png");
		$("#edit .add_img").attr("data-url" ,"");
		$(".id_num").val('');
		$("#inlineRadio1").removeAttr('checked');
		editorRichTextArea.setData('');
	},
	// 添加求购调用接口
	addBuy:function(that,pic_big,title,brief,intro,good_id,type_id,deal_status,deal){
		Loading('show');
		ajax_request(that,addAction,'post',{
			pic_big:pic_big,
			name:title,
			brief:brief,
			intro:intro,
			good_id:good_id, //关联产品ID
			type_id:type_id, //推荐频道
			deal_status:deal_status, //交易是否成功
			deal:deal //交易性质，求购还是出售，求购0，出售1
		},function(that,msg){
			var data = msg.data;
			console.log(data);
			if (msg.status && msg.status==1) {
 				// 增加一条数据
				Loading('hide');
				GhostMsg(msg.info);
				modalView('hide' ,true);
				saleBuyView.addData(0,data.id,pic_big,title,brief,intro,good_id,type_id,deal_status,0,0,data.admin_uname);
			}else{
				Loading('hide');
				return GhostMsg("error:"+ msg.info);
			};

		});

	},
	// 添加数据
	addData:function(dataType,dataId,pic_big,title,brief,intro,good_id,type_id,deal_status,recommend,like_count,admin_uname){
		var html = '';
		html +='<td class= "id">'+dataId+'</td>';
		html +='<td class= "pic"><img class = "min_img" src="'+imageDomain+pic_big+'" data-url='+pic_big+'></td>';
		html +='<td class= "title">'+title+'</td>';
		html +='<td class= "publisher">0</td>';
		html +='<td class="comment">0</td>';
		html +='<td class="praise"><input class = "like_count" value="'+like_count+'" style="width: 30px; text-align:center;"></td></td>';
		html +='<td class="collection">0</td>';
		html +='<td class="trading">'+deal_status+'</td>';
		html +='<td class="rest_time">刚刚</td>';
		html +='<td class="weight"><input class = "recommend" value="'+recommend+'" style="width: 30px;"></td>';
		html +='<td class="recommend">'+type_id+'</td>';
		html +='<td class="good_id">'+good_id+'</td>';
		html +='<td class="last_restPerson">'+admin_uname+'</td>';
		html +='<td class="operation">';
		// 判断操作内容
		switch(dataType){
			case 0:
				html += modifyBtn;
				html += deleteBtn;
				html += closeBtn;
				html += '</td>';
				$('#dataListTable').prepend('<tr id = "'+dataId+'" data-id ="'+dataId+'" >'+html+'</tr>');
			break;
			case 1:
				var tdLastHtml = $('#'+dataId).find('td').last().html();
				console.log($('#'+dataId).find('td').last().html());
				$('#'+dataId).html(html+tdLastHtml);
			break;
		};

	},
	// 修改之前默认数据
	modifyBefore:function(that,dataId){
		// loading加载
		Loading('show');
		ajax_request(that,modifyInitial,'get',{id:dataId},function (that,msg){
			if (msg.status && msg.status==1) {
				var data = msg.data;
				console.log(data);
				/*赋值*/
				// 封面图
				$('.add_img').attr({
					'src':'http://dimage.nbclub.cc'+data.pic_big+'',
					'data-url':''+data.pic_big+''
				});
				$('.imgfile').attr('data-url',''+data.pic_big+'');
				// 标题
				$('.title input').val(''+data.name+'');
				// 存储oldname
				$('.title').attr('data-title',''+data.name+'');
				// 摘要
				$('.brief textarea').val(''+data.brief+'');
				// 详情
				// $('._intro textarea').val(''+data.intro+'');
				editorRichTextArea.setData(data.intro);
				// 关联产品ID
				$('.btn_edit_submit').attr('good_id',data.good_id);
				$('.id_num').val(''+data.good_id+'');
				// console.log(data.good_id);
				if ($('.id_num').val() != '0') {
					$('.sure').addClass('btn-success');
				};
				// 推荐频道
				$('.r-channel .radio-inline').each(function(){
					if ($(this).index() == data.type_id) {
						console.log(data.type_id);
						$(this).find('input').attr('checked',true);
						$(this).siblings().find('input').removeAttr('checked');
						$('.btn_edit_submit').attr('data-num',data.type_id);
					};
				});
				
				// 交易成功
				$('.btn_edit_submit').attr('deal_status',data.deal_status);

				$('.prodict_edit').attr('like_count',data.like_count);
				$('.prodict_edit').attr('admin_uname',data.admin_uname);


				Loading('hide');
				//GhostMsg(msg.info);
				
			}else{
				Loading('hide');
				return GhostMsg("error:"+ msg.info);
				
			};
		});
	},
	// 修改之后数据
	modifyEnd:function(that,dataId,pic_big,title,oldtitle,brief,intro,good_id,type_id,deal_status,recommend,like_count,admin_uname){
		Loading('show');
		ajax_request(that,modifyAction,'post',{
			id:dataId,
			pic_big:pic_big,
			name:title,
			oldname:oldtitle,
			brief:brief,
			intro:intro,
			good_id:good_id, //关联产品ID
			type_id:type_id //推荐频道
		},function(that,msg){
			// var data = msg.data;
			// console.log(msg.data);
			if (msg.status && msg.status==1) {
 				// 增加一条数据
				Loading('hide');
				GhostMsg(msg.info);
				modalView('hide' ,true);
				console.log(title+'a')
				saleBuyView.addData(1,dataId,pic_big,title,brief,intro,good_id,type_id,deal_status,recommend,like_count,admin_uname);
			}else{
				Loading('hide');
				return GhostMsg("error:"+ msg.info),$('.btn_edit_submit').attr('data-modify','modify');
			};

		});
	},
	// 点击提交按钮提交数据
	submitData:function(dataId,good_id,type_id,deal_status,recommend,like_count,admin_uname){
		var pic_big = $('.add_img').attr('data-url'),
			title = $('.title input').val(),
			oldtitle = $('.title').attr('data-title'),
			brief = $('.brief textarea').val(),
			intro = editorRichTextArea.getData(),
			modifyAtrr = $('.btn_edit_submit').attr('data-modify');
			// console.log(title);

		// 根据提交按钮Attr属性，判断点击入口
		if (modifyAtrr == 'modify') {
			// alert('修改1');
			saleBuyView.modifyEnd(this,dataId,pic_big,title,oldtitle,brief,intro,good_id,type_id,deal_status,recommend,like_count,admin_uname);
			$('.btn_edit_submit').removeAttr('data-modify');
		}else{
			// alert('添加');
			saleBuyView.addBuy(this,pic_big,title,brief,intro,good_id,type_id,0,0);
		};

	},
	// 修改状态接口(开启、关闭)
	openState:function(that,dataId,status){
		// loading
		Loading('show');
		ajax_request(that, modifyStatusAction, 'post',{
			id:dataId,
			status:status
		}, function (that,msg){
			if (msg.status && msg.status==1) {
				var statusAttr = $(that).attr('data-status');
				switch (statusAttr){
					case '1':
						$(that).html('关闭');
						$(that).addClass('btn-danger');
						$(that).removeClass('btn-warning')
					break;

					case '0':
						$(that).html('开启');
						$(that).addClass('btn-warning');
						$(that).removeClass('btn-danger');
					break;
				};
				if (statusAttr == -1) {
					$(that).html("已删除")
				};
				Loading('hide');
				GhostMsg(msg.info);
			}else{
				Loading('hide');
				return GhostMsg("error:"+ msg.info);
			};
		});
	},
	// 修改状态接口(删除、还原)
	deleteState:function(that,dataId,status){
		// loading加载
		Loading('show');
		ajax_request(that, modifyStatusAction, 'post', {id:dataId,status:status}, function (that,msg){
			if (msg.status && msg.status==1) {
				var statusAttr = $(that).attr('data-status');
				if (statusAttr == -1) {
					$(that).html('删除');
				}else if(statusAttr == 1){
					$(that).html('还原');
				};
				Loading('hide');
				GhostMsg(msg.info);
			}else{
				Loading('hide');
				return GhostMsg("error:"+ msg.info);
				
			};
		});
	}


}


/*上传图片*/
$("#edit .imgfile").live("change",function (){
	if(  $(this).val() !== ""){
		$(this).closest("form").submit();
	}
});

/*上传图片完成回调函数*/
function upImgCallback(msg){   
	// console.log(msg)
	$('#edit .add_img').attr('src','http://'+msg.imageUrl);
	$('#edit .add_img').attr('data-url',msg.dbPath);
};


// 添加模态框内容
var addHtml = function(){
	// 赋值模态框内容
	var addhtml ;
	if (!addhtml) {
		addhtml = '';
 		addhtml += '<div id="edit" style="max-width:700px; margin:0 auto; border-left:1px solid #ddd;" class="table-con">';
		addhtml += '<table class="prodict_edit"><thead><tr><th colspan="2">求购基本信息</th></tr></thead><tbody>';
		// 封面图
		addhtml += '<tr><td style="width:150px;">*封面图<span style="display:block; padding-top:5px;">尺寸：720*404</span></td><td class="_pic">';
		addhtml += '<form style="width:150px; position:relative;" action="'+upimgAction+'" id="form_pic" name="form_pic" encType="multipart/form-data" method="post" target="hidden_frame">';
		addhtml += '<img class="add_img"  data-url=" " src = "'+imageDomain+'/images/admin/iconfont-shangchuantupian.png"> ';
		addhtml += '<input type="file" data-url="" class="imgfile" name="uploadFile" style="opacity: 0; position: absolute; width: 100%;height:100%;top:0;left:0;" />';
		addhtml += '<iframe name="hidden_frame" id="hidden_frame" style="display: none;"></iframe>';
		addhtml += '</form></td></tr>';
		// 标题
		addhtml += '<tr><td>*标题</td><td class="title"><input value="" class="form-control" placeholder="添加标题" /></td></tr>';
		// 摘要
		addhtml += '<tr><td>摘要</td><td class="brief"><textarea class="form-control" rows="2" placeholder="添加摘要"></textarea></td></tr>';
		// 关联产品ID
		addhtml += '<tr><td>关联产品ID</td><td style="text-align:left;"><input type="text" class="form-control id_num" value="" class=""><span class="name"></span><button class="btn btn-default sure">确定</button></td></tr>';
		
		// 推荐频道
		addhtml += '<tr><td><h3>推荐频道</h3></td><td><div class="r-channel" style="padding-bottom:0;"><label class="radio-inline" data-num="0"><input id="inlineRadio1" type="radio" value="0" name="inlineRadioOptions">不推荐</label><label class="radio-inline" data-num="1"><input id="inlineRadio1" type="radio" value="1" name="inlineRadioOptions">首页-牛社快讯</label><label class="radio-inline" data-num="2"><input id="inlineRadio1" type="radio" value="2" name="inlineRadioOptions">首页-焦点图</label><label class="radio-inline" data-num="3"><input id="inlineRadio1" type="radio" value="3" name="inlineRadioOptions">爆料详情页-推荐</label>';
		addhtml +='</div></td></tr>';
		// 详情
		addhtml += '<tr><td>*详情</td><td class="_intro"><textarea name="RichTextArea"></textarea></td></tr>';
		addhtml += '<tr><th colspan= "2"><p style="line-height:30px;"><span id="msg_error" style="font-size:10px;" class="warning"></span></p><button class="btn_edit_submit btn btn-success">确认提交</button></th></tr></tbody></table>';
		addhtml += '</div>' ;
		$('.modal-body').html(addhtml);
		// 调用富文本编辑器
	    editorRichTextArea = CKEDITOR.replace('RichTextArea', {/*toolbar : 'MyToolbar'*/});

	    $('.id_num').on('change',function(){
	    	var reg = /[^\d]/g,
	    		idVal = $(this).val();
	    	$('.sure').removeClass('btn-success');
	    	if (idVal == idVal.replace(reg)) {
	    		ajax_request(this,getGoodname,'get',{
		    		id:$(this).val()
		    	},function(_this,msg){
		    		console.log(msg.name);
		    		$(_this).siblings('span.name').html(''+msg.name+'').css('padding-right','10px');
		    	});
	    	}else{
	    		alert('请输入正确的ID数字');
	    	};
	    	
	    });

	    $('.sure').on('click',function(){
	    	$('.btn_edit_submit').attr('good_id',$('.id_num').val());
	    	$(this).addClass('btn-success');
	    	GhostMsg('关联成功');
	    });

	    $('.radio-inline input').click(function(){
	   			var oParent = $(this).parent(),
	   				index = oParent.index(),
	   				dataNum = oParent.attr('data-num');
		    	/*console.log(index);
		    	console.log(dataNum);*/
		    	if (index == dataNum) {
		    		$(this).attr('checked',true);
		    		$('.btn_edit_submit').attr('data-num',dataNum);
		    		oParent.siblings().find('input').removeAttr('checked');
		    	};
		    	
	    	});

	    
	    // 点击提交按钮提交数据
	    $('.btn_edit_submit').on('click',function(){
	    	var dataId = $(this).attr('id'),
	    		good_id = $(this).attr('good_id'),
	    		type_id = $(this).attr('data-num'),
	    		recommend = $(this).attr('recommend'),
	    		deal_status = $(this).attr('deal_status'),
	    		like_count = $(this).closest('.prodict_edit').attr('like_count'),
	    		admin_uname = $(this).closest('.prodict_edit').attr('admin_uname');
	    	// alert(11);
	    	// console.log(dataId,good_id,type_id,like_count);
	    	saleBuyView.submitData(dataId,good_id,type_id,deal_status,recommend,like_count,admin_uname);
	    });


	};

}