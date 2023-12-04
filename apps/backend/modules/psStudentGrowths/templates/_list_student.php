<?php use_helper('I18N', 'Date') ?>
<?php use_helper('I18N', 'Number') ?>
<?php foreach ($list_student_malnutrition as $key=>$student){?>
<?php 
$coment_height = $coment_weight = '';
$height = $student->getHeight ();
$weight = $student->getWeight ();
?>
<tr>
	<td class="text-center"><?php echo $key + 1?></td>
	<td>
	<a data-backdrop="static" data-toggle="modal" data-target="#remoteModal"
	href="<?php echo url_for('@ps_student_growths_detail?id='.$student->getStudentId()) ?>">
	<?php echo $student->getStudentName ()?><br/>
	<code><?php echo $student->getStudentCode ()?></code>
	</a>
	</td>
	<td class="text-center"><?php echo $student->getClassName ()?></td>
	<td class="text-center"><?php echo $student->getIndexAge ()?></td>
	<td class="text-center"><?php echo get_partial('global/field_custom/_field_sex', array('value' => $student->getSex())) ?></td>
	<td class="text-center">
	<?php if($student->getIndexHeight () == -1){ echo $height;}?>
	</td>
	<td class="text-center"><?php if($student->getIndexHeight () == -2){ echo $height;}?></td>
	<td class="text-center"><?php if($student->getIndexWeight () == -1){ echo $weight;}?></td>
	<td class="text-center"><?php if($student->getIndexWeight () == -2){ echo $weight;}?></td>
	<td class="text-center"><?php if($student->getIndexWeight () == 1){ echo $weight;}?></td>
	<td class="text-center"><?php if($student->getIndexWeight () == 2){ echo $weight;}?></td>
	<td class="text-center">
	<?php
	if($student->getIndexHeight () < 0){
		echo get_partial('psStudentGrowths/index_height', array('value' => $student->getIndexHeight ())) ;
	}?>
	</td>
	<td class="text-center">
	<?php if($student->getIndexWeight () != 0){
		echo get_partial('psStudentGrowths/index_weight', array('value' => $student->getIndexWeight ()));
	} ?>
	</td>
</tr>
<?php }?>