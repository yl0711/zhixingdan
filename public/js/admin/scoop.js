/*初始化*/
 var upimgAction, 	/*上传图片*/
	 imageDomain ,	/*图片HOST  path*/
 	 addAction ,	/*添加一条数据*/
 	 modifyAction ,	/*修改产品信息*/
 	 modifyStatusAction ,	/*修改产品的 status*/
 	 seachByStatusAction ,	/*按照状态查询*/
 	 seachByNameAction ,	/*模糊查询*/
 	 getContactAction ,	/*获得标签厂商IP*/
 	 modifyInitial,	 /*修改 时候 根据产品 获取 产品信息*/
 	 modifyBtn, openBtn, closeBtn, deleteBtn, huanyuanBtn,
  	 draftGetAction  , /*获得草稿*/
  	 draftSetAction  ; /*草稿保存*/
  	 /* 转换为json*//*获得标签厂商IP*/
  	 getContactAction = eval('(' + getContactAction + ')'); 

$(function (){
	init() ;
})	

function init(){

	/*初始化模态框内的值*/
	initEditDefaultVal();
	
	/*修改推荐权重值*/
	baseInit.byModifyStatusAction('recommend',modifyStatusAction);
	/*修改点赞数*/
	baseInit.byModifyStatusAction('like_count',modifyStatusAction);

	/*按照数据状态（是否已经开启）检索 */
 	baseInit.seachByStatus(seachByStatusAction) ;
 	
	/*添加一条数据*/
	$(".add-ico").bind("click",function(){
		/*数据初始化*/
		initEditDefaultVal () ;
 		/*
		 * 先去获得草稿 ： 
 		 * 如果存在草稿，则显示。 
		 * 同时启动定时器 。50000ms调用草稿保存功能
		 **/ 
 		/*查看是否存在草稿*/
 		Loading('show');
		baseInit.draftSave.getDraft(draftGetAction , "article" ,function(_this,draftInfo){
			Loading('hide');
	 		if(draftInfo){
				for(var i in draftInfo){
					switch(i){
						case 'tag':
							editDefaultVal[i] = draftInfo[i].split(',');
						break;
						default:
							editDefaultVal[i] = draftInfo[i] ;
						break;
					}	
				}
				GhostMsg("已自动填充草稿中的数据");
			}
	   		/*显示 添加模态框 */
			addProductMode();
	 		/*启动草稿保存*/
	  		baseInit.draftSave.start(checkInputMsg,true,draftSetAction , "article" ) ;
		}) ;
		
 	});

     /*修改一条数据*/
	$(".modify").live("click",function(){
		modifyByThis(this);
 	});
 	
 	/*克隆一条数据*/
	$(".copy").live("click",function(){
		modifyByThis(this);
	});

 	function modifyByThis( _this ){
 		Loading('show');
		var $_closest = $(_this).closest("tr"),
		 	thisPic =$_closest.find("._pic").find("img").attr("src"),
			_dataId = $_closest.attr("data-id");
   			ajax_request(_this, modifyInitial , "post",{id:_dataId} ,function (_this,msg){
  				if(msg.status && msg.status==1){
  					var OtherMsg = msg.data;
  					var _tag = [] ; 
 					for(var i in OtherMsg.tag){
						_tag.push(''+OtherMsg.tag[i].id)
					}
   					editDefaultVal ={
						modal:{id:_dataId ,title:"修改" , req:"modify"},
						id :_dataId ,
						recommend : parseInt( $_closest.find(".recommend").val() || $_closest.find("._recommend").text() ),
						like_count :   parseInt( $_closest.find(".like_count").val()|| $_closest.find(".like_count").text() ),
						pic_big  : OtherMsg.pic_big  ,
						name : $_closest.find("._name").text(),
						brief :  OtherMsg.brief,
 						tag : _tag ,
  						status :$_closest.find(".on-off").attr('data-status'),
 						intro : OtherMsg.intro ,
 						type_id:OtherMsg.type_id,
 						brief:OtherMsg.brief
					}
 					if( $(_this).hasClass('modify' ) ){
						editDefaultVal.modal = {
							id:_dataId,
							title:"修改",
							req:"modify",
						};
						addProductMode();
  					}
 				}else{
					return GhostMsg();
				}
				Loading("hide") ;
			}) ;
 	}

	/*更改状态*/
	$(".on-off").live("click",function(){
		Loading() ;
		 var _dataId = $(this).closest("tr").attr("data-id");
		 var status = 0 ;
	 	 var data_status= $(this).attr('data-status');
		switch(data_status){
			case '1': //关闭
				status = 0 ;
			break;
			case '0': //开启
				status = 1 ;
			break;
			case '-1': //删除
				status = -1 ;
			break;
			case '-2': //还原
				status = 1 ;
			break;
		}
		// 请求数据
		editDefaultVal.status = status ;
		ajax_request(this, modifyStatusAction , "post",{id:_dataId,status:status} ,modifyStatus_success) ;
 	});

	/*上传图片*/
  	$("#edit .imgfile").live("change",function (){
  		if($(this).closest("td").hasClass("_pic")){
  			current_add_img = "_pic";
  		}else{
  			current_add_img = "_pic_big";
  		}
  		if(  $(this).val() !== ""){
  			$(this).closest("form").submit();
  		}
  	});

	/*确认提交*/
	$(".btn_edit_submit").live("click",function (){
 		var _Msg = checkInputMsg();
 		
		if(typeof _Msg=="object" && !$(this).hasClass("lock")){
			$(this).addClass("lock");
			$(this).text("提交中...");
			$("#msg_error").html('');
			
			if($(".modal-title").attr( "data-req") == "modify"){
 				ajax_request(this, modifyAction , "post", _Msg , modify_success) ;
			}else{
 				/*请求数据*/
				ajax_request(this, addAction , "post", _Msg, add_success) ;	
			}
		}else{
			$("#msg_error").html(_Msg);
		}
	});

	/*清空编辑框中的数据*/
	$(".btn_edit_empty").live("click",function (){
		
		edits_Empty();
	});
  	
	/*选择*/
	$(".mymode").live("click",function()
	{
		var  _tit =$(this).html()  ;
 		var  _txt1 = "选择",_txt2 = "收起" ;
 		 if( _tit.indexOf(_txt1) < 0 )
 		 {
		 	/*收齐*/
		 	$(this).html(_tit.replace(_txt2,_txt1));
		 	$(this).closest("td").find(".dynamicDiv").slideUp("fast");	
		 }
 		 else
		 {
		 	$(".dynamicDiv").slideUp("fast");	
		 	$(".mymode").html(_tit.replace(_txt2,_txt1));
		 	$(this).html(_tit.replace(_txt1,_txt2));
		 	$(this).closest("td").find(".dynamicDiv").slideDown("fast");	
		 }
		 
 	});
 	
	/*按类别查询查询 规格*/
	$('.EN_serch li').live("click",function(){
		var thisId = $(this).closest('td').attr('id') ;
 		$(this).siblings("li").removeClass("current").end().addClass("current");
		 if($(this).closest('td').hasClass('_tag')){
 			var PP = Product_Parm_ArreaList[thisId] ;
			var _html = '';
  			//判断是否已经选择过了
			 isIncude = function (j){
				for(var k in  editDefaultVal[thisId])
					{
						if(PP[j].id == editDefaultVal[thisId][k] )
						{
							return true ; 
						}
					 }
			}
			if($(this).text() == "全部"){
				for(var i in  PP){
					if( !isIncude(i) ){
						_html +='<li data-id="'+ PP[i].id +'" data-status ="'+PP[i].status+'" >'+ PP[i].name +'</li>';
	 				}
				}
			}else{
				for(var i in  PP){
					if( ($(this).text()).indexOf(PP[i].first_word) !==-1  ){
		 				if( !isIncude(i) ){
	 						_html +='<li  data-id="'+ PP[i].id +'" data-status ="'+PP[i].status+'" >'+ PP[i].name +'</li>';
	 					}
		 			}
		 		}
 			}
			$(this).closest('td').find('.seach_list').attr('this-in',$(this).attr('this-in')) ;
			$(this).closest('td').find('.seach_list').html(_html);
 		}

	});
	
	/*选择*/
	$('.seach_list li').live("click",function(){
		_cur = $(this).parent().attr("data-parent");
		
		$('#'+_cur+' .selected_list').append($(this).clone());
		$(this).remove();
		if(_cur == "tag"){
			if(!editDefaultVal[_cur]){editDefaultVal[_cur] = []}
			editDefaultVal[_cur].push($(this).attr('data-id'))
		};
  	});

	/*删除已选中*/
	$('.selected_list li').live("click",function(){
		_cur = $(this).parent().attr("data-parent");
		var _id = $(this).attr('data-id');
 		/*判断所属类别*/
		isNormsByType = function (){
			for(var k in Product_Parm_ArreaList[_cur]){
				for(var n in Product_Parm_ArreaList[_cur][k].norms_value){
					if(_id == Product_Parm_ArreaList[_cur][k].norms_value[n].id ){
						return k ;
					}
				}
			}
			return 0 ;
		}

  		if(!$(this).hasClass("mymode")){
			if( $('#'+_cur+' .seach_list').attr('this-in') == isNormsByType()){
				$('#'+_cur+' .seach_list').append($(this).clone());
			}
			$(this).remove();
			delateEditDefaultVal(_cur , _id) ;
		}
		 
	});
	
}

