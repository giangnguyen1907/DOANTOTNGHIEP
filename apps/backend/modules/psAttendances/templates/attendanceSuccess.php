<?php use_helper('I18N', 'Date')?>
<?php include_partial('psAttendances/assets')?>
<?php include_partial('global/include/_box_modal_messages');?>
<style>
.sunday {
	background: #999 !important;
}

.saturday {
	background: #ccc !important;
}

li {
	list-style: none;
}

.checkbox.col-xs-3 {
	margin-top: 10px !important;
}
</style>
<?php
$student_id = $student->getId ();
$ps_customer_id = $student->getPsCustomerId ();
$login_relative_id = null;
$login_at = date ( 'H:i', strtotime ( "now" ) );
$logout_at = date ( 'H:i', strtotime ( "now" ) );
$login_member_id = null;
$ps_mote = null;
$log_value = 0;
if ($ps_logtimes) {

	$login_relative_id = ($ps_logtimes->getloginRelativeId ()) ? $ps_logtimes->getloginRelativeId () : '';

	$login_member_id = ($ps_logtimes->getloginMemberId ()) ? $ps_logtimes->getloginMemberId () : '';

	$logout_relative_id = ($ps_logtimes->getLogoutRelativeId ()) ? $ps_logtimes->getLogoutRelativeId () : '';

	$logout_member_id = ($ps_logtimes->getLogoutMemberId ()) ? $ps_logtimes->getLogoutMemberId () : '';

	$login_at = ($ps_logtimes->getLoginAt () != '') ? date ( 'H:i', strtotime ( $ps_logtimes->getLoginAt () ) ) : '';
	$logout_at = ($ps_logtimes->getLogoutAt () != '') ? date ( 'H:i', strtotime ( $ps_logtimes->getLogoutAt () ) ) : '';
	$log_value = ($ps_logtimes->getLogValue ()) ? $ps_logtimes->getLogValue () : 0;
	$ps_mote = ($ps_logtimes->getNote ()) ? $ps_logtimes->getNote () : '';
}

$date_at = PsDateTime::psDatetoTime ( $tracked_at );
$saturday = date ( 'l', $date_at );
$disable = '';

