<?php use_helper('I18N', 'Date', 'Number')?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">×</button>
	<h4 class="modal-title" id="myModalLabel">Các khoản được giảm trừ</h4>
</div>
<div class="modal-body">
	<div class="row">		
		<div class="custom-scroll table-responsive" style="">
			<table id="dt_basic"
				class="table table-bordered table-hover no-footer no-padding"
				width="100%">
				<thead>
					<tr style="background-color: #fff;">
						<th class="text-center">STT</th>
						<th >Tiêu đề</th>
						<th class="text-center">Mức độ giảm trừ</th>
						<th class="text-center">Giảm trừ</th>
						<th class="text-center">Kiểu giảm trừ</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					//echo count($psStudentServiceReduce);
					foreach($psStudentServiceReduce as $key=>$studentService){ $key++;?>
					<tr>
						<td class="text-center"><?=$key?></td>
						<td><?=$studentService->getTitle()?></td>
						<td class="text-center"><?=$studentService->getLevel()?></td>
						<td class="text-center"><?=number_format($studentService->getDiscount())?></td>
						<td class="text-center"><?php echo PreSchool::$ps_giamtru[$studentService->is_type]?></td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">
		<i class="fa-fw fa fa-ban"></i>&nbsp;<?php echo __('Close') ?></button>
</div>

