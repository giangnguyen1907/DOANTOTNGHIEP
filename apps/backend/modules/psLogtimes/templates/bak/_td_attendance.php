<?php
$student_id = $ps_logtimes->getStudentId ();
$logtime = Doctrine::getTable ( 'PsLogtimes' )->getLogtimeByTrackedAt ( $student_id, $filter_value ['tracked_at'] );
if ($logtime) {
	$log_value = ($logtime->getLogValue ()) ? $logtime->getLogValue () : '0';
} else
	$log_value = '0';

?>
<center>
	<section>
		<div class="inline-group">
			<input type="checkbox"
				name="student_logtime[<?php echo $ps_logtimes->getStudentId();?>][log_value]"
				<?php if ($log_value > 0) :?> checked="checked" <?php endif;?>
				value="1"
				onclick="javascript:setLogtime(<?php echo  $ps_logtimes->getStudentId();?>,this);">

		</div>
	</section>
</center>