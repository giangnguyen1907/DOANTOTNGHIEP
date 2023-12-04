$(document).ready(function() {
	
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
          icon: {},
      });

    $('#ps-form').formValidation('setLocale', PS_CULTURE);

});