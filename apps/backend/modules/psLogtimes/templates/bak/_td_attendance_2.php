
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