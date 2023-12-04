<?php $school_code = $ps_service_courses->getPsService()->getPsCustomer()->getSchoolCode(); ?>
<?php use_helper('I18N', 'Date') ?>

<table id="dt_basic"
	class="table table-striped table-bordered table-hover" width="100%">

	<thead>
		<tr>
			<th class="text-center" style="width: 10%;"><?php echo __('Image');?></th>
			<th class="text-center" style="width: 20%;"><?php echo __('Student code');?></th>
			<th class="text-center" style="width: 30%;"><?php echo __('Full name');?></th>
			<th class="text-center" style="width: 20%;"><?php echo __('Birthday');?></th>
			<th class="text-center" style="width: 10%;"><?php echo __('Sex');?></th>
			<th class="text-center" style="width: 10%;"><?php echo __('Select');?></th>

		</tr>
	</thead>
	<tbody>
		
		<?php foreach($list_student as $student): ?>
		<tr>
			<td>			
			
            <?php
			if ($student->getImage () != '') {
				$path_file = '/media-web/' . PreSchool::MEDIA_TYPE_STUDENT . '/' . $school_code . '/' . $student->getYearData () . '/' . $student->getImage ();
				echo '<img style="max-width: 45px; text-align: center;" src="' . $path_file . '">';
			}
			?>
			</td>
			<td><?php echo $student->getStudentCode();?></td>
			<td><?php echo $student->getFullName();?></td>
			<td class="text-center">
				<div class="date"><?php echo (false !== strtotime($student->getBirthday())) ? format_date($student->getBirthday(),"dd-MM-yyyy").'<div><code>'.PreSchool::getAge($student->getBirthday(),false).'</code>' : '';?>
			
			
			</td>
			<td class="text-center"><?php echo get_partial('global/field_custom/_field_sex', array('value' => $student->getSex())) ?></td>
			<td class="text-center" style="width: 100px;"><label
				class="checkbox-inline">
			<?php echo $form['select']->render(array('name' => 'form_student_service['.$student->getId().'][select]')) ?><span></span>
			</label></td>
		</tr>
		<?php endforeach;?>
		</tbody>
</table>
<script type="text/javascript">
	$(document).ready(function() {	
		$('.select3').select2({
			  dropdownParent: $('#remoteModal'),
			  dropdownCssClass : 'no-search'
		});
	});
</script>