<div id="dt_basic_filter" class="sf_admin_filter dataTables_filter">
	<?php if ($formFilter->hasGlobalErrors()): ?>
      <?php echo $formFilter->renderGlobalErrors() ?>
    <?php endif; ?>
	<form id="ps-filter" class="form-inline pull-left"
		action="<?php echo url_for('ps_evaluate_index_student_collection', array('action' => 'statistic')) ?>"
		method="post">
		<input type="hidden" name="evaluate_student_statistic_url"
			value="evaluate_student_statistic"
			id="evaluate_student_statistic_url">
		<div class="pull-left">
    	 	<?php echo $formFilter->renderHiddenFields(true) ?>
    	 	<div class="form-group">
				<label>
        		 	<?php echo $formFilter['ps_school_year_id']->render() ?>
        		 </label>
			</div>
			
			<div class="form-group">
				<label>
    		 		<?php echo $formFilter['ps_semester']->render() ?>
    		  	</label>
			</div>
			
			<div class="form-group">
				<label>
        		 	<?php echo $formFilter['ps_customer_id']->render() ?>
        		</label>
			</div>
			<div class="form-group">
				<label>
        		 	<?php echo $formFilter['ps_workplace_id']->render() ?>
        		</label>
			</div>

			<div class="form-group">
				<label>
    		 		<?php echo $formFilter['class_id']->render() ?>
    		  	</label>
			</div>

			<div class="form-group">
				<label>
    		 		<?php echo $formFilter['type']->render() ?>
    		  	</label>
			</div>

			<div class="form-group">
				<label>
    				<?php echo $helper->linkToFilterSearch() ?>
    			</label>
			</div>

		</div>
	</form>
</div>
