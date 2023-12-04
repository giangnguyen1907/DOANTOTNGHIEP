<?php use_helper('I18N', 'Date') ?>

<section id="widget-grid">
	<!--  sf_admin_container -->
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
     <?php include_partial('global/include/_flashes') ?>
    
       <div class="jarviswidget col-sm-6" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-grid="false" data-widget-collapsed="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false" data-widget-togglebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-pencil-square-o"></i></span>
					<h2><?php echo __('Change password', array(), 'messages') ?></h2>
				</header>
				<div class="widget-body text-center">
					<div id="sf_admin_content">
						<form class="form-horizontal"
							action="<?php echo url_for('@change_password') ?>" id="ps-form"
							data-fv-addons='i18n' method="post">
             <?php echo $form->renderFormTag('ps_profile/change_password')?>
              <?php

foreach ( $form as $field ) :
															?>
              <div class="row">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<div class="form-group">
                       <?php echo $field->renderLabel($form->getWidgetSchema()->getLabel($field->getName()),array('class' => 'col-md-3 control-label'))?>
                       <div class="col-md-8">
                       <?php echo $field->render(array('class' => 'form-control'))?>
                  	   </div>
									</div>
								</div>
							</div>
          	   
            <?php endforeach; ?>   
          <div class="form-actions">
								<div class="sf_admin_actions">
									<button type="submit"
										class="btn btn-default btn-success btn-sm btn-psadmin">
										<i class="fa-fw fa fa-floppy-o" aria-hidden="true"></i>&nbsp;<?php echo __('Save') ?></button>  
			<?php
			echo link_to ( '<i class="fa-fw fa fa-ban"></i> ' . __ ( 'Cancel' ), '@ps_profile', array (
					'class' => 'btn btn-default btn-danger btn-sm btn-psadmin' ) );
			?>      
              
            </div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</article>
	</div>
</section>

<script>

var msg_password_lenght = '<?php echo __('It must be more than 6 characters long')?>';

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
        	'change_password[old_password]': {
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

                            return true;
                        }
                    }
                }
            
        	},
        	'change_password[new_password]': {
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
                            if (value.length < 6) {
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
            },
            'change_password[comfirm_password]': {
            	validators: {
                    notEmpty: {
                    	message: msg_password_empty
                    },
                    identical: {
                        field: 'change_password[new_password]',
                        message: 'The password and its confirm are not the same'
                    },
                    callback: {
                    	message: msg_password_valid,
                        callback: function(value, validator, $field) {
                            if (value === '') {
                                return true;
                            }

                            // Check the password strength
                            if (value.length < 6) {
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
<script type="text/javascript"
	src="<?php echo sfContext::getInstance()->getRequest()->getRelativeUrlRoot();?>/psAdminThemePlugin/js/plugin/formvalidation/js/form_validation.min.js"></script>
<script type="text/javascript"
	src="<?php echo sfContext::getInstance()->getRequest()->getRelativeUrlRoot();?>/psAdminThemePlugin/js/plugin/formvalidation/js/framework/bootstrap.min.js"></script>
<script type="text/javascript"
	src="<?php echo sfContext::getInstance()->getRequest()->getRelativeUrlRoot();?>/psAdminThemePlugin/js/plugin/formvalidation/js/language/i18n.min.js"></script>
<script type="text/javascript"
	src="<?php echo sfContext::getInstance()->getRequest()->getRelativeUrlRoot();?>/psAdminThemePlugin/js/plugin/formvalidation/js/language/vi_VN.js"></script>
