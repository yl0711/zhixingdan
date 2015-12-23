//分页js power by newsnow.com.cn 大飞
function re_url(val,maxval,url_1,url_2,url_3){
	var i = parseInt(val);
	if( isNaN(i) || i>maxval || i<=0 ){
		alert('友情提示：错误的页码范围：'+val+'，请检查！！！');
		return false;
	}else{ 
		document.location.href = url_1 + i + url_2 + url_3;
		//'".$base_url_addtion.$base_url_addtion_str."';
		return true;
	}
}