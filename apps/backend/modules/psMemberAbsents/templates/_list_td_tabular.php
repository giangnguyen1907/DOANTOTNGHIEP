<td class="sf_admin_foreignkey sf_admin_list_td_member_id">
  <?php echo $ps_member_absents->getTeacherName() ?>
</td>
<td class="sf_admin_date sf_admin_list_td_absent_at text-center">
  <?php echo false !== strtotime($ps_member_absents->getAbsentAt()) ? format_date($ps_member_absents->getAbsentAt(), "dd/MM/yyyy") : '&nbsp;' ?>
</td>
<td class="sf_admin_text sf_admin_list_td_absent_type text-center">
  <?php echo get_partial('psMemberAbsents/index_type', array('type' => 'list', 'ps_member_absents' => $ps_member_absents)) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_description">
  <?php echo $ps_member_absents->getDescription() ?>
</td>
<td class="sf_admin_date sf_admin_list_td_updated_at text-center">
  <?php echo $ps_member_absents->getUpdatedBy() ?><br />
  <?php echo false !== strtotime($ps_member_absents->getUpdatedAt()) ? format_date($ps_member_absents->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?>
</td>
