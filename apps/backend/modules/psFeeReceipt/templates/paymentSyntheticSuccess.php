<?php use_helper('I18N', 'Date')?>
<?php include_partial('psFeeReceipt/assets')?>
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
	var msg_select_ps_month		    = '<?php echo __('Please select month filter the data.')?>';
	var msg_select_ps_schoolyear_id	= '<?php echo __('Please select school year to filter the data.')?>';
	
	$('#export_filter_ps_customer_id').change(function() {
		resetOptions('export_filter_ps_workplace_id');
		$('#export_filter_ps_workplace_id').select2('val','');
		$("#export_filter_ps_workplace_id").attr('disabled', 'disabled');
		
	if ($(this).val() > 0) {

		$("#export_filter_ps_workplace_id").attr('disabled', 'disabled');
		$("#export_filter_class_id").attr('disabled', 'disabled');
		
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

			$("#export_filter_class_id").attr('disabled', 'disabled');

	    });
	}		
});

$('#export_filter_date_at').datepicker({
	dateFormat : 'dd-mm-yy',
	maxDate : new Date(),
	prevText : '<i class="fa fa-chevron-left"></i>',
	nextText : '<i class="fa fa-chevron-right"></i>',
	changeMonth : true,
	changeYear : true,
});
	
$('#ps-filter').formValidation({
	framework : 'bootstrap',
	addOns : {
		i18n : {}
	},
	err : {
		container: '#errors'
	},
	message : {
		vi_VN : 'This value is not valid'
	},
	icon : {},
	fields : {
		"export_filter[ps_customer_id]": {
            validators: {
                notEmpty: {
                    message: {vi_VN: msg_select_ps_customer_id,
                    		  en_US: msg_select_ps_customer_id
                    }
                }
            }
        },
        
        "export_filter[ps_workplace_id]": {
            validators: {
                notEmpty: {
                    message: {vi_VN: msg_select_ps_workplace_id,
                    		  en_US: msg_select_ps_workplace_id
                    }
                }
            }
        },
        
	}
}).on('err.form.fv', function(e) {
	$('#messageModal').modal('show');
});
$('#ps-filter').formValidation('setLocale', PS_CULTURE);

