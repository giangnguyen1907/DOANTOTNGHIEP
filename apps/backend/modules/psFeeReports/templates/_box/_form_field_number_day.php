<div class="form-group">
	<?php echo $formFilter['normal_day']->renderlabel()?><span
		class="required"> *</span>
	<?php echo $formFilter['normal_day']->render()?>
</div>
<div class="form-group">
	<?php echo $formFilter['saturday_day']->renderlabel()?><span
		class="required"> *</span>
	<?php echo $formFilter['saturday_day']->render()?>
</div>
<script type="text/javascript">
var msg_select_ps_customer_id	= '<?php echo __('Please select School.')?>';

var msg_select_ps_workplace_id	= '<?php echo __('Please select Workplace.')?>';

var msg_normal_day_invalid 		= '<?php echo __('Please enter a value for a valid normal number of days.')?>';

var msg_saturday_day_invalid 	= '<?php echo __('Please enter a value for the number of days that have a valid Saturday.')?>';

$(document).ready(function() {
	
	$('#fstep1').formValidation({
		framework : 'bootstrap',
		excluded : [':disabled', ':hidden', ':not(:visible)'],
		addOns : {
			i18n : {}
		},
		err : {
			container : '#errors'
		},
		message : {
			vi_VN : 'This value is not valid'
		},
		icon : {},
		fields : {			
			"control_filter[normal_day]" : {
				validators : {
					notEmpty : {
						message : {
							en_US : msg_normal_day_invalid,
							vi_VN : msg_normal_day_invalid
						}
					}
				}
			},
			"control_filter[saturday_day]" : {
				validators : {
					notEmpty : {
						message : {
							en_US : msg_saturday_day_invalid,
							vi_VN : msg_saturday_day_invalid
						}
					}
				}
			}
		}
	}).on('err.form.fv', function(e) {
		$('#messageModal').modal('show');
	});
	
	
	//$('#fstep3').formValidation('setLocale', PS_CULTURE);
	$('#fstep1').formValidation('setLocale', '<?php echo $PS_CULTURE;?>');

});
</script>

