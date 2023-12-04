<?php use_helper('I18N', 'Date') ?>
<div class="jarviswidget jarviswidget-sortable" id="wid-id-1"
	data-widget-colorbutton="false" data-widget-editbutton="false"
	role="widget">
	<header role="heading">
		<span class="widget-icon"><i class="fa fa-birthday-cake faa-pulse animated" style="color: #e600ffc7;"></i></span>
		<h2><?php echo __('Student birthday on');?> <div class="label label-danger"><?php echo date('m-Y');?></div></h2>
	</header>
	<div>
		<div class="widget-body no-padding">
			<div class="table-responsive no-margin custom-scroll" style="height: 400px; overflow-y: scroll;">
				<table class="table table-striped table-hover table-condensed">
					<thead>
						<th><?php echo __('Class name') ?></th>
						<th class="text-left"><?php echo __('Student') ?> </th>
						<th class="text-center"><?php echo __('Birthday') ?></th>						
						<th class="text-center"><?php echo __('Sex') ?></th>
					</thead>
					<tbody>
					<?php $key_check_wp = 0; $total_student = count($student_birthday) ?>
					
					<?php foreach ( $workplaces as $workplace ) :?>					
						<tr>
							<td colspan="4"><strong><a><?php echo $workplace->getTitle() ?></a></strong></td>
						</tr>
						<?php foreach ($student_birthday as $student):?>
						
							<?php
								if ($workplace->getId() == $student->getWpId ()):
							?>
								<tr>
									<td><i><?php echo $student->getMcName() ?></i></td>
									<td><?php echo $student->getFullName() ?><br><code><?php echo $student->getStudentCode() ?><code></td>
									<td class="text-center">
									<?php							
									echo '<div class="date">' . format_date ( $student->getBirthday(), "dd-MM-yyyy" );							
									if (format_date ( $student->getBirthday(), "MMdd" ) == date("md")) {
										echo ' <i class="fa fa-birthday-cake faa-pulse animated" style="color: #e600ffc7;"></i>';	
									}
									echo '</div>';
									?>					
									</td>
									<td class="text-center"><?php echo get_partial('global/field_custom/_field_sex', array('value' => $student->getSex())) ?></td>
								</tr>							
							<?php endif;?>
													
						<?php endforeach;?>					
					<?php endforeach;?>
				</tbody>
				</table>
			</div>
		</div>
	</div>
</div>