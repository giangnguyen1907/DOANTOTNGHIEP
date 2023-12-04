$(document).ready(function() {

	$('#ps-filter').formValidation({
		framework : 'bootstrap',
		//excluded : [':disabled'],
		excluded : [':disabled'],
		addOns : {
			i18n : {}
		},
		icon : {
			valid : null,
			invalid : null,
			validating : null
		},
		fields : {

		}
	});
	/*
	.on('err.field.fv', function(e, data) {
	        // $(e.target)  --> The field element
	        // data.fv      --> The FormValidation instance
	        // data.field   --> The field name
	        // data.element --> The field element

	        // Hide the messages
	        data.element
	            .data('fv.messages')
	            .find('.help-block[data-fv-for="' + data.field + '"]').hide();
	});	*/

	$('#ps-filter').formValidation('setLocale', PS_CULTURE);
});
