<div class="table-responsive custom-scroll" style="max-height: 615px; overflow-y: scroll;width: 100%;">
	<table id="dt" class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th style="width: 50px;" class="text-center"><?php echo __('Image');?></th>
				<th><?php echo __('Student name', array(), 'messages') ?></th>
				<th class="text-center"><?php echo __('Birthday') ?></th>
				<th class="text-center"><?php echo __('Sex') ?></th>
				<th class="text-center"><?php echo __('Created by') ?></th>
			</tr>
		</thead>
		<tbody>
	<?php
	foreach ( $list_student_class_to as $student ) {
		?>
		<tr>
				<td>			
        			<?php
		if ($student->getImage () != '') {
			$path_file = '/media-web/' . PreSchool::MEDIA_TYPE_STUDENT . '/' . $student->getSchoolCode () . '/' . $student->getYearData () . '/' . $student->getImage ();
			echo '<img style="max-width: 45px; text-align: center;" src="' . $path_file . '">';
		}
		?>
        	</td>
				<td><?php echo $student->getFullName() ?>
			<p>
						<code><?php echo $student->getStudentCode(); ?></code>
					</p></td>
					<td class="text-center"><?php echo date('d-m-Y', strtotime($student->getBirthday())) ?></td>
					<td class="text-center">
					<?php echo get_partial('global/field_custom/_field_sex', array('value' => $student->getSex())) ?>
					</td>
					<td class="text-center">
					<?php 
					echo $student->getCreatedBy () . '<br/>';
					echo false !== strtotime ( $student->getCreatedAt () ) ? date ( 'd/m/Y', strtotime ( $student->getCreatedAt () ) ) : '&nbsp;';
					?>
					</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div>