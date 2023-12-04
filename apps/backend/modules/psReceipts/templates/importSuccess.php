<?php use_helper('I18N', 'Date')?>
<?php include_partial('psReceipts/assets')?>
<style>
.float-right {
	float: right;
	margin-right: 20px;
}

.float-right a {
	margin-top: -10px;
}
</style>
<?php
$upload_max_size = 2000;
?>
<script type="text/javascript">
$(document).ready(function() {

var msg_name_file_invalid 	= '<?php

echo __ ( 'The excel file must be in the format: xls, xlsx. File size less than %value%KB.', array (
		'%value%' => $upload_max_size ) )?>';
var PsMaxSizeFile = '<?php echo $upload_max_size;?>';

$('#ps-form').formValidation({
	framework : 'bootstrap',
	excluded : [ ':disabled' ],
	addOns : {
		i18n : {}
	},
	errorElement : "div",
	errorClass : "help-block with-errors",
	message : {
		vi_VN : 'This value is not valid'
	},
	fields : {
		'import_filter[ps_file]' : {
			validators : {
				file : {
					extension : 'xls,xlsx',
					//type : 'application/vnd.ms-excel,application/msexcel,application/x-msexcel,application/x-ms-excel,application/x-excel,application/xls,application/x-xls,application/x-dos_ms_excel',
					maxSize : PsMaxSizeFile * 1024,
					message : {
						en_US : msg_name_file_invalid,
						vi_VN : msg_name_file_invalid
					}
				}
			}
		}
	}
});
$('#ps-form').formValidation('setLocale', PS_CULTURE);


//end
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
					<h2><?php echo __('Fee report import', array(), 'messages') ?></h2>
					<?php //$path_file = '/uploads'.'/'.'import_data'.'/'.form.'/'.'phieuthanhtoan.xlsx'; ?>
					<?php $path_file = '/media-template/import/phieuthanhtoan.xlsx'; ?>
        			<div class="widget-toolbar">
						<a class="btn btn-success" href="<?php echo $path_file ?>"><i
							class="fa fa-cloud-download"></i><?php echo __('Download file') ?></a>
					</div>
				</header>
				<div>
					<div class="widget-body no-padding">
						<div id="datatable_fixed_column_wrapper"
							class="dataTables_wrapper form-inline no-footer no-padding">
		<?php if ($formFilter->hasGlobalErrors()): ?>
      <?php echo $formFilter->renderGlobalErrors() ?>
    <?php endif; ?>
		<form id="ps-form" class="form-horizontal fv-form fv-form-bootstrap"
								action="<?php echo url_for('@ps_receipts_import_save') ?>"
								method="post" enctype="multipart/form-data">
								<div class="sf_fieldset_none">
    	 	<?php echo $formFilter->renderHiddenFields(true) ?>
    	 	
    		<div class='col-md-4'>
										<div class="sf_admin_form_row sf_admin_foreignkey"
											style="margin-top: 25px">
											<div class="col-md-12">
            		 	<?php echo $formFilter['ps_customer_id']->render() ?>
            		 	<?php echo $formFilter['ps_customer_id']->renderError() ?>
            		 </div>
										</div>
									</div>

									<div class='col-md-4'>
										<div class="sf_admin_form_row sf_admin_foreignkey"
											style="margin-top: 25px">

											<div class="col-md-12">
        		  		<?php echo $formFilter['ps_file']->render() ?>
        		  		<?php echo $formFilter['ps_file']->renderError() ?>
            		</div>
										</div>
									</div>

									<div class='col-md-4'>
										<div class="sf_admin_form_row sf_admin_foreignkey"
											style="margin-top: 25px">
											<button type="submit"
												class="btn btn-default btn-success btn-sm btn-attendance">
												<i class="fa-fw fa fa-cloud-upload" aria-hidden="true"
													title="<?php echo __('Upload');?>"></i><?php echo __('Upload', array(), 'messages') ?>
                    </button>
										</div>
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


<section id="widget-grid">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	
	<?php include_partial('psReceipts/flashes2')?>
	
	</article>
	</div>
</section>