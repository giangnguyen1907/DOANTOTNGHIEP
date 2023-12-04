<?php
$defaultLogout = $ps_work_places ? $ps_work_places->getConfigDefaultLogout () : '';
?>
<div class="custom-scroll table-responsive">
	<table id="dt_basic" class="table table-bordered" style="width: 100%;">
		<thead>
			<tr role="row">
				<th style="width: 5%"><?php echo __('Time')?></th>
				<th style="width: 5%"><?php echo __('Td attendance')?></th>
				<th style="width: 25%"><?php echo __('Td login infomation')?></th>
				<th style="width: 25%"><?php echo __('Td logout infomation')?> - <?php echo __('Pick up time').': <code>'.($ps_work_places ? $ps_work_places->getConfigDefaultLogout() : '').'</code>'?></th>
				<th style="width: 3%"><?php echo __('Minutes late')?></th>
				<th style="width: 20%"><?php echo __('Student service diary')?></th>
				<th style="width: 7%"><?php echo __('Note')?></th>
				<th style="width: 10%"><?php echo __('Updated by')?></th>
			</tr>
		</thead>
       <?php
							// Lay so ngay cua thang
							$total_days = date ( "t", mktime ( 0, 0, 0, $month, 1, $year ) );
							?>
      <tbody>
         <?php for ($i = 1 ; $i <= $total_days; $i++):?>         
         <?php
										$date = $i . '-' . $month . '-' . $year;
										$datetime = mktime ( 0, 0, 0, $month, $i, $year );
										$localtime = localtime ( $datetime );
										$class = '';
										if ($localtime [6] == '0') {
											$class = 'bg-color-red';
										} elseif ($localtime [6] == '6') {
											$class = 'bg-color-orange';
										}
										$date_at = PsDateTime::psDatetoTime ( $date );
										$saturday = date ( 'l', $date_at );

										?>
         <?php

										$logtime_id = $login_member_id = $logout_at = $login_relative_id = $logout_relative_id = $logout_member_id = $note = $class_id = null;

										$login_at = date ( 'H:i' );

										if ($date_at <= PsDateTime::psDatetoTime ( date ( 'd-m-Y' ) )) {

											$disable = '';

											$ps_logtimes = $student_logtime [$i - 1];

											$logtime_id = $ps_logtimes->getLogtimeId ();

											$login_relative_id = $ps_logtimes->getLoginRelativeId ();

											$logout_relative_id = $ps_logtimes->getLogoutRelativeId ();

											$login_member_id = $ps_logtimes->getLoginMemberId ();

											$login_at = ($ps_logtimes->getLoginAt ()) ? date ( 'H:i', strtotime ( $ps_logtimes->getLoginAt () ) ) : date ( 'H:i' );

											$logout_at = ($ps_logtimes->getLogoutAt ()) ? date ( 'H:i', strtotime ( $ps_logtimes->getLogoutAt () ) ) : null;

											$logout_member_id = $ps_logtimes->getLogoutMemberId ();

											$note = $ps_logtimes->getNote ();

											$class_id = $ps_logtimes->getMyclassId ();

											$created_at = ($ps_logtimes->getCreatedAt ()) ? format_date ( $ps_logtimes->getCreatedAt (), "HH:mm dd/MM/yyyy" ) : null;

											$created_by = ($ps_logtimes->getCreatedBy ()) ? $ps_logtimes->getCreatedBy () : null;

											$updated_at = ($ps_logtimes->getUpdatedAt ()) ? format_date ( $ps_logtimes->getUpdatedAt (), "HH:mm dd/MM/yyyy" ) : null;

											$updated_by = ($ps_logtimes->getUpdatedBy ()) ? $ps_logtimes->getUpdatedBy () : null;

											// Lay dich vu ma hoc sinh dang ky su dung + dich vu cua lop
											$list_service = $student->getServicesDiaryByStudent ( $class_id, $date );

											// Lay dich vu ma hoc sinh dang ky su dung + dich vu goi y theo lop
											// $list_service = $student->getServicesForStudent($datetime);

											$list_member = $ps_logtimes->getPsMember () ? explode ( ",", $ps_logtimes->getPsMember () ) : array ();
										} else {
											$disable = 'disabled';
											$list_service = array ();
											$list_member = array ();
										}
										?>
       
         <tr class="sf_admin_row odd <?php echo $class;?>" role="row">

				<td>
					<div><?php echo __(date('l', $date_at)) ?></div>
					<div><?php echo date('d/m/Y', $date_at) ?></div>
				</td>
				<td class="text-center"><input style="width: 19px; height: 19px;"
					type="checkbox" <?php echo $disable?>
					name="student_logtime[<?php echo $date_at;?>][log_value]"
					<?php if ($logtime_id > 0):?> checked="checked" <?php endif?>
					value="1"
					onclick="javascript:setLogtime(<?php echo $date_at;?>,this);"></td>
				<td>
					<div class="form-inline pull-left">
						<div class="form-group">

							<select class="form-control" <?php if (!$logtime_id):?> disabled
								<?php endif?> <?php echo $disable?>
								name="student_logtime[<?php echo strtotime($date)?>][relative_login]"
								id="select_<?php echo strtotime($date)?>_relative_login"
								style="width: 170px;">
                <?php if (count($list_relative) == 0) : ?>
                <option selected value=""><?php echo __('-Select login relative-') ?></option>
                <?php endif; ?>	
                <?php foreach ($list_relative as $relative) : ?>
                <?php if ( $relative->getRelativeId() == $login_relative_id): ?>
                <option selected
									value="<?php echo  $relative->getRelativeId() ?>"><?php echo $relative->getFullName() ?></option>
                <?php else : ?>
                <option
									value="<?php echo  $relative->getRelativeId() ?>"><?php echo $relative->getFullName() ?></option>
                <?php endif?>
                <?php endforeach;?>
                </select>


						</div>
						<div class="form-group">
							<select class="form-control" <?php if (!$logtime_id):?> disabled
								<?php endif?> <?php echo $disable?>
								name="student_logtime[<?php echo strtotime($date)?>][member_login]"
								id="select_<?php echo strtotime($date)?>_member_login"
								style="width: 170px;">
                <?php if (count($list_member) == 0) : ?>
                <option selected value=""><?php echo __('-Select login member-') ?></option>
                <?php endif; ?>	
                <?php foreach ( $list_member as $member) :?>
                <?php
											$member_id = strstr ( $member, ';', true );

											$member_title = substr ( strstr ( $member, ';' ), 1 );
											?>
                <?php if ( $member_id == $login_member_id): ?>
                <option selected value="<?php echo  $member_id ?>"><?php echo $member_title ?></option>
                <?php else : ?>
                <option value="<?php echo $member_id ?>"><?php  echo $member_title ?></option>
                <?php endif; ?>								
                <?php endforeach; ?>
                </select> <i></i>

						</div>
						<div class="form-group">
							<div class="input-group ">
								<span class="input-group-addon"><i
									class="icon-append fa fa-clock-o"></i></span> <input
									<?php if (!$logtime_id):?> disabled <?php endif?>
									<?php echo $disable?>
									name="student_logtime[<?php echo $date_at;?>][login_at]"
									class="time_picker form-control input-sm_<?php echo $date_at;?>_login"
									value="<?php echo $login_at?>" style="width: 60px;">
							</div>
						</div>
					</div>
				</td>
				<td>
					<div class="form-inline pull-left">
						<div class="form-group">

							<select class="form-control" <?php if (!$logtime_id):?> disabled
								<?php endif?> <?php echo $disable?>
								name="student_logtime[<?php echo $date_at;?>][relative_logout]"
								id="select_<?php echo $date_at;?>_relative_logout"
								style="width: 170px;">
                <?php if (count($list_relative) == 0) : ?>
                <option selected value=""><?php echo __('-Select logout relative-') ?></option>
                <?php endif; ?>	
                <?php foreach ($list_relative as $relative) : ?>
                <?php if ( $relative->getRelativeId() == $logout_relative_id): ?>
                <option selected
									value="<?php echo   $relative->getRelativeId() ?>"><?php echo $relative->getFullName() ?></option>
                <?php else : ?>
                <option
									value="<?php echo  $relative->getRelativeId() ?>"><?php echo $relative->getFullName() ?></option>
                <?php endif?>
                <?php endforeach;?>
                </select>

						</div>
						<div class="form-group">
							<select class="form-control" <?php if (!$logtime_id):?> disabled
								<?php endif?> <?php echo $disable?>
								name="student_logtime[<?php echo $date_at;?>][member_logout]"
								id="select_<?php echo $date_at;?>_member_logout"
								style="width: 170px;">
                <?php if (count($list_member) == 0) : ?>
                <option selected value=""><?php echo __('-Select logout member-') ?></option>
                <?php endif; ?>	
                <?php foreach ( $list_member as $member) :?>
                <?php
											$member_id = strstr ( $member, ';', true );

											$member_title = substr ( strstr ( $member, ';' ), 1 );
											?>
                <?php if ( $member_id == $logout_member_id): ?>
                <option selected value="<?php echo  $member_id ?>"><?php echo $member_title ?></option>
                <?php else : ?>
                <option value="<?php echo $member_id ?>"><?php  echo $member_title ?></option>
                <?php endif; ?>								
                <?php endforeach; ?>
                </select> <i></i>

						</div>
						<div class="form-group">
							<div class="input-group ">
								<span class="input-group-addon"><i
									class="icon-append fa fa-clock-o"></i></span> <input
									<?php if (!$logtime_id):?> disabled <?php endif?>
									<?php echo $disable?>
									name="student_logtime[<?php echo $date_at;?>][logout_at]"
									class="time_picker form-control input-sm_<?php echo $date_at;?>_logout"
									value="<?php echo $logout_at?>" style="width: 60px;">
							</div>
						</div>
					</div>
				</td>
				<td class="text-center">
         		<?php
										if ($logout_at != '') {

											$first_date = strtotime ( $logout_at );
											$second_date = strtotime ( $defaultLogout );
											$datediff = $first_date - $second_date;

											if ($datediff > 0) {
												echo floor ( $datediff / (60) );
											} else {
												echo 0;
											}
										}
										?>
         	</td>
				<td>
					<div class="checkbox_list form-inline"
						id="block_student_service_<?php echo $date_at;?>">	            
		            <?php foreach ($list_service as $key => $service): ?>
            		<?php

