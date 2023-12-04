<?php use_helper('I18N', 'Date')?>
<?php include_partial('psMenusImports/assets')?>
<?php include_partial('global/include/_box_modal_messages');?>
<style>
.float-right {
	float: right;
	margin-right: 20px;
}

.float-right a {
	margin-top: -10px;
}
</style>
<?php $upload_max_size = 2000;?>
<script type="text/javascript">
$(document).ready(function() {

	$('#remoteModal').on('hide.bs.modal', function(e) {
		$(this).removeData('bs.modal');
	});
	
	var msg_select_ps_customer_id	= '<?php echo __('Please select school to filter the data.')?>';
	var msg_select_ps_workplace_id 	= '<?php echo __('Please select workplace to filter the data.')?>';
	
	
	$('#export_filter_ps_customer_id').change(function() {
		resetOptions('export_filter_ps_workplace_id');
		$('#export_filter_ps_workplace_id').select2('val','');
		$("#export_filter_ps_workplace_id").attr('disabled', 'disabled');
		
	if ($(this).val() > 0) {

		$("#export_filter_ps_workplace_id").attr('disabled', 'disabled');
		
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

	    	$('#export_filter_ps_workplace_id').select2('val','');

			$("#export_filter_ps_workplace_id").html(msg);

			$("#export_filter_ps_workplace_id").attr('disabled', null);

	    });
	}		
});
 

});

</script>

<section id="widget-grid">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

			<div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-grid="false" data-widget-collapsed="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false" data-widget-togglebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-table"></i></span>
					<h2><?php echo __('Download template menus', array(), 'messages') ?></h2>
				</header>
				<div>
					<div class="widget-body">
						<div id="dt_basic_filter"
							class="sf_admin_filter dataTables_filter">
	<?php if ($formFilter->hasGlobalErrors()): ?>
    <?php echo $formFilter->renderGlobalErrors()?>
    <?php endif; ?>
		<form id="ps-filter" class="form-inline pull-left"
								action="<?php echo url_for('ps_menus_imports_collection', array('action' => 'exportTemp')) ?>"
								method="post">
								<div class="pull-left">
    	 	<?php echo $formFilter->renderHiddenFields(true) ?>
    	 	
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
											<button type="submit" rel="tooltip" data-placement="bottom"
												data-original-title="<?php echo __('Download template');?>"
												class="btn btn-sm btn-default btn-success btn-filter-search btn-psadmin">
												<i class="fa-fw fa fa-cloud-download"></i><?php echo __('Download template')?>
                    </button>
										</label>
									</div>

								</div>
							</form>
						</div>
					</div>

				</div>
			</div>

		</article>

	</div>
</section>
