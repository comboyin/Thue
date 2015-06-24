var EditableTable = function () {

	//key
	var _MaSoThue = "";

	return {

		init : function () {
			
			$("#ChonNganh").click(function(){
				MaNganhCu = $("span[class='MaNganhCu']").html();
				
				DialogTable.showFromUrl('get','laydanhsachnganh',{MaNganhCu:MaNganhCu},function(){
					checkboxs = $('#DialogTable input.check_item:checked').parents("tr");

					if (checkboxs.length == 1) {
						var MaNganh = $('td', checkboxs[0])[1].textContent;
						var TenNganh = $('td', checkboxs[0])[2].textContent;
						
						
						$("#DialogTable").modal("hide");
						
						$("input[name='MaNganh']").val(MaNganh);
						$("span.TenNganh").html(TenNganh);
						
						
					} else {
						alert("Vui lòng chọn ít nhất một !");
					}
				});
			});
			
			
			$('#formCapNhatNganh').submit(function (e) {
				MaNganh = $("input[name='MaNganh']").val();
				if(MaNganh.length==0){
					e.preventDefault();
					DialogTable.showThongBao('Thông báo','Vui lòng chọn ngành trước khi cập nhật');
				}
				else{
					DialogTable.showPropress();
					$('<input />').attr('type', 'hidden')
		            .attr('name', 'MaNganh')
		            .attr('value', MaNganh)
		            .appendTo('#formCapNhatNganh');
				}
				
				
			   
			});
			
			
		}
	}
}();
	
	
	
	
	jQuery(document).ready(function () {

		EditableTable.init();
		
	});