//删除数组中的某一个元素
 function delateEditDefaultVal(cur,_id){
	for(var i in editDefaultVal[cur]){
		if(editDefaultVal[cur][i] == _id){
			editDefaultVal[cur].splice(i,1) ;
			return true ;
		}
	}
}

 
/*清空编辑框中的数据*/
var edits_Empty = function (){
	$("#edit .add_img").attr("src" ,"/images/admin/iconfont-shangchuantupian.png")  ;
	$("#edit .add_img").attr("data-url" ,"")  ;
 	$("#edit ._name input").val( "" );
	$("#edit ._intro textarea").text("") ;
}

/*添加 数据 成功 后 回调函数*/
var current_add_img;

var add_success = function (_this,msg){
	
	if( msg &&  msg.status && msg.status == 1  &&  msg.data.id ){
 		// 添加成功后在列表中增一条数据
 		modalView('hide');
		var _html = '<tr id = "data_'+  msg.data.id +'"　 data-id = "'+  msg.data.id +'" >' ;
			_html += '<td class= "_id" >'+ msg.data.id +'</td>' ;
			
			_html += '<td class= "_pic" ><img data-url="'+editDefaultVal.pic_big+'" src="'+imageDomain+editDefaultVal.pic_big+'" class = "min_img" ></td>' ;
			_html += '<td class= "_name" >'+editDefaultVal.name+'</td>' ;
			_html += '<td >0</td><td >0</td><td class= "_like_count" ><input class = "like_count" value="'+editDefaultVal.like_count+'" style="width: 30px;"></td><td>0</td><td>0</td>'
			_html += ' <td>刚刚</td> <td class= "_recommend" > <input class = "recommend" value="'+editDefaultVal.recommend+'" style="width: 30px;"></td> <td>'+editDefaultVal.type_id+'</td>' ;
			_html += '<td >' + modifyBtn + '  ' + deleteBtn + '  ' + closeBtn + '</td> ' ;
			_html += '</tr>';
			

		$("#dataListTable").prepend(_html);
		
	}else{
		GhostMsg("error:"+ msg.info);
	};
	
	$(_this).removeClass("lock");
	$(_this).text("确认提交");
 }



