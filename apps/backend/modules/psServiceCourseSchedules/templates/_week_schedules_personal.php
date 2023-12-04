<div class="box-body">
	<form id="psnew-filter" class="form-inline pull-left dataTables_filter"
		action="<?php echo url_for('ps_service_course_schedules_collection', array('action' => 'new')) ?>"
		method="post">
		<div class="dt-toolbar" style="padding-bottom: 10px;">
			<div class="form-group ">
				<label>
			 <?php echo $formFilter['ps_customer_id']->render()?>
			 <?php echo $formFilter['ps_customer_id']->renderError()?>
			  </label>
			</div>

			<div class="form-group ">
				<label> 
			 	<?php echo $formFilter['ps_workplace_id']->render()?>
			 	<?php echo $formFilter['ps_workplace_id']->renderError()?>
			  	</label>
			</div>

			<div class="form-group ">
				<label> 
			 	<?php echo $formFilter['ps_class_room_id']->render()?>
			 	<?php echo $formFilter['ps_class_room_id']->renderError()?>
			  	</label>
			</div>

			<div class="form-group ">
				<label>	 
			 	<?php echo $formFilter['ps_member_id']->render()?>
			 	<?php echo $formFilter['ps_member_id']->renderError()?>
			  	</label>
			</div>

			<div class="form-group ">
				<label>	 
			 	<?php echo $formFilter['ps_service_id']->render()?>
			 	<?php echo $formFilter['ps_service_id']->renderError()?>
			  	</label>
			</div>

			<div class="form-group">
				<label>
			 	<?php echo $formFilter['ps_service_course_id']->render()?>
			 	<?php echo $formFilter['ps_service_course_id']->renderError()?>
			 	 </label>
			</div>

			<div class="form-group">
				<label>
			 	<?php echo $formFilter['ps_year']->render()?>
			 	<?php echo $formFilter['ps_year']->renderError()?>
			 	 </label>
			</div>

			<div class="form-group ">
				<label>
			 	<?php echo $formFilter['ps_week']->render()?>
			 	<?php echo $formFilter['ps_week']->renderError()?>
			 	</label>
			</div>
			<div class="form-group ">
				<div class="btn-group">
					<label> <a href="javascript:void(0)" rel="tooltip"
						data-placement="top"
						data-original-title="<?php echo __("Week pre");?>"
						class="btn btn-default btn-sm" id="btn-prev"><i
							class="fa fa-chevron-left"></i></a> <a href="javascript:void(0)"
						class="btn btn-default btn-sm" id="btn-next"><i
							class="fa fa-chevron-right"></i></a>
					</label>
				</div>
			</div>

		</div>
		
		<?php include_partial('global/include/_ic_loading');?>
		
		<div id="tbl-menu">	
		<?php include_partial('psServiceCourseSchedules/table_schedules', array('list_course_schedules' => $list_course_schedules, 'week_list' => $week_list, 'width_th' => (100 / (count($week_list) + 1)),'formFilter' => $formFilter));?>
		</div>
	</form>
</div>