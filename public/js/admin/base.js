/*
login居中显示
*/
$(function(){
 
	var id = $('#login'),
		body = $('body'),
		oLi = $('.slide-item');
	// 调用方法
	loginView.center(id);
	loginView.bodyHeight(body);
	loginView.menuFn(oLi);
	baseInit.init() ;
	// 菜单添加滚动条
	$(window).on('resize',function(){
		$('.slide-l,.content-r').css({
			'overflow-y':'scroll',
			'height':$(window).height()-$('#header').height()-6
		});
	});
	

	//临时判断左侧菜单是否被选中
	$('.slide-l .slide-item ol li').each(function(){
		
		if( (location.href).indexOf( $(this).find('a').attr('href').replace('http://','') ) >= 0){
			var oFirst = $(this).closest('.slide-item');
			oFirst.attr({'show':1}).find('ol').show();
			oFirst.find('i').addClass('up');
			$(this).find('a').addClass('on');
 		}
	})

})

var baseInit = {
  	 /*草稿保存*/	
  	 draftSave : {
  	 	/*是否需要保存草稿*/
		isDraft : false,
		/*要保存的value*/
		value : '', 
		/*要保存的key*/
		key: 'null' ,
		draftSetAction: null ,
		draftGetAction: null ,
		/*配置校验数据的函数*/
		getDraft : function (draftGetAction,key,fun){
			this.draftGetAction = draftGetAction ;
			ajax_request(this,this.draftGetAction ,'POST',{key:key},function(_this,msg){
	 			if(msg.status == 1 &&  msg.data &&  msg.data.length>0){
	 				success = baseInit.draftSave.value = eval('('+(msg.data[0].value)+')')  ;
 	 				$('.draft-ico').show();
	 			}else{
	 				$('.draft-ico').hide();
 	 				success =  false;
 	 			}
	 			//console.log(msg.info);
	 			if(fun){fun(_this,success) ;}
			},function(_this,msg){ 
				/*获取草稿当前失败*/
				//console.log("获取草稿当前失败：");
				if(fun){ fun(_this,false) };
	 		});
		},
 		/*草稿保存：请求保存*/
		saveFun : function (checkValueFun,param) {
 			var _value = checkValueFun(param) ;
   	 		if(
	 			typeof _value=="object"
	 			//&& this.value != _value.toString()
 	 		){
 	 			//判断与上一次自动保存时候数据是否一致
  	 			var toStr = '' ;
	 			for(var i in _value){
	 				toStr += _value[i]
	 			}
 	 			if( this.value == toStr){
 	 				return false;
 	 			}
				ajax_request(this, this.draftSetAction , "post", {key:this.key,value:_value}  , function(_this,msg){
					_this.value = toStr ;
 				//	alert(new Date().toLocaleString()+' \n 已经自动为您保存一条草稿'+ '\n 本草稿会在正式提交该条数据后清除');
					$('.draft-ico').show();
					console.log('已经保存:'+_this.value) ;
				},function(_this,msg){
					//console.log('error:自动保存失败') ;
				}) ;
			}else{
				//	console.log('数据不符合自动保存')
			}
		},
		timer :	null ,
		start : function (checkValueFun ,param, draftSetAction , key){
			this.draftSetAction = draftSetAction ;
			this.key = key ;
			t=0
 			this.timer = setInterval(
 				function(){
 					t++ ;
					if(t==20){
						baseInit.draftSave.close();
					}
					baseInit.draftSave.saveFun(checkValueFun,param);
				},20000) ;
			$('.modal .close').click('click',function(){
				baseInit.draftSave.close();
			})
 		} ,
		
		close : function(){
			clearInterval(this.timer) 
		}
 	 } ,
	 
 	/*搜索区域--每页显示条数设置*/
	seachSubmitByPageSize:function(){
  		$('.pageSize').on('change',function(){
 			Loading('show');
 			/*提交的地址*/
 			var _a = $(this).attr('action') ; 
 			var _v = $(this).val() ; 
 			if ( isNaN( _v ) ) {
 				$(this).val( parseInt( _v ) );
 			}
 			if (_a) {
 				$('#form_seach').attr('action',_a) ;
 				return form_seach.submit();
 			}else if ( seachByStatusAction ) {
  				location.href = seachByStatusAction + "?&pageSize="+$('.pageSize').val();
 			}else{
 				/*如果没有搜索框 ，则在原地址栏后追加 pageSize*/
   				var _url = location.href  ;
   				var regPageSize = new RegExp("(^|&)pageSize=([^&]*)(&|$)");
			 	var r = window.location.search.substr(1).match(regPageSize);
   				if(r!=null){
   					location.href = _url.replace(r[0],'pageSize=')+$('.pageSize').val();
   				}else{
   					location.href = _url + "?&pageSize="+$('.pageSize').val();
   				}
 			}
 			
 			Loading('hide');
		}) 
 	},
  	/*按照数据状态（是否已经开启）检索 */
	seachByStatus :function(ele){
		$(".seachByStatus").on("change",function(){
			Loading('show');
			/*页面跳转  $(this).val() */
			location.href = ele +"/"+ $(this).val() + "?pageSize=" +  $('.pageSize').val() ;
			Loading('hide');
		});
	},
 	//修改点赞数  修改推荐值
	byModifyStatusAction:function(_this,ele){
  		
 		switch(_this){
 			/***修改点赞数***/
			case 'like_count':
 				$(".like_count").live("click",".like_count,",function(){
					if(!$(this).attr('history')){$(this).attr('history',$(this).val())};
				});
   				$(".like_count").live("change",function(){
 					Loading('show');
					//modifyByThis(this);
					var reg = /[^\d]/g;
					if($(this).val() == $(this).val().replace(reg)){
						ajax_request(this, ele , 
							"post",
							{
								id:$(this).closest("tr").attr("data-id"),like_count:$(this).val()
							} ,
							function(_this,msg){
								Loading('hide');
								GhostMsg("已赞："+$(_this).val());
							},
							function (_this,msg){
								Loading('hide');
								$(_this).val($(_this).attr('history')) ;
							}
						);
					}else{
						Loading('hide');
						GhostMsg("请填写数字");
						$(this).val($(this).attr('history')) ;
					};
				});
 			break;
			/***修改推荐值***/
			case 'recommend':
				$(".recommend").live("click",function(){
 					if(!$(this).attr('history')){$(this).attr('history',$(this).val())};
				});
				$(".recommend").live("change",function(){
					//modifyByThis(this);
					Loading('show');
					var reg = /[^\d]/g;
					if($(this).val() == $(this).val().replace(reg)){
						ajax_request(this, ele , 
							"post",
							{
								id:$(this).closest("tr").attr("data-id"),recommend:$(this).val()
							} ,
							function(_this,msg){
								Loading('hide');
								GhostMsg("已推荐:"+ $(_this).val() );
							},
							function (_this,msg){
								Loading('hide');
								$(_this).val($(_this).attr('history')) ;
							}
							
						);
					}else{
						Loading('hide');
						GhostMsg("请填写数字");
						$(this).val($(this).attr('history')) ;
					};
				})
			break;
		}
	},
	
	//全局初始化
 	init:function(){
		this.seachSubmitByPageSize();
		//this.seachByStatus(seachByStatusAction);
 	}
}



