<?php use_helper('I18N', 'Date')?>
<style type="text/css">
.control-label {
	font-weight: bold;
}

.mt-1 {
	margin-top: 2.5rem;
}

.form-control-feedback {
	top: 0;
	right: 10px;
}
</style>
<?php $department_title = $department_fc = '';?>
<form class="form-horizontal123"
	action="<?php echo url_for('@refresh_password_save') ?>" id="ps-form"
	data-fv-addons='i18n' method="post">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal"
			aria-hidden="true">×</button>
		<h4 class="modal-title" id="myModalLabel">
			<strong><?php echo __('Refresh password') ?></strong>
		</h4>
	</div>

	<div class="modal-body">

		<div class=" col-lg-8 col-md-8 col-sm-12 col-xs-12">
			<div class="row">
				<div class="form-group">
					<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
						<label class="control-label"><?php echo __('School name') ?></label>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
						<p>
    					<?php echo $user_detail->getSchoolName()?>
    				</p>
					</div>
				</div>
				<div style="clear: both"></div>

				<div class="form-group">
					<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
						<label class="control-label"><?php echo __('Workplaces') ?></label>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
						<p>
    					<?php echo $workplace_name;?>
    				</p>
					</div>
				</div>
				<div style="clear: both"></div>

				<div class="form-group">
					<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
						<label class="control-label"><?php echo __('Full name') ?></label>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
						<p>
    					<?php echo $user_detail->getFullName()?>
    				</p>
					</div>
				</div>
				<div style="clear: both"></div>
    		
    		<?php if($user_detail->getUserType() == PreSchool::USER_TYPE_TEACHER){?>
    		<?php

foreach ( $member_department as $key => $member_departments ) {
								$department_title .= $member_departments->getDTitle () . ', ';
								$department_fc .= $member_departments->getFcTitle () . ', ';
							}
							?>
    		
    		<div class="form-group">
					<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
						<label class="control-label"><?php echo __('Department') ?></label>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
						<p><?php echo $department_title;?></p>
					</div>
				</div>
				<div style="clear: both"></div>

				<div class="form-group">
					<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
						<label class="control-label"><?php echo __('Function') ?></label>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
						<p><?php echo $department_fc;?></p>
					</div>
				</div>
				<div style="clear: both"></div>
    		<?php }else{ ?>
    		
    		<div class="form-group">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<label class="control-label"><?php echo __('Relative of student') ?></label>
					</div>

					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    				<?php foreach ($relative_students as $relative){?>
    				<p><?php echo $relative->getTitle().': '.$relative->getStudentName().' ('.(false !== strtotime($relative->getStudentBrithday()) ? format_date($relative->getStudentBrithday(), "dd/MM/yyyy") : '' ).'), '.__('Class').': '.$relative->getMcTitle()?></p>
    				<?php }?>
    			</div>

				</div>
				<div style="clear: both"></div>
    		
    		<?php }?>
		</div>
		</div>

		<div class=" col-lg-4 col-md-4 col-sm-12 col-xs-12">
			<input type="hidden" name="change_password[user_id]"
				class="form-control" value="<?php echo $user_detail->getId();?>"
				id="change_password_user_id"
				data-fv-field="change_password[user_id]">
			<div class="form-group">
				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
					<label class="control-label"><?php echo __('Username') ?></label>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
					<p>
					<?php echo $user_detail->getUserName()?>
				</p>
				</div>
			</div>
         <?php echo $form->renderFormTag('sf_guard_user/refreshpassword')?>
          <?php

foreach ( $form as $field ) :
											?>
          <div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
					style="margin-bottom: 25px">
					<div class="form-group">
                       <?php echo $field->renderLabel($form->getWidgetSchema()->getLabel($field->getName()),array('class' => 'col-md-12 control-label'))?>
                       <div class="col-md-12">
                       <?php echo $field->render(array('class' => 'form-control'))?>
                  	   </div>
					</div>
				</div>
			</div>
        <?php endforeach; ?>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
				style="margin-bottom: 25px">

				<div class="buttom-ramdom">
					<a class="btn btn-sm btn-default ramdom_password"
						id="generatePassword"><?php echo __('Random')?></a> <label
						id="randomPassword"
						style="margin-left: 10px; margin-bottom: 0; font-size: 16px; background: #dedede;"></label>
				</div>

			</div>
			<script type="text/javascript">
function random_password_generate(max,min)
{
    var passwordChars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz#@!%&()/";
    var randPwLen = Math.floor(Math.random() * (max - min + 1)) + min;
    var randPassword = Array(randPwLen).fill(passwordChars).map(function(x) { return x[Math.floor(Math.random() * x.length)] }).join('');
    return randPassword;
}
document.getElementById("generatePassword").addEventListener("click", function(){
    random_password = random_password_generate(16,8);
    
    document.getElementById("change_password_new_password").value = random_password;
    document.getElementById("change_password_comfirm_password").value = random_password;
    document.getElementById("randomPassword").textContent = random_password;
});
</script>

		</div>

		<div class="clearfix"></div>
	</div>

	<div class="modal-footer">

		<button type="submit"
			class="btn btn-default btn-success btn-sm btn-psadmin">
			<i class="fa-fw fa fa-floppy-o" aria-hidden="true"></i>&nbsp;<?php echo __('Save') ?></button>

		<button type="button" class="btn btn-default" data-dismiss="modal">
			<i class="fa-fw fa fa-ban"></i>&nbsp;<?php echo __('Close') ?></button>
	</div>
</form>

<script type="text/javascript"
	src="<?php echo sfContext::getInstance()->getRequest()->getRelativeUrlRoot();?>/psAdminThemePlugin/js/plugin/formvalidation/js/form_validation.min.js"></script>
<script type="text/javascript"
	src="<?php echo sfContext::getInstance()->getRequest()->getRelativeUrlRoot();?>/psAdminThemePlugin/js/plugin/formvalidation/js/framework/bootstrap.min.js"></script>
<script type="text/javascript"
	src="<?php echo sfContext::getInstance()->getRequest()->getRelativeUrlRoot();?>/psAdminThemePlugin/js/plugin/formvalidation/js/language/i18n.min.js"></script>
<script type="text/javascript"
	src="<?php echo sfContext::getInstance()->getRequest()->getRelativeUrlRoot();?>/psAdminThemePlugin/js/plugin/formvalidation/js/language/vi_VN.js"></script>

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
