<?php use_helper('I18N', 'Date')?>
<?php include_partial('psRelatives/assets')?>
<?php
$active_app = 0;
$logging_now = 0;
$username = 0;
$user_lock = 0;
$array_st_active = $array_st_logging = $array_st_user = $array_st_lock = array();

//print_r($array_class);die;
//$active_app = count(Doctrine::getTable ( 'Relative' )->getRelativeActiveAccount2 ( $ps_customer_id, $array_class, PreSchool::ACTIVE ));

foreach ( $filter_member_statistic as $relative ) {
    
    $student_id = $relative->getStudentId();
    
    if ($relative->getIsActive () != PreSchool::CUSTOMER_LOCK) {
        if ($relative->getApiToken () != '') {
            //$logging_now ++;
            array_push($array_st_logging,$student_id);
        }
        if ($relative->getAppDeviceId () != '') {
            //$active_app ++;
            array_push($array_st_active,$student_id);
        }
    }else{
        array_push($array_st_lock,$student_id);
    }
	if ($relative->getUsername () != '') {
		//$username ++;
		array_push($array_st_user,$student_id);
	}
}

$active_app = count(array_unique($array_st_active));
$username = count(array_unique($array_st_user));
$logging_now = count(array_unique($array_st_logging));
$user_lock = count(array_unique($array_st_lock));

$accountRelative = Doctrine::getTable ( 'sfGuardUser' )->checkAccountRelativeByStudentStop ( $ps_customer_id, $ps_workplace_id );
$accountRelationship = Doctrine::getTable ( 'sfGuardUser' )->checkAccountRelativeNotJoinStudent ( $ps_customer_id, $ps_workplace_id );
$count = count($filter_member_statistic);

?>

