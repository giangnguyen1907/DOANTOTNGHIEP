<?php
$arr_step = array ();

$arr_step [1] = __ ( 'Choose month' ) . '' . $sf_user->hasCredential ( 'PS_FEE_REPORT_FILTER_SCHOOL' ) ? __ ( 'Choose school and workplace' ) : __ ( 'Choose workplace' );

// $arr_step[2] = $sf_user->hasCredential('PS_FEE_REPORT_FILTER_SCHOOL') ? __('Choose school and workplace') : __('Choose workplace');

$arr_step [2] = __ ( 'Choose class or skip' );

$arr_step [3] = __ ( 'Add new receivable or skip' );

$arr_step [4] = __ ( 'Confirm process' );

$arr_step [5] = __ ( 'Process fee report' );
?>
<style>
.fuelux .wizard ul li {
	font-size: 12px;
	color: #333;
}
</style>
<div class="wizard">
	<ul class="steps">
		<?php foreach ($arr_step as $i => $step):?>
			<?php if ($current_step == $i):?>
			<li data-target="#step<?php echo ($i);?>" class="active"><span
			class="badge badge-info"><?php echo __('Step').' '.($i);?></span><?php echo $step;?><span
			class="chevron"></span></li>
			<?php else:?>
			<li data-target="#step<?php echo ($i);?>"><span class="badge"><?php echo __('Step').' '.($i);?></span><?php echo $step;?><span
			class="chevron"></span></li>
			<?php endif;?>
		<?php endforeach; ?>
	</ul>
</div>