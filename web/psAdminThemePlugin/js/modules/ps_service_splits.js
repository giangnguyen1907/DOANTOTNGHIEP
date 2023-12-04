$(document).ready(function() {
    $('#ps-form').formValidation({
        framework: 'bootstrap',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
        	
        	'service_split[count_value]': {
                validators: {
                    between: {
                        min: 0,
                        max: 99
                    }
                }
            },
            'service_split[count_ceil]': {
                validators: {
                    between: {
                        min: 'service_split[count_value]',
                        max: 500                       
                    }
                }
            }
        }
    })
    .on('keyup', '[name="service_split[count_value]"]', function(e) {
        $('#ps-form').formValidation('revalidateField', 'service_split[count_ceil]');
    });
});