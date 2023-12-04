<input type="hidden" name="count" id="count"
	value="<?php echo count($form['FeatureBranchTimes']);?>" />
<input type="hidden" name="newfieldscount" id="newfieldscount" value="0" />
<div class="table-responsive">
	<table id="dt_basic"
		class="table table-striped table-bordered table-hover no-footer no-padding"
		width="100%">
		<thead>
			<tr>
				<th class="text-center col-md-2"><?php echo __('Start at')?></th>
				<th class="text-center col-md-2"><?php echo __('End at')?></th>
				<th class="text-center col-md-3"><?php echo __('Start time')?> | <?php echo __('End time')?></th>
				<th class="text-center col-md-1"><?php echo __('Class room')?></th>
				<th class="text-center col-md-1">
					<div class="col-md-6"><?php echo __('Saturday')?></div>
					<div class="col-md-6"><?php echo __('Sunday')?></div>
				</th>
				<th class="text-center col-md-2" data-hide="phone"><?php echo __('Description')?><?php echo __('(500 char)')?></th>
				<th class="text-center col-md-1">
					<div class="col-md-6">
						<a class="btn btn-xs btn-default"><i
							class="fa-fw fa fa-trash-o fa-lg"
							title="<?php echo __('Delete')?>"></i></a>
					</div>
					<div class="col-md-6" rel="tooltip" data-placement="left"
						data-original-title="<?php echo __('Click here to add new')?>">
						<a class="btn btn-xs btn-default" id="btn_add"><i
							class="fa-fw fa fa-plus-square fa-lg"></i></a>
					</div>
				</th>
			</tr>
		</thead>
		<tbody>
		<?php
		foreach ( $form ['FeatureBranchTimes'] as $i => $ps_feature_branch_times ) :
			?>
		<tr class="list-times" row-id="<?php echo $i;?>">
				<td>
					<div
						class="form-group <?php $ps_feature_branch_times['start_at']->hasError() and print ' errors';?> ">
						<div class="col-md-12">
							<div class="input-group">
								<span class="required input-group-addon">*</span><?php echo $ps_feature_branch_times['start_at']; ?>		          		
		          	</div>
							<small class="help-block" data-fv-result="INVALID"><?php echo $ps_feature_branch_times['start_at']->renderError();?></small>
						</div>
					</div>
				</td>
				<td>
					<div
						class="form-group <?php $ps_feature_branch_times['end_at']->hasError() and print ' errors';?> ">
						<div class="col-md-12">
							<div class="input-group">
								<span class="required input-group-addon">*</span><?php echo $ps_feature_branch_times['end_at']; ?>	          		
	          		</div>
							<small class="help-block" data-fv-result="INVALID"><?php echo $ps_feature_branch_times['end_at']->renderError() ?></small>
						</div>
					</div>
				</td>
				<td>

					<div
						class="form-group <?php $ps_feature_branch_times['start_time']->hasError() and print ' errors';?> ">
						<div class="col-md-6">
							<div class="input-group">
								<span class="required input-group-addon">*</span><?php echo $ps_feature_branch_times['start_time']; ?>
		          	</div>
							<small class="help-block" data-fv-result="INVALID"><?php echo $ps_feature_branch_times['start_time']->renderError() ?></small>
						</div>

						<div class="col-md-6">
					<?php echo $ps_feature_branch_times['end_time']; ?>
		          	<small class="help-block" data-fv-result="INVALID"><?php echo $ps_feature_branch_times['end_time']->renderError() ?></small>
						</div>
					</div>
				</td>

				<td>
					<div
						class="form-group <?php $ps_feature_branch_times['ps_class_room_id']->hasError() and print ' errors';?> ">
						<div class="col-md-12">
					<?php echo $ps_feature_branch_times['ps_class_room_id']; ?>
		          	<small class="help-block" data-fv-result="INVALID"><?php echo $ps_feature_branch_times['ps_class_room_id']->renderError() ?></small>
						</div>
					</div>
				</td>

				<td>
					<div class="col-md-6">
						<div class="checkbox">
							<label>
						<?php echo $ps_feature_branch_times['is_saturday'] ?><span></span>
							</label>
						</div>
					</div>
					<div class="col-md-6">
						<div class="checkbox">
							<label>
						<?php echo $ps_feature_branch_times['is_sunday'] ?><span></span>
							</label>
						</div>
					</div>
				</td>

				<td>
					<div
						class="form-group <?php $ps_feature_branch_times['note']->hasError() and print ' errors';?> ">
						<div class="col-md-12">
							<div class="input-group">
		          		<?php echo $ps_feature_branch_times['note']; ?>
		          	</div>
							<small class="help-block" data-fv-result="INVALID"><?php echo $ps_feature_branch_times['note']->renderError() ?></small>
						</div>
					</div>
				</td>
				<td>
					<div class="col-md-6">
						<label class="checkbox-inline" rel="tooltip" data-placement="auto"
							data-original-title="<?php echo __('Tick to delete');?>">
					<?php echo $ps_feature_branch_times['delete'] ?><span></span>
						</label>
					</div>
					<div class="col-md-6">&nbsp;</div>
				</td>
			</tr>        
        <?php
		endforeach
		;
		?>
        
        <?php
								$form_FeatureBranchTimes = new FeatureBranchForm ();
								$form_FeatureBranchTimes->loadRowFeatureBranchTimesFormTemplate ( $form->getObject () );
								?>        
        <tr class="list-new hide" id="rowTemplate">
				<td>
					<div
						class="form-group <?php $form_FeatureBranchTimes['new']['temp']['start_at']->hasError() and print ' errors';?> ">
						<div class="col-md-12">
							<div class="input-group">
								<span class="required input-group-addon">*</span><?php echo $form_FeatureBranchTimes['new']['temp']['start_at']; ?>		          		
		          	</div>
							<small class="help-block" data-fv-result="INVALID"><?php echo $form_FeatureBranchTimes['new']['temp']['start_at']->renderError();?></small>
						</div>
					</div>
				</td>
				<td>
					<div
						class="form-group <?php $form_FeatureBranchTimes['new']['temp']['end_at']->hasError() and print ' errors';?> ">
						<div class="col-md-12">
							<div class="input-group">
								<span class="required input-group-addon">*</span><?php echo $form_FeatureBranchTimes['new']['temp']['end_at']; ?>	          		
	          		</div>
							<small class="help-block" data-fv-result="INVALID"><?php echo $form_FeatureBranchTimes['new']['temp']['end_at']->renderError() ?></small>
						</div>
					</div>
				</td>
				<td>
					<div
						class="form-group <?php $form_FeatureBranchTimes['new']['temp']['start_time']->hasError() and print ' errors';?> ">
						<div class="col-md-6">
							<div class="input-group">
								<span class="required input-group-addon">*</span><?php echo $form_FeatureBranchTimes['new']['temp']['start_time']; ?>
		          	</div>
							<small class="help-block" data-fv-result="INVALID"><?php echo $form_FeatureBranchTimes['new']['temp']['start_time']->renderError() ?></small>
						</div>

						<div class="col-md-6">
					<?php echo $form_FeatureBranchTimes['new']['temp']['end_time']; ?>
		          	<small class="help-block" data-fv-result="INVALID"><?php echo $form_FeatureBranchTimes['new']['temp']['end_time']->renderError() ?></small>
						</div>
					</div>
				</td>
				<td>
					<div
						class="form-group <?php $form_FeatureBranchTimes['new']['temp']['ps_class_room_id']->hasError() and print ' errors';?> ">
						<div class="col-md-12">
					<?php echo $form_FeatureBranchTimes['new']['temp']['ps_class_room_id']; ?>
		          	<small class="help-block" data-fv-result="INVALID"><?php echo $form_FeatureBranchTimes['new']['temp']['ps_class_room_id']->renderError() ?></small>
						</div>
					</div>
				</td>

				<td>
					<div class="col-md-6">
						<label class="checkbox-inline"> <input type="checkbox" /> <span></span>
						</label>
					</div>
					<div class="col-md-6">
						<label class="checkbox-inline"> <input type="checkbox" /> <span></span>
						</label>
					</div>
				</td>

				<td>
					<div
						class="form-group <?php $form_FeatureBranchTimes['new']['temp']['note']->hasError() and print ' errors';?> ">
						<div class="col-md-12">
							<div class="input-group">
		          		<?php echo $form_FeatureBranchTimes['new']['temp']['note']; ?>
		          	</div>
							<small class="help-block" data-fv-result="INVALID"><?php echo $form_FeatureBranchTimes['new']['temp']['note']->renderError() ?></small>
						</div>
					</div>
				</td>
				<td>
					<div class="col-md-6"></div>
					<div class="col-md-6 text-left">
						<div class="checkbox">
							<button type="button"
								class="btn btn-default btn-danger btn-xs removeButton text-right">
								<i class="fa fa-fw fa-minus-square"></i>
							</button>
						</div>
					</div>
				</td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<td style="border-right: none; border-left: none;"></td>
				<td style="border-right: none; border-left: none;"></td>
				<td style="border-right: none; border-left: none;"></td>
				<td style="border-right: none; border-left: none;"></td>
				<td style="border-right: none; border-left: none;"></td>
				<td style="border-left: none;"></td>
				<td>
					<div class="col-md-6">&nbsp;</div>
					<div class="col-md-6" rel="tooltip" data-placement="left"
						data-original-title="<?php echo __('Click here to add new')?>">
						<a class="btn btn-xs btn-default" id="btn_add"><i
							class="fa-fw fa fa-plus-square fa-lg"></i></a>
					</div>
				</td>
			</tr>
		</tfoot>
	</table>
</div>