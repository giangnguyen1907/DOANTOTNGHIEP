<div id="errors"></div>

<form id="ps-filter-form" class="form-inline fv-form fv-form-bootstrap"
	method="post"
	action="<?php echo url_for('ps_fee_reports_control_step2')?>">
	<div class="widget-body-toolbar text-right">
		<button form="ps-filter-form" type="submit"
			class="btn btn-sm btn-success bg-color-green btn-next"><?php echo __('Next')?> <i
				class="fa fa-arrow-right"></i>
		</button>
	</div>	
	<?php $form = new BaseForm(); if ($formFilter->isCSRFProtected()): ?>
	    <input type="hidden"
		name="<?php echo $formFilter->getCSRFFieldName() ?>"
		value="<?php echo $formFilter->getCSRFToken()?>" />
	<?php endif; ?>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">			
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

