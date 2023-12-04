<div id="dt_basic_filter" class="sf_admin_filter dataTables_filter">
	<?php if ($formFilter->hasGlobalErrors()): ?>
      <?php echo $formFilter->renderGlobalErrors() ?>
    <?php endif; ?>
	<form id="ps-filter" class="form-inline pull-left"
		action="<?php echo url_for('ps_student_service_course_comment_collection', array('action' => 'history')) ?>"
		method="post">
		<input type="hidden" name="ps_student_service_course_comment_history"
			value="ps_student_service_course_comment_history"
			id="ps_student_service_course_comment_history_url">
		<div class="pull-left">
    	 	<?php echo $formFilter->renderHiddenFields(true) ?>
    		<div class="form-group hidden">
				<label>
        		 	<?php echo $formFilter['ps_school_year_id']->render() ?>
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
    		 		<?php echo $formFilter['student_id']->render() ?>
    		  	</label>
			</div>

			<div class="form-group">
				<label>
    		 		<?php echo $formFilter['date_at_from']->render() ?>
    		  	</label>
			</div>

			<div class="form-group">
				<label>
    		 		<?php echo $formFilter['date_at_to']->render() ?>
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