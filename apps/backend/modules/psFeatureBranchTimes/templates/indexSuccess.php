<?php use_helper('I18N', 'Date') ?>
<?php include_partial('psFeatureBranchTimes/assets') ?>
<script type="text/javascript">
	var number_page = <?php echo $pager->getNbResults();?>;
</script>
<style>
.float-right {
	float: right;
}

.float-right a {
	margin-top: -10px;
}
</style>
<section id="widget-grid">
	<!--  sf_admin_container -->
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

		<?php include_partial('psFeatureBranchTimes/flashes') ?>

		<!-- sf_admin_container -->
			<div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-grid="false" data-widget-collapsed="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false" data-widget-togglebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-table"></i></span>
					<h2><?php echo __('Schedule activity', array(), 'messages') ?></h2>
				</header>
				<div>
					<div class="widget-body no-padding">
						<div id="datatable_fixed_column_wrapper"
							class="dataTables_wrapper form-inline no-footer no-padding">
							<div class="dt-toolbar no-margin no-padding no-border">
								<div class="col-xs-12 col-sm-12">
									<div id="sf_admin_header"><?php include_partial('psFeatureBranchTimes/list_header', array('pager' => $pager)) ?></div>
								</div>
							</div>
							<div id="sf_admin_bar" class="dt-toolbar">
								<div class="col-xs-12 col-sm-12">
							    <?php include_partial('psFeatureBranchTimes/filters', array('form' => $filters, 'configuration' => $configuration,'helper' => $helper)) ?>
							  </div>
							</div>												
					
						<?php if (!$pager->getNbResults()):?>
						<?php include_partial('global/include/_no_result');?>  
					  	<?php endif;?>
						<div
								class="sf_admin_actions dt-toolbar-footer no-border-transparent">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right">
									<a class="btn btn-success"
										href="<?php echo url_for(@ps_feature_branch_import) ?>"><i
										class="fa fa-upload"></i><?php echo __('Import file') ?></a>
								</div>
							</div>
							<form id="frm_batch"
								action="<?php echo url_for('ps_feature_branch_times_collection', array('action' => 'batch')) ?>"
								method="post" style="border-top: 1px solid #ccc;">					
					<?php include_partial('psFeatureBranchTimes/list', array('pager' => $pager, 'sort' => $sort, 'helper' => $helper)) ?>
					
					<!-- sf_admin_footer -->
								<div
									class="sf_admin_actions dt-toolbar-footer no-border-transparent">
									<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-left">				    	
				    	<?php if ($pager->haveToPaginate()): ?>
      					<?php include_partial('psFeatureBranchTimes/pagination', array('pager' => $pager)) ?>
    					<?php endif; ?>
				    	</div>

									<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-right">					      
					      <?php include_partial('psFeatureBranchTimes/list_actions', array('helper' => $helper)) ?>
					      <?php include_partial('psFeatureBranchTimes/list_batch_actions', array('helper' => $helper)) ?>
					    </div>
								</div>
								<!-- END: sf_admin_footer -->
							</form>
							<div class="float-right">
								<a class="btn btn-success"
									href="<?php echo url_for(@ps_feature_branch_import) ?>"><i
									class="fa fa-upload"></i><?php echo __('Import file') ?></a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- END: sf_admin_container -->
		</article>
	</div>
</section>

<script type="text/javascript">
$(document).ready(function() {
	
	$.datepicker.setDefaults($.datepicker.regional[ "vi" ]);

	if($('#feature_branch_times_filters_school_year_id').val() > 0 ){
		$("#feature_branch_times_filters_school_year_id").trigger("change");
	}
	
	// START AND FINISH DATE	
	$('#feature_branch_times_filters_date_at_from').datepicker({	
		prevText : '<i class="fa fa-chevron-left"></i>',
	    nextText : '<i class="fa fa-chevron-right"></i>',
		changeMonth: true,
        changeYear: true,
        dateFormat: 'dd-mm-yy',
		onSelect : function(selectedDate) {
			$('#feature_branch_times_filters_date_at_to').datepicker('option', 'minDate', selectedDate);
		}
	}).on('changeDate', function(e) {
	     $('#ps-filter-form').formValidation('revalidateField', 'feature_branch_times_filters[date_at_from]');
	});

	$('#feature_branch_times_filters[date_at_from]').change(function() {      
		$('#ps-filter-form').formValidation('revalidateField', 'feature_branch_times_filters[date_at_from]');    	
    });
	
	$('#feature_branch_times_filters_date_at_to').datepicker({			
		prevText : '<i class="fa fa-chevron-left"></i>',
	    nextText : '<i class="fa fa-chevron-right"></i>',
		changeMonth: true,
        changeYear: true,
        dateFormat: 'dd-mm-yy',
		onSelect : function(selectedDate) {
			$('#feature_branch_times_filters_date_at_from').datepicker('option', 'maxDate', selectedDate);
		}
	}).on('changeDate', function(e) {
	     $('#ps-filter-form').formValidation('revalidateField', 'feature_branch_times_filters[date_at_from]');
	});	
});
</script>