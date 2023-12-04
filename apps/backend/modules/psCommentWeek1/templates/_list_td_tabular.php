<td class="sf_admin_text sf_admin_list_td_image" style="width: 50px">
  <?php echo get_partial('psCommentWeek/view_img', array('type' => 'list', 'ps_comment_week' => $ps_comment_week)) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_student_name"
	style="width: 200px">
  <?php echo $ps_comment_week->getStudentName() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_title" style="width: 200px">
  <?php echo $ps_comment_week->getTitle() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_comment">
	<div class="custom-scroll table-responsive" style="max-height: 250px;overflow-y: scroll;">
	<?php echo $ps_comment_week->getComment() ? sfOutputEscaperGetterDecorator::unescape($ps_comment_week->getComment()) : ''?>
	</div>
</td>
<td class="sf_admin_boolean sf_admin_list_td_is_activated" style="width: 80px">
	<?php if($ps_comment_week->getIsActivated() > 0){?>
	<span class="label <?php echo ($ps_comment_week->getIsActivated() == 1) ? 'label-primary': 'label-warning'; ?>"
	style="font-weight: normal;"><?php echo __(PreSchool::loadBrowseArticles()[$ps_comment_week->getIsActivated()]);?></span>
	<?php }?>
</td>
<td class="sf_admin_date sf_admin_list_td_updated_at"
	style="width: 150px">
  <?php
	if ($ps_comment_week->getCommentId () > 0) {
		echo $ps_comment_week->getUpdatedBy () . '<br/>';
		echo false !== strtotime ( $ps_comment_week->getUpdatedAt () ) ? date ( 'd/m/Y', strtotime ( $ps_comment_week->getUpdatedAt () ) ) : '&nbsp;';
	}
	?>
</td>
<style type="text/css">
	.btn-label {
    left: -8px;
  }
</style>
<td class="sf_admin_text sf_admin_list_td_number_push_notication">
	<div id="ic-loading-<?php echo $ps_comment_week->getCommentId();?>"
		style="display: none;">
		<i class="fa fa-spinner fa-2x fa-spin text-success"
			style="padding: 3px;"></i><?php echo __('Loading...')?>
    </div>
    
	<a
	class="btn btn-labeled btn-success push_notication"
	id="push_notication-<?php echo $ps_comment_week->getCommentId() ?>"
	href="javascript:;" value="<?php echo $ps_comment_week->getStudentId() ?>"
	data-value="<?php echo $ps_comment_week->getCommentId() ?>"> <span
		class="btn-label  list-inline"
		id="box-<?php echo $ps_comment_week->getCommentId() ?>">
    		<?php echo get_partial('psCommentWeek/load_number_notication', array('psCommentWeek' => $ps_comment_week))?>
    	</span> <span class="btn-control"> <i class="fa fa-bell"></i>
	</span>
</a>
</td>
