<?php
$student_id = $ps_logtimes->getStudentId ();
$class_id = $ps_logtimes->getClassId ();
$date = date ( 'Ymd' );
$ps_customer_id = $ps_logtimes->getPsCustomerId ();

$list_service = Doctrine::getTable ( 'Service' )->getServicesDiaryByStudent ( $student_id, $class_id, $filter_value ['tracked_at'], $ps_customer_id );
$logtime = Doctrine::getTable ( 'PsLogtimes' )->getLogtimeByTrackedAt ( $student_id, $filter_value ['tracked_at'] );
$disable = ($check_logtime && ! $logtime) ? 'disabled' : '';
$checked = ($disable == 'disabled') ? '' : 'checked=checked';

?>

<ul class="checkbox_list"
	id="block_student_service_<?php echo $student_id;?>">
<?php foreach ($list_service as $key => $service): ?>
<?php if (($check_logtime) && (!$service->getSsdId())) : ?>
  <li><input type="checkbox" <?php echo $disable ?>
		name="student_logtime[<?php echo $student_id;?>][student_service][]"
		value="<?php echo $service->getId();?>"> <label class="checkbox"><?php echo $service->getTitle()?>
</label></li>
<?php else :?>
 <li><input type="checkbox" <?php echo $checked ?>
		<?php echo $disable ?>
		name="student_logtime[<?php echo $student_id;?>][student_service][]"
		value="<?php echo $service->getId();?>"> <label class="checkbox"><?php echo $service->getTitle()?>
</label></li>
<?php endif;?>
<?php endforeach; ?>
</ul>


