<td class="sf_admin_text sf_admin_list_td_title">
  <?php
		if (! $ps_app_permission->getPsAppRoot ()) {
			echo '<strong style="text-transform:uppercase;">' . $ps_app_permission->getTitle () . '</strong>';
		} else {
			echo '<strong style="padding-left: 30px;">' . $ps_app_permission->getTitle () . '</strong>';
		}
		?>
</td>
<td class="sf_admin_text sf_admin_list_td_app_code">
  <?php echo '<strong>'.$ps_app_permission->getAppCode().'</strong>'?>
</td>
