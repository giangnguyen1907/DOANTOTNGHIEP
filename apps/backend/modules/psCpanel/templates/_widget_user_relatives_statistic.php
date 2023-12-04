<div class="jarviswidget" id="wid-id-4" data-widget-editbutton="false"
	data-widget-colorbutton="false" data-widget-editbutton="false"
	data-widget-togglebutton="false" data-widget-deletebutton="false"
	data-widget-fullscreenbutton="false" data-widget-custombutton="false"
	data-widget-collapsed="false" data-widget-sortable="false">
	<header role="heading">
		<span class="widget-icon"> <i
			class="glyphicon glyphicon-stats faa-horizontal animated"></i>
		</span>
		<h2> <?php echo __('Relative statistics') ?></h2>
	</header>
	
	<div>
		<div class="widget-body no-padding">
			<canvas id="barChartUserRelative" height="150"></canvas>
		</div>
	</div>
</div>
<script>
	var label1 = '<?php echo __('Granted').': '.$number_users?>', label2 = '<?php echo __('Activated app').': '.$number_users_active?>', label3 = '<?php echo __('Logging').': '.$number_users_online?>';
	
	<?php
	$labels = __('Granted').','.__('Activated app').','.__('Logging');
	?>

	var str_labels = ['<?php echo $number_users?>,<?php echo __('Activated app')?>, <?php echo __('Logging')?>'];

	var barChartUserRelative;
	var max_y = <?php echo $number_users?>;

	barChartUserRelative = {
		labels : [''],
		datasets : [
				{
					label : '<?php echo __('Granted')?>',
					backgroundColor : "#00c0ef",
					data : [<?php echo $number_users;?>]
				},
				{
					label : '<?php echo __('Activated app')?>',
					backgroundColor : "#8142FF",
					data : [<?php echo $number_users_active;?>]
				},
				{
					label : '<?php echo __('Logging')?>',
					backgroundColor : "#00a65a",
					data : [<?php echo $number_users_online;?>]
				}
				]

	};
	
</script>