?>
<form id="frm_batch"
	action="<?php echo url_for('@ps_attendances_student_statistic_save') ?>"
	method="post">

	<!-- Modal content-->
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"
				aria-hidden="true">×</button>
			<h4 class="modal-title" id="myModalLabel">
          <?php
										echo __ ( 'Updated attendances' ) . '<b>' . date ( 'd-m-Y', strtotime ( $tracked_at ) ) . '</b>' . ' - ';
										?>
          <strong><?php
										echo $student->getFirstName () . ' ' . $student->getLastName () . ' (' . date ( 'd-m-Y', strtotime ( $student->getBirthday () ) ) . ')';
										?></strong>
			</h4>
		</div>
		<div class="modal-body">

			<div class="row">

				<div class="tab-content">
					<div id="home" class="tab-pane fade in active">
						<div class="widget-body">

							<div class="row">
								<input name="student_logtime[student_id]" type="hidden"
									value="<?php echo $student_id ?>"> <input
									name="student_logtime[tracked_at]" type="hidden"
									value="<?php echo $tracked_at ?>"> <input
									name="student_logtime[school_year_id]" type="hidden"
									value="<?php echo $school_year_id ?>"> <input
									name="student_logtime[ps_customer_id]" type="hidden"
									value="<?php echo $ps_customer_id ?>"> <input
									name="student_logtime[ps_workplace_id]" type="hidden"
									value="<?php echo $ps_workplace_id ?>"> <input
									name="student_logtime[ps_class_id]" type="hidden"
									value="<?php echo $ps_class_id ?>">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<div
										class="form-group sf_admin_form_row sf_admin_foreignkey sf_admin_form_field_type ">
										<label class="col-md-3 control-label"
											for="student_class_is_activated"><?php echo __('Status')?></label>

										<div class="col-md-9">
											<label class="radio radio-inline"
												for="form_student_myclass_mode_1" style="margin-top: 0"> <input
												class="radiobox" name="student_logtime[log_value]"
												type="radio" value="1" id="form_student_myclass_mode_1"
												<?php if($log_value == 1){echo 'checked="checked"';}?>><span><?php echo __('Go school')?></span>
											</label> <label class="radio radio-inline"
												for="form_student_myclass_mode_0"> <input class="radiobox"
												name="student_logtime[log_value]" type="radio" value="0"
												id="form_student_myclass_mode_0"
												<?php if($log_value == 0){echo 'checked="checked"';}?>><span><?php echo __('Absent').' '.__('Permission')?></span>
											</label> <label class="radio radio-inline"
												for="form_student_myclass_mode_2"> <input class="radiobox"
												name="student_logtime[log_value]" type="radio" value="2"
												id="form_student_myclass_mode_2"
												<?php if($log_value == 2){echo 'checked="checked"';}?>><span><?php echo __('Absent').' '.__('Not Permission')?></span>
											</label>
										</div>

									</div>
								</div>
							</div>
							<br />
							<div class="row">

								<div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
									<p>
										<strong><?php echo __('Attendance go')?></strong>
									</p>
									<div class="row">
										<li class="col-md-6 col-sm-6 col-xs-6"
											style="padding-top: 5px;"><label class="select"
											style="width: 100%"> <select class="form-control"
												style="width: 100%;" name="student_logtime[relative_login]"
												id="select_relative_login">
                    	    		<?php //if (count($list_relative) == 0 || $absent == 1) : ?>
                    	    		<option selected value=""><?php echo __('-Select login relative-') ?></option>
                    	    		<?php //endif; ?>	
                    	    		<?php foreach ($list_relative  as $relative) : ?>
                    	    		<?php if ( $relative->getRelativeId() == $login_relative_id): ?>
                    	    		<option selected
														value="<?php echo  $relative->getRelativeId() ?>"><?php echo $relative->getTitle().': '.$relative->getFullName() ?></option>
                    	    		<?php else : ?>
                    	    		<option
														value="<?php echo  $relative->getRelativeId() ?>"><?php echo $relative->getTitle().': '.$relative->getFullName() ?></option>
                    	    		<?php endif; ?>													
                    	    		<?php endforeach;?>
                    	    </select>
										</label></li>

										<li class="col-md-6 col-sm-6 col-xs-6"
											style="padding-top: 5px;"><label class="select"
											style="width: 100%"> <select class="form-control"
												style="width: 100%" name="student_logtime[member_login]"
												id="select_member_login">
                    	        <?php //if (count($list_member) == 0) : ?>
                    	        <option value=""><?php echo __('-Select login member-') ?></option>
                    	        <?php //endif; ?>	
                    	        <?php foreach ( $list_member as $member) :?>
                    	        <?php if ( $member->getId() == $login_member_id): ?>
                    	        <option selected
														value="<?php echo  $member->getId() ?>"><?php echo $member->getFullName() ?></option>
                    	        <?php else : ?>
                    	        <option
														value="<?php echo  $member->getId() ?>"><?php echo $member->getFullName() ?></option>
                    	        <?php endif; ?>								
                    	        <?php endforeach; ?>
                    	    </select>
										</label></li>
										<li class="col-md-6 col-sm-6 col-xs-6"
											style="padding-bottom: 5px;">
											<div class="input-group" style="width: 100%">
												<span class="input-group-addon"><i
													class="icon-append fa fa-clock-o"></i></span> <input
													id="login_at_<?php echo $student_id ?>"
													name="student_logtime[login_at]"
													class="time_picker form-control input-sm_<?php echo $student_id;?>_logout"
													maxlength="5" value="<?php echo $login_at ?>">
											</div>
										</li>
										<li class="col-md-6 col-sm-6 col-xs-6"
											style="padding-bottom: 5px;"><input
											name="student_logtime[note1]" type="text"
											class="form-control" style="width: 100%"
											id="note_<?php echo $student_id ?>"
											placeholder="<?php echo __('Enter note')?>"
											value="<?php echo $ps_mote ?>"></li>
									</div>
								</div>

								<div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
									<p>
										<strong><?php echo __('Attendance out')?></strong>
									</p>
									<div class="row">
										<li class="col-md-6 col-sm-6 col-xs-6"
											style="padding-top: 5px;"><label class="select"
											style="width: 100%"> <select class="form-control"
												style="width: 100%;" name="student_logtime[relative_logout]"
												id="select_relative_logout">
													<option selected value=""><?php echo __('-Select logout relative-') ?></option>
                    	    		<?php foreach ($list_relative  as $relative) : ?>
                    	    		<?php if ( $relative->getRelativeId() == $logout_relative_id): ?>
                    	    		<option selected
														value="<?php echo  $relative->getRelativeId() ?>"><?php echo $relative->getTitle().': '.$relative->getFullName() ?></option>
                    	    		<?php else : ?>
                    	    		<option
														value="<?php echo  $relative->getRelativeId() ?>"><?php echo $relative->getTitle().': '.$relative->getFullName() ?></option>
                    	    		<?php endif; ?>													
                    	    		<?php endforeach;?>
                    	    </select>
										</label></li>

										<li class="col-md-6 col-sm-6 col-xs-6"
											style="padding-top: 5px;"><label class="select"
											style="width: 100%"> <select class="form-control"
												style="width: 100%" name="student_logtime[member_logout]"
												id="select_member_login">
													<option value=""><?php echo __('-Select login member-') ?></option>
                    	        <?php foreach ( $list_member as $member) :?>
                    	        <?php if ( $member->getId() == $logout_member_id): ?>
                    	        <option selected
														value="<?php echo  $member->getId() ?>"><?php echo $member->getFullName() ?></option>
                    	        <?php else : ?>
                    	        <option
														value="<?php echo  $member->getId() ?>"><?php echo $member->getFullName() ?></option>
                    	        <?php endif; ?>								
                    	        <?php endforeach; ?>
                    	    </select>
										</label></li>
										<li class="col-md-6 col-sm-6 col-xs-6"
											style="padding-bottom: 5px;">
											<div class="input-group" style="width: 100%">
												<span class="input-group-addon"><i
													class="icon-append fa fa-clock-o"></i></span> <input
													id="login_at" name="student_logtime[logout_at]"
													class="time_picker form-control input-sm_<?php echo $student_id;?>_logout"
													maxlength="5" value="<?php echo $logout_at ?>">
											</div>
										</li>
										<li class="col-md-6 col-sm-6 col-xs-6"
											style="padding-bottom: 5px;"><input
											name="student_logtime[note2]" type="text"
											class="form-control" style="width: 100%"
											id="note2_<?php echo $student_id ?>"
											placeholder="<?php echo __('Enter note')?>"
											value="<?php echo $ps_mote ?>"></li>
									</div>
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12"
									id="block_student_service_<?php echo $student_id;?>">
									<p>
										<strong><?php echo __('Attendance service')?></strong>
									</p>
            		<?php $array = array();?>
            		<?php foreach ($list_service as $key => $service): ?>
            		<?php

