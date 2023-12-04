<div id="errors"></div>
<form id="ps-filter-form" class="form-inline fv-form fv-form-bootstrap"
	method="post" action="">
	<div class="form-group">
			<?php echo $formFilter['year_month']->render()?>
		</div>			
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
		
		<div class="form-group">
			<?php echo $formFilter['ps_class_id']->render()?>
		</div>

	<div class="form-group">
			<?php echo $formFilter['keywords']->render()?>
		</div>

	<div class="form-group">			      
	    	<?php echo $helper->linkToFilterSearchReceivableStudent()?>
	 	</div>	 	
	<?php if ($formFilter->isCSRFProtected()): ?>
	<input type="hidden"
		name="<?php echo $formFilter->getCSRFFieldName();?>"
		value="<?php echo $formFilter->getCSRFToken();?>" />
	<?php endif; ?>	
</form>

