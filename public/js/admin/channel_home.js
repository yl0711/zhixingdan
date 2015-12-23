 /*初始化*/
var upimgAction, modifyAction , getnameAction ;
var tempData = {
		currentUpimg : ".add_img",
		currentsubmit : ''
	}
 $(function(){init();});
 var init = function()
{
	/*上传图片*/
  	$(".imgfile").live("change",function (){
  		if(  $(this).val() !== ""){
   			$(this).parent().submit();
  			tempData.currentUpimg =$(this).prev('.add_img')  ;
  		}
  	});
   	
  	/*提交推荐数据*/
  	$(".submit_ok").on("click",function (){
  		var param = false ;
   		switch( $(this).attr('data') ){
  			/*活动管理*/
  			case "active":
  				var active = [] ;
  				tempData.currentsubmit = "活动管理";
				 $(".active tr").each(function(){
				 	if($(this).find('.id').val() == "" || $(this).find('.name').attr('data') ==""){
				 		return false;
				 	}
				 	active.push( {id:$(this).find('.id').val(),name:$(this).find('.name').attr('data')} );
				 });
				 if(active.length > 0 ){param = {active:active} }
  			break;
  			/*牛社资讯管理*/
  			case "article":
  				 var article = [] ;
  				 tempData.currentsubmit = "牛社资讯管理";
				 $(".article tr").each(function(){
				 	if($(this).find('.id').val() == "" || $(this).find('.name').attr('data') ==""){
				 		return false;
				 	}
				 	article.push( {id:$(this).find('.id').val(),name:$(this).find('.name').attr('data')} );
				 });
  				 if(article.length > 0 ){param = {article:article} }
  			break;
  			/*首页焦点图管理*/
   			case "focus":
  				var focus = [] ;
  				tempData.currentsubmit = "首页焦点图管理";
			 	$(".focus tr").each(function(){
			 		if($(this).find('.add_img').attr("data-url") !== "" &&  $(this).find('.url').val() !== ""){
			 			focus.push( {
					 		status: $(this).find("input[type='checkbox']").is(':checked') ? 1 : 0 ,
					 		pic: $(this).find('.add_img').attr("data-url") || "",
					 		name: $(this).find('.name').val() || "" ,
					 		url:  $(this).find('.url').val() || "" ,
					 		recommend :  $(this).find('.recommend').val() || 0 
					 	});
			 		}
				 });
 				if(focus.length > 0 ){param = {focus:focus} }
			break;
			
			/*
			 * 供销社
			 *
			 */
			
			/*出售*/
			case "sell":
  				 var sell = [] ;
  				 tempData.currentsubmit = "出售管理";
				 $(".sell tr").each(function(){
				 	if($(this).find('.id').val() == "" || $(this).find('.name').attr('data') ==""){
				 		return false;
				 	}
				 	sell.push( {id:$(this).find('.id').val(),name:$(this).find('.name').attr('data')} );
				 });
  				 if(sell.length > 0 ){param = {sell:sell} }
  			break;
			/*求购*/
			case "buy":
  				 var buy = [] ;
  				 tempData.currentsubmit = "求购管理";
				 $(".buy tr").each(function(){
				 	if($(this).find('.id').val() == "" || $(this).find('.name').attr('data') ==""){
				 		return false;
				 	}
				 	buy.push( {id:$(this).find('.id').val(),name:$(this).find('.name').attr('data')} );
				 });
  				 if(buy.length > 0 ){param = {buy:buy} }
  			break;
			
  		}
   		/*判断是否有需要提交的数据*/
   		console.log(param)
   		if(param){
   			ajax_request(this, modifyAction , "post",param ,
		  		function (_this,msg){
		  			if(msg && msg.info && msg.status == 1 ){
		  				if($(_this).attr('data')!=="focus"){
		  					//$(_this).attr('disabled',true);
		  					$(_this).attr('title','当前已经是最新状态');
 		  				}
		  				return GhostMsg(tempData.currentsubmit+'更新成功');
		  			}
		  		},
		  		function (_this,msg){
		  			GhostMsg('ERROR:请重试'); 
		  		}
		  	);
   		}else{
   			GhostMsg('请完善需要更新的内容');
   		}

  	});
  	
  	/*获取标题信息*/
  	$(".getname").on("change",function (){
  		if($(this).val() == "" || $(this).val() == undefined){
  			$(this).closest('tr').find('.name').attr('data',''); 
  			$(this).closest('tr').find('.name').html('【无数据】'); 
  			return false;
  		}
  		
  		Loading('show') ;
  		ajax_request(this, getnameAction , "post",
  			{
  				id:$(this).val(),
  				type:$(this).attr('data')
  			} ,
	  		function (_this,msg){
	  			Loading('hide') ;
	  			if(msg && msg.data && msg.data.name ){
	  				//$(_this).closest('.'+$(_this).attr('data')).find('.submit_ok').attr('disabled',false);
	  				$(_this).closest('tr').find('.name').html(msg.data.name); 
	  				$(_this).closest('tr').find('.name').attr('data',msg.data.name); 
	  			}else{
	  				GhostMsg('没有查询到对应数据');
	  				//$(_this).closest('.'+$(_this).attr('data')).find('.submit_ok').attr('disabled',true);
	  				$(_this).closest('tr').find('.name').html('【无数据】'); 
	  				$(_this).val(''); 
	  				$(_this).closest('tr').find('.name').attr('data',''); 
	  			}
	  		},
	  		function (_this,msg){
	  			Loading('hide') ;
	  			GhostMsg('ERROR:请重试');
	  		}
	  	);
  	});
}

/*上传图片完成回调函数*/
var upImgCallback = function (msg)
{   
	
	console.log(tempData.currentUpimg)
	$(tempData.currentUpimg).attr("data-url",msg.dbPath);
    $(tempData.currentUpimg).attr("src","http://"+msg.imageUrl);
 } 

