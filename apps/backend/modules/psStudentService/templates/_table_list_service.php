<p class="label label-warning"><?php echo __('List service')?></p>
<div class="custom-scroll table-responsive"
	style="height: 500px; overflow-y: scroll;">
	<table id="dt_basic_receivable"
		class="display table table-striped table-bordered table-hover"
		width="100%">
		<thead>
			<tr>
				<th><?php echo __('Name');?></th>
				<th><?php echo __('Amount')?></th>
				<th class="text-center"><?php echo __('Tần xuất thu');?></th>
				<th class="text-center no-order" style="width: 60px;"><?php echo __('Choose')?></th>
			</tr>
		</thead>

		<tbody>			
			<tr>
				<td colspan="6" class="bg-color-orange"><strong><?php echo __('Class services')?></strong>
				</td>
			</tr>			
			<?php
			foreach ( $list_service as $service ) :
			if ($service->getCsServiceId () > 0) {
			?>
			<tr>
				<td>
				<?php echo $service->getTitle()?><br> <small style="font-size: 75%;"><i><?php echo $service->getGrTitle()?></i></small>
				</td>

				<td>
					<div class="form-group" style="margin-bottom: 0px;">
						<input type="number" readonly class="form-control" min="-9999999"
							max="9999999"
							name="control_filter[receivable][<?php echo $service->getId();?>][amount]"
							id="control_filter_receivable_month_my_class_<?php echo $service->getId();?>_amount"
							value="<?php echo $service->getAmount()?>" />
					</div>
				</td>

				<td >
					<input type="hidden" class="form-control"
							name="control_filter[receivable][<?php echo $service->getId();?>][fixed]"
							id="control_filter_receivable_month_my_class_<?php echo $service->getId();?>_discount_fixed"
							value="" />
					<input type="hidden" class="form-control"
							name="control_filter[receivable][<?php echo $service->getId();?>][discount]"
							id="control_filter_receivable_month_my_class_<?php echo $service->getId();?>_discount"
							value="" />
					<?php foreach($psRegularity as $key=> $regularity){ ?>
					<label style="padding: 0px 10px;">
					<input class=" style-0" <?php if($key==0) echo 'checked'; ?> name="control_filter[receivable][<?php echo $service->getId();?>][regularity]" type="radio" value="<?=$regularity->getId()?>" id="radiobox-<?=$regularity->getId()?>">
						<span><?=$regularity->getTitle()?></span>
					</label>
					<?php } ?>
				</td>
				<td class="text-center"><label class="checkbox-inline"> <input
						class="select checkbox chk_ids" type="checkbox"
						name="control_filter[receivable][<?php echo $service->getId();?>][ids]"
						id="control_filter_receivable_month_my_class_<?php echo $service->getId();?>_select"
						value="<?php echo $service->getId();?>"><span></span>
				</label></td>
			</tr>
		<?php } ?>
		<?php endforeach;?>
			
			<tr>
				<td colspan="6" class="bg-color-orange"><strong>
				<?php
				if ($ps_workplace_id == '') {
					$member_id = myUser::getUser ()->getMemberId ();
					$ps_workplace_id = myUser::getWorkPlaceId ( $member_id );
				}				
				if ($ps_workplace_id > 0)
					echo __ ( 'Service workplace' ) . $title = Doctrine::getTable ( 'PsWorkPlaces' )->getColumnWorkPlaceById ( $ps_workplace_id, 'title' )->getTitle ();
				?></strong>
			</td>
			</tr>
			<?php
			foreach ( $list_service as $service ) :
			if ($ps_workplace_id == $service->getWpId () && $service->getCsServiceId () <= 0) {
			?>
			<tr>
				<td>
				<?php echo $service->getTitle()?><br> <small style="font-size: 75%;"><i><?php echo $service->getGrTitle()?></i></small>
				</td>

				<td>
					<div class="form-group" style="margin-bottom: 0px;">
						<input type="number" readonly class="form-control" min="-9999999"
							max="9999999"
							name="control_filter[receivable][<?php echo $service->getId();?>][amount]"
							id="control_filter_receivable_month_my_class_<?php echo $service->getId();?>_amount"
							value="<?php echo $service->getAmount()?>" />
					</div>
				</td>
				
				<td >
					<input type="hidden" class="form-control"
							name="control_filter[receivable][<?php echo $service->getId();?>][fixed]"
							id="control_filter_receivable_month_my_class_<?php echo $service->getId();?>_discount_fixed"
							value="" />
					<input type="hidden" class="form-control"
							name="control_filter[receivable][<?php echo $service->getId();?>][discount]"
							id="control_filter_receivable_month_my_class_<?php echo $service->getId();?>_discount"
							value="" />
					<?php foreach($psRegularity as $key=> $regularity){ ?>
					<label style="padding: 0px 10px;">
					<input class=" style-0" <?php if($key==0) echo 'checked'; ?> name="control_filter[receivable][<?php echo $service->getId();?>][regularity]" type="radio" value="<?=$regularity->getId()?>" id="radiobox-<?=$regularity->getId()?>">
						<span><?=$regularity->getTitle()?></span>
					</label>
					<?php } ?>
				</td>
				<td class="text-center"><label class="checkbox-inline"> <input
						class="select checkbox chk_ids" type="checkbox"
						name="control_filter[receivable][<?php echo $service->getId();?>][ids]"
						id="control_filter_receivable_month_my_class_<?php echo $service->getId();?>_select"
						value="<?php echo $service->getId();?>"><span></span>
				</label></td>
			</tr>
		<?php } ?>
		<?php endforeach;?>
		<tr>
			<td colspan="6" class="bg-color-orange">
			<strong>
				<?php
				if ($ps_customer_id == '') {
					$ps_customer_id = myUser::getPscustomerID ();
				}
				if ($ps_customer_id > 0)
					echo __ ( 'Service customer' ) . $title = Doctrine::getTable ( 'PsCustomer' )->getColumnById ( $ps_customer_id,'title' )->getTitle ();
			?></strong>
			</td>
			</tr>
		<?php
		foreach ( $list_service as $service ) :
		if ($service->getWpId () == '' && $service->getCsServiceId () <= 0) {
		?>
		<tr>
				<td>
				<?php echo $service->getTitle()?><br> <small style="font-size: 75%;"><i><?php echo $service->getGrTitle()?></i></small>
				</td>
				<td>
					<div class="form-group" style="margin-bottom: 0px;">
						<input type="number" readonly class="form-control" min="-9999999"
							max="9999999"
							name="control_filter[receivable][<?php echo $service->getId();?>][amount]"
							id="control_filter_receivable_month_my_class_<?php echo $service->getId();?>_amount"
							value="<?php echo $service->getAmount()?>" />
					</div>
				</td>
				
				<td>
					<input type="hidden" class="form-control"
							name="control_filter[receivable][<?php echo $service->getId();?>][fixed]"
							id="control_filter_receivable_month_my_class_<?php echo $service->getId();?>_discount_fixed"
							value="" />
					<input type="hidden" class="form-control"
							name="control_filter[receivable][<?php echo $service->getId();?>][discount]"
							id="control_filter_receivable_month_my_class_<?php echo $service->getId();?>_discount"
							value="" />
					<?php foreach($psRegularity as $key=> $regularity){ ?>
					<label style="padding: 0px 10px;">
					<input class=" style-0" <?php if($key==0) echo 'checked'; ?> name="control_filter[receivable][<?php echo $service->getId();?>][regularity]" type="radio" value="<?=$regularity->getId()?>" id="radiobox-<?=$regularity->getId()?>">
						<span><?=$regularity->getTitle()?></span>
					</label>
					<?php } ?>
				</td>
				<td class="text-center">
					<label class="checkbox-inline">
						<input class="select checkbox chk_ids" type="checkbox" name="control_filter[receivable][<?php echo $service->getId();?>][ids]" id="control_filter_receivable_month_my_class_<?php echo $service->getId();?>_select"
						value="<?php echo $service->getId();?>"><span></span>
					</label>
				</td>
			</tr>
		<?php } ?>
		<?php endforeach;?>
		</tbody>
	</table>
</div>