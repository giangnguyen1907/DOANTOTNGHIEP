<?php
$student = $form->getObject ()
	->getStudent ();
$date = $form->getDefault ( 'tracked_at' );
$ps_customer_id = $student->getPsCustomerId ();
$class_id = ($student->getMyClassByStudent ()) ? $student->getMyClassByStudent ()
	->getMyclassId () : '';
$student_services = $student->getServicesStudentByDate ( $class_id, $date, $ps_customer_id );
?>
<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
	<div class="form-group">
		<label class="col-md-3 control-label"
			for="ps_logtimes_student_service"></label>
		<div class="col-md-9">
			<div class="inline-group">
		<?php foreach ($student_services as $key => $student_service): ?>
		<label class="checkbox"> <input type="checkbox"
					<?php if ($student_service->get('ssd_id') > 0) echo 'checked="checked"';?>
					name="ps_logtimes[student_service][]"
					value="<?php echo $student_service->getId();?>"><?php echo $student_service->getTitle()?>
		</label>
		<?php endforeach; ?>
		</div>
		</div>
	</div>
</div>
