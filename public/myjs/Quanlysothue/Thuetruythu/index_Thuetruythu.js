


var EditableTable = function () {

	//key
	//Thang
	var _KyThueThueTruyThu = "";
	var _TieuMuc = "";
	var _MaSoThue = "";
	var oTableGhiSo=null;
	var oTableThueTruyThu=null;
	return {

		init : function () {
			
			
			//*******************ONLY PAGE BEGIN************************//
			$("button.ChonChoDuyet").click(function(){
				chonChoDuyet();
			});
			
			
			//chon chờ duyệt
			function chonChoDuyet(){
				
				// lay nhung dong dang "Chờ duyệt"
				var dangChoDuyet_td = $("#TableThueTruyThu td span");
				
				$.each(dangChoDuyet_td,function(key,value){
					if(value.innerHTML=="Chờ duyệt"){
						var elemTr =  $(value).parents('tr')[0];
						var checkbox = $('input',elemTr)[0];
						
						$(checkbox).attr('checked','checked');
					}
				});
				
			}
			$("button.Duyet").click(function(){
				duyetThueTruyThu();
			});
			
			

			
			//duyet du kien thue nam
			function duyetThueTruyThu(){
				
				var row = $("#TableThueTruyThu input.check_item:checked").parents('tr');
				if(row.length==0){
					DialogTable.showThongBaoUnlimit('Thông báo !','Vui lòng chọn ít nhất một để duyệt !');
					return;
				}
				
				
				
				BootstrapDialog.confirm({
					title : 'Cảnh báo',
					message : '<span style="color:red">Có thật sự chắc chắn với hành động này?</span>',
					type : BootstrapDialog.TYPE_WARNING,
					btnCancelLabel : 'NO',
					btnOKLabel : 'YES',
					callback : function (result) {
						if (result) {
							//
							$("img.loading").css('display','inline');
							var indexMaSoThue = 1;
							var indexTieuMuc = 3;
							var dsMaSoThue = [];
							var dsTieuMuc = [];
							var row = $("#TableThueTruyThu input.check_item:checked").parents('tr');
							
							$.each(row,function(key,value){
								var MaSoThue = $('td',value)[indexMaSoThue].innerHTML.trim();
								var TieuMuc =  $('td',value)[indexTieuMuc].innerHTML.trim();
								dsMaSoThue.push(MaSoThue);
								dsTieuMuc.push(TieuMuc);
							});
							
							
							data = {
								dsMaSoThue : dsMaSoThue, 
								dsTieuMuc : dsTieuMuc,
								Thang : _KyThueThueTruyThu
							};
							
							$.post("duyet",data,function(json){
								if(json.kq==true){
									LoadDSThueTruyThu();
								}
								DialogTable.showThongBaoUnlimit('Thông báo',json.messenger);
								$("img.loading").css('display','none');
							},'json');
						} else {
							
						}
					}
				});

			}
			
			$("#check_all_ghiso").click(function (e) {
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
			});
			
			
			$("#GhiSo").click(function(){
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
						Thang : _KyThueThueTruyThu
				};
				
				$.post("ghiso",data, function(json){
					$("img.loading_GhiSo").css('display','none');
					$("#DialogGhiSo").modal('hide');
					DialogTable.showThongBaoUnlimit('Thông báo',json.messenger);
					if(json.kq==true)
					{
						LoadDSThueTruyThu();
					}
				},'json');
	
			}
			
			
			$("#dpGhiSo").datepicker();
			_KyThueGhiSo = $.datepicker.formatDate("mm/yy", new Date());
			$("#dpGhiSo").datepicker('setValue',_KyThueGhiSo);
			
			$("input[name='NgayPhaiNop']").live('focus', function(){
			    if (false == $(this).hasClass('hasDatepicker')) {
			    	$(this).datepicker({ 
				    	format: 'dd-mm-yyyy'
				    });
			    }
			});
			
			
			
			
			$("button.cancel").click(function(){
				$("#DialogGhiSo").modal('hide');
				
			});
			$("button.GhiSo").click(function(){
				$("#DialogGhiSo").modal('show');
				LoadDSDKThueTruyThu();
			});
			
			
			if ($("#kythue").val() == "") {
				var today = new Date();
				_KyThueThueTruyThu = $.datepicker.formatDate("mm/yy", today);
				$("#dpThueTruyThu").datepicker('setValue', _KyThueThueTruyThu);
			}
			
			
			
			$('#dpGhiSo').datepicker().on('changeDate', function (ev) {
				_KyThueGhiSo = $.datepicker.formatDate("mm/yy", ev.date);
				LoadDSDKThueTruyThu();
			});
			
			function LoadDSDKThueTruyThu(){
				$("img.loading_GhiSo").css("display","inline");
				
				data = {
					Thang : _KyThueThueTruyThu	
				}
				
				deleteAllRowsGhiSo();
				
				$.post('DSDKThueTruyThu',data,function(json){

					data = json.obj; 
				
					for (i = 0; i < data.length; i++) {
						oTableGhiSo
						.fnAddData([
								'<th><input class="check_item" type="checkbox"></th>',
								data[i]['dukienthue']['nguoinopthue']['MaSoThue'],
								data[i]['dukienthue']['nguoinopthue']['TenHKD'],
								data[i]['dukienthue']['muclucngansach']['TieuMuc'],
								data[i]['DoanhSo'],
								data[i]['TiLeTinhThue'],
								data[i]['SoTien'],
								//$.datepicker.formatDate('dd-mm-yy',new Date(data[i]['NgayPhaiNop'].date)),
								data[i]['LyDo'],
								data[i]['user']['MaUser']]);
					}
					$("img.loading_GhiSo").css('display','none');
				},'json');
				
				oTableGhiSo.fnAdjustColumnSizing();
				
				$("#TableGhiSo_wrapper div.dataTables_scrollBody").css("height","inherit");
				
			}
			
			
			
				
			oTableGhiSo = $('#TableGhiSo')
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
			});
			
			
			
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
			
			$("input[name='DoanhSo']").live('focus',function(){
				if($("input[name='DoanhSo']").val() == 0)
				{
					$("input[name='DoanhSo']").val('');
				}
			});
			
			$("input[name='DoanhSo']").live('keyup',function(){
				TinhTien();
			});
			
			
			
			function LoadDSThueTruyThu(){
				$('img.loading').css('display','inline');
				
				/*$("#progess_dpmonths").css('display', 'block');*/
				deleteAllRows();
				//post
				$.get('dsThueTruyThu', {Thang : _KyThueThueTruyThu},
					function (json) {

					deleteAllRows();
					data = json.obj; 
					LoaiUser = $("input:hidden[name='LoaiUser']").val();
					for (i = 0; i < data.length; i++) {
						oTableThueTruyThu
						.fnAddData([
								'<th><input class="check_item" type="checkbox"></th>',
								data[i]['thue']['nguoinopthue']['MaSoThue'],
								data[i]['thue']['nguoinopthue']['TenHKD'],
								data[i]['thue']['muclucngansach']['TieuMuc'],
								data[i]['DoanhSo'],
								data[i]['TiLeTinhThue'],
								data[i]['SoTien'],
								data[i]['LyDo'],
								//$.datepicker.formatDate("dd-mm-yy",new Date(data[i]['NgayPhaiNop'].date)) ,
								data[i]['TrangThai']==0?'<span style="color:red;">'+'Chờ duyệt'+'</span>':'<span style="color:green;">'+'Đã duyệt'+'</span>',	
										LoaiUser==3?(data[i]['TrangThai']==0?'<a class="edit" href="">Edit</a>':''):(''),
												LoaiUser==3?(data[i]['TrangThai']==0?'<a class="Delete" href="">Delete</a>':''):('')]);
					}

					$('img.loading').css('display','none');
				}, "json");
				
				//oTableThueTruyThu.fnDraw();
				//update kích thước cột
				oTableThueTruyThu.fnAdjustColumnSizing();	
				$("div.dataTables_scrollHead").css("width","initial");
				$("div.dataTables_scrollBody").css("width","initial");

			}
			
			$('#dpThueTruyThu').datepicker().on('changeDate', function (ev) {

				_KyThueThueTruyThu = $.datepicker.formatDate("mm/yy", ev.date);
				LoadDSThueTruyThu();
				
			});

			if ($("#kythue").val() == "") {
				var today = new Date();
				_KyThueThueTruyThu = $.datepicker.formatDate("mm/yy", today);
				$("#kythue").val(_KyThueThueTruyThu);
				$("#dpThueTruyThu").datepicker('setValue', _KyThueThueTruyThu);

			}
			
			
			
			
			
			
			
			
			
			
			
			//*******************ONLY PAGE END************************//

			function deleteAllRows() {

				var oSettings = oTableThueTruyThu.fnSettings();
				var iTotalRecords = oSettings.fnRecordsTotal();
				for (i = 0; i <= iTotalRecords; i++) {
					oTableThueTruyThu.fnDeleteRow(0, null, true);
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
			oTableThueTruyThu = $('#TableThueTruyThu')
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


				jqTds[4].innerHTML = '<input style="width:90px;" name="DoanhSo" type="text"  value="'
					 + aData[4] + '">';
				
				jqTds[5].innerHTML = '<input style="width:40px;" name="TiLeTinhThue" type="text"  value="'
					 + aData[5] + '"disabled><button class="btn btn-primary DialogTiLeTinhThue" style="margin:0 0px;margin-top:2px">Chọn</button>';
				
				
				jqTds[6].innerHTML = '<input style="width:90px;" name="SoTien" type="text"  value="'
					 + aData[6] + '">';
				jqTds[7].innerHTML = '<input style="width:90px;" name="LyDo" type="text"  value="'
					 + aData[7] + '">';
				jqTds[8].innerHTML = aData[8];
				
				
				
				
				jqTds[9].innerHTML = '<a class="edit" href="">Save edit</a>';
				jqTds[10].innerHTML = '<a class="cancel" data-mode="edit" href="">Cancel</a>';
				
				//update kích thước cột
				oTableThueTruyThu.fnAdjustColumnSizing();	
				$("div.dataTables_scrollHead").css("width","initial");
				$("div.dataTables_scrollBody").css("width","initial");
				

			}


			
			var nEditing = null;
			//Thêm 1 dòng mới
			$('#TableThueTruyThu_new').click(
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
				//var StringTrangThai = ==0?'<span style="color:red;">'+'Chờ duyệt'+'</span>':'<span style="color:green;">'+'Đã duyệt'+'</span>';
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

			jQuery('#TableThueTruyThu_wrapper .dataTables_filter input')
			.addClass(" medium"); // modify

			jQuery('#TableThueTruyThu_wrapper .dataTables_length select')
			.addClass(" xsmall"); // modify


			// Khai báo chỉ số cột muốn sắp xếp, 'asc' là kiểu 
			oTableThueTruyThu.fnSort([[1, 'asc']]);
			
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
				var checkboxs = $("#TableThueTruyThu input.check_item:checked").parents('tr');
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
					$("#TableThueTruyThu input.check_item").each(function () {
						$(this).attr('checked', true);
					});
				} else {
					$("#TableThueTruyThu input.check_item").each(function () {
						$(this).attr('checked', false);
					});
				}
			});

			

			$('#TableThueTruyThu a.cancel').live('click', function (e) {
				e.preventDefault();

				if ($(this).attr("data-mode") == "new") {
					var nRow = $(this).parents('tr')[0];

					oTableThueTruyThu.fnDeleteRow(nRow);
				} else {

					restoreRow(oTableThueTruyThu, nEditing);
					nEditing = null;
				}
			});

			$('#TableThueTruyThu a.Delete').live('click', function (e) {
				e.preventDefault();

				var nRow = $(this).parents('tr')[0];
				var aData = oTableThueTruyThu.fnGetData(nRow);

				// cansuaxoa
				
				//dang lam
				_MaSoThue = aData[1].trim();
				_TieuMuc = aData[3].trim();
				
				data = {
					_MaSoThue : _MaSoThue,
					_KyThue : _KyThueThueTruyThu,
					_TieuMuc : _TieuMuc
				};
				
				
				var url = 'xoa';
				Xoa('post', url, data, oTableThueTruyThu, nRow);

			});

			$('#TableThueTruyThu a.edit').live(
				'click',function (e) {
					
				e.preventDefault();
				var nRow = $(this).parents('tr')[0];
				var aData = oTableThueTruyThu.fnGetData(nRow);
				
				
					
						if(aData[13] == $("span.MaCB").html()){
							var flag = false;
							var jqTds = $('>td', nEditing);
							$.each(jqTds, function (i, val) {
								if (val.textContent == 'Save new') {

									flag = true;

							}
							});

							if (flag == true) {
								oTableThueTruyThu.fnDeleteRow(nEditing);
								editRow(oTableThueTruyThu, nRow);
								nEditing = nRow;

								flag = false;
							} else {
								restoreRow(oTableThueTruyThu, nEditing);
								editRow(oTableThueTruyThu, nRow);
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
						var DoanhSo = $("input[name='DoanhSo']", nRow).val().trim();				
						var TiLeTinhThue = $("input[name='TiLeTinhThue']", nRow).val().trim();

		
						data = {
								KyThue : _KyThue,
								MaSoThue : MaSoThue,
								TieuMuc : TieuMuc,
								DoanhSo : DoanhSo,
								TiLeTinhThue : TiLeTinhThue,
								ThueSuat : ThueSuat,
								TenGoi : TenGoi,
								SanLuong : SanLuong,
								GiaTinhThue : GiaTinhThue,
								SoTien : SoTien,
								NgayPhaiNop : NgayPhaiNop
						}

						var url = 'them';
						console.log(nEditing);
						SaveNew('post', url, data, oTableThueTruyThu, nEditing);

					} else if (nEditing == nRow
						 && this.innerHTML == "Save edit") {
						
						var jqInputs = $('input', nRow);
						
						//cansuaedit

						//lay du lieu					
						var MaSoThue = $("input[name='masothue']", nRow).val().trim();
						var TieuMuc = $("input[name='TieuMuc']", nRow).val().trim();
						var SoTien = $("input[name='SoTien']", nRow).val().trim();
						//var TrangThai = $("input[name='TrangThai']", nRow).val().trim();
						var LyDo = $("input[name='LyDo']", nRow).val().trim();
						var TiLeTinhThue = $("input[name='TiLeTinhThue']", nRow).val().trim();
						var DoanhSo  = $("input[name='DoanhSo']", nRow).val().trim();
						
						
						data = {
							_MaSoThue : _MaSoThue,
							_KyThue : _KyThueThueTruyThu,
							_TieuMuc : _TieuMuc,

							KyThue : _KyThueThueTruyThu,
							MaSoThue : MaSoThue,
							TieuMuc : TieuMuc,
							SoTien : SoTien,
							//TrangThai : TrangThai,
							LyDo : LyDo,
							TiLeTinhThue:TiLeTinhThue,
							DoanhSo:DoanhSo
						}

						var url = "sua";

						

						SaveEdit('post', url, data, oTableThueTruyThu, nEditing);

					} else {
						
							editRow(oTableThueTruyThu, nRow);
							nEditing = nRow;
						
						
					}
				
				 
				

				
			});

			// dialogTable
			$('#TableThueTruyThu button.DialogNNT').live('click', function (e) {
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
			

			$('#TableThueTruyThu button.DialogTieuMuc').live('click', function (e) {

				var nRow = $(this).parents('tr')[0];

				TieuMuc = $("input[name='TieuMuc']", nRow);

				DialogTable.showFromUrl('get',baseUrl('application/Service/mlnstruythu'),{}, function () {
					
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
			
			//DialogTiLeTinhThue
			$('#TableThueTruyThu button.DialogTiLeTinhThue').live('click', function (e) {
console.log('a');
				var nRow = $(this).parents('tr')[0];
				var TieuMuc = $("input[name='TieuMuc']", nRow).val().trim();
				//console.log(TieuMuc);
				if(TieuMuc == ""){
					alert("Vui lòng chọn tiểu mục trước !");
				} else{
					
					TiLeTinhThue = $("input[name='TiLeTinhThue']", nRow);
					data = {
							TieuMuc : TieuMuc
					};
					DialogTable.showFromUrl('get',baseUrl('application/Service/tiletinhthuetm'),data, function () {
						
						checkboxs = $('#DialogTable input.check_item:checked').parents("tr");

						if (checkboxs.length == 1) {
							
							var TiLeTinhThueString = $('td', checkboxs[0])[3].textContent;
							
							$("#DialogTable").modal("hide");
							
							TiLeTinhThue.val(TiLeTinhThueString);
							TinhTien();
							//MaSoThue = $("input[name='masothue']", nRow).val();
							//loadTyLeTinhThue(MaSoThue,TieuMucString);
						} else {
							alert("Vui lòng chọn ít nhất một !");
						}
					});
				}

			});
			
			//upload
			
			$("#Import").click(function(){
				var fd = new FormData();    
				fd.append( 'file-excel',$('input[name="dukientruythu-file"]')[0].files[0]);
				DialogTable.showPropressUnlimit();
				$.ajax({
				  url: 'uploadForm',
				  data: fd,
				  dataType: 'json',
				  processData: false,
				  contentType: false,
				  type: 'POST',
				  success: function(json){
					  
				    if(json.sucess==false){
				    	
				    	$.fileDownload('downloadFile', {
							successCallback : function(url) {
							},
							failCallback : function(responseHtml, url) {
							},
							httpMethod : "GET",
							data : 'filename='+json.fileNameErr
						});
				    }else if(json.sucess==true){
				    	
				    }
				    DialogTable.setHeadAndMess('Thông báo',json.mess);
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
