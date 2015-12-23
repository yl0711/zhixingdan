(function() {

    CKEDITOR.dialog.add("multiimg",
        function(a) {
            return {
                title: "批量上传图片",
                minWidth: "660px",
                minHeight:"400px",
                contents: [{
                    id: "tab1",
                    label: "",
                    title: "",
                    expand: true,
                    width: "420px",
                    height: "300px",
                    padding: 0,
                    elements: [{
                        type: "html",
                        style: "width:660px;height:400px",
                        html: '<iframe id="uploadFrame" src="/js/admin/plug/edit/plugins/multiimg/image.html?editorImgAction='+editorImgAction+'&v=' +new Date().getSeconds() + '" frameborder="0"></iframe>'
                    }]
                }],
                onOk: function() {
                    var ins = a;
                     var num = window.imgs.length;
                    if(window.duiqi == undefined || window.duiqi == null){
                        window.duiqi = "none";
                    }
                    console.log(num)
                    for(var i=0;i<num;i++){
                        var imgHtml = "<p";
                        if("center" == window.duiqi){
                            imgHtml += " style=\"text-align:center\">";
                        }else{
                            imgHtml += ">";
                        }

                        imgHtml += "<img src=\"" + window.imgs[i].url + "\" ";
                        if("none" != window.duiqi && "center" != window.duiqi){
                            imgHtml += "style=\"float: " + window.duiqi + ";\"/>";
                        }else{
                            imgHtml += "/>";
                        }
                        imgHtml += "</p>";
                        ins.insertHtml(imgHtml);
                        console.log(imgHtml)
                    }
                    //console.log(98789)
                    
                    //window.imgs = new Array();
                    //点击确定按钮后的操作
                    //a.insertHtml(5454);
                },

                onShow: function () {
                    console.log($(this).icon)
                    document.getElementById("uploadFrame").setAttribute("src","/js/admin/plug/edit/plugins/multiimg/image.html?editorImgAction="+editorImgAction+"&v=" +new Date().getSeconds() );
                }
            }
        })
})();