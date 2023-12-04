<table id="dt_basic"
	class="table table-striped table-bordered table-hover" width="100%">
	<thead>
		<th> <?php echo __('Amount') ?></th>
		<th> <?php echo __('Pay created at') ?></th>
		<th> <?php echo __('Description') ?> </th>
		<th> <?php echo __('Updated at') ?></th>
	</thead>
	<tbody>
		<?php foreach ($list_history as $history): ?>
			<tr>
			<td>
					<?php echo format_currency($history->getAmount()) ?>
				</td>
			<td>
					<?php echo date_format(date_create($history->getPayAt()), "d/m/Y") ?>
				</td>
			<td>
					<?php echo $history->getDescription() ?>
				</td>
			<td>
					<?php echo date_format(date_create($history->getUpdatedAt()), "d/m/Y H:i:s") ?>
				</td>
		</tr>
		<?php endforeach ?>
	</tbody>
</table>