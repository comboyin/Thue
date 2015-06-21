<?php
?>




<!-- BEGIN EDITABLE TABLE widget-->
<div class="row-fluid">
	<div>
		<!-- BEGIN EXAMPLE TABLE widget-->
		<div class="widget purple">
			<div class="widget-title">
				<h4>danh sách ...............</h4>
				<span class="tools"> <a href="javascript:;"
					class="icon-chevron-down"></a> 
				</span>
			</div>
			<div class="widget-body">
				<div>
					<div class="clearfix">
						<div class="btn-group">
							<button id="editable-sample_new" class="btn green">
								Thêm mới <i class="icon-plus"></i>
							</button>
						</div>

						<div style="margin-left: 5px" class="btn-group">
							<button id="xoa_nhieu" class="btn green">
								Xóa chọn <i class="icon-remove"></i>
							</button>
						</div>


						<div class="btn-group pull-right">
							<button class="btn dropdown-toggle" data-toggle="dropdown">
								Tùy chọn <i class="icon-angle-down"></i>
							</button>
							<ul class="dropdown-menu pull-right">
								<li><a href="#">Print</a></li>
								<li><a href="#">Save as PDF</a></li>
								<li><a href="#">Export to Excel</a></li>
							</ul>
						</div>
					</div>
					<div class="space15"></div>
					<table cellspacing="0" class="table table-striped table-bordered"
						width="100%" id="editable-sample">
						<thead>
							<tr>
								<th><input id="check_all" type="checkbox"
									class="group-checkable"></th>
								<th>Mã số thuế</th>
								<th>Tên gọi</th>
								<th>Thu tu</th>
								<th>Dia chi</th>
								<th>Chan le</th>
								<th>hem</th>
								<th>Số nhà</th>
								<th>Số nhà phụ</th>
								<th>Tên đường vt</th>
								<th>Mã ngành</th>
								<th>Nghề kinh doanh</th>
								<th>Bậc môn bài</th>
								<th>Ngày tính thuế</th>
								<th>Số giấy phép</th>
								<th>Ngày cấp giấy phép</th>
								<th>Số dt</th>
								<th>bkt</th>
								<th>ghi chú</th>
								<th>macbql</th>
								<th>Sửa</th>
								<th>Xóa</th>
							</tr>
						</thead>
						<tbody id="chitiet">
										
										
										<?php if($dkts!=null):?>
										
        										<?php if($obj->getRowCount()>0):?>
        										          <?php foreach ($dkts as $dkt):?>
                                <tr class="odd">
								<td class=" sorting_1"><input class="check_item" type="checkbox"></td>
								<td><?php echo $dkt['masothue']?></td>
								<td><?php echo $dkt['tengoi']?></td>
								<td><?php echo $dkt['thutu']?></td>
								<td><?php echo $dkt['diachi']?></td>
								<td><?php echo $dkt['chanle']?></td>
								<td><?php echo $dkt['hem']?></td>
								<td><?php echo $dkt['sonha']?></td>
								<td><?php echo $dkt['sonhaphu']?></td>
								<td><?php echo $dkt['tenduongvt']?></td>
								<td><?php echo $dkt['manganh']?></td>
								<td><?php echo $dkt['nghekinhdoanh']?></td>
								<td><?php echo $dkt['bacmb']?></td>
								<td><?php echo $dkt['ngaytinhthue']?></td>
								<td><?php echo $dkt['sogp']?></td>
								<td><?php echo $dkt['ngaycapgp']?></td>
								<td><?php echo $dkt['sodt']?></td>
								<td><?php echo $dkt['bkt']?></td>
								<td><?php echo $dkt['ghichu']?></td>
								<td><?php echo $dkt['macbql']?></td>
								<td><a class="edit" href="">Edit</a></td>
								<td><a class="Delete" href="">Delete</a></td>
								</td>
							</tr>
                                
                                <?php endforeach;?>
        										          
        										
        										<?php endif;?>
        										
										
										
										<?php endif;?>		
  
							</tbody>
					</table>
				</div>
			</div>
		</div>
		<!-- END EXAMPLE TABLE widget-->
	</div>
</div>




<!-- cho no vao layout  -->

<div id="DialogTable" style="width: 800px; margin-left: -400px;"
	class="modal hide fade in" tabindex="-1" role="dialog"
	aria-labelledby="myModalLabel1" aria-hidden="false"
	style="display: block;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal"
			aria-hidden="true">×</button>
		<h3 id="myModalLabel1">...</h3>
	</div>
	<div class="modal-body">
		<p>Body goes here...</p>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
		<button class="btn btn-primary" id="DialogTable_btnOK">OK</button>
	</div>
</div>
