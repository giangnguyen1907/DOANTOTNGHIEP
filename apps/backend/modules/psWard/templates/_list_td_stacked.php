<td colspan="7">
  <?php echo __('%%s_code%% - %%name%% - %%district_name%% - %%iorder%% - %%is_activated%% - %%updated_by%% - %%updated_at%%', array('%%s_code%%' => link_to($ps_ward->getSCode(), 'ps_ward_edit', $ps_ward), '%%name%%' => link_to($ps_ward->getName(), 'ps_ward_edit', $ps_ward), '%%district_name%%' => $ps_ward->getDistrictName(), '%%iorder%%' => $ps_ward->getIorder(), '%%is_activated%%' => get_partial('psWard/list_field_boolean', array('value' => $ps_ward->getIsActivated())), '%%updated_by%%' => $ps_ward->getUpdatedBy(), '%%updated_at%%' => false !== strtotime($ps_ward->getUpdatedAt()) ? format_date($ps_ward->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;'), 'messages') ?>
</td>
