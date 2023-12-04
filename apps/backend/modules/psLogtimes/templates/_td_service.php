<?php echo get_partial('psLogtimes/td_service_of_student', array('type' => 'list', 'ps_logtimes' => $ps_logtimes,'filter_value' => $filter_value, 'check_logtime' => $check_logtime, 'list_service' => $list_service2))?>

<?php
$student_id = $ps_logtimes->getStudentId ();
$disable = ($ps_logtimes->getId () > 0) ? '' : 'disabled';
$checked = ($disable == 'disabled') ? '' : 'checked=checked';
foreach ( $list_service2 as $key => $obj ) {
	echo $obj->id . ' - ' . $obj->title . '<br/>';
}
?>
<div class="form-group">
	<div class="col-md-12"
		id="block_student_service_<?php echo $student_id;?>">
	<?php foreach ($list_service as $key => $service): ?>		
		<?php if (($check_logtime) && (!$service->getSsdId())) : ?>
	  	<div
			class="checkbox <?php if ($service->getSsId() > 0) echo 'ss_id_'.$student_id;?>">
			<label> <input class="checkbox style-0" type="checkbox"
				<?php echo $disable ?>
				name="student_logtime[<?php echo $student_id;?>][student_service][]"
				value="<?php echo $service->getId();?>" /><span><?php echo $service->getTitle()?></span>
			</label>
		</div>
		<?php else :?>
		 <div
			class="checkbox <?php if ($service->getSsId() > 0) echo 'ss_id_'.$student_id;?>">
			<label> <input class="checkbox style-0" type="checkbox"
				<?php echo $checked ?> <?php echo $disable ?>
				name="student_logtime[<?php echo $student_id;?>][student_service][]"
				value="<?php echo $service->getId();?>" /><span><?php echo $service->getTitle()?></span>
			</label>
		</div>
		<?php endif;?>
	<?php endforeach; ?>
	</div>
</div>