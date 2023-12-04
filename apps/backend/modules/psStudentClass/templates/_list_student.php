<?php
/**
 * @project_name
 * @subpackage     interpreter 
 *
 * @file _list_student.php
 * @filecomment Danh sach hoc sinh chon de phan lop
 * @package_declaration package_declaration
 * @author PC
 * @version 1.0 22-08-2017 -  01:17:58
 */
$status_student = PreSchool::loadStatusStudent ();
?>

<table id="dt_basic"
	class="table table-striped table-bordered table-hover" width="100%">
	<thead>
		<tr>
			<th style="width: 50px;" class="text-center"><?php echo __('Image');?></th>
			<th><?php echo __('Full name');?></th>
			<th class="center-text" style="width: 160px;"><?php echo __('Birthday');?></th>
			<th style="width: 100px;"><?php echo __('Status');?></th>
			<th class="center-text" style="width: 130px;"><?php echo __('Current class');?></th>
			<th class="text-center" style="width: 90px;"><?php echo __('Actions', array(), 'sf_admin') ?></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($students as $student): ?>
			<tr>
			<td></td>
			<td><?php echo $student->getFirstName().' '.$student->getLastName();?></td>
			<td class="text-center">
				<div class="date"><?php echo (false !== strtotime($student->getBirthday())) ? format_date($student->getBirthday(),"dd-MM-yyyy").'<code>'.PreSchool::getAge($student->getBirthday(),false).'</code>' : '';?></div>
			</td>
			<td><?php if (isset($status_student[$student->getStatus()])) echo __($status_student[$student->getStatus()]);?></td>
			<td><?php echo $student->getClassName();?></td>
			<td></td>
		</tr>
		<?php endforeach;?>
		</tbody>

	<tfoot>

	</tfoot>
</table>