var loginView = {
	center:function(id){
		id.css({
			'top':($(window).height()-id.height())/2
		});
	},
	bodyHeight:function(body){
		body.css({
			'height':$(window).height()
		});
	},
	menuFn:function(ele){
		
		ele.each(function(){
			$(this).on('click',function(){
				var attr = $(this).attr('show'),
					oI = $(this).find('i'),
					olBox = $(this).find('ol');
				if (attr == true) {
					$(this).attr({'show':0});
					olBox.hide();
					oI.removeClass('up');
				}else{
					$(this).attr({'show':1});
					olBox.show();
					oI.addClass('up');
				};
			});
			$(this).find('ol').on('click',function(e){
				e.stopPropagation();
			});
			var oA = $(this).find('a');
			oA.on('click',function(){
				$(this).addClass('on');
				$(this).parent().siblings('li').find('a').removeClass('on');
			});
		});
	}
}

/*
*ｃｈｅｎｓｈａｏ
*创建bootstrap Modal模态框
* */


function modalView(view, big, title){
	if ('' == title) {
		title = '标题';
	}
	var myModal = document.getElementById("myModal");
	if(! myModal ){
		myModal = "" ;
		myModal +=' <!-- Modal模态框 --> ' ;
		myModal +=' <div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" aria-hidden="true">' ;
		if(big){
			myModal +=' <div class="modal-dialog  modal-lg">' ;
		}else{
			myModal +=' <div class="modal-dialog">' ;
		}
		myModal +='  <div class="modal-content">' ;
		myModal +='  <div class="modal-header">' ;
		myModal +='  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>' ;
		myModal +=' <h4 class="modal-title" data-dismiss="modal" >' + title + '</h4>' ;
		myModal +='  </div>' ;
		myModal +=' <div class="modal-body">' ;
		myModal +=' </div>' ;
		myModal +=' <div class="modal-footer">' ;
		//myModal +=' <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>' ;
		myModal +='  </div> </div></div></div></div>' ;
		$('body').append(myModal);
	}
	if(view == 'hide'){ baseInit.draftSave.close(); }
	$('#myModal').modal(view);
}


