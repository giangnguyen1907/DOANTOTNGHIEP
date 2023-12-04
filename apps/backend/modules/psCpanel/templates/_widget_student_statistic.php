<div class="jarviswidget" id="wid-id-3" data-widget-editbutton="false"
	data-widget-colorbutton="false" data-widget-editbutton="false"
	data-widget-togglebutton="false" data-widget-deletebutton="false"
	data-widget-fullscreenbutton="false" data-widget-custombutton="false"
	data-widget-collapsed="false" data-widget-sortable="false">
	<header role="heading">
		<span class="widget-icon"> <i
			class="glyphicon glyphicon-stats faa-vertical animated"></i>
		</span>
		<h2> <?php echo __('Student statistics chart') ?></h2>
	</header>
	<div>
		<div class="widget-body no-padding">
			<canvas id="barChart" height="150"></canvas>
		</div>
	</div>
</div>
<script>
	var label1 = '<?php echo __('School test')?>', label2 = '<?php echo __('Official')?>', label3 = '<?php echo __('Not class')?>';
	
	<?php
	$arr_label = array ();
	$labels = '';

	$arr_hocthu = array ();
	$arr_chinhthuc = array ();

	foreach ( $student_statistic as $key => $_student_statistic ) {
		array_push ( $arr_label, "'" . $key . "'" );
		array_push ( $arr_hocthu, $_student_statistic ['hoc_thu'] );
		array_push ( $arr_chinhthuc, $_student_statistic ['chinh_thuc'] );
	}

	$labels = implode ( ",", $arr_label );
	$data_hocthu = implode ( ",", $arr_hocthu );
	$data_chinhthuc = implode ( ",", $arr_chinhthuc );

	$max_hocthu = max ( $arr_hocthu );
	$max_chinhthuc = max ( $arr_chinhthuc );

	$max_y = ($max_hocthu > $max_chinhthuc) ? $max_hocthu : $max_chinhthuc;

	if ($max_y < 20)
		$max_y = 20;
	elseif ($max_y < 30)
		$max_y = 30;
	elseif ($max_y > 30 && $max_y < 40)
		$max_y = 40;

	$max_y = ceil ( $max_y + 5 );
	?>

	var str_labels = [<?php echo $labels;?>];
	var data_hocthu = [<?php echo $data_hocthu;?>];
	var data_chinhthuc = '<?php echo $data_chinhthuc;?>';

	var barChartData;

	var max_y = <?php echo $max_y?>;

	barChartData = {
		labels : str_labels,
		datasets : [
				{
					label : label1,
					backgroundColor : "#9cb4c5",
					data : [<?php echo $data_hocthu;?>]
				},
				{
					label : label2,
					backgroundColor : "#739e73",
					data : [<?php echo $data_chinhthuc;?>]
				}]

	};
	
</script>
