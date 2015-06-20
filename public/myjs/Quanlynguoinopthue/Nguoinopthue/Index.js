/**
 * Module: Quanlynguoinopthue
 * Controller: Nguoinopthue
 * Action: Index
 */

var oTable = null;

var EditableTable = function () {

    return {

        // main function to initiate the module
        init: function () {
        	
            oTable = $('#editable-sample').dataTable({
                "aLengthMenu": [
                    [5, 15, 20, -1],
                    [5, 15, 20, "All"] // change per page values here
                ],
                // set the initial value
                
              //*************************************
				"sScrollY": "350px",
				"sScrollX": "100%",
				/*"bScrollCollapse": true,*/
				//*************************************
                
                
                "iDisplayLength": -1,
                "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
                "sPaginationType": "bootstrap",
                "oLanguage": {
                    "sLengthMenu": "_MENU_",
                    "oPaginate": {
                        "sPrevious": "Prev",
                        "sNext": "Next"
                    }
                },
                "aoColumnDefs": [{
                        'bSortable': false,
                        'aTargets': [0]
                    }
                ]
            });

            jQuery('#editable-sample_wrapper .dataTables_filter input').addClass(" medium"); // modify
																								// table
																								// search
																								// input
            jQuery('#editable-sample_wrapper .dataTables_length select').addClass(" xsmall"); // modify

        }
    }
}();

function deleteAllRows()
{
	
	var oSettings =	oTable.fnSettings();
	var iTotalRecords = oSettings.fnRecordsTotal();
	for (i=0;i<=iTotalRecords;i++) {
		oTable.fnDeleteRow(0,null,true);
	}
	
}

function postAndRedirect(url, postData)
{
    var postFormStr = "<form method='POST' action='" + url + "'>\n";

    for (var key in postData)
    {
        if (postData.hasOwnProperty(key))
        {
            postFormStr += "<input type='hidden' name='" + key + "' value='" + postData[key] + "'></input>";
        }
    }

    postFormStr += "</form>";

    var formElement = $(postFormStr);

    $('body').append(formElement);
    $(formElement).submit();
}

function getAndRedirect(url, getData)
{
    var getFormStr = "<form method='GET' action='" + url + "'>\n";

    for (var key in getData)
    {
        if (getData.hasOwnProperty(key))
        {
            getFormStr += "<input type='hidden' name='" + key + "' value='" + getData[key] + "'></input>";
        }
    }

    getFormStr += "</form>";

    var formElement = $(getFormStr);

    $('body').append(formElement);
    $(formElement).submit();
}

function RedirectSua(parent) {
	
	//var mst = $('td', parent)[1].textContent;
	
	//var kythue = $('#kythue').val();
	
	//postAndRedirect('themdukiendoanhso', {HanhDong:'LoadFormSua', KyThue: kythue,MaSoThue: mst});
	
	
}

function RedirectThem()
{
	//getAndRedirect('themdukiendoanhso', {});
}

//Xoa 1
function deleterow(parent)
{
	
	//var mst = $('td', parent)[1].textContent;
	
	//var kythue = $('#kythue').val();
	
	//postAndRedirect('index', {xoadoanhsodukien:true,KyThue: kythue,MaSoThue: mst});
}

//Xoa tat ca
function XoaSelect(){
	//var checkboxs = $("input.check_item:checked").parents('tr');
	//var MaSoThueData = new Array();
	//for(i = checkboxs.length-1 ; i>=0;i--)
	//	{
	//		var MaSoThue = $('td',checkboxs[i])[1].textContent;
	//		MaSoThueData.push(MaSoThue);		
	//	}
	
	//var kythue = $("#kythue").val();

	//postAndRedirect('index', {XoaNhieu:true,MaSoThue:MaSoThueData,KyThue:kythue});
	
}

jQuery(document).ready(function() {
	
	
	EditableTable.init();
	
	var tenchicuc = 'aaaaa';
	//
    $.post("dsnntbyidentity", {}, function(data){
    	
    	deleteAllRows();
    	for(i=0; i< data.length; i++) {
    		var NgayCapMST = $.datepicker.formatDate("dd-mm-yy", new Date(data[i]['NgayCapMST'].date));
    		var ThoiDiemBDKD = $.datepicker.formatDate("dd-mm-yy", new Date(data[i]['ThoiDiemBDKD'].date));    		   		
    		
    		console.log(tenchicuc);
    		oTable.fnAddData(['<th style="width: 12.0909091234207px;" class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label=""><input class="check_item" type="checkbox" class="group-checkable"></th>',
    		                  data[i]['MaSoThue'],
    		                  NgayCapMST,
    		                  data[i]['TenHKD'],
    		                  data[i]['SoCMND'],
    		                  data[i]['DiaChiCT'],
    		                  data[i]['thongtinnnt'][0]['DiaChiKD']+", "+data[i]['thongtinnnt'][0]['phuong']['TenPhuong']+"," + data[i]['thongtinnnt'][0]['phuong']['coquanthue']['TenChiCuc'].replace("Chi cục thuế "," ") ,
    		                  data[i]['SoGPKD'],
    		                  ThoiDiemBDKD,
    		                  data[i]['NNTNganhs'][0]['nganh']['TenNganh'].substring(0, 10)+"...",
    		                  data[i]['NgheKD'].substring(0, 10)+"...",
    		                  data[i]['usernnts'][0]['user']['TenUser'],
	                          '<button onclick="RedirectEdit($(this).parents(\'tr\'))" class="btn btn-primary"><i class="icon-pencil"></i></button><button class="btn btn-danger"><i class="icon-trash "></i></button>'
	                  ]);
    		var nTr = oTable.fnSettings().aoData[i].nTr;
    		
    		$('td', nTr)[10].setAttribute( 'class', 'popovers' );
    		$('td', nTr)[10].setAttribute( 'data-trigger', 'hover' );
    		$('td', nTr)[10].setAttribute( 'data-container', 'body' );
    		$('td', nTr)[10].setAttribute( 'data-content', data[i]['NgheKD'] );
    		
    		$('td', nTr)[9].setAttribute( 'class', 'popovers' );
    		$('td', nTr)[9].setAttribute( 'data-trigger', 'hover' );
    		$('td', nTr)[9].setAttribute( 'data-container', 'body' );
    		$('td', nTr)[9].setAttribute( 'data-content', data[i]['NNTNganhs'][0]['nganh']['TenNganh'] );
    		$('.popovers').popover();
    	} 	
    	$("html").getNiceScroll().resize();
    	
    },"json");
        
	//
	$("#check_all").click(function (e){
		//uncheck to checked
		if($("#check_all").attr("checked")=="checked")
			{
					$("input.check_item").each(function() {
					    $(this).attr('checked', true);
					  });
			}
		else{
			$("input.check_item").each(function() {
			    $(this).attr('checked', false);
			  });
			}
		}
	);

});
