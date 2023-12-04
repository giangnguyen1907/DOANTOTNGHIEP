<div id="errors"></div>
<form id="ps-filter-form" class="form-inline fv-form fv-form-bootstrap"
	method="post"
	action="<?php echo url_for('ps_fee_reports_control_step2')?>">
	<div class="widget-body-toolbar">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<div class="form-group">
					<?php echo $formFilter['year_month']->render()?>
				</div>
				<div class="form-group">
					<?php echo $formFilter['normal_day']->render()?>
				</div>
				<div class="form-group">
					<?php echo $formFilter['saturday_day']->render()?>
				</div>

			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<button form="ps-filter-form" type="submit"
					class="btn btn-sm btn-success bg-color-green btn-next"><?php echo __('Next')?> <i
						class="fa fa-arrow-right"></i>
				</button>
			</div>
		</div>
	</div>	
	<?php if ($formFilter->isCSRFProtected()): ?>
	<input type="hidden"
		name="<?php echo $formFilter->getCSRFFieldName();?>"
		value="<?php echo $formFilter->getCSRFToken();?>" />
	<?php endif; ?>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			<?php if ($sf_user->hasCredential('PS_FEE_REPORT_FILTER_SCHOOL')): ?>
				<div class="form-group">
					<?php echo $formFilter['ps_customer_id']->render()?>
				</div>
			<div class="form-group">
					<?php echo $formFilter['ps_workplace_id']->render()?>
				</div>					
			<?php else:?>
				<?php echo $formFilter['ps_customer_id']->render()?>
				<div class="form-group">
					<?php echo $formFilter['ps_workplace_id']->render()?>
				</div>
			<?php endif;?>
		</div>
	</div>
</form>

