

jQuery(document).ready(function () {

	
/*	
	$('#dpMonths').datepicker().on('changeDate', function(ev) {
		// request lay json danh sach nnt
		var kythue = $.datepicker.formatDate("mm-yy", ev.date);

		$("#progress_kythue").css('display', 'block');
		$.post("dsNNTChuaCoDKDSTrongKyThue", {
			kythue : kythue
		}, function(data) {

			
			var mang = new Array();
			for(i=0;i<data.length;i++)
				{
					mang.push(data[i]['MaSoThue']);
				}
			
			console.log('['+ mang+']');
			
			
			var autocomplete = $('#MaSoThue').typeahead();


			autocomplete.data('typeahead').source = mang;
			
			
			

			$("#progress_kythue").css('display', 'none');
		}, "json");
	});*/
	
	$("#DialogMaSoThue").click(function(){
		var KyThue = $("#kythue").val();
		var TieuMuc = $("input[name|='TieuMuc']").val();
		
		$.post('dsNNTChuaCoDKT',{
			KyThue : KyThue,
			TieuMuc : TieuMuc
		},function(data){
			
			DialogTable.show(data,function()
			{
				checkboxs = $('input.check_item:checked').parents("tr");
				
				if (checkboxs.length == 1) {
					var nnt_masothue = $('td', checkboxs[0])[1].textContent;
					
					var nnt_ten =$('td', checkboxs[0])[2].textContent;
					var nnt_diachi =$('td', checkboxs[0])[4].textContent;
					var nnt_sogpkd =$('td', checkboxs[0])[5].textContent;
					var nnt_nghekd = $('td', checkboxs[0])[8].textContent;
					
					$("#nnt_masothue").html(nnt_masothue);
					$("#nnt_ten").html(nnt_ten);
					$("#nnt_diachi").html(nnt_diachi);
					$("#nnt_sogpkd").html(nnt_sogpkd);
					$("#nnt_nghekd").html(nnt_nghekd);
					
					$("input[name|='MaSoThue']").val(nnt_masothue);
					$("#DialogTable").modal("hide");
				}else{
					alert("Vui lòng chọn ít nhất một !");
				}
			});
		},'json');
	});
	$("#DialogTieuMuc").click(function(){
		$.post('dsMucLucNganSach',{
		},function(data){
			console.log(data);
			DialogTable.show(data,function()
			{
				checkboxs = $('input.check_item:checked').parents("tr");
				
				if (checkboxs.length == 1) {
					var TieuMuc = $('td', checkboxs[0])[1].textContent;
					var TenGoiTieuMuc = $('td', checkboxs[0])[2].textContent
					$("input[name|='TieuMuc']").val(TieuMuc);
					$("#TenGoiTieuMuc").html(TenGoiTieuMuc);
					$("#DialogTable").modal("hide");
				}else{
					alert("Vui lòng chọn ít nhất một !");
				}
			});
		},'json');
	});
	
	$("input[name|='TieuMuc']").change(function() { 
	    alert('aaaaaaaaaaa'); // get the current value of the input field.
	});
	
	
});
