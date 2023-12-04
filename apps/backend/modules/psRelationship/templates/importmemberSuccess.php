<?php use_helper('I18N', 'Date')?>
<?php include_partial('psRelationship/assets')?>
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

	$('#import_member_ps_customer_id').change(function() {
		
		if ($(this).val() > 0) {

			$("#import_member_ps_workplace_id").attr('disabled', 'disabled');
			
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

		    	$('#import_member_ps_workplace_id').select2('val','');

				$("#import_member_ps_workplace_id").html(msg);

				$("#import_member_ps_workplace_id").attr('disabled', null);

		    });

			}		
		});

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
		'import_member[ps_file]' : {
			validators : {
				file : {
					extension : 'xls,xlsx',
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

});

</script>
<section id="widget-grid">
	<div class="row">
	<?php include_partial('psRelationship/flashes3')?>
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

			<div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-grid="false" data-widget-collapsed="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false" data-widget-togglebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-table"></i></span>
					<h2><?php echo __('Import member', array(), 'messages') ?></h2>
					<?php 
						$path_file1 = '/media-template/import/importMember.xlsx';
						$path_file2 = '/media-template/import/importMember2.xlsx';
					?>
					<div class="widget-toolbar">
						<a class="btn btn-success" href="<?php echo $path_file1 ?>"><i
							class="fa fa-cloud-download"></i><?php echo __('Download template 1') ?></a>
						<a class="btn btn-xs btn-success" href="<?php echo $path_file2?>"><i
						class="fa fa-cloud-download"></i><?php echo __('Download template 2') ?></a>
					</div>
				</header>
				
				<div id="sf_admin_content">

					<div class="sf_admin_form widget-body">
						<?php if ($formFilter->hasGlobalErrors()): ?>
					    <?php echo $formFilter->renderGlobalErrors()?>
					    <?php endif; ?>
							<form id="ps-form"
							class="form-horizontal fv-form fv-form-bootstrap"
							action="<?php echo url_for('@ps_member_import_save') ?>"
							method="post" enctype="multipart/form-data">
							<fieldset>
					    	 	<?php echo $formFilter->renderHiddenFields(true) ?>
					    	 	<div class='col-md-6 hidden'>
									<div class="sf_admin_form_row sf_admin_foreignkey"
										style="margin-top: 25px">
										<label class="col-md-3 control-label">
					            		 	<?php echo __('School year', array(), 'messages') ?>
					            		 </label>
										<div class="col-md-9">
					            		 	<?php echo $formFilter['ps_school_year_id']->render() ?>
					            		 	<?php echo $formFilter['ps_school_year_id']->renderError() ?>
					            		 </div>
									</div>
								</div>
								<?php if (myUser::credentialPsCustomers ( 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' )) {?>
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
								<?php }?>
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
									<a
										class="btn btn-default btn-success bg-color-green btn-sm btn-psadmin pull-left"
										href="<?php echo url_for('@ps_member') ?>"><i
										class="fa-fw fa fa-list-ul"
										title="<?php echo __('Roll Back')?>"></i><?php echo __('Roll Back')?></a>
									<button type="submit"
										class="btn btn-default btn-success btn-sm btn-attendance">
										<i class="fa-fw fa fa-cloud-upload" aria-hidden="true"
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
