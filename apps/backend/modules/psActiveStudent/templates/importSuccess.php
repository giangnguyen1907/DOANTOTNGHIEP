<?php use_helper('I18N', 'Date')?>
<?php include_partial('psActiveStudent/assets')?>
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
	
	//import
	$('#import_filter_ps_customer_id').change(function() {

		$('#import_filter_ps_workplace_id').select2('val','');
		$("#import_filter_ps_workplace_id").attr('disabled', 'disabled');

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

	    	$('#import_filter_ps_workplace_id').select2('val','');
	    	$("#import_filter_ps_workplace_id").html(msg);
	    	$("#import_filter_ps_workplace_id").attr('disabled', null);
	    });
	      
	});

});
</script>



<section id="widget-grid">
	<div class="row">
		<?php include_partial('psActiveStudent/flashes')?>
		
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-grid="false" data-widget-collapsed="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false" data-widget-togglebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-table"></i></span>
					<h2><?php echo __('Feature branch import', array(), 'messages') ?></h2>
					<?php $path_file = '/media-template/import/lichhoatdongnew.xlsx'; ?>
					<div class="widget-toolbar">
						<a class="btn btn-success" href="<?php echo $path_file ?>"><i
							class="fa fa-download"></i><?php echo __('Tải file biểu mẫu') ?></a>
					</div>
				</header>
				<div id="sf_admin_content">

					<div class="sf_admin_form widget-body">
						<?php if ($formFilter->hasGlobalErrors()): ?>
					    <?php echo $formFilter->renderGlobalErrors()?>
					    <?php endif; ?>
							<form id="ps-form"
							class="form-horizontal fv-form fv-form-bootstrap"
							action="<?php echo url_for('@ps_active_student_import') ?>"
							method="post" enctype="multipart/form-data">
							<fieldset>
            	 	<?php echo $formFilter->renderHiddenFields(true) ?>
                  <?php if ($sf_user->hasCredential(array('PS_SYSTEM_FEATURE_BRANCH_FILTER_SCHOOL'))): ?>
                   <div class='col-md-6'>
									<div class="sf_admin_form_row sf_admin_foreignkey"
										style="margin-top: 25px">
										<label class="col-md-3 control-label">
                		 	<?php echo __('Select customer', array(), 'messages') ?>
                		</label>
										<div class="col-md-9">
                		 	<?php echo $formFilter['ps_customer_id']->render() ?>
                		 	<?php echo $formFilter['ps_customer_id']->renderError() ?>
                		 </div>
									</div>
								</div>
              		<?php endif?>
              		<div class='col-md-6'>
									<div class="sf_admin_form_row sf_admin_foreignkey"
										style="margin-top: 25px">
										<label class="col-md-3 control-label">
	              		 	<?php echo __('Select workplace', array(), 'messages') ?>
	              		</label>
										<div class="col-md-9">
                		 	<?php echo $formFilter['ps_workplace_id']->render() ?>
                		 	<?php echo $formFilter['ps_workplace_id']->renderError() ?>
                		 </div>
									</div>
								</div>

								<div class='col-md-6'>
									<div class="sf_admin_form_row sf_admin_foreignkey"
										style="margin-top: 25px">
										<label class="col-md-3 control-label">
                		 		<?php echo __('Input file', array(), 'messages') ?>
                		  	</label>
										<div class="col-md-9">
              		  		<?php echo $formFilter['ps_file']->render() ?>
              		  		<?php echo $formFilter['ps_file']->renderError() ?>
                  		</div>
									</div>
								</div>
							</fieldset>

							<div class="form-actions">
								<div class="sf_admin_actions">
									<button type="submit"
										class="btn btn-default btn-success btn-sm btn-attendance">
										<i class="fa-fw fa fa-upload" aria-hidden="true"
											title="<?php echo __('Upload');?>"></i><?php echo __('Upload', array(), 'messages') ?>
                                            </button>
								</div>
							</div>
						</form>
					</div>
					<div id="sf_admin_footer" class="no-border no-padding"></div>
				</div>
			</div>
		</article>
	</div>
</section>