/**
 * var map =  
 * @author king
 * @param url
 */
var host = "" ;
function ajax_request(_this, url, method, params_map, func ,errorfunc,async) {
	/*var params = "";
	for ( var k in params_map) {params += k + "=" + params_map[k] + "&";}*/
/*	console.log("["+method+"]request -> "+url);
*/
	var _async = async == undefined ? true : async ;
	$.ajax({
		url :host + url,
		dataType : "json",
		type : method,
		cache:false,
		async : _async ,
		//timeout: 1000,
		beforeSend: function(){
			//Loading('show') ;
		}, //加载执行方法
		data : params_map,
		success : function(msg) {
			Loading('hide') ;
			if(func){func(_this, msg);}
		},
		error : function(msg) {
			Loading('hide') ;
			console.log('[ajax]:error:'+url)
			if(errorfunc){
				errorfunc(_this, msg) ;
			}
		}
	});
}


//@ChenShao     获得地址栏参数

function GetQueryString(name){
	 var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
	 var r = window.location.search.substr(1).match(reg);
	 if(r!=null)return  unescape(r[2]); return null;
}
 


//获取当前鼠标地址
function mousePos(e){
	var x,y;
	var e = e||window.event;
	return {x:e.clientX+document.body.scrollLeft+document.documentElement.scrollLeft,
	y:e.clientY+document.body.scrollTop+document.documentElement.scrollTop};
};
var LoadingHtml ; 
function Loading(dis,fun){
	if(!LoadingHtml){
		LoadingHtml = "<span id = 'loading' style = 'display:none;position:fixed;top:40%;' ><img src = '/images/admin/iconfont-load.png' style = ''></span>"
		$('body').append(LoadingHtml);
	}
	if(dis == "hide" ){
		if(fun){$('#loading').hide(fun());}else{$('#loading').hide();}
	}else{
		if(fun){$('#loading').show(fun());}else{$('#loading').show();}
	}
}
/**
 * 模仿android里面的Toast效果，主要是用于在不打断程序正常执行的情况下显示提示数据 调用方法：new
 * Toast({context:$('body'),message:'Toast效果显示'}).show();
 * 
 * @param config
 * @return
 */
 
//幽灵信息
function GhostMsg(Msg){
	new Toast({
			message : Msg
	}).show();
}

var Toast = function(config) {
	this.context = config.context == null ? $('body') : config.context;// 上下文
	this.message = config.message;// 显示内容
	this.time = config.time == null ? 3000 : config.time;// 持续时间
	this.left = config.left;// 距容器左边的距离
	this.top = config.top;// 距容器上方的距离
	this.init();
};
var msgEntity;
Toast.prototype = {
	// 初始化显示的位置内容等
	init : function() {
		$("#toastMessage").remove();
		// 设置消息体
		var msgDIV = new Array();
		msgDIV.push('<div id="toastMessage">');
		msgDIV.push('<span>' + this.message + '</span>');
		msgDIV.push('</div>');
		msgEntity = $(msgDIV.join('')).appendTo(this.context);
		// 设置消息样式
		var left = this.left == null ? this.context.width() / 2
				- msgEntity.find('span').width() / 2 : this.left;
		var top = this.top == null ? (document.body.clientHeight/2 + 'px')
				: this.top;
		msgEntity.css({
			position : 'fixed',
			top : top,
			'z-index' : '9999',
			left : left,
			'background-color' : 'black',
			color : 'white',
			'font-size' : '18px',
			padding : '10px',
			'border-radius' : '4px',
			margin : '10px'
		});
		msgEntity.hide();
	},
	// 显示动画
	show : function() {
		msgEntity.fadeIn(this.time / 2);
		msgEntity.fadeOut(this.time / 2);
	}
};


