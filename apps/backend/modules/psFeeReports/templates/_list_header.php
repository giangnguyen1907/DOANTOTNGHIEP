<?php
$arr_step = array ();

$arr_step [0] = __ ( 'Choose school' );

$arr_step [1] = __ ( 'Choose workplace' );

$arr_step [2] = __ ( 'Choose month' );

$arr_step [3] = __ ( 'Choose class' );

?>
<div class="wizard">
	<ul class="steps">
		<?php foreach ($arr_step as $i => $step):?>
			<?php if (/*$ps_customer_id > 0 && */$i== 0 || ($ps_workplace_id > 0 )):?>
			<li data-target="#step<?php echo ($i+1);?>" class="active"><span
			class="badge badge-info"><?php echo ($i+1);?></span><?php echo $step;?><span
			class="chevron"></span></li>
			<?php else:?>
			<li data-target="#step<?php echo ($i+1);?>"><span class="badge"><?php echo ($i+1);?></span><?php echo $step;?><span
			class="chevron"></span></li>
			<?php endif;?>
		<?php endforeach; ?>
	</ul>
	<!--  <div class="actions">
		<button type="button" class="btn btn-sm btn-primary btn-prev"><i class="fa fa-arrow-left"></i>Prev</button>
		<button type="button" class="btn btn-sm btn-success btn-next" data-last="Finish">Next <i class="fa fa-arrow-right"></i>
		</button>
	</div>-->
</div>