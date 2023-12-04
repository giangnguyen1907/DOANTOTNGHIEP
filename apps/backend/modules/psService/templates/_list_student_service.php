<?php
/**
 * @project_name
 *
 * @subpackage interpreter
 *
 * @file _list_student.php
 * @filecomment Danh sach hoc sinh cua lop
 * @package _declaration package_declaration
 * @author thangnc
 * @version 1.0 22-08-2017 - 01:17:58
 *
 */
$ps_students = $services->getStudentsByPsServiceId ();
?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<div class="custom-scroll table-responsive"
		style="max-height: 200px; overflow-y: scroll;">
		<table id="dt_basic_student" class="table table-bordered" width="100%">
			<thead>
				<tr>
					<th style="width: 50px;" class="no-order text-center"><?php echo __('Image');?></th>
					<th style="width: auto;"><?php echo __('Full name');?></th>
					<th class="text-center" style="max-width: 80px;"><?php echo __('Birthday');?></th>
					<th class="text-center"><?php echo __('Sex');?></th>
					<th style="width: 100px;" class="text-center"><?php echo __('Registration date');?> </th>
					<th class="no-order text-center" style="width: 60px;"><?php echo __('Actions', array(), 'sf_admin')?></th>
				</tr>
			</thead>
			<tbody>
			<?php
			foreach ( $ps_students as $student ) :
				?>
			<tr>
					<td style="min-width: 50px;" class="text-center">
				<?php
				if ($student->getImage () != '') {
					$path_file = '/media-web/' . PreSchool::MEDIA_TYPE_STUDENT . '/' . $student->getSchoolCode () . '/' . $student->getYearData () . '/' . $student->getImage ();
					echo '<img style="max-width: 45px; text-align: center;" src="' . $path_file . '">';
				}
				?>
				</td>
					<td><?php echo $student->getFullName();?><br /> <code><?php echo $student->getStudentCode();?></code>
					</td>
					<td class="text-center">
						<div class="date">
						<?php echo (false !== strtotime($student->getBirthday())) ? format_date($student->getBirthday(), "dd-MM-yyyy") . '<code>' . PreSchool::getAge($student->getBirthday(), false) . '</code>' : '';?>
						</div>
					</td>
					<td class="text-center"><?php echo get_partial('global/field_custom/_field_sex', array('value' => $student->getSex())) ?></td>
					<td class="text-center">
						<div class="date">
						<?php echo (false !== strtotime($student->getCreatedAt())) ? format_date($student->getCreatedAt(), "dd-MM-yyyy") : '';?>
						</div>
					</td>
					<td class="text-center"></td>
				</tr>
			<?php endforeach;?>
			</tbody>
		</table>
	</div>
</div>