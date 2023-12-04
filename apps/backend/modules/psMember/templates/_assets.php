<?php use_helper('I18N', 'Number') ?>
<?php $app_upload_max_size = (int)sfConfig::get('app_upload_max_size');?>
<style>
#ps-filter .has-error {	z-index: 9999;color: #b94a48;}
.datepicker {z-index: 1051 !important;}
.ui-datepicker {z-index: 1051 !important;}
.select2-container {width: 100% !important;	padding: 0;}
.country-list {	z-index: 1051 !important;}
#widget-grid{overflow: hidden;}
</style>
<script type="text/javascript">
$(document).ready(function() {
    $('.btn-delete-department').click(function() {
    	var item_id = $(this).attr('data-item');		
    	$('#ps-form-delete-member-department').attr('action', '<?php echo url_for('@ps_member_departments')?>/' + item_id);
    });
    
    $('.btn-delete-member-salary').click(function() {
    	var item_id = $(this).attr('data-item');		
    	$('#ps-form-delete-member-salary').attr('action', '<?php echo url_for('@ps_member_salary')?>/' + item_id);
    });
    
    $('.btn-delete-member-allowance').click(function() {
    	var item_id = $(this).attr('data-item');		
    	$('#ps-form-delete-member-allowance').attr('action', '<?php echo url_for('@ps_member_allowance')?>/' + item_id);
    });	 

    $('.btn-delete-member-working-time').click(function() {
    	var item_id = $(this).attr('data-item');		
    	$('#ps-form-delete-member-working-time').attr('action', '<?php echo url_for('@ps_member_working_time')?>/' + item_id);
    });
    
    $('#remoteModal').on('hide.bs.modal', function(e) {
    	$(this).removeData('bs.modal');
    });
      
}); 
</script>
<script type="text/javascript">
var msg_file_invalid 	= '<?php

echo __ ( 'The image file must be in the format: jpg, png, gif. File size less than %value%KB.', array (
		'%value%' => $app_upload_max_size ) )?>';

var PsMaxSizeFile = '<?php echo $app_upload_max_size;?>';
var URL_CHECKEMAIL = '<?php echo url_for('@hr_checkemail')?>';
var URL_CHECK_IDENTITYCARD = '<?php echo url_for('@hr_checkidentitycard')?>';


var msg_mobile_invalid 	= '<?php echo __('Mobile is not a valid')?>';
var msg_identity_card_invalid 	= '<?php echo __('The identity card can only consist of alphabetical, number.')?>';
var msg_identity_card_lenght 	= '<?php echo __('The identity card must be more than %s and less than %s characters long.')?>';
var msg_identity_card_exist 	= '<?php echo __('The identity card already exits.')?>';
var msg_email_exist 	= '<?php echo __('Email address already exist.')?>';

<?php if (myUser::credentialPsCustomers ( 'PS_HR_HR_FILTER_SCHOOL' )):?>
$(document).ready(function() {
	
	$('.sf_admin_list_td_member_code a, .sf_admin_list_td_first_name a, .sf_admin_list_td_last_name a').on('contextmenu', function () {
		return false;
	});

	// Lay co so dao tao theo nha truong
	$('#ps_member_ps_customer_id').change(function() {
		
		$("#ps_member_ps_workplace_id").attr('disabled', 'disabled');
		
		$.ajax({
	        url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' + $(this).val(),
	        type: 'POST',
	        data: 'f=<?php echo md5(time().time().time().time())?>&psc_id=' + $(this).val(),
	        success: function(data) {
	        	$("#ps_member_ps_workplace_id").val(null).trigger("change");
	        	$('#ps_member_ps_workplace_id').html(data);
				$("#ps_member_ps_workplace_id").attr('disabled', null);
	        }
		});		 	
	});
	
	$('#ps_member_filters_ps_customer_id').change(function() {
		
		$("#ps_member_filters_ps_workplace_id").attr('disabled', 'disabled');
		
		$.ajax({
	        url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' + $(this).val(),
	        type: 'POST',
	        data: 'f=<?php echo md5(time().time().time().time())?>&psc_id=' + $(this).val(),
	        success: function(data) {
	        	$("#ps_member_filters_ps_workplace_id").val(null).trigger("change");
	        	$('#ps_member_filters_ps_workplace_id').html(data);
				$("#ps_member_filters_ps_workplace_id").attr('disabled', null);
	        }
		});		 	
	});

	
});

<?php endif;?>
</script>

<?php include_partial('global/include/_box_modal') ?>
<?php include_partial('psMember/box_modal_confirm_remover_member_salary');?>
<?php include_partial('psMember/box_modal_confirm_remover_member_allowance');?>
<?php include_partial('psMember/box_modal_confirm_remover_member_working_time');?>