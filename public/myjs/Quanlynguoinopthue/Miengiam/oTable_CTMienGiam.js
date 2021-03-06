/**
 * 
 */



var EditableTableChiTietMienGiam = function () {

	//key
	var _SoQDMG = "";
	var _TieuMuc = "";
	var _KyThue = "";
	var oTable = null;

	return {

		init : function () {
			
			//*******************ONLY PAGE BEGIN************************//
			
			$("#editable-chitietmiengiam input[name='KyThue']").live('focus', function(){
			    if (false == $(this).hasClass('hasDatepicker')) {
			    	$(this).datepicker({ 
					    viewMode: "years", 
					    minViewMode: "months",
				    	format: 'mm/yyyy'
				    });
			    }
			});
			
			
			
			
			//chọn số miễn giảm
			$("#tablemiengiam a.CTMienGiam").live('click', function(e){
				/*var tbody = $(this).parents('tr')[0];
				console.log(tbody);*/
				e.preventDefault();
				
				var nRow = $(this).parents('tr')[0];

				$('img.loading').css('display','inline');
				SoQDMG =($('td',nRow)[1]).textContent ;
				deleteAllRows();
				$("div.progressSoQDMG").css('display','block');
				$("h1.SoQDMG").html(SoQDMG);
    			 
			    $.get(baseUrl('application/Service/danhSachChiTietMienGiam'),{
			    	SoQDMG:SoQDMG
			    },function(json){
			    	$('img.loading').css('display','none');
			    	if(json.kq==true){
			    		data = json.obj;
			    		deleteAllRows();
			    		$.each(data,function(key,value){
             				
            				
            				
    			    		oTable.fnAddData([
    			    		         value.KyThue,
    			    		         value.TieuMuc,
    			    		         value.SoTienMG,
    			    		         '<a class="edit" href="">Edit</a>',
    			    		         '<a class="Delete" href="">Delete</a>'
    				                  ]);
	        			    		
	        			    	
            			  });
			    		
			    	}
			    	
			    	$("div.progressSoQDMG").css('display','none');
			    },'json');
			});
			
			//*******************ONLY PAGE END************************//

			function deleteAllRows() {

				var oSettings = oTable.fnSettings();
				var iTotalRecords = oSettings.fnRecordsTotal();
				for (i = 0; i <= iTotalRecords; i++) {
					oTable.fnDeleteRow(0, null, true);
				}

			}
			// Khởi tạo oTable
			oTable = $('#editable-chitietmiengiam')
				.dataTable({

					"aLengthMenu" : [[5, 15, 20, -1],
						[5, 15, 20, "All"]// change per
						// page values
						// here
					],
					// new 
					
					
					//*************************************
					"sScrollY": "210px",
					"sScrollX": "100%",
					/*"bScrollCollapse": true,*/
					//*************************************
					
					
					
					"iDisplayLength" : -1,
					"sDom" : "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
					"sPaginationType" : "bootstrap",
					"oLanguage" : {
						"sLengthMenu" : "_MENU_",
						"oPaginate" : {
							"sPrevious" : "Prev",
							"sNext" : "Next"
						}
					},
					"aoColumnDefs" : [{
							'bSortable' : false,
							'aTargets' : [0]
						}
					]
				});

			function XoaNhieuDong(oTable, Rows) {

				$.each(Rows, function (key, value) {
					oTable.fnDeleteRow(value);
				});
			}

			function restoreRow(oTable, nRow) {

				var aData = oTable.fnGetData(nRow);
				var jqTds = $('>td', nRow);
				if (aData != null) {
					if (aData[0] != null) {
						for (var i = 0, iLen = jqTds.length; i < iLen; i++) {

							oTable.fnUpdate(aData[i], nRow, i, false);
						}
					}
				}

				oTable.fnDraw();
			}

			function editRow(oTable, nRow) {
				var aData = oTable.fnGetData(nRow);

				var jqTds = $('>td', nRow);

				// cansua
				//lưu các biến key
				_SoQDMG = $("h1.SoQDMG").html().trim();
				_KyThue = aData[0].trim();
				_TieuMuc = aData[1].trim();
				
				
				/*_MaSoThue = $("input[name='masothue']", nRow).val().trim();
				_TieuMuc = $("input[name='TieuMuc']", nRow).val().trim();*/
				

				// cansua
				// khi click edit trên 1 dòng
				// chuyển tất cả các ô trên dòng thành input
				
				jqTds[0].innerHTML = '<input style="width:80px;" name="KyThue" type="text"  value="'
					 + aData[0] + '">';
				
				jqTds[1].innerHTML = '<input style="width:80px;" name="TieuMuc" type="text"  value="'
					 + aData[1] + '"disabled><button style="margin:0 20px;margin-top:2px" class="btn btn-primary DialogTieuMuc">Chọn</button>';



				
				jqTds[2].innerHTML = '<input style="width:80px;" type="text" name="SoTien" value="'
					 + aData[2] + '">';
				
				
				jqTds[3].innerHTML = '<a class="edit" href="">Save edit</a>';
				jqTds[4].innerHTML = '<a class="cancel" data-mode="edit" href="">Cancel</a>';
				
				//update kích thước cột
				oTable.fnAdjustColumnSizing();

			}

			function addRow(oTable, nRow) {
				var aData = oTable.fnGetData(nRow);
				var jqTds = $('>td', nRow);
				jqTds[0].innerHTML = '<input style="width:80px;" name="KyThue" type="text"  value="'
					 + aData[0] + '">';
				
				jqTds[1].innerHTML = '<input style="width:80px;" name="TieuMuc" type="text"  value="'
					 + aData[1] + '"disabled><button style="margin:0 20px;margin-top:2px" class="btn btn-primary DialogTieuMuc">Chọn</button>';

				
				jqTds[2].innerHTML = '<input style="width:80px;" type="text" name="SoTien" value="'
					 + aData[2] + '">';
				

				jqTds[3].innerHTML = '<a class="edit" href="">Save new</a>';
				jqTds[4].innerHTML = '<a class="cancel" data-mode="new" href="">Cancel</a>';
				
				oTable.fnAdjustColumnSizing();

			}

			function saveRow(oTable, nRow) {
				var jqInputs = $('input', nRow);
				
				//oTable.fnUpdate('<input class="check_item" type="checkbox">', nRow, 0, false);

				// cansua
				oTable.fnUpdate(jqInputs[0].value, nRow, 0, false);
				oTable.fnUpdate(jqInputs[1].value, nRow, 1, false);
				oTable.fnUpdate(jqInputs[2].value, nRow, 2, false);


				oTable.fnUpdate('<a class="edit" href="">Edit</a>', nRow,3,
					false);
				oTable.fnUpdate('<a class="Delete" href="">Delete</a>', nRow,
					4, false);
				oTable.fnAdjustColumnSizing();
				oTable.fnDraw();
			}
			
			
			var nEditing = null;
			//Thêm 1 dòng mới
			$('#editable-chitietmiengiam_new').click(
				function (e) {
				e.preventDefault();
				if (nEditing != null) {
					restoreRow(oTable, nEditing);
				}
				
				if($("h1.SoQDMG").html() != "Vui lòng chọn số QDMG" && $("h1.SoQDMG").html() != "")
				{
					// cansua
					var aiNew = oTable
						.fnAddData([
						        '',
								'',
								'',
								'',
								'<a class="edit" href="">Edit</a>',
								'<a class="cancel" data-mode="new" href="">Cancel</a>'
							]);
					var nRow = oTable.fnGetNodes(aiNew[0]);
					addRow(oTable, nRow);
					nEditing = nRow;
				}else
				{

					BootstrapDialog
					.confirm({
						title : 'Cảnh báo',
						message : 'Vui lòng chọn miễn giảm cần thêm chi tiết miễn giảm',
					});
				}

			});

			/*function cancelEditRow(oTable, nRow) {
			var jqInputs = $('input', nRow);




			oTable.fnUpdate(jqInputs[0].value, nRow, 0, false);
			oTable.fnUpdate(jqInputs[1].value, nRow, 1, false);
			oTable.fnUpdate(jqInputs[2].value, nRow, 2, false);
			oTable.fnUpdate(jqInputs[3].value, nRow, 3, false);
			oTable.fnUpdate('<a class="edit" href="">Edit</a>', nRow, 4,
			false);



			oTable.fnDraw();
			}*/

			function SaveNew(method, url, data, oTable, nEditing) {
				$("img.loading").css('display','inline');
				if (method == "get") {
					$.get(url, data, function (json) {
						$("img.loading").css('display','none');
						if (json.kq == false) {
							BootstrapDialog
							.confirm({
								title : 'Thông báo',
								message : json.messenger,
							});
							return nEditing;
						} else {

							
							BootstrapDialog
							.confirm({
								title : 'Thông báo',
								message : json.messenger,

							});
							saveRow(oTable, nEditing);
							nEditing = null;
							return nEditing;
						}

					}, "json");
					return nEditing;
				} else if (method == "post") {
					$.post(url, data, function (json) {
						$("img.loading").css('display','none');
						if (json.kq == false) {
							BootstrapDialog
							.confirm({
								title : 'Thông báo',
								message : json.messenger,
							});
							return nEditing;
						} else {

							saveRow(oTable, nEditing);
							BootstrapDialog
							.confirm({
								title : 'Thông báo',
								message : json.messenger,

							});

							nEditing = null;
							return nEditing;
						}

					}, "json");
				}else{
					return nEditing;
				}

			}

			function SaveEdit(method, url, data, oTable, nEditing) {

				BootstrapDialog.confirm({
					title : 'Cảnh báo',
					message : 'Có thật sự chắc chắn với hành động này?',
					type : BootstrapDialog.TYPE_WARNING, // <--
					closable : true,
					draggable : true,
					btnCancelLabel : 'Quay lại',
					btnOKLabel : 'OK !',
					btnOKClass : 'OK !',
					callback : function (result) {
						if (result) {
							$("img.loading").css('display','inline');
							if (method == "get") {

								$.get(url, data, function (json) {
									$("img.loading").css('display','none');
									if (json.kq == false) {
										restoreRow(oTable, nEditing);
										BootstrapDialog.confirm({
											title : 'Cảnh báo',
											message : json.messenger,
										});
										return nEditing;
									} else {
										BootstrapDialog
										.confirm({
											title : 'Cảnh báo',
											message : json.messenger,
										});

										saveRow(oTable, nEditing);
										nEditing = null;
										return nEditing;
									}
								}, "json");
								return nEditing;
							} else {
								$.post(url, data, function (json) {
									$("img.loading").css('display','none');
									if (json.kq == false) {

										restoreRow(oTable, nEditing);
										BootstrapDialog.confirm({
											title : 'Cảnh báo',
											message : json.messenger,
										});
										return nEditing;
									} else {
										BootstrapDialog
										.confirm({
											title : 'Cảnh báo',
											message : json.messenger,
										});

										saveRow(oTable,
											nEditing);
										nEditing = null;
										return nEditing;
									}
								}, "json");

								return nEditing;
							}

						} else {
							return nEditing;
						}
					}
				});

			}

			//Bắt đâu xóa
			// param: url
			// param: get|post
			// param: data
			// param: nRow
			function Xoa(method, url, data, oTable, nRow) {
				// xoa trong csdl

				BootstrapDialog.confirm({
					title : 'Cảnh báo',
					message : 'Có thật sự chắc chắn với hành động này?',
					type : BootstrapDialog.TYPE_WARNING, // <-- Default value
					// is
					// BootstrapDialog.TYPE_PRIMARY
					closable : true, // <-- Default value is false
					draggable : true, // <-- Default value is false
					btnCancelLabel : 'No', // <-- Default value is
					// 'Cancel',
					btnOKLabel : 'Yes',
					// dialog type
					// will be used,
					callback : function (result) {
						// result will be true if button was click, while it
						// will be false
						// if users close the dialog directly.
						if (result) {
							$("img.loading").css('display','inline');
							if (method == "get") {
								$.get(url, data, function (json) {
									$("img.loading").css('display','none');
									if (json.kq == false) {

										BootstrapDialog.confirm({
											title : 'Thông báo',
											message : json.messenger
										});
										return;
									} else {
										// xoa html

										oTable.fnDeleteRow(nRow);

										BootstrapDialog.confirm({
											title : 'Thông báo',
											message : json.messenger
										});
									}

								}, "json");
							} else if (method == "post") {
								$.post(url, data, function (json) {
									$("img.loading").css('display','none');
									if (json.kq == false) {

										BootstrapDialog.confirm({
											title : 'Thông báo',
											message : json.messenger
										});
										return;
									} else {

										oTable.fnDeleteRow(nRow);

										BootstrapDialog.confirm({
											title : 'Thông báo',
											message : json.messenger
										});
									}

								}, "json");

							}

						} else {}
					}
				});

			}

			jQuery('#editable-chitietmiengiam_wrapper .dataTables_filter input')
			.addClass(" span6"); // modify

			jQuery('#editable-chitietmiengiam_wrapper .dataTables_length select')
			.addClass(" xsmall"); // modify


			// Khai báo chỉ số cột muốn sắp xếp, 'asc' là kiểu 
			oTable.fnSort([[0, 'asc']]);
			
			function XoaNhieuDong(oTable,Rows)
			{
				$.each(Rows,function(key,value){
					oTable.fnDeleteRow(value);
				});
			}
			
			//checkboxs : mảng element checkbox
			function XoaNhieu(checkboxs, method, url, data, oTable) {
				BootstrapDialog.confirm({
					title : 'Cảnh báo',
					message : 'Bạn có thật sự chắc chắn với hành động này?',
					type : BootstrapDialog.TYPE_WARNING,

					closable : true,
					draggable : true,
					btnCancelLabel : 'Quay lại',
					btnOKLabel : 'Xóa !',
					callback : function (result) {
						if (result) {
							if (method == "get") {
								$.get(url, data, function (json) {
									if (json.kq == false) {
										BootstrapDialog.confirm({
											title : 'Thông báo',
											message : json.messenger,
										});

									} else {
										XoaNhieuDong(oTable,checkboxs);
										BootstrapDialog.confirm({
											title : 'Thông báo',
											message : json.messenger,
										});
									}
								}, 'json');
							} else 
							if (method == "post") {
								$.post(url, data, function (json) {

									if (json.kq == false) {
										BootstrapDialog
										.confirm({
											title : 'Thông báo',
											message : json.messenger,
										});

									} else {
										XoaNhieuDong(oTable,checkboxs);
										BootstrapDialog
										.confirm({
											title : 'Thông báo',
											message : json.messenger,
										});
									}
								}, 'json');
							}

						}
					}
				});
			}

			$("#xoa_nhieu").click(function (e) {
				var checkboxs = $("#editable-chitietmiengiam input.check_item:checked").parents('tr');
				console.log(checkboxs);
				if (checkboxs.length > 0) {

					// cansuaxoanhieu
					// Cái nào checked thì lấy
					var MaSoThueData = new Array();
					var TieuMucData = new Array();
					for (i = checkboxs.length - 1; i >= 0; i--) {
						var MaSoThue = $('td', checkboxs[i])[1].textContent;
						MaSoThueData.push(MaSoThue);
						var TieuMuc = $('td', checkboxs[i])[3].textContent;
						TieuMucData.push(TieuMuc);
					}

					data = {
						_KyThue : _KyThue,
						MaSoThueData : MaSoThueData,
						TieuMucData : TieuMucData
					};
					var url = "xoanhieu";

					XoaNhieu(checkboxs, 'post', url, data, oTable);

				} else {
					BootstrapDialog.show({
						title : "Thông báo !",
						message : 'Vui lòng chọn trước khi xóa !'
					});
				}
			});

			$("#check_all").click(function (e) {
				// uncheck to checked
				if ($("#check_all").attr("checked") == "checked") {
					$("input.check_item").each(function () {
						$(this).attr('checked', true);
					});
				} else {
					$("input.check_item").each(function () {
						$(this).attr('checked', false);
					});
				}
			});

			

			$('#editable-chitietmiengiam a.cancel').live('click', function (e) {
				e.preventDefault();

				if ($(this).attr("data-mode") == "new") {
					var nRow = $(this).parents('tr')[0];

					oTable.fnDeleteRow(nRow);
				} else {

					restoreRow(oTable, nEditing);
					nEditing = null;
				}
			});

			$('#editable-chitietmiengiam a.Delete').live('click', function (e) {
				e.preventDefault();

				var nRow = $(this).parents('tr')[0];
				var aData = oTable.fnGetData(nRow);

				// cansuaxoa
				
				//dang lam
				SoQDMG = $("h1.SoQDMG").html();
				
				KyThue = aData[0].trim();
				TieuMuc = aData[1].trim();
					
				
				
				data = {
					SoQDMG : SoQDMG,
					KyThue : KyThue,
					TieuMuc : TieuMuc
				};
				
				
				var url = "xoaCTMienGiam";
				Xoa('post', url, data, oTable, nRow);

			});

			$('#editable-chitietmiengiam a.edit').live(
				'click',
				function (e) {
				e.preventDefault();

				var nRow = $(this).parents('tr')[0];

				if (nEditing !== null && nEditing != nRow) {

					var flag = false;
					var jqTds = $('>td', nEditing);
					$.each(jqTds, function (i, val) {
						if (val.textContent == 'Save new') {

							flag = true;

						}
					});

					if (flag == true) {
						oTable.fnDeleteRow(nEditing);
						editRow(oTable, nRow);
						nEditing = nRow;

						flag = false;
					} else {
						restoreRow(oTable, nEditing);
						editRow(oTable, nRow);
						nEditing = nRow;
					}

				} else if (nEditing == nRow
					 && this.innerHTML == "Save new") {
					/* Editing this row and want to save it */
					var jqInputs = $('input', nRow);

					//cansua Save new

					//lay du lieu


					
					var KyThue = $("input[name='KyThue']", nRow).val().trim();
					var TieuMuc = $("input[name='TieuMuc']", nRow).val().trim();
					var SoTien = $("input[name='SoTien']", nRow).val().trim();
					var SoQDMG = $("h1.SoQDMG").html().trim();
					
					data = {
						KyThue : KyThue,
						TieuMuc:TieuMuc,
						SoTien : SoTien,
						SoQDMG:SoQDMG
					}

					var url = 'themkythuemg';
					
					SaveNew('post', url, data, oTable, nEditing);

				} else if (nEditing == nRow
					 && this.innerHTML == "Save edit") {

					var jqInputs = $('input', nRow);

					//cansuaedit

					//lay du lieu					
					var KyThue = $("input[name='KyThue']", nRow).val().trim();
					var TieuMuc = $("input[name='TieuMuc']", nRow).val().trim();
					var SoTien = $("input[name='SoTien']", nRow).val().trim();
					var _SoQDMG = $("h1.SoQDMG").html().trim();
					
					data = {
						_KyThue : _KyThue,
						_TieuMuc : _TieuMuc,
						
						
						SoQDMG : _SoQDMG,
						KyThue : KyThue,
						TieuMuc : TieuMuc,
						SoTien : SoTien
					}

					var url = "suaCTMienGiam";

					

					SaveEdit('post', url, data, oTable, nEditing);

				} else {

					editRow(oTable, nRow);
					nEditing = nRow;
				}
			});

			
			
			// dialogTable
			$('#editable-chitietmiengiam button.DialogNNT').live('click', function (e) {
				//lấy dòng được chọn
				var nRow = $(this).parents('tr')[0];
				//get input có name là "masothue"
				MaSoThue = $("input[name='masothue']", nRow);
				TenHKD = $("input[name='TenHKD']", nRow);
				
				
				DialogTable.showFromUrl('get','danhsachNNT',{},function(){
					checkboxs = $('#DialogTable input.check_item:checked').parents("tr");

					if (checkboxs.length == 1) {
						var MaSoThueString = $('td', checkboxs[0])[1].textContent;
						var TenHKDString = $('td', checkboxs[0])[2].textContent;
						
						$("#DialogTable").modal("hide");
						
						MaSoThue.val(MaSoThueString);
						TenHKD.val(TenHKDString);
						//TieuMuc = $("input[name='TieuMuc']").val();
					} else {
						alert("Vui lòng chọn ít nhất một !");
					}
				});
				
				/*$.get('danhsachNNT', {}, function (json) {

					DialogTable.show(json, function () {
					
						checkboxs = $('#DialogTable input.check_item:checked').parents("tr");

						if (checkboxs.length == 1) {
							var MaSoThueString = $('td', checkboxs[0])[1].textContent;
							var TenHKDString = $('td', checkboxs[0])[2].textContent;
							
							$("#DialogTable").modal("hide");
							
							MaSoThue.val(MaSoThueString);
							TenHKD.val(TenHKDString);
							
						} else {
							alert("Vui lòng chọn ít nhất một !");
						}
					});
				}, 'json');*/

			});
			
			
			//
			

			$('#editable-chitietmiengiam button.DialogTieuMuc').live('click', function (e) {

				var nRow = $(this).parents('tr')[0];

				TieuMuc = $("input[name='TieuMuc']", nRow);

				DialogTable.showFromUrl('get',baseUrl('application/Service/muclucngansach'),{}, function () {
					
					checkboxs = $('#DialogTable input.check_item:checked').parents("tr");

					if (checkboxs.length == 1) {
						
						var TieuMucString = $('td', checkboxs[0])[1].textContent;
						
						$("#DialogTable").modal("hide");
						
						TieuMuc.val(TieuMucString);
						//MaSoThue = $("input[name='masothue']").val();
					} else {
						alert("Vui lòng chọn ít nhất một !");
					}
				});
				

			});
			
			

		}

	};
}
();
jQuery(document).ready(function () {

	EditableTableChiTietMienGiam.init();
	console.log(EditableTableChiTietMienGiam.oTable);
});
