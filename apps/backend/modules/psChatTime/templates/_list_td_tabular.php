<td class="sf_admin_text sf_admin_list_td_title">
  <?php echo $ps_chat_time->getTitle() ?>
</td>
<td class="sf_admin_foreignkey sf_admin_list_td_ps_customer_id">
  <?php echo $ps_chat_time->getSchoolName() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_chat_time">
 
  <?php
		echo false !== strtotime ( $ps_chat_time->getStartTime () ) ? date ( 'H:i', strtotime ( $ps_chat_time->getStartTime () ) ) : '&nbsp;'?> &rarr; <?php

echo false !== strtotime ( $ps_chat_time->getEndTime () ) ? date ( 'H:i', strtotime ( $ps_chat_time->getEndTime () ) ) : '&nbsp;'?>
</td>
<td class="sf_admin_text sf_admin_list_td_note">
  <?php echo $ps_chat_time->getNote() ?>
</td>
<td class="sf_admin_boolean sf_admin_list_td_is_activated">
  <?php echo get_partial('psChatTime/list_field_boolean', array('value' => $ps_chat_time->getIsActivated())) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_updated_by">
  <?php echo $ps_chat_time->getUpdatedBy() ?>
</td>
