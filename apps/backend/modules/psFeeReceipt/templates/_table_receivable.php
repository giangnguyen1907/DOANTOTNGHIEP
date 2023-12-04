<div id="datatable_fixed_column_wrapper"
	class="dataTables_wrapper form-inline no-footer no-padding">
	<div class="custom-scroll table-responsive">
		<table id="dt_basic"
			class="table table-striped table-bordered table-hover no-footer no-padding"
			width="100%">

			<thead>
				<tr>
					<th class="text-center"><?php echo __('STT', array(), 'messages') ?></th>
					<th class="text-center"><?php echo __('Student code', array(), 'messages') ?></th>
					<th class="text-center"><?php echo __('Student name', array(), 'messages') ?></th>
					<th class="text-center"><?php echo __('Birthday', array(), 'messages') ?></th>
					<th class="text-center"><?php echo __('Class name', array(), 'messages') ?></th>
					<th class="text-center"><?php echo $receivable_title ?></th>
				</tr>
			</thead>
			<?php $tong_tien = 0; //echo count($list_service);?>
			<tbody>
				<?php foreach ($list_student as $key => $student){?>
				<tr>
					<td class="text-center"><?php echo $key+1?></td>
					<td><?php echo $student->getStudentCode()?></td>
					<td><?php echo $student->getStudentName()?></td>
					<td class="text-center">
					<?php echo get_partial('global/field_custom/_field_birthday_student', array('value' => $student->getBirthday())) ?>
					</td>
					<td><?php echo $student->getClassName()?></td>
					<td class="text-right">
    					<?php

foreach ( $list_receivable as $receivable ) {
						if ($student->getId () == $receivable->getStudentId ()) {
							$sotien = $receivable->getAmount ();
							echo PreNumber::number_format ( $sotien );
							$tong_tien += $sotien;
						}
					}
					?>
					</td>
				</tr>
				<?php }?>
				<tr>
					<td colspan="5" class="text-right"><?php echo __('Total this month')?></td>
					<td class="text-right"><?php echo PreNumber::number_format($tong_tien)?></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>