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
 	 modifyBtn,	 /*修改按钮*/ 
 	 closeBtn,	 /*关闭按钮*/  
 	 openBtn,	 /*开启按钮*/  
 	 cloneBtn,	 /*克隆按钮*/ 
 	 like_count_input, /*修改点赞数输入框*/
 	 recommend_input, /*修改推荐数输入框*/
  	 draftGetAction  , /*获得草稿*/
  	 draftSetAction ; /*草稿保存*/
  	 /* 转换为json*//*获得标签厂商IP*/
  	 getContactAction = eval('(' + getContactAction + ')'); 
  	
$(function (){
	init() ;
})	
 var  init = function (){
	/*初始化模态框内的值*/
	initEditDefaultVal() ;
	
	/*修改推荐权重值*/
	baseInit.byModifyStatusAction('recommend',modifyStatusAction);
	/*修改点赞数*/
	baseInit.byModifyStatusAction('like_count',modifyStatusAction);
 	/*按照数据状态（是否已经开启）检索 */
 	baseInit.seachByStatus(seachByStatusAction) ;
      /*修改一条数据*/
	$(".modify").live("click",function(){
		modifyByThis(this);
 	});
  	 /*克隆一条数据*/
	$(".copy").live("click",function(){
		modifyByThis(this);
	});
  	 
   	/*添加一条数据*/
	$(".add-ico").bind("click",function(){
 		/*初始化数据*/
		initEditDefaultVal () ;
 		/*
		 * 先去获得草稿 ： 
 		 * 如果存在草稿，则显示。 
		 * 同时启动定时器 。50000ms调用草稿保存功能
		 **/
 		/*查看是否存在草稿*/
 		Loading('show');
		baseInit.draftSave.getDraft(draftGetAction , "goods" ,function(_this,draftInfo){
			if(draftInfo){
				for(var i in draftInfo){
					switch (i){
						case 'norms':
							editDefaultVal.norms = draftInfo[i].split(',');
						break;
						case 'firm_id':
							editDefaultVal.firm = draftInfo[i].split(',');
						break;
						case 'cartoon_id':
							editDefaultVal.cartoon = draftInfo[i].split(',');
						break ;
						default:
							editDefaultVal[i] = draftInfo[i] ;
						break;
					}
				}
				GhostMsg("已自动填充草稿中的数据");
			}
			Loading('hide');
			/*显示 添加模态框 */
			addProductMode();
	 		/*启动草稿保存*/
	  		baseInit.draftSave.start(checkInputMsg,true,draftSetAction , "goods" ) ;
	   		/*清空*/
			//edits_Empty();
		} )  ;
   	});
	
 	/*更改状态*/
	$(".on-off").live("click",function(){
		Loading('show') ;
		 var _dataId = $(this).closest("tr").attr("data-id");
		 var _status = 0 ;
		if($(this).text() == "关闭"){
 			_status = 0 ; 
		}else{
 			_status = 1 ; 
		}
		/*请求数据*/
		editDefaultVal.status = _status ;
		ajax_request(this, modifyStatusAction , "post",{id:_dataId,status:_status} ,modifyStatus_success) ;
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
		//
  	});
	  	
	/*确认提交*/
	$(".btn_edit_submit").live("click",function (){
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
		 	//$(".dynamicDiv").slideUp("fast");	
		 	//$(".mymode").html(_tit.replace(_txt2,_txt1));
		 	$(this).html(_tit.replace(_txt1,_txt2));
		 	$(this).closest("td").find(".dynamicDiv").slideDown("fast");	
		 }
  	});
 	
  	/*按类别查询查询 规格*/
	$('.EN_serch li').live("click",function()
	{
 		$(this).siblings("li").removeClass("current").end().addClass("current");
		if( $(this).closest('td').hasClass('_norms') )
		{
			var _norms = Product_Parm_ArreaList.norms[ $(this).attr('this-in') ]['norms_value'] ;
			var _html = ''
			//判断是否已经选择过了
			 isIncude = function (j){
				for(var k in  editDefaultVal.norms)
					{
						if(_norms[j].id == editDefaultVal.norms[k] )
						{
							return true ; 
						}
					 }
			}
			for(j in _norms)
			{
				if( !isIncude(j))
				{
					_html +='<li class ="status_color_'+_norms[j].status+'" data-id="'+ _norms[j].id +'" data-status ="'+_norms[j].status+'" >'+ _norms[j].value +'</li>';
				}
			}
			 $(this).closest('td').find('.seach_list').attr('this-in',$(this).attr('this-in')) ;
			 $(this).closest('td').find('.seach_list').html(_html);
		}
		else if( $(this).closest('td').hasClass('_firm') || $(this).closest('td').hasClass('_cartoon') )
		{
			
			var PP = Product_Parm_ArreaList[$(this).closest('td').attr('id')] ;
			var _html = '';
  			var includeID = 0 ;
			if( editDefaultVal[$(this).closest('td').attr('id')].length>0 ){
				includeID = editDefaultVal[$(this).closest('td').attr('id')][0] ;
			}
 			if($(this).text() == "全部" ){
				for(var i in  PP){
					if( includeID !==  PP[i].id ){
						_html +='<li class ="status_color_'+PP[i].status+'" data-id="'+ PP[i].id +'" data-status ="'+PP[i].status+'" >'+ PP[i].name +'</li>';
	 				}
				}
			}else{
				for(var i in  PP){
	 				if( ($(this).text()).indexOf(PP[i].first_word) !==-1  ){
	 					if(parseInt(includeID)  !==  PP[i].id){
	 						_html +='<li class ="status_color_'+PP[i].status+'" data-id="'+ PP[i].id +'" data-status ="'+PP[i].status+'" >'+ PP[i].name +'</li>';
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
			if($(this).hasClass('status_color_0')){
				return GhostMsg("此项已禁用");
			}
			_cur = $(this).parent().attr("data-parent");
		
			if( _cur == "cartoon" || _cur == "firm" ){
				$(this).parent().append($('#'+_cur+' .selected_list li').clone());
				$('#'+_cur+' .selected_list li').remove();
				/*单选直接赋值*/
				editDefaultVal[_cur] = [$(this).attr('data-id')]
				
			}
  			if(_cur == "norms")
  			{
  				/*多 选需要 push */
  				if(!editDefaultVal[_cur]){
  					editDefaultVal[_cur] = [ $(this).attr('data-id') ]
  				}else{
  					editDefaultVal[_cur].push($(this).attr('data-id'))
  				}
   				
  			}
  			$('#'+_cur+' .selected_list').append($(this).clone());
  			$(this).remove();
  	});
  	
	/*删除已选中*/
	$('.selected_list li').live("click",function(){
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
 			$(this).remove();
		}
	});
	
}

//删除 附属标签 数组中的某一个元素
 var delateEditDefaultVal = function   (cur , _id){
		for(var i in editDefaultVal[cur] ){
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

  
var	   current_add_img ,
	 /*添加 数据 成功 后 回调函数*/
	 add_success = function (_this,msg){
 		if( msg &&  msg.status && msg.status == 1  &&  msg.data.id )
		{
			/*添加成功后在列表中增一条数据 ;*/
	 		modalView('hide');
	 		
			var _html = '<tr id = "data_'+  msg.data.id +'"　 data-id = "'+  msg.data.id +'" >' ;
				_html += '<td class= "_id" >'+ msg.data.id +'</td>' ;
				_html += '<td class= "_pic" ><img data-url="'+editDefaultVal.pic+'" src="'+imageDomain+editDefaultVal.pic+'" class = "min_img" ></td>' ;
				_html += '<td class= "_name" >'+editDefaultVal.name+'</td>' ;
				_html += ' <td class= "_year">'+editDefaultVal.year+'</td>' ;
				var _firm = getProduct_Parm_ArreaList('firm',editDefaultVal.firm[0]);
				if(_firm){
					_html += '<td class= "_firm" data-id='+_firm.id+' >'+_firm.name+'</td> ' ;
				}else{
					_html += '<td class= "_firm" data-id ></td> ' ;
				}
				var _cartoon = getProduct_Parm_ArreaList('cartoon',editDefaultVal.cartoon[0]);
				if(_cartoon){
					_html += '<td class= "_cartoon" data-id='+_cartoon.id+' >'+_cartoon.name+'</td> ' ;
				}else{
					_html += '<td class= "_cartoon" data-id ></td> ' ;
				}
				_html += '<td >0</td><td class = "_like_count">' + like_count_input.replace('#num#', 0) + '</td> <td>刚刚</td><td  class = "_recommend"> ' + recommend_input.replace('#num#', 0) + '</td>' ;
				_html += '<td >' + cloneBtn + '  ' + modifyBtn + '  ' + closeBtn + '</td> ' ;
				_html += '</tr> ' ;
			$("#dataListTable").prepend(_html) ;
		}else{
			  GhostMsg("error:"+ msg.info);
		}
		
		$(_this).removeClass("lock");
		$(_this).text("确认提交");
	 },
	//点击修改按钮 执行
	 modifyByThis = function  ( _this ){
		Loading('show') ;
		var $_closest = $(_this).closest("tr"),
		 	thisPic =$_closest.find("._pic").find("img").attr("src") ,
			_dataId = $_closest.attr("data-id");
			ajax_request(_this, modifyInitial , "post",{id:_dataId} ,function (_this,msg){
				
				if(msg.status && msg.status==1){
					var OtherMsg = msg.data;
					var _norms = [] ; 
 					for(var i in OtherMsg.norms){
						_norms.push(''+OtherMsg.norms[i].id)
					}
 					editDefaultVal =
					{
 						modal:{id:_dataId ,title:"修改" , req:"modify"},
						id :_dataId ,
						recommend :  parseInt($_closest.find(".recommend").val() || $_closest.find("._recommend").text()),
						like_count : parseInt($_closest.find(".like_count").val() || $_closest.find("._like_count").text()),
						pic : thisPic.replace(imageDomain,"") ,
						pic_big  : OtherMsg.pic_big  ,
						name : $_closest.find("._name").text() ,
						year :  $_closest.find("._year").text()  ,
						//cartoon : [{
							//"name":$_closest.find("._cartoon").text(),
							//id :$_closest.find("._cartoon").attr('data-id') ,
							//"status" :$_closest.find("._cartoon").attr('data-status')
						//}]  ,
						//firm : [{
							//name:$_closest.find("._firm").text() ,
							//id: $_closest.find("._firm").attr('data-id'),
							//status :$_closest.find("._firm").attr('data-status')
						//}],
						//tag : OtherMsg.tag  ,
						//norms :OtherMsg.norms ,
						cartoon : [$_closest.find("._cartoon").attr('data-id')],
						firm: [$_closest.find("._firm").attr('data-id')],
						norms: _norms,
						status :$_closest.find(".on-off").attr('data-status') ,
						intro : OtherMsg.intro
						
					}
					
					if( $(_this).hasClass('modify' ) ){
						editDefaultVal.modal = {id:_dataId ,title:"修改" , req:"modify"} ;
						addProductMode();
					}else if( 	$(_this).hasClass('copy') ){
						editDefaultVal.modal = {id:_dataId ,title:"克隆" , req:"add"} ;
						editDefaultVal.name = "" ;
						addProductMode();
					}
					
				}else{
					return GhostMsg();
				}
				Loading("hide") ;
			}) ;
	},
	/*修改 数据 成功 后 回调函数*/
	 modify_success = function (_this,msg)
	{
		
	  	if(msg &&  msg.status && msg.status == 1  )
		{
			modalView('hide');
			var _html = '' ;
	 			_html += '<td class= "_id" >'+ msg.data.id +'</td>' ;
				_html += '<td class= "_pic" ><img data-url="'+editDefaultVal.pic+'" src="'+imageDomain+editDefaultVal.pic+'" class = "min_img" ></td>' ;
				_html += '<td class= "_name" >'+editDefaultVal.name+'</td>' ;
				_html += ' <td class= "_year">'+editDefaultVal.year+'</td>' ;

				var _firm = getProduct_Parm_ArreaList('firm',editDefaultVal.firm[0]);
				if(_firm){
					_html += '<td class= "_firm" data-id='+_firm.id+' >'+_firm.name+'</td> ' ;
				}else{
					_html += '<td class= "_firm" data-id ></td> ' ;
				}
				var _cartoon = getProduct_Parm_ArreaList('cartoon',editDefaultVal.cartoon[0]);
				if(_cartoon){
					_html += '<td class= "_cartoon" data-id='+_cartoon.id+' >'+_cartoon.name+'</td> ' ;
				}else{
					_html += '<td class= "_cartoon" data-id ></td> ' ;
				}
				_html += '<td >0</td><td class = "_like_count" >' + like_count_input.replace('#num#', editDefaultVal.like_count) + '</td><td>刚刚</td><td  class = "_recommend" > ' + recommend_input.replace('#num#', editDefaultVal.recommend) + '</td>' ;
				_html += '<td >' + cloneBtn + '  ' + modifyBtn + '  ' ;
				if(editDefaultVal.status == 0 ){
					_html += openBtn;
				}else{
					_html += closeBtn;
				}
				_html += '</td> ' ;
			$("#dataListTable #data_"+editDefaultVal.id ).html( _html ) ;
		}else{
			 GhostMsg("error:"+  msg.info);
		}
		
		$(_this).removeClass("lock");
		$(_this).text("确认提交");
	},

	/*更改状态 数据 成功 后 回调函数*/
	 modifyStatus_success =  function(_this,msg){
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
		Loading("hide") ;
	 },

	/*上传图片完成回调函数*/
	upImgCallback = function (msg)
	{   
	 	$("#edit ."+current_add_img+" .add_img").attr("data-url",msg.dbPath);
	    $("#edit ."+current_add_img+" .add_img").attr("src",imageDomain + msg.dbPath);
	  
	} ,
	
	/*校验提交数据是否合格*/
	checkInputMsg = function (draft)
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
			 
			var msg ,
				 pic  =  $("#edit ._pic .add_img").attr("data-url"),
				 pic_big  =  $("#edit ._pic_big .add_img").attr("data-url"),
				 name  =  $("#edit ._name input").val(),
				 year = $("#edit ._year input").val() ||'',
	  			 Numreg = /^[0-9]*$/ , /*是否数字*/
	 			 cartoon_id  =  getSelectedIds("_cartoon") || "",
				 firm_id  =   getSelectedIds("_firm") || "" ,
				 intro  = editorRichTextArea.getData(),
	  			 norms_id = getSelectedIds("_norms") || "";
			  	 //tags_id = getSelectedIds("_tag") || "" ;
			  	
			  	
			if(!draft){
      			if(!pic || pic == ""){
					return "请上传图片" ;
				}else if(!pic_big || pic_big == ""){
					return "请上传附图" ;
				}else if(!name || name == ""){
					return "请填写标题.." ;
				}else if(!year || year == "" || !Numreg.test(year) ){
					return "年代输入有误" ;
				}
				/*else if(!cartoon_id || cartoon_id == ""   ){
					return "请选择一个IP" ;
				}else if(!firm_id || firm_id == ""  ){
					return "请选择一个厂商" ;
				}else if(!norms_id || norms_id == ""  ){
					return "请至少选择一个规格" ;
				}*/
			}else if(
				name == cartoon_id && 
				name == firm_id && 
				name == intro && 
				name == norms_id  
			){
				return false;
			}
 			if($(".modal-title").attr( "data-req")=="modify"){
	 			msg ={ id: $(".modal-title").attr( "data-id") ,
		 			recommend:editDefaultVal.recommend ,
		 			pic:pic,pic_big:pic_big,
		 			name:name,intro:intro,
		 			year:year,
		 			cartoon_id:cartoon_id,
		 			firm_id:firm_id,
		 			norms:norms_id ,
					/*tag:tags_id, */
		 			oldname:editDefaultVal.name
	 			}
 			}else if($(".modal-title").attr( "data-req")=="add"){
				msg = 
				{
					pic:pic,
					pic_big:pic_big,
					name:name,
					intro:intro,
					year:year,
					cartoon_id:cartoon_id,
	 				firm_id:firm_id,
	 				recommend:0 ,
					/*tag:tags_id,*/
					norms:norms_id
					
				}
				editDefaultVal.status = 1 ;
				
			}
 			if(!draft){
 				editDefaultVal.recommend = msg.recommend ;
				editDefaultVal.pic = pic ;
				editDefaultVal.pic_big =pic_big ;
				editDefaultVal.name = name ;
				editDefaultVal.year =year ;
				editDefaultVal.cartoon = [cartoon_id]  ;
				editDefaultVal.firm = [firm_id] ; 
				/*//editDefaultVal.tag =tags_id;*/
				editDefaultVal.norms = norms_id ; 
				editDefaultVal.intro = intro ;
 			}
			
			
			return msg ;
	} ;
/*
 * 添加产品的魔板
 * 通过后台接口调用到所哟的
 * 所有的IP、厂商、规格、和标签
 * 
 * */
/*产品相关参数*/
var Product_Parm_ArreaList = {
		about:{"cartoon":"IP" ,"firm":"厂商" ,"norms":"规格" }
	},
	addProductModeHtml  ,
	editorRichTextArea ,
	editDefaultVal ,
	initEditDefaultVal = function (){
			editDefaultVal =
			{
				modal:{id:"",title:"添加" , req:"add"},
				recommend:0,
				like_count:0,
				pic : "/images/admin/iconfont-shangchuantupian.png" ,
		 		pic_big : "/images/admin/iconfont-shangchuantupian.png" ,
				name : "" ,
				year : "" ,
		 		cartoon : [] ,
				firm : [] ,
				/*tag : [] ,*/
				norms : [] ,
				status :1 ,
				intro : ""
			}
	} ,
	addProductMode = function (){
	 	$('.btn_edit_submit').removeClass("lock");
		$('.btn_edit_submit').text("确认提交");
	 	if(!addProductModeHtml || !Product_Parm_ArreaList.status ){	
			
	 		Product_Parm_ArreaList.firm = getContactAction.data.firm ;
			Product_Parm_ArreaList.cartoon = getContactAction.data.cartoon ;
			Product_Parm_ArreaList.norms = getContactAction.data.norms ;
			Product_Parm_ArreaList.status = 1 ;
			ProductMode() ;
		}else{
			ProductMode() ;
		}
	},
	
	/*根据id查询一条数据*/
	getProduct_Parm_ArreaList = function(ele,id){
 		var p = Product_Parm_ArreaList[ele]
		for(var i in p){
			switch(ele){
				case 'norms' :
					for(var k in p[i]['norms_value'] ){
						if( p[i]['norms_value'][k].id  == id ){return p[i]['norms_value'][k] ;}
					}
				break;
				default:
					if(p[i].id == id ){return p[i] ;}
				break;
			}
 		}
		
	};
	
var ProductMode = function (){
		modalView('show' ,true);
		if(!addProductModeHtml)
		{
				addProductModeHtml = '';
				addProductModeHtml += '<div  id= "edit"  style="max-width: 700px; margin: 0 auto;border-left: 1px solid #ddd; " class="table-con">' ;
				addProductModeHtml += '<table class ="prodict_edit"> <thead> <tr> <th colspan = "2" > 产品 基本信息</th></tr></thead>' ;
				addProductModeHtml += '<tbody class = "_others" ></tbody> ' ;
				addProductModeHtml += '<tr><td style = "width:150px ;" >*缩略图<br>360*360(px)</td><td  class = "_pic">';
	 			addProductModeHtml += '</td></tr>' ;
				addProductModeHtml += '<tr ><td >*封面图<br>720*404(px)</td><td  class = "_pic_big">' ;
	 			addProductModeHtml += '</td></tr>';
		 		addProductModeHtml += '<tr><td>*标题</td><td class = "_name">  </td></tr>' ;
				addProductModeHtml += '<tr><td>*年代</td><td class = "_year"> </td></tr>' ;
				addProductModeHtml += '<tr><td>*详情 :</td><td class = "_intro"><textarea  name="RichTextArea"></textarea></td></tr>' ;
	  			 addProductModeHtml += '<tr><th colspan = "2" ><p style = "line-height:30px;" > <span id = "msg_error" style = "font-size:10px;" class = "warning" ></span> </p><button class="btn_edit_submit btn btn-success" >确认提交  </button></th></tr></table>' ;
				 addProductModeHtml += '</div>' ; 
		 		 $(".modal-body").html( addProductModeHtml);
		 		editorRichTextArea = CKEDITOR.replace('RichTextArea', {/*toolbar : 'MyToolbar'*/});
		}
		 
		 $(".modal-title").html( editDefaultVal.modal.title );
		 $(".modal-title").attr( "data-req" , editDefaultVal.modal.req );
		 $(".modal-title").attr( "data-id" , editDefaultVal.modal.id );
	
	   //编辑框赋值
		var currenthtml = '' ;
		currenthtml +=  '<form style = "width:150px ;position:relative;" action="'+upimgAction+'" id="form_pic" name="form_pic" encType="multipart/form-data"  method="post" target="hidden_frame"  >' ;
		if( editDefaultVal.pic == '/images/admin/iconfont-shangchuantupian.png'){
			currenthtml += '<img class = "add_img" data-url="" src = "/images/admin/iconfont-shangchuantupian.png"> ';
			currenthtml += ' <input type="file" data-url = "" class="imgfile" name="uploadFile" style="opacity: 0; position: absolute; width: 100%;height:100%;top:0;left:0;" />';
		}else{
			currenthtml += '<img class = "add_img" data-url="'+editDefaultVal.pic+'" src = "'+imageDomain+editDefaultVal.pic+'"> ';
			currenthtml += ' <input type="file" data-url = "'+editDefaultVal.pic+'" class="imgfile" name="uploadFile" style="opacity: 0; position: absolute; width: 100%;height:100%;top:0;left:0;" />';
		}
 		currenthtml +=  '<iframe name="hidden_frame" id="hidden_frame" style="display: none;"></iframe>';
		currenthtml += ' </form> ';
		$('#edit ._pic ').html(currenthtml);
		currenthtml = '' ;
		currenthtml += '<form style = "width:150px ;position:relative;"  action="'+upimgAction+'" id="form_pic_big" name="form_pic_big" encType="multipart/form-data"  method="post" target="hidden_frame"  >' ;
		if( editDefaultVal.pic_big == '/images/admin/iconfont-shangchuantupian.png'){
			currenthtml += '<img class = "add_img"  src = "/images/admin/iconfont-shangchuantupian.png"> ';
			currenthtml += ' <input type="file"  class="imgfile"  name="uploadFile" style="opacity: 0; position: absolute; width: 100%;height:100%;top:0;left:0;" />';
		}else{
			currenthtml += '<img class = "add_img"   data-url="'+editDefaultVal.pic_big+'"  src = "'+imageDomain+editDefaultVal.pic_big+'"> ';
			currenthtml += ' <input type="file" data-url = "'+editDefaultVal.pic_big+'" class="imgfile"  name="uploadFile" style="opacity: 0; position: absolute; width: 100%;height:100%;top:0;left:0;" />';
		}
		currenthtml +=  '<iframe name="hidden_frame" id="hidden_frame" style="display: none;"></iframe>';
		currenthtml += ' </form> ';
		$('#edit ._pic_big ').html(currenthtml);
		$('#edit ._name').html('<input value = "'+editDefaultVal.name+'" class="form-control" placeholder="添加标题">');
		$('#edit ._year').html('<input value = "'+editDefaultVal.year+'"  class="form-control" placeholder="如：2015">  ');
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
		for(var  i in Product_Parm_ArreaList["about"])
		{
			currenthtml += '<tr><td > '+Product_Parm_ArreaList["about"][i]+' :</td><td class = "_'+i+'" id = "'+i+'">' ;
			currenthtml += '<div><ul class = "selected_list" data-parent= "'+i+'">' ;
			currenthtml += '<i class ="mymode"> <i class = "glyphicon glyphicon-folder-open" ></i>&nbsp;选择</i>' ;
  		 	
  		 	for(var k in  editDefaultVal[i])
			{
 				var _thisArr = getProduct_Parm_ArreaList(i,editDefaultVal[i][k]);
			 	if(_thisArr){
			 		var _tagsname = _thisArr.name    ;
				 	if(i == "norms")
				 	{
				 		_tagsname = _thisArr.value ;
				 	}
			 	 	if(_tagsname !== "")
				 	{
				 		currenthtml += "<li  class = 'status_color_"+_thisArr.status+"' data-status='"+_thisArr.status+"'  data-id = '"+_thisArr.id+"'>"+_tagsname+"</li>"
				 	}
			 	}
			 }
			currenthtml +=' </ul></div> ' 
		 	currenthtml += ' </ul></div><div  class = "dynamicDiv"  ><ul class ="EN_serch"  data-parent= "'+i+'" >' ;
		 	var Product_Parm_ArreaListI = Product_Parm_ArreaList[i] ;
			if(i=="norms")
			{
				for(k in  Product_Parm_ArreaList[i]){
					if(k == 0 )
					{
						currenthtml += '<li class = "current" this-in ='+k+' data-id = '+Product_Parm_ArreaList[i][k].id+' >'+Product_Parm_ArreaList[i][k].name+'</li>' ;
					}else
					{
						currenthtml += '<li  this-in ='+k+' data-id = '+Product_Parm_ArreaList[i][k].id+' >'+Product_Parm_ArreaList[i][k].name+'</li>' ;
					}
				}
				Product_Parm_ArreaListI = Product_Parm_ArreaList[i][0]["norms_value"] ;
			}else if(i=="cartoon" || i=="firm")
			{
				currenthtml += _seachEN[0]  ;
			}
			currenthtml += '<p style ="clear: both;"></p></ul>' ;
			currenthtml += '<ul  class = "seach_list" this-in = 0   data-parent= "'+i+'">' ;
			//currenthtml +='<span style = "">  添加  </span> '
			for(var j in Product_Parm_ArreaListI)
			{
				var value  ;
				if(i=="norms"){
					value =  Product_Parm_ArreaListI[j].value ;
				}else{
					value =  Product_Parm_ArreaListI[j].name
				}
				if( !isIncude(i,j)){
					currenthtml +='<li class="status_color_'+Product_Parm_ArreaListI[j].status+'" data-id="'+ Product_Parm_ArreaListI[j].id +'" >'+ value +'</li>';
				}
			}
			currenthtml += '</ul><p style= "border-top:1px solid #eee;background:#fafafa; padding:3px;color:#aaa;"><s>&nbsp;加删除线 表示此项已禁用&nbsp; </s></p></div></td></tr>' ;
		}
		$('#edit ._others').html( currenthtml );
	}