/*修改 数据 成功 后 回调函数*/
var modify_success = function (_this,msg){
  	if(msg && msg.status && msg.status == 1){
		modalView('hide');
		var _html = '',
			restHtml = $("#dataListTable #data_"+editDefaultVal.id ).find('td').last().html();
 			_html += '<td class= "_id" >'+ msg.data.id +'</td>' ;
			_html += '<td class= "_pic" ><img data-url="'+editDefaultVal.pic_big+'" src="'+imageDomain+editDefaultVal.pic_big+'" class = "min_img" ></td>' ;
			_html += '<td class= "_name" >'+editDefaultVal.name+'</td>' ;
			_html += '<td>0</td><td >0</td><td><input class = "like_count" value="'+editDefaultVal.like_count+'" style="width: 30px;"></td><td>0</td><td>0</td>'
			_html += ' <td>刚刚</td><td><input class = "recommend" value="'+editDefaultVal.recommend+'" style="width: 30px;"></td> <td>'+editDefaultVal.type_id+'</td>';
			_html += '<td>'+restHtml+'</td>';
		$("#dataListTable #data_"+editDefaultVal.id ).html( _html ) ;
	}else{
		 GhostMsg("error:"+  msg.info);
	}
	
	$(_this).removeClass("lock");
	$(_this).text("确认提交");
}

