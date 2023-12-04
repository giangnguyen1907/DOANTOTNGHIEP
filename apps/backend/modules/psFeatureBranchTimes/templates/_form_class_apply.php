<fieldset>
	<legend><?php echo __('List class apply')?></legend>
	<div class="custom-scroll table-responsive"
		style="max-height: 200px; overflow-y: scroll;">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th class="col-md-4"><?php echo __('Class name')?></th>
					<th class="col-md-5"><?php echo __('Note activitie of class')?></th>
					<th class="col-md-2"><?php echo __('Location')?></th>
					<th class="col-md-1"><?php echo __('Choose')?></th>
				</tr>
			</thead>
			<tbody>
			<?php foreach($list_myclass as $obj ) :?>
			<tr>
					<td><?php echo $obj->getClassName()?><br /> <small
						class="text-muted"> <i>
					<?php echo __('Object group').': '.$obj->getGroupName()?> - <?php echo $obj->getWorkplaceName()?>
					</i> </span></td>
					<td>
						<div class="form-group  ">
							<div class="col-md-12">
								<textarea class="form-control"
									<?php if ($obj->getFbctMyclassId() != $obj->getId()) echo 'disabled="disabled"'?>
									rows="3" maxlength="5000"
									name="class_apply[my_class][<?php echo $obj->getId();?>][note]"
									id="psactivitie_my_class_<?php echo $obj->getId();?>_note"><?php if ($obj->getFbctMyclassId() == $obj->getId()) echo $obj->getFbctNoteClass()?></textarea>
							</div>
						</div>
					</td>
					<td>
						<div class="col-md-12">
							<input type="text" class="form-control"
								<?php if ($obj->getFbctMyclassId() != $obj->getId()) echo 'disabled="disabled"'?>
								maxlength="300"
								name="class_apply[my_class][<?php echo $obj->getId();?>][ps_class_room]"
								id="psactivitie_my_class_<?php echo $obj->getId();?>_ps_class_room"
								value="<?php if ($obj->getFbctMyclassId() == $obj->getId()) echo $obj->getFbctPsClassRoomId()?>" />
						</div>
					</td>
					<td class="text-center"><label class="checkbox-inline"> <input
							class="select checkbox chk_ids" type="checkbox"
							name="class_apply[my_class][<?php echo $obj->getId();?>][ids]"
							id="psactivitie_my_class_<?php echo $obj->getId();?>_select"
							value="<?php echo $obj->getId();?>"
							<?php if ($obj->getFbctMyclassId() == $obj->getId()) echo 'checked="checked"'?>><span></span>
					</label></td>
				</tr>
			<?php endforeach;?>
		</tbody>
		</table>
	</div>
</fieldset>