//Xuất thống kê học sinh thanh toán trong ngày
$('.btn-export-payment').click(function() {
	if ($('#export_filter_ps_workplace_id').val() <= 0) {
		alert('<?php echo __('Select workplace')?>');
		return false;
	}
	$('#export_ps_customer_id').val($('#export_filter_ps_customer_id').val());
	$('#export_ps_workplace_id').val($('#export_filter_ps_workplace_id').val());
	$('#export_date_at').val($('#export_filter_date_at').val());
	
	$('#frm_export_01').submit();
	
	return true;
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
					<h2><?php echo __('Payment synthetic export', array(), 'messages') ?></h2>

				</header>
				<div>
					<div class="widget-body no-padding">
						<div class="dt-toolbar">
							<div class="col-xs-12 col-sm-12">
						<div id="dt_basic_filter"
							class="sf_admin_filter dataTables_filter">
							
						<?php if ($formFilter->hasGlobalErrors()): ?>
					    <?php echo $formFilter->renderGlobalErrors()?>
					    <?php endif; ?>
							<form id="ps-filter" class="form-inline pull-left"
								action="<?php echo url_for('ps_fee_receipt_collection', array('action' => 'paymentSynthetic')) ?>"
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
        		 	<?php echo $formFilter['date_at']->render() ?>
        		 	<?php echo $formFilter['date_at']->renderError() ?>
        		 </label>
									</div>

									<div class="form-group">
										<label>
					    				<?php echo $helper->linkToFilterSearch() ?>
					    			</label>
									</div>
									<!--  
									<div class="form-group">
										<label>
											<button type="submit" rel="tooltip" data-placement="bottom"
												data-original-title="<?php echo __('Download template');?>"
												class="btn btn-sm btn-default btn-success btn-filter-search btn-psadmin">
												<i class="fa-fw fa fa-cloud-download"></i><?php echo __('Export template')?>
                    						</button>
										</label>
									</div>
									-->
								</div>
							</form>
						</div>
						</div>
						</div>
						<?php if(count($list_student_payment) > 0) { ?>
						<div class="sf_admin_actions dt-toolbar-footer no-border-transparent">
				        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-left">
						</div>
						<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-right">					      
							<a class="btn btn-default btn-export-payment" href="javascript:void(0);" id="btn-export-payment"><i class="fa-fw fa fa-cloud-download"></i>&nbsp;<?php echo __('Export file')?></a>
						</div>
						</div>
						<?php }?>
						<div id="datatable_fixed_column_wrapper" class="dataTables_wrapper form-inline no-footer no-padding">
							<div class="custom-scroll table-responsive">
								<table id="dt_basic" class="table table-striped table-bordered table-hover no-footer no-padding" width="100%">
									<thead>
										<tr>
											<th class="text-center">STT</th>
											<th class="text-center"><?php echo __('Receipt no')?></th>
											<th class="text-center"><?php echo __('Student code')?></th>
											<th class="text-center"><?php echo __('Student')?></th>
											<th class="text-center"><?php echo __('Class')?></th>
											<th class="text-center"><?php echo __('Amount')?></th>
											<th class="text-center"><?php echo __('Relative payment')?></th>
											<th class="text-center"><?php echo __('Payment type')?></th>
											<th class="text-center"><?php echo __('Note')?></th>
										</tr>
									</thead>
									<tbody>
										<?php $tong_tien_da_thu = 0;?>
										<?php foreach ($list_student_payment as $key => $student_payment){?>
										<tr>
											<?php $tong_tien_da_thu = $tong_tien_da_thu + $student_payment->getCollectedAmount () ?>
											<td class="text-center"><?php echo $key+1 ?></td>
											<td class="text-center"><?php echo $student_payment->getReceiptNo()?></td>
											<td class="text-center"><?php echo $student_payment->getStudentCode ()?></td>
											<td><?php echo $student_payment->getStudentName ()?></td>
											<td class="text-center"><?php echo $student_payment->getMcName ()?></td>
											<td class="text-right"><?php echo PreNumber::number_format($student_payment->getCollectedAmount ())?></td>
											<td><?php echo $student_payment->getPaymentRelativeName ()?></td>
											<td><?php echo __(PreSchool::loadPsPaymentType()[$student_payment->getPaymentType ()])?></td>
											<td><?php echo $student_payment->getNote ()?></td>
										</tr>
										<?php }?>
										
										<?php if(count($list_student_payment) <= 0) { ?>
										<tr>
											<td colspan="9">
												<?php echo __('Not data student payment');?>
											</td>
										</tr>
										<?php }else{?>
										<tr>
											<td colspan="5" class="text-right">
												<b><?php echo __('Total');?></b>
											</td>
											<td class="text-right"><b><?php echo PreNumber::number_format($tong_tien_da_thu);?></b></td>
											<td colspan="3" class="text-left"> </td>
										</tr>
										<?php }?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					
				</div>
				
			</div>
		</article>
		<?php if(count($list_student_payment) > 0) { ?>
		<form id="frm_export_01" action="<?php echo url_for('@ps_fee_receipt_payment_synthetic_export_post') ?>">
			
			<input type="hidden" name="export_ps_customer_id" id="export_ps_customer_id">
			<input type="hidden" name="export_ps_workplace_id" id="export_ps_workplace_id">
			<input type="hidden" name="export_date_at" id="export_date_at">
					
			<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right">
	        	<a class="btn btn-default btn-export-payment" href="javascript:void(0);" id="btn-export-payment"><i class="fa-fw fa fa-cloud-download"></i>&nbsp;<?php echo __('Export file')?></a>
	        </article>
        </form>
        <?php } ?>
	</div>
</section>