/*更改状态 数据 成功 后 回调函数*/

var modifyStatus_success =  function(_this,msg){
	if(msg && msg.status && msg.status == 1){
		switch($(_this).attr('data-status')){
			case '1': //关闭
				$(_this).html('开启');
				$(_this).attr('data-status',0);
				$(_this).removeClass("btn-danger");
				$(_this).addClass("btn-warning");
			break;
			case '0': //开启
				$(_this).text("关闭");
				$(_this).attr('data-status',1);
				$(_this).removeClass("btn-warning");
				$(_this).addClass("btn-danger");
			break;
			case '-1': //删除
				$(_this).text("还原");
				$(_this).attr('data-status',-2);
				$(_this).next('.on-off').hide();
			break;
			case '-2': //删除
				$(_this).text("删除");
				$(_this).attr('data-status',-1);
				$(_this).next('.on-off').show();
			break;
		}
		GhostMsg(msg.info);
	}else{
		return GhostMsg("error:"+ msg.info);
	}
	Loading("hide") ;
 }

/*上传图片完成回调函数*/
function upImgCallback(msg){   
 	$("#edit ."+current_add_img+" .add_img").attr("data-url",msg.dbPath);
    $("#edit ."+current_add_img+" .add_img").attr("src",imageDomain + msg.dbPath);  
};

/*校验提交数据是否合格*/

function checkInputMsg(draft){
	var getSelectedIds = function (sel){
		var str="";
 		 $("."+sel+" .selected_list li").each(function(){
		 	if(!$(this).hasClass("mymode")){
		 		str+=$(this).attr("data-id") + ",";
		 	}
		  });
		  
		return str==""?"":str.substr(0,str.length-1) ;
	}
 
	var msg ;
	var pic_big  =  $("#edit ._pic_big .add_img").attr("data-url") || "";
	var name  =  $("#edit ._name input").val() || "";
	var intro  = editorRichTextArea.getData() || "" ;
	var tags_id = getSelectedIds("_tag") || "";
	var type_id = $('.r-channel input:radio:checked').val() || "";
	var brief = $("#edit ._brief  textarea").val() || "";
	if(!draft){
		if(!pic_big || pic_big == ""){
			return "请上封面图";
		}else if(!name || name == ""){
			return "请填写标题";
		}else if(!tags_id || tags_id == "" ){
			return "请至少选择一个标签";
		}
	}else if(
		name == "" && 
		pic_big == "" && 
		intro == "" && 
		brief == "" && 
		tags_id == ""  
	){
		return false;
	}

	
	if($(".modal-title").attr( "data-req")=="modify"){
		msg = { 
			id: $(".modal-title").attr( "data-id"),
			recommend: editDefaultVal.recommend ,
			pic_big: pic_big,
			name: name,
			intro: intro,
			tag: tags_id, 
			oldname: editDefaultVal.name,
			type_id:type_id,
			brief : brief
		}
			
	}else{
		msg = {
			pic_big: pic_big,
			name: name,
			intro: intro,
			recommend: 0,
			tag: tags_id,
			type_id:type_id,
			brief : brief
		}
 		editDefaultVal.status = 1;
 	};
	editDefaultVal.recommend = msg.recommend;
	editDefaultVal.pic_big = pic_big;
	editDefaultVal.name = name;
	editDefaultVal.tag = tags_id;
	editDefaultVal.intro = intro;
	editDefaultVal.type_id = type_id ;
	editDefaultVal.brief = brief ;
	return msg ;
}

/*
* 添加产品的魔板
* 通过后台接口调用到所哟的
* 所有的IP、厂商、规格、和标签 
*/

