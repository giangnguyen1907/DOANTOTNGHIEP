<div class="jarviswidget" id="wid-id-4" data-widget-editbutton="false"
	data-widget-colorbutton="false" data-widget-editbutton="false"
	data-widget-togglebutton="false" data-widget-deletebutton="false"
	data-widget-fullscreenbutton="false" data-widget-custombutton="false"
	data-widget-collapsed="false" data-widget-sortable="false">
	<header role="heading">
		<span class="widget-icon"> <i class="glyphicon glyphicon-stats"></i>
		</span>
		<h2><?php echo __('Relatives') ?></h2>
	</header>
	<div>
		<div class="widget-body no-padding padding-10">
			<div class="table-responsive no-margin custom-scroll"
				style="height: 150px; overflow-y: scroll;">
				<table class="table table-striped table-hover table-condensed">
					<tbody>
				<?php
				$index = 1;
				foreach ( $list_users as $user ) :
					?>
				
				<tr>
							<td>
						<?php echo $index.': '.$user->getUserType().'. '?> <?php echo $user->getName();?> - <?php echo $user->getUsername();?>
					</td>
						</tr>
				<?php
					$index ++;
					?>
				<?php endforeach ?>
				</tbody>
				</table>
			</div>
		</div>
	</div>
</div>