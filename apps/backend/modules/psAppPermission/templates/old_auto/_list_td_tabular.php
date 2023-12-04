<td class="sf_admin_text sf_admin_list_td_title" style="width: 170px;">
  <?php echo link_to($ps_app_permission->getTitle(), 'ps_app_permission_edit', $ps_app_permission) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_app_permission_code">
  <?php
		$ps_app_permission_list = $ps_app_permission->getPsAppPermissions ();
		foreach ( $ps_app_permission_list as $key => $obj ) {
			echo $obj->getTitle () . ' | ';
			?>
       
       <ul class="sf_admin_td_actions">
		<li class="sf_admin_action_edit"><a
			href="/quanlymamnon.vn/truongnet/backend_dev.php/psAppPermission/<?php echo $obj->getId();?>/edit">Sửa</a></li>
		<li class="sf_admin_action_delete"><a
			onclick="if (confirm('Bạn chắc chắn xóa không?')) { var f = document.createElement('form'); f.style.display = 'none'; this.parentNode.appendChild(f); f.method = 'post'; f.action = this.href;var m = document.createElement('input'); m.setAttribute('type', 'hidden'); m.setAttribute('name', 'sf_method'); m.setAttribute('value', 'delete'); f.appendChild(m);var m = document.createElement('input'); m.setAttribute('type', 'hidden'); m.setAttribute('name', '_csrf_token'); m.setAttribute('value', 'ef0259a4c624c59092f9b4903ae29221'); f.appendChild(m);f.submit(); };return false;"
			href="/quanlymamnon.vn/truongnet/backend_dev.php/psAppPermission/<?php echo $obj->getId();?>">Xóa</a></li>
	</ul>       
       <?php
		}
		?>
</td>
