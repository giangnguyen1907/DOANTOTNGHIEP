<div id="errors"></div>
<form id="ps-filter-form" class="form-inline fv-form fv-form-bootstrap"
	method="post" action="">
	<div class="form-group">
			<?php echo $formFilter['ps_school_year_id']->render()?>
		</div>			
		<?php if ($sf_user->hasCredential('PS_STUDENT_SERVICE_FILTER_SCHOOL')): ?>
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
			<?php echo $formFilter['class_id']->render()?>
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
