<?php use_helper('I18N', 'Date')?>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">×</button>
	<h4 class="modal-title" id="myModalLabel"><?php echo __('Work places information') ?></h4>
</div>
<div class="modal-body">
	<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9"
		style="border-right: dashed 1px #D3D3D3;">
		<div class="row">
			<div class="form-group">
				<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
					<label class="control-label" style="font-weight: bold;"><?php echo __('Title') ?></label>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
					<p>
						<strong><?php echo $work_place_detail->getTitle()?></strong>
					</p>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="form-group">
				<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
					<label class="control-label" style="font-weight: bold;"><?php echo __('Address') ?></label>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
					<p><?php echo $work_place_detail->getAddress() ? $work_place_detail->getAddress() : '' ?></p>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="form-group">
				<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
					<label class="control-label" style="font-weight: bold;"><?php echo __('Phone') ?></label>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
					<p><?php echo $work_place_detail->getPhone() ? $work_place_detail->getPhone() : '' ?></p>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="form-group">
				<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
					<label class="control-label" style="font-weight: bold;"><?php echo __('Is activated') ?></label>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
					<p><?php echo $work_place_detail->getIsActivated()  ?  '<span class="label-success" style="color:white;">'. __('Activated') .'</span>' :  '<span class="label-warning" style="color:white;">'. __('Not activated') .'</span>';?></p>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="form-group">
				<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
					<label class="control-label" style="font-weight: bold;"><?php echo __('Note') ?></label>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
					<p><?php echo $work_place_detail->getNote() ? $work_place_detail->getNote() : '' ?></p>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="form-group">
				<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
					<label class="control-label" style="font-weight: bold;"><?php echo __('Description') ?></label>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
					<p><?php echo $work_place_detail->getDescription() ? $work_place_detail->getDescription() : '' ?></p>
				</div>
			</div>
		</div>

	</div>

	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
		<h6 style="margin-top: -10px">Lịch sử</h6>
		<div class="row">
			<div class="form-group">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<label class="control-label" style="font-weight: bold;"><?php echo __('Created at') ?></label>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<p><?php echo $work_place_detail->getCreatedAt(); ?></p>

				</div>
			</div>
		</div>

		<div class="row">
			<div class="form-group">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<label class="control-label" style="font-weight: bold;"><?php echo __('Created by', array(), 'messages') ?></label>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<p><?php echo $work_place_detail->getCreatedBy(); ?></p>

				</div>
			</div>
		</div>

		<div class="row">
			<div class="form-group">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<label class="control-label" style="font-weight: bold;"><?php echo __('Updated at') ?></label>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<p><?php echo $work_place_detail->getUpdatedAt(); ?></p>

				</div>
			</div>
		</div>

		<div class="row">
			<div class="form-group">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<label class="control-label" style="font-weight: bold;"><?php echo __('Updated by', array(), 'messages') ?></label>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<p><?php echo $work_place_detail->getUpdatedBy();?></p>
				</div>
			</div>
		</div>

	</div>
</div>
<div class="clearfix"></div>
</div>
<div class="modal-footer">
	<?php
	if ($sf_user->hasCredential ( 'PS_SYSTEM_WORK_PLACES_EDIT' )) {
		echo link_to ( '<i class="fa-fw fa fa-pencil"></i> ' . __ ( 'Edit' ), 'ps_work_places_edit', $work_place_detail, array (
				'class' => 'btn btn-default btn-success btn-sm btn-psadmin' ) );
	}
	?>
	<button type="button" class="btn btn-default" data-dismiss="modal">
		<i class="fa-fw fa fa-ban"></i>&nbsp;<?php echo __('Close') ?></button>
</div>