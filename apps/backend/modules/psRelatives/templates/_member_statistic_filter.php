<div id="dt_basic_filter" class="sf_admin_filter dataTables_filter">
	<?php if ($formFilter->hasGlobalErrors()): ?>
      <?php echo $formFilter->renderGlobalErrors() ?>
    <?php endif; ?>
	<form id="ps-filter" class="form-inline pull-left"
		action="<?php echo url_for('ps_relatives_collection', array('action' => 'statistic')) ?>"
		method="post">
		<input type="hidden" name="member_statistic_filter_url"
			value="member_statistic_filter" id="member_statistic_filter_url">
		<div class="pull-left">
             <?php echo $formFilter->renderHiddenFields(true) ?>
             
			<div class="form-group">
				<label>
                      <?php echo $formFilter['school_year_id']->render() ?>
                </label>
			</div>

			<!-- 			<div class="form-group"> -->
			<!-- 				<label> -->
                      <?php //echo $formFilter['ps_month']->render() ?>
<!--                 </label> -->
			<!-- 			</div> -->

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
                      <?php echo $formFilter['ps_class_id']->render() ?>
                </label>
			</div>

			<div class="form-group">
				<label>
                      <?php echo $formFilter['app_mobile_actived']->render() ?>
                </label>
			</div>

			<div class="form-group">
				<label>
                      <?php echo $formFilter['type_statistic']->render() ?>
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