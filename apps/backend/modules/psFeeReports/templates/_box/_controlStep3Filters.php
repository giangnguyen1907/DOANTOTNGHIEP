<div id="errors"></div>
<form id="fstep3" class="form-inline fv-form fv-form-bootstrap"
	method="post"
	action="<?php echo url_for('ps_fee_reports_control_step4')?>">
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
				<button type="submit"
					class="btn btn-sm btn-success btn-prev btn-prev-step3">
					<i class="fa fa-arrow-left"></i> <?php echo __('Prev')?></button>
				<button type="submit"
					class="btn btn-sm btn-success bg-color-green btn-next"><?php echo __('Next')?> <i
						class="fa fa-arrow-right"></i>
				</button>
			</div>
		</div>
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
		<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
					<?php if ($sf_user->hasCredential('PS_FEE_REPORT_FILTER_SCHOOL')):?>
					<p>
					<?php echo $ps_workplace->getPsCustomer()->getSchoolName();?>
					</p>
					<?php endif;?>					
					<p class="label label-primary"><?php echo $ps_workplace->getTitle();?></p>
					<br />
					<p class="label label-primary"><?php echo __('List of paid processing classes')?>:</p>
					<div class="custom-scroll"
						style="height: 200px; overflow-y: scroll;">
					<?php foreach ($ps_fee_reports_my_class as $i => $ps_my_class):?>
					<p><?php echo $ps_my_class->getClassName();?></p>
					<?php endforeach;?>						
					</div>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
					<div class="form-group">
						<p class="label label-primary"><?php echo __('List of select classes')?>:</p>
						<div class="custom-scroll padding-5"
							style="height: 200px; overflow-y: scroll;">
						<?php echo $formFilter['ps_class_id_2']->render()?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
			<span id="list_receivable_temp">
					<?php include_partial('psFeeReports/box/_table_receivable_month', array('receivable_for_fee_report' => $receivable_for_fee_report, 'receivables' => $receivables));?>
			</span>
		</div>
	</div>
</form>