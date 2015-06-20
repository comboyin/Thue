/**
 * Module: Quanlynguoinopthue
 * Controller: Nguoinopthue
 * Action: Index
 */


var EditableTable = function () {

	//key
	var _MaSoThue = "";

	return {

		init : function () {
			
			today = $.datepicker.formatDate("dd-mm-yy", new Date());
			//dp_NgayCapMST
			$("#dp_NgayCapMST").val(today);
			$('#dp_NgayCapMST').datepicker({
		        format: 'dd-mm-yyyy'
		    });
			$("#dp_NgayCapMST").datepicker('update', today);
			
			
			//dp_ThoiDiemBDKD
		    $("#dp_ThoiDiemBDKD").val(today);
			$('#dp_ThoiDiemBDKD').datepicker({
		        format: 'dd-mm-yyyy'
		    });
			$("#dp_ThoiDiemBDKD").datepicker('update', today);
			
			
			//Quan
			$("select[name='Quan']").change(function(e){
				$MaCoQuan = $("select[name='Quan']").val();
				
				$.get('dsPhuongForQuan',{MaCoQuan:$MaCoQuan},function(json){
					$("select[name='Phuong']").find('option:not(:first)').remove();
					for($i=0;$i<json.length;$i++)
						{
						
							$("select[name='Phuong']").append($('<option>', { 
						        value: json[$i].MaPhuong,
						        text : json[$i].TenPhuong 
						    }));
						
						}
					
				},'json');
			});
			
			//MaSoThue
			
			$("input[name='MaSoThue']").on( "keyup", function(e){
				var MaSoThue = $("input[name='MaSoThue']").val();
				if(MaSoThue.length >= 9 && MaSoThue.length<=14)
					{
						
						
						$("#progress_masothue").css('display','block');
						$.post('checkmasothue',{MaSoThue:MaSoThue},function(json){
							if(json.dem==0)
								{
								$(".check_ > i").attr('class','icon-ok');
								}
							else{
								$(".check_ > i").attr('class','icon-exclamation-sign');
								
							}
							$("#progress_masothue").css('display','none');
						},'json');
						
						
					}
			} );
		}
	
		}
	}();
jQuery(document).ready(function () {

	EditableTable.init();
	
});