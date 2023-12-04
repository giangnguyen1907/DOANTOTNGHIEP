<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<!-- Copy tu newSuccess Studentclass -->
<script type="text/javascript">

	$(document).ready(function() {

		$('#ps_member_working_time_ps_workingtime_id').select2({
			dropdownParent: $('#remoteModal')
			//dropdownCssClass : 'no-search'
		});
		
		$.datepicker.setDefaults($.datepicker.regional[ "vi" ]);
		
		// START AND FINISH DATE	
		$('#ps_member_working_time_start_at').datepicker({			
			prevText : '<i class="fa fa-chevron-left"></i>',
		    nextText : '<i class="fa fa-chevron-right"></i>',
			changeMonth: true,
	        changeYear: true,
	        dateFormat: 'dd-mm-yy',
			onSelect : function(selectedDate) {
				$('#ps_member_working_time_stop_at').datepicker('option', 'minDate', selectedDate);
			}
		}).on('changeDate', function(e) {
		     $('#memberWorkingTimeNewForm').formValidation('revalidateField', 'ps_member_working_time[start_at]');
		});
	
		$('#ps_member_working_time[start_at]').change(function() {      
			$('#memberWorkingTimeNewForm').formValidation('revalidateField', 'ps_member_working_time[start_at]');    	
	    });
		
		$('#ps_member_working_time_stop_at').datepicker({			
			prevText : '<i class="fa fa-chevron-left"></i>',
		    nextText : '<i class="fa fa-chevron-right"></i>',
			changeMonth: true,
	        changeYear: true,
	        dateFormat: 'dd-mm-yy',
			onSelect : function(selectedDate) {
				$('#ps_member_working_time_start_at').datepicker('option', 'maxDate', selectedDate);
			}
		}).on('changeDate', function(e) {
		     $('#memberWorkingTimeNewForm').formValidation('revalidateField', 'ps_member_working_time[stop_at]');
		});	
	});
</script>
<div class="sf_admin_form widget-body">
  

    <?php foreach ($configuration->getFormFields($form, $form->isNew() ? 'new' : 'edit') as $fieldset => $fields): ?>
      
      <?php include_partial('psMemberWorkingTime/form_fieldset', array('ps_member_working_time' => $ps_member_working_time, 'form' => $form, 'fields' => $fields, 'fieldset' => $fieldset)) ?>
    
    <?php endforeach; ?>

<!--   </form> -->
</div>
