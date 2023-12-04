<input type="hidden" name="count" id="count"
	value="<?php echo count($form['ServiceDetail']);?>" />
<input type="hidden" name="newfieldscount" id="newfieldscount" value="" />
<div class="col-sm-12">
	<table id="tb" style="width: 100%;"
		class="table table-striped table-bordered table-hover no-footer no-padding dataTable">
		<thead>
			<tr>
				<th rowspan="2" style="vertical-align: middle;"><?php echo __('Amount')?></th>
				<th rowspan="2" style="vertical-align: middle;"><?php echo __('By number')?></th>
				<th colspan="2" style="vertical-align: middle;" class="text-center"><?php echo __('Date of application')?></th>
				<th rowspan="2" style="vertical-align: middle; width: 40px;"
					class="text-center"><?php echo __('#')?></th>
			</tr>

			<tr>
				<th class="text-center" style="width: 220px;"><?php echo __('From day')?></th>
				<th class="text-center"
					style="width: 220px; border-right-width: 1px;"><?php echo __('To day')?></th>
			</tr>
		</thead>
		<tbody>
	<?php if ($form->getObject()->isNew()):?>
	<script type="text/javascript">newfieldscount = 1;</script>
			<tr>
				<td>
					<div class="form-group">
						<div class="col-md-12">
			    	<?php echo $form['new'][0]['amount']->renderError()?>	    	
			    	<div class="input-group">
								<span class="required input-group-addon">*</span><?php echo $form['new'][0]['amount']?>
					</div>
						</div>
					</div>
				</td>
				<td>
					<div class="form-group">
						<div class="col-md-12">
			    	<?php echo $form['new'][0]['by_number']->renderError()?>
			    	<div class="input-group">
								<span class="required input-group-addon">*</span>
						<?php echo $form['new'][0]['by_number']?>
					</div>
						</div>
					</div>
				</td>
				<td>
					<div class="form-group">
						<div class="col-md-12">
		    		<?php echo $form['new'][0]['detail_at']->renderError()?>
	    			<div class="input-group">
								<span class="required input-group-addon">*</span><?php echo $form['new'][0]['detail_at']?>
					<?php echo $form['new'][0]['detail_at_check']?>
					</div>
						</div>
					</div>
				</td>
				<td>
					<div class="form-group">
						<div class="col-md-12">
			    	<?php echo $form['new'][0]['detail_end']->renderError()?>
		    		<div class="input-group">
								<span class="required input-group-addon">*</span><?php echo $form['new'][0]['detail_end']?>
					<?php echo $form['new'][0]['detail_end_check']?>
					</div>
						</div>
					</div>
				</td>
				<td>&nbsp;</td>
			</tr>
	<?php endif;?>
	
	<?php foreach($form['ServiceDetail'] as $k => $servicedetail):?>
	<tr>
				<td>
			<?php echo $servicedetail['amount']->renderError() ?>	    	
	    	<div class="input-group">
						<span class="required input-group-addon">*</span><?php echo $servicedetail['amount']?>	
			</div>
				</td>

				<td>
			<?php echo $servicedetail['by_number']->renderError() ?>
	    	<div class="input-group">
						<span class="required input-group-addon">*</span>
				<?php echo $servicedetail['by_number']?>				
			</div>

				</td>
				<td>
			<?php echo $servicedetail['detail_at']->renderError() ?>
			<div class="input-group">
						<span class="required input-group-addon">*</span><?php echo $servicedetail['detail_at']?>
			</div>


				</td>

				<td>
			<?php echo $servicedetail['detail_end']->renderError() ?>
    		<div class="input-group">
						<span class="required input-group-addon">*</span><?php echo $servicedetail['detail_end']?>
			</div>

				</td>
				<td class="text-center">
			<?php echo $servicedetail['delete'] ?>
		</td>
			</tr>
	<?php endforeach;?>
	  </tbody>
	</table>
	<div class="sf_admin_actions dt-toolbar-footer no-border-transparent">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right">
			<?php if ($sf_user->hasCredential(array(  0 =>   array(    0 => 'PS_STUDENT_SERVICE_DETAIL_ADD',    1 => 'PS_STUDENT_SERVICE_DETAIL_EDIT',  ),))): ?>
			<button id="btn_adddetail" type="button"
				class="btn btn-default btn-success btn-psadmin">
				<i class="fa-fw fa fa-plus-square" aria-hidden="true"
					title="<?php echo __('New detail', array(), 'messages');?>"></i> 
			<?php echo __('New detail', array(), 'messages');?>
			</button>
			<?php endif;?>
		</div>
	</div>
</div>