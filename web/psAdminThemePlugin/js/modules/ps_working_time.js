$(document).ready(function() {
	
	if (typeof number_page !== 'undefined' && number_page > 0) {

		$('.time_picker').timepicker({
			timeFormat : 'HH:mm',
			showMeridian : false,
			defaultTime : null
		});
	}

})
