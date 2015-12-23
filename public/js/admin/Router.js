//接口
var Router = {};
//接口URL前缀
var Prefix = 'http://dadmin.nbclub.cc/';


// 上传图片
var upimgAction = Prefix + 'sell/uploadfile';

// 编辑器
var editorImgAction = Prefix + 'sell/editoruploadfile';

//文案修改
Router.officialdocs_modify = {
    url: Prefix + 'officialdocs/modify'
}


//用户管理
Router.member = {
    modifystatus: Prefix + 'member/modifystatus'
}



