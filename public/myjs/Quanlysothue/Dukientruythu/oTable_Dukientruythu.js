


var EditableTable = function () {

	//key
	var _KyThue = "";
	var _TieuMuc = "";
	var _MaSoThue = "";

	return {

		init : function () {
			
			//*******************ONLY PAGE BEGIN************************//
			function TinhTien(){
				doanhso = $("input[name='DoanhSo']").val();
				tile = $("input[name='TiLeTinhThue']").val();
				$("input[name='SoTien']").val(parseInt(doanhso*tile));
			}
			
			$("input[name='DoanhSo']").live('blur',function(){
				if($("input[name='DoanhSo']").val() == '')
					{
					$("input[name='DoanhSo']").val(0);
					}
			});
			
			$("input[name='DoanhSo']").live('keyup',function(){
				TinhTien();
			});

			$("input[name='TiLeTinhThue']").live('keyup',function(){
				TinhTien();
			});
			
			$("input[name='TiLeTinhThue']").live('blur',function(){
				if($("input[name='TiLeTinhThue']").val() == '')
					{
					$("input[name='TiLeTinhThue']").val(1.0);
					}
			});
			
			$('#dpMonths').datepicker().on('changeDate', function (ev) {

				_KyThue = $.datepicker.formatDate("mm/yy", ev.date);
				
				DialogTable.showPropress();
				
				/*$("#progess_dpmonths").css('display', 'block');*/

				//post
				$.get("dsDKTTJson", {KyThue : _KyThue},
					function (json) {

					deleteAllRows();
					data = json.obj;

					for (i = 0; i < data.length; i++) {
						oTable
						.fnAddData([
								'<th><input class="check_item" type="checkbox"></th>',
								data[i]['nguoinopthue']['MaSoThue'],
								data[i]['nguoinopthue']['TenHKD'],
								data[i]['TieuMuc'],
								data[i]['DoanhSo'],
								data[i]['TiLeTinhThue'],
								data[i]['SoTien'],
								data[i]['TrangThai'],
								data[i]['LyDo'],
								'<a class="edit" href="">Edit</a>',
								'<a class="Delete" href="">Delete</a>']);
					}

					/*$("#progess_dpmonths").css(
						'display',
						'none');*/
					DialogTable.hidePropress();
				}, "json");

			});

			if ($("#kythue").val() == "") {
				var today = new Date();
				_KyThue = $.datepicker.formatDate("mm/yy", today)
				$("#kythue").val(_KyThue);
				$("#dpMonths").datepicker('update', _KyThue);

			}
			
			//*******************ONLY PAGE END************************//

			function deleteAllRows() {

				var oSettings = oTable.fnSettings();
				var iTotalRecords = oSettings.fnRecordsTotal();
				for (i = 0; i <= iTotalRecords; i++) {
					oTable.fnDeleteRow(0, null, true);
				}

			}
			// Khởi tạo oTable
			oTable = $('#editable-sample')
				.dataTable({

					"aLengthMenu" : [[5, 15, 20, -1],
						[5, 15, 20, "All"]// change per
						// page values
						// here
					],
					// new 
					
					
					//*************************************
					"sScrollY": "350px",
					"sScrollX": "100%",
					/*"bScrollCollapse": true,*/
					//*************************************
					
					
					
					"iDisplayLength" : 5,
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
				_MaSoThue = aData[1].trim();
				_TieuMuc = aData[3].trim();
				
				/*_MaSoThue = $("input[name='masothue']", nRow).val().trim();
				_TieuMuc = $("input[name='TieuMuc']", nRow).val().trim();*/
				

				// cansua
				// khi click edit trên 1 dòng
				// chuyển tất cả các ô trên dòng thành input
				jqTds[0].innerHTML = '<input class="check_item" type="checkbox">';

				jqTds[1].innerHTML = '<input style="width:80px;" name="masothue" type="text"  value="'
					 + aData[1] + ' "disabled><button style="margin:0 20px;margin-top:2px" class="btn btn-primary DialogNNT">Chọn</button>';
				jqTds[2].innerHTML = '<input style="width:80px;" name="TenHKD" type="text"  value="'

					 + aData[2] + '"disabled>';
					 
				jqTds[3].innerHTML = '<input style="width:80px;" name="TieuMuc" type="text"   value="'
					 + aData[3] + ' "disabled><button class="btn btn-primary DialogTieuMuc" style="margin:0 13px;margin-top:2px">Tìm</button>';


				jqTds[4].innerHTML = '<input style="width:120px;" name="DoanhSo" type="text"  value="'
					 + aData[4] + '">';
				
				jqTds[5].innerHTML = '<input style="width:40px;" name="TiLeTinhThue" type="text"  value="'
					 + aData[5] + '">';
				
				jqTds[6].innerHTML = '<input style="width:100px;" name="SoTien" type="text"  value="'
					 + aData[6] + '"disabled>';
				jqTds[7].innerHTML = '<input style="width:10px;" name="TrangThai" type="text" value="'
					 + aData[7] + ' "disabled>';
				jqTds[8].innerHTML = '<input style="width:120px;" name="LyDo" type="text" value="'
					 + aData[8] + ' ">';

				jqTds[9].innerHTML = '<a class="edit" href="">Save edit</a>';
				jqTds[10].innerHTML = '<a class="cancel" data-mode="edit" href="">Cancel</a>';
				
				//update kích thước cột
				oTable.fnAdjustColumnSizing();

			}

			function addRow(oTable, nRow) {
				var aData = oTable.fnGetData(nRow);
				var jqTds = $('>td', nRow);

				// cansua
				jqTds[1].innerHTML = '<input style="width:80px;" name="masothue" type="text"  value="'
					 + aData[1] + ' "disabled><button style="margin:0 20px;margin-top:2px" class="btn btn-primary DialogNNT">Chọn</button>';
				jqTds[2].innerHTML = '<input style="width:110px;" name="TenHKD" type="text"  value="'

					 + aData[2] + '"disabled>';
					 
				jqTds[3].innerHTML = '<input style="width:60px;" name="TieuMuc" type="text"   value="'
					 + aData[3] + ' "disabled><button class="btn btn-primary DialogTieuMuc" style="margin:0 13px;margin-top:2px">Tìm</button>';


				jqTds[4].innerHTML = '<input style="width:90px;" name="DoanhSo" type="text"  value="'
					 + aData[4] + '">';
				
				jqTds[5].innerHTML = '<input style="width:40px;" name="TiLeTinhThue" type="text"  value="'
					 + aData[5] + '">';
				
				jqTds[6].innerHTML = '<input style="width:100px;" name="SoTien" type="text"  value="'
					 + aData[6] + '"disabled>';
				jqTds[7].innerHTML = '<input style="width:10px;" name="TrangThai" type="text" value="'
					 + aData[7] + ' "disabled>';
				jqTds[8].innerHTML = '<input style="width:120px;" name="LyDo" type="text" value="'
					 + aData[8] + ' ">';

				jqTds[9].innerHTML = '<a class="edit" href="">Save new</a>';
				jqTds[10].innerHTML = '<a class="cancel" data-mode="new" href="">Cancel</a>';
				
				oTable.fnAdjustColumnSizing();

			}

			function saveRow(oTable, nRow) {
				var jqInputs = $('input', nRow);
				oTable.fnUpdate('<input class="check_item" type="checkbox">', nRow, 0, false);

				// cansua
				oTable.fnUpdate(jqInputs[1].value, nRow, 1, false);
				oTable.fnUpdate(jqInputs[2].value, nRow, 2, false);
				oTable.fnUpdate(jqInputs[3].value, nRow, 3, false);
				oTable.fnUpdate(jqInputs[4].value, nRow, 4, false);

				oTable.fnUpdate(jqInputs[5].value, nRow, 5, false);
				oTable.fnUpdate(jqInputs[6].value, nRow, 6, false);
				oTable.fnUpdate(jqInputs[7].value, nRow, 7, false);
				oTable.fnUpdate(jqInputs[8].value, nRow, 8, false);

				oTable.fnUpdate('<a class="edit" href="">Edit</a>', nRow, 9,
					false);
				oTable.fnUpdate('<a class="Delete" href="">Delete</a>', nRow,
					10, false);
				oTable.fnDraw();
			}
			
			
			var nEditing = null;
			//Thêm 1 dòng mới
			$('#editable-sample_new').click(
				function (e) {
				e.preventDefault();
				if (nEditing != null) {
					restoreRow(oTable, nEditing);
				}

				// cansua
				var aiNew = oTable
					.fnAddData([
							'',
							'',
							'',
							'',
							0,
							'1.0',
							0,
							0,
							'',
							'<a class="edit" href="">Edit</a>',
							'<a class="cancel" data-mode="new" href="">Cancel</a>'
						]);
				var nRow = oTable.fnGetNodes(aiNew[0]);
				addRow(oTable, nRow);
				nEditing = nRow;
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

				if (method == "get") {
					$.get(url, data, function (json) {

						if (json.kq == false) {
							BootstrapDialog
							.confirm({
								title : 'Thông báo',
								message : json.messenger,
							});

						} else {

							saveRow(oTable, nEditing);
							BootstrapDialog
							.confirm({
								title : 'Thông báo',
								message : json.messenger,

							});

							nEditing = null;

						}

					}, "json");
					return nEditing;
				} else if (method == "post") {
					$.post(url, data, function (json) {

						if (json.kq == false) {
							BootstrapDialog
							.confirm({
								title : 'Thông báo',
								message : json.messenger,
							});

						} else {

							saveRow(oTable, nEditing);
							BootstrapDialog
							.confirm({
								title : 'Thông báo',
								message : json.messenger,

							});

							nEditing = null;

						}

					}, "json");

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

							if (method == "get") {

								$.get(url, data, function (json) {
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

							if (method == "get") {
								$.get(url, data, function (json) {

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

			jQuery('#editable-sample_wrapper .dataTables_filter input')
			.addClass(" medium"); // modify

			jQuery('#editable-sample_wrapper .dataTables_length select')
			.addClass(" xsmall"); // modify


			// Khai báo chỉ số cột muốn sắp xếp, 'asc' là kiểu 
			oTable.fnSort([[1, 'asc']]);
			
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
				var checkboxs = $("#editable-sample input.check_item:checked").parents('tr');
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

			

			$('#editable-sample a.cancel').live('click', function (e) {
				e.preventDefault();

				if ($(this).attr("data-mode") == "new") {
					var nRow = $(this).parents('tr')[0];

					oTable.fnDeleteRow(nRow);
				} else {

					restoreRow(oTable, nEditing);
					nEditing = null;
				}
			});

			$('#editable-sample a.Delete').live('click', function (e) {
				e.preventDefault();

				var nRow = $(this).parents('tr')[0];
				var aData = oTable.fnGetData(nRow);

				// cansuaxoa
				
				//dang lam
				_MaSoThue = aData[1].trim();
				_TieuMuc = aData[3].trim();
				
				data = {
					_MaSoThue : _MaSoThue,
					_KyThue : _KyThue,
					_TieuMuc : _TieuMuc
				};
				
				
				var url = "xoa";
				Xoa('post', url, data, oTable, nRow);

			});

			$('#editable-sample a.edit').live(
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


					
					var MaSoThue = $("input[name='masothue']", nRow).val().trim();
					var TieuMuc = $("input[name='TieuMuc']", nRow).val().trim();
					var SoTien = $("input[name='SoTien']", nRow).val().trim();
					var TrangThai = $("input[name='TrangThai']", nRow).val().trim();
					var LyDo = $("input[name='LyDo']", nRow).val().trim();
					var TiLeTinhThue = $("input[name='TiLeTinhThue']", nRow).val().trim();
					var DoanhSo  = $("input[name='DoanhSo']", nRow).val().trim();
	
					data = {
						KyThue : _KyThue,
						MaSoThue : MaSoThue,
						TieuMuc : TieuMuc,
						SoTien : SoTien,
						TrangThai : TrangThai,
						LyDo : LyDo,
						TiLeTinhThue:TiLeTinhThue,
						DoanhSo:DoanhSo
					}

					var url = 'them';

					nEditing = SaveNew('post', url, data, oTable, nEditing);

				} else if (nEditing == nRow
					 && this.innerHTML == "Save edit") {

					var jqInputs = $('input', nRow);

					//cansuaedit

					//lay du lieu					
					var MaSoThue = $("input[name='masothue']", nRow).val().trim();
					var TieuMuc = $("input[name='TieuMuc']", nRow).val().trim();
					var SoTien = $("input[name='SoTien']", nRow).val().trim();
					var TrangThai = $("input[name='TrangThai']", nRow).val().trim();
					var LyDo = $("input[name='LyDo']", nRow).val().trim();
					var TiLeTinhThue = $("input[name='TiLeTinhThue']", nRow).val().trim();
					var DoanhSo  = $("input[name='DoanhSo']", nRow).val().trim();
					
					
					
					data = {
						_MaSoThue : _MaSoThue,
						_KyThue : _KyThue,
						_TieuMuc : _TieuMuc,

						KyThue : _KyThue,
						MaSoThue : MaSoThue,
						TieuMuc : TieuMuc,
						SoTien : SoTien,
						TrangThai : TrangThai,
						LyDo : LyDo,
						TiLeTinhThue:TiLeTinhThue,
						DoanhSo:DoanhSo
					}

					var url = "sua";

					

					SaveEdit('post', url, data, oTable, nEditing);

				} else {

					editRow(oTable, nRow);
					nEditing = nRow;
				}
			});

			// dialogTable
			$('#editable-sample button.DialogNNT').live('click', function (e) {
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

			$('#editable-sample button.DialogTieuMuc').live('click', function (e) {

				var nRow = $(this).parents('tr')[0];

				TieuMuc = $("input[name='TieuMuc']", nRow);

				DialogTable.showFromUrl('get','muclucngansach',{}, function () {
					
					checkboxs = $('#DialogTable input.check_item:checked').parents("tr");

					if (checkboxs.length == 1) {
						
						var TieuMucString = $('td', checkboxs[0])[1].textContent;
						
						$("#DialogTable").modal("hide");
						
						TieuMuc.val(TieuMucString);
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

	EditableTable.init();
});
