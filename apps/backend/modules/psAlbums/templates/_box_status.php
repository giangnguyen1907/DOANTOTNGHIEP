<script type="text/javascript">
$(document).ready(function(){
	//Change status state
	$('.btn-album-item-activated').click(function() {

		var alb_id = $(this).attr('data-value');
		var alb_state = $(this).attr('data-check');
		//alert(alb_state);
		$.ajax({
	        url: '<?php echo url_for('@ps_album_item_activated') ?>',
	        type: 'POST',
	        data: 'id=' + alb_id +"&state=" + alb_state,
	        success: function(data) {
	        	$('#box-status-' + alb_id).html(data);
	        }
		});
	    
  	});

	//Change status state
	$('.btn-album-item-deactivated').click(function() {

		var alb_id = $(this).attr('data-value');
		var alb_state = $(this).attr('data-check');
		//alert(alb_state);
		$.ajax({
	        url: '<?php echo url_for('@ps_album_item_activated') ?>',
	        type: 'POST',
	        data: 'id=' + alb_id +"&state=" + alb_state,
	        success: function(data) {
	        	$('#box-status-' + alb_id).html(data);
	        }
		});
	    
  	});

	//Change status state
	$('.btn-album-item-lock').click(function() {

		var alb_id = $(this).attr('data-value');
		var alb_state = $(this).attr('data-check');
		//alert(alb_state);
		$.ajax({
	        url: '<?php echo url_for('@ps_album_item_activated') ?>',
	        type: 'POST',
	        data: 'id=' + alb_id +"&state=" + alb_state,
	        success: function(data) {
	        	$('#box-status-' + alb_id).html(data);
	        }
		});
	    
  	});
});
</script>
<?php
$status = $a->getIsActivated ();
// $state = PreSchool::loadPsActivity();
if ($status == PreSchool::ACTIVE) {
	$att = 'label label-success';
	$state = __ ( 'Activity' );
} elseif ($status == PreSchool::NOT_ACTIVE) {
	$att = 'label label-danger';
	$state = __ ( 'Inactive' );
} else {
	$att = 'label label-warning';
	$state = __ ( 'Lock' );
}
?>
<div class="btn-group">
	<a class="btn btn-default" href="javascript:void(0);"><?php echo __('Actions') ?></a>
	<a class="btn btn-default dropdown-toggle" data-toggle="dropdown"
		href="javascript:void(0);" aria-expanded="false"><span class="caret"></span></a>
	<ul class="dropdown-menu">
		<li><a href="javascript:void(0);" class="btn-album-item-activated"
			id="item-activated-<?php echo $a->getId() ?>"
			data-value="<?php echo $a->getId() ?>"
			data-check="<?php echo PreSchool::ACTIVE ?>"><?php echo __('Active') ?></a></li>
		<li><a href="javascript:void(0);" class="btn-album-item-deactivated"
			id="item-deactivated-<?php echo $a->getId() ?>"
			data-value="<?php echo $a->getId() ?>"
			data-check="<?php echo PreSchool::NOT_ACTIVE ?>"><?php echo __('Inactive') ?></a></li>
		<li><a href="javascript:void(0);" class="btn-album-item-lock"
			id="item-lock-<?php echo $a->getId() ?>"
			data-value="<?php echo $a->getId() ?>"
			data-check="<?php echo PreSchool::LOCK ?>"><?php echo __('Lock') ?></a></li>
	</ul>
</div>
<div>
	<p class="<?php echo $att ?>"><?php echo $state ?></p>
</div>