if ($saturday != 'Saturday') {
												if ($service->getEnableSaturday () == 0) {
													?>
            			<div
							class="checkbox <?php if ($service->ss_id > 0) echo 'ss_id_'.$date_at;?>"
							style="width: 49%">
							<label> <input class="checkbox style-0" <?php echo $disable;?>
								type="checkbox"
								name="student_logtime[<?php echo $date_at;?>][student_service][]"
								value="<?php echo $service->id;?>"
								<?php if ($service->ssd_id > 0) echo 'checked=checked';?>> <span><?php echo $service->title?></span>
							</label>
						</div>
            			<?php }}else{?>
            			<div
							class="checkbox <?php if ($service->ss_id > 0) echo 'ss_id_'.$date_at;?>"
							style="width: 49%">
							<label> <input class="checkbox style-0" <?php echo $disable;?>
								type="checkbox"
								name="student_logtime[<?php echo $date_at;?>][student_service][]"
								value="<?php echo $service->id;?>"
								<?php if ($service->ssd_id > 0) echo 'checked=checked';?> /> <span><?php echo $service->title?></span>
							</label>
						</div>
            			<?php }?>
            		<?php endforeach; ?>	            
	            </div>
				</td>
				<td><input
					class="form-control input-sm_<?php echo $date_at;?>_logout"
					<?php if (!$logtime_id):?> disabled <?php endif?>
					<?php echo $disable?> type="text"
					name="student_logtime[<?php echo $date_at;?>][note]"
					value="<?php echo $note;?>"> <input type="hidden"
					name="student_logtime[<?php echo $date_at;?>][student_id]"
					value="<?php echo $student->getId() ;?>" /></td>
				<td>
	         	<?php echo $created_at;?><br />
	         	<?php echo $created_by;?><br />
	         	
	         	<?php echo $updated_at?><br />
	         	<?php echo $updated_by;?>
	         </td>
			</tr>               
         <?php endfor;?>
         </tbody>
	</table>
</div>