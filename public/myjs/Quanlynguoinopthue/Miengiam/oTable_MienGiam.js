
var EditableTableMienGiam = function () {

	//key
	var _SoQDMG = "";
	var oTable = null;

	return {

		init : function () {
			
			//*******************ONLY PAGE BEGIN************************//
			
			
			
			$("#editable-miengiam_wrapper input[name='NgayCoHieuLuc']").live('focus', function(){
			    if (false == $(this).hasClass('hasDatepicker')) {
			    	$(this).datepicker({ 
				    	format: 'dd-mm-yyyy'
				    });
			    }
			});
			
			

			
			
			
			$('#reservation').daterangepicker({
                format: 'DD-MM-YYYY'
              }, function(start, end, label) {
            	  $("h1.SoQDMG").html('');
            	  start1 = $.datepicker.formatDate("yy/mm/dd", start._d);
            	  end1 = $.datepicker.formatDate("yy/mm/dd", end._d);
            	  DialogTable.showPropressUnlimit();
            	  deleteAllRows();
            	  $.get('danhSachMienGiamGiuaNgay',{
            		  start:start1,
            		  end:end1
            	  },function(json){	
            		  if(json.kq == true){
            			  
            			  data = json.obj;
            			  
            			  $.each(data,function(key,value){
    			    		oTable.fnAddData([
    			    		             '<span class="label label-success"><a class="CTMienGiam" href>Chi tiết</a></span>',     
    			    		         value.SoQDMG,
    			    		         $.datepicker.formatDate("dd-mm-yy",new Date(value.NgayCoHieuLuc.date)),
    			    		         
    			    		         value.LyDo==null?'':value.LyDo,
    			    		         value.nguoinopthue.MaSoThue,
    			    		         value.nguoinopthue.TenHKD,
    			    		         value.MaTTNgungNghi==null?'':value.MaTTNgungNghi,
    				                 '<a class="edit" href="">Edit</a>', '<a class="Delete" href="">Delete</a>'
    				                  ]);
	        			    		
	        			    	
            			  });
            			  
            			 
            			  
            		  }
            		  DialogTable.setHeadAndMess('Thông báo',json.messenger);
            		  
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
			oTable = $('#editable-miengiam')
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
				_SoQDMG = aData[1].trim();
				
				
				/*_MaSoThue = $("input[name='masothue']", nRow).val().trim();
				_TieuMuc = $("input[name='TieuMuc']", nRow).val().trim();*/
				

				// cansua
				// khi click edit trên 1 dòng
				// chuyển tất cả các ô trên dòng thành input
				
				
				jqTds[0].innerHTML = '<span class="label label-success"><a class="CTMienGiam" href>Chi tiết</a></span>';
				
				jqTds[1].innerHTML = '<input style="width:100px;" name="SoQDMG" type="text"  value="'
					 + aData[1] + '">';
				jqTds[2].innerHTML = '<input type="text" style="width:80px;" name="NgayCoHieuLuc" required="required"  class="m-ctrl-medium popovers" value="'
					 + aData[2] + '">';
				jqTds[3].innerHTML = '<input type="text" style="width:80px;" name="LyDo" type="text" value="'
					 + aData[3] + '">';
				jqTds[4].innerHTML = '<input style="width:80px;" name="MaSoThue" type="text"  value="'
					 + aData[4] + ' "disabled><button style="margin:0 20px;margin-top:2px" class="btn btn-primary DialogNNT">Chọn</button>';
				jqTds[5].innerHTML = '<input style="width:80px;" name="TenHKD" type="text"  value="'
					 + aData[5] + '"disabled>';
				jqTds[6].innerHTML = '<input style="width:80px;" name="MaTTNgungNghi" type="text"  value="'
					 + aData[6] + ' "disabled><button style="margin:0 20px;margin-top:2px" class="btn btn-primary DialogTTNgungNghi">Chọn</button>';
				jqTds[7].innerHTML = '<a class="edit" href="">Save edit</a>';
				jqTds[8].innerHTML = '<a class="cancel" data-mode="edit" href="">Cancel</a>';
				
				//update kích thước cột
				oTable.fnAdjustColumnSizing();

			}

			function addRow(oTable, nRow) {
				var aData = oTable.fnGetData(nRow);
				var jqTds = $('>td', nRow);
				
				jqTds[0].innerHTML = '<span class="label label-success"><a class="CTMienGiam" href>Chi tiết</a></span>';
				
				jqTds[1].innerHTML = '<input style="width:100px;" name="SoQDMG" type="text"  value="'
					 + aData[1] + '">';
				jqTds[2].innerHTML = '<input type="text" style="width:80px;" name="NgayCoHieuLuc" required="required"  class="m-ctrl-medium popovers" value="'
					 + aData[2] + '">';
				jqTds[3].innerHTML = '<input type="text" style="width:80px;" name="LyDo" type="text" value="'
					 + aData[3] + '">';
				jqTds[4].innerHTML = '<input style="width:80px;" name="MaSoThue" type="text"  value="'
					 + aData[4] + ' "disabled><button style="margin:0 20px;margin-top:2px" class="btn btn-primary DialogNNT">Chọn</button>';
				jqTds[5].innerHTML = '<input style="width:80px;" name="TenHKD" type="text"  value="'
					 + aData[5] + ' "disabled>';
				jqTds[6].innerHTML = '<input style="width:80px;" name="MaTTNgungNghi" type="text"  value="'
					 + aData[6] + ' "disabled><button style="margin:0 20px;margin-top:2px" class="btn btn-primary DialogTTNgungNghi">Chọn</button>';
				jqTds[7].innerHTML = '<a class="edit" href="">Save new</a>';
				jqTds[8].innerHTML = '<a class="cancel" data-mode="new" href="">Cancel</a>';
				
				oTable.fnAdjustColumnSizing();

			}

			function saveRow(oTable, nRow) {
				var jqInputs = $('input', nRow);
				
				oTable.fnUpdate('<span class="label label-success"><a class="CTMienGiam" href>Chi tiết</a></span>', nRow, 0, false);

				// cansua
				oTable.fnUpdate(jqInputs[0].value, nRow, 1, false);
				oTable.fnUpdate(jqInputs[1].value, nRow, 2, false);
				oTable.fnUpdate(jqInputs[2].value, nRow, 3, false);
				oTable.fnUpdate(jqInputs[3].value, nRow, 4, false);
				oTable.fnUpdate(jqInputs[4].value, nRow, 5, false);
				oTable.fnUpdate(jqInputs[5].value, nRow, 6, false);


				oTable.fnUpdate('<a class="edit" href="">Edit</a>', nRow, 7,
					false);
				oTable.fnUpdate('<a class="Delete" href="">Delete</a>', nRow,
					8, false);
				oTable.fnDraw();
			}
			
			
			var nEditing = null;
			//Thêm 1 dòng mới
			$('#editable-miengiam_new').click(
				function (e) {
				e.preventDefault();
				if (nEditing != null) {
					restoreRow(oTable, nEditing);
				}

				// cansua
				var aiNew = oTable
					.fnAddData([
					        '<span class="label label-success"><a class="CTMienGiam" href>Chi tiết</a></span>',    
							'',
							'',
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
			});

			

			function SaveNew(method, url, data, oTable, nEditing) {

				if (method == "get") {
					$.get(url, data, function (json) {

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

			}

			jQuery('#editable-miengiam_wrapper .dataTables_filter input')
			.addClass(" span6"); // modify

			jQuery('#editable-miengiam_wrapper .dataTables_length select')
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
				var checkboxs = $("#editable-miengiam input.check_item:checked").parents('tr');
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

			

			$('#editable-miengiam a.cancel').live('click', function (e) {
				e.preventDefault();

				if ($(this).attr("data-mode") == "new") {
					var nRow = $(this).parents('tr')[0];

					oTable.fnDeleteRow(nRow);
				} else {

					restoreRow(oTable, nEditing);
					nEditing = null;
				}
			});

			$('#tablemiengiam a.Delete').live('click', function (e) {
				
				
				
				e.preventDefault();

				var nRow = $(this).parents('tr')[0];
				var aData = oTable.fnGetData(nRow);

				// cansuaxoa
				
				
				_SoQDMG = aData[1].trim();
				
				
				//thong bao ban co muon xoa chung tu nay khong ? 
				BootstrapDialog.confirm({
					title : 'Cảnh báo',
					message : 'Có thật sự muốn xóa miễn giảm này ?',
					type : BootstrapDialog.TYPE_WARNING, // <--
					closable : true,
					draggable : true,
					btnCancelLabel : 'No',
					btnOKLabel : 'Yes',
					btnOKClass : 'Yes',
					callback : function (result) {
						
						// neu nhan ok => hanh dong xoa
						if (result) {
							
							$.get('SoChiTietCuaMienGiam',{
								SoQDMG:_SoQDMG
							},function(json){
								if(json.count>0){
									BootstrapDialog.confirm({
										title : 'Cảnh báo',
										message : 'miễn giảm này hiện có '+json.count+ ' chi tiết miễn giảm, bạn có muốn tiếp tục ?',
										type : BootstrapDialog.TYPE_WARNING, // <--
										closable : true,
										draggable : true,
										btnCancelLabel : 'No',
										btnOKLabel : 'Yes',
										btnOKClass : 'Yes',
										callback : function (result) {
											
											// neu nhan ok => hanh dong xoa
											if (result) {
												
												data = {
														SoQDMG:_SoQDMG
													};
													
													
													var url = "xoa";
													Xoa('post', url, data, oTable, nRow);
												
											} else {
												
											}
										}
									});
								}else{
									data = {
											SoQDMG:_SoQDMG
										};
										
										
										var url = "xoa";
										Xoa('post', url, data, oTable, nRow);
								}
							},'json');
							
							
						} else {
							
						}
					}
				});
				
				
				

			});

			$('#editable-miengiam a.edit').live(
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


					
					
					var NgayCoHieuLuc= $("input[name='NgayCoHieuLuc']", nRow).val().trim();
					var LyDo= $("input[name='LyDo']", nRow).val().trim();
					var SoQDMG= $("input[name='SoQDMG']", nRow).val().trim();
					var MaSoThue = $("input[name='MaSoThue']", nRow).val().trim();
					var MaTTNgungNghi = $("input[name='MaTTNgungNghi']", nRow).val().trim();
					
					data = {
							NgayCoHieuLuc : NgayCoHieuLuc,
							LyDo : LyDo,
							SoQDMG : SoQDMG,
							MaSoThue : MaSoThue,
							MaTTNgungNghi : MaTTNgungNghi
					}

					var url = 'them';
					
					SaveNew('post', url, data, oTable, nEditing);

				} else if (nEditing == nRow
					 && this.innerHTML == "Save edit") {

					var jqInputs = $('input', nRow);

					//cansuaedit

					//lay du lieu					
					var SoQDMG = $("input[name='SoQDMG']", nRow).val().trim();
					var NgayCoHieuLuc = $("input[name='NgayCoHieuLuc']", nRow).val().trim();
					var MaSoThue = $("input[name='MaSoThue']", nRow).val().trim();
					var LyDo = $("input[name='LyDo']", nRow).val().trim();
					var MaTTNgungNghi = $("input[name='MaTTNgungNghi']", nRow).val().trim();
					
					
					
					data = {
						_SoQDMG : _SoQDMG,
						
						SoQDMG : SoQDMG,
						LyDo : LyDo,
						MaSoThue : MaSoThue,
						NgayCoHieuLuc : NgayCoHieuLuc,
						MaTTNgungNghi : MaTTNgungNghi
					}

					var url = "sua";

					

					SaveEdit('post', url, data, oTable, nEditing);

				} else {

					editRow(oTable, nRow);
					nEditing = nRow;
				}
			});

			
			
			// dialogTable
			$('#editable-miengiam button.DialogNNT').live('click', function (e) {
				//lấy dòng được chọn
				var nRow = $(this).parents('tr')[0];
				//get input có name là "masothue"
				MaSoThue = $("input[name='MaSoThue']", nRow);
				TenHKD = $("input[name='TenHKD']", nRow);
				
				
				DialogTable.showFromUrl('get',baseUrl('application/Service/danhsachNNT'),{},function(){
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
			
			
			//
			

			$('#editable-miengiam button.DialogTieuMuc').live('click', function (e) {

				var nRow = $(this).parents('tr')[0];

				TieuMuc = $("input[name='TieuMuc']", nRow);

				DialogTable.showFromUrl('get','muclucngansach',{}, function () {
					
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
			
			$('#editable-miengiam button.DialogTTNgungNghi').live('click', function (e) {

				MaSoThue = $("input[name='MaSoThue']", nRow);
				//console.log(MaSoThue);
				if(MaSoThue.val().trim() != ""){
					var nRow = $(this).parents('tr')[0];
	
					MaTTNgungNghi = $("input[name='MaTTNgungNghi']", nRow);

					DialogTable.showFromUrl('get',baseUrl('application/Service/thongtinngungnghi'),{MaSoThue : MaSoThue.val().trim()}, function () {
						
						checkboxs = $('#DialogTable input.check_item:checked').parents("tr");
	
						if (checkboxs.length == 1) {
							
							var MaTTNgungNghiString = $('td', checkboxs[0])[1].textContent;
							
							$("#DialogTable").modal("hide");
							
							MaTTNgungNghi.val(MaTTNgungNghiString);

						} else {
							alert("Vui lòng chọn ít nhất một !");
						}
					});
				}
				else
				{
					alert("Vui lòng chọn mã số thuế trước !");
				}

			});
			
			
			//upload
			
			$("#Import").click(function(e){
				e.preventDefault();
				var fd = new FormData();    
				fd.append( 'file-excel',$('input[name="file-excel"]')[0].files[0]);
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
				    	
				    	/*$.fileDownload(baseUrl("application/Service/downloadFile"), {
							successCallback : function(url) {
							},
							failCallback : function(responseHtml, url) {
							},
							httpMethod : "GET",
							data : 'filename='+json.fileNameErr
						});*/
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

	EditableTableMienGiam.init();
});
