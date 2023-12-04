
  <?php if ($pager->getNbResults()): ?>
<div class="clear" style="clear: both;"></div>
<section class="table_scroll">
	<div class="container_table custom-scroll table-responsive">
	<table id="dt_basic"
		class="table table-bordered table-striped"
		width="100%">
		<thead>
			<tr class="header hidden-sm hidden-xs">
				<?php slot('sf_admin.current_header') ?>

				<?php $name_field = 'view_img';
				$style_order = '';
				if ($name_field == 'order' || $name_field == 'iorder'):
					$style_order = 'style="width:60px;"';
				endif;
				?>
				
				<th class="sf_admin_text sf_admin_list_th_view_img" <?php echo $style_order; ?>>
				  <?php echo __('Image', array(), 'messages') ?>
				  <div><?php echo __('Image', array(), 'messages') ?></div>
				</th>
				<?php end_slot(); ?>
				<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>
				
				<?php $name_field = 'first_name';
				$style_order = '';
				if ($name_field == 'order' || $name_field == 'iorder'):
					$style_order = 'style="width:60px;"';
				endif;
				?>
				
				<th class="sf_admin_text sf_admin_list_th_first_name" <?php echo $style_order; ?>>
				  <?php echo __('First name', array(), 'messages') ?>
				  <div><?php if ('first_name' == $sort[0]): ?>
				    <?php echo link_to(__('First name', array(), 'messages'), '@ps_relatives', array('query_string' => 'sort=first_name&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
				    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
					
				  <?php else: ?>
				    <?php echo link_to(__('First name', array(), 'messages'), '@ps_relatives', array('query_string' => 'sort=first_name&sort_type=asc')) ?>
				  <?php endif; ?></div>
				</th>
				<?php end_slot(); ?>
				<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>
				
				<?php $name_field = 'last_name';
				$style_order = '';
				if ($name_field == 'order' || $name_field == 'iorder'):
					$style_order = 'style="width:60px;"';
				endif;
				?>
				
				<th class="sf_admin_text sf_admin_list_th_last_name" <?php echo $style_order; ?>>
				  <?php echo __('Last name', array(), 'messages') ?>
				  <div><?php if ('last_name' == $sort[0]): ?>
				    <?php echo link_to(__('Last name', array(), 'messages'), '@ps_relatives', array('query_string' => 'sort=last_name&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
				    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
					
				  <?php else: ?>
				    <?php echo link_to(__('Last name', array(), 'messages'), '@ps_relatives', array('query_string' => 'sort=last_name&sort_type=asc')) ?>
				  <?php endif; ?></div>
				</th>
				<?php end_slot(); ?>
				<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>
				
				<?php $name_field = 'birthday';
				$style_order = '';
				if ($name_field == 'order' || $name_field == 'iorder'):
					$style_order = 'style="width:60px;"';
				endif;
				?>
				
				<th class="sf_admin_text sf_admin_list_th_birthday" <?php echo $style_order; ?>>
				  <?php echo __('Birthday', array(), 'messages') ?>
				  <div><?php if ('birthday' == $sort[0]): ?>
				    <?php echo link_to(__('Birthday', array(), 'messages'), '@ps_relatives', array('query_string' => 'sort=birthday&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
				    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
					
				  <?php else: ?>
				    <?php echo link_to(__('Birthday', array(), 'messages'), '@ps_relatives', array('query_string' => 'sort=birthday&sort_type=asc')) ?>
				  <?php endif; ?></div>
				</th>
				<?php end_slot(); ?>
				<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>
				
				<?php $name_field = 'sex';
				$style_order = '';
				if ($name_field == 'order' || $name_field == 'iorder'):
					$style_order = 'style="width:60px;"';
				endif;
				?>
				
				<th class="sf_admin_boolean sf_admin_list_th_sex" <?php echo $style_order; ?>>
				  <?php echo __('Sex', array(), 'messages') ?>
				  <div><?php if ('sex' == $sort[0]): ?>
				    <?php echo link_to(__('Sex', array(), 'messages'), '@ps_relatives', array('query_string' => 'sort=sex&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
				    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
					
				  <?php else: ?>
				    <?php echo link_to(__('Sex', array(), 'messages'), '@ps_relatives', array('query_string' => 'sort=sex&sort_type=asc')) ?>
				  <?php endif; ?></div>
				</th>
				<?php end_slot(); ?>
				<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>
				
				<?php $name_field = 'mobile';
				$style_order = '';
				if ($name_field == 'order' || $name_field == 'iorder'):
					$style_order = 'style="width:60px;"';
				endif;
				?>
				
				<th class="sf_admin_text sf_admin_list_th_mobile" <?php echo $style_order; ?>>
				  <?php echo __('Mobile', array(), 'messages') ?>
				  <div><?php if ('mobile' == $sort[0]): ?>
				    <?php echo link_to(__('Mobile', array(), 'messages'), '@ps_relatives', array('query_string' => 'sort=mobile&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
				    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
					
				  <?php else: ?>
				    <?php echo link_to(__('Mobile', array(), 'messages'), '@ps_relatives', array('query_string' => 'sort=mobile&sort_type=asc')) ?>
				  <?php endif; ?></div>
				</th>
				<?php end_slot(); ?>
				<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>
				
				<?php $name_field = 'email';
				$style_order = '';
				if ($name_field == 'order' || $name_field == 'iorder'):
					$style_order = 'style="width:60px;"';
				endif;
				?>
				
				<th class="sf_admin_text sf_admin_list_th_email" <?php echo $style_order; ?>>
				  <?php echo __('Email', array(), 'messages') ?>
				  <div><?php if ('email' == $sort[0]): ?>
				    <?php echo link_to(__('Email', array(), 'messages'), '@ps_relatives', array('query_string' => 'sort=email&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
				    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
					
				  <?php else: ?>
				    <?php echo link_to(__('Email', array(), 'messages'), '@ps_relatives', array('query_string' => 'sort=email&sort_type=asc')) ?>
				  <?php endif; ?>
				  </div>
				</th>
				<?php end_slot(); ?>
				<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>
				
				<?php $name_field = 'username';
				$style_order = '';
				if ($name_field == 'order' || $name_field == 'iorder'):
					$style_order = 'style="width:60px;"';
				endif;
				?>
				
				<th class="sf_admin_text sf_admin_list_th_username" <?php echo $style_order; ?>>
				  <?php echo __('Username', array(), 'messages') ?>
				  <div><?php echo __('Username', array(), 'messages') ?></div>
				</th>
				<?php end_slot(); ?>
				<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>
				
				<?php $name_field = 'ps_workplace_id';
				$style_order = '';
				if ($name_field == 'order' || $name_field == 'iorder'):
					$style_order = 'style="width:60px;"';
				endif;
				?>
				
				<th class="sf_admin_foreignkey sf_admin_list_th_ps_workplace_id" <?php echo $style_order; ?>>
				  <?php echo __('Ps workplace', array(), 'messages') ?>
				  <div><?php if ('ps_workplace_id' == $sort[0]): ?>
				    <?php echo link_to(__('Ps workplace', array(), 'messages'), '@ps_relatives', array('query_string' => 'sort=ps_workplace_id&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
				    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
					
				  <?php else: ?>
				    <?php echo link_to(__('Ps workplace', array(), 'messages'), '@ps_relatives', array('query_string' => 'sort=ps_workplace_id&sort_type=asc')) ?>
				  <?php endif; ?></div>
				</th>
				<?php end_slot(); ?>
				<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>
				
				<?php $name_field = 'updated_at';
				$style_order = '';
				if ($name_field == 'order' || $name_field == 'iorder'):
					$style_order = 'style="width:60px;"';
				endif;
				?>
				
				<th class="sf_admin_date sf_admin_list_th_updated_at" <?php echo $style_order; ?>>
				  <?php echo __('Updated at', array(), 'messages') ?>
				  <div><?php if ('updated_at' == $sort[0]): ?>
				    <?php echo link_to(__('Updated at', array(), 'messages'), '@ps_relatives', array('query_string' => 'sort=updated_at&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
				    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
					
				  <?php else: ?>
				    <?php echo link_to(__('Updated at', array(), 'messages'), '@ps_relatives', array('query_string' => 'sort=updated_at&sort_type=asc')) ?>
				  <?php endif; ?></div>
				</th>
				<?php end_slot(); ?>
				<?php include_slot('sf_admin.current_header') ?>
			
				<th data-hide="phone,tablet" id="sf_admin_list_th_actions"
					class="text-center" style="width: 85px;">
					<?php echo __('Actions', array(), 'sf_admin') ?>
					<div><?php echo __('Actions', array(), 'sf_admin') ?></div></th>

				<th data-hide="phone,tablet" id="sf_admin_list_batch_actions"
					class="text-center" style="width: 31px;">
					<div><label class="checkbox-inline"> <input id="sf_admin_list_batch_checkbox"
						class="sf_admin_list_batch_checkbox checkbox style-0"
						type="checkbox" /> <span></span>
				</label></div></th>
			</tr>
			<tr class="hidden-lg hidden-md">
          <?php include_partial('psRelatives/list_th_tabular', array('sort' => $sort)) ?>
          
          <th data-hide="phone,tablet" id="sf_admin_list_th_actions"
					class="text-center" style="width: 85px;"><?php echo __('Actions', array(), 'sf_admin') ?></th>

				<th data-hide="phone,tablet" id="sf_admin_list_batch_actions"
					class="text-center" style="width: 31px;"><label
					class="checkbox-inline"> <input id="sf_admin_list_batch_checkbox"
						class="sf_admin_list_batch_checkbox checkbox style-0"
						type="checkbox" /> <span></span>
				</label></th>

			</tr>
		</thead>
		<tbody>
        <?php

			if ($sf_user->hasCredential ( array (
					'PS_STUDENT_RELATIVE_DETAIL',
					'PS_STUDENT_RELATIVE_EDIT',
					'PS_STUDENT_RELATIVE_ADD',
					'PS_STUDENT_RELATIVE_DELETE' ), false )) {
				// Cho xem chi tiet
				$link_name = 'link_detail';
			} else {
				$link_name = 'link_no_detail';
			}

			$list_td_tabular = $sf_user->hasCredential ( 'PS_SYSTEM_USER_EDIT' ) ? 'list_td_tabular_link_user' : 'list_td_tabular';

			foreach ( $pager->getResults () as $i => $relative ) :
				$odd = fmod ( ++ $i, 2 ) ? 'odd' : 'even'?>
          <tr class="sf_admin_row <?php echo $odd ?>">
          <?php include_partial('psRelatives/'.$list_td_tabular, array('relative' => $relative, 'link_name' => $link_name)) ?>
          <?php include_partial('psRelatives/list_td_actions', array('relative' => $relative, 'helper' => $helper)) ?>
          <?php include_partial('psRelatives/list_td_batch_actions', array('relative' => $relative, 'helper' => $helper)) ?>
          </tr>
        <?php endforeach; ?>
      </tbody>
	</table>
	</div>
	</section>
	<div class="clear" style="clear: both;"></div>
	<style>
	.border_top_bottom{border: 1px solid #ccc;border-left: none;border-right: none; }
	.border_top_bottom span{font-weight: 700;}
	</style>
	<div class="col-xs-12 border_top_bottom">
		<span class="text-results">
          <?php echo __('View %%from_item%% - %%to_item%% /%%nbResults%% results', array('%%from_item%%' => $pager->getFirstIndice(), '%%to_item%%' => $pager->getLastIndice(), '%%nbResults%%' => $pager->getNbResults())) ?>
        </span>
	</div>
<?php endif; ?>