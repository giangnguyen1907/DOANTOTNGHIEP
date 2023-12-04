<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<div class="table-responsive custom-scroll"
		style="overflow-y: scroll; max-height: 300px;">
		<table id="dt" class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th class="text-center"><?php echo __('Time') ?></th>
					<th class="text-center"><?php echo __('Comment') ?></th>
				</tr>
			</thead>
			<tbody>
				<?php $list = array(); ?>
	  			<?php foreach ($comment_list as $comment): ?>
					<?php
							if ($list [$comment->getScheduleId ()] == null) {
								if ($comment->getNote ()) {
									$list [$comment->getScheduleId ()] = array (

											format_date ( $comment->getDateAt (), 'dd/MM/yyyy' ) . " (" . $comment->getStartTime () . "-" . $comment->getEndTime () . ")",

											$comment->getNote () );
								} else

									$list [$comment->getScheduleId ()] = array (

											format_date ( $comment->getDateAt (), 'dd/MM/yyyy' ) . " (" . $comment->getStartTime () . "-" . $comment->getEndTime () . ")",
											$comment->getOptionName () );
							} 
							else if ($list [$comment->getScheduleId ()] != null) {
								$tempdata = $list [$comment->getScheduleId ()] [1];

								if ($comment->getNote ()) {
									$tempdata = $tempdata . ", " . $comment->getNote ();
								} else {
									$tempdata = $tempdata . ", " . $comment->getOptionName ();
								}

								$list [$comment->getScheduleId ()] [1] = $tempdata;
							}

							?>
				<?php endforeach ?>  

				<?php foreach($list as $row): ?>
					<tr>
					<td>
							<?php echo $row[0] ?>
						</td>
					<td>
							<?php echo $row[1] ?>
						</td>
				</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
</div>