var upimgAction, addAction , modifyAction , modifyStatusAction ;
$(function()
{
	// 初始化
	init();
})

var init = function()
{
	
	/*添加一条数据*/
	$("#add-ico").live("click",function(){
		current_checkInputMsg = checkInputMsg();
		if(typeof current_checkInputMsg=="object"  ){
			ajax_request(this,addAction,"post",current_checkInputMsg,add_success) ;
		}else{
			$("#keyTest").html(current_checkInputMsg);
		}
	});
	
 
			
	/*更改状态*/
	$(".datalist span").live("click","",function(){


		if($(this).hasClass("current")){
			if($(this).parent().hasClass("on"))
			{
				
				current_checkInputMsg_ststus = 0 ;/*console.log("关闭");*/
			}
			else if($(this).parent().hasClass("off"))
			{
				
				
				current_checkInputMsg_ststus = 1 ;/*console.log("开启 ");*/
			}
			ajax_request(this, modifyStatusAction , "post",{id:$(this).attr("data-id"),status:current_checkInputMsg_ststus},modifyStatus_success) ;
		}else{
			$(this).siblings("span").removeClass("current").end().addClass("current");
		}

	});
	
	
	
	
}


/*校验提交数据是否合格*/
var current_checkInputMsg_ststus ;
function checkInputMsg()
{
		var name = $("#addName").val() || "";
		var first_word = $("#addFirst_word").val() || "";	
		var ENreg= /^[A-Za-z]+$/; /*是否字母*/
		if( !first_word || first_word.length !== 1 || first_word == "" || !ENreg.test(first_word) ){
			return "首字母填写有误"  ;
		}else if(!name || name == "" ){
			return "标签填写有误"  ;
		}

		return {name:name,first_word:first_word.toUpperCase() } ;
}

/*添加 数据 成功 后 回调函数*/
var add_success = function (_this,msg)
{
	
	if(msg.status && msg.status == 1 ){
			$(".datalist .on").append( "<span data-id = '"+msg.data.id+"' data-first_word = '"+current_checkInputMsg.first_word+"'  >"+current_checkInputMsg.name+"</span>" );
			return GhostMsg(msg.info);
	}else{
		return GhostMsg(msg.info);
	}

	
}

/*修改 数据 成功 后 回调函数*/
var modify_success = function (_this,msg)
{
	
	return GhostMsg(msg.info);
	
}

//, _name =  , _first_word =  $this.attr("data-first_word"),

/*更改状态 数据 成功 后 回调函数*/
var modifyStatus_success =  function(_this,msg)
{
	if(msg.status && msg.status == 1 ){
		if( current_checkInputMsg_ststus == 1 ){
			$(".datalist .on").append( "<span data-id = '"+ $(_this).text() +"' data-first_word = '"+$(_this).attr('data-first_word')+"'  >"+ $(_this).text() +"</span>" );
		}else{
			
			$(".datalist .off").append( "<span data-id = '"+ $(_this).text() +"' data-first_word = '"+$(_this).attr('data-first_word')+"'  >"+ $(_this).text() +"</span>" );
		}
		$(_this).remove();
		return GhostMsg(msg.info);
	}else{
		return GhostMsg("添加失败");
	}
	
	
}


