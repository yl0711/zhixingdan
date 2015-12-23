/*  
*@Description: 活动列表
*@Author:      Riven  
*@Update:      Riven(2015-11-12)  
*/ 

var	draftGetAction  ='http://dadmin.nbclub.cc/draft/get', /*获得草稿*/
  	draftSetAction  ='http://dadmin.nbclub.cc/draft/set' ; /*草稿保存*/

$(function(){
 	// 点击添加按钮活动按钮弹出模态框
	$('.add-ico').on('click',function(){

		/*
		 * 先去获得草稿 ： 
 		 * 如果存在草稿，则显示。 
		 * 同时启动定时器 。50000ms调用草稿保存功能
		 **/
 		/*查看是否存在草稿*/
		

		// 调用模态框
		modalView('show' ,true);
		addHtml();
		activeListView.resetHtml();

		/*启动草稿保存*/
  		//baseInit.draftSave.start(checkInputMsg,draftSetAction , "article" ) ;
	});

	/*修改推荐权重值*/
	baseInit.byModifyStatusAction('recommend',modifyStatusAction);
	/*修改点赞数*/
	baseInit.byModifyStatusAction('like_count',modifyStatusAction);

	/*按照数据状态（是否已经开启）检索 */
 	//baseInit.seachByStatus(seachByStatusAction) ;
 	
	// 调用下拉搜索方法
	$('.seachByStatus').on('change',function(){
		var val = $(this).val();
		activeListView.selectSerch(val);
	});

	// 调用开启状态方法
	$('.on-off').live('click',function(){
		var statusAttr = $(this).attr('data-status'),
			dataId = $(this).parent().parent().attr('data-id'),
			status = null;
		switch (statusAttr){
			case '1':
				$(this).attr('data-status',0);
				$(this).html('开启');
				status = 0;
				activeListView.openState(this,dataId,status);
			break;
			case '0':
				$(this).attr('data-status',1);
				$(this).html('关闭');
				status = 1;
				activeListView.openState(this,dataId,status);
			break;
		};
	});

	// 删除按钮状态修改
	$('.btn-info').live('click',function(){
		var statusAttr = $(this).attr('data-status'),
			dataId = $(this).parent().parent().attr('data-id');
		if (statusAttr == -1) {
			$(this).html('还原');
			$(this).attr('data-status',1);
			$(this).next('.on-off').hide();
			activeListView.deleteState(this,dataId,statusAttr);
		}else if(statusAttr == 1){
			$(this).html('删除');
			$(this).attr('data-status',-1);
			$(this).next('.on-off').show();
			activeListView.deleteState(this,dataId,statusAttr);
		};	
	});
	
	// 点击修改按钮调用方法
	$('.modify').live('click',function(){
		var dataId = $(this).parent().parent().attr('data-id'),
			recommend =  parseInt($(this).parent().siblings('._recommend').find('.recommend').val() ||$(this).parent().siblings('._recommend').text()) ,
			like_count =  parseInt($(this).parent().siblings('._like_count').find('.like_count').val()|| $(this).parent().siblings('._like_count').text());
		// 调用获取修改前默认数据接口
		activeListView.modifyBefore(this,dataId);
		$('.btn_edit_submit').attr('id',dataId);
		$('.btn_edit_submit').attr('recommend',recommend);
		$('.btn_edit_submit').attr('like_count',like_count);
	});


});

