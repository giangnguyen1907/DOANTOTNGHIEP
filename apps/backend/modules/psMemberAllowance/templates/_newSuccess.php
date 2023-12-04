<?php use_helper('I18N', 'Date') ?>
<?php include_partial('psMemberAllowance/assets') ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">×</button>
	<?php if ( $form->isNew()) :?>
	<h4 class="modal-title"><?php echo __('Add member allowance: %%ps_member%%', array('%%ps_member%%' => $ps_member_allowance->getPsMember()->getFirstName().' '.$ps_member_allowance->getPsMember()->getLastName()), 'messages') ?>
		<small>
			(<?php echo $ps_member_allowance->getPsMember()->getMemberCode()?>)
		</small>
	</h4>
	<?php else : ?>
	<h4 class="modal-title"><?php echo __('Edit member allowance: %%ps_member%%', array('%%ps_member%%' => $ps_member_allowance->getPsMember()->getFirstName().' '.$ps_member_allowance->getPsMember()->getLastName()), 'messages') ?>
		<small>
			(<?php echo $ps_member_allowance->getPsMember()->getMemberCode()?>)
		</small>
	</h4>
	<?php endif;?>
</div>

<?php echo form_tag_for($form, '@ps_member_allowance', array('class' => 'form-horizontal fv-form-bootstrap', 'id' => 'memberAllowanceNewForm', 'data-fv-addons' => 'i18n')) ?>
    <?php echo $form->renderHiddenFields(true) ?>
    <?php if ($form->hasGlobalErrors()): ?>
      <?php echo $form->renderGlobalErrors() ?>
    <?php endif;?>
<div class="modal-body" style="overflow: hidden;">	
		<?php include_partial('psMemberAllowance/form', array('ps_member_allowance' => $ps_member_allowance, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
	</div>
<div class="modal-footer">
	    <?php include_partial('psMemberAllowance/form_actions', array('ps_member_allowance' => $ps_member_allowance, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
	</div>
</form>

<!-- Copy tu newSuccess Studentclass -->
<script type="text/javascript">

	$(document).ready(function() {

		$('#ps_member_allowance_ps_allowance_id').select2({
			dropdownParent: $('#remoteModal')
			//dropdownCssClass : 'no-search'
		});
		
		$.datepicker.setDefaults($.datepicker.regional[ "vi" ]);
		
		// START AND FINISH DATE	
		$('#ps_member_allowance_start_at').datepicker({			
			prevText : '<i class="fa fa-chevron-left"></i>',
		    nextText : '<i class="fa fa-chevron-right"></i>',
			changeMonth: true,
	        changeYear: true,
	        dateFormat: 'dd-mm-yy',
			onSelect : function(selectedDate) {
				$('#ps_member_allowance_stop_at').datepicker('option', 'minDate', selectedDate);
			}
		}).on('changeDate', function(e) {
		     $('#memberAllowanceNewForm').formValidation('revalidateField', 'ps_member_allowance[start_at]');
		});
	
		$('#sps_member_allowance[start_at]').change(function() {      
			$('#memberAllowanceNewForm').formValidation('revalidateField', 'ps_member_allowance[start_at]');    	
	    });
		
		$('#ps_member_allowance_stop_at').datepicker({			
			prevText : '<i class="fa fa-chevron-left"></i>',
		    nextText : '<i class="fa fa-chevron-right"></i>',
			changeMonth: true,
	        changeYear: true,
	        dateFormat: 'dd-mm-yy',
			onSelect : function(selectedDate) {
				$('#ps_member_allowance_start_at').datepicker('option', 'maxDate', selectedDate);
			}
		}).on('changeDate', function(e) {
		     $('#memberAllowanceNewForm').formValidation('revalidateField', 'ps_member_allowance[stop_at]');
		});	

		var msg_select_ps_allowance_id	= '<?php echo __('Please select allowance to filter the data.')?>';
		var msg_select_start_at	= '<?php echo __('Please select start at to filter the data.')?>';

		$('#memberAllowanceNewForm').formValidation({
	    	framework : 'bootstrap',
	    	excluded: [':disabled', ':hidden'],
	    	addOns : {
				i18n : {}
			},
			
			icon : {},
	    	fields : {
				"ps_member_allowance[start_at]": {
	                validators: {
	                    notEmpty: {
	                        message: msg_select_start_at
	                        }
	                    }
	            },
	            
	            "ps_member_allowance[ps_allowance_id]": {
	                validators: {
	                    notEmpty: {
	                        message: msg_select_ps_allowance_id
	                        }
	                    }
	            }
			}
	    });

	    $('#memberAllowanceNewForm').formValidation('setLocale', PS_CULTURE);
		
	});
</script>


