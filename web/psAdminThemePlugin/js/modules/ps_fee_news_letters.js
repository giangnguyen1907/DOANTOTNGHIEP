$(document).ready(function() {
	
	tinymce.init({
		  language: PS_CULTURE,
		  selector: '#ps_fee_news_letters_note',
		  indentation : '13px',
		  font_formats: 'Arial=arial,sans-serif;',
		  relative_urls : false,
		  remove_script_host : false,
		  convert_urls : true,
		  plugins: [
				    'advlist autolink lists charmap print preview hr anchor pagebreak',
				    'searchreplace wordcount visualblocks visualchars code fullscreen',
				    'insertdatetime media nonbreaking save table contextmenu directionality',
				    'emoticons template paste textcolor colorpicker textpattern imagetools'
				  ],
		  toolbar: 'undo redo | fontselect | fontsizeselect | styleselect | bold italic | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent',
		  
	});

	$('#ps-form').formValidation({
      	framework: 'bootstrap',
          addOns: {

            	i18n: {}
        	},

        icon: {},
        fields: {
        	'ps_fee_news_letters[title]': {
        		validators: {
        			notEmpty: {
        				message: {
        					en_US: 'Please enter title',
        					vi_VN: 'Vui lòng nhập tiêu đề'
        				}
        			},
        			stringLength: {
        				max: 150,
        				message: {
        					en_US: 'Title must be lower than %s characters long',
        					vi_VN: 'Tối đa %s ký tự'
        				}
        			}
        		}
        	},
        	'ps_fee_news_letters[note]': {
        		validators: {
        			notEmpty: {
        				message: {
        					en_US: 'Please enter note',
        					vi_VN: 'Vui lòng nhập Nội dung bản tin'
        				}
        			},
        			stringLength: {
        				max: 250,
        				message: {
        					en_US: 'Note must be lower than %s characters long',
        					vi_VN: 'Tối đa %s ký tự'
        				}
        			}	
        		}
        	}
        }
      });

    $('#ps-form').formValidation('setLocale', PS_CULTURE);

});