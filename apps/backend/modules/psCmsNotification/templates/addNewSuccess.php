<?php use_helper('I18N', 'Date') ?>
<?php include_partial('psCmsNotification/assets') ?>

<style>
textarea.input_textarea, input.input_text {
	width: 100% !important
}

#load-ajax {
	max-height: 450px;
	overflow-y: scroll;
	overflow-x: hidden;
}

.form-actions_notification {
	border-top: 1px solid #ddd;
	text-align: right;
	padding: 10px 0px;
	margin-top: 20px;
}
</style>
<script>
$(document).ready(function() {

	$('.input_text').keyup(function(){
	    $( "#remainingInput_text" ).html( this.value.length + '/' + $(this).attr('maxLength') );
	  });

	$('.input_textarea').keyup(function(){
	    $( "#remainingInput_textarea" ).html( this.value.length + '/' + $(this).attr('maxLength') );
	  });

	$('.is_system').on('change', function() {

	   if($(".is_system:checked").val() == '1'){
		   $('.checkbox').prop('checked',true);
		   $('.checkbox').attr('disabled','disabled');
		   $('.btn-psadmin').attr('disabled', false);
		   $('.is_all').attr('disabled', 'disabled');
		   $('.is_workplace').attr('disabled', 'disabled');
		   $('.is_object').attr('disabled', 'disabled');
		}
	   else if ($(".is_system:checked").val() == '0'){
		   $('.is_all').attr('disabled', false);
		   $('.is_workplace').attr('disabled', false);
		   $('.is_object').attr('disabled', false);
		}
	});
	
	$('.is_all').on('change', function() {
		
	   if($(".is_all:checked").val() == '1'){
		   $('.checkbox').prop('checked',true);
		   $('.checkbox').attr('disabled','disabled');
		   $('.btn-psadmin').attr('disabled', false);
		   $('.is_workplace').attr('disabled', 'disabled');
		   $('.is_object').attr('disabled', 'disabled');
		   }
	   else if ($(".is_all:checked").val() == '0'){
		   $('.checkbox').prop('checked',false);
		   $('.checkbox').attr('disabled',false);
		   $('.btn-psadmin').attr('disabled', 'disabled');
		   $('.is_workplace').attr('disabled', false);
		   $('.is_object').attr('disabled', false);
		   }
	});
	
	$('.is_workplace').on('change', function() {

		   if($(".is_workplace:checked").val() == '1'){
			   $('.checkbox').prop('checked',true);
			   $('.checkbox').attr('disabled','disabled');
			   $('.btn-psadmin').attr('disabled', false);
			   $('.is_object').attr('disabled', 'disabled');
			   }
		   else if ($(".is_workplace:checked").val() == '0'){
			   $('.checkbox').prop('checked',false);
			   $('.checkbox').attr('disabled',false);
			   $('.btn-psadmin').attr('disabled', 'disabled');
			   $('.is_object').attr('disabled', false);
			   }
	});

	$('.is_object').on('change', function() {

		   if($(".is_object:checked").val() == '1'){
			   $('._check_teacher').prop('checked',true);
			   $('._check_relative').prop('checked',false);
			   $('.checkbox').attr('disabled','disabled');
			   $('.btn-psadmin').attr('disabled', false);
			   }
		   else if ($(".is_object:checked").val() == '2'){
			   $('._check_relative').prop('checked',true);
			   $('._check_teacher').prop('checked',false);
			   $('.checkbox').attr('disabled','disabled');
			   $('.btn-psadmin').attr('disabled', false);
			   }
		   else if ($(".is_object:checked").val() == '0'){
			   $('._check_all').prop('checked',false);
			   $('._check_all').attr('disabled',false);
			   $('.btn-psadmin').attr('disabled', 'disabled');
			   }
	});
	
});

