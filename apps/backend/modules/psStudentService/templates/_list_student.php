<?php use_helper('I18N', 'Date') ?>
<?php use_helper('I18N', 'Number') ?>
<?php foreach ($list_student as $student): ?>
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
    	<?php echo $student->getStudentName();?><br /> <code><?php echo $student->getStudentCode();?></code>
	</td>
	<td class="text-center">
		<?php echo get_partial('global/field_custom/_field_birthday_student', array('value' => $student->getBirthday())) ?>
    </td>
	<td class="text-center">
		<div class="btn-group">

			<a  class="btn btn-xs btn-default"
				data-backdrop="static"
				data-toggle="modal" data-target="#remoteModal"
				title="<?php echo __('Detail')?>"
				href="<?php echo url_for('@ps_service_registration_student_detail?sid='.$student->getId().'&kstime='.$ktime)?>"><i
				class="fa-fw fa fa-eye txt-color-blue"></i></a>

		</div>
	</td>
	<td><label class="checkbox-inline"> <input type="checkbox" name="ids[]"
			id="chk_id_<?php echo $student->getId();?>"
			value="<?php echo $student->getId();?>"
			class="sf_admin_batch_checkbox checkbox style-0"> <span></span>
	</label></td>
</tr>
<?php endforeach; ?>