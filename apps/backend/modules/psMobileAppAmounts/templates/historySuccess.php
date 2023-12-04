<?php use_helper('I18N', 'Date')?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true"
	>×</button>
	<h4 class="modal-title" id="myModalLabel"><?php echo __('App amount history') ?></h4>
</div>
<div class="modal-body">
<?php if($histories->count()): ?>					
	<table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
		<tr class="info">
			<th style="width: 250px;">
				<?php echo __ ( 'Amount' ); ?>
			</th>
			<th style="width: 250px;">
				<?php echo __ ( 'Pay created at' ); ?>
			</th>
			<th style="width: 250px;">
				<?php echo __ ( 'Pay type' ); ?>
			</th>
			<th style="width: 250px;">
				<?php echo __ ( 'Description' ); ?>
			</th>		
		</tr>

		<?php foreach($histories as $history): ?>
			<tr>
				<td>
					<?php echo $history->getAmount() ?>
				</td>

				<td>
					<?php echo $history->getPayAt() ?>
				</td>

				<td>
					<?php echo $history->getPayType() ?>
				</td>

				<td>
					<?php echo $history->getDescription() ?>
				</td>
			</tr>
		<?php endforeach ?>
	</table>
<?php else: ?>
	<i class="fa fa-fw fa-info"></i>
	<span><?php echo __("No pay history") ?></span>
<?php endif ?>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal" 
		><i class="fa-fw fa fa-ban"></i>&nbsp;<?php echo __('Close') ?></button>				
</div>


