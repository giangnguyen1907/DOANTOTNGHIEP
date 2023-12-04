<?php use_helper('I18N', 'Date')?>
<?php include_partial('receivable/assets')?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">×</button>
	<h4 class="modal-title"><?php echo __('Add receivables to fee report', array(), 'messages') ?>
	<small><code class="lable"><?php echo date("m/Y", $ps_date) ?></code><?php echo __('Class apply')?>: <?php echo $my_class->getName();?>(<?php echo $my_class->getPsClassRooms()->getPsWorkPlaces()->getTitle()?>)</small>
	</h4>
</div>
<div class="modal-body">
	<div class="row">
		<?php $form = new BaseForm();?>
		<form id="ps-filter-receivable" action="" method="post">
			<input type="hidden" name="number_chk" id="number_chk" value="0" />		
			<?php if ($form->isCSRFProtected()): ?>
		    <input type="hidden"
				name="<?php echo $form->getCSRFFieldName() ?>"
				value="<?php echo $form->getCSRFToken() ?>" /> <input type="hidden"
				name="params[ps_customer_id]"
				value="<?php echo $params['ps_customer_id'] ?>" /> <input
				type="hidden" name="params[ps_workplace_id]"
				value="<?php echo $params['ps_workplace_id'] ?>" /> <input
				type="hidden" name="params[ps_school_year_id]"
				value="<?php echo $params['ps_school_year_id'] ?>" /> <input
				type="hidden" name="params[receivable_at]"
				value="<?php echo $params['date'] ?>" /> <input type="hidden"
				name="params[ps_myclass_id]"
				value="<?php echo $params['ps_myclass_id'] ?>" />
		    
		  	<?php endif;?>
		  	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<h4><?php echo __('Receivable List').'. '.__('School year').': ' .$my_class->getPsSchoolYear()->getTitle();?></h4>
			<?php include_partial('receivable/form_list_for_choice', array('receivable_for_fee_report' => $receivable_for_fee_report));?>
			</div>
		</form>
	</div>
</div>

<div class="modal-footer">
	<button type="button"
		class="btn btn-default btn-success btn-sm btn-psadmin"
		id="btn-get-receivable" data-dismiss="modal">
		<i class="fa-fw fa fa-floppy-o"></i> <?php echo __('Save')?></button>
	<button type="button" class="btn btn-default" data-dismiss="modal">
		<i class="fa-fw fa fa-ban"></i>&nbsp;<?php echo __('Cancel')?></button>
</div>
<script type="text/javascript">
$(function () {
	
	$('.chk_ids').click(function(){
		var number_chk = $('#number_chk').val();
		if (number_chk < 0)
			number_chk = 0;
		if ($(this).prop('checked'))
			number_chk++;
		else
			number_chk--;

		$('#number_chk').val(number_chk);
		
		if (number_chk <= 0)
			$("#btn-get-receivable").attr('disabled', 'disabled');
		else
			$("#btn-get-receivable").attr('disabled', null);
	});	
	
	$('#btn-get-receivable').click(function(){
		$('#list_receivable_temp').hide();
		$('#ic-loading').show();
		
		$.ajax({
			url: '<?php echo url_for('@ps_receivable_month_put_save')?>',
	        type: "POST",
	        data: $("#ps-filter-receivable").serialize(),
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#list_receivable_temp').show();
	    	$("#list_receivable_temp").html(msg);
			$('#ic-loading').hide();
	    });		
	});	
});
</script>