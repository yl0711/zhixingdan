/*初始化*/
var addAction , modifyAction , modifyStatusAction ,seachByStatusAction , seachByNameAction,
	getNormsAction , currentNorms , modifyBtn , openBtn , closeBtn, recommend_input;

$(function(){
	initEditDefaultVal();
	/*清空编辑框中的数据*/
	$(".btn_edit_empty").live("click",function (){
		edits_Empty();
	});
	
	/*修改推荐权重值*/
	baseInit.byModifyStatusAction('recommend',modifyStatusAction);
	
 	/*按照数据状态（是否已经开启）检索 */
 	baseInit.seachByStatus(seachByStatusAction) ;
 	
 	/*添加一条数据*/
	$(".add-ico").bind("click",function(){
		initEditDefaultVal();
		addProductModeVal();
		$(".modal-title").html( "添加" );
		$(".modal-title").attr( "data-req" , "add");
		 /*//清空*/
		edits_Empty();
		if($(this).attr("norms-id")>0){
			$("#edit .norms_id_list li").css("display","none").removeClass("btn-success") ;
			$("#edit #norms_"+$(this).attr("norms-id")).css("display","inline-block").addClass("btn-success") ;
		}else{
			$("#edit .norms_id_list li").css("display","inline-block").removeClass("btn-success") ;
		}
		
		 
	});
	/*修改一条数据*/
	$(".modify").live("click",function(){
		 
			modifyByThis(this);
	});
	
	function  modifyByThis( _this ){
			edits_Empty();
			var $_closest = $(_this).closest("tr"),
			_dataId = $_closest.attr("data-id");
			editDefaultVal.id = _dataId ;
			editDefaultVal.status = $_closest.find(".on-off").attr("data-status") ;
			editDefaultVal.value = $_closest.find("._value").text() ;
			editDefaultVal.recommend =parseInt( $_closest.find(".recommend").val() ||  $_closest.find("._recommend").text() );
			editDefaultVal.norms_id = $_closest.find("._norms").attr("norms-id") ;
		 if( $(_this).hasClass('modify') ){
			 addProductModeVal();
  			 $("#edit .value").val( editDefaultVal.value );
 			 $("#edit .norms_id_list li").css("display","none").removeClass("btn-success")  ;
			 $("#edit #norms_"+editDefaultVal.norms_id).css("display","inline-block").addClass("btn-success")  ;
  			 $(".modal-title").html( "修改:"+ Norms[editDefaultVal.norms_id]  );
			 $(".modal-title").attr( "data-req" , "modify");
			 $(".modal-title").attr( "data-id" , _dataId);
		}else if( 	$(_this).hasClass('copy') ){
		}else if(	$(_this).hasClass('recommend')  ){
			ajax_request(this, modifyAction , "post", {
	 			
				 id: editDefaultVal.id ,
				 norms_id:editDefaultVal.norms_id,
				 value:editDefaultVal.value,
				 recommend:editDefaultVal.recommend

			} , function(_this,msg){
				GhostMsg("已推荐");
			}) ;
		}
	}
	
 
	
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
		if(typeof _Msg=="object"  && !$(this).hasClass("lock")){
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
	
	
	/*分类管理参数*
	 */
	
	/*按照数据类型检索 */
	$(".seachByNormsId").live("change",function(){
		 /*页面跳转  $(this).val() */
		location.href = seachByStatusAction +"/"+ $(this).val() +"/"+  $(".seachByStatus").val() ;
	});
	/*按照数据状态（是否已经开启）检索 */
	$(".seachByStatus").live("change",function(){
		 /*页面跳转  $(this).val() */
		location.href = seachByStatusAction +"/"+$('.seachByNormsId').val()+"/"+ $(this).val()+ "?pageSize=" +  $('.pageSize').val() ;  ;
	});

 	/*******************规格参数 页面***********************/
	/*选择类*/
	$(".type_select li").live("click",function(){
		if($('.modal-title').attr('data-req')!=="modify"){
			$(this).siblings("li").removeClass("btn-success").end().addClass("btn-success") ;
		}else{
			GhostMsg('类型 : 暂不支持修改');
		}
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
			_html += '<td class= "_id _NEW" >'+  msg.data.id +'</td>' ;
			_html += '<td class= "_norms" norms-id="'+current_add_data_msg.norms_id+'" >'+Norms[current_add_data_msg.norms_id]+'</td>' ;
			_html += '<td class= "_value" >'+current_add_data_msg.value+'</td> ' ;
			_html += '<td class= "_timestamp" > 刚刚 </td> ' ;
			_html += '<td  class= "_recommend"> ' + recommend_input.replace('#num#', 0) + '</td>'
			_html += ' <td  >' + modifyBtn + '&nbsp;' + closeBtn + '</td> ' ;
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
	console.log(msg) ;
	if( msg.status && msg.status == 1  )
	{
		modalView('hide');
		var _html = '' ;
			_html += '<td class= "_id _MODIFY" >'+  current_add_data_msg.id +'</td>' ;
			_html += '<td class= "_norms"  norms-id="'+current_add_data_msg.norms_id+'" >'+Norms[current_add_data_msg.norms_id]+'</td>' ;
			_html += '<td class= "_value" >'+current_add_data_msg.value+'</td> ' ;
			_html += '<td class= "_timestamp" > 刚刚 </td> ' ;
			_html += '<td  class= "_recommend" > ' + recommend_input.replace('#num#', editDefaultVal.recommend) + '</td>'
 			_html += '<td >' + modifyBtn + '  ' ;
			if(editDefaultVal.status == 0 ){
				_html += openBtn;
			}else{
				_html += closeBtn;
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
		name : "" ,
		status :1 
	}
}
/*校验提交数据是否合格*/
function checkInputMsg()
{
		var msg ;
		var _norms_id  =  $("#edit .norms_id_list").find(".btn-success").attr("data-id");
		var _value  = $("#edit .value").val() ;
		if(!_norms_id || _norms_id == ""){
			return msg = "类型不能为空" ;
		}else if(!_value || _value == ""){
			return msg = "参数值不能为空" ;
		}
		if($(".modal-title").attr( "data-req")=="modify"){
			msg ={ id: $(".modal-title").attr( "data-id") ,norms_id:_norms_id,value:_value,recommend:editDefaultVal.recommend}  
		}else{
			msg = {norms_id:_norms_id,value:_value,recommend:0} ;
		}
		
		return msg ;
}

/*清空编辑框中的数据*/
var edits_Empty = function ()
{
	$("#edit .value").val("") ;
}
function getNorms (){
	Norms = {}
	$(".seachByNormsId option").each(function(){
		Norms[$(this).val()] = $(this).text()  ;
	})
	return Norms ;
}
/*添加规格参数*/
var  addModeHtmlVal ;
var Norms ;
function addProductModeVal()
{
	modalView('show');
	$('.btn_edit_submit').removeClass("lock");
	$('.btn_edit_submit').text("确认提交");
	Norms = getNorms() ;
	if(!addModeHtmlVal && typeof Norms == "object"){
		
		addModeHtmlVal = ''
		addModeHtmlVal += '<div  id= "edit"  style="max-width: 700px; margin: 0 auto;border-left: 1px solid #ddd; " class="table-con">' ;
		addModeHtmlVal += '<table class ="prodict_edit"> <thead> <tr> <th colspan = "2" > 规格参数 </th></tr></thead>' ;
		addModeHtmlVal += '<tbody >' ;
		addModeHtmlVal += '<tr><td style = "width:150px ;">* 类型 </td><td> <ul class = "type_select norms_id_list"　>'
		for(var i in Norms){
			addModeHtmlVal += ' <li class = ""  data-id = "'+i+'" id = "norms_'+i+'" >'+Norms[i]+' </li> ';
		}
		addModeHtmlVal +='</ul> </td></tr>' ;
		addModeHtmlVal += '<tr><td style = "width:150px ;">* 值</td><td> <input class="value form-control" placeholder="如：PVC">  </td></tr>'
		addModeHtmlVal += '<tr><th colspan = "2" ><p style = "line-height:30px;" > <span id = "msg_error" style = "font-size:10px;" class = "warning" ></span> </p><button class="btn btn-default btn_edit_empty" style = "margin-right: 20px;">  清空  </button><button class="btn_edit_submit btn btn-success" >确认提交  </button></th></tr></tbody></table>' ;
		addModeHtmlVal += '</div>' ;
		 $(".modal-body").html( addModeHtmlVal );
	}
	return    ;
   
}

 
