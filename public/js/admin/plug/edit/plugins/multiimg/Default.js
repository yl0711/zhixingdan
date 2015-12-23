parent.imgs = new Array();
/***********************Flash事件*****************************/
/**
* 检查flash状态
* @private
* @param {Object} target flash对象
* @return {Boolean}
*/
function _checkReady(target) {
    if (typeof target !== 'undefined' && typeof target.flashInit !== 'undefined' && target.flashInit()) {
        return true;
    } else {
        return false;
    }
}

/**
* 创建一个随机的字符串
* @private
* @return {String}
*/
function _createString() {
    var prefix = 'mw__flash__';
    return prefix + Math.floor(Math.random() * 2147483648).toString(36);
}

/**
* 为传入的匿名函数创建函数名
* @private
* @param {String|Function} fun 传入的匿名函数或者函数名
* @return {String}
*/
function _createFunName(fun) {
    var name = '';
    name = _createString();
    window[name] = function () {
        fun.apply(window, arguments);
    };
    return name;
}
/***
反复判断Flash是欧加载完成,完成后为Flash添加回调函数..
*/
var interval = setInterval(function () {
    try {
        var flash = thisMovie("flash");
        if (_checkReady(flash)) {               //轮询flash的某个方法即可

            var callBack = [];
            callBack[0] = _createFunName(selectFileCallback);
            callBack[1] = _createFunName(exceedFileCallback);
            callBack[2] = _createFunName(deleteFileCallback);
            callBack[3] = _createFunName(StartUploadCallback);
            callBack[4] = _createFunName(uploadCompleteCallback);
            callBack[5] = _createFunName(uploadErrorCallback);
            callBack[6] = _createFunName(allCompleteCallback);
            callBack[7] = _createFunName(changeHeightCallback);
            thisMovie("flash").call('setJSFuncName', [callBack]);

            clearInterval(interval);
        }
    }
    catch (ex) {
    }
}, 20);

//获得Flash对象
function thisMovie(movieName) {
    if (navigator.appName.indexOf("Misrosoft") != -1) {
        return window[movieName];
    }
    else {
        return document[movieName];
    }
}
//事件
$(function () {
	
    $("#upload").live("click", function () { return upload(); });

});
function Setbtn(count) {
	console.log('Setbtn')
    if ($("#ddlAlbum").val() != "0") {
          if (count > 0) {
            $("#updisable").hide();
              $("#upload").show();
          }
          else {
            $("#updisable").show();
              $("#upload").hide();
          }
    }
    $("#curCount").val(count);
}
// 通过添加文件按钮新增的需要上传文件
function selectFileCallback(selectFiles) {
	console.log('selectFileCallback')
    var count = $("#curCount").val();
    count = parseInt(count) + selectFiles.length;
    Setbtn(count);
}
// 文件超出限制的最大体积时的回调
function exceedFileCallback(selectFiles) {
console.log('exceedFileCallback')
}
//被删除的文件: 用于控制上传按钮是否显示...
function deleteFileCallback(delFiles) {
	console.log('deleteFileCallback')
    var count = $("#curCount").val();
    count = parseInt(count) - delFiles.length;
    Setbtn(count);
}
//开始上传前执行的JS函数.
function StartUploadCallback(data) {
console.log('StartUploadCallback')
}
//上传单个文件后执行的JS函数.
function uploadCompleteCallback(data) {
	console.log('uploadCompleteCallback：单张上传完成')
	console.log(data)
    try {
        var info = eval("(" + data.info + ")");
        info && parent.imgs.push(info);
        var count = $("#curCount").val();
        count = parseInt(count) - 1;
        Setbtn(count);
    } catch (e) {alert(e)}
}

 
//上传失败后执行的JS函数.
function uploadErrorCallback(data) {
	console.log('uploadErrorCallback')
    if (!data.info) {
        alert("照片上传失败，请刷新后重试");
        window.location.reload();
    }
}
//全部完成上传后执行::定向到相册界面
function allCompleteCallback(data) {
	console.log('allCompleteCallback:全部上传完成')
//    alert("上传成功！" + parent.imgs);

//	    window.location.reload();
}
//改变Flash高度时执行
function changeHeightCallback(data) {
	console.log('changeHeightCallback')
}
//开始上传:添加参数：aid，表示相册， mark，表示水印，需要为每个照片都添加这两个参数。
function upload() {
	console.log('upload')
      var count = parseInt($("#curCount").val());
      for (var i = 0; i < count; i++) {
          thisMovie("flash").addCustomizedParams(i, { "aid": $("#ddlAlbum").val(), "mark": $("#mark").attr("checked") == true });
      }
    thisMovie("flash").upload();
		
} 
