<?php if($pager->getNbResults()): ?>
<div class="custom-scroll table-responsive"
	style="height: auto; max-height: 400px; overflow-y: scroll;">

	<div id="list-history">
		<?php include_partial('psMobileAppAmounts/table_history', array('list_history' => $list_history, 'app_amount' => $app_amount)) ?>
	</div>
<?php else: ?>
	<i class="fa fa-fw fa-info"></i> <span><?php echo __("No pay history") ?></span>
<?php endif; ?>

<script type="text/javascript">
$(document).ready(function(){
	$('.page').click(function(){
		$('#list-history').html('');

		var $this = $(this);
		var page = $(this).attr('data-page');
		var user_id = '<?php echo $app_amount->getUserId() ?>';
		$('#page-history li.active').removeClass('active');
		$(this).parent('li').addClass('active');
		// if(! $this.parent('li').hasClass('active')){
		// }

		$.ajax({
			url: '<?php echo url_for('@ps_mobile_app_amounts_list_history?user_id=') ?>'+user_id,
			type: 'get',
			data: 'page='+page,
			success: function(data){
				$('#list-history').html('');
				$('#list-history').html(data);
			}
		});
	});
});
</script>