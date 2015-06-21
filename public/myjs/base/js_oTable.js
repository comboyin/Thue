



var EditableTable = function () {

	

	// Cac biến khai báo ở đâu để làm biến toàn cục trong đối tượng nay
	// Nó là các khóa chính để xóa hay sửa 1 dòng
	
	// cansuaxoa
	// cansuaedit
	var _masothueOld = "";
	
	
	return {
		

		
		
		
		init : function () {
			
			this.oTable = $('#editable-sample')
			.dataTable({
				
				"aLengthMenu" : [[5, 15, 20, -1],
					[5, 15, 20, "All"]// change per
					// page values
					// here
				],
				// set the initial value
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
			
			
			function XoaNhieuDong(oTable,Rows)
			{
				$.each(Rows,function(key,value){
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

				// cansuaxoa
				// cansuaedit
				_masothueOld =aData[1].trim();

				
				
				// cansuaedit
				// khi click edit trên 1 dòng
				// chuyển tất cả các ô trên dòng thành input
				jqTds[0].innerHTML = '<input class="check_item" type="checkbox">';
				jqTds[1].innerHTML = '<input style="width:80px;" type="text" style="width:80px;" name="masothue"  value="'
					 + aData[1] + ' "disabled>';
				jqTds[2].innerHTML = '<input style="width:80px;" name="tengoi" type="text"  value="'
					 + aData[2] + '">';
				jqTds[3].innerHTML = '<input style="width:30px;" name="thutu" type="text"  value="'
					 + aData[3] + '">';

	

				jqTds[20].innerHTML = '<a class="edit" href="">Save edit</a>';
				jqTds[21].innerHTML = '<a class="cancel" data-mode="edit" href="">Cancel</a>';

			}

			function addRow(oTable, nRow) {
				var aData = oTable.fnGetData(nRow);
				var jqTds = $('>td', nRow);
				
				
				
				// cansuathem
				jqTds[0].innerHTML = '<input class="check_item" type="checkbox">';
				jqTds[1].innerHTML = '<input style="width:80px;" type="text" style="width:80px;" name="masothue"  value="'
					 + aData[1] + ' ">';
				jqTds[2].innerHTML = '<input style="width:80px;" name="tengoi" type="text"  value="'
					 + aData[2] + '">';
				jqTds[3].innerHTML = '<input style="width:30px;" name="thutu" type="text"  value="'
					 + aData[3] + '">';
				jqTds[20].innerHTML = '<a class="edit" href="">Save new</a>';
				jqTds[21].innerHTML = '<a class="cancel" data-mode="new" href="">Cancel</a>';

				

			}

			function saveRow(oTable, nRow) {
				var jqInputs = $('input', nRow);
				oTable.fnUpdate('<input class="check_item" type="checkbox">', nRow, 0, false);

				// cansuaedit
				// cansuathem
				oTable.fnUpdate(jqInputs[1].value, nRow, 1, false);
				oTable.fnUpdate(jqInputs[2].value, nRow, 2, false);
				oTable.fnUpdate(jqInputs[3].value, nRow, 3, false);
				oTable.fnUpdate(jqInputs[4].value, nRow, 4, false);


				oTable.fnUpdate('<a class="edit" href="">Edit</a>', nRow, 20,
					false);
				oTable.fnUpdate('<a class="delete" href="">Delete</a>', nRow,
					21, false);
				oTable.fnDraw();
			}

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
			
			
			function SaveNew(method,url,data,oTable,nEditing){
				if(method=="get"){
					$.get(url, data, function (json) {
						
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
					return nEditing;
				}
				else if(method=="post"){
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
								
								$.get(url, data,function (json) {
									if (json.kq == false) {
										restoreRow(oTable,nEditing);
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

										saveRow(oTable,nEditing);
										nEditing = null;
										return nEditing;
									}
								},"json");
								return nEditing;
							} 
							
							else {$.post(url, data,function(json) {
									if (json.kq == false) {
										
										restoreRow(oTable,nEditing);
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
								},"json");
							
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
			function Xoa(method,url,data,oTable,nRow){
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
							
							if(method=="get"){
								$.get(url, data , function (json) {

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
							}
							else if(method=="post"){
								$.post(url, data, function (json) {

									if (json.kq== false) {
										
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

			
			// cansua sort
			oTable.fnSort([[2, 'asc']]);

			
			function XoaNhieuDong(checkboxs,method,url,data,oTable){
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
							if(method=="get")
								{
									$.get(url, data, function (
											json) {
	
										if (data.ketqua == false) {
											BootstrapDialog
											.confirm({
												title : 'Thông báo',
												message : json.messenger,
											});
	
										} else {
											XoaNhieuDong(checkboxs);
											BootstrapDialog
											.confirm({
												title : 'Thông báo',
												message : json.messenger,
											});
										}
									}, 'json');
								}
							else if(method=="post")
								{
								$.post(url, data, function (
										json) {

									if (data.ketqua == false) {
										BootstrapDialog
										.confirm({
											title : 'Thông báo',
											message : json.messenger,
										});

									} else {
										XoaNhieuDong(checkboxs);
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
				var checkboxs = $("input.check_item:checked").parents('tr');

				if (checkboxs.length > 0) {
					
					// cansuaxoanhieu
					
					var MaSoThueData = new Array();
					for (i = checkboxs.length - 1; i >= 0; i--) {
						var MaSoThue = $('td', checkboxs[i])[1].textContent;
						MaSoThueData.push(MaSoThue);
					}
					
					data = {};
					
					var url = "";
					
					XoaNhieuDong(checkboxs,'get','url.php',data,oTable);
					
					

				} else {
					BootstrapDialog.show({
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
			
			var nEditing = null;

			$('#editable-sample_new')
			.click(
				function (e) {
				e.preventDefault();
				if (nEditing != null) {
					restoreRow(oTable, nEditing);
				}
				
				
				// cansua new data khi nhan nut them moi
				var aiNew = oTable
					.fnAddData([
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
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
			
			
			$('#editable-sample a.Delete').live('click', function (e) {
				e.preventDefault();

				var nRow = $(this).parents('tr')[0];
				var aData = oTable.fnGetData(nRow);
				
				
				// cansuaxoa
				//Lấy dử liệu
				
				
				//data truyền lên
				data = {

				};
				
				var url = '';
				
				Xoa('get','abc.php',data,oTable,nRow);
				
			});
			// nhan cancel
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

			$('#editable-sample a.edit')
			.live(
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

				}
				else if (nEditing == nRow
					 && this.innerHTML == "Save new") {
					/* Editing this row and want to save it */
					var jqInputs = $('input', nRow);
				
					//cansuathem Save new
					
					//lay du lieu
					var masothue = jqInputs[1].value.trim();
					var tengoi = jqInputs[2].value.trim();
					var thutu = jqInputs[3].value.trim();
					var diachi = jqInputs[4].value.trim();
					
					//dang lam
					
					nEditing = saveNew(method,url,data,oTable,nEditing);
					
					
					

					// alert("Updated! Do not forget to do some
					// ajax to
					// sync with backend :)");
				} else if (nEditing == nRow
					 && this.innerHTML == "Save edit") {

					var jqInputs = $('input', nRow);

					//cansuaedit
					
					//lay du lieu
					var tengoi = jqInputs[2].value.trim();
					var thutu = jqInputs[3].value.trim();
					var diachi = jqInputs[4].value.trim();

					// vadilation
					if (diachi.length <= 0 || tengoi.length <= 0) {
						BootstrapDialog
						.confirm({
							title : 'Cảnh báo',
							message : "Không được bỏ trống thông tin quan trọng: \n Tên gọi, địa chỉ !",
						});
						return;
					}
					
					nEditing = SaveEdit(method,url,data,oTable,nEditing);
					
					

					

				
				} else {
					/* No edit in progress - let's start one */
					editRow(oTable, nRow);
					nEditing = nRow;
				}
			});
		}

	};

}
();


jQuery(document).ready(function () {

	EditableTable.init();

});
