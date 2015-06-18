										
function deleterow(parents_tr)
{
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
					HanhDong : '',
					TieuMuc : kythue,
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
		var TieuMuc = new Array();
		for (i = checkboxs.length - 1; i >= 0; i--) {
			var MaSoThue = $('td', checkboxs[i])[1].textContent;
			MaSoThueData.push(MaSoThue);

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
						HanhDong : '',
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

function RedirectEdit(parents_tr)
{
	var mst = $('td', parent)[1].textContent;

	var kythue = $('#kythue').val();

	postAndRedirect('themdukiendoanhso', {
		HanhDong : 'LoadFormSua',
		KyThue : kythue,
		MaSoThue : mst
	});
}

function RedirectThem() {
	getAndRedirect('persit', {});
}