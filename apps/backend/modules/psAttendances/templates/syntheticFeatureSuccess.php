<?php use_helper('I18N', 'Date')?>
<?php include_partial('psAttendances/assets')?>
<style>
.sunday {
	background: #999 !important;
}

.saturday {
	background: #ccc !important;
}
</style>

<script type="text/javascript">
$(document).ready(function() {
	$('#synthetic_month_filter_ps_customer_id').change(function() {
		resetOptions('synthetic_month_filter_ps_workplace_id');
		$('#synthetic_month_filter_ps_workplace_id').select2('val','');
		$("#synthetic_month_filter_ps_workplace_id").attr('disabled', 'disabled');
		
	if ($(this).val() > 0) {

		$("#synthetic_month_filter_ps_workplace_id").attr('disabled', 'disabled');
		
		$.ajax({
			url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' + $(this).val(),
	        type: "POST",
	        data: {'psc_id': $(this).val()},
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {

	    	$('#synthetic_month_filter_ps_workplace_id').select2('val','');

			$("#synthetic_month_filter_ps_workplace_id").html(msg);

			$("#synthetic_month_filter_ps_workplace_id").attr('disabled', null);

	    });
	}		
});

	$('#synthetic_month_filter_date_at').datepicker({
		dateFormat : 'dd-mm-yy',
		maxDate : new Date(),
		prevText : '<i class="fa fa-chevron-left"></i>',
		nextText : '<i class="fa fa-chevron-right"></i>',
		changeMonth : true,
		changeYear : true,
	})
});
</script>

<section id="widget-grid">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<?php include_partial('psAttendances/flashes')?>
			<div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-grid="false" data-widget-collapsed="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false" data-widget-togglebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-table"></i></span>
					<h2><?php echo __('Synthetic statistic month', array(), 'messages').__('Day total', array(), 'messages').$year_month.' : '.$number_day['saturday_day'].' '.__('Day', array(), 'messages') ?></h2>

				</header>

				<div>
					<div class="widget-body no-padding">
						<div class="dt-toolbar">
							<div class="col-xs-12 col-sm-12">
								<div id="dt_basic_filter"
									class="sf_admin_filter dataTables_filter">
	<?php if ($formFilter->hasGlobalErrors()): ?>
      <?php echo $formFilter->renderGlobalErrors() ?>
    <?php endif; ?>
	<form id="ps-filter" class="form-inline pull-left"
										action="<?php echo url_for('ps_attendances_collection', array('action' => 'syntheticFeature')) ?>"
										method="post">
										<div class="pull-left">
    	 	<?php echo $formFilter->renderHiddenFields(true) ?>
    	 	<div class="form-group">
												<label>
        		 	<?php echo $formFilter['ps_school_year_id']->render() ?>
        		 	<?php echo $formFilter['ps_school_year_id']->renderError() ?>
        		 </label>
											</div>
											<div class="form-group">
												<label>
        		 	<?php echo $formFilter['ps_customer_id']->render() ?>
        		 	<?php echo $formFilter['ps_customer_id']->renderError() ?>
        		</label>
											</div>
											<div class="form-group">
												<label>
        		 	<?php echo $formFilter['ps_workplace_id']->render() ?>
        		 	<?php echo $formFilter['ps_workplace_id']->renderError() ?>
        		</label>
											</div>
											<div class="form-group">
												<label>
        		 	<?php echo $formFilter['date_at']->render() ?>
        		 	<?php echo $formFilter['date_at']->renderError() ?>
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
							</div>
						</div>
						<div id="datatable_fixed_column_wrapper"
							class="dataTables_wrapper form-inline no-footer no-padding">
							<div class="custom-scroll table-responsive">
								<div class="row1">
									<div class="tab-content">
										<div id="home" class="tab-pane fade in active">
											<div class="table-responsive"></div>
										</div>

									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

		</article>

	</div>
</section>
