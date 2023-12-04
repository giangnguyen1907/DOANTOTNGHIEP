<?php
$date_at = PsDateTime::psDatetoTime ( $tracked_at );
$saturday = date ( 'l', $date_at );
$student_id = $ps_attendances->getStudentId ();
$disable = ($ps_attendances->getId () > 0) ? '' : 'disabled';
$checked = ($disable == 'disabled') ? '' : 'checked=checked';
?>
<div class="">
	<div class="row">
		<div class="col-md-12"
			id="block_student_service_<?php echo $student_id;?>">
		<?php $array = array();?>
		<?php foreach ($list_service as $key => $service): ?>
		<?php
			if($service->enable_roll == 1){ $display='style="display:none"'; }else{ $display='';}
			if ($saturday != 'Saturday') {
				if ($service->getEnableSaturday () == 0) {
					?>
			<div <?=$display?> class="checkbox <?php if ($service->ss_id > 0) echo 'ss_id_'.$student_id;?>"
				style="width: 49%">
				<label> <input class="checkbox style-0" <?php echo $disable;?>
					type="checkbox"
					name="student_logtime[<?php echo $student_id;?>][student_service][]"
					value="<?php echo $service->id;?>"
					<?php if ($service->ssd_id > 0) echo 'checked=checked';?>
					style="position: absolute !important" /> <span><?php echo $service->title?></span>
				</label>
			</div>
			<?php }}else{?>
			<div <?=$display?> class="checkbox <?php if ($service->ss_id > 0) echo 'ss_id_'.$student_id;?>"
				style="width: 49%">
				<label> <input class="checkbox style-0" <?php echo $disable;?>
					type="checkbox"
					name="student_logtime[<?php echo $student_id;?>][student_service][]"
					value="<?php echo $service->id;?>"
					<?php if ($service->ssd_id > 0) echo 'checked=checked';?> /> <span><?php echo $service->title?></span>
				</label>
			</div>
			<?php }?>
		<?php endforeach; ?>

			<select class="select2" <?php echo $disable;?> multiple="multiple" placeholder="-Chọn mã ghi chú dịch vụ-" id="service_code_<?php echo $student_id;?>" name="student_logtime[<?php echo $student_id;?>][service_code][]">
				<?php foreach($array_dichvu as $key => $dichvu){ ?>
					<option value="<?=$key?>" ><?=$dichvu?></option>
				<?php } ?>
			</select>

		</div>
	</div>
</div>