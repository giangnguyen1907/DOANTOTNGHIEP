<td class="sf_admin_text sf_admin_list_td_view_img">
  <?php
		if ($relative->getImage () != '') {
			// echo image_tag('/pschool/'.$relative->getSchoolCode().'/relative/thumb/'.$relative->getImage(), array('style' => 'max-width:45px;text-align:center;'));
			$path_file = '/media-web/02/' . $relative->getSchoolCode () . '/' . $relative->getYearData () . '/' . $relative->getImage ();
			echo '<img style="max-width: 45px; text-align: center;" src="' . $path_file . '">';
		}
		?>
</td>

<?php include_partial('psRelatives/'.$link_name, array('relative' => $relative)) ?>

<td class="sf_admin_text sf_admin_list_td_birthday">
  <?php echo false !== strtotime($relative->getBirthday()) ? format_date($relative->getBirthday(), "dd-MM-yyyy") : '&nbsp;' ?>
</td>
<td class="sf_admin_boolean sf_admin_list_td_sex">
  <?php echo get_partial('global/field_custom/_field_sex', array('value' => $relative->getSex())) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_mobile">
  <?php echo $relative->getMobile() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_email">
  <?php echo $relative->getEmail() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_username">
<?php
if ($relative->getUserId () > 0)
	echo link_to ( $relative->getUsername (), '@sf_guard_user_edit?id=' . $relative->getUserId (), array (
			'data-original-title' => 'Edit user relative',
			'rel' => 'tooltip',
			'target' => '_blank' ) );
else {
	// Add new account
	echo link_to ( '<i class="fa fa-user-plus txt-color-green"></i> ', '@sf_guard_user_new', array (
			'data-original-title' => __ ( 'New user relative' ),
			'rel' => 'tooltip',
			'target' => '_blank',
			'data-placement' => "bottom",
			'class' => 'btn btn-xs btn-default btn-add-td-action',
			'query_string' => 'utype=R&mid=' . $relative->getId () ) );
}
?>
</td>
<td class="sf_admin_text sf_admin_list_td_username">
  <?php echo $relative->getWorkplaceName(); ?>
</td>
<td class="sf_admin_date sf_admin_list_td_updated_at">
<?php echo $relative->getUpdatedBy() ?><br />
  <?php echo false !== strtotime($relative->getUpdatedAt()) ? format_date($relative->getUpdatedAt(), "HH:mm dd-MM-yyyy") : '&nbsp;' ?>
</td>
