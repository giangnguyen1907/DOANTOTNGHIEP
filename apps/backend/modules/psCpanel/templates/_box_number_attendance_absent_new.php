<ul class="list-inline">
	<li class="text-center" style="width: 45%"><?php echo $absent;?></li>
	<li class="text-center" style="width: 49%">
		<div class="easy-pie-chart txt-color-red easyPieChart font-xs" data-pie-size="45" data-line-width="1" data-size="45" data-percent="<?php echo round($percentage_absent,2);?>"><?php echo round($percentage_absent,2);?>%</div>
	</li>
</ul>