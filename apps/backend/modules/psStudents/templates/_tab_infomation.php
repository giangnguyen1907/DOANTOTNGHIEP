<?php $student_id = $student->getId();$student_sex = $student->getSex();?>
<ul class="nav nav-tabs">
	<li class="<?php if($tabmenu == 1) echo "active";?>"><a data-toggle="tab" href="#home"><?php echo __('Student') ?></a></li>
	<li class="<?php if($tabmenu == 4) echo "active";?> "><a data-toggle="tab" href="#menu4"><?php echo __('Attendance') ?></a></li>
	<li><a data-toggle="tab" href="#menu1"><?php echo __('Features branch') ?></a></li>
	<li><a data-toggle="tab" href="#menu2"><?php echo __('Comment week') ?></a></li>
	<li><a data-toggle="tab" href="#menu3"><?php echo __('Growths information') ?></a></li>
</ul>

<div class="tab-content">

	<div id="home" class="tab-pane fade in <?php if($tabmenu == 1) echo 'active';?>">
			
			<?php echo get_partial('psStudents/list_info_student', array('ps_month' => $ps_month, 'student'=>$student ));?>
			
          </div>

	<div id="menu4" class="tab-pane fade in <?php if ($tabmenu == 4)echo 'active';?>">
			
			<?php echo get_partial('psStudents/list_attendance', array('defaultLogout' => $defaultLogout,'ps_customer_id' => $ps_customer_id,'ps_month' => $ps_month, 'student'=>$student,'year'=>$year,'month'=>$month ));?>
			
          </div>
	<div id="menu1" class="tab-pane fade in">
          
          	<?php echo get_partial('psStudents/list_features', array('student_id'=>$student_id, 'ps_month'=>$ps_month ));?>
          	
          </div>
	<div id="menu2" class="tab-pane fade in">
          	
            <?php
												$number_month = date ( 't', strtotime ( '01-' . $ps_month ) );

												$start_week = PsDateTime::getIndexWeekOfYear ( '01-' . $ps_month );
												$end_week = PsDateTime::getIndexWeekOfYear ( $number_month . '-' . $ps_month );

												$comment_student = Doctrine::getTable ( 'PsCommentWeek' )->getCommentStudentByStudentId ( $student_id, $ps_month, $start_week, $end_week );
												?>	
            
            <div class="table-responsive">
			<table class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th style="width: 300px" class="text-center"><?php echo __('Title') ?></th>
						<th class="text-center"><?php echo __('Comment') ?></th>
						<th style="width: 100px" class="text-center"><?php echo __('Status') ?></th>
					</tr>
				</thead>
				<tbody>
                	<?php foreach ($comment_student as $comment){?>
                    <tr>
						<td><?php echo $comment->getTitle()?></td>
						<td style="white-space: pre-wrap;"><?php echo $comment->getComment()?></td>
						<td class="text-center"><span
							class="label <?php echo ($comment->getIsActivated() == 1) ? 'label-primary': 'label-warning'; ?>"
							style="font-weight: normal;"><?php echo __(PreSchool::loadBrowseArticles()[$comment->getIsActivated()]);?></span>
						</td>
					</tr>
                    <?php }?>
                </tbody>
			</table>
		</div>

	</div>
	<div id="menu3" class="tab-pane fade in">
            
			<?php echo get_partial('psStudents/list_growth', array('student_id'=>$student_id,'student_sex'=>$student_sex ));?>
			
          </div>

</div>
