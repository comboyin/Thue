

function baseUrl (url){
	var baseFolder = '/'+window.location.pathname.split( '/' )[1]+'/';
	
	return baseFolder+url;
	
	
}
function postAndRedirect(url, postData) {
	var postFormStr = "<form method='POST' action='" + url + "'>\n";

	for ( var key in postData) {
		if (postData.hasOwnProperty(key)) {
			postFormStr += "<input type='hidden' name='" + key + "' value='"
					+ postData[key] + "'></input>";
		}
	}

	postFormStr += "</form>";

	var formElement = $(postFormStr);

	$('body').append(formElement);
	$(formElement).submit();
}

function getAndRedirect(url, postData) {
	var postFormStr = "<form method='GET' action='" + url + "'>\n";

	for ( var key in postData) {
		if (postData.hasOwnProperty(key)) {
			postFormStr += "<input type='hidden' name='" + key + "' value='"
					+ postData[key] + "'></input>";
		}
	}

	postFormStr += "</form>";

	var formElement = $(postFormStr);

	$('body').append(formElement);
	$(formElement).submit();
}


/**
 * Get an array of column indexes that match a given property
 *  
 *  @param {string} stringDate 
 *  @returns {Date} Array of indexes with matched properties
 *  @memberof DataTable#oApi
 */
function StringToDate(stringDate){
	var parts =stringDate.split('-');
	//please put attention to the month (parts[0]), Javascript counts months from 0:
	//January - 0, February - 1, etc
	var mydate = new Date(parts[2],parts[1]-1,parts[0]); 
	
	return mydate;
}

function DateToString(stringDate){
	var d = stringDate.slice(0, 10).split('-');   
	var mydate = d[2] +'-'+ d[1] +'-'+ d[0];	
	return mydate;
}

