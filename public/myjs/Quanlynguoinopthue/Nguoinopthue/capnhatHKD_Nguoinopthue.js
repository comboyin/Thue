var EditableTable = function () {

	//key
	var _MaSoThue = "";

	return {

		init : function () {
			function laydanhsachnganh(){
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
			}
			
			function laydanhsachcanbo(){
				MaNganhCu = $("span[class='MaCanBoCu']").html();
				
				DialogTable.showFromUrl('get',baseUrl("application/Service/laydanhsachcbtcapnhat"),{MaNganhCu:MaNganhCu},function(){
					checkboxs = $('#DialogTable input.check_item:checked').parents("tr");

					if (checkboxs.length == 1) {
						var MaCanBo = $('td', checkboxs[0])[1].textContent;
						var TenCanBo = $('td', checkboxs[0])[3].textContent;
						
						
						$("#DialogTable").modal("hide");
						
						$("input[name='MaCanBo']").val(MaCanBo);
						$("span.TenCanBo").html(TenCanBo);
						
						
					} else {
						alert("Vui lòng chọn ít nhất một !");
					}
				});
			}
			
			
			$("#ChonNganh").click(function(){
				laydanhsachnganh();
			});
			
			$("#ChonCanBo").click(function(){
				laydanhsachcanbo();
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
			
			$('#formCapNhatCanBo').submit(function (e) {
				MaCanBo = $("input[name='MaCanBo']").val();
				if(MaCanBo.length==0){
					e.preventDefault();
					DialogTable.showThongBao('Thông báo','Vui lòng chọn cán bộ trước khi cập nhật');
				}
				else{
					DialogTable.showPropress();
					$('<input />').attr('type', 'hidden')
		            .attr('name', 'MaCanBo')
		            .attr('value', MaCanBo)
		            .appendTo('#formCapNhatCanBo');
				}
			});
			
			$('#formTTCoBanNNT').submit(function (e) {
				
					DialogTable.showPropress();
					
				
			});
			$('#formThayDoiDiaChiKDNNT').submit(function (e) {
				
				DialogTable.showPropress();
				
			
			});
			
			
			
			//dp_ThoiDiemThayDoi
			$('#dp_ThoiDiemThayDoi').datepicker({
		        format: 'dd-mm-yyyy'
		    });
			$("#dp_ThoiDiemThayDoi").datepicker('setValue', new Date());
			
			
			//today = $.datepicker.formatDate("dd-mm-yy", new Date());
			curendateNgayCapMST = $("span[class='NgayCapMST']").html();
			
			//dp_NgayCapMST
			if(curendateNgayCapMST.length > 1 ){
				
				$('#dp_NgayCapMST').datepicker({
			        format: 'dd-mm-yyyy'
			    });
				$("#dp_NgayCapMST").datepicker('setValue', StringToDate(curendateNgayCapMST));
				
				
				
			}
			
			
			
			//dp_ThoiDiemBDKD
			curendateThoiDiemBDKD = $("span[class='ThoiDiemBDKD']").html();
			if(curendateThoiDiemBDKD.length > 1 ){
				 
					$('#dp_ThoiDiemBDKD').datepicker({
				        format: 'dd-mm-yyyy'
				    });
					$("#dp_ThoiDiemBDKD").datepicker('setValue', StringToDate(curendateThoiDiemBDKD));
				
				
				
				
			}
		   
			
		}
	}
}();
	
	
	
	
	jQuery(document).ready(function () {

		EditableTable.init();
		
	});