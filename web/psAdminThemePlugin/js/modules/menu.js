$(document).ready(function() {

  $('#ps_menus_filters_date_at_from')
    .datepicker({
      dateFormat : 'dd-mm-yy',
	  changeMonth: true,
	  changeYear: true,
      prevText : '<i class="fa fa-chevron-left"></i>',
      nextText : '<i class="fa fa-chevron-right"></i>',
    })
    .on('changeDate', function(e) {
      // Revalidate the date field
      //$('#ps-form').formValidation('revalidateField', 'ps_menus_filters[date_at_from]');
    });
  
  $('#ps_menus_date_at')
  .datepicker({
    dateFormat : 'dd-mm-yy',
    prevText : '<i class="fa fa-chevron-left"></i>',
    nextText : '<i class="fa fa-chevron-right"></i>',
  })
  .on('changeDate', function(e) {
    // Revalidate the date field
    $('#ps-form').formValidation('revalidateField', 'ps_menus[date_at]');
  });
  
	/*
	  $('#ps-form')    
	    .formValidation({
	
	      framework: 'bootstrap',
	      excluded: [':disabled'],
	          addOns: {
	               i18n: {}
	          },
	          errorElement: "div",
	          errorClass: "help-block with-errors",
	          message: {vi_VN: 'This value is not valid'},
	          icon: {
	                valid: 'glyphicon glyphicon-ok-circle',
	                invalid: 'glyphicon glyphicon-remove-circle',
	                validating: 'glyphicon glyphicon-refresh'
	          },
	          fields: {               
	                "ps_menus[date_at]": {
	                    validators: {
	                       date: {
	                            format: 'DD-MM-YYYY'
	                        }
	                    }
	                }
	          }
	    });                
	
	    $('#ps-form').formValidation('setLocale', PS_CULTURE);
	*/
});