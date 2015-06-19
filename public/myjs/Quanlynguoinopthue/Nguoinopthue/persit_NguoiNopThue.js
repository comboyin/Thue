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
			
			//MaSoThue
			
			$("input[name='MaSoThue']").on( "keyup", function(e){
				console.log($("input[name='MaSoThue']").val()) ;
			} );
		}
	
		}
	}();
jQuery(document).ready(function () {

	EditableTable.init();
	
});