<div id="errors"></div>
<form id="ps-filter-form"
	class="form-horizontal fv-form fv-form-bootstrap" method="post"
	action="<?php echo url_for('ps_fee_reports_control_step3')?>">
	<div class="widget-body-toolbar text-right">
		<button type="button"
			class="btn btn-sm btn-success btn-prev btn-prev-step2">
			<i class="fa fa-arrow-left"></i> <?php echo __('Prev')?></button>
		<button type="submit"
			class="btn btn-sm btn-success bg-color-green btn-next"
			data-last="Finish"><?php echo __('Next')?> <i
				class="fa fa-arrow-right"></i>
		</button>
	</div>
	
	<?php $form = new BaseForm(); if ($formFilter->isCSRFProtected()): ?>
	    <input type="hidden"
		name="<?php echo $formFilter->getCSRFFieldName() ?>"
		value="<?php echo $formFilter->getCSRFToken()?>" />
	<?php endif; ?>
	<?php echo $formFilter['ps_customer_id']->render()?>
	<?php echo $formFilter['ps_workplace_id']->render()?>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<div class="form-group">
				<?php echo $ps_workplace->getPsCustomer()->getSchoolName();?><br />
				<?php echo $ps_workplace->getTitle();?>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<div class="form-group">
					<label><strong><?php echo __('Class')?></strong></label>
					<div class="custom-scroll"
						style="height: 200px; overflow-y: scroll;">
					<?php echo $formFilter['ps_class_id']->render()?>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			<div class="form-group">
				<div class="alert alert-warning no-margin fade in">
						<?php echo __('Note: choose class or skip');?>
					</div>
			</div>
		</div>
	</div>
</form>
