 /*初始化*/
 var upimgAction, addAction , modifyAction , modifyStatusAction ,seachByStatusAction , seachByNameAction ,getContactAction ,modifyInitial,imageDomain;
$(function (){
	init() ;
})	

function init(){
	/*初始化模态框内的值*/
	initEditDefaultVal() ;
	/*按照数据状态（是否已经开启）检索 */
	$(".seachByStatus").on("change",function(){
		 /*页面跳转  $(this).val() */
		location.href = seachByStatusAction +"/"+ $(this).val()  ;
	});
     /*修改一条数据*/
	$(document).on("click",".modify",function(){
		Loading() ;
		var $_closest = $(this).closest("tr"),
		 	thisPic =$_closest.find("._pic").find("img").attr("src") ,
			_dataId = $_closest.attr("data-id");
			current_add_data_msg_status = $_closest.find(".on-off").attr("class") ;
   			ajax_request(this, modifyInitial , "post",{id:_dataId} ,function (_this,msg){
   				
  				if(msg.status && msg.status==1){
  					var OtherMsg = msg.data;
 					editDefaultVal =
					{
						modal:{id:_dataId ,title:"修改" , req:"modify"},
						pic_addimg_src : thisPic ,
						pic_addimg_dataurl : thisPic.replace(imageDomain,"") ,
						picbig_addimg_src : imageDomain+OtherMsg.pic_big ,
						picbig_addimg_dataurl : OtherMsg.pic_big  ,
						name : $_closest.find("._name").text() ,
						year :  $_closest.find("._year").text()  ,
						cartoon : [{
							"name":$_closest.find("._cartoon").text(),
							id :$_closest.find("._cartoon").attr('data-id') ,
							"status" :$_closest.find("._cartoon").attr('data-status')
						}]  ,
						firm : [{
							name:$_closest.find("._firm").text() ,
							id:$_closest.find("._firm").attr('data-id') ,
							status :$_closest.find("._firm").attr('data-status')
						}],
						tag : OtherMsg.tag  ,
 						norms :OtherMsg.norms ,
 						status :$_closest.find(".on-off").attr('data-status') ,
 						intro : OtherMsg.intro 
					}
 					addProductMode();
				}else{
					return GhostMsg();
				}
				Loading("hide") ;
			}) ;
 	});
 	/*克隆一条数据*/
	$(document).on("click",".copy",function(){
		Loading() ;
		var $_closest = $(this).closest("tr"),
		 	thisPic =$_closest.find("._pic").find("img").attr("src") ,
			_dataId = $_closest.attr("data-id");
			current_add_data_msg_status = $_closest.find(".on-off").attr("class") ;
   			ajax_request(this, modifyInitial , "post",{id:_dataId} ,function (_this,msg){
   			 
 				if(msg.status && msg.status==1){
  					var OtherMsg = msg.data;
 					editDefaultVal =
					{
						modal:{id:_dataId ,title:"克隆" , req:"add"},
						pic_addimg_src : thisPic ,
						pic_addimg_dataurl : thisPic.replace(imageDomain,"") ,
						picbig_addimg_src : imageDomain+OtherMsg.pic_big ,
						picbig_addimg_dataurl : OtherMsg.pic_big  ,
						name :"" ,
						year :  $_closest.find("._year").text()  ,
						cartoon : [{
							"name":$_closest.find("._cartoon").text(),
							id :$_closest.find("._cartoon").attr('data-id') ,
							"status" : 1 
						}]  ,
						firm : [{
							name:$_closest.find("._firm").text() ,
							id:$_closest.find("._firm").attr('data-id') ,
							status : 1 
						}],
						tag : OtherMsg.tag  ,
 						norms :OtherMsg.norms ,
 						status :$_closest.find(".on-off").attr('data-status') ,
 						intro : OtherMsg.intro 
					}
 					addProductMode();
				}else{
					 GhostMsg("请重试");
				}
				Loading("hide") ;
			}) ;
			
	});
	
 	/*添加一条数据*/
	$(".add-ico").bind("click",function(){
		initEditDefaultVal () ;
		 addProductMode();
		 /*//清空*/
		// edits_Empty();
 	});
	

	/*更改状态*/
	$(document).on("click",".on-off",function(){
	
		Loading() ;
		 var _dataId = $(this).closest("tr").attr("data-id");
		 var _status = 0 ;
		if($(this).text() == "关闭"){
 			_status = 0 ; 
		}else{
 			_status = 1 ; 
		}
		/*请求数据*/
		current_add_data_msg = {status:_status}
		ajax_request(this, modifyStatusAction , "post",{id:_dataId,status:_status} ,modifyStatus_success) ;
 	});
	/*上传图片*/
	$(document).on("click","#edit .imgfile",function(){
  	
	  		if($(this).closest("td").hasClass("_pic")){
	  			current_add_img = "_pic";
	  		}else{
	  			current_add_img = "_pic_big";
	  		}
  			$(this).closest("form").submit();
	  	});
	/*确认提交*/
	$(document).on("click",".btn_edit_submit",function(){
	
 		var _Msg = checkInputMsg()  ;
 		
		if(typeof _Msg=="object" && !$(this).hasClass("lock")){
			$(this).addClass("lock");
			$(this).text("提交中...");
			$("#msg_error").html('');
			
			if($(".modal-title").attr( "data-req")=="modify"){
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
	$(document).on("click",".btn_edit_empty",function(){
		edits_Empty();
	});
  	
	/*选择*/
	$(document).on("click",".mymode",function()
	
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
	$(document).on("click","#norms .EN_serch li",function(){
	
		
		$(this).siblings("li").removeClass("current").end().addClass("current");
		var _norms = Product_Parm_ArreaList.norms[ $(this).attr('this-in') ]['norms_value'] ;
		var _html = ''
		//判断是否已经选择过了
		 isIncude = function (j){
			for(var k in  editDefaultVal.norms)
				{
					if(_norms[j].id == editDefaultVal.norms[k].id )
					{
						return true ; 
					}
				 }
		}
		for(j in _norms){
			if( !isIncude(j)){
				_html +='<li class ="status_color_'+_norms[j].status+'" data-id="'+ _norms[j].id +'" data-status ="'+_norms[j].status+'" >'+ _norms[j].value +'</li>';
			}
		}
		$(this).closest('td').find('.seach_list').attr('this-in',$(this).attr('this-in')) ;
		 $(this).closest('td').find('.seach_list').html(_html);
	});
	/*选择*/
	$(document).on("click",".seach_list li",function(){
	
			if($(this).hasClass('status_color_0')){
				return GhostMsg("此项已禁用");
			}
		
			_cur = $(this).parent().attr("data-parent");
			if( _cur == "cartoon" || _cur == "firm" ){
				$(this).parent().append($('#'+_cur+' .selected_list li').clone());
				$('#'+_cur+' .selected_list li').remove();
			}
			
 			$('#'+_cur+' .selected_list').append($(this).clone());
  			$(this).remove();
  			if(_cur == "norms")
  			{
  				editDefaultVal.norms.push({
  					id:$(this).attr('data-id'),
  					status:$(this).attr('data-status'),
  					value:$(this).text()})
  			}
  	});
	/*删除已选中*/
	$(document).on("click",".selected_list li",function(){
		_cur = $(this).parent().attr("data-parent");
		
		var _id = $(this).attr('data-id') ;
		
		/*判断所属类别*/
		isNormsByType = function ()
		{
			for(var k in Product_Parm_ArreaList[_cur])
			{
				for(var n in Product_Parm_ArreaList[_cur][k].norms_value)
				{
					if(_id == Product_Parm_ArreaList[_cur][k].norms_value[n].id ){
						return k ;
					}
					
				}
			}
			return 0 ;
		}
  		if(!$(this).hasClass("mymode"))
		{
			if( $('#'+_cur+' .seach_list').attr('this-in') == isNormsByType() )
			{
				$('#'+_cur+' .seach_list').append($(this).clone());
			}
			delateEditDefaultVal(_cur , _id) ;
			//editDefaultVal.norms.
 			$(this).remove();
		}
	});
	
}
//删除数组中的某一个元素
 function delateEditDefaultVal  (cur , _id){
	for(var i in editDefaultVal[cur] ){
		if(editDefaultVal[cur][i].id == _id){
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
var current_add_data_msg ,current_add_data_msg_picPath ,
	current_add_data_msg_status,current_add_data_msg_picBigPath,
	current_add_img;
var add_success = function (_this,msg)
{
	
	
	if( msg &&  msg.status && msg.status == 1  &&  msg.data.id )
	{
		/*添加成功后在列表中增一条数据 ;*/
 		modalView('hide');
		var _html = '<tr id = "data_'+  msg.data.id +'"　 data-id = "'+  msg.data.id +'" >' ;
			_html += '<td class= "_id" >'+ msg.data.id +'</td>' ;
			_html += '<td class= "_pic" ><img data-url="'+current_add_data_msg.pic+'" src="'+imageDomain+current_add_data_msg.pic+'" class = "min_img" ></td>' ;
			_html += '<td class= "_name" >'+current_add_data_msg.name+'</td>' ;
			_html += ' <td class= "_year">'+current_add_data_msg.year+'</td>' ;
			_html += '<td class= "_firm" data-id='+current_add_data_msg.firm_id+' >'+current_add_data_msg.firm+'</td> ' ;
			_html += '<td class= "_cartoon" data-id='+current_add_data_msg.cartoon_id+' >'+current_add_data_msg.cartoon+'</td> ' ;
			_html += '<td >0</td><td  >0</td><td>刚刚</td>' ;
			_html += '<td ><button type="button" class = "copy btn btn-success"  >克隆</button>  <button type="button" class = "modify btn btn-info" >修改</button>  <button type="button" class = "on-off btn btn-danger" >关闭</button></td> ' ;
			_html += '</tr> ' ;
		$("#dataListTable").prepend(_html) ;
	}else{
		  GhostMsg("error:"+ msg.info);
	}
	
	$(_this).removeClass("lock");
	$(_this).text("确认提交");
 }


/*修改 数据 成功 后 回调函数*/
var modify_success = function (_this,msg)
{
	
  	if(msg &&  msg.status && msg.status == 1  )
	{
		modalView('hide');
		var _html = '' ;
 			_html += '<td class= "_id" >'+ msg.data.id +'</td>' ;
			_html += '<td class= "_pic" ><img data-url="'+current_add_data_msg.pic+'" src="'+imageDomain+current_add_data_msg.pic+'" class = "min_img" ></td>' ;
			_html += '<td class= "_name" >'+current_add_data_msg.name+'</td>' ;
			_html += ' <td class= "_year">'+current_add_data_msg.year+'</td>' ;
			_html += '<td class= "_firm" data-id='+current_add_data_msg.firm_id+' >'+current_add_data_msg.firm+'</td> ' ;
			_html += '<td class= "_cartoon" data-id='+current_add_data_msg.cartoon_id+' >'+current_add_data_msg.cartoon+'</td> ' ;
			_html += '<td >0</td><td  >0</td><td>刚刚</td>' ;
			_html += '<td ><button type="button" class = "copy btn btn-success"  >克隆</button>  <button type="button" class = "modify btn btn-info" >修改</button>  <button type="button" data-status = "'+editDefaultVal.status+'" class = "on-off btn' ;
			if(editDefaultVal.status == 0 ){
				_html += ' btn-warning" >开启' ;
			}else{
				_html += ' btn-danger" >关闭' ;
			}
			_html += '</button></td> ' ;
		$("#dataListTable #data_"+current_add_data_msg.id ).html( _html ) ;
	}else{
		 GhostMsg("error:"+  msg.info);
	}
	
	$(_this).removeClass("lock");
	$(_this).text("确认提交");
}

/*更改状态 数据 成功 后 回调函数*/
var modifyStatus_success =  function(_this,msg)
{
	if(msg &&  msg.status && msg.status == 1 )
	{
		
		if($(_this).text() == "关闭"){
			$(_this).text("开启");
			$(_this).removeClass("btn-danger");
			$(_this).addClass("btn-warning");
		}else if ($(_this).text() == "开启") {
			$(_this).text("关闭");
			$(_this).removeClass("btn-warning");
			$(_this).addClass("btn-danger");
		}
		GhostMsg(msg.info);
	}else{
		 return GhostMsg("error:"+ msg.info);
	}
	Loading("hide") ;
 }

/*上传图片完成回调函数*/
function upImgCallback(msg)
{   
 	$("#edit ."+current_add_img+" .add_img").attr("data-url",msg.dbPath);
    $("#edit ."+current_add_img+" .add_img").attr("src",imageDomain + msg.dbPath);
    if(current_add_img == "_pic"){
    	current_add_data_msg_picPath = "http://"+msg.imageUrl ;
    }else{
    	current_add_data_msg_picBigPath = "http://"+msg.imageUrl ;
    }
} 

/*校验提交数据是否合格*/
function checkInputMsg()
{
		var getSelectedIds = function (sel){
			var str="";
			 $("."+sel+"  .selected_list li").each(function(){
			 	if(!$(this).hasClass("mymode")){
			 		str+=$(this).attr("data-id") + ",";
			 	}
			  });
			  
			return str==""?"":str.substr(0,str.length-1) ;
		}
		 
		var msg ;
		var pic  =  $("#edit ._pic .add_img").attr("data-url");
		var pic_big  =  $("#edit ._pic_big .add_img").attr("data-url");
		var name  =  $("#edit ._name input").val();
		var year = $("#edit ._year input").val() || 2015;
  		var Numreg = /^[0-9]*$/ ; /*是否数字*/
 		var cartoon_id  =  getSelectedIds("_cartoon") ;
		var firm_id  =   getSelectedIds("_firm") ;
		var intro  = editorRichTextArea.getData();
  		var norms_id = getSelectedIds("_norms") ;
		var tags_id = getSelectedIds("_tag") ;
		if(!pic || pic == ""){
			return "请上传图片" ;
		}else if(!pic_big || pic_big == ""){
			return "请上传附图" ;
		}else if(!name || name == ""){
			return "请填写标题" ;
		}else if(!year || year == "" || !Numreg.test(year) || year < 1000 || year > 2015 ){
			return "年代输入有误" ;
		}else if(!cartoon_id || cartoon_id == ""   ){
			return "请选择一个IP" ;
		}else if(!firm_id || firm_id == ""  ){
			return "请选择一个厂商" ;
		}else if(!tags_id || tags_id == ""   ){
			return "请至少选择一个标签" ;
		}else if(!norms_id || norms_id == ""  ){
			return "请至少选择一个规格" ;
		}
 		
		
		if($(".modal-title").attr( "data-req")=="modify"){
 			msg ={ id: $(".modal-title").attr( "data-id") ,
 			pic:pic,pic_big:pic_big,
 			name:name,intro:intro,
 			year:year,
 			cartoon_id:cartoon_id,
 			firm_id:firm_id,
 			tag:tags_id,norms:norms_id , 
 			oldname:editDefaultVal.name
 			}
		}else{
			msg = {pic:pic,pic_big:pic_big,name:name,intro:intro,year:year,
				cartoon_id:cartoon_id,
 				firm_id:firm_id,
				tag:tags_id,norms:norms_id}
		}
		current_add_data_msg = { id: $(".modal-title").attr( "data-id") ,
 			pic:pic,pic_big:pic_big,
 			name:name,intro:intro,
 			year:year,
 			cartoon_id:cartoon_id,cartoon:$("._cartoon .selected_list li").text(),
 			firm_id:firm_id,firm:$("._firm .selected_list li").text(),
 			tag:tags_id,norms:norms_id , 
 			}
		return msg ;
}
  /*
 * 添加产品的魔板
 * 通过后台接口调用到所哟的
 * 所有的IP、厂商、规格、和标签
 * 
 * */
 

/*产品相关参数*/
var Product_Parm_ArreaList = {
	about:{"cartoon":"*IP（单选）" ,"firm":"*厂商（单选）" ,"tag":"*标签（多选）" ,"norms":"*规格（多选）" }
}
  
var  addProductModeHtml  ;
var editorRichTextArea ;
var  editDefaultVal ;
function initEditDefaultVal(){
	editDefaultVal =
	{
		modal:{id:"",title:"添加" , req:"add"},
		pic_addimg_src : "/images/admin/iconfont-shangchuantupian.png" ,
		pic_addimg_dataurl : "" ,
		picbig_addimg_src : "/images/admin/iconfont-shangchuantupian.png" ,
		picbig_addimg_dataurl : "" ,
		name : "" ,
		year : "" ,
 		cartoon : [] ,
		firm : [] ,
 		tag : [] ,
		norms : [] ,
		status :1 ,
		intro : ""
	}
}



 function addProductMode(){
 	$('.btn_edit_submit').removeClass("lock");
	$('.btn_edit_submit').text("确认提交");
 	if(!addProductModeHtml || !Product_Parm_ArreaList.status ){
		ajax_request(this, getContactAction , "get", {}, function (_this,msg){
			if(msg.status && msg.status == 1){
				
				Product_Parm_ArreaList.firm = msg.data.firm ;
				Product_Parm_ArreaList.cartoon = msg.data.cartoon ;
				Product_Parm_ArreaList.norms = msg.data.norms ;
				Product_Parm_ArreaList.tag = msg.data.tag ;
				Product_Parm_ArreaList.status = 1 ;
				
				ProductMode() ;
			}else{
				GhostMsg("产品相关参数不全,无法操作<br><br>请检查标签、厂商、IP、规格内是否有数据");
			}
		}) ;	
	}else{
		ProductMode() ;
	}
}

function ProductMode(){
	modalView('show' ,true);
	addProductModeHtml = ''
		addProductModeHtml += '<div  id= "edit"  style="max-width: 700px; margin: 0 auto;border-left: 1px solid #ddd; " class="table-con">' ;
		addProductModeHtml += '<table class ="prodict_edit"> <thead> <tr> <th colspan = "2" > 产品 基本信息</th></tr></thead>' ;
		addProductModeHtml += '<tbody > ' ;
		addProductModeHtml += '<tr><td style = "width:150px ;" >*主图</td><td  class = "_pic">';
			addProductModeHtml += '<form style = "width:150px ;position:relative;" action="'+upimgAction+'" id="form_pic" name="form_pic" encType="multipart/form-data"  method="post" target="hidden_frame"  >' ;
			addProductModeHtml += '<img class = "add_img" data-url="'+editDefaultVal.pic_addimg_dataurl+'" src = "'+editDefaultVal.pic_addimg_src+'"> ';
			addProductModeHtml += ' <input type="file" data-url = "'+editDefaultVal.pic_addimg_dataurl+'" class="imgfile" name="uploadFile" style="opacity: 0; position: absolute; width: 100%;height:100%;top:0;left:0;" />';
			addProductModeHtml +=  '<iframe name="hidden_frame" id="hidden_frame" style="display: none;"></iframe>';
			addProductModeHtml += ' </form> ';
		addProductModeHtml += '</td></tr>' ;
		addProductModeHtml += '<tr ><td >*附图</td><td  class = "_pic_big">' ;
			addProductModeHtml += '<form style = "width:150px ;position:relative;"  action="'+upimgAction+'" id="form_pic_big" name="form_pic_big" encType="multipart/form-data"  method="post" target="hidden_frame"  >' ;
			addProductModeHtml += '<img class = "add_img"   data-url="'+editDefaultVal.picbig_addimg_dataurl+'"  src = "'+editDefaultVal.picbig_addimg_src+'"> ';
			addProductModeHtml += ' <input type="file" data-url = "'+editDefaultVal.picbig_addimg_dataurl+'" class="imgfile"  name="uploadFile" style="opacity: 0; position: absolute; width: 100%;height:100%;top:0;left:0;" />';
			addProductModeHtml +=  '<iframe name="hidden_frame" id="hidden_frame" style="display: none;"></iframe>';
			addProductModeHtml += ' </form> ';
		addProductModeHtml += '</td></tr>';
 		addProductModeHtml += '<tr><td >*标题</td><td class = "_name"> <input value = "'+editDefaultVal.name+'" class="form-control" placeholder="添加标题"> </td></tr>' ;
		addProductModeHtml += '<tr><td  >*年代</td><td class = "_year"> <input value = "'+editDefaultVal.year+'"  class="form-control" placeholder="如：2015">  </td></tr>' ;
		addProductModeHtml += '<tr><td> 详情 :</td><td class = "_intro"><textarea  name="RichTextArea">'+editDefaultVal.intro+'</textarea></td></tr>' ;
 	
 		var _seachEN = ['<li class = "current" >全部</li>'];
 		//判断是否已经选择过了
		var  isIncude = function (j){
			for(var k in  editDefaultVal[i])
				{
					if(Product_Parm_ArreaListI[j].id == editDefaultVal[i][k].id )
					{
						return true ; 
					}
				 }
		}
 		
		for(var  i in Product_Parm_ArreaList["about"])
		{
			addProductModeHtml += '<tr><td > '+Product_Parm_ArreaList["about"][i]+' :</td><td class = "_'+i+'" id = "'+i+'">' ;
			addProductModeHtml += '<div><ul class = "selected_list" data-parent= "'+i+'">' ;
			addProductModeHtml += '<i class ="mymode"> <i class = "glyphicon glyphicon-folder-open" ></i>&nbsp;选择</i>' ;
			
			for(var k in  editDefaultVal[i])
			{
			 	var _tagsname = editDefaultVal[i][k].name ;
			 	if(i == "norms")
			 	{
			 		_tagsname = editDefaultVal[i][k].value ;
			 	}
			 	
			 	if(_tagsname !== "")
			 	{
			 		addProductModeHtml += "<li  class = 'status_color_"+editDefaultVal[i][k].status+"' data-status='"+editDefaultVal[i][k].status+"'  data-id = '"+editDefaultVal[i][k].id+"'>"+_tagsname+"</li>"
			 	}
			 }
			
			addProductModeHtml += ' </ul></div><div  class = "dynamicDiv"  ><ul class ="EN_serch"  data-parent= "'+i+'" >' ;
			
			var Product_Parm_ArreaListI = Product_Parm_ArreaList[i] ;
			if(i=="norms")
			{
				for(k in  Product_Parm_ArreaList[i]){
					if(k == 0 )
					{
						addProductModeHtml += '<li class = "current" this-in ='+k+' data-id = '+Product_Parm_ArreaList[i][k].id+' >'+Product_Parm_ArreaList[i][k].name+'</li>' ;
					}else
					{
						addProductModeHtml += '<li  this-in ='+k+' data-id = '+Product_Parm_ArreaList[i][k].id+' >'+Product_Parm_ArreaList[i][k].name+'</li>' ;
					}
				}
				Product_Parm_ArreaListI = Product_Parm_ArreaList[i][0]["norms_value"] ;
			}else
			{
				addProductModeHtml += _seachEN[0]  ;
			}
			
 			addProductModeHtml += '<p style ="clear: both;"></p></ul>' ;
			addProductModeHtml += '<ul  class = "seach_list" this-in = 0   data-parent= "'+i+'">' ;
			for(var j in Product_Parm_ArreaListI)
			{
				var value  ;
				if(i=="norms"){
					value =  Product_Parm_ArreaListI[j].value ;
				}else{
					value =  Product_Parm_ArreaListI[j].name
				}
				
				if( !isIncude(j)){
					addProductModeHtml +='<li class="status_color_'+Product_Parm_ArreaListI[j].status+'" data-id="'+ Product_Parm_ArreaListI[j].id +'" >'+ value +'</li>';
				}
			}
			addProductModeHtml += '</ul><p style= "border-top:1px solid #eee;background:#fafafa; padding:3px;color:#aaa;"><s>&nbsp;加删除线 表示此项已禁用&nbsp; </s></p></div></td></tr>' ;
			
		}
		addProductModeHtml += '<tr><th colspan = "2" ><p style = "line-height:30px;" > <span id = "msg_error" style = "font-size:10px;" class = "warning" ></span> </p><button class="btn_edit_submit btn btn-success" >确认提交  </button></th></tr></tbody></table>' ;
		addProductModeHtml += '</div>' ;

		$(".modal-body").html( addProductModeHtml);
		
		 $(".modal-title").html( editDefaultVal.modal.title );
		 $(".modal-title").attr( "data-req" , editDefaultVal.modal.req );
		 $(".modal-title").attr( "data-id" , editDefaultVal.modal.id );
		 
	   /*富文本 赋值*/
	   editorRichTextArea = CKEDITOR.replace('RichTextArea', {/*toolbar : 'MyToolbar'*/});
}	
 


	
	