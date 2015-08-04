


var EditableTable = function () {

	var _KyThue = '';
	var _check = 0;

	return {

		init : function () {
			
			//*******************ONLY PAGE BEGIN************************//
			
			$('div.dpMonths').datepicker();
			$('button.ChamBo').click(function(){
				$("img.loading").css('display','inline');
				dsSoChungTu = [];
				
				
				$.each($("#editable-chambo td.group"),function(key,value){
					
					
					
					if($('input',value).attr('checked')=='checked'){
						
						var SoChungTu  = $('span.SoChuongTu',value).html();
						
						dsSoChungTu.push(SoChungTu);
					}
				});
				
				//post
				var data = {
						dsSoChungTu : dsSoChungTu,
						KyThue : _KyThue
				};
				
				$.post(baseUrl("quanlysothue/Chambo/Chambo"),data,function(json){
					$("img.loading").css('display','none');
					
					DialogTable.showThongBaoUnlimit('Thông báo',json.messenger);
					
					if(json.kq==true){
						
				    	KyThue = json.obj;
				    	Thang = KyThue.split('/')[0];
				    	Nam = KyThue.split('/')[1];
				    	var today = new Date(Nam+'-'+Thang+'-01');
						_KyThue = $.datepicker.formatDate("mm/yy", today);
						$("div.dpMonths input").val(_KyThue);
						$("div.dpMonths").datepicker('update', _KyThue);
						loadDSChungTu();
				    }
					
					
				},'json');
			});
			$('div.dpMonths').datepicker().on('changeDate', function (ev) {

				_KyThue = $.datepicker.formatDate("mm/yy", ev.date);
				loadDSChungTu();
				

			});
			//chon chờ duyệt
			function chonChungTu(){
				
				
				
				// uncheck to checked
				if (_check==0) {
					$("#editable-chambo input.check_item").each(function () {
						$(this).attr('checked', true);
						
					});
					_check=1;
				} else {
					$("#editable-chambo input.check_item").each(function () {
						$(this).attr('checked', false);
					});
					_check=0;
				}
				
			}
			$('button.ChonChungTu').click(function(){
				
				chonChungTu();
			});

			if ($("div.dpMonths input").val() == "") {
				var today = new Date();
				_KyThue = $.datepicker.formatDate("mm/yy", today)
				$("div.dpMonths input").val(_KyThue);
				$("div.dpMonths").datepicker('update', _KyThue);

			}
			
			function loadDSChungTu(){
				$("img.loading").css('display','inline');

				deleteAllRows();
				url = baseUrl('quanlysothue/Chambo/loadChungTu');
				//post
				$.get( url, {KyThue : _KyThue},
					function (json) {
					data = json.obj;
					
					for (i = 0; i < data.length; i++) {
						//console.log(data[i]['NgayChungTu']);
						$.each(data[i]['chitietchungtus'],function(key,value){
							oTable
							.fnAddData([
									'<td><input class="check_item" type="checkbox"><span class="SoChuongTu">'+data[i]['SoChungTu']+'</span></td>',
									
									$.datepicker.formatDate("dd-mm-yy",new Date(data[i]['NgayChungTu'].date)) ,
									$.datepicker.formatDate("dd-mm-yy",new Date(value['NgayHachToan'].date)),
									data[i]['nguoinopthue']['MaSoThue'],
									data[i]['nguoinopthue']['TenHKD'],
									value['KyThue'],
									value['SoTien'],
									value['muclucngansach']['TieuMuc']
									
									
									]);
						});
						
						
						
					}

					$("img.loading").css('display','none');
				}, "json");
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
			oTable = $('#editable-chambo')
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
					"sDom" : 'lfr<"giveHeight"t>ip',
					"sPaginationType" : "bootstrap",
					"oLanguage" : {
						"sLengthMenu" : "_MENU_",
						"oPaginate" : {
							"sPrevious" : "Prev",
							"sNext" : "Next"
						}
					},
					"fnDrawCallback": function( oSettings ) {
			            if ( oSettings.aiDisplay.length == 0 )
			            {
			                return;
			            }
			             
			            var nTrs = $('#editable-chambo tbody tr');
			            var iColspan = nTrs[0].getElementsByTagName('td').length;
			            var sLastGroup = "";
			            for ( var i=0 ; i<nTrs.length ; i++ )
			            {
			                var iDisplayIndex = oSettings._iDisplayStart + i;
			                var sGroup = oSettings.aoData[ oSettings.aiDisplay[iDisplayIndex] ]._aData[0];
			                if ( sGroup != sLastGroup )
			                {
			                    var nGroup = document.createElement( 'tr' );
			                    var nCell = document.createElement( 'td' );
			                    nCell.colSpan = iColspan;
			                    nCell.className = "group";
			                    nCell.innerHTML = sGroup;
			                    nGroup.appendChild( nCell );
			                    nTrs[i].parentNode.insertBefore( nGroup, nTrs[i] );
			                    sLastGroup = sGroup;
			                }
			            }
			            },
					"aoColumnDefs" : [{
							'bVisible' : false,
							'aTargets' : [0]
						}
					],
					"aaSortingFixed": [[ 0, 'asc' ]],
			        "aaSorting": [[ 1, 'asc' ]]
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
					 + aData[3] + ' "disabled><button class="btn btn-primary DialogTieuMuc" style="margin:0 13px;margin-top:2px">Chọn</button>';


				jqTds[4].innerHTML = '<input style="width:120px;" name="DoanhSo" type="text"  value="'
					 + aData[4] + '">';
				
				jqTds[5].innerHTML = '<input style="width:40px;" name="TiLeTinhThue" type="text"  value="'
					 + aData[5] + '"disabled><button class="btn btn-primary DialogTiLeTinhThue" style="margin:0 0px;margin-top:2px">Chọn</button>';
				
				jqTds[6].innerHTML = '<input style="width:100px;" name="SoTien" type="text"  value="'
					 + aData[6] + '"disabled>';
				jqTds[7].innerHTML = aData[7];
				jqTds[8].innerHTML = '<input style="width:120px;" name="LyDo" type="text" value="'
					 + aData[8] + '">';
				jqTds[9].innerHTML = '<input style="width:120px;" name="MaCanBo" type="text" value="'
					 + aData[9] + '"disabled>';

				jqTds[10].innerHTML = '<a class="edit" href="">Save edit</a>';
				jqTds[11].innerHTML = '<a class="cancel" data-mode="edit" href="">Cancel</a>';
				
				//update kích thước cột
				oTable.fnAdjustColumnSizing();

			}

			function addRow(oTable, nRow) {
				var aData = oTable.fnGetData(nRow);
				var jqTds = $('>td', nRow);
				var MaCanBo = $("span.MaCanBo").html();
				jqTds[0].innerHTML = '<input class="check_item" type="checkbox">';
				// cansua
				jqTds[1].innerHTML = '<input style="width:80px;" name="masothue" type="text"  value="'
					 + aData[1] + ' "disabled>';
				jqTds[2].innerHTML = '<input style="width:110px;" name="TenHKD" type="text"  value="'

					 + aData[2] + '"disabled>';
					 
				jqTds[3].innerHTML = '<input style="width:60px;" name="TieuMuc" type="text"   value="'
					 + aData[3] + ' "disabled>';


				jqTds[4].innerHTML = '<input style="width:90px;" name="DoanhSo" type="text"  value="'
					 + aData[4] + '">';
				
				jqTds[5].innerHTML = '<input style="width:40px;" name="TiLeTinhThue" type="text"  value="'
					 + aData[5] + '"disabled><button class="btn btn-primary DialogTiLeTinhThue" style="margin:0 0px;margin-top:2px">Chọn</button>';
				
				jqTds[6].innerHTML = '<input style="width:100px;" name="SoTien" type="text"  value="'
					 + aData[6] + '"disabled>';
				jqTds[7].innerHTML = '<span style="color:red;">'+'Chưa ghi sổ'+'</span>';
				jqTds[8].innerHTML = '<input style="width:120px;" name="LyDo" type="text" value="'
					 + aData[8] + '">';
				jqTds[9].innerHTML = '<input style="width:120px;" name="MaCanBo" type="text" value="'
					 + MaCanBo + '"disabled>';
				jqTds[10].innerHTML = '<a class="edit" href="">Save new</a>';
				jqTds[11].innerHTML = '<a class="cancel" data-mode="new" href="">Cancel</a>';
				
				oTable.fnAdjustColumnSizing();

			}

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
				oTable.fnUpdate('<span style="color:red;">'+'Chưa ghi sổ'+'</span>', nRow, 7, false);
				oTable.fnUpdate(jqInputs[7].value, nRow, 8, false);
				oTable.fnUpdate(jqInputs[8].value, nRow, 9, false);

				oTable.fnUpdate('<a class="edit" href="">Edit</a>', nRow, 10,
					false);
				oTable.fnUpdate('<a class="Delete" href="">Delete</a>', nRow,
					11, false);
				oTable.fnDraw();
			}
			
			
			var nEditing = null;
			//Thêm 1 dòng mới
			$('#editable-chambo_new').click(
				function (e) {
				e.preventDefault();
				if (nEditing != null) {
					restoreRow(oTable, nEditing);
				}

				data = {
					KyThue : _KyThue
				};
				DialogTable.showFromUrl('post',baseUrl('quanlysothue/Dukientruythu/DSDuKienThueNotTruyThu'),data, function () {
					
					checkboxs = $('#DialogTable input.check_item:checked').parents("tr");

					if (checkboxs.length == 1) {
						$("#DialogTable").modal("hide");
						
						var TieuMucString = $('td', checkboxs[0])[2].textContent;
						var MaSoThueString = $('td', checkboxs[0])[3].textContent;
						var TenHKDString = $('td', checkboxs[0])[4].textContent;
						
						// cansua
						var aiNew = oTable
							.fnAddData([
									'',
									MaSoThueString,
									TenHKDString,
									TieuMucString,
									0,
									0,
									0,
									'',
									'',
									'',
									'<a class="edit" href="">Edit</a>',
									'<a class="cancel" data-mode="new" href="">Cancel</a>'
								]);
						var nRow = oTable.fnGetNodes(aiNew[0]);
						addRow(oTable, nRow);
						nEditing = nRow;
						
						
						
					} else {
						alert("Vui lòng chọn ít nhất một !");
					}
				});
				
				
				
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
						$("img.loading").css('display','inline');
						// result will be true if button was click, while it
						// will be false
						// if users close the dialog directly.
						if (result) {

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

			jQuery('#editable-chambo_wrapper .dataTables_filter input')
			.addClass(" medium"); // modify

			jQuery('#editable-chambo_wrapper .dataTables_length select')
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
							$("img.loading").css('display','inline');
							if (method == "get") {
								$.get(url, data, function (json) {
									$("img.loading").css('display','none');
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
									$("img.loading").css('display','none');
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
				var checkboxs = $("#editable-chambo input.check_item:checked").parents('tr');
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

			

			$('#editable-chambo a.cancel').live('click', function (e) {
				e.preventDefault();

				if ($(this).attr("data-mode") == "new") {
					var nRow = $(this).parents('tr')[0];

					oTable.fnDeleteRow(nRow);
				} else {

					restoreRow(oTable, nEditing);
					nEditing = null;
				}
			});

			$('#editable-chambo a.Delete').live('click', function (e) {
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

			$('#editable-chambo a.edit').live(
				'click',
				function (e) {
				e.preventDefault();

				var nRow = $(this).parents('tr')[0];
				var MaCB =  $('td',nRow)[9].innerHTML;

				if (nEditing !== null && nEditing != nRow) {
					
					if(MaCB == $('span.MaCanBo').html())
					{

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
					}
					else{
						DialogTable.showThongBaoUnlimit('Thông báo',
								'<span style="color:red" ><h3>Bạn không có quyền sửa dự kiến này !</h3></span>');
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
					//var TrangThai = $("input[name='TrangThai']", nRow).val().trim();
					var LyDo = $("input[name='LyDo']", nRow).val().trim();
					var TiLeTinhThue = $("input[name='TiLeTinhThue']", nRow).val().trim();
					var DoanhSo  = $("input[name='DoanhSo']", nRow).val().trim();
	
					data = {
						KyThue : _KyThue,
						MaSoThue : MaSoThue,
						TieuMuc : TieuMuc,
						SoTien : SoTien,
						//TrangThai : TrangThai,
						LyDo : LyDo,
						TiLeTinhThue:TiLeTinhThue,
						DoanhSo:DoanhSo
					}

					var url = baseUrl('quanlysothue/Dukientruythu/them');
				
					SaveNew('post', url, data, oTable, nEditing);

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
						_KyThue : _KyThue,
						_TieuMuc : _TieuMuc,

						KyThue : _KyThue,
						MaSoThue : MaSoThue,
						TieuMuc : TieuMuc,
						SoTien : SoTien,
						//TrangThai : TrangThai,
						LyDo : LyDo,
						TiLeTinhThue:TiLeTinhThue,
						DoanhSo:DoanhSo
					}

					var url = "sua";

					

					SaveEdit('post', url, data, oTable, nEditing);

				} else {
					
					if(MaCB == $('span.MaCanBo').html())
					{
						editRow(oTable, nRow);
						nEditing = nRow;
					}
					else{
						DialogTable.showThongBaoUnlimit('Thông báo',
								'<span style="color:red" ><h3>Bạn không có quyền sửa dự kiến này !</h3></span>');
					}
					
				}
			});

			
/*			function loadTyLeTinhThue(MaSoThue,TieuMuc){
				
				$.get('loadTyLeTinhThue',{MaSoThue:MaSoThue,TieuMuc:TieuMuc},function(json){
					$("input[name='TiLeTinhThue']").val(json.TyLeTinhThue);
				},'json');
			}*/
			
			// dialogTable
			$('#editable-chambo button.DialogNNT').live('click', function (e) {
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
						//loadTyLeTinhThue(MaSoThueString,TieuMuc);
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
			

			$('#editable-chambo button.DialogTieuMuc').live('click', function (e) {

				var nRow = $(this).parents('tr')[0];

				TieuMuc = $("input[name='TieuMuc']", nRow);

				DialogTable.showFromUrl('get',baseUrl('application/Service/mlnstruythu'),{}, function () {
					
					checkboxs = $('#DialogTable input.check_item:checked').parents("tr");

					if (checkboxs.length == 1) {
						
						var TieuMucString = $('td', checkboxs[0])[1].textContent;
						
						$("#DialogTable").modal("hide");
						
						TieuMuc.val(TieuMucString);
						//MaSoThue = $("input[name='masothue']", nRow).val();
						//loadTyLeTinhThue(MaSoThue,TieuMucString);
					} else {
						alert("Vui lòng chọn ít nhất một !");
					}
				});
				

			});
			
			
			//DialogTiLeTinhThue
			$('#editable-chambo button.DialogTiLeTinhThue').live('click', function (e) {

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
				fd.append( 'dukientruythu-file',$('input[name="dukientruythu-file"]')[0].files[0]);
				DialogTable.showPropressUnlimit();
				$.ajax({
				  url: 'uploadForm',
				  data: fd,
				  dataType: 'json',
				  processData: false,
				  contentType: false,
				  type: 'POST',
				  success: function(json){

					DialogTable.setHeadAndMess('Thông báo',json.mess);
					
				    if(json.sucess==false && typeof(json.fileNameErr) == 'string'){
				    	$.fileDownload(baseUrl("application/Service/downloadFile"), {
							successCallback : function(url) {
							},
							failCallback : function(responseHtml, url) {
							},
							httpMethod : "GET",
							data : 'filename='+json.fileNameErr
						});
				    }
				    else if(json.sucess == true){
				    	KyThue = json.KyThue;
				    	thang = KyThue.split('/')[0];
				    	nam = KyThue.split('/')[1]; 
				    	var today = new Date(nam+'-'+thang+'-01');
						_KyThue = $.datepicker.formatDate("mm/yy", today)
						$("#kythue").val(_KyThue);
						$("#dpMonths").datepicker('update', _KyThue);
						loadDSDK();
				    }
				    	
				    
				    
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
