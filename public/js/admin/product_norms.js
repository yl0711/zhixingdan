 
 
/*初始化*/
var upimgAction, addAction , modifyAction , modifyStatusAction ,seachByStatusAction , seachByNameAction , managePramAction,
	modifyBtn, closeBtn, openBtn, managePramBtn, recommend_input;

$(function(){
	initEditDefaultVal() ;
	 
		/*清空编辑框中的数据*/
	$(".btn_edit_empty").live("click",function (){
		edits_Empty();
	});
	
	/*修改推荐权重值*/
	baseInit.byModifyStatusAction('recommend',modifyStatusAction);
	
 	/*按照数据状态（是否已经开启）检索 */
 	baseInit.seachByStatus(seachByStatusAction) ; 
 	
	/*修改一条数据*/
	$(".modify").live("click",function(){
		 
			modifyByThis(this);
	});
	
	function  modifyByThis( _this ){
		
		 var $_closest = $(_this).closest("tr");
			
			 editDefaultVal.id = $_closest.attr("data-id");
			 editDefaultVal.oldname = $_closest.find("._name").text()  ;
			 editDefaultVal.status = $_closest.find(".on-off").attr("data-status") ;
			 editDefaultVal.name = $_closest.find("._name").text() ;
			 editDefaultVal.intro =  $_closest.find("._intro").text()  ;
			 editDefaultVal.recommend = parseInt($_closest.find(".recommend").val() || $_closest.find("._recommend").text()) ; 
			 if( $(_this).hasClass('modify') ){
				 addProductMode() ;
				 $("#edit #edit_name").val( editDefaultVal.name );
				 $("#edit #edit_intro").val(editDefaultVal.intro ) ;
				 $(".modal-title").html( "修改:"+ $_closest.find("._name").text()  );
				 $(".modal-title").attr( "data-req" , "modify");
				 $(".modal-title").attr( "data-id" , editDefaultVal.id);
			}else if( 	$(_this).hasClass('copy') ){
 			}else if(	$(_this).hasClass('recommend')  ){
				ajax_request(this, modifyAction , "post", {
		 			
					id:editDefaultVal.id  ,
					recommend: editDefaultVal.recommend,
					name:editDefaultVal.name,
					oldname:editDefaultVal.oldname,
					intro:editDefaultVal.intro
		
				} , function(_this,msg){
 					GhostMsg("已推荐");
				}) ;
			}
			
		
	}
 
	/*添加一条数据*/
	$(".add-ico").bind("click",function(){
		initEditDefaultVal() ;
		 addProductMode() ;
		 $(".modal-title").html( "添加" );
		 $(".modal-title").attr( "data-req" , "add");
		 /*//清空*/
		 edits_Empty();
		 
	});
	/*更改状态*/
	$(".on-off").live("click",function(){
		Loading() ;
		  editDefaultVal.id = $(this).closest("tr").attr("data-id");
		 var _status = 0 ;
		if($(this).text() == "关闭"){
 			_status = 0 ; 
		}else{
 			_status = 1 ; 
		}
		/*请求数据*/
		editDefaultVal.status =_status
		ajax_request(this, modifyStatusAction , "post",{id:editDefaultVal.id,status:_status} ,modifyStatus_success) ;
	 
		
	});
	
	/*确认提交*/
	$(".btn_edit_submit").live("click",function (){
 		var _Msg = checkInputMsg()  ;
		if(typeof _Msg=="object"   && !$(this).hasClass("lock")){
			$(this).addClass("lock");
			$(this).text("提交中...");
			$("#msg_error").html('');
			if($(".modal-title").attr( "data-req")=="add"){
 				/*请求数据*/
				ajax_request(this, addAction , "post", _Msg, add_success) ;	
  			}else if($(".modal-title").attr( "data-req")=="modify"){
				ajax_request(this, modifyAction , "post", _Msg , modify_success) ;
			}else{
				GhostMsg('error:请刷新重试') ;
			}
				
		}else{
			$("#msg_error").html(_Msg);
		}
	});
	
	
	/*管理参数*
	 */
 	$(".edit_parameter").live("click",function(){
		location.href = managePramAction + "/"+$(this).closest("tr").attr("data-id")+"/1"; // 1 默认显示已经打开的，0关闭，2全部
	});

	
 		
})


