<td class="sf_admin_boolean sf_admin_list_td_sex">
  <?php echo get_partial('psStudentBmi/sex', array('type' => 'list', 'ps_student_bmi' => $ps_student_bmi)) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_is_month text-center">
  <?php echo $ps_student_bmi->getIsMonth() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_min_height text-center">
  <?php echo $ps_student_bmi->getMinHeight1() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_min_height text-center">
  <?php echo $ps_student_bmi->getMinHeight() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_min_height text-center">
  <?php echo $ps_student_bmi->getMediumHeight() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_max_height text-center">
  <?php echo $ps_student_bmi->getMaxHeight() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_min_height text-center">
  <?php echo $ps_student_bmi->getMaxHeight1() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_min_height text-center">
  <?php echo $ps_student_bmi->getMinWeight1() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_min_weight text-center">
  <?php echo $ps_student_bmi->getMinWeight() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_min_height text-center">
  <?php echo $ps_student_bmi->getMediumWeight() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_max_weight text-center">
  <?php echo $ps_student_bmi->getMaxWeight() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_min_height text-center">
  <?php echo $ps_student_bmi->getMaxWeight1() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_updated_at text-center">
  <?php echo $ps_student_bmi->getUpdatedBy() ?><br />
  <?php echo false !== strtotime($ps_student_bmi->getUpdatedAt()) ? format_date($ps_student_bmi->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?>
</td>
