 /*初始化*/
var upimgAction, addAction , modifyAction , modifyStatusAction ,seachByStatusAction , seachByNameAction, 
	modifyBtn, openBtn, closeBtn, recommend_input;

$(function(){init();});

var init = function()
{
	initEditDefaultVal() ;
	 
	/*上传图片*/
  	$("#edit .imgfile").live("change",function (){
  		if(  $(this).val() !== ""){
  			$("#form1").submit();
  		}
  	});
	/*修改推荐权重值*/
	baseInit.byModifyStatusAction('recommend',modifyStatusAction);
	
 	/*按照数据状态（是否已经开启）检索 */
 	baseInit.seachByStatus(seachByStatusAction) ;
	
 	/*修改一条数据*/
	$(".modify").live("click",function(){
		 
			modifyByThis(this);
	});
	
	function modifyByThis(_this){
 		 var $_closest = $(_this).closest("tr"),
			_dataId = $_closest.attr("data-id");
			editDefaultVal.picPath = $_closest.find("._pic").find("img").attr("src") ;
 			editDefaultVal.status= $_closest.find(".on-off").attr("data-status") ;
			editDefaultVal.name=$_closest.find("._name").text() ;
			editDefaultVal.first_word = $_closest.find("._first_word").text() ;
			editDefaultVal.intro = $_closest.find("._intro").text() ;
			editDefaultVal.pic =  $_closest.find("._pic").find("img").attr("data-url")  ;
			editDefaultVal.recommend = parseInt($_closest.find(".recommend").val() || $_closest.find("._recommend").text()) ;
			if( $(_this).hasClass('modify') ){
				AddFirmMode();
				 $("#edit .add_img").attr("src" , editDefaultVal.picPath)  ;
	 			 $("#edit .add_img").attr("data-url" ,editDefaultVal.pic );
				 $("#edit #edit_first_word").val( editDefaultVal.first_word )  ;
				 $("#edit #edit_name").val( editDefaultVal.name );
				 $("#edit #edit_intro").html( editDefaultVal.intro ) ;
				 $(".modal-title").html( "修改");
				 $(".modal-title").attr( "data-req" , "modify");
				 $(".modal-title").attr( "data-id" , _dataId);
			}else if( 	$(_this).hasClass('copy') ){
 			}else if(	$(_this).hasClass('recommend')  ){
				ajax_request(this, modifyAction , "post", {
		 			id: _dataId,
		 			pic:$_closest.find("._pic").find("img").attr("data-url") ,
		 			first_word:$_closest.find("._first_word").text(),
		 			name:editDefaultVal.name,
		 			intro:editDefaultVal.intro,
		 			oldname:editDefaultVal.name,
		 			recommend : editDefaultVal.recommend
				} , function(_this,msg){
 					GhostMsg("已推荐");
				}) ;
			}
			
	}
	/*添加一条数据*/
	$(".add-ico").bind("click",function(){
		initEditDefaultVal();
		 AddFirmMode();
		 $(".modal-title").html( "添加" );
		 $(".modal-title").attr( "data-req" , "add");
		 /*//清空*/
		 edits_Empty();
		 
		 
	});
	/*更改状态*/
	$(".on-off").live("click",function(){
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
	
	/*确认提交*/
	$(".btn_edit_submit").live("click",function (){
 		var _Msg = checkInputMsg()  ;
		if(typeof _Msg=="object"   && !$(this).hasClass("lock")){
			$(this).addClass("lock");
			$(this).text("提交中...");
			$("#msg_error").html('');
			if($(".modal-title").attr( "data-req")=="add"){
				current_add_data_msg = _Msg ;
				/*请求数据*/
				ajax_request(this, addAction , "post", _Msg, add_success) ;	
  				
			}else if($(".modal-title").attr( "data-req")=="modify"){
				current_add_data_msg = _Msg ;
				ajax_request(this, modifyAction , "post", _Msg , modify_success) ;
 				
			}else{
				GhostMsg('error:请刷新重试') ;
			}
				
		}else{
			$("#msg_error").html(_Msg);
		}
	});
	/*清空编辑框中的数据*/
	$(".btn_edit_empty").live("click",function (){
		edits_Empty();
	});
	
}

/*清空编辑框中的数据*/
var edits_Empty = function (){
	$("#edit .add_img").attr("src" ,"/images/admin/iconfont-shangchuantupian.png")  ;
	$("#edit .add_img").attr("data-url" ,"")  ;
	$("#edit #edit_first_word").val( "")  ;
	$("#edit #edit_name").val( "" );
	$("#edit #edit_intro").val("") ;
}

/*添加 数据 成功 后 回调函数*/
var current_add_data_msg  ;
var add_success = function (_this,msg)
{
 	if(msg.status && msg.status == 1  &&  msg.data.id )
	{
		/*添加成功后在列表中增一条数据 ;*/
		modalView('hide');
		var msg_id =  20823;
		var _html = '<tr id = "data_'+  msg.data.id +'"　 data-id = "'+  msg.data.id +'" >' ;
			_html += '<td class= "_id" >'+  msg.data.id +'</td>' ;
			_html += '<td class= "_pic" ><img data-url="'+current_add_data_msg.pic+'" src="'+editDefaultVal.picPath+'" class = "min_img" ></td>' ;
			_html += '<td class= "_name" >'+current_add_data_msg.name+'</td>' ;
			_html += '<td class= "_intro" >'+current_add_data_msg.intro+'</td> ' ;
			_html += '<td class= "_first_word" >'+current_add_data_msg.first_word+'</td> ' ;
			_html += '<td  class= "_recommend"> ' + recommend_input.replace('#num#', 0) + '</td>'
			_html += ' <td>' + modifyBtn + '  ' + closeBtn + '</td> ' ;
			_html += '</tr> ' ;
		$("#dataListTable").prepend(_html) ;
	}else{
		   GhostMsg(msg.info);
	}
	$(_this).removeClass("lock");
	$(_this).text("确认提交");
	
}

/*修改 数据 成功 后 回调函数*/
var modify_success = function (_this,msg)
{
	if( msg.status && msg.status == 1  )
	{
		modalView('hide');
		var _html = '' ;
 		_html += '<td class= "_id" >'+ current_add_data_msg.id +'</td>' ;
		_html += '<td class= "_pic" ><img data-url="'+current_add_data_msg.pic+'" src="'+editDefaultVal.picPath+'" class = "min_img" ></td>' ;
		_html += '<td class= "_name" >'+current_add_data_msg.name+'</td>' ;
		_html += '<td class= "_intro" >'+current_add_data_msg.intro+'</td> ' ;
		_html += '<td class= "_first_word" >'+current_add_data_msg.first_word+'</td> ' ;
		_html += '<td class= "_recommend"> ' + recommend_input.replace('#num#', editDefaultVal.recommend) + '</td>'
		_html += '<td >' + modifyBtn + '  ' ;
		if(editDefaultVal.status == 0 ){
			_html += openBtn ;
		}else{
			_html += closeBtn ;
		}
		_html += '</td> ' ;
		$("#dataListTable #data_"+current_add_data_msg.id ).html( _html ) ;
	}else{
		  GhostMsg(msg.info);
	}
	$(_this).removeClass("lock");
	$(_this).text("确认提交");
}

/*更改状态 数据 成功 后 回调函数*/
var modifyStatus_success =  function(_this,msg)
{
	Loading("hide") ;
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
		  GhostMsg("error:"+ msg.info);
	}
	
 }

/*上传图片完成回调函数*/
function upImgCallback(msg)
{   
	$(".add_img").attr("data-url",msg.dbPath);
    $(".add_img").attr("src","http://"+msg.imageUrl);
    editDefaultVal.picPath = "http://"+msg.imageUrl ;
} 

var  editDefaultVal ;
function initEditDefaultVal(){
	editDefaultVal =
	{
		modal:{id:"",title:"添加" , req:"add"},
		name : "" ,
		status: 1 ,
		picPath:"",
		intro : "" , 
		recommend : 0,
	}
}
/*校验提交数据是否合格*/
function checkInputMsg()
{
  		var msg ;
		var pic  =  $("#edit .add_img").attr("data-url");
		var first_word  =  $("#edit_first_word").val().toUpperCase()  ;
		var name  =  $("#edit_name").val();
		var intro  = $("#edit_intro").val() || " " ;
		
		var ENreg= /^[A-Za-z]+$/; /*是否字母*/
		if(!pic || pic == ""){
			return "请上传图片" ;
		}else if(!name || name == ""){
			return "请填写名字" ;
		}else if( first_word.length !== 1 ||  first_word == "" || !ENreg.test(first_word) ){
			return "首字母填写有误"  ;
		}
		
		
		
		if($(".modal-title").attr( "data-req")=="modify"){
 			msg ={ id: $(".modal-title").attr( "data-id") ,recommend:editDefaultVal.recommend ,pic:pic,first_word:first_word,name:name,intro:intro,oldname:editDefaultVal.name}  
		}else{
			msg = {pic:pic,first_word:first_word,name:name,intro:intro,recommend:0} 
		}
		return msg ;
}
/*添加 或修改 */
var  AddFirmModeHtml;
function AddFirmMode()
{
	modalView('show');
	$('.btn_edit_submit').removeClass("lock");
	$('.btn_edit_submit').text("确认提交");
	if(!AddFirmModeHtml)
	{
		
		var upimgfile =  '<div  style = "width:150px ;position:relative;"> ';
			upimgfile += '<form action="'+upimgAction+'" id="form1" name="form1" encType="multipart/form-data"  method="post" target="hidden_frame"  >' ;
			upimgfile += '<img class = "add_img" src = " "> ';
			upimgfile += ' <input type="file" data-url = "" class = "imgfile"  id="imgfile" name="uploadFile" style="opacity: 0; position: absolute; width: 100%;height:100%;top:0;left:0;" />';
			upimgfile +=  '<iframe name="hidden_frame" id="hidden_frame" style="display: none;"></iframe>';
			upimgfile += ' </form> ';
			upimgfile += ' </div>';
		AddFirmModeHtml = ''
		AddFirmModeHtml += '<div id = "edit" style="max-width: 700px; margin: 0 auto;border-left: 1px solid #ddd; " class="table-con">' ;
		AddFirmModeHtml += '<table > <thead> <tr> <th colspan = "2" > 基本信息 </th></tr></thead><tbody >' ;
		AddFirmModeHtml += '<tr><td style = "width:150px ;">'+upimgfile+'540*180(px)</td><td> <input id = "edit_name" class="form-control" placeholder="添加名称">  </td></tr>' ;
		AddFirmModeHtml += '<tr><td style = "width:150px ;">* 首字母 </td><td> <input id = "edit_first_word" style="text-transform: uppercase;"  class="form-control" placeholder="首字母"> </td></tr>'
		AddFirmModeHtml += '<tr><td> 简介 </td><td><textarea id = "edit_intro" class="form-control" rows="3"  placeholder="简介" ></textarea></td></tr>' ;
		AddFirmModeHtml += '<tr><td colspan = "2"> <p style = "line-height:30px;" > <span id = "msg_error" style = "font-size:10px;" class = "warning" ></span> </p>';
		AddFirmModeHtml += '<button   class="btn_edit_empty btn btn-default" style = "margin-right: 20px;">  清空  </button><button    class="btn_edit_submit btn btn-success" >确认提交  </button> </th></tr></tbody></table>' ;
		AddFirmModeHtml += '</div>' ;
		 $(".modal-body").html( AddFirmModeHtml );
	}
	return ;
}
