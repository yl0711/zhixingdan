$(function(){
	// 点击修改按钮弹框
	model();
	
})
var model = function(){
	$(".modify").on("click",function(){
		 AddFirmMode()
		  $(".modal-title").html( "修改" );
	});
	
	$(".add-ico").on("click",function(){
		 $(".modal-title").html( "添加" );
		 AddFirmMode()
	});
}



var  addProductModeHtml;
	function addProductMode(){
		if(!addProductModeHtml){
			addProductModeHtml = ''
			addProductModeHtml += '<div  id= "product_edit"  style="max-width: 700px; margin: 0 auto;border-left: 1px solid #ddd; " class="table-con">' ;
			addProductModeHtml += '<table class ="prodict_edit"> <thead> <tr> <th colspan = "2" > 规格参数 </th></tr></thead>' ;
			addProductModeHtml += '<tbody ><tr><td style = "width:150px ;">* 名称</td><td> <input class="form-control" placeholder="添加名称">  </td></tr>' ;
			addProductModeHtml += '<tbody ><tr><td style = "width:150px ;">* 首字母</td><td> <input class="form-control" placeholder="首字母">  </td></tr>' ;
			addProductModeHtml += '<tr><td style = "width:150px ;">* 类型 </td><td> <ul class = "type_select"　> <li>尺寸</li> <li class = " btn-success" >材质</li> <li>系列</li>  <li>比例</li>  </ul> </td></tr>'
			addProductModeHtml += '<tr><td style = "width:150px ;">* 值</td><td> <input class="form-control" placeholder="如：PVC">  </td></tr>'
			addProductModeHtml += '<tr><td> 备注 </td><td><textarea class="form-control" rows="3"  placeholder="添加说明" ></textarea></td></tr>' ;
			addProductModeHtml += '<tr><th colspan = "2" ><button class="btn btn-default" style = "margin-right: 20px;">  清空  </button><button class="btn btn-success" >确认提交  </button></th></tr></tbody></table>' ;
			addProductModeHtml += '</div>' ;
			 $(".modal-body").html( addProductModeHtml );
		}
		return  bindProductModeHtml() ;

	}



function AddFirmMode(){
		
		modalView('show');
		addProductMode() ;
		
}
//绑定产品基本信息
	function bindProductModeHtml(){
		/*选择*/
		$(".type_select").on("click","li",function(){
			$(this).siblings("li").removeClass("btn-success").end().addClass("btn-success") ;
		});
		
	}