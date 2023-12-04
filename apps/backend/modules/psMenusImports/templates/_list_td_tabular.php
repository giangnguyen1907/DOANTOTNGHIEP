<td class="sf_admin_text sf_admin_list_td_meal_title">
  <?php echo $ps_menus_imports->getMealTitle() ?>
</td>
<td class="sf_admin_date sf_admin_list_td_date_at">
  <?php echo false !== strtotime($ps_menus_imports->getDateAt()) ? format_date($ps_menus_imports->getDateAt(), "dd-MM-yyyy") : '&nbsp;' ?>
</td>
<td class="sf_admin_text sf_admin_list_td_object_group_title">
  <?php echo $ps_menus_imports->getObjectGroupTitle() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_description">
  <p class="description-food"><?php echo $ps_menus_imports->getDescription(); ?></p>
</td>
<td class="sf_admin_text sf_admin_list_td_updated_by">
  <?php echo $ps_menus_imports->getUpdatedBy() ?><br/>
  <?php echo false !== strtotime($ps_menus_imports->getUpdatedAt()) ? format_date($ps_menus_imports->getUpdatedAt(), "HH:mm dd-MM-yyyy") : '&nbsp;' ?>
</td>

