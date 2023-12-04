<?php use_helper('I18N', 'Date') ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">×</button>
	<?php if ( $form->isNew()) :?>
	<h4 class="modal-title"><?php echo __('Add member working time: %%ps_member%%', array('%%ps_member%%' => $ps_member_working_time->getPsMember()->getFirstName().' '.$ps_member_working_time->getPsMember()->getLastName()), 'messages') ?>
		<small>
			(<?php echo $ps_member_working_time->getPsMember()->getMemberCode()?>)
		</small>
	</h4>
	<?php else : ?>
	<h4 class="modal-title"><?php echo __('Edit member working time: %%ps_member%%', array('%%ps_member%%' => $ps_member_working_time->getPsMember()->getFirstName().' '.$ps_member_working_time->getPsMember()->getLastName()), 'messages') ?>
		<small>
			(<?php echo $ps_member_working_time->getPsMember()->getMemberCode()?>)
		</small>
	</h4>
	<?php endif;?>
</div>

<?php echo form_tag_for($form, '@ps_member_working_time', array('class' => 'form-horizontal fv-form-bootstrap', 'id' => 'memberWorkingTimeNewForm', 'data-fv-addons' => 'i18n')) ?>
    <?php echo $form->renderHiddenFields(true) ?>
    <?php if ($form->hasGlobalErrors()): ?>
      <?php echo $form->renderGlobalErrors() ?>
    <?php endif;?>
<div class="modal-body" style="overflow: hidden;">	
		<?php include_partial('psMemberWorkingTime/form', array('ps_member_working_time' => $ps_member_working_time, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
	</div>
<div class="modal-footer">
	    <?php include_partial('psMemberWorkingTime/form_actions', array('ps_member_working_time' => $ps_member_working_time, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
	</div>
</form>

<!-- Copy tu newSuccess Studentclass -->
<script type="text/javascript">

    $(".select2").addClass("form-control");
    $('#student_class_myclass_id, #student_class_type').select2({
		dropdownParent: $('#remoteModal')
	});
	$.datepicker.setDefaults($.datepicker.regional[ "vi" ]);
	
</script>
<!-- Copy tu newSuccess Studentclass -->
<script type="text/javascript">

	$(document).ready(function() {

		var msg_select_ps_workingtime_id	= '<?php echo __('Please select workingtime to filter the data.')?>';
		var msg_select_start_at	= '<?php echo __('Please select start at to filter the data.')?>';

		$('#memberWorkingTimeNewForm').formValidation({
	    	framework : 'bootstrap',
	    	excluded: [':disabled', ':hidden'],
	    	addOns : {
				i18n : {}
			},
			
			icon : {},
	    	fields : {
				"ps_member_working_time[start_at]": {
	                validators: {
	                    notEmpty: {
	                        message: msg_select_start_at
	                        }
	                    }
	            },
	            
	            "ps_member_working_time[ps_workingtime_id]": {
	                validators: {
	                    notEmpty: {
	                        message: msg_select_ps_workingtime_id
	                        }
	                    }
	            }
			}
	    });

	    $('#memberWorkingTimeNewForm').formValidation('setLocale', PS_CULTURE);

	});
</script>