if ($saturday != 'Saturday') {
																if ($service->getEnableSaturday () == 0) {
																	?>
            			<div
										class="checkbox col-md-3 col-lg-3 col-xs-4 col-xs-6 <?php if ($service->ss_id > 0) echo 'ss_id_'.$student_id;?>"
										style="margin-top: 10px !important;">
										<label> <input class="checkbox style-0" <?php echo $disable;?>
											type="checkbox" name="student_logtime[student_service][]"
											value="<?php echo $service->id;?>"
											<?php if ($service->ssd_id > 0) echo 'checked=checked';?>
											style="position: absolute !important" /> <span><?php echo $service->title?></span>
										</label>
									</div>
            			<?php }}else{?>
            			<div
										class="checkbox col-md-3 col-lg-3 col-xs-4 col-xs-6 <?php if ($service->ss_id > 0) echo 'ss_id_'.$student_id;?>"
										style="margin-top: 10px !important;">
										<label> <input class="checkbox style-0" <?php echo $disable;?>
											type="checkbox" name="student_logtime[student_service][]"
											value="<?php echo $service->id;?>"
											<?php if ($service->ssd_id > 0) echo 'checked=checked';?> />
											<span><?php echo $service->title?></span>
										</label>
									</div>
            			<?php }?>
            		<?php endforeach; ?>
                </div>
							</div>

						</div>
					</div>
				</div>

			</div>
		</div>

		<div class="modal-footer">
			<button type="submit" class="btn btn-default btn-success">
				<i class="fa-fw fa fa-save"></i>&nbsp;<?php echo __('Save');?></button>
			<button type="button" class="btn btn-default" data-dismiss="modal">
				<i class="fa-fw fa fa-ban"></i><?php echo __('Close');?></button>
		</div>
	</div>
</form>