// 创建方法
var activeListView = {
	// 下拉搜索
	selectSerch:function(val){
		location.href = seachByStatusAction + "/"+ val+ "?pageSize=" +  $('.pageSize').val() ;;
	},
	// 修改状态（开启、关闭）
	openState:function(that,dataId,status){
		// 加载loading
		Loading('show');
		ajax_request(that, modifyStatusAction, 'post', {id:dataId,status:status}, function (that,msg){
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
	// 修改状态（删除、还原）
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
	},
	// 修改之前默认数据
	modifyBefore:function(that,dataId){
		// 调用模态框
		modalView('show' ,true);
		addHtml();
		// 添加标记
		$('.btn_edit_submit').attr('data-modify','modify');
		// loading加载
		Loading('show');
		ajax_request(that, modifyInitial, 'post', {id:dataId}, function (that,msg){
			if (msg.status && msg.status==1) {
				// console.log(msg.data);
				var data = msg.data;
				console.log(data.intro);
				editorRichTextArea.setData(data.intro);
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
				// editorRichTextArea.setData(data.intro);
				
				// 开始时间拆分  格式：年 - 月 - 日 空格 时 : 冒号 分
				var startString = data.start_time,
					startYearstring = startString.substring(0,4),
					startMonthstring = startString.substring(5,7),
					startDaystring = startString.substring(8,10),
					startTimesstring = startString.substring(11,13),
					startMintring = startString.substring(14,16);
					$('.start_time .year').val(startYearstring);
					$('.start_time .month').val(startMonthstring);
					$('.start_time .day').val(startDaystring);
					$('.start_time .times').val(startTimesstring);
					$('.start_time .min').val(startMintring);

				// 结束时间拆分  格式：年 - 月 - 日 空格 时 : 冒号 分
				var endString = data.end_time,
					endYearstring = endString.substring(0,4),
					endMonthstring = endString.substring(5,7),
					endDaystring = endString.substring(8,10),
					endTimesstring = endString.substring(11,13),
					endMintring = endString.substring(14,16);
					$('.end_time .year').val(endYearstring);
					$('.end_time .month').val(endMonthstring);
					$('.end_time .day').val(endDaystring);
					$('.end_time .times').val(endTimesstring);
					$('.end_time .min').val(endMintring);

				// 人数
				$('.p-number input').val(''+data.member_total+'');
				// 地点
				$('.address input').val(''+data.address+'');

				Loading('hide');
				//GhostMsg(msg.info);
				
			}else{
				Loading('hide');
				return GhostMsg("error:"+ msg.info);
				
			};
		});
	},
	// 修改后数据接口调用
	modifyEnd:function (that,dataId,title,oldtitle,brief,intro,pic_big,start_time,end_time,member_total,address,recommend,like_count) {
		// loading加载
		Loading('show');
		ajax_request(that, modifyAction, 'post', {id:dataId,name:title,oldname:oldtitle,brief:brief,intro:intro,pic_big:pic_big,start_time:start_time,end_time:end_time,member_total:member_total,address:address}, function (that,msg){
			if (msg.status && msg.status==1) {
				
				Loading('hide');
				GhostMsg(msg.info);
				modalView('hide' ,true);
				addData(1,dataId,pic_big,title,brief,intro,start_time,end_time,member_total,address,recommend,like_count);
			}else{
				Loading('hide');
				return GhostMsg("error:"+ msg.info),$('.btn_edit_submit').attr('data-modify','modify');
			};
			
		});
	},
	// 添加活动信息接口调用
	addAcive:function(that,pic_big,title,brief,intro,start_time,end_time,member_total,address){
		// console.log(intro);
		Loading('show');
		ajax_request(that, addAction, 'post', {pic_big:pic_big,name:title,brief:brief,intro:intro,start_time:start_time,end_time:end_time,member_total:member_total,address:address}, function(_this,msg){
			if (msg.status && msg.status==1) {
 				// 增加一条数据
				Loading('hide');
				GhostMsg(msg.info);
				modalView('hide' ,true);
				
				addData(0,msg.data.id,pic_big,title,brief,intro,start_time,end_time,member_total,address,0,0);
			}else{
				Loading('hide');
				return GhostMsg("error:"+ msg.info);
			};
		});
	},
	// 初始化数据
	resetHtml:function(){
		$('.title input,.brief textarea,.start_time input,.end_time input,.p-number input,.address input').val('');
		$("#edit .add_img").attr("src" ,"/images/admin/iconfont-shangchuantupian.png");
		$("#edit .add_img").attr("data-url" ,"");
		// editorRichTextArea.setData('');
	},
	submitFn:function(dataId,recommend,like_count){
		// var dataId = $('.modify').parent().parent().attr('data-id'),
		var pic_big = $('.add_img').attr('data-url'),
			title = $('.title input').val(),
			oldtitle = $('.title').attr('data-title'),
			brief = $('.brief textarea').val(),
			intro = editorRichTextArea.getData(),
			// 获取开始时间val值
			startYear = $('.start_time .year').val(),
			startMonth = $('.start_time .month').val(),
			startDay = $('.start_time .day').val(),
			startTimes = $('.start_time .times').val(),
			startMin = $('.start_time .min').val(),
			// 拼接时间格式
			start_time = startYear+'-'+startMonth+'-'+startDay+' '+startTimes+':'+startMin,
			// 获取结束时间val值
			endtYear = $('.end_time .year').val(),
			endMonth = $('.end_time .month').val(),
			endDay = $('.end_time .day').val(),
			endTimes = $('.end_time .times').val(),
			endtMin = $('.end_time .min').val(),
			// 拼接时间格式
			end_time = endtYear+'-'+endMonth+'-'+endDay+' '+endTimes+':'+endtMin,
			member_total = $('.p-number input').val(),
			address = $('.address input').val(),
			reg = /[^\d]/g,
			modifyAttr = $('.btn_edit_submit').attr('data-modify');

			// 判断提交的属性（点击添加按钮还是修改按钮）
			if (modifyAttr == 'modify') {
				activeListView.modifyEnd(this,dataId,title,oldtitle,brief,intro,pic_big,start_time,end_time,member_total,address,recommend,like_count);
				$('.btn_edit_submit').removeAttr('data-modify');
			}else{
				activeListView.addAcive(this,pic_big,title,brief,intro,start_time,end_time,member_total,address);
			};
		
	}

}
  
// 新增数据
var addData = function(datatype,dataId,pic_big,title,brief,intro,start_time,end_time,member_total,address,recommend,like_count){
	var html = '';
	html+='<td class="_id">'+dataId+'</td>';
	html+='<td class="_pic"><img src="'+imageDomain+pic_big+'" class="min_img" data-url='+pic_big+'></td>';
	html+='<td>'+title+'</td>';
	html+='<td>0</td>';
	html+='<td>0</td>';
	html+='<td class="_like_count"><input style="width: 30px;" value="'+like_count+'" class="like_count" style="text-align:center;"></td>';
	html+='<td>0</td>';
	html+='<td>0</td>';
	html+='<td class= "_recommend"><input style="width: 30px;" value="'+recommend+'" class="recommend"></td>';
	html+='<td>admin</td>';
	html+='<td>'+start_time+'</td>';
	html+='<td>'+end_time+'</td>';
	html+='<td>'+member_total+'</td>';
	html+='<td>';	
	
	switch(datatype){
		case 0:
			html+= modifyBtn + ' ';
			html+= deleteBtn + ' ';
			html+= closeBtn + ' ';
			html+= memberBtn.replace('#dataId#', dataId);
			html+= '</td>';
			$('#dataListTable').prepend('<tr id = "data_'+dataId+'" data-id ="'+dataId+'" >'+html+'</tr>');
			
		break;
		case 1:
			var tdLastHtml = $('#data_'+dataId).find('td').last().html();
			// console.log($('#data_'+dataId).find('td').last().html());
			$('#data_'+dataId).html(html+tdLastHtml);
		break;
	}
	
};


/*上传图片*/
  	$("#edit .imgfile").live("change",function (){
  		
  		if(  $(this).val() !== ""){
  			$(this).closest("form").submit();
  		}
  	});

/*上传图片完成回调函数*/
function upImgCallback(msg){   
	console.log(msg)
	$('#edit .add_img').attr('src','http://'+msg.imageUrl);
	$('#edit .add_img').attr('data-url',msg.dbPath);
};
// 添加模态框内容
var addhtml ;
var addHtml = function(){
	// 赋值模态框内容
	if(!addhtml){
		addhtml = '';
 		addhtml += '<div id="edit" style="max-width:700px; margin:0 auto; border-left:1px solid #ddd;" class="table-con">';
		addhtml += '<table class="prodict_edit"><thead><tr><th colspan="2">活动基本信息</th></tr></thead><tbody>';
		// 封面图
		addhtml += '<tr><td style="width:150px;">*封面图<span style="display:block; padding-top:5px;">尺寸：720*404</span></td><td class="_pic">';
		addhtml += '<form style="width:150px; position:relative;" action="'+upimgAction+'" id="form_pic" name="form_pic" encType="multipart/form-data" method="post" target="hidden_frame">';
		addhtml += '<img class="add_img"  data-url=" "  src = "'+imageDomain+'/images/admin/iconfont-shangchuantupian.png"> ';
		addhtml += '<input type="file" data-url="" class="imgfile" name="uploadFile" style="opacity: 0; position: absolute; width: 100%;height:100%;top:0;left:0;" />';
		addhtml += '<iframe name="hidden_frame" id="hidden_frame" style="display: none;"></iframe>';
		addhtml += '</form></td></tr>';
		// 标题
		addhtml += '<tr><td>*标题</td><td class="title"><input value="" class="form-control" placeholder="添加标题" /></td></tr>';
		// 摘要
		addhtml += '<tr><td>摘要</td><td class="brief"><textarea class="form-control" rows="2" placeholder="添加摘要"></textarea></td></tr>';
		// 开始时间 格式：2015-12-11 10:10
		addhtml += '<tr><td>*开始时间</td><td class="start_time"><input type="text" class="year form-control" maxlength="4" /><span>年</span><input type="text" class="month form-control" maxlength="2" /><span>月</span><input type="text" class="day form-control" maxlength="2" /><span>日</span>&nbsp;<input type="text" class="times form-control" maxlength="2" /><span>时</span><input type="text" class="min form-control" maxlength="2" /><span>分</span></td></tr>';
		// 结束时间
		addhtml += '<tr><td>*结束时间</td><td class="end_time"><input type="text" class="year form-control" maxlength="4" /><span>年</span><input type="text" class="month form-control" maxlength="2" /><span>月</span><input type="text" class="day form-control" maxlength="2" /><span>日</span>&nbsp;<input type="text" class="times form-control" maxlength="2" /><span>时</span><input type="text" class="min form-control" maxlength="2" /><span>分</span></td></tr>';
		// 人数
		addhtml += '<tr><td>*人数</td><td class="p-number"><input value="" class="form-control" placeholder="添加人数" /></td></tr>';
		// 地点
		addhtml += '<tr><td>*地点</td><td class="address"><input value="" class="form-control" placeholder="添加地点" /></td></tr>';
		// 详情
		addhtml += '<tr><td>*详情</td><td class="_intro"><textarea name="RichTextArea"></textarea></td></tr>';
		addhtml += '<tr><th colspan= "2"><p style="line-height:30px;"><span id="msg_error" style="font-size:10px;" class="warning"></span></p><button class="btn_edit_submit btn btn-success">确认提交</button></th></tr></tbody></table>';
		addhtml += '</div>' ;
		$('.modal-body').html(addhtml);
		// 调用富文本编辑器
	    editorRichTextArea = CKEDITOR.replace('RichTextArea', {/*toolbar : 'MyToolbar'*/});

	    $('.btn_edit_submit').on('click',function(){
	    	var dataId = $(this).attr('id'),
	    		recommend = $(this).attr('recommend'),
	    		like_count = $(this).attr('like_count');
	    	activeListView.submitFn(dataId,recommend,like_count);
	    	// console.log(dataId);
	    });
	}
}