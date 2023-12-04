<input type="hidden" name="count" id="count"
	value="<?php echo count($form['ServiceDetail']);?>" />
<input type="hidden" name="newfieldscount" id="newfieldscount" value="0" />
<div class="col-sm-12">
	<div class="form-group" style="border-bottom: 1px solid #ddd;">
		<div class="col-md-3 bold border-right">
			<label><strong><?php echo __('Amount')?></strong></label>
		</div>
		<div class="col-md-2 border-right">
			<label><strong><?php echo __('By number')?></strong></label>
		</div>
		<div class="col-md-3 border-right">
			<label><strong><?php echo __('From day')?></strong></label>
		</div>
		<div class="col-md-3 border-right">
			<label><strong><?php echo __('To day')?></strong></label>
		</div>
		<div class="col-md-1 text-center">
			<div class="col-md-6 text-left">
				<a class="btn btn-xs btn-default"><i
					class="fa-fw fa fa-trash-o fa-lg txt-color-red"
					title="<?php echo __('Delete')?>"></i></a>
			</div>
			<div class="col-md-6 text-left">
				<a class="btn btn-xs btn-default" id="btn_add_servicedetail"><i
					class="fa-fw fa fa-plus-square"></i></a>
			</div>
		</div>
	</div>
	<?php foreach($form['ServiceDetail'] as $k => $servicedetail):?>	
	<div class="form-group list-detail">
		<div class="col-md-3 border-right">
			<div class="input-group">
				<span class="required input-group-addon">*</span>
				<?php echo $servicedetail['amount']?>
				<?php echo $servicedetail['amount']->renderError() ?>
			</div>
		</div>
		<div class="col-md-2 border-right">
			<div class="input-group">
				<span class="required input-group-addon">*</span>
				<?php echo $servicedetail['by_number']?>
				<?php echo $servicedetail['by_number']->renderError() ?>
			</div>
		</div>
		<div class="col-md-3 border-right">
			<div class="input-group" style="padding-left: 20px;">
				<span class="required input-group-addon">*</span>
			<?php echo $servicedetail['detail_at']?>
			<?php echo $servicedetail['detail_at']->renderError() ?>					
			</div>
		</div>

		<div class="col-md-3 border-right">
			<div class="input-group" style="padding-left: 20px;">
				<span class="required input-group-addon">*</span>
        	<?php echo $servicedetail['detail_end']?>
        	<?php echo $servicedetail['detail_end']->renderError() ?>
        	</div>
		</div>
		<div class="col-md-1 text-center">
			<div class="col-md-6 text-right">
				<div class="checkbox" style="padding-left: 5px;">
					<label><?php echo $servicedetail['delete'] ?><span></span></label>
				</div>
			</div>
			<div class="col-md-6 text-right">
				<div>
					<a data-toggle="modal" data-target="#confirmDeleteModal"
						data-backdrop="static"
						class="btn btn-xs btn-default btn-delete-item pull-right"
						data-item="<?php echo $servicedetail['id']->getValue();?>"><i
						class="fa-fw fa fa-times txt-color-red"
						title="<?php echo __('Delete')?>"></i></a>
				</div>
			</div>
		</div>
	</div>	
	<?php endforeach; ?>
	
	<?php

$form_ServiceForm = new ServiceForm ();
	$form_ServiceForm->loadRowSeviceDetailTemplate ();
	?>
	
	<div class="form-group list-new hide" id="sevice_detail_template">
		<div class="col-md-3 border-right">
			<div class="input-group">
				<span class="required input-group-addon">*</span><?php echo $form_ServiceForm['new']['temp']['amount']?>
			</div>
		</div>
		<div class="col-md-2 border-right">
			<div class="input-group">
				<span class="required input-group-addon">*</span><?php echo $form_ServiceForm['new']['temp']['by_number']?>
			</div>
		</div>
		<div class="col-md-3 border-right">
			<div class="input-group" style="padding-left: 20px;">
				<span class="required input-group-addon">*</span><?php echo $form_ServiceForm['new']['temp']['detail_at']?>					
			</div>
		</div>

		<div class="col-md-3">
			<div class="input-group" style="padding-left: 20px;">
				<span class="required input-group-addon">*</span><?php echo $form_ServiceForm['new']['temp']['detail_end']?>
        	</div>
		</div>

		<div class="col-md-1 text-center">
			<div class="col-md-6 text-right"></div>
			<div class="col-md-6 text-left">
				<div class="checkbox">
					<button type="button"
						class="btn btn-default btn-danger btn-xs removeButton text-right">
						<i class="fa fa-fw fa-minus-square"></i>
					</button>
				</div>
			</div>
		</div>
	</div>
</div>