var DialogTable = (function() {
	var self;
	return self = {
		// property

		oTable : null,

		// function
		getoTable : function() {
			return this.oTable; // could be self, only differs in non-context
								// use
		},
		CreateHTMLTable : function(Json) {
			// key - :TableHead:
			// key - :ChiTietTable:
			var html = '<table class="table table-striped table-hover table-bordered"id="editable-dialog"><thead><tr><th></th>:TableHead:</tr></thead><tbody id="chitiet">:ChiTietTable:</tbody></table>';

			// create tablehead
			var TeamplateTableHead = '<th>:th:</th>';
			var TableHead = "";

			$.each(Json, function(key, obj) {

				$.each(obj, function(key, value) {
					TableHead += TeamplateTableHead.replace(':th:', key);
				});

				return false;
			});

			html = html.replace(':TableHead:', TableHead);
			// create chitiettable

			var TeamplateChiTietTable = '<td class="">:chitiet:</td>';
			var ChiTietTable = "";

			$
					.each(
							Json,
							function(key, value) {
								ChiTietTable += '<tr class="odd">';
								ChiTietTable += '<td><input class="check_item" type="checkbox"></td>';
								$.each(value, function(key, value) {
									
									if (value instanceof Object) {
										value = $.datepicker.formatDate(
												"dd-mm-yy",
												new Date(value.date));
									}
									ChiTietTable += TeamplateChiTietTable
											.replace(':chitiet:', value);

								});

								ChiTietTable += '</tr>';

							});

			html = html.replace(':ChiTietTable:', ChiTietTable);
			// html+='<script>this.oTable=$("#editable-dialog").dataTable({"aLengthMenu":[[5,15,20,-1],[5,15,20,"All"]],"iDisplayLength":-1,"sDom":"<\'row-fluid\'<\'span6\'l><\'span6\'f>r>t<\'row-fluid\'<\'span6\'i><\'span6\'p>>","sPaginationType":"bootstrap","oLanguage":{"sLengthMenu":"_MENU_","oPaginate":{"sPrevious":"Prev","sNext":"Next"}},"aoColumnDefs":[{"bSortable":false,"aTargets":[0]}]});jQuery("#editable-sample_wrapper
			// .dataTables_filter input").addClass("
			// medium");jQuery("#editable-sample_wrapper .dataTables_length
			// select").addClass(" xsmall");</script>';
			return html;

		},

		CreateObjectTable : function() {

			this.oTable = $('#editable-dialog')
					.dataTable(
							{

								"aLengthMenu" : [ [ 5, 15, 20, -1 ],
										[ 5, 15, 20, "All" ] // change per
								// page values
								// here
								],
								// set the initial value
								"sScrollY" : "230px",

								'bScrollCollapse' : true,
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
								"aoColumnDefs" : [ {
									'bSortable' : false,
									'aTargets' : [ 0 ]
								} ]
							});

			jQuery('#editable-sample_wrapper .dataTables_filter input')
					.addClass(" medium"); // modify

			jQuery('#editable-sample_wrapper .dataTables_length select')
					.addClass(" xsmall"); // modify

			$("#DialogTable tr").live('click', function() {

				var tbody = $(this).parents('tbody')[0];
				$('input.check_item:checked', tbody).attr('checked', false);
				$('input.check_item', this).attr('checked', true);

			});

		},
		addHead : function(stringHead) {
			$("#DialogTable h3").html(stringHead);
		},

		addData : function(Json) {
			$("#DialogTable > .modal-body").html(this.CreateHTMLTable(Json));
			this.CreateObjectTable();
		},
		showAction : function(action) {
			if (this.oTable == null) {
				alert('DialogTable.oTable == null, Vui lòng gọi addData trước khi gọi show');
				return;
			} else {
				$("#DialogTable").modal("show");
				// even click
				$("#DialogTable_btnOK").unbind("click")
				$("#DialogTable_btnOK").click(action);
			}
		},
		show : function(Json, action) {

			this.addData(Json);
			$("#DialogTable").modal("show");
			// even click
			$("#DialogTable_btnOK").unbind("click");
			$("#DialogTable_btnOK").click(action);

		},
		// show from url

		showFromUrl : function(method, url, data, action) {
			console.log(data);
			$("#DialogTable > .modal-body")
					.html(
							'<div class="progress progress-striped active"> <div style="width: 100%;" class="bar"></div></div>');
			$("#DialogTable").modal("show");
			if (method == 'get') {
				$.get(url, data, function(json) {
					self.addData(json);
					$("#DialogTable_btnOK").unbind("click");
					$("#DialogTable_btnOK").click(action);
				}, 'json');
			} else if(method == 'post') {
				$.post(url, data, function(json) {

					self.addData(json);
					$("#DialogTable_btnOK").unbind("click");
					$("#DialogTable_btnOK").click(action);
				}, 'json');
			}
		},
		showPropress : function() {
			if($('#DialogTable').hasClass('in')!=true){
				this.addHead('Vui lòng chờ...');
				$("#DialogTable > .modal-body").html('<div class="progress progress-striped active"> <div style="width: 100%;" class="bar"></div></div>');
				$("#DialogTable").modal("show");
				
				setTimeout(function(){
					if($('#DialogTable').hasClass('in')==true)
						{
							$('#DialogTable').modal('hide');
						}
				}, 3000);
			}
		},
		showPropressUnlimit : function() {
			if($('#DialogTable').hasClass('in')!=true){
				this.addHead('Vui lòng chờ...');
				$("#DialogTable > .modal-body").html('<div class="progress progress-striped active"> <div style="width: 100%;" class="bar"></div></div>');
				$("#DialogTable").modal("show");
				
				
			}
		},
		showThongBao : function(head,messenger) {
			if($('#DialogTable').hasClass('in')!=true){
				this.addHead(head);
				$("#DialogTable > .modal-body").html(messenger);
				$("#DialogTable").modal("show");
				
				setTimeout(function(){
					if($('#DialogTable').hasClass('in')==true)
						{
							$('#DialogTable').modal('hide');
						}
				}, 3000);
			}
		},
		
		showThongBaoUnlimit : function(head,messenger) {
			if($('#DialogTable').hasClass('in')!=true){
				this.addHead(head);
				$("#DialogTable > .modal-body").html(messenger);
				$("#DialogTable").modal("show");
				
				
			}
		},
		hidePropress : function() {

			if ($('#DialogTable').hasClass('in') == true) {
				$('#DialogTable').modal('hide');
			}

		},
		setHeadAndMess : function(head,messenger){
			this.addHead(head);
			$("#DialogTable > .modal-body").html(messenger);
		}
		

	// example khai bao 1 doi tuong c
	/*
	 * , c : { c1: function(ten){ return self.a + this.b(); } }
	 */
	};
})();

// VI DU MAU
/*
 * $("#DialogMaSoThue").click(function(){
 * 
 * $.get('ct.php',{ LayDanhSachNNT : true },function(data){
 * 
 * DialogTable.show(data,function() { checkboxs =
 * $('input.check_item:checked').parents("tr");
 * 
 * if (checkboxs.length == 1) { var nnt_masothue = $('td',
 * checkboxs[0])[1].textContent; $("input[name|='masothue']").val(nnt_masothue);
 * $("#DialogTable").modal("hide"); var mst = $("#masothue").val();
 * 
 * $.post("ct.php", {blurmasothue: 'true',masothue: mst}, function(data){
 * 
 * if((data['ketqua'] == true)) { $("#tengoi").val(data['danhba'][0].tengoi);
 * $("#diachi").val(data['danhba'][0].diachi); } },"json"); }else{ alert("Vui
 * lòng chọn ít nhất một !"); } }); },'json'); });
 */

/*
 * var DialogLoading = new BootstrapDialog({ message: function(dialogRef){ var
 * $message = $('<div class="progress progress-striped active"> <div
 * style="width: 100%;" class="bar"></div></div>'); return $message; },
 * closable: false });
 */