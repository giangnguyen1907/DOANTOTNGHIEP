<?php
// Neu Ngay dang kiem tra chua tung duoc diem danh
if (! $check_logtime) :
	?>
<center>
	<section>
		<div class="inline-group">
			<input type="checkbox"
				name="student_logtime[<?php echo $ps_logtimes->getStudentId();?>][log_value]"
				checked="checked" value="1"
				onclick="javascript:setLogtime(<?php echo  $ps_logtimes->getStudentId();?>,this);">

		</div>
	</section>
</center>
<?php

else :
	// Neu ngay dang kiem tra da tung duoc luu thi chi danh dau nhung sinh vien di hoc
	?>
<center>
	<section>
		<div class="inline-group">
			<input type="checkbox"
				name="student_logtime[<?php echo $ps_logtimes->getStudentId();?>][log_value]"
				<?php if ($ps_logtimes->getId() > 0) :?> checked="checked"
				<?php endif;?> value="1"
				onclick="javascript:setLogtime(<?php echo  $ps_logtimes->getStudentId();?>,this);">
		</div>
	</section>
</center>

<?php endif;?>