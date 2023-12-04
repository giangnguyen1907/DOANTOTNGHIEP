$(document).ready(
		function() {				
			$('#ps_mobile_app_amounts_filters_expiration_date_at').datepicker({
				dateFormat : 'dd-mm-yy',
				maxDate : new Date(),
				prevText : '<i class="fa fa-chevron-left"></i>',
				nextText : '<i class="fa fa-chevron-right"></i>',
				changeMonth : true,
				changeYear : true
			});

			$('#ps_mobile_app_amounts_expiration_date_at').datepicker({
				dateFormat : 'dd-mm-yy',
				prevText : '<i class="fa fa-chevron-left"></i>',
				nextText : '<i class="fa fa-chevron-right"></i>',
				changeMonth : true,
				changeYear : true
			});		

			$('#s2id_ps_mobile_app_amounts_filters_year').css('min-width','135px');
			$('#s2id_ps_mobile_app_amounts_filters_month').css('min-width', '150px');

		})
