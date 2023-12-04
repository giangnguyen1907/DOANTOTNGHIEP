<div id="dt_basic_filter" class="sf_admin_filter dataTables_filter">
	<?php if ($formFilter->hasGlobalErrors()): ?>
      <?php echo $formFilter->renderGlobalErrors() ?>
    <?php endif; ?>
	<form id="ps-filter" class="form-inline pull-left"
		action="<?php echo url_for('ps_timesheet_summarys_collection', array('action' => 'synthetic')) ?>"
		method="post">
		<input type="hidden" name="ps_logtimes_statistic_url"
			value="ps_logtimes_statistic" id="ps_logtimes_statistic_url">
		<div class="pull-left">
    	 	<?php echo $formFilter->renderHiddenFields(true) ?>
    	 	<div class="form-group">
				<label>
        		 	<?php echo $formFilter['ps_school_year_id']->render() ?>
        		 </label>
			</div>

			<div class="form-group">
				<label>
        		 	<?php echo $formFilter['year_month']->render() ?>
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
    		 		<?php echo $formFilter['ps_department_id']->render() ?>
    		  	</label>
			</div>

			<div class="form-group">
				<label>
    				<?php echo $helper->linkToFilterSearch2() ?>
    			</label>
			</div>

		</div>
	</form>
</div>