/*添加 数据 成功 后 回调函数*/
var current_add_data_msg ,current_add_data_msg_picPath ;
var add_success = function (_this,msg)
{
 	if(msg.status && msg.status == 1  &&  msg.data.id )
	{
		/*添加成功后在列表中增一条数据 ;*/
		modalView('hide');
		var _html = '<tr id = "data_'+  msg.data.id +'"　 data-id = "'+  msg.data.id +'" >' ;
			_html += '<td class= "_id" >'+  msg.data.id +'</td>' ;
			_html += '<td class= "_name" >'+editDefaultVal.name+'</td>' ;
			_html += '<td class= "_intro" >'+editDefaultVal.intro+'</td> ' ;
			_html += '<td class= "_timestamp" > 刚刚 </td> ' ;
			_html += '<td class= "_recommend" > ' + recommend_input.replace('#num#', 0) + '</td>'
			_html += ' <td data-type="norms">' + modifyBtn + '&nbsp;' + managePramBtn + '&nbsp;' + closeBtn + '</td> ' ;
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
			_html += '<td class= "_id" >'+  editDefaultVal.id +'</td>' ;
			_html += '<td class= "_name" >'+editDefaultVal.name+'</td>' ;
			_html += '<td class= "_intro" >'+editDefaultVal.intro+'</td> ' ;
			_html += '<td class= "_timestamp" > 刚刚 </td> ' ;
			_html += '<td class= "_recommend" > ' + recommend_input.replace('#num#', editDefaultVal.recommend) + '</td>'
			_html += '<td data-type="norms"> ' + modifyBtn;
			_html += '&nbsp;' + managePramBtn + '&nbsp;' ;
			if(editDefaultVal.status == 0 ){
				_html += openBtn;
			}else{
				_html += closeBtn;
			}
			_html += '</td> ' ;
		$("#dataListTable #data_"+editDefaultVal.id ).html( _html ) ;
	}else{
		  GhostMsg(msg.info);
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
			$(_this).attr("data-status",0);
		}else if ($(_this).text() == "开启") {
			$(_this).text("关闭");
			$(_this).removeClass("btn-warning");
			$(_this).addClass("btn-danger");
			$(_this).attr("data-status",1);
		}
		GhostMsg(msg.info);
	}else{
		 return GhostMsg("error:"+ msg.info);
	}
	Loading("hide") ;
 }

var  editDefaultVal ;
function initEditDefaultVal(){
	editDefaultVal =
	{
		modal:{id:"",title:"添加" , req:"add"},
		id:"",
		name : "" ,
		oldname:"00000000000",
		status :1 
	}
}
/*校验提交数据是否合格*/
function checkInputMsg()
{
		var msg ;
 		editDefaultVal.name =$("#edit_name").val() ;
 		editDefaultVal.intro =$("#edit_intro").val() || " " ;
		if(editDefaultVal.name == ""){
			return msg = "类型不能为空"
		}
		if($(".modal-title").attr( "data-req")=="modify"){
 			msg ={ id: $(".modal-title").attr( "data-id") ,recommend: editDefaultVal.recommend,name:editDefaultVal.name,oldname:editDefaultVal.oldname,intro:editDefaultVal.intro}  
		}else{
			msg = {name:editDefaultVal.name,intro:editDefaultVal.intro,recommend:0} 
		}
		return msg ;
}

/*清空编辑框中的数据*/
var edits_Empty = function ()
{
	$("#edit #edit_name").val( "" );
	$("#edit #edit_intro").val("") ;  
}
var  addModeHtml;
/*添加规格*/
function addProductMode()
{
	modalView('show');
	$('.btn_edit_submit').removeClass("lock");
	$('.btn_edit_submit').text("确认提交");
	if(!addModeHtml)
	{
		addModeHtml = ''
		addModeHtml += '<div id = "edit" style="max-width: 700px; margin: 0 auto;border-left: 1px solid #ddd; " class="table-con">' ;
		addModeHtml += '<table > <thead> <tr> <th colspan = "2" > 基本信息 </th></tr></thead><tbody >' ;
		addModeHtml += '<tr><td style = "width:150px ;">* 类型 </td><td> <input id = "edit_name" class="form-control" placeholder="添加名称">  </td></tr>' ;
		addModeHtml += '<tr><td> 简介 </td><td><textarea id = "edit_intro" class="form-control" rows="3"  placeholder="简介" ></textarea></td></tr>' ;
		addModeHtml += '<tr><td colspan = "2"> <p style = "line-height:30px;" > <span id = "msg_error" style = "font-size:10px;" class = "warning" ></span> </p>';
		addModeHtml += '<button   class="btn_edit_empty btn btn-default" style = "margin-right: 20px;">  清空  </button> <button    class="btn_edit_submit btn btn-success" >确认提交  </button> </th></tr></tbody></table>' ;
		addModeHtml += '</div>' ;
		 $(".modal-body").html( addModeHtml );
	}
	return ;
}
