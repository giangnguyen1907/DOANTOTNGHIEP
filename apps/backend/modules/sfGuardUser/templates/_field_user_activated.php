<?php //include_partial('sfGuardUser/assets') ?>
<?php if(myUser::credentialPsCustomers('PS_SYSTEM_USER_EDIT')): ?>
	<?php $state = PreSchool::loadPsUserActivated(); ?>
<div class="btn-group" rel="tooltip" data-placement="top"
	data-original-title="<?php echo __($state[$sf_guard_user->getIsActive()])?>">
	<a class="btn btn-default btn-xs" href="javascript:void(0);"><?php echo get_partial('global/field_custom/_list_field_user_activated', array('value' => $sf_guard_user->getIsActive()));?></a>
	<a class="btn btn-default btn-xs dropdown-toggle"
		data-toggle="dropdown" href="javascript:void(0);"
		aria-expanded="false"><span class="caret"></span></a>
	<ul class="dropdown-menu">
		<li><a href="javascript:void(0);" class="btn-item-activated"
			item="<?php echo $sf_guard_user->getId() ?>"
			data-check="<?php echo PreSchool::CUSTOMER_ACTIVATED ?>"><?php echo __('Active') ?></a></li>
		<li><a href="javascript:void(0);" class="btn-item-deactivated"
			item="<?php echo $sf_guard_user->getId() ?>"
			data-check="<?php echo PreSchool::CUSTOMER_NOT_ACTIVATED ?>"><?php echo __('Not active') ?></a></li>
		<li><a href="javascript:void(0);" class="btn-item-lock"
			item="<?php echo $sf_guard_user->getId() ?>"
			data-check="<?php echo PreSchool::CUSTOMER_LOCK ?>"><?php echo __('Lock') ?></a></li>
	</ul>
</div>
<?php else: ?>
<?php
	echo get_partial ( 'global/field_custom/_list_field_user_activated', array (
			'value' => $sf_guard_user->getIsActive () ) );
	?>
<?php endif;?>
<script>
$(document).ready(function(){

	$('.btn-group').tooltip({trigger: "hover"}); 

	    //Load ajax User state
	$(".btn-item-activated, .btn-item-deactivated, .btn-item-lock").click(function() {
		var id =  $(this).attr('item');
		var state = $(this).attr('data-check');
		
		$.ajax({
	        url: '<?php echo url_for('@sf_guard_user_activated') ?>',
	        type: 'POST',
	        data: 'id=' + id + '&state=' + state,
	        success: function(data) {
	        	$('#field-user-' + id).html(data);
	        	return;
	        }
		});
	});
});
</script>