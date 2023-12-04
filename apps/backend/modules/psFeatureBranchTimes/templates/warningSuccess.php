<?php use_helper('I18N', 'Date') ?>
<?php include_partial('psFeatureBranchTimes/assets') ?>
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
									<div id="sf_admin_header"></div>
								</div>
							</div>
							<div id="sf_admin_bar" class="dt-toolbar">
								<div class="col-xs-12 col-sm-12">
							    <?php include_partial('psFeatureBranchTimes/filters', array('form' => $filters, 'configuration' => $configuration,'helper' => $helper)) ?>
							  </div>
							</div>
						<?php include_partial('global/include/_no_result', array('text' => __('No result', array(), 'sf_admin')));?>
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
	
	// START AND FINISH DATE	
	if($('#feature_branch_times_filters_school_year_id').val() > 0 ){
		$("#feature_branch_times_filters_school_year_id").trigger("change");
	}
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