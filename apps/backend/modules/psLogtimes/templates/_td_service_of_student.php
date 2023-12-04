<?php
$student_id = $ps_logtimes->getStudentId ();
$disable = ($ps_logtimes->getId () > 0) ? '' : 'disabled';
$checked = ($disable == 'disabled') ? '' : 'checked=checked';
?>
<div class="form-group">
	<div style="width: 100%"
		id="block_student_service_<?php echo $student_id;?>">
	<?php foreach ($list_service as $key => $service): ?>
		<div
			class="checkbox <?php if ($service->ss_id > 0) echo 'ss_id_'.$student_id;?>">
			<label> <input class="checkbox style-0" <?php echo $disable;?>
				type="checkbox"
				name="student_logtime[<?php echo $student_id;?>][student_service][]"
				value="<?php echo $service->id;?>"
				<?php if ($ps_logtimes->getId() > 0 && $service->ssd_id > 0) echo 'checked=checked';?> />
				<span><?php echo $service->title?></span>
			</label>
		</div>		
	<?php endforeach; ?>
	</div>
</div>