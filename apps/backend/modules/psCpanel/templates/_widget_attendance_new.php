<div class="jarviswidget jarviswidget-sortable" id="wid-id-0" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-refreshbutton="true" role="widget">
	<header role="heading">
		<span class="widget-icon"><i class="fa fa-clock-o faa-burst animated"></i></span>
		<h2><?php echo __('Statistics attendance day');?> <div class="label label-success"><?php echo PsDateTime::psTimetoDate(null, "d-m-Y");?></div></h2>
	</header>
	<div>
		<div class="widget-body no-padding">
			<div class="table-responsive no-margin custom-scroll" style="height: 400px; overflow-y: scroll;">				
				<table class="table table-bordered table-condensed">
                  <thead>
                      <tr>
                        <th style="width:auto;padding-top:10px!important;padding-bottom:10px!important;"><?php echo __('Class name') ?></th>
                        <th class="text-center" style="width:10%;padding-top:10px!important;padding-bottom:10px!important;"><?php echo __('Total student') ?></th>
						<th class="text-center" style="width:15%;padding-top:10px!important;padding-bottom:10px!important;"><?php echo __('Login student count') ?> </th>
						<th class="text-center" style="width:15%;padding-top:10px!important;padding-bottom:10px!important;">
    						<?php echo __('Not login student count');?>						
    						<!--  <ul class="list-inline">
                            	<li class="text-center">Tổng</li>
                            	<li class="text-center">Có phép</li>
                            	<li class="text-center">Ko phép</li>
                            </ul>-->						
						</th>
						<th class="text-center" style="width:10%;padding-top:10px!important;padding-bottom:10px!important;"><?php echo __('Check out') ?></th>
                      </tr>
                  </thead>
                  <tbody>
                  <?php
                    foreach ( $workplaces as $workplace ) :
						$total_active_student_workplace = 0;
						$total_number_login = 0;
						$total_logout_sum   = 0;
						$list_my_class = Doctrine::getTable ( 'MyClass' )->getAttendancesOfClassByPsWorkplace ( $workplace->getId (),time() );
					?>
                      <tr>
                        <td colspan="5"><strong><a><?php echo $workplace->getTitle()?></a></strong></td>
                      </tr>
                      <?php foreach($list_my_class as $my_class): ?>
                      <tr>
                        <td style="padding-left: 20px!important;"><?php echo $my_class->getName() ?></td>
                        <td class="text-center" style="vertical-align: middle;">
                        	<strong>
							<?php
							echo $total_active_student = $my_class->getNumberStudentClassActivitie();
							$total_active_student_workplace = $total_active_student_workplace + $total_active_student;
							?>
							</strong>
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
					    <?php
					    $number_login = (int)$my_class->getLoginSum();
						$percentage_login = ($total_active_student != 0) ? ($number_login * 100) / $total_active_student : 0;
						$total_number_login = $total_number_login + $number_login;
						?>
						<?php include_partial('psCpanel/box_number_attendance_login_new', array('number_login' => $number_login, 'percentage_login' => $percentage_login)) ?>																			
						</td>
                        <td class="text-center" style="vertical-align: middle;">
							<?php
							      $absent = $total_active_student - $number_login;
							      $percentage_absent = ($total_active_student != 0) ? ($absent * 100) / $total_active_student : 0;
							?>
							<?php include_partial('psCpanel/box_number_attendance_absent_new', array('absent' => $absent, 'percentage_absent' => $percentage_absent)) ?>
						</td>
                        <td class="text-center" style="vertical-align: middle;">
                        <?php
                            $total_logout_sum = $total_logout_sum + (int)$my_class->getLogoutSum();
                            echo (int)$my_class->getLogoutSum();
                        ?>
                        </td>
                      </tr>
                      <?php endforeach ?>                      
                      <tr style="font-weight: bold;">
                        <td class="text-right" style="vertical-align: middle;"><b><?php echo __('Total')?></b></td>
                        <td class="text-center" style="vertical-align: middle;"><?php echo $total_active_student_workplace;?></td>
                        <td class="text-center" style="vertical-align: middle;">
                        	<?php
							 $total_percentage_login = ($total_active_student_workplace != 0) ? ($total_number_login * 100) / $total_active_student_workplace : 0;
							 include_partial ( 'psCpanel/box_number_attendance_login_footer', array ('number_login' => $total_number_login,'percentage_login' => $total_percentage_login ) );
							?>
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                        	<?php
								$total_absent = $total_active_student_workplace - $total_number_login;
								$total_percentage_absent = ($total_active_student_workplace != 0) ? ($total_absent * 100) / $total_active_student_workplace : 0;
								
								include_partial('psCpanel/box_number_attendance_absent_footer', array('absent' => $total_absent, 'percentage_absent' => $total_percentage_absent));
							?>
                        </td>
                        <td class="text-center" style="vertical-align: middle;"><?php echo $total_logout_sum;?></td>
                      </tr>                      
                  <?php endforeach ?>                  
                  </tbody>
                </table>	
			</div>
		</div>
	</div>
</div>
