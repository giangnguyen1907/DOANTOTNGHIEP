<script type="text/javascript">
$(document).ready(function(){
	
	$(".btn-articles-item-activated, .btn-articles-item-deactivated, .btn-articles-item-lock").click(function() {
		
		var alb_id = $(this).attr('data-value');
		var alb_state = $(this).attr('data-check');
		$('#ic-loading-' + alb_id).show();
		//alert(alb_state);
		$.ajax({
	        url: '<?php echo url_for('@ps_off_school_activated') ?>',
	        type: 'POST',
	        data: 'id=' + alb_id +"&state=" + alb_state,
	        success: function(data) {
	        	$('#ic-loading-' + alb_id).hide();
	        	$('#status-' + alb_id).html(data);
	        }
		});
		
	});

});
</script>

<?php
$off_school_id = $ps_off_school->getId ();
$status = $ps_off_school->getIsActivated ();

if ($status == PreSchool::PUBLISH) {
	$att = 'btn-success';
	$state = __ ( 'Valid' );
} elseif ($status == PreSchool::NOT_PUBLISH) {
	$att = 'btn-warning';
	$state = __ ( 'Inactive' );
} else {
	$att = 'btn-danger';
	$state = __ ( 'Not Valid' );
}
?>
<div id="ic-loading-<?php echo $off_school_id;?>" style="display: none;">
	<i class="fa fa-spinner fa-2x fa-spin text-success"
		style="padding: 3px;"></i><?php echo __('Loading...')?>
</div>
<?php if(myUser::credentialPsCustomers('PS_STUDENT_OFF_SCHOOL_EDIT')): // Neu co quyen sua ?>
<div class="btn-group">
	<a class="btn btn-default <?php echo $att;?>" href="javascript:void(0);"
		style="padding: 3px 9px;"><?php echo $state ?></a> <a
		class="btn btn-default dropdown-toggle" data-toggle="dropdown"
		href="javascript:void(0);" aria-expanded="false"
		style="padding: 3px 9px;"><span class="caret"></span></a>
	<ul class="dropdown-menu">
		<li><a href="javascript:void(0);" class="btn-articles-item-activated"
			data-value="<?php echo $off_school_id ?>"
			data-check="<?php echo PreSchool::PUBLISH ?>"><?php echo __('Valid') ?></a></li>
		<li><a href="javascript:void(0);"
			class="btn-articles-item-deactivated"
			data-value="<?php echo $off_school_id ?>"
			data-check="<?php echo PreSchool::NOT_PUBLISH ?>"><?php echo __('Inactive') ?></a></li>
		<li><a href="javascript:void(0);" class="btn-articles-item-lock"
			data-value="<?php echo $off_school_id ?>"
			data-check="<?php echo PreSchool::LOCK ?>"><?php echo __('Not Valid') ?></a></li>
	</ul>
</div>
<?php else :?>
<div class="btn-group">
	<a class="btn btn-default <?php echo $att;?>" href="javascript:void(0);" style="padding: 3px 9px;"><?php echo $state ?></a>
</div>
<?php endif;?>

