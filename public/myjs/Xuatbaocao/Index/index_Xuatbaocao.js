


var xuatbaocao = function () {



	return {

		init : function () {
			$("div.Years").datepicker();
			$("div.months").datepicker();
			
			$("button.xuatcaobao").live('click',function(){
				
				
				
				$tr =  $(this).parents('tr')[0];
				
				var Mau = $('td',$tr)[2].innerHTML.trim();
				var KyThue = $('input',$tr).val().trim();
				$('img',$tr).css('display','inline');
				
				
				
				if(KyThue.length == 0 ){
					DialogTable.showThongBaoUnlimit('Thông báo','Vui lòng chọn kỳ thuế !');
					$('img',$tr).css('display','none');
					return ;
				}
				$.post(baseUrl("Xuatbaocao/index/xuatbaocao"),{Mau : Mau, KyThue : KyThue},function(json){
					
					DialogTable.showThongBaoUnlimit('Thông báo',json.messenger);
					// new true - download file ve
					if(json.kq==true){
						$.fileDownload(baseUrl("application/Service/downloadFile"), {
							successCallback : function(url) {
							},
							failCallback : function(responseHtml, url) {
							},
							httpMethod : "GET",
							data : 'filename='+json.obj
						});
					}
					$('img',$tr).css('display','none');
				},'json');
			});
		}

	};
}();
jQuery(document).ready(function () {

	xuatbaocao.init();
	
});
