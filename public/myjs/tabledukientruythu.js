var oTable = null;
var giaTriCu = null;

// so chung tu
var sochungtuOld = '';
// kythue
var kythueOld = '';
// ngay ht
var ngayhtOld = '';
// ngay ct
var ngayctOld = '';
// tieu muc
var tieumucOld = '';
// ma so thue
var masothueOld = '';

var EditableTable = function () {

	return {

		// main function to initiate the module
		init : function () {
			oTable = $('#editbable-sample')
				.dataTable({
					"aLengthMenu" : [[5, 15, 20, -1],
						[5, 15, 20, "All"]// change per
						// page values
						// here
					],
					// set the initial value
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

			/*jQuery('#editable-sample_wrapper .dataTables_filter input')
			.addClass(" medium"); // modify
			// table
			// search
			// input
			jQuery('#editable-sample_wrapper .dataTables_length select')
			.addClass(" xsmall"); // modify
			// table
			// per
			// page
			// dropdown
			 */

			function restoreRow(oTable, nRow) {

				var aData = oTable.fnGetData(nRow);
				var jqTds = $('>td', nRow);

				for (var i = 0, iLen = jqTds.length; i < iLen; i++) {

					oTable.fnUpdate(aData[i], nRow, i, false);
				}

				oTable.fnDraw();
			}

			function editRow(oTable, nRow) {
				var aData = oTable.fnGetData(nRow);

				var jqTds = $('>td', nRow);
				// so chung tu
				sochungtuOld = $('#sochungtu').val().trim();
				// ma so thue
				masothueOld = $('#masothue').val().trim();
				// kythue
				kythueOld = aData[2].trim();
				// ngay ht
				ngayhtOld = $('#dp1').val().trim();
				// ngay ct
				ngayctOld = $('#dp2').val().trim();
				// tieu muc
				tieumucOld = aData[0].trim();

				// ma so thue
				jqTds[0].innerHTML = '<input name="tieumuc" type="text" class=" small" value="'
					 + aData[0] + '">';
				jqTds[1].innerHTML = '<input type="text" class=" small" value="'
					 + aData[1] + ' "disabled>';
				jqTds[2].innerHTML = '<input name="kythue" type="text" class=" small" value="'
					 + aData[2] + '">';
				jqTds[3].innerHTML = '<input name="sotien" type="text" class=" small" value="'
					 + aData[3] + '">';
				jqTds[4].innerHTML = '<a class="edit" href="">Save edit</a>';
				jqTds[5].innerHTML = '<a class="cancel" href="">Cancel</a>';
			}

			function addRow(oTable, nRow) {
				var aData = oTable.fnGetData(nRow);
				var jqTds = $('>td', nRow);
				jqTds[0].innerHTML = '<input onblur="LayMucLucNganSach($(this).parents(\'tr\'))" name="tieumuc" type="text" class=" small" value="'
					 + aData[0] + '">';
				jqTds[1].innerHTML = '<input type="text" class=" small" value="'
					 + aData[1] + ' "disabled>';
				jqTds[2].innerHTML = '<input name="kythue" type="text" class=" small" value="'
					 + aData[2] + '">';
				jqTds[3].innerHTML = '<input name="sotien" type="text" class=" small" value="'
					 + aData[3] + '">';
				jqTds[4].innerHTML = '<a class="edit" href="">Save new</a>';
				jqTds[5].innerHTML = '<a class="cancel" data-mode="new" href="">Cancel</a>';
			}

			function saveRow(oTable, nRow) {
				var jqInputs = $('input', nRow);
				oTable.fnUpdate(jqInputs[0].value, nRow, 0, false);
				oTable.fnUpdate(jqInputs[1].value, nRow, 1, false);
				oTable.fnUpdate(jqInputs[2].value, nRow, 2, false);
				oTable.fnUpdate(jqInputs[3].value, nRow, 3, false);
				oTable.fnUpdate('<a class="edit" href="">Edit</a>', nRow, 4,
					false);
				oTable.fnUpdate('<a class="delete" href="">Delete</a>', nRow,
					5, false);
				oTable.fnDraw();
			}

			function cancelEditRow(oTable, nRow) {
				var jqInputs = $('input', nRow);
				oTable.fnUpdate(jqInputs[0].value, nRow, 0, false);
				oTable.fnUpdate(jqInputs[1].value, nRow, 1, false);
				oTable.fnUpdate(jqInputs[2].value, nRow, 2, false);
				oTable.fnUpdate(jqInputs[3].value, nRow, 3, false);
				oTable.fnUpdate('<a class="edit" href="">Edit</a>', nRow, 4,
					false);
				oTable.fnDraw();
			}

			var nEditing = null;

			/*
			 * $('#editable-sample_new').click(function (e) {
			 * e.preventDefault(); if(nEditing!=null) {
			 * restoreRow(oTable,nEditing); } var aiNew = oTable.fnAddData(['',
			 * '', '', '', '<a class="edit" href="">Edit</a>', '<a
			 * class="cancel" data-mode="new" href="">Cancel</a>' ]); var nRow =
			 * oTable.fnGetNodes(aiNew[0]); addRow(oTable, nRow); nEditing =
			 * nRow; });
			 */

			$('#editable-sample a.delete').live('click', function (e) {
				e.preventDefault();

				if (confirm("Are you sure to delete this row ?") == false) {
					return;
				}

				var nRow = $(this).parents('tr')[0];
				var aData = oTable.fnGetData(nRow);

				sochungtuOld = $('#sochungtu').val().trim();
				ngayhtOld = $('#dp1').val().trim();
				tieumucOld = aData[0].trim();
				kythueOld = aData[2].trim();
				// xoa trong csdl
				$.post("ct.php", {
					xoachungtu : 'true',
					sochungtuOld : sochungtuOld,
					ngayhtOld : ngayhtOld,
					tieumucOld : tieumucOld,
					kythueOld : kythueOld
				}, function (data) {
					console.log(data);

					if (data.ketqua == false) {
						alert('Không thể chứng từ');
						return;
					} else {

						alert('Xóa chứng từ THÀNH CÔNG');
					}

				}, "json");

				// xoa html
				oTable.fnDeleteRow(nRow);
				// alert("Deleted! Do not forget to do some ajax to sync with
				// backend :)");
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

			$('#editable-sample a.edit').live(
				'click',
				function (e) {
				e.preventDefault();

				/*
				 * Get the row as a parent of the link that was clicked
				 * on
				 */
				var nRow = $(this).parents('tr')[0];

				if (nEditing !== null && nEditing != nRow) {
					/*
					 * Currently editing - but not this row - restore
					 * the old before continuing to edit mode Dang edit
					 * 1 dong - chuyen sang edit dong khac
					 */

					var flag = false;
					var jqTds = $('>td', nEditing);
					$.each(jqTds, function (i, val) {
						if (val.textContent == 'Save new') {
							// delete
							flag = true;

						}
					});

					// delete new row
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
					// BEGIN save vao database
					// neu them moi
					// so chung tu
					var sochungtu = $('#sochungtu').val().trim();
					// kythue
					var kythue = jqInputs[2].value;
					// ngay ht
					var ngayht = $('#dp1').val().trim();
					// ngay ct
					var ngayct = $('#dp2').val().trim();
					// tieu muc
					var tieumuc = jqInputs[0].value.trim();
					// ma so thue
					var masothue = $('#masothue').val().trim();
					// so tien
					var sotien = jqInputs[3].value.trim();
					// ma_cbql
					var ma_cbql = '7010926016';

					// vadilation
					if (sochungtu.length <= 0 || kythue.length <= 0
						 || ngayht.length <= 0 || ngayct.length <= 0
						 || tieumuc.length <= 0
						 || masothue.length <= 0
						 || sotien.length <= 0) {
						alert('Không bỏ trống thông tin !');
						return;
					}

					$.post("ct.php", {
						themchungtu : 'true',
						sochungtunew : sochungtu,
						tieumuc : tieumuc,
						ngayht : ngayht,
						ngayct : ngayct,
						masothue : masothue,
						kythue : kythue,
						sotien : sotien
					}, function (data) {

						if (data.ketqua == false) {
							alert('Thêm chứng từ thất bại');
							return;
						} else {
							alert('Thêm chứng từ THÀNH CÔNG');
						}

					}, "json");

					// END save vao database

					// save GUI html
					saveRow(oTable, nEditing);
					nEditing = null;
					// alert("Updated! Do not forget to do some ajax to
					// sync with backend :)");
				} else if (nEditing == nRow
					 && this.innerHTML == "Save edit") {
					/* Editing this row and want to save it */
					var jqInputs = $('input', nRow);
					// BEGIN save vao database
					// neu them moi
					// so chung tu
					var sochungtu = $('#sochungtu').val().trim();
					// kythue
					var kythue = jqInputs[2].value;
					// ngay ht
					var ngayht = $('#dp1').val().trim();
					// ngay ct
					var ngayct = $('#dp2').val().trim();
					// tieu muc
					var tieumuc = jqInputs[0].value.trim();
					// ma so thue
					var masothue = $('#masothue').val().trim();
					// so tien
					var sotien = jqInputs[3].value.trim();
					// ma_cbql
					var ma_cbql = '7010926016';

					// vadilation
					if (sochungtu.length <= 0 || kythue.length <= 0
						 || ngayht.length <= 0 || ngayct.length <= 0
						 || tieumuc.length <= 0
						 || masothue.length <= 0
						 || sotien.length <= 0) {
						alert('Không bỏ trống thông tin !');
						return;
					}

					$.post("ct.php", {
						suachungtu : 'true',

						sochungtuOld : sochungtuOld,
						masothueOld : masothueOld,
						kythueOld : kythueOld,
						ngayhtOld : ngayhtOld,
						ngayctOld : ngayctOld,
						tieumucOld : tieumucOld,

						sochungtunew : sochungtu,
						tieumuc : tieumuc,
						ngayht : ngayht,
						ngayct : ngayct,
						masothue : masothue,
						kythue : kythue,
						sotien : sotien
					}, function (data) {

						if (data.ketqua == false) {
							alert('Sửa chứng từ thất bại');
							return;
						} else {
							alert('Sửa chứng từ THÀNH CÔNG');
						}
					}, "json");

					// END save vao database

					// save GUI html
					saveRow(oTable, nEditing);
					nEditing = null;
					// alert("Updated! Do not forget to do some ajax to
					// sync with backend :)");
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

function deleteAllRows() {

	var oSettings = oTable.fnSettings();
	var iTotalRecords = oSettings.fnRecordsTotal();
	for (i = 0; i <= iTotalRecords; i++) {
		oTable.fnDeleteRow(0, null, true);
	}

}

function RedirectEdit(parent) {

	var mst = $('td', parent)[1].textContent;

	var kythue = $('#kythue').val();

	postAndRedirect('themdukiendoanhso', {
		HanhDong : 'LoadFormSua',
		KyThue : kythue,
		MaSoThue : mst
	});

}

function RedirectThem() {
	getAndRedirect('themdukiendoanhso', {});
}

function deleterow(parent) {

	var mst = $('td', parent)[1].textContent;

	var kythue = $('#kythue').val();

	BootstrapDialog.confirm({

		title : 'Cảnh báo',
		message : 'Bạn có thật sự chắc chắn với hành động này?',
		type : BootstrapDialog.TYPE_WARNING, // <-- Default value is
		// BootstrapDialog.TYPE_PRIMARY
		closable : true, // <-- Default value is false
		draggable : true, // <-- Default value is false
		btnCancelLabel : 'Quay lại', // <-- Default value is 'Cancel',
		btnOKLabel : 'Xóa !', // <-- Default value is 'OK',
		btnOKClass : 'Xóa !', // <-- If you didn't specify it, dialog type
		// will be used,
		callback : function (result) {
			// result will be true if button was click, while it will be false
			// if users close the dialog directly.
			if (result) {
				postAndRedirect('index', {
					xoadoanhsodukien : true,
					KyThue : kythue,
					MaSoThue : mst
				});
			} else {
				alert('Nope.');
			}
		}
	});

}

function XoaSelect() {
	var checkboxs = $("input.check_item:checked").parents('tr');

	if (checkboxs.length > 0) {
		var MaSoThueData = new Array();
		for (i = checkboxs.length - 1; i >= 0; i--) {
			var MaSoThue = $('td', checkboxs[i])[1].textContent;
			MaSoThueData.push(MaSoThue);

			// $().scroll(postAndRedirect())
		}

		BootstrapDialog.confirm({
			title : 'Cảnh báo',
			message : 'Bạn có thật sự chắc chắn với hành động này?',
			type : BootstrapDialog.TYPE_WARNING, // <-- Default value is
			// BootstrapDialog.TYPE_PRIMARY
			closable : true, // <-- Default value is false
			draggable : true, // <-- Default value is false
			btnCancelLabel : 'Quay lại', // <-- Default value is 'Cancel',
			btnOKLabel : 'Xóa !', // <-- Default value is 'OK',
			btnOKClass : 'Xóa !', // <-- If you didn't specify it, dialog type
			// will be used,
			callback : function (result) {
				// result will be true if button was click, while it will be
				// false if users close the dialog directly.
				if (result) {

					var kythue = $("#kythue").val();

					postAndRedirect('index', {
						XoaNhieu : true,
						MaSoThue : MaSoThueData,
						KyThue : kythue
					});
				}
			}
		});

	} else {
		BootstrapDialog.show({
			message : 'Vui lòng chọn dự kiến doanh số trước khi xóa !'
		});
	}

}

jQuery(document)
.ready(
	function () {

	EditableTable.init();

	var kythue = $("#kythue").val();

	if ($("#kythue").val() == "") {
		var today = new Date();

		$("#kythue").val(
			$.datepicker.formatDate("mm-yy", today));
		$("#kythue").datepicker('update',
			$.datepicker.formatDate("mm-yy", today));
		$("#kythue").unbind();
	}

	if ($("#kythue").val() != "") {
		var kythue = $("#kythue").val();
		$("#progress_kythue").css('display', 'block');
		$.post(
			"dsdukiendsbykythue", {
			kythue : kythue
		},
			function (data) {

			deleteAllRows();

			for (i = 0; i < data.length; i++) {
				oTable
				.fnAddData([
						'<th style="width: 12.0909091234207px;" class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label=""><input class="check_item" type="checkbox" class="group-checkable"></th>',
						data[i]['nguoinopthue']['MaSoThue'],
						data[i]['nguoinopthue']['TenHKD'],
						data[i]['nguoinopthue']['DiaChiCT'],
						data[i]['DoanhSo'],
						'<button onclick="RedirectEdit($(this).parents(\'tr\'))" class="btn btn-primary"><i class="icon-pencil"></i></button><button onclick="deleterow($(this).parents(\'tr\'))" class="btn btn-danger"><i class="icon-trash "></i></button>']);

			}

			$("#progress_kythue").css(
				'display', 'none');

			$("html").getNiceScroll().resize();

		}, "json");
	}

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

	$('#dpMonths').datepicker().on('changeDate', function (ev) {
		// request lay json danh sach nnt
		var kythue = $.datepicker.formatDate("mm-yy", ev.date);

		$("#progress_kythue").css('display', 'block');

		//post
		$.post(
			"dsdukiendsbykythue", {
			kythue : kythue
		},
			function (data) {

			deleteAllRows();

			for (i = 0; i < data.length; i++) {
				oTable
				.fnAddData([
						'<th style="width: 12.0909091234207px;" class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label=""><input class="check_item" type="checkbox" class="group-checkable"></th>',
						data[i]['nguoinopthue']['MaSoThue'],
						data[i]['nguoinopthue']['TenHKD'],
						data[i]['nguoinopthue']['DiaChiCT'],
						data[i]['DoanhSo'],
						'<button onclick="RedirectEdit($(this).parents(\'tr\'))" class="btn btn-primary"><i class="icon-pencil"></i></button><button class="btn btn-danger"><i class="icon-trash "></i></button>']);

			}

			$(
				"#progress_kythue")
			.css(
				'display',
				'none');

		}, "json");
	});

	$("#kythue").blur(
		function () {

		var kythue = $("#kythue").val();
		$("#progress_kythue").css('display',
			'block');

		$.post(
			"dsdukiendsbykythue", {
			kythue : kythue
		},
			function (data) {

			deleteAllRows();

			for (i = 0; i < data.length; i++) {
				oTable
				.fnAddData([
						'<th style="width: 12.0909091234207px;" class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label=""><input class="check_item" type="checkbox" class="group-checkable"></th>',
						data[i]['nguoinopthue']['MaSoThue'],
						data[i]['nguoinopthue']['TenHKD'],
						data[i]['nguoinopthue']['DiaChiCT'],
						data[i]['DoanhSo'],
						'<button onclick="RedirectEdit($(this).parents(\'tr\'))" class="btn btn-primary"><i class="icon-pencil"></i></button><button class="btn btn-danger"><i class="icon-trash "></i></button>']);

			}

			$(
				"#progress_kythue")
			.css(
				'display',
				'none');

		}, "json");
	});

});
