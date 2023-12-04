<input type="hidden" name="count" id="count"
	value="<?php echo count($form['ServiceDetail']);?>" />
<input type="hidden" name="newfieldscount" id="newfieldscount" value="" />
<div class="col-sm-12">
	<div class="form-group" style="border-bottom: 1px solid #ddd;">
		<div class="col-md-3 border-right">
			<label><?php echo __('Amount')?></label>
		</div>
		<div class="col-md-3 border-right">
			<label><?php echo __('By number')?></label>
		</div>
		<div class="col-md-3 border-right">
			<label><?php echo __('From day')?></label>
		</div>
		<div class="col-md-3">
			<label><?php echo __('To day')?></label>
		</div>
	</div>
	<?php if ($form->getObject()->isNew()):?>	
	<div class="form-group">
		<div class="col-md-3 border-right">
			<div class="input-group">
				<span class="required input-group-addon">*</span><?php echo $form['new'][0]['amount']?>
			</div>
		</div>
		<div class="col-md-3 border-right">
			<div class="input-group">
				<span class="required input-group-addon">*</span><?php echo $form['new'][0]['by_number']?>
			</div>
		</div>
		<div class="col-md-3 border-right">
			<div class="input-group" style="padding-left: 20px;">
				<span class="required input-group-addon">*</span><?php echo $form['new'][0]['detail_at']?>					
			</div>
		</div>

		<div class="col-md-3">
			<div class="input-group" style="padding-left: 20px;">
				<span class="required input-group-addon">*</span><?php echo $form['new'][0]['detail_end']?>
        	</div>
		</div>
	</div>	
	<?php endif;?>
</div>