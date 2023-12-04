<div class="dt-toolbar no-margin no-padding no-border"
	style="padding-bottom: 10px;">
	<div class="col-xs-12 col-sm-12">
		<div id="dt_basic_filter" class="sf_admin_filter dataTables_filter">
			<form id="psnew-filter"
				class="form-inline pull-left dataTables_filter"
				action="<?php echo url_for('ps_feature_branch_times_collection', array('action' => '')) ?>"
				method="post">
				<div class="form-group">
					<label> 
				 <?php echo $formFilter['school_year_id']->render()?>
				 <?php echo $formFilter['school_year_id']->renderError()?>
				 </label>
				</div>
				<div class="form-group">
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
				 <?php echo $formFilter['ps_class_id']->render()?>
				 <?php echo $formFilter['ps_class_id']->renderError()?>
				 </label>
				</div>
				<div class="form-group ">
					<label> 
				 <?php echo $formFilter['date_at']->render()?>
				 <?php echo $formFilter['date_at']->renderError()?>
				 </label>
				</div>

			</form>
		</div>
	</div>
</div>
<?php include_partial('global/include/_ic_loading');?>

<div id="tbl-menu">	
<?php include_partial('psFeatureBranchTimes/table_menu', array('list_menu'=>$list_menu, 'week_start'=>$week_start, 'week_end'=>$week_end, 'week_list' => $week_list, 'width_th' => (100 / (count($week_list) + 1)),'formFilter' => $formFilter, 'form' => $form, 'ps_feature_branch_times' => $ps_feature_branch_times));?>
</div>
<script>
if($('#menus_filter_school_year_id').val() > 0 ){
	$("#menus_filter_school_year_id").trigger("change");
}
</script>