/*产品相关参数*/
var Product_Parm_ArreaList = {
	about:{"tag":" * 标签"}
};

var addProductModeHtml;
var editorRichTextArea;
var editDefaultVal;

function initEditDefaultVal(){
	editDefaultVal ={
		modal:{
			id:"",
			title:"添加",
			req:"add"
		},
		recommend:0,
		like_count:0,
 		pic_big : "/images/admin/iconfont-shangchuantupian.png",
		name : "",
		brief : "",
		tag : [],
		status :1,
		type_id : '' ,
		intro : "",
		
	}
};
/*根据id查询一条数据*/
var getProduct_Parm_ArreaList = function(ele,id){
	var p = Product_Parm_ArreaList[ele]
	for(var i in p){
		switch(ele){
			//case 'norms' :
			//	for(var k in p[i]['norms_value'] ){
			//		if( p[i]['norms_value'][k].id  == id ){return p[i]['norms_value'][k] ;}
			//	}
			//break;
			default:
				if(p[i].id == id ){return p[i] ;}
			break;
		}
	}
	
};
function addProductMode(){
 	$('.btn_edit_submit').removeClass("lock");
	$('.btn_edit_submit').text("确认提交");
 	if(!addProductModeHtml || !Product_Parm_ArreaList.status ){
		Product_Parm_ArreaList.tag = getContactAction.data;
		Product_Parm_ArreaList.status = 1;
	 	ProductMode();
	}else{
		ProductMode() ;
	}
};


