$(function(){

	var i =97;
	$('.join-btn').on('click',function(){
		i++;
		if(i<=100){
			$(this).find('i').html(i);
		}else{
			return false;
		}
	})

	$('.detailed-parameters').on('click','span',function(){
		var num = $(this).attr('show');
		if ( num == 0) {
			$(this).attr('show','1');
			$(this).siblings('.con').slideDown();
		}else if(num == 1){
			$(this).attr('show','0');
			$(this).siblings('.con').slideUp();
		};
	});

	// 跳转到出售列表
	$('.listbox li').eq(0).on('click',function(){
		location.href="app:///coding/1014/target/2";
	});

	// 跳转到求购列表
	$('.listbox li').eq(1).on('click',function(){
		location.href="app:///coding/1014/target/3";
	});

	// 跳转到个人中心
	$('.active-title dl').on('click',function(){
		var id = $(this).attr('data-id');
		console.log(id);
		location.href="app:///coding/1012/target/"+id;
	})



	detialView.fnLocation();
})

var detialView = {
	fnLocation:function(){
		var url = location.href;
		console.log(url);
		var encodeUrl = encodeURIComponent(url);
		console.log(encodeUrl);
		location.href='app:///coding/1011/callback/'+encodeUrl;
		console.log(location.href='app:///coding/1011/callback/'+encodeUrl)
	}
}