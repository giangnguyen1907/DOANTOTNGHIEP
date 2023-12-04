<div id="dt_basic_filter" class="sf_admin_filter dataTables_filter">
	<?php if ($formFilter->hasGlobalErrors()): ?>
      <?php echo $formFilter->renderGlobalErrors() ?>
    <?php endif; ?>
	<form id="ps-filter" class="form-inline pull-left"
		action="<?php echo url_for('ps_service_courses_collection', array('action' => 'statistic')) ?>"
		method="post">
		<input type="hidden" name="student_servive_filter"
			value="student_servive_filter" id="student_servive_filter">
		<div class="pull-left">
             <?php echo $formFilter->renderHiddenFields(true) ?>
             
			<div class="form-group">
				<label>
                      <?php echo $formFilter['school_year_id']->render() ?>
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
                        <?php echo $formFilter['ps_service_id']->render() ?>
                </label>
			</div>

			<!-- 			<div class="form-group"> -->
			<!-- 				<label> -->
                        <?php //echo $formFilter['course']->render() ?>
<!--                 </label> -->
			<!-- 			</div> -->

			<!-- 			<div class="form-group"> -->
			<!-- 				<label> -->
                    	<?php //echo $formFilter['ps_member_id']->render() ?>
<!--                 </label> -->
			<!-- 			</div> -->

			<!-- 			<div class="form-group"> -->
			<!-- 				<label> -->
                    	<?php //echo $formFilter['is_status']->render() ?>
<!--                 </label> -->
			<!-- 			</div> -->

			<!-- 			<div class="form-group"> -->
			<!-- 				<label> -->
                    	<?php //echo $formFilter['date_at_from']->render() ?>
<!--                 </label> -->
			<!-- 			</div> -->

			<!-- 			<div class="form-group"> -->
			<!-- 				<label> -->
                    	<?php //echo $formFilter['date_at_to']->render() ?>
<!--                 </label> -->
			<!-- 			</div> -->

			<div class="form-group">
				<label>
                    	<?php echo $formFilter['keywords']->render() ?>
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