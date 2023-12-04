<?php use_helper('I18N', 'Date') ?>
<?php
include_partial ( 'sfGuardUser/assets' );

$title = 'Create account';

if ($sf_guard_user->getUserType () == PreSchool::USER_TYPE_TEACHER) {
	$title = 'Create account for personnel';
} elseif ($sf_guard_user->getUserType () == PreSchool::USER_TYPE_RELATIVE) {
	$title = 'Create account for relatives';
} elseif ($sf_guard_user->getUserType () == PreSchool::USER_TYPE_MANAGER) {
	$title = 'Create account for manager';	
}
?>

<section id="widget-grid">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      
      <?php include_partial('sfGuardUser/flashes') ?>

      <div class="jarviswidget " id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-togglebutton="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-pencil-square-o"></i></span>
					<h2><?php echo __($title, array(), 'messages'); ?></h2>
					<ul class="nav nav-tabs pull-right in" id="myTab">
    		<?php
				$index = 1;
				foreach ( $configuration->getFormFields ( $form, $form->isNew () ? 'new' : 'edit' ) as $fieldset => $fields ) :
			?>    		
    		<?php
				$count_field = count ( $fields );
				$not_show = 0;

				foreach ( $fields as $name => $field ) :
			?>    		
    		<?php if ((isset($form[$name]) && $form[$name]->isHidden()) || (!isset($form[$name]) && $field->isReal())) $not_show++; ?>
    		
    		<?php endforeach; ?>
    		
    		<?php if($count_field != $not_show): ?>
    		<li class="pull-right"><a data-toggle="tab"
							href="#pstab_<?php echo $index;?>"> <span>
            			<?php if ('NONE' != $fieldset):?>
                    		<?php echo __($fieldset, array(), 'messages') ?>
                    	<?php endif;?>
        			</span>
						</a></li>
    		<?php endif; ?>
    		<?php $index++;endforeach;?>
    		</ul>
				</header>

				<div id="sf_admin_header" class="no-margin no-padding no-border">
          <?php include_partial('sfGuardUser/form_header', array('sf_guard_user' => $sf_guard_user, 'form' => $form, 'configuration' => $configuration)) ?>
        </div>

				<div id="sf_admin_content">
          <?php include_partial('sfGuardUser/form', array('sf_guard_user' => $sf_guard_user, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
        </div>

				<div id="sf_admin_footer" class="no-border no-padding">
          <?php include_partial('sfGuardUser/form_footer', array('sf_guard_user' => $sf_guard_user, 'form' => $form, 'configuration' => $configuration)) ?>
        </div>
			</div>
		</article>
	</div>
</section>
<script type="text/javascript">
$(document).ready(function() {    
    var hash = window.location.hash;
    if (hash == '')
    hash = '#pstab_1';	
	$('#myTab a[href="' + hash + '"]').tab('show');    

});    
</script>
<script>

var msg_password_lenght = '<?php echo __('It must be more than 8 characters long')?>';

var msg_password_valid = '<?php echo __('The password is not valid')?>';

var msg_password_uppercase = '<?php echo __('It must contain at least one upper case character')?>';

var msg_password_lowercase = '<?php echo __('It must contain at least one lower case character')?>';

var msg_password_digit = '<?php echo __('It must contain at least one digit')?>';

var msg_password_empty= '<?php echo __('The password is required and cannot be empty')?>';

$(document).ready(function() {
	
	$('#ps-form').formValidation({
        framework: 'bootstrap',
        excluded: [':disabled', ':hidden'],
        addOns: {
             i18n: {}
     	 },
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
        	"sf_guard_user[username]": {
                verbose: false,
				threshold: 4,
				trigger: 'blur',
                validators: {
                    regexp: {
                    	regexp: /^[a-zA-Z0-9@_\.]+$/,
                    	message: {
                    		en_US: 'The username can only consist of alphabetical, number, dot, @ and underscore.',
                    		vi_VN: 'Tên người dùng chỉ có thể bao gồm các chữ cái, số, dấu chấm, @ và gạch dưới.'
                    	}
                    },
                    stringLength: {
                        min: 4,
                        max: 50,
                        message: {
                            en_US: 'The username must be more than 4 and less than 50 characters long.',
                            vi_VN: 'Tên người dùng phải từ 4 đến 50 ký tự.'
                        }
                    },
                    remote: {
						url: router_check,
						data: function(validator, $field, value) {
							return {
								userid: $('#sf_guard_user_id').val()
							};
						},
						message: {
							en_US: 'Username already exist.',
							vi_VN: 'Tên đăng nhập này đã tồn tại.'
						},
						type: 'POST',
						delay: 1000
					}                        
                }
            },
        	'sf_guard_user[password]': {
        		validators: {
                    notEmpty: {
                    	message: msg_password_empty
                    },
                    callback: {
                    	message: msg_password_valid,
                        callback: function(value, validator, $field) {
                            if (value === '') {
                                return true;
                            }

                            // Check the password strength
                            if (value.length < 8) {
                                return {
                                    valid: false,
                                    message: msg_password_lenght
                                };
                            }

                            // The password doesn't contain any uppercase character
                            if (value === value.toLowerCase()) {
                                return {
                                    valid: false,
                                    message: msg_password_uppercase
                                }
                            }

                            // The password doesn't contain any uppercase character
                            if (value === value.toUpperCase()) {
                                return {
                                    valid: false,
                                    message: msg_password_lowercase
                                }
                            }

                            // The password doesn't contain any digit
                            if (value.search(/[0-9]/) < 0) {
                                return {
                                    valid: false,
                                    message: msg_password_digit
                                }
                            }

                            return true;
                        }
                    }
                }
            }
          }
    });
 $('#ps-form').formValidation('setLocale', PS_CULTURE);   
});
</script>
 