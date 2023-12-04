<td class="sf_admin_text sf_admin_list_td_student_name"
	style="white-space: nowrap">
<?php
if ($ps_student_growths->getImage () != '') {
	$path_file = '/media-web/' . PreSchool::MEDIA_TYPE_STUDENT . '/' . $ps_student_growths->getSchoolCode () . '/' . $ps_student_growths->getYearData () . '/' . $ps_student_growths->getImage ();

	$path_file_root = '/media-web/root/' . PreSchool::MEDIA_TYPE_STUDENT . '/' . $ps_student_growths->getSchoolCode () . '/' . $ps_student_growths->getYearData () . '/' . $ps_student_growths->getImage ();
} else {
	$path_file_root = $path_file = '/images/no_img.png';
}
?>
  <div class="project-members" style="float: left;">
		<a href="#" rel="popover-hover" data-placement="auto" data-html="true"
			data-content="<?php echo "<img style='width: 150px;' src='$path_file_root'>";?>">
			<div style="float: left;"><?php echo get_partial('global/include/_student_img2', array('path_file' => $path_file)) ?></div>
			<div style="float: left;"><?php echo $ps_student_growths->getStudentName() ?><br />
				<code><?php echo $ps_student_growths->getStudentCode();?></code>
			</div>
		</a>
	</div>
</td>

<td class="sf_admin_text sf_admin_list_td_student_name text-center">
  <?php echo get_partial('global/field_custom/_field_birthday_student', array('value' => $ps_student_growths->getBirthday())) ?>
</td>

<td class="sf_admin_text sf_admin_list_td_height text-center">
  <?php echo get_partial('global/field_custom/_field_sex', array('value' => $ps_student_growths->getSex())) ?><hr style="margin: 6px 0px"/>
  <?php echo PreSchool::getMonthYear1($ps_student_growths->getBirthday(),$ps_student_growths->getExInputDateAt())?><br>
	<code><?php echo PreSchool::getMonthYear($ps_student_growths->getBirthday(),$ps_student_growths->getExInputDateAt()).' '.__('month')?></code>
</td>
<td class="sf_admin_date sf_admin_list_td_input_date_at text-center">
  <?php echo $ps_student_growths->getExName() ?><br /> <small>
  <?php echo false !== strtotime($ps_student_growths->getExInputDateAt()) ? format_date($ps_student_growths->getExInputDateAt(), "dd-MM-yyyy") : '&nbsp;' ?>
  </small>
</td>
<td class="sf_admin_text sf_admin_list_td_height text-center"><b><?php echo $ps_student_growths->getHeight() ?></b>
	<br>
  <?php echo get_partial('psStudentGrowths/index_height', array('value' => $ps_student_growths->getIndexHeight())) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_weight text-center"><b><?php echo $ps_student_growths->getWeight() ?></b>
	<br>
  <?php echo get_partial('psStudentGrowths/index_weight', array('value' => $ps_student_growths->getIndexWeight())) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_index_tooth">
  <?php echo $ps_student_growths->getIndexTooth() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_index_throat">
  <?php echo $ps_student_growths->getIndexThroat() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_index_eye">
  <?php echo $ps_student_growths->getIndexEye() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_index_heart">
  <?php echo $ps_student_growths->getIndexHeart() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_index_lung">
  <?php echo $ps_student_growths->getIndexLung() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_index_skin">
  <?php echo $ps_student_growths->getIndexSkin() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_people_make text-center">
  <?php echo $ps_student_growths->getUpdatedBy() ?><br />
  <?php echo false !== strtotime($ps_student_growths->getUpdatedAt()) ? format_date($ps_student_growths->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?>
</td>
<td class="sf_admin_text sf_admin_list_td_index_skin">
  
  <div class="guithongbao">
  	
<?php if ($sf_user->hasCredential(array('PS_MEDICAL_GROWTH_PUSH')) && $ps_student_growths->getId() > 0): ?>
  	<div id="ic-loading-<?php echo $ps_student_growths->getId();?>"
		style="display: none;">
		<i class="fa fa-spinner fa-2x fa-spin text-success"
			style="padding: 3px;"></i><?php echo __('Loading...')?>
    </div> <a
	class="btn btn-labeled btn-success push_notication"
	id="push_notication-<?php echo $ps_student_growths->getId() ?>"
	href="javascript:;"
	value="<?php echo $ps_student_growths->getStudentId() ?>"
	data-value="<?php echo $ps_student_growths->getId() ?>"> <span
		class="btn-label list-inline"
		id="box-<?php echo $ps_student_growths->getId() ?>">
    		<?php echo get_partial('psStudentGrowths/load_number_notication', array('value' => $ps_student_growths->getNumberPushNotication()))?>
    	</span> <span class="btn-control"> <i class="fa fa-bell"></i>
	</span>
</a>
	<?php endif;?>
  </div>
  
</td>
