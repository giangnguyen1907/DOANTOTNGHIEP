<label class="checkbox-inline"> <input type="checkbox"
	item_name="attendance[]" class="checkbox style-0 attendance-check"
	name="student_logtime[<?php echo $ps_logtimes->getStudentId();?>][log_value]"
	<?php if ($ps_logtimes->getId() > 0) :?> checked="checked"
	<?php endif;?> value="1"
	onclick="javascript:setLogtime(<?php echo  $ps_logtimes->getStudentId();?>,this);" />
	<span></span>
</label>