function ProductMode(){
 	modalView('show' ,true);
 	if(!addProductModeHtml)
	{
		addProductModeHtml = '';
		addProductModeHtml += '<div  id= "edit"  style="max-width: 700px; margin: 0 auto;border-left: 1px solid #ddd; " class="table-con">' ;
		addProductModeHtml += '<table class ="prodict_edit"> <thead> <tr> <th colspan = "2" > 爆料基本信息</th></tr></thead>' ;
		addProductModeHtml += '<tr><td  style="width:100px;" >*封面图</td><td  class = "_pic_big">' ;
		addProductModeHtml += '</td></tr>';
 		addProductModeHtml += '<tr><td>*标题</td><td class = "_name">  </td></tr>' ;
		addProductModeHtml += '<tr><td>摘要</td><td class = "_brief"> </td></tr>' ;
		addProductModeHtml += '<tr><td>*详情:</td><td class = "_intro"><textarea  name="RichTextArea"></textarea></td></tr>' ;
 		addProductModeHtml += '<tbody class = "_others" ></tbody> ' ;
 		var Rchannel = ['不推荐','首页-牛社快讯','首页-焦点图','爆料详情页-推荐',] ;
 		addProductModeHtml += '<tr><th colspan = "2"><p style = "line-height:30px;"> <span id = "msg_error" style = "font-size:10px;" class = "warning" ></span> </p><div class="r-channel"><h3>推荐频道</h3>';
 		for (var i=0 ;i<4;i++){var checked='';
 			if(editDefaultVal.type_id && i== editDefaultVal.type_id ){checked = 'checked'}
 			addProductModeHtml += '<label data-num='+i+' class="radio-inline"><input type="radio" '+checked+' name="inlineRadioOptions" id="inlineRadio1" value='+i+'>'+Rchannel[i]+'</label>';
 		}
 		addProductModeHtml += '</div><button class="btn_edit_submit btn btn-success">确认提交</button></th></tr></tbody></table>';
 		addProductModeHtml += '</div>';
 		 $(".modal-body").html( addProductModeHtml);
 		editorRichTextArea = CKEDITOR.replace('RichTextArea', {/*toolbar : 'MyToolbar'*/});
	}
	 $(".modal-title").html( editDefaultVal.modal.title );
	 $(".modal-title").attr( "data-req" , editDefaultVal.modal.req );
	 $(".modal-title").attr( "data-id" , editDefaultVal.modal.id );
   //编辑框赋值
	var currenthtml = '' ;
	currenthtml += '<form style = "width:150px ;position:relative;"  action="'+upimgAction+'" id="form_pic_big" name="form_pic_big" encType="multipart/form-data"  method="post" target="hidden_frame"  >' ;
	
	if( editDefaultVal.pic_big == '/images/admin/iconfont-shangchuantupian.png'){
		currenthtml += '<img class = "add_img"   data-url=""  src = "/images/admin/iconfont-shangchuantupian.png"> ';
		currenthtml += ' <input type="file" data-url = "" class="imgfile"  name="uploadFile" style="opacity: 0; position: absolute; width: 100%;height:100%;top:0;left:0;" />';
	}else{
		currenthtml += '<img class = "add_img"   data-url="'+editDefaultVal.pic_big+'"  src = "'+imageDomain+editDefaultVal.pic_big+'"> ';
		currenthtml += ' <input type="file" data-url = "'+editDefaultVal.pic_big+'" class="imgfile"  name="uploadFile" style="opacity: 0; position: absolute; width: 100%;height:100%;top:0;left:0;" />';
	
	}
	currenthtml +=  '<iframe name="hidden_frame" id="hidden_frame" style="display: none;"></iframe>';
	currenthtml += ' </form> ';
	$('#edit ._pic_big ').html(currenthtml);
	$('#edit ._name').html('<input value = "'+editDefaultVal.name+'" class="form-control" placeholder="添加标题">');
	$('#edit ._brief').html('<input value = "'+editDefaultVal.brief+'"  class="form-control" placeholder="添加摘要">  ');
	/*富文本 赋值*/  
	$('#edit ._intro textarea').val(editDefaultVal.intro);
	editorRichTextArea.setData(editDefaultVal.intro);
	/**
	  *厂商 ip 规格 赋值
	  */
	currenthtml ='';
	var _seachEN = ['<li class = "current" >全部</li><li>AB</li><li>CD</li><li>EF</li><li>GH</li><li>IJ</li><li>KL</li><li>MN</li><li>OP</li><li>QR</li><li>ST</li><li>UV</li><li>WX</li><li>YZ</li>'];
	//判断是否已经选择过了
	var  isIncude = function (i,j){
		for(var k in  editDefaultVal[i])
			{
				if(Product_Parm_ArreaListI[j].id == editDefaultVal[i][k]) { return true ;  }
			 }
	}
	for(var  i in Product_Parm_ArreaList["about"]){
		currenthtml += '<tr><td > '+Product_Parm_ArreaList["about"][i]+' :</td><td class = "_'+i+'" id = "'+i+'">' ;
		currenthtml += '<div><ul class = "selected_list" data-parent= "'+i+'">' ;
		currenthtml += '<i class ="mymode"> <i class = "glyphicon glyphicon-folder-open" ></i>&nbsp;选择</i>' ;
		
		
		for(var k in  editDefaultVal[i]){
			var _thisArr = getProduct_Parm_ArreaList(i,editDefaultVal[i][k]);
  			 	if(_thisArr){
			 		var _tagsname = _thisArr.name    ;
 			 	 	if(_tagsname !== "")
				 	{
				 		currenthtml += "<li   data-status='"+_thisArr.status+"'  data-id = '"+_thisArr.id+"'>"+_tagsname+"</li>"
				 	}
			 	}
 		};
		currenthtml += '</ul></div><div  class = "dynamicDiv"><ul class ="EN_serch" data-parent= "'+i+'">' ;
		var Product_Parm_ArreaListI = Product_Parm_ArreaList[i];
		if(i=="tag" ){
			currenthtml += _seachEN[0]  ;
		};
		currenthtml += '<p style ="clear: both;"></p></ul>' ;
		currenthtml += '<ul class = "seach_list" this-in = 0 data-parent= "'+i+'">';
		for(var j in Product_Parm_ArreaListI){
			var value  =  Product_Parm_ArreaListI[j].name;
			if( !isIncude(i,j)){
				currenthtml +='<li data-id="'+ Product_Parm_ArreaListI[j].id +'" >'+ value +'</li>';
			}
		};
		currenthtml += '</ul><p style= "border-top:1px solid #eee;background:#fafafa; padding:3px;color:#aaa;"><s>&nbsp;加删除线表示此项已禁用&nbsp; </s></p></div></td></tr>';	
	};
	$('#edit ._others').html( currenthtml );
}
 	



	
	