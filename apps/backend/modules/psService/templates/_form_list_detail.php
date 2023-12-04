<input type="hidden" name="count" id="count"
	value="<?php echo count($form['ServiceDetail']);?>" />
<table
	class="table table-striped table-bordered table-hover no-footer no-padding dataTable"
	style="width: 100%;" id="tb">
	<thead>
		<tr>
			<th><?php echo __('Amount')?></th>
			<th style="width: 25%"><?php echo __('By number')?></th>
			<th colspan="2" style="text-align: center;"><?php echo __('Date of application')?>
	  <div style="width: 300px;">
					<div class="row_datetime" style="float: left;"><?php echo __('From day')?></div>
					<div class="row_datetime" style="float: left;"><?php echo __('To day')?></div>
					<div></th>
			<th style="width: 100px;"><?php echo __('#')?></th>
		</tr>
	
	
	<thead>
<?php if ($form->getObject()->isNew()):?>
<script type="text/javascript">newfieldscount = 1;</script>
		<tr>
			<td class="row_number"><span class="required">*</span> <?php echo $form['new'][0]['amount']->renderError()?><?php echo $form['new'][0]['amount']?>	</td>
			<td class="row_number" style="text-align: center"><span
				class="required">*</span> <?php echo $form['new'][0]['by_number']->renderError()?><?php echo $form['new'][0]['by_number']?>	</td>
			<td class="row_datetime"><span class="required">*</span> <?php echo $form['new'][0]['detail_at']->renderError()?><?php echo $form['new'][0]['detail_at']?>	</td>
			<td class="row_datetime"><span class="required">*</span> <?php echo $form['new'][0]['detail_end']->renderError()?><?php echo $form['new'][0]['detail_end']?>	</td>
			<td>&nbsp;</td>
		</tr>
<?php endif;?>

<?php foreach($form['ServiceDetail'] as $k => $servicedetail):?>
<tr>
			<td class="row_number"><span class="required">*</span> <?php echo $servicedetail['amount']->renderError() ?><?php echo $servicedetail['amount'] ?></td>
			<td class="row_number"><span class="required">*</span> <?php echo $servicedetail['by_number']->renderError() ?> 
		<?php echo $servicedetail['by_number'] ?></td>
			<td class="row_datetime"><span class="required">*</span> <?php echo $servicedetail['detail_at']->renderError() ?>
		<?php echo $servicedetail['detail_at'] ?>
		<input type="hidden"
				name="service[ServiceDetail][<?php echo $k ?>][detail_at][day]"
				value="01" /></td>
			<td class="row_datetime"><span class="required">*</span> <?php echo $servicedetail['detail_end']->renderError() ?>
		<?php echo $servicedetail['detail_end'] ?>
		<input type="hidden"
				name="service[ServiceDetail][<?php echo $k ?>][detail_end][day]"
				value="01" /></td>
			<td class="action">
		 AAAAAÂ<?php echo $servicedetail['delete']->renderError() ?>
		 <?php echo $servicedetail['delete'] ?>		 
	</td>
		</tr>
<?php endforeach;?>


</table>
