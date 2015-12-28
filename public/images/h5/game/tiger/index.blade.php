<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<meta id="viewport" name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,initial-scale=1.0,user-scalable=no">
		<title>Game/tiger</title>
		
	</head>
	<style>
		*{padding: 0;margin: 0;}
		.Btn_i.{
			-moz-box-shadow:5px 5px 5px red inset;               /* For Firefox3.6+ */
			-webkit-box-shadow:5px 5px 5px red inset;            /* For Chrome5+, Safari5+ */
			box-shadow:5px 5px 5px red inset;                    /* For Latest Opera */
			}
		#main {width: 100%; height:100%;position: absolute;}
		#main .p_parent li{display: block;width: 100%;height: 100%; position: absolute;overflow: hidden;}
		#main .Btn_i{position: absolute;}
		.p_2_1 input {background: none;border: none;}
		
	</style>
	<script src="../js/jquery-1.8.3.min.js"></script>
	<script>
		
	</script>

	<body style="background: #eee;">
		<article id="main"  >
			<h3 style="display: block;position: absolute;top:50%;width:100%;text-align: center; ">加载中....</h3>
			<div class="evalWH" style="position: absolute;width:100%;height:100%;z-index: -9999;opacity: 0;"></div>
			<div class = "topdiv" style = " position :absolute;z-index:101 ;width: 100%;height: 100%;display: none;" >
				<div class="mask" style="z-index: 100; top:0; position: absolute; width: 100%;height: 100%;background: rgba(0,0,0,.7);overflow: hidden;"></div>
				<div class="rule" style="position: absolute;top:25%;display:none ;z-index: 101;">
					<img src="img/rule.png" style = " width: 100%;" >
					<i class="Btn_i " data-target = "maskHide"  style="width:11%;height:19%; top:2%;left:84%;"></i>
				</div>
				<div class="share" style="position: absolute;top:0%;width:48%;right:0 ;display: none;z-index: 101;">
					<img src="img/share.png" style = " width: 100%;" >
				</div>
				<div class="error" style="position: absolute;top:25%;display:none ;z-index: 101;">
					<img src="img/error.png" style = " width: 100%;" >
					<h3 style = "display: block;position: absolute;top:47%;left:48%;"> </h3>
					<i class="Btn_i "  data-target = "maskHide" style="width:44%;height:24%; top:62%;left:28%;"></i>
				</div>
				<div class="success" style="position: absolute;top:25%;display: none;z-index: 101;">
					<img src="img/success.png" style = " width: 100%;" >
					<i class="Btn_i " data-target = "edit" style="width:44%;height:24%; top:62%;left:28%;"></i>
				</div>
				<div class="fail" style="position: absolute;top:25%;display:none;z-index: 101;">
					<img src="img/fail.png" style = " width: 100%;" >
					<i class="Btn_i " data-target = "share2"  style="width:20%;height:22%; top:65%;left:19%;"></i>
					<i class="Btn_i " data-target = "download"  style="width:20%;height:22%; top:65%;left:41%;"></i>
					<i class="Btn_i " data-target = "care3WY"  style="width:20%;height:22%; top:65%;left:62%;"></i>
				</div>
			</div>
			<ul class="p_parent">
					
				<li class="p_2"  style="display: ;"  >
					<div class = "p_2_1"  style="position: relative;overflow: hidden;" >
						<img src="img/P3.png" class = "p_2_1_1"  style = "position: absolute;width: 100%;top:50%;" >
						<i class="Btn_i " data-target = "ready"  style="width:43%;height:7%; top:62%;left:8%;"></i>
						<i class="Btn_i " data-target = "care3WY"  style="width:27%;height:7%; top:70%;left:8%;"></i>
						<i class="Btn_i " data-target = "share"  style="width:13%;height:19%; top:69%;left:37%;"></i>
						<i class="Btn_i " data-target = "download"  style="width:27%;height:11%; top:79%;left:8%;"></i>
						
						<i class="Btn_i " data-target = "submit"  style="width:37%;height:27%; top:62%;left:55%;"></i>
						<input type="" name="" id="name" value="" style="position: absolute;top:32.5%;left:38%;line-height:26px;width:46%;" />
						<input type="" name="" id="tel" value="" style="position: absolute;top:37%;left:38%;line-height:26px;width:46%;" />
						<input type="" name="" id="address" value="" style="position: absolute;top:42%;left:38%;line-height:26px;width:46%;" />
						<input type="" name="" id="NID" value="" style="position: absolute;top:50%;left:38%;line-height:26px;width:46%;" />
					
					</div>
				</li>
				
				<li class="p_1"  style="display: ;"  >
					<div class = "p_1_1"  style="position: relative;overflow: hidden;background: #f2e7e3;" >
							<div class = "p_1_1_award1"　 style="position: absolute;width:13%; top:48.5%;left:25%;background:url(img/award.png);background-size: 100% auto;"></div>
							<div class = "p_1_1_award2"　 style="position: absolute;width:13%; top:48.5%;left:40%;background:url(img/award.png);background-size: 100% auto;"></div>
							<div class = "p_1_1_award3"　 style="position: absolute;width:13%; top:48.5%;left:55.5%;background:url(img/award.png);background-size: 100% auto;"></div>
							<img src="img/P2.png" class = "p_1_1_1"  style = "position: absolute;width: 100%;top:50%;" >
							<i class="Btn_i " data-target = "gamestart"  style="width:14%;height:7%; top:39%;left:81%;"></i>
							<h4 style="display: block;position: absolute;top:80%;left:26%;width:34%;text-align: center; "></h4>
					
					</div>					
				</li>
				<!--开始-->
				<li class="p_start" style="display:;" >
					<div class = "p_start_1"  style="position: relative;overflow: hidden;" >
						<img src="img/P1.jpg" class = "p_start_1_1"  style = "position: absolute;width: 100%;top:50%;" >
						<i class="Btn_i " data-target = "rule"  style="width:43%;height:7%; top:62%;left:8%;"></i>
						<i class="Btn_i " data-target = "care3WY"  style="width:27%;height:7%; top:70%;left:8%;"></i>
						<i class="Btn_i " data-target = "share"  style="width:13%;height:19%; top:69%;left:37%;"></i>
						<i class="Btn_i " data-target = "download"  style="width:27%;height:11%; top:79%;left:8%;"></i>
						<i class="Btn_i " data-target = "ready"  style="width:37%;height:27%; top:62%;left:55%;"></i>
					</div>
					
					
				</li>
  				
			
			</ul>
		</article>
	</body>
	<script>
	
		/*奖品宽度 ，高度 ，宽高比 ： 168 756，168/189*/
			//求开始值
		
		
		 /*计时器*/
		var  awards_log= "我的中奖记录" ,awards,awards_num=0 ,awards_y, award_height , award_start , award_end , speed ,speed_n , times=0 ,game_times,timeout ;
		 
		$(function(){
			winHeight =  $(".evalWH").height();
			winWidth =  $(".evalWH").width();
			if(winWidth>winHeight){
				$('#main').css('max-width',(winHeight*32/48)+"px");
				winWidth =  $(".evalWH").width();
			}
			award_height = ($('.p_1_1_award1').width())*189/168  ;
			$('.p_start_1,.p_1_1,.p_2_1').css('height',winHeight+'px');
			$('.p_1_1_award1,.p_1_1_award2,.p_1_1_award3').css('height',(winWidth/640*1259*0.18) +'px');
			$('.p_1_1_award1,.p_1_1_award2,.p_1_1_award3').css('background-position-y',(award_height*0.75)+'px');
			/*640*1259*/
			$('.p_start_1_1,.p_1_1_1,.p_2_1_1').css('margin-top',-winWidth/640*1259*0.49+'px');
			
			$('.Btn_i').on('click',function(){
				var thistarget = $(this).attr('data-target') ;
				switch(thistarget){
					case 'maskHide' :
						maskfadeOut();
					break;
					
					case 'rule' :
						maskfadeOut();
						$('.topdiv').fadeIn() ;
						$('.rule').fadeIn() ;
					break;
					case 'share' :
						maskfadeOut();
						$('.topdiv').fadeIn() ;
						$('.share').fadeIn() ;
					break;
					case 'share2' :
 						$('.fail').fadeIn() ;
						$('.share').fadeIn() ;
					break;
					
					case 'care3WY' :
						location.href = "http://mp.weixin.qq.com/s?__biz=MzAwOTU1MTQxMA==&mid=400594441&idx=1&sn=8fc9a7919d09989f52a2332e45ceb12f&3rd=MzA3MDU4NTYzMw==&scene=6#rd" ;
 					break;
					case 'download' :
						location.href = "http://a.app.qq.com/o/simple.jsp?pkgname=com.nbclub.nbclub&g_f=991653" ;
					break;
					
					case 'ready' :
						maskfadeOut();
						$('.p_1 h4').html(awards_log) ;
						$('.p_start,.p_2').fadeOut() ;
						$('.p_1').fadeIn() ;
						
					break;
					case 'gamestart' :
						maskfadeOut();
						gamestart();
					break;
					case 'edit' :
						maskfadeOut();
						$('.p_start,.p_1').fadeOut() ;
						$('.p_2').fadeIn() ;
					break;
					case 'submit' :
						console.log("提交");
						if($('#tel').val()!==""){
							$('body').html('<br>以下信息请出设计图：<br>恭喜您已经成功领取到一等奖：XXXXXX<br>活动结束后,牛社掌柜会尽快通知您,请保持电话畅通')
						}else{
							alert('电话为必填项')
						}
					break;
					
					
					
				}
			});
			$('.mask').on('click',function(){
				maskfadeOut();
			});
			$('.p_1 h4').on('click',function (){
				if($(this).html() !== "我的中奖记录"){
					maskfadeOut();
					$('.p_start,.p_1').fadeOut() ;
					$('.p_2').fadeIn() ;
				}
			});
			
			
			
		})
		var n = 0 ;
		for(i=0;i<400;i++){
			n+=i
			console.log(n)
		}

		
		function gamestart (){
			if(times == 0 ){
				timeout = false; //启动及关闭按钮
				if(awards_num == 0 ){
					awards_num =[  Math.ceil(Math.random()*4), Math.ceil(Math.random()*4), Math.ceil(Math.random()*4)]  ;
					game_times = 0 ;
					console.log('请求数据获取抽奖号码:' +awards_num )
				}
				if((3-game_times)>0){
					awards = [award_height*0.75 ,award_height*3.75 ,award_height*2.75 ,award_height*1.75 ] ;
					speed = [[40,50,60 ],[24465,12475,17970]] ;
					speed_n = 0.1 ;
					awards_y = [ speed[1][0]+awards[awards_num[0]-1] ,awards[awards_num[1]-1]+speed[1][1] ,awards[awards_num[2]-1]+speed[1][2] ];
					times = 1 ;
					console.log(times) ;
					setTime() ;
					
				}else{
					gameout()
				}
			}
		}
		//机会用光
		function gameout(){
			$('.topdiv').fadeIn() ;
			$('.fail').fadeIn() ;
		}
		
		/*隐藏所有浮层*/
		function maskfadeOut(){
			$('.topdiv').fadeOut() ;
			$('.rule').fadeOut() ;
			$('.error').fadeOut() ;
			$('.success').fadeOut() ;
			$('.fail').fadeOut() ;
			$('.share').fadeOut() ;
		}
		
		function setTime()
		{
		  if(timeout) return;
		  timekeeper();
		  setTimeout(setTime,10); //time是指本身,延时递归调用自己,100为间隔调用时间,单位毫秒
		}
		
		
		function timekeeper(){
			for(var n = 0;n<3;n++){
				if(speed[0][n]>speed_n*0.5){
					$('.p_1_1_award'+(n+1)).css('background-position-y',awards_y[n] +'px');
					speed[0][n] -= speed_n ;
					awards_y[n]-= speed[0][n];
				}
			}
			if(speed[0][2]<speed_n*0.5 ){
				//游戏结束
				game_times ++ ;
				
				if(awards_num[0] == awards_num[1] && awards_num[0] == awards_num[2]){
					//奖成功 
					$('.topdiv').fadeIn() ;
					$('.success').fadeIn() ;
					
					switch(awards_num[0]){
						case 1:awards_log = "一等奖" ;
						break;
						case 2:awards_log = "二等奖" ;
						break;
						case 3:awards_log = "三等奖" ;
						break;
						case 4:awards_log = "四等奖" ;
						break;
					}
					$('.p_1 h4').html(awards_log) ;
					awards_num =[  Math.ceil(Math.random()*4), Math.ceil(Math.random()*4), Math.ceil(Math.random()*4)]  ;
				}else{
					//奖失败
					if((3-game_times)>0){
						$('.topdiv').fadeIn() ;
						$('.error').fadeIn() ;
						$('.error h3').html(3-game_times) ;
					}else{
						gameout();
					}
					awards_num = [1,1,1] ;	
				}
				
				times = 0 ;
				timeout = true; //启动及关闭按钮
				console.log("//游戏结束:上传一条访问记录") ; 
				//awards_num = 0 ;
				//***********上传一条访问记录
				
			}
		}
	</script>
	
</html>