</script>
<section id="widget-grid">
	<!--  sf_admin_container -->
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

		<?php include_partial('psCmsNotification/flashes') ?>

		<!-- sf_admin_container -->
			<div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-grid="false" data-widget-collapsed="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false" data-widget-togglebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-table"></i></span>
					<h2><?php echo __('Notification new', array(), 'messages') ?></h2>
				</header>
				<div>
					<div class="widget-body no-padding">
						<div id="datatable_fixed_column_wrapper"
							class="dataTables_wrapper form-inline no-footer no-padding">
							<div class="dt-toolbar no-margin no-padding no-border">
								<div class="col-xs-12 col-sm-12">
									<div id="sf_admin_header"><?php include_partial('psCmsNotification/list_header') ?></div>
								</div>
							</div>

							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">						
						<?php include_partial('psCmsNotification/menu') ?>					
                    	</div>
								<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
					<?php
					// ham updated id truong cua thong bao

					// $lists = Doctrine::getTable('PsCmsNotifications')->getAllUserId();
					// $array_id = array();
					// foreach ($lists as $list){
					// $sf_user = Doctrine::getTable('sfGuardUser')->findOneById($list->getUserCreatedId())->getPsCustomerId();
					// $list->setPsCustomerId($sf_user);
					// $list->save();
					// }

					// $user_type_m = PreSchool::USER_TYPE_TEACHER;

					// $user_type_r = PreSchool::USER_TYPE_RELATIVE;

					// $member_class = Doctrine::getTable ( 'sfGuardUser' )->getUserInfoNotification ( 81, $user_type_m );
					// echo $member_class->getId();
					?>	
					<form id="frm_batch"
										class="form-horizontal fv-form fv-form-bootstrap"
										action="<?php echo url_for('@ps_cms_notification_add_new_save') ?>"
										method="post">
										<fieldset>
											<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4"
												style="padding: 30px 0px;">
												<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
													style="padding: 0px;">
													<div class="form-group" style="width: 100%">
														<div class="col-md-12 control-label"
															style="text-align: left;">
															<label for="ps_cms_notifications_title"><?php echo __('Title') ?><span
																class="required"> *</span></label>
														</div>
														<div class="col-md-12">
                                        <?php echo $formFilter['title']->render() ?>
                                	 	<?php echo $formFilter['title']->renderError() ?>
                                        	<!-- 
                                        	<input maxlength="150" class="form-control input_text form-control" required="required" type="text" name="ps_cms_notifications[title]" id="ps_cms_notifications_title" data-fv-field="ps_cms_notifications[title]">
                    						-->
															<span id="remainingInput_text" class="note pull-right">0/150</span>
															<small class="help-block" data-fv-result="INVALID"></small>
															<small class="help-block" data-fv-validator="notEmpty"
																data-fv-for="ps_cms_notifications[title]"
																data-fv-result="NOT_VALIDATED" style="display: none;"><?php echo __('Please enter a value') ?></small>
															<small class="help-block"
																data-fv-validator="stringLength"
																data-fv-for="ps_cms_notifications[title]"
																data-fv-result="NOT_VALIDATED" style="display: none;"><?php echo __('Please enter a valid length value') ?></small>

														</div>
													</div>
												</div>
												<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
													style="padding: 0px;">
													<div class="form-group" style="width: 100%">
														<div class="col-md-12 control-label"
															style="text-align: left;">
															<label for="ps_cms_notifications_description"><?php echo __('Description') ?><span
																class="required"> *</span></label>
														</div>
														<div class="col-md-12">
                                    	<?php echo $formFilter['description']->render() ?>
                                	 	<?php echo $formFilter['description']->renderError() ?>
                                    	<!--
                                    	<textarea class="input_textarea form-control" required="required" name="ps_cms_notifications[description]" id="ps_cms_notifications_description" data-fv-field="ps_cms_notifications[description]"></textarea>        	
                                    	-->
															<span id="remainingInput_textarea"
																class="note pull-right">0/5000</span> <small
																class="help-block" data-fv-result="INVALID"></small> <small
																class="help-block" data-fv-validator="notEmpty"
																data-fv-for="ps_cms_notifications[description]"
																data-fv-result="NOT_VALIDATED" style="display: none;"><?php echo __('Please enter a value') ?></small>
															<small class="help-block"
																data-fv-validator="stringLength"
																data-fv-for="ps_cms_notifications[description]"
																data-fv-result="NOT_VALIDATED" style="display: none;"><?php echo __('Please enter a valid length value') ?></small>

														</div>
													</div>
												</div>
                            	<?php if ($sf_user->hasCredential(array('PS_CMS_NOTIFICATIONS_SYSTEM'))): ?>
                            	<div
													class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
													style="padding: 0px;">
													<div class="form-group" style="width: 100%">
														<div class="col-md-5 control-label"
															style="text-align: left;">
															<label for="ps_cms_notifications_description"><?php echo __('is system') ?></label>
														</div>
														<div class="col-md-7">
															<div class="is_system">
                                    		<?php echo $formFilter['is_system']->render() ?>
                                    	 	<?php echo $formFilter['is_system']->renderError() ?>
                                    	</div>
														</div>
													</div>
												</div>
                            	<?php endif;?>
                            	<?php if ($sf_user->hasCredential(array('PS_CMS_NOTIFICATIONS_ALL'))): ?>
                            	<div
													class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
													style="padding: 0px;">
													<div class="form-group" style="width: 100%">
														<div class="col-md-5 control-label"
															style="text-align: left;">
															<label for="ps_cms_notifications_description"><?php echo __('is all') ?></label>
														</div>
														<div class="col-md-7">
															<div class="is_system">
                                    		<?php echo $formFilter['is_all']->render() ?>
                                    	 	<?php echo $formFilter['is_all']->renderError() ?>
                                    	</div>
														</div>
													</div>
												</div>
                            	<?php endif;?>
                            	<?php if ($sf_user->hasCredential(array('PS_CMS_NOTIFICATIONS_WORKPLACE')) || $sf_user->hasCredential(array('PS_CMS_NOTIFICATIONS_ALL'))): ?>
                            	<div
													class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
													style="padding: 0px;">
													<div class="form-group" style="width: 100%">
														<div class="col-md-5 control-label"
															style="text-align: left;">
															<label for="ps_cms_notifications_description"><?php echo __('is workplace') ?></label>
														</div>
														<div class="col-md-7">
															<div class="is_system">
                                    		<?php echo $formFilter['is_workplace']->render() ?>
                                    	 	<?php echo $formFilter['is_workplace']->renderError() ?>
                                    	</div>
														</div>
													</div>
												</div>
												<!-- -->
												<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
													style="padding: 0px;">
													<div class="form-group" style="width: 100%">

														<div class="col-md-12">
															<div class="is_object">
																<label class="radio"
																	for="ps_cms_notifications_is_object_1"> <input
																	class="is_object radiobox"
																	name="ps_cms_notifications[is_object]" type="radio"
																	value="1" id="ps_cms_notifications_is_object_1"> <span><?php echo __('Is teacher')?></span>
																</label> <label class="radio"
																	for="ps_cms_notifications_is_object_2"> <input
																	class="is_object radiobox"
																	name="ps_cms_notifications[is_object]" type="radio"
																	value="2" id="ps_cms_notifications_is_object_2"> <span><?php echo __('Is relative')?></span>
																</label> <label class="radio"
																	for="ps_cms_notifications_is_object_0"> <input
																	class="is_object radiobox"
																	name="ps_cms_notifications[is_object]" type="radio"
																	value="0" id="ps_cms_notifications_is_object_0"
																	checked="checked"> <span><?php echo __('no')?></span>
																</label>
															</div>

														</div>
													</div>
												</div>
                            	
                            	<?php endif;?>
                            </div>
											<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
												<div class=""
													style="border: 1px solid #ccc; padding: 11px 0px 10px 15px;">

													<div class="form-controll">
														<label class="hidden">
                                		<?php echo $formFilter['ps_school_year_id']->render() ?>
                                	 	<?php echo $formFilter['ps_school_year_id']->renderError() ?>
                                	</label> <label>
                                		<?php echo $formFilter['ps_customer_id']->render() ?>
                                	 	<?php echo $formFilter['ps_customer_id']->renderError() ?>
                                	</label> <label>
                                	 	<?php echo $formFilter['ps_workplace_id']->render() ?>
                                	 	<?php echo $formFilter['ps_workplace_id']->renderError() ?>
                                	 </label>
													</div>
												</div>

												<div id="load-ajax">
                        	<?php include_partial('psCmsNotification/table_list_class', array('my_class' => $my_class)) ?>
                        	</div>

											</div>
										</fieldset>
										<div class="form-actions_notification">
											<div class="sf_admin_actions">
												<button type="submit" name="_save_and_add"
													class="btn btn-default btn-success btn-sm btn-psadmin">
													<i class="fa-fw fa fa-cloud-upload" aria-hidden="true"></i> <?php echo __('Send') ?></button>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- END: sf_admin_container -->
		</article>
	</div>
</section>