<div id="errors"></div>
<form id="ps-filter-form" class="form-inline fv-form fv-form-bootstrap"
	method="post"
	action="<?php echo url_for('ps_fee_reports_control_step3')?>">
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
				<button type="button"
					class="btn btn-sm btn-success btn-prev btn-prev-step2">
					<i class="fa fa-arrow-left"></i> <?php echo __('Prev')?></button>
				<button type="submit"
					class="btn btn-sm btn-success bg-color-green btn-next"
					data-last="Finish"><?php echo __('Next')?> <i
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
						<label><strong class="label label-primary"><?php echo __('Class')?></strong></label>
						<div class="custom-scroll padding-5"
							style="height: 200px; overflow-y: scroll;">
						<?php echo $formFilter['ps_class_id']->render()?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
			<div class="form-group">
				<div class="alert alert-warning no-margin fade in">
					<?php echo __('Only show unprocessed layers of the month.');?><br />
					<?php echo __('Note: choose class or skip.');?>										
				</div>
			</div>
		</div>
	</div>
</form>
