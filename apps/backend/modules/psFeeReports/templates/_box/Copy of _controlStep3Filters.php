<form id="fstep3" class="form-horizontal fv-form fv-form-bootstrap"
	method="post"
	action="<?php echo url_for('ps_fee_reports_control_step4')?>">
	<div class="widget-body-toolbar text-right">
		<button type="submit"
			class="btn btn-sm btn-success btn-prev btn-prev-step3">
			<i class="fa fa-arrow-left"></i> <?php echo __('Prev')?></button>
		<button type="submit"
			class="btn btn-sm btn-success bg-color-green btn-next"
			data-last="Finish"><?php echo __('Next')?> <i
				class="fa fa-arrow-right"></i>
		</button>
	</div>
	<?php if ($formFilter->isCSRFProtected()): ?>
	    <input type="hidden"
		name="<?php echo $formFilter->getCSRFFieldName() ?>"
		value="<?php echo $formFilter->getCSRFToken()?>" />
	<?php endif; ?>
	<?php echo $formFilter['ps_customer_id']->render()?>
	<?php echo $formFilter['ps_workplace_id']->render()?>
	
	<?php if(count($ps_class_id) > 0):?>
		<?php foreach ($ps_class_id as $i => $class_id):?>
		<input type="hidden" name="control_filter[ps_class_id][]"
		value="<?php echo $class_id;?>" />
		<?php endforeach;?>
	<?php else: ?>
		<input type="hidden" name="control_filter[ps_class_id][]" />
	<?php endif; ?>
	
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">

			<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
				<div class="form-group">
				<?php echo $ps_workplace->getPsCustomer()->getSchoolName();?><br />
				<?php echo $ps_workplace->getTitle();?>
				</div>
			</div>

			<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
				<div class="form-group">
					<label><strong><?php echo __('Class')?></strong></label>
					<?php echo $formFilter['ps_class_id_2']->render()?>
				</div>
			</div>

			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<div class="row">
					<div class="form-group"
						title="<?php echo __('Please select Month.')?>">
						<div class="col-md-4">										
							<?php echo $formFilter['year_month']->render()?>					
						</div>
						<div class="col-md-8">
						<?php echo $formFilter['year_month']->renderlabel(array(), array('class' => 'control-label'))?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="form-group">
						<div class="col-md-4">
							<?php echo $formFilter['normal_day']->render()?>
						</div>
						<div class="col-md-8">
						<?php echo $formFilter['normal_day']->renderlabel(array(), array('class' => 'control-label'))?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="form-group">
						<div class="col-md-4">
						<?php echo $formFilter['saturday_day']->render()?>
						</div>
						<div class="col-md-8">
						<?php echo $formFilter['saturday_day']->renderlabel(array(), array('class' => 'control-label'))?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
			<div class="form-group">
				<div class="alert alert-warning no-margin fade in">
					<?php echo __('Note: Enter normal day and saturday day');?>
				</div>
			</div>
		</div>
	</div>
</form>
