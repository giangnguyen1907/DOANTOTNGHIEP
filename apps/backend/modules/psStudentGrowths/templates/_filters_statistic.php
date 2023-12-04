<div id="dt_basic_filter" class="sf_admin_filter dataTables_filter">
	<?php if ($formFilter->hasGlobalErrors()): ?>
      <?php echo $formFilter->renderGlobalErrors() ?>
    <?php endif; ?>
	<form id="psnew-filter" class="form-inline pull-left"
		action="<?php echo url_for('ps_student_growths_collection', array('action' => 'statistic')) ?>"
		method="post">
		<input type="hidden" name="ps_student_growths_statistic_url"
			value="ps_student_growths_statistic"
			id="ps_student_growths_statistic_url">
		<div class="pull-left">
    	 	<?php echo $formFilter->renderHiddenFields(true) ?>
    	 	<div class="form-group">
				<label>
        		 	<?php echo $formFilter['ps_school_year_id']->render() ?>
        		 	<?php echo $formFilter['ps_school_year_id']->renderError()?>
        		 </label>
			</div>

			<div class="form-group">
				<label>
        		 	<?php echo $formFilter['ps_customer_id']->render() ?>
        		 	<?php echo $formFilter['ps_customer_id']->renderError()?>
        		</label>
			</div>
			<div class="form-group">
				<label>
        		 	<?php echo $formFilter['ps_workplace_id']->render() ?>
        		 	<?php echo $formFilter['ps_workplace_id']->renderError()?>
        		</label>
			</div>
			<div class="form-group">
				<label>
        		 	<?php echo $formFilter['ps_obj_group_id']->render() ?>
        		 	<?php echo $formFilter['ps_obj_group_id']->renderError()?>
        		</label>
			</div>
			<div class="form-group">
				<label>
    		 		<?php echo $formFilter['class_id']->render() ?>
    		 		<?php echo $formFilter['class_id']->renderError()?>
    		  	</label>
			</div>

			<div class="form-group">
				<label>
    		 		<?php echo $formFilter['examination_id']->render() ?>
    		 		<?php echo $formFilter['examination_id']->renderError()?>
    		  	</label>
			</div>
			<div class="form-group">
				<label>
    		 		<?php echo $formFilter['malnutrition']->render() ?>
    		 		<?php echo $formFilter['malnutrition']->renderError()?>
    		  	</label>
			</div>
			<div class="form-group">
				<label>
    				<?php echo $helper->linkToFilterSearch() ?>
    			</label>
			</div>

			<div class="form-group">
				<label>
    				<?php echo $helper->linkToFilterReset2() ?>
    			</label>
			</div>

		</div>
	</form>
</div>