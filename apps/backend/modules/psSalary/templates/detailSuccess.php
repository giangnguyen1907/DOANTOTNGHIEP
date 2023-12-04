<?php use_helper('I18N', 'Date')?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">×</button>
	<h4 class="modal-title" id="myModalLabel"><?php echo __('Salary Detail') ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group">
			<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
				<label class="control-label"><strong><?php echo __('Customer') ?></strong></label>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
				<p>
					<?php echo $ps_salary->getSchoolName()?>
				</p>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
				<label class="control-label"><strong><?php echo __('Basic salary') ?></strong></label>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
				<p>
					<?php echo $ps_salary->getTitle()?>
				</p>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
				<label class="control-label"><strong><?php echo __('Note') ?></strong></label>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
				<p>
					<?php echo $ps_salary->getNote() ? $ps_salary->getNote() : '&nbsp' ?>
				</p>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
				<label class="control-label"><strong><?php echo __('Day work per month') ?></strong></label>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
				<p>
					<?php echo $ps_salary->getDayWorkPerMonth()?>
				</p>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
				<label class="control-label"><strong><?php echo __('Updated at') ?></strong></label>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
				<p>
					<?php echo $ps_salary->getUpdatedBy()?>
                  <?php echo ' - '?>
                  <?php echo (false !== strtotime($ps_salary->getUpdatedAt())) ? format_date($ps_salary->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '';?>
				</p>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
				<label class="control-label"><strong><?php echo __('Is Activated') ?></strong></label>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
				<p>
					<?php echo get_partial('psSalary/list_field_boolean', array('value' => $ps_salary->getIsActivated())) ?>
				</p>
			</div>
		</div>
	</div>


</div>
<div class="modal-footer">
	<?php

	if ($sf_user->hasCredential ( 'PS_HR_HR_EDIT' )) {
		echo link_to ( '<i class="fa-fw fa fa-pencil"></i> ' . __ ( 'Edit' ), 'ps_salary_edit', $ps_salary, array (
				'class' => 'btn btn-default btn-success btn-sm btn-psadmin' ) );
	}
	?>
	<button type="button" class="btn btn-default" data-dismiss="modal">
		<i class="fa-fw fa fa-ban"></i>&nbsp;<?php echo __('Close') ?></button>
</div>