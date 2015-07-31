


var EditableTable = function () {

	//key
	//Thang
	var _KyLapBo = "";
	var _TieuMuc = "";
	var _MaSoThue = "";
	//var oTableGhiSo=null;
	var oTableNoThue=null;
	return {

		init : function () {
			
			
			//*******************ONLY PAGE BEGIN************************//
			
/*			$("#check_all_ghiso").click(function (e) {
				// uncheck to checked
				if ($("#check_all_ghiso").attr("checked") == "checked") {
					$("#TableGhiSo input.check_item").each(function () {
						$(this).attr('checked', true);
					});
				} else {
					$("#TableGhiSo input.check_item").each(function () {
						$(this).attr('checked', false);
					});
				}
			});*/
			
			
/*			$("#GhiSo").click(function(){
				GhiSo();
			});
			function GhiSo(){
				$("img.loading_GhiSo").css('display','inline');
				var dsMaSoThue =[];
				var dsTieuMuc=[];
				var Row = $("#TableGhiSo input.check_item:checked").parents("tr");
				if(Row.length==0){
					$("#DialogGhiSo").modal('hide');
					DialogTable.showThongBaoUnlimit('Thông báo !','Cần chọn trước khi click ghi sổ !');
				}
				$.each(Row,function($key,$value){
					$MaSoThue = $("td",$value)[1].innerHTML.trim();
					$TieuMuc = $("td",$value)[3].innerHTML.trim();	
						
					dsMaSoThue.push($MaSoThue);
					dsTieuMuc.push($TieuMuc);
					
				});
				
				//post
				var data = {
						dsMaSoThue : dsMaSoThue,
						dsTieuMuc : dsTieuMuc,
						Thang : _KyLapBo
				};
				
				$.post("ghiso",data, function(json){
					$("img.loading_GhiSo").css('display','none');
					$("#DialogGhiSo").modal('hide');
					DialogTable.showThongBaoUnlimit('Thông báo',json.messenger);
					if(json.kq==true)
					{
						LoadDSNoThue();
					}
				},'json');
	
			}*/
			
			
/*			$("#dpGhiSo").datepicker();
			_KyThueGhiSo = $.datepicker.formatDate("mm/yy", new Date());
			$("#dpGhiSo").datepicker('setValue',_KyThueGhiSo);
			
			$("input[name='NgayPhaiNop']").live('focus', function(){
			    if (false == $(this).hasClass('hasDatepicker')) {
			    	$(this).datepicker({ 
				    	format: 'dd-mm-yyyy'
				    });
			    }
			});*/
			
			
			
			
/*			$("button.cancel").click(function(){
				$("#DialogGhiSo").modal('hide');
				
			});
			$("button.GhiSo").click(function(){
				$("#DialogGhiSo").modal('show');
				LoadDSDKNoThue();
			});*/
			
			
			if ($("#kylapbo").val() == "") {
				var today = new Date();
				_KyLapBo = $.datepicker.formatDate("mm/yy", today);
				$("#dpNoThue").datepicker('setValue', _KyLapBo);
			}
			
			
			
/*			$('#dpGhiSo').datepicker().on('changeDate', function (ev) {
				_KyThueGhiSo = $.datepicker.formatDate("mm/yy", ev.date);
				LoadDSDKNoThue();
			});*/
			
			/*function LoadDSDKNoThue(){
				$("img.loading_GhiSo").css("display","inline");
				
				data = {
					Thang : _KyLapBo	
				}
				
				deleteAllRowsGhiSo();
				
				$.post('DSDKNoThue',data,function(json){

					data = json.obj; 
				
					for (i = 0; i < data.length; i++) {
						oTableGhiSo
						.fnAddData([
								'<th><input class="check_item" type="checkbox"></th>',
								data[i]['nguoinopthue']['MaSoThue'],
								data[i]['nguoinopthue']['TenHKD'],
								data[i]['TieuMuc'],
								data[i]['DoanhThuChiuThue'],
								data[i]['TiLeTinhThue'],
								data[i]['SoTien'],
								$.datepicker.formatDate('dd-mm-yy',new Date(data[i]['NgayPhaiNop'].date)),
								data[i]['user']['MaUser']]);
					}
					$("img.loading_GhiSo").css('display','none');
				},'json');
				
				oTableGhiSo.fnAdjustColumnSizing();
				
				$("#TableGhiSo_wrapper div.dataTables_scrollBody").css("height","inherit");
				
			}*/
			
			
			
				
			/*oTableGhiSo = $('#TableGhiSo')
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
				"bScrollCollapse": true,
				//*************************************
				
				
				"bAutoWidth":false,
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
			});*/
			
			
			function LoadDSNoThue(){
				$('img.loading').css('display','inline');
				
				/*$("#progess_dpmonths").css('display', 'block');*/
				deleteAllRows();
				//post
				$.get('dsNoThue', {Thang : _KyLapBo},
					function (json) {

					deleteAllRows();
					data = json.obj; 
					LoaiUser = $("input:hidden[name='LoaiUser']").val();
					for (i = 0; i < data.length; i++) {
						oTableNoThue
						.fnAddData([
						        data[i]['KyThue'],
						        data[i]['nguoinopthue']['MaSoThue'],
								data[i]['nguoinopthue']['TenHKD'],
								data[i]['TieuMuc'],
								data[i]['SoTien']
						        ]);
					}

					$('img.loading').css('display','none');
				}, "json");

			}
			
			$('#dpNoThue').datepicker().on('changeDate', function (ev) {

				_KyLapBo = $.datepicker.formatDate("mm/yy", ev.date);
				LoadDSNoThue();
				
			});

/*			if ($("#kylapbo").val() == "") {
				var today = new Date();
				_KyLapBo = $.datepicker.formatDate("mm/yy", today);
				$("#kylapbo").val(_KyLapBo);
				$("#dpNoThue").datepicker('setValue', _KyLapBo);

			}*/
			
			
			
			
			
			
			
			
			
			
			
			//*******************ONLY PAGE END************************//

			function deleteAllRows() {

				var oSettings = oTableNoThue.fnSettings();
				var iTotalRecords = oSettings.fnRecordsTotal();
				for (i = 0; i <= iTotalRecords; i++) {
					oTableNoThue.fnDeleteRow(0, null, true);
				}

			}
			function deleteAllRowsGhiSo() {

				var oSettings = oTableGhiSo.fnSettings();
				var iTotalRecords = oSettings.fnRecordsTotal();
				for (i = 0; i <= iTotalRecords; i++) {
					oTableGhiSo.fnDeleteRow(0, null, true);
				}

			}
			// Khởi tạo oTable
			oTableNoThue = $('#TableNoThue')
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
				_MaSoThue = aData[1].trim();
				_TieuMuc = aData[3].trim();
				
				/*_MaSoThue = $("input[name='masothue']", nRow).val().trim();
				_TieuMuc = $("input[name='TieuMuc']", nRow).val().trim();*/
				
				
				// cansua
				// khi click edit trên 1 dòng
				// chuyển tất cả các ô trên dòng thành input
				jqTds[0].innerHTML = '<input class="check_item" type="checkbox">';

				jqTds[1].innerHTML = '<input style="width:65px;" name="masothue" type="text"  value="'
					 + aData[1] + ' "disabled><button style="margin:0 10px;margin-top:2px" class="btn btn-primary DialogNNT">Chọn</button>';
				jqTds[2].innerHTML = '<input style="width:100px;" name="TenHKD" type="text"  value="'

					 + aData[2] + '"disabled>';
					 
				jqTds[3].innerHTML = '<input style="width:50px;" name="TieuMuc" type="text"   value="'
					 + aData[3] + ' "disabled><button class="btn btn-primary DialogTieuMuc" style="margin:0 6px;margin-top:2px">Chọn</button>';


				jqTds[4].innerHTML = '<input style="width:90px;" name="DoanhThuChiuThue" type="text"  value="'
					 + aData[4] + '">';
				
				jqTds[5].innerHTML = '<input style="width:35px;" name="TiLeTinhThue" type="text"  value="'
					 + aData[5] + '"disabled>';
				
				
				jqTds[6].innerHTML = '<input style="width:90px;" name="SoTien" type="text"  value="'
					 + aData[6] + '">';
				jqTds[7].innerHTML = '<input style="width:90px;" name="NgayPhaiNop" type="text"  value="'
					 + aData[7] + '">';
				jqTds[8].innerHTML = aData[8];
				
				
				
				
				jqTds[9].innerHTML = '<a class="edit" href="">Save edit</a>';
				jqTds[10].innerHTML = '<a class="cancel" data-mode="edit" href="">Cancel</a>';
				
				//update kích thước cột
				oTableNoThue.fnAdjustColumnSizing();	
				$("div.dataTables_scrollHead").css("width","initial");
				$("div.dataTables_scrollBody").css("width","initial");
				

			}


			
			var nEditing = null;
			//Thêm 1 dòng mới
			$('#TableNoThue_new').click(
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
							0,
							0,
							'',
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
								
				oTable.fnUpdate('<span style="color:red;">'+'Chờ duyệt'+'</span>', nRow, 8, false);

				oTable.fnUpdate('<a class="edit" href="">Edit</a>', nRow, 9,
					false);
				oTable.fnUpdate('<a class="Delete" href="">Delete</a>', nRow,
					10, false);
				oTable.fnDraw();
			}

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
							$('img.loading').css('display','inline');
							if (method == "get") {

								$.get(url, data, function (json) {
									$('img.loading').css('display','none');
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
									$('img.loading').css('display','none');
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
						$('img.loading').css('display','inline');
						if (result) {

							if (method == "get") {
								$.get(url, data, function (json) {
									$('img.loading').css('display','none');
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
									$('img.loading').css('display','none');	
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

			jQuery('#TableNoThue_wrapper .dataTables_filter input')
			.addClass(" medium"); // modify

			jQuery('#TableNoThue_wrapper .dataTables_length select')
			.addClass(" xsmall"); // modify


			// Khai báo chỉ số cột muốn sắp xếp, 'asc' là kiểu 
			oTableNoThue.fnSort([[1, 'asc']]);
			
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
				var checkboxs = $("#TableNoThue input.check_item:checked").parents('tr');
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
						_KyLapBo : _KyLapBo,
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
					$("#TableNoThue input.check_item").each(function () {
						$(this).attr('checked', true);
					});
				} else {
					$("#TableNoThue input.check_item").each(function () {
						$(this).attr('checked', false);
					});
				}
			});

			

			$('#TableNoThue a.cancel').live('click', function (e) {
				e.preventDefault();

				if ($(this).attr("data-mode") == "new") {
					var nRow = $(this).parents('tr')[0];

					oTableNoThue.fnDeleteRow(nRow);
				} else {

					restoreRow(oTableNoThue, nEditing);
					nEditing = null;
				}
			});

			$('#TableNoThue a.Delete').live('click', function (e) {
				e.preventDefault();

				var nRow = $(this).parents('tr')[0];
				var aData = oTableNoThue.fnGetData(nRow);

				// cansuaxoa
				
				//dang lam
				_MaSoThue = aData[1].trim();
				_TieuMuc = aData[3].trim();
				
				data = {
					_MaSoThue : _MaSoThue,
					_KyLapBo : _KyLapBo,
					_TieuMuc : _TieuMuc
				};
				
				
				var url = 'xoa';
				Xoa('post', url, data, oTableNoThue, nRow);

			});

			$('#TableNoThue a.edit').live(
				'click',function (e) {
					
				e.preventDefault();
				var nRow = $(this).parents('tr')[0];
				var aData = oTableNoThue.fnGetData(nRow);
				
				
					
						if(aData[13] == $("span.MaCB").html()){
							var flag = false;
							var jqTds = $('>td', nEditing);
							$.each(jqTds, function (i, val) {
								if (val.textContent == 'Save new') {

									flag = true;

							}
							});

							if (flag == true) {
								oTableNoThue.fnDeleteRow(nEditing);
								editRow(oTableNoThue, nRow);
								nEditing = nRow;

								flag = false;
							} else {
								restoreRow(oTableNoThue, nEditing);
								editRow(oTableNoThue, nRow);
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
						var DoanhThuChiuThue = $("input[name='DoanhThuChiuThue']", nRow).val().trim();				
						var TiLeTinhThue = $("input[name='TiLeTinhThue']", nRow).val().trim();
						var ThueSuat = $("input[name='ThueSuat']", nRow).val().trim();
						var TenGoi = $("input[name='TenGoi']", nRow).val().trim();
						var SanLuong  = $("input[name='SanLuong']", nRow).val().trim();
						var GiaTinhThue  = $("input[name='GiaTinhThue']", nRow).val().trim();
						var SoTien  = $("input[name='SoTien']", nRow).val().trim();
		
						data = {
								KyLapBo : _KyLapBo,
								MaSoThue : MaSoThue,
								TieuMuc : TieuMuc,
								DoanhThuChiuThue : DoanhThuChiuThue,
								TiLeTinhThue : TiLeTinhThue,
								ThueSuat : ThueSuat,
								TenGoi : TenGoi,
								SanLuong : SanLuong,
								GiaTinhThue : GiaTinhThue,
								SoTien : SoTien
						}

						var url = 'them';
						console.log(nEditing);
						SaveNew('post', url, data, oTableNoThue, nEditing);

					} else if (nEditing == nRow
						 && this.innerHTML == "Save edit") {
						
						var jqInputs = $('input', nRow);
						
						//cansuaedit

						//lay du lieu					
						var MaSoThue = $("input[name='masothue']", nRow).val().trim();
						var TieuMuc = $("input[name='TieuMuc']", nRow).val().trim();
						var DoanhThuChiuThue = $("input[name='DoanhThuChiuThue']", nRow).val().trim();				
						var TiLeTinhThue = $("input[name='TiLeTinhThue']", nRow).val().trim();
						var SoTien  = $("input[name='SoTien']", nRow).val().trim();
						var NgayPhaiNop  = $("input[name='NgayPhaiNop']", nRow).val().trim();
						var ThueSuat = 1;
						var SanLuong = 0;
						
						data = {
							_MaSoThue : _MaSoThue,
							_KyLapBo : _KyLapBo,
							_TieuMuc : _TieuMuc,

							KyLapBo : _KyLapBo,
							MaSoThue : MaSoThue,
							TieuMuc : TieuMuc,
							DoanhThuChiuThue : DoanhThuChiuThue,
							TiLeTinhThue : TiLeTinhThue,
							SoTien : SoTien,
							NgayPhaiNop : NgayPhaiNop,
							SanLuong : SanLuong,
							ThueSuat : ThueSuat
						}

						var url = "sua";

						

						SaveEdit('post', url, data, oTableNoThue, nEditing);

					} else {
						
							editRow(oTableNoThue, nRow);
							nEditing = nRow;
						
						
					}
				
				 
				

				
			});
			
			function loadTyLeTinhThue(MaSoThue,TieuMuc){
				
				$.get('loadTyLeTinhThue',{MaSoThue:MaSoThue,TieuMuc:TieuMuc},function(json){
					$("input[name='TiLeTinhThue']").val(json.TyLeTinhThue);
					TinhTien();
				},'json');

				

			}

			// dialogTable
			$('#TableNoThue button.DialogNNT').live('click', function (e) {
				//lấy dòng được chọn
				var nRow = $(this).parents('tr')[0];
				//get input có name là "masothue"
				MaSoThue = $("input[name='masothue']", nRow);
				TenHKD = $("input[name='TenHKD']", nRow);
				
				
				DialogTable.showFromUrl('get',baseUrl('application/Service/danhsachNNT'),{},function(){
					checkboxs = $('#DialogTable input.check_item:checked').parents("tr");

					if (checkboxs.length == 1) {
						var MaSoThueString = $('td', checkboxs[0])[1].textContent;
						var TenHKDString = $('td', checkboxs[0])[2].textContent;
						
						$("#DialogTable").modal("hide");
						
						MaSoThue.val(MaSoThueString);
						TenHKD.val(TenHKDString);
						TieuMuc = $("input[name='TieuMuc']").val();
						loadTyLeTinhThue(MaSoThueString,TieuMuc);
						
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
			

			$('#TableNoThue button.DialogTieuMuc').live('click', function (e) {

				var nRow = $(this).parents('tr')[0];

				TieuMuc = $("input[name='TieuMuc']", nRow);

				DialogTable.showFromUrl('get',baseUrl('application/Service/mlnstruythu'),{}, function () {
					
					checkboxs = $('#DialogTable input.check_item:checked').parents("tr");

					if (checkboxs.length == 1) {
						
						var TieuMucString = $('td', checkboxs[0])[1].textContent;
						
						$("#DialogTable").modal("hide");
						
						TieuMuc.val(TieuMucString);
						MaSoThue = $("input[name='masothue']").val();
						loadTyLeTinhThue(MaSoThue,TieuMucString);
						
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
