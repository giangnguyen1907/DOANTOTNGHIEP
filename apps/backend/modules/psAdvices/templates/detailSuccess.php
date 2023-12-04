<?php use_helper('I18N', 'Date') ?>
<?php include_partial('psAdvices/assets') ?>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">×</button>
	<h4 class="modal-title" id="myModalLabel"><?php echo __('Advice information') ?></h4>
</div>
<div class="modal-body">

	<div class="row">
		<div class="form-group">
			<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
				<label class="control-label"><?php echo __('Category') ?></label>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
				<p>
					<strong><?php echo $advice_detail->getAcTitle()?></strong>
				</p>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
				<label class="control-label"><?php echo __('Title') ?></label>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
				<p><?php echo $advice_detail->getTitle()?></p>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
				<label class="control-label"><?php echo __('Student') ?></label>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
				<p><?php echo $advice_detail->getStudentCode() . ' - ' . $advice_detail->getStudentName()?></p>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
				<label class="control-label"><?php echo __('User created') ?></label>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
				<p><?php echo $advice_detail->getCreatorBy() ?></p>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
				<label class="control-label"><?php echo __('Content') ?></label>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
				<p><?php echo $advice_detail->getContent()?></p>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
				<label class="control-label"><?php echo __('User') ?></label>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
				<p><?php echo $advice_detail->getUserId() ?></p>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
				<label class="control-label"><?php echo __('Feedback content') ?></label>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
				<p><?php echo $advice_detail->getFeedbackContent() ?></p>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
				<label class="control-label"><?php echo __('Status') ?></label>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
				<?php $status = $advice_detail->getIsActivated() ?>
				<?php if($status == PreSchool::NOT_ACTIVE) {?>
				<?php echo __('Inactive')?>
				<?php }else{ ?>
				<?php echo __('Inactived') ?>
				<?php }?>
			</div>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">
		<i class="fa-fw fa fa-ban"></i>&nbsp;<?php echo __('Close') ?></button>
</div>

