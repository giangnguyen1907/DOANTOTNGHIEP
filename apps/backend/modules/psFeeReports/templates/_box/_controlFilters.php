<form id="ps-filter" class="form-horizontal pull-left" method="post"
	action="<?php echo url_for('ps_fee_reports_control')?>">
	<?php $form = new BaseForm(); if ($form->isCSRFProtected()): ?>
	    <input type="hidden" name="<?php echo $form->getCSRFFieldName() ?>"
		value="<?php echo $form->getCSRFToken()?>" />
	<?php endif; ?>
	
	<?php if ($sf_user->hasCredential('PS_FEE_REPORT_FILTER_SCHOOL')): ?>
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="form-group">
			<?php echo $formFilter['ps_customer_id']->render()?>
		</div>
	</div>
	<?php else:?>
		<?php echo $formFilter['ps_customer_id']->render()?>
	<?php endif;?>
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="form-group">
			<?php echo $formFilter['ps_workplace_id']->render()?>
		</div>
	</div>

	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="form-group">
		<?php echo $formFilter['year_month']->render()?>
		</div>
	</div>

	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="form-group">
			<label><strong><?php echo __('Class')?></strong></label>
			<div id="list_class">
				<?php echo $formFilter['ps_class_id']->render()?>
			</div>
		</div>
	</div>

</form>