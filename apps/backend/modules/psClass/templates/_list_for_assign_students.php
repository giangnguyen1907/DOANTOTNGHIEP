<?php use_helper('I18N', 'Date') ?>
<div class="custom-scroll table-responsive" style="<?php if (count($students_not_exits_class) > 10) {?> height:400px; <?php };?>overflow-y: scroll;">
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th style="width: 50px;" class="text-center"><?php echo __('Image');?></th>
				<th><?php echo __('Student', array(), 'messages') ?></th>
				<th><?php echo __('Birthday', array(), 'messages') ?></th>
				<th><?php echo __('Sex', array(), 'messages') ?></th>
				<th class="text-center" style="width: 30px;"><?php echo __('Select') ?></th>
			</tr>
		</thead>
		<tbody>		
		<?php
		foreach ( $students_not_exits_class as $student ) {
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
				<td>
					<?php echo $student->getFullName() ?>
					<p>
						<code><?php echo $student->getStudentCode(); ?></code>
					</p>
				</td>
				<td class="text-center">
				<?php
			if (false !== strtotime ( $student->getBirthday () ))
				echo '<div class="date">' . format_date ( $student->getBirthday (), "dd-MM-yyyy" ) . '</div><div><code>' . PreSchool::getAge ( $student->getBirthday (), false ) . '</code></div>';
			else
				echo '&nbsp;';
			?>
				</td>
				<td class="text-center">
				<?php echo get_partial('global/field_custom/_field_sex', array('value' => $student->getSex())) ?>
				</td>
				<td class="text-center" style="width: 100px;"><label
					class="checkbox-inline"> <input class="select checkbox"
						type="checkbox"
						name="form_student[<?php echo $student->getStudentId()?>][select]" /><span></span>
				</label></td>
			</tr>
			<?php } ?>		
		</tbody>
	</table>
</div>

