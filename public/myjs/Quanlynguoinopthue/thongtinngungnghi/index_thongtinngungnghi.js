
var EditableTableThongtinngungnghi = function () {

	//key
	var _MaSoThue = '';
	var oTable = null;

	return {

		init : function () {
			
			//*******************ONLY PAGE BEGIN************************//
			
			$("a.TimNNT").click(function(){
				DialogTable.showFromUrl('get',baseUrl('application/Service/danhsachNNT'),{},function(){
					checkboxs = $('#DialogTable input.check_item:checked').parents("tr");
					$("img.loading").css('display','inline');
					if (checkboxs.length == 1) {
						var MaSoThueString = $('td', checkboxs[0])[1].textContent;
						var TenHKDString = $('td', checkboxs[0])[2].textContent;
						var SoCMNDString = $('td', checkboxs[0])[3].textContent;
						var DiaChiCTString = $('td', checkboxs[0])[4].textContent;
						var SoGPKDString = $('td', checkboxs[0])[5].textContent;
						var NgayCapMSTString = $('td', checkboxs[0])[6].textContent;
						var ThoiDiemBDKDString = $('td', checkboxs[0])[7].textContent;
						var NgheKDString  = $('td', checkboxs[0])[8].textContent;
						
						$("span.MaSoThue").html(MaSoThueString);
						$("span.TenHKD").html(TenHKDString);
						$("span.SoCMND").html(SoCMNDString);
						$("span.DiaChiCT").html(DiaChiCTString);
						$("span.SoGPKD").html(SoGPKDString);
						$("span.NgayCapMST").html(NgayCapMSTString);
						$("span.ThoiDiemBDKD").html(ThoiDiemBDKDString);
						$("span.NgheKD").html(NgheKDString);
						
						
						
						$("#DialogTable").modal("hide");
						
						/// reset table
						deleteAllRows();
						$.get('ajaxDanhSachThongTinNgungNghi',{
		            		  MaSoThue : MaSoThueString
		            	  },function(json){
		            		  if(json.kq == true){
		            			  
		            			  data = json.obj;
		            			  
		            			  $.each(data,function(key,value){
		    			    		oTable.fnAddData([
		    			    		             value.MaTTNgungNghi,     
		    			    		             $.datepicker.formatDate("dd-mm-yy",new Date(value.TuNgay.date)),
		    			    		         $.datepicker.formatDate("dd-mm-yy",new Date(value.DenNgay.date)),
		    			    		         value.LyDo,
		    			    		         $.datepicker.formatDate("dd-mm-yy",new Date(value.NgayNopDon.date)),
		    				                 '<a class="edit" href="">Edit</a>', '<a class="Delete" href="">Delete</a>'
		    				                  ]);
			        			    		
			        			    	
		            			  });
		            			  
		            			 $("#editable-sample").attr('masothue',MaSoThueString);
		            			  
		            		  }
		            		  $("img.loading").css('display','none');
		            		  
		            	  },'json');
						
					} else {
						alert("Vui lòng chọn ít nhất một !");
					}
				});
			});
			
			
			$("#editable-sample input[name='TuNgay']").live('focus', function(){
			    if (false == $(this).hasClass('hasDatepicker')) {
			    	$(this).datepicker({ 
				    	format: 'dd-mm-yyyy'
				    });
			    }
			});
			$("#editable-sample input[name='DenNgay']").live('focus', function(){
			    if (false == $(this).hasClass('hasDatepicker')) {
			    	$(this).datepicker({ 
				    	format: 'dd-mm-yyyy'
				    });
			    }
			});
			$("#editable-sample input[name='NgayNopDon']").live('focus', function(){
			    if (false == $(this).hasClass('hasDatepicker')) {
			    	$(this).datepicker({ 
				    	format: 'dd-mm-yyyy'
				    });
			    }
			});
			
			

			
			
			
			$('#reservation').daterangepicker({
                format: 'DD-MM-YYYY'
              }, function(start, end, label) {
            	  $("h1.SoChungTu").html('');
            	  start1 = $.datepicker.formatDate("yy/mm/dd", start._d);
            	  end1 = $.datepicker.formatDate("yy/mm/dd", end._d);
            	  DialogTable.showPropressUnlimit();
            	  deleteAllRows();
            	  $.get(baseUrl('application/Service/danhSachChungTuGiuaNgay'),{
            		  start:start1,
            		  end:end1
            	  },function(json){
            		  if(json.kq == true){
            			  
            			  data = json.obj;
            			  
            			  $.each(data,function(key,value){
    			    		oTable.fnAddData([
    			    		             '<span class="label label-success"><a class="CTChungTu" href>Chi tiết</a></span>',     
    			    		         value.SoChungTu,
    			    		         $.datepicker.formatDate("dd-mm-yy",new Date(value.NgayChungTu.date)),
    			    		         value.nguoinopthue.MaSoThue,
    			    		         value.nguoinopthue.TenHKD,
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
						"bVisible": false,
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

				_MaSoThue = _MaSoThue = $("#editable-sample").attr('masothue');
				
				
				
				
				
				
				jqTds[0].innerHTML = '<input style="width:100px;" name="TuNgay" type="text"  value="'
					 + aData[1] + '">';
				jqTds[1].innerHTML = '<input style="width:100px;" name="DenNgay" type="text"  value="'
					 + aData[2] + '">';
				
				
				jqTds[2].innerHTML = '<textarea type="text" style="width:200px;" name="LyDo">'
					 + aData[3] + '</textarea>';
				jqTds[3].innerHTML = '<input style="width:80px;" name="NgayNopDon" type="text"  value="'
					 + aData[4] + '">';
				jqTds[4].innerHTML = '<a class="edit" href="">Save edit</a>';
				jqTds[5].innerHTML = '<a class="cancel" data-mode="edit" href="">Cancel</a>';
				
				//update kích thước cột
				oTable.fnAdjustColumnSizing();

			}

			function addRow(oTable, nRow) {
				var aData = oTable.fnGetData(nRow);
				var jqTds = $('>td', nRow);
				
				jqTds[0].innerHTML = '<input style="width:100px;" name="TuNgay" type="text"  value="'
					 + aData[1] + '">';
				jqTds[1].innerHTML = '<input style="width:100px;" name="DenNgay" type="text"  value="'
					 + aData[2] + '">';
				jqTds[2].innerHTML = '<textarea type="text" style="width:200px;" name="LyDo">'
					 + aData[3] + '</textarea>';
				jqTds[3].innerHTML = '<input style="width:80px;" name="NgayNopDon" type="text"  value="'
					 + aData[4] + '">';
				jqTds[4].innerHTML = '<a class="edit" href="">Save new</a>';
				jqTds[5].innerHTML = '<a class="cancel" data-mode="new" href="">Cancel</a>';
				
				oTable.fnAdjustColumnSizing();

			}

			function saveRow(oTable, nRow) {
				var jqInputs = $('input', nRow);
				var jqTextareas = $('textarea', nRow);
				
				
				// cansua
				oTable.fnUpdate(jqInputs[0].value, nRow, 0, false);
				oTable.fnUpdate(jqInputs[1].value, nRow, 1, false);
				oTable.fnUpdate(jqTextareas[0].value, nRow, 2, false);
				oTable.fnUpdate(jqInputs[2].value, nRow, 3, false);
				


				oTable.fnUpdate('<a class="edit" href="">Edit</a>', nRow, 4,
					false);
				oTable.fnUpdate('<a class="Delete" href="">Delete</a>', nRow,
					5, false);
				oTable.fnDraw();
			}
			
			
			var nEditing = null;
			//Thêm 1 dòng mới
			$('#editable-sample_new').click(
				function (e) {
				e.preventDefault();
				
				if( $("#editable-sample").attr('masothue').length>=10){
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
								'',
								'<a class="edit" href="">Edit</a>',
								'<a class="cancel" data-mode="new" href="">Cancel</a>'
							]);
					var nRow = oTable.fnGetNodes(aiNew[0]);
					addRow(oTable, nRow);
					
					_MaSoThue = $("#editable-sample").attr('masothue');
					nEditing = nRow;
				}
				else{
					DialogTable.showThongBao("Thông báo","Vui lòng tìm và chọn người nộp thuế trước khi thêm mới !");
				}
				
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
			.addClass(" span6"); // modify

			jQuery('#editable-sample_wrapper .dataTables_length select')
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

				MaTTNgungNghi = aData[0].trim();
				
				//thong bao ban co muon xoa chung tu nay khong ? 
				data = {
						MaTTNgungNghi:MaTTNgungNghi
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
					var jqTextarea = $('textarea', nRow);

					//cansua Save new

					//lay du lieu


					
					
					var TuNgay= $("input[name='TuNgay']", nRow).val().trim();
					
					var DenNgay= $("input[name='DenNgay']", nRow).val().trim();
					var LyDo = $("textarea[name='LyDo']", nRow).val().trim();
					var NgayNopDon = $("input[name='DenNgay']", nRow).val().trim();
						
					
					
					data = {
							MaSoThue : _MaSoThue,
							TuNgay : TuNgay,
							DenNgay : DenNgay,
							LyDo : LyDo,
							NgayNopDon :NgayNopDon 
					}

					var url = 'them';
					
					SaveNew('post', url, data, oTable, nEditing);

				} else if (nEditing == nRow
					 && this.innerHTML == "Save edit") {
					
					var jqInputs = $('input', nRow);
					
					
					
					var aData = oTable.fnGetData(nRow);
					//cansuaedit
					
					var TuNgay= $("input[name='TuNgay']", nRow).val().trim();
					var DenNgay= $("input[name='DenNgay']", nRow).val().trim();
					var LyDo = $("textarea[name='LyDo']", nRow).val().trim();
					var NgayNopDon = $("input[name='DenNgay']", nRow).val().trim();
					var MaTTNgungNghi = aData[0];
					
					
					data = {
							MaTTNgungNghi : MaTTNgungNghi,
							MaSoThue : _MaSoThue,
							TuNgay : TuNgay,
							DenNgay : DenNgay,
							LyDo : LyDo,
							NgayNopDon :NgayNopDon 
					}

					var url = "sua";

					

					SaveEdit('post', url, data, oTable, nEditing);

				} else {

					editRow(oTable, nRow);
					nEditing = nRow;
				}
			});

			
			function loadTyLeTinhThue(MaSoThue,TieuMuc){
				
				$.get('loadTyLeTinhThue',{MaSoThue:MaSoThue,TieuMuc:TieuMuc},function(json){
					$("input[name='TiLeTinhThue']").val(json.TyLeTinhThue);
				},'json');
			}
			
			// dialogTable
			$('#editable-sample button.DialogNNT').live('click', function (e) {
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
			

			$('#editable-sample button.DialogTieuMuc').live('click', function (e) {

				var nRow = $(this).parents('tr')[0];

				TieuMuc = $("input[name='TieuMuc']", nRow);

				DialogTable.showFromUrl('get','muclucngansach',{}, function () {
					
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

	EditableTableThongtinngungnghi.init();
});
