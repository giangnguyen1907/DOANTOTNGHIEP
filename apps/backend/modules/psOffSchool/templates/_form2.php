<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<style>
.radio-inline{margin-top: 10px!important}
</style>
<script type="text/javascript">

$(document).ready(function() {

	//$('#ps_off_school_student_id').attr('disabled', 'disabled');
	
	$('.radiobox').click(function(){

		if ($(this).val() == 2) {

			$('.btn-submit-check').addClass("disabled");
    		$('.btn-submit-check').attr('disabled', 'disabled');
    		$('#ps_off_school_reason_illegal').attr('required', true);

    		$('#ps_off_school_reason_illegal').keyup(function(){

				if(this.value.length > 0){
					$('.btn-submit-check').attr('disabled', false);
    				$('.btn-submit-check').removeClass("disabled");
				}else{
        			$('.btn-submit-check').addClass("disabled");
            		$('.btn-submit-check').attr('disabled', 'disabled');
            		$('#ps_off_school_reason_illegal').attr('required', true);
            	}
      		});
    		
		}else{
			$('#ps_off_school_reason_illegal').attr('required', false);
			$('.btn-submit-check').attr('disabled', false);
			$('.btn-submit-check').removeClass("disabled");
		}
		
	});


	$('#ps_off_school_date_at').datepicker({
		dateFormat : 'dd-mm-yy',
		maxDate : new Date(),
		prevText : '<i class="fa fa-chevron-left"></i>',
		nextText : '<i class="fa fa-chevron-right"></i>',
		changeMonth : true,
		changeYear : true,
	});
	
	
$('#ps_off_school_date_from').datepicker(
		{
			dateFormat : 'dd-mm-yy',
			minDate : new Date(),
			prevText : '<i class="fa fa-chevron-left"></i>',
			nextText : '<i class="fa fa-chevron-right"></i>',
			onSelect : function(selectedDate) {
				$('#ps_off_school_date_to')
						.datepicker('option',
								'minDate',
								selectedDate);
			}
		}).on(
		'changeDate',
		function(e) {
			// Revalidate the date field
			$('#ps_off_school_form').formValidation(
					'revalidateField',
					'ps_off_school[date][from]');
		});

$('#ps_off_school_date_to')
	.datepicker(
			{
				dateFormat : 'dd-mm-yy',
				minDate : new Date(),
				prevText : '<i class="fa fa-chevron-left"></i>',
				nextText : '<i class="fa fa-chevron-right"></i>',
				onSelect : function(selectedDate) {
					$('#ps_off_school_date_from')
							.datepicker('option',
									'maxDate',
									selectedDate);
				}
			}).on(
			'changeDate',
			function(e) {
				// Revalidate the date field
				$('#ps_off_school_form').formValidation(
						'revalidateField',
						'ps_off_school[date][to]');
			});

});

</script>


<div class="sf_admin_form widget-body">
  <?php echo form_tag_for($form, '@ps_off_school', array('class' => 'form-horizontal', 'id' => 'ps-form', 'data-fv-addons' => 'i18n')) ?>
    <?php echo $form->renderHiddenFields(true) ?>

    <?php if ($form->hasGlobalErrors()): ?>
      <?php echo $form->renderGlobalErrors() ?>
    <?php endif; ?>

	<?php foreach ($configuration->getFormFields($form, $form->isNew() ? 'new' : 'edit') as $fieldset => $fields): ?>

      <?php include_partial('psOffSchool/form_fieldset2', array('ps_off_school' => $ps_off_school, 'form' => $form, 'fields' => $fields, 'fieldset' => $fieldset)) ?>

    <?php endforeach; ?>

    <?php //include_partial('psOffSchool/form_actions', array('ps_off_school' => $ps_off_school, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
  <div class="modal-footer">  
   <button type="submit" class="btn btn-default btn-success btn-sm btn-submit-check">
                	<i class="fa-fw fa fa-save" aria-hidden="true" title="<?php echo __('Save');?>"></i><?php echo __('Save', array(), 'messages') ?>
              	</button>
  <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa-fw fa fa-ban"></i>&nbsp;<?php echo __('Close')?></button>    
</div>
  </form>
</div>