<section id="widget-grid">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

			<div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-grid="false" data-widget-collapsed="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false" data-widget-togglebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-table"></i></span>
					<h2><?php echo __('Statistic account relatives by student') ?></h2>
				</header>

				<div>
					<div class="widget-body no-padding">
						<div class="dt-toolbar">
							<div class="col-xs-12 col-sm-12">
            			    <?php include_partial('psRelatives/member_statistic_filter', array('formFilter' => $formFilter, 'helper' => $helper)) ?>
            			    
            			  </div>
						</div>
						<form id="frm_batch" class="form-horizontal"
							action="@ps_relative_statistic" method="post">
							<input class="ps_customer_id hidden"
								value="<?php echo $ps_customer_id;?>" /> <input
								class="school_year_id hidden"
								value="<?php echo $school_year_id;?>" /> <input
								class="ps_class_id hidden" value="<?php echo $ps_class_id;?>" />
							<input class="keywords hidden" value="<?php echo $keywords;?>" />

							<div id="datatable_fixed_column_wrapper"
								class="dataTables_wrapper form-inline no-footer no-padding">
								<div class="custom-scroll table-responsive">
    							<?php if ($ps_class_id > 0) :?>
    							
    							<?php $my_class = Doctrine::getTable('MyClass')->findOneById($ps_class_id); ?>
            					<table id="dt_basic"
										class="table table-striped table-bordered table-hover no-footer no-padding"
										width="100%">
										<thead>
											<tr>
												<th class="text-center"><?php echo __('Class') ?></th>
												<th class="text-center"><?php echo __('Total relatives') ?></th>
												<th class="text-center"><?php echo __('Granted') ?></th>
												<th class="text-center"><?php echo __('Activated app') ?></th>
												<th class="text-center"><?php echo __('Online') ?></th>
												<th class="text-center"><?php echo __('Lock') ?></th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td class="text-center"><?php echo $my_class->getName(); ?></td>
												<td class="text-center"><?php echo $count; ?></td>
												<td class="text-center"><?php echo $username; ?></td>
												<td class="text-center"><?php echo $active_app; ?></td>
												<td class="text-center"><?php echo $logging_now; ?></td>
												<td class="text-center"><?php echo $user_lock; ?></td>
											</tr>
										</tbody>
									</table>
    							<?php elseif(count($class_list) > 0):?>
    							
    							<div class="text-center"
										style="padding: 20px 0px; clear: both">
										<button type="button" class="btn btn-labeled btn-success">
											<span class="btn-label">
                                     <?php echo __('Granted') ?>
                                     </span>
                                     <?php echo $username; ?>
                                    </button>
										<button type="button" class="btn btn-labeled btn-info">
											<span class="btn-label">
                                     <?php echo __('Activated app') ?>
                                     </span>
                                     <?php echo $active_app; ?>
                                    </button>
									<button type="button" class="btn btn-labeled btn-danger">
											<span class="btn-label">
                                      <?php echo __('Online') ?>
                                     </span>
                                     <?php echo $logging_now; ?>
                                    </button>
                                    <button type="button" class="btn btn-labeled btn-danger">
											<span class="btn-label">
                                      <?php echo __('Lock') ?>
                                     </span>
                                     <?php echo $user_lock; ?>
                                    </button>
									</div>

								<?php endif;?>
    							
            				</div>
							</div>
						</form>
					</div>
				</div>
			</div>

		</article>
		
		<?php if((count($accountRelative) + count($accountRelationship)) > 0){ ?>
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    		<div class="jarviswidget" id="wid-id-2"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-grid="false" data-widget-collapsed="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false" data-widget-togglebutton="false">
	    		<header>
					<span class="widget-icon"><i class="fa fa-table"></i></span>
					<h2><?php echo __('List account relatives by student stop') ?></h2>
				</header>
				<div>
				<div class="widget-body no-padding">
						<div id="datatable_fixed_column_wrapper"
							class="dataTables_wrapper form-inline no-footer no-padding">
							<div class="custom-scroll table-responsive"
								style="max-height: 400px; overflow-y: scroll;">
			    		<table id="dt_basic" class="table table-striped table-bordered table-hover no-footer no-padding"width="100%">
							<thead>
								<tr>
									<th class="text-center"><?php echo __('Relatives') ?></th>
									<th class="text-center"><?php echo __('Username') ?></th>
									<th class="text-center"><?php echo __('Student name') ?></th>
									<th class="text-center"><?php echo __('Birthday') ?></th>
									<th class="text-center"><?php echo __('Class') ?></th>
								</tr>
							</thead>
							<tbody>
							<?php foreach ($accountRelative as $relative){?>
			    				<tr>
			    					<td><?php echo $relative->getFirstName().' '.$relative->getLastName()?></td>
			    					<td><?php 
			    					if ($sf_user->hasCredential ( 'PS_STUDENT_RELATIVE_EDIT' )) {
			    						echo link_to ( $relative->getUsername (), '@sf_guard_user_edit?id=' . $relative->getId () );
			    					} else {
			    						
			    						echo $relative->getUsername ();
			    					}
			    					?></td>
			    					<td><?php echo $relative->getStudentName().' ( '.$relative->getStudentCode().' ) ';?></td>
			    					<td><?php echo date('d-m-Y',strtotime($relative->getBirthday()));?></td>	
			    					<td><?php echo $relative->getClassName();?></td>		
			    				</tr>
			    			<?php }?>
			    			<?php foreach ($accountRelationship as $relationship){?>
			    				<tr>
			    					<td><?php echo $relationship->getFirstName().' '.$relationship->getLastName()?></td>
			    					<td><?php 
			    					if ($sf_user->hasCredential ( 'PS_STUDENT_RELATIVE_EDIT' )) {
			    						echo link_to ( $relationship->getUsername (), '@sf_guard_user_edit?id=' . $relationship->getId () );
			    					} else {
			    						
			    						echo $relationship->getUsername ();
			    					}
			    					?></td>
			    					<td><?php echo $relationship->getStudentName() ? $relationship->getStudentName().' ( '.$relationship->getStudentCode().' ) ' : __('Not student');?></td>
			    					<td><?php echo $relationship->getBirthday() ? date('d-m-Y',strtotime($relationship->getBirthday())) : '';?></td>	
			    					<td><?php echo $relationship->getClassName() ? $relationship->getClassName():'';?></td>		
			    				</tr>
			    			<?php }?>
			    			</tbody>
			    		</table>
	    			</div>
	    			</div>
	    		</div>
	    		</div>
    		</div>
    	</article>
    	<?php }?>
		
		<?php if($count):?>
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

			<div class="jarviswidget" id="wid-id-2"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-grid="false" data-widget-collapsed="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false" data-widget-togglebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-table"></i></span>
					<h2><?php echo __('List account relatives') ?></h2>
				</header>

				<div>
					<div class="widget-body no-padding">
						<div id="datatable_fixed_column_wrapper"
							class="dataTables_wrapper form-inline no-footer no-padding">
							<div class="custom-scroll table-responsive"
								style="max-height: 400px; overflow-y: scroll;">
								<table id="dt_basic"
									class="table table-striped table-bordered table-hover no-footer no-padding"
									width="100%">
									<thead>
										<tr>
											<th class="text-center"><?php echo __('Image') ?></th>
											<th class="text-center"><?php echo __('First name') ?></th>
											<th class="text-center"><?php echo __('Last name') ?></th>
											<th class="text-center"><?php echo __('Sex') ?></th>
											<th class="text-center"><?php echo __('Mobile') ?></th>
											<th class="text-center"><?php echo __('Email') ?></th>
											<th class="text-center"><?php echo __('Student') ?></th>
											<th class="text-center"><?php echo __('Username') ?></th>
											<th class="text-center"><?php echo __('Activated app') ?></th>
											<th class="text-center"><?php echo __('Online') ?></th>
											<th class="text-center"><?php echo __('Last Login') ?></th>

										</tr>
									</thead>
									<tbody>
            							<?php foreach ($filter_member_statistic as $relative):?>
            							
            							<tr>
											<td class="sf_admin_text sf_admin_list_td_view_img">
                                              <?php
				if ($relative->getImage () != '') {
					$path_file = '/media-web/' . PreSchool::MEDIA_TYPE_RELATIVE . '/' . $relative->getSchoolCode () . '/' . $relative->getYearData () . '/' . $relative->getImage ();
					echo '<img style="max-width: 45px; text-align: center;" src="' . $path_file . '">';
				}
				?>
                                            </td>
											<td class="sf_admin_text sf_admin_list_td_first_name"><?php echo $relative->getFirstName(); ?></td>
											<td class="sf_admin_text sf_admin_list_td_last_name"><?php echo $relative->getLastName(); ?></td>
											<td class="sf_admin_boolean sf_admin_list_td_sex">
                                              <?php echo get_partial('global/field_custom/_field_sex', array('value' => $relative->getSex())) ?>
                                            </td>
											<td class="sf_admin_text sf_admin_list_td_mobile">
                                              <?php echo $relative->getMobile() ?>
                                            </td>
											<td class="sf_admin_text sf_admin_list_td_email">
                                              <?php echo $relative->getEmail() ?>
                                            </td>
											<td class="sf_admin_text sf_admin_list_td_student">
                                              <?php

				foreach ( $students as $key => $student ) {

					if ($relative->getMemberId () == $key) {
						foreach ( $student as $s ) {
							echo $s . '<br>';
						}
					}
				}
				?>
                                            </td>
											<td>
                                			<?php
				if ($relative->getUserId () > 0) {
					if ($sf_user->hasCredential ( 'PS_STUDENT_RELATIVE_EDIT' )) {
						echo link_to ( $relative->getUsername (), '@sf_guard_user_edit?id=' . $relative->getUserId () );
					} else {

						echo $relative->getUsername ();
					}
				} else {
					if ($sf_user->hasCredential ( 'PS_STUDENT_RELATIVE_ADD' )) {
						// Add new account
						echo link_to ( '<i class="fa fa-user-plus txt-color-green"></i> ', '@sf_guard_user_new', array (
								'data-original-title' => __ ( 'New user relative' ),
								'rel' => 'tooltip',
								'target' => '_blank',
								'data-placement' => "bottom",
								'class' => 'btn btn-xs btn-default btn-add-td-action',
								'query_string' => 'utype=R&mid=' . $relative->getId () ) );
					}
				}
				?>
            								</td>
											<td class="text-center ">
            								  <?php
				if ($relative->getUserId () > 0) {
				    if ($relative->getIsActive() == 1) {
						echo "<span class='label bg-color-green' style='font-weight: normal;'> " . __ ( 'Actived' ) . "</span>";
					} elseif($relative->getIsActive() == 2) {
						echo "<span class='label bg-color-orange' style='font-weight: normal;'> " . __ ( 'Lock' ) . "</span>";
					}else{
					    echo "<span class='label bg-color-blueLight' style='font-weight: normal;'> " . __ ( 'Not Active' ) . "</span>";
					}
				}
				?>
            								</td>
											<td class="text-center ">
            								  <?php
				if ($relative->getUserId () > 0) {
					if ($relative->getApiToken () != '') {
						echo "<span class='label bg-color-teal' style='font-weight: normal;'> " . __ ( 'Online' ) . "</span>";
					}
				}
				?>
            								</td>
											<td class="text-center">
            								  <?php
				if ($relative->getUserId () > 0) {
					if ($relative->getAppDeviceId () != '') {
						echo false !== strtotime ( $relative->getTokenLastLogin () ) ? format_date ( $relative->getTokenLastLogin (), "HH:mm  dd-MM-yyyy" ) : '&nbsp;';
					}
				}
				?>
                                            </td>
										</tr>
            							<?php endforeach;?>
            						</tbody>
								</table>
							</div>


						</div>

					</div>
				</div>
			</div>

		</article>
		<?php if ($ps_class_id > 0) :?>
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="widget-body-toolbar">
				<div class="pull-right">
					<a class="btn btn-default"
						href="<?php echo url_for('ps_relatives/statistic').'/'.$school_year_id.'/'.$ps_customer_id.'/'.$ps_class_id.'/'; ?>export"
						id="btn-export"><i class="fa-fw fa fa-cloud-download"></i>&nbsp;<?php echo __('Export xls')?></a>
					<!-- <a data-target="#remoteModal" data-backdrop="static" class="btn btn-default btn-sm btn-psadmin btn-export-xls" href=" javascript:; "><i class="fa-fw fa fa-cloud-download" aria-hidden="true" title="<?php //echo __('Export xls')?>"></i><?php //echo ' ' . __(' Export xls')?></a> -->
				</div>
			</div>
		</article>
		<?php endif;?>
		<?php endif;?>
		
    </div>


	</div>
</section>
