$(document).ready(function() {
	
	tinymce.init({
		  selector: '#ps_comment_week_comment',
		  indentation : '13px',
		  font_formats: 'Arial=arial,sans-serif;',
		  plugins: [
				    'advlist autolink lists link image charmap print preview hr anchor pagebreak',
				    'searchreplace wordcount visualblocks visualchars code fullscreen',
				    'insertdatetime media nonbreaking save table contextmenu directionality',
				    'emoticons template paste textcolor colorpicker textpattern imagetools'
				  ],
		  toolbar: 'undo redo | fontselect | fontsizeselect | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
		  
	});


	tinymce.init({
		  selector: '.ps_comment_week_student',
		  indentation : '13px',
		  font_formats: 'Arial=arial,sans-serif;',
		  plugins: [
				    'advlist autolink lists link image charmap print preview hr anchor pagebreak',
				    'searchreplace wordcount visualblocks visualchars code fullscreen',
				    'insertdatetime media nonbreaking save table contextmenu directionality',
				    'emoticons template paste textcolor colorpicker textpattern imagetools'
				  ],
		  toolbar: 'undo redo | fontselect | fontsizeselect | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
		  
	});

	
	$('#form_ps_comment_week').formValidation({
      	framework: 'bootstrap',
          addOns: {
            	i18n: {}
        	},

        icon: {},
        fields: {
        	'ps_comment_week[title]': {
        		validators: {
        			notEmpty: {
        				message: {
        					en_US: 'Please enter title',
        					vi_VN: 'Vui lòng nhập tiêu đề'
        				}
        			},
        			stringLength: {
        				max: 255,
        				message: {
        					en_US: 'Title must be lower than %s characters long',
        					vi_VN: 'Tối đa %s ký tự'
        				}
        			}
        		}
        	},
        }
      });

    $('#form_ps_comment_week').formValidation('setLocale', PS_CULTURE);

});