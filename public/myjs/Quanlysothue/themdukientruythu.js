/**
 * 
 */

$(function() {

	$('#dpMonths').datepicker({
		onSelect : function(dateText, inst) {
			$("input[name='KyThue']").val(dateText);
		}
	});

});

jQuery(document).ready(function() {
	
	
	$("#kythue")
	.blur(
		function () {

		var kythue = $("#kythue").val();
		
		$("#progress_kythue").css('display',
			'block');
		$.post("dsNNTChuaCoDKDSTrongKyThue", {
			KyThue : kythue
		}, function(data) {

			
			var mang = new Array();
			for(i=0;i<data.length;i++)
				{
					mang.push(data[i]['MaSoThue']);
				}
			
			
			
			
			var autocomplete = $('#MaSoThue').typeahead();


			autocomplete.data('typeahead').source = mang;
			
			
			

			$("#progress_kythue").css('display', 'none');
		}, "json");
		
	});
	
	
	$("#MaSoThue").blur(function() {
		// request lay json danh sach nnt
		var masothue = $("#MaSoThue").val();

		$("#nnt_ten").text('.....');
		$("#nnt_masothue").text('...');
		$("#nnt_diachi").text('...');
		$("#nnt_sogpkd").text('...');
		$("#nnt_nghekd").text('...');
		$("#progress_kythue").css('display', 'block');

		$.post("getnguoinopthue", {
			masothue : masothue
		}, function(data) {

			if (data['loi'] == null && data.length > 0) {

				var masothue = data[0]['MaSoThue'];
				var ten = data[0]['TenHKD'];
				var diachi = data[0]['DiaChiCT'];
				var sogiayphep = data[0]['SoGPKD'];
				var nghe = data[0]['NgheKD'];

				$("#nnt_ten").text(ten);
				$("#nnt_masothue").text(masothue);
				$("#nnt_diachi").text(diachi);
				$("#nnt_sogpkd").text(sogiayphep);
				$("#nnt_nghekd").text(nghe);

			}

			$("#progress_kythue").css('display', 'none');
		}, "json");
	});

	$('#dpMonths').datepicker().on('changeDate', function(ev) {
		// request lay json danh sach nnt
		var kythue = $.datepicker.formatDate("mm-yy", ev.date);
		$("#progress_kythue").css('display', 'block');
		$.post("dsNNTChuaCoDKDSTrongKyThue", {
			KyThue : kythue
		}, function(data) {

			
			var mang = new Array();
			for(i=0;i<data.length;i++)
				{
					mang.push(data[i]['MaSoThue']);
				}
			
			
			
			
			var autocomplete = $('#MaSoThue').typeahead();


			autocomplete.data('typeahead').source = mang;
			
			
			

			$("#progress_kythue").css('display', 'none');
		}, "json");
	});

});