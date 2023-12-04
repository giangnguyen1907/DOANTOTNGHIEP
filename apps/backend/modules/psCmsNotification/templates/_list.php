
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

<?php

$name_field = 'created_by';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder') :
	$style_order = 'style="width:60px;"';
endif;

?>

<th class="sf_admin_text sf_admin_list_th_created_by"	<?php echo $style_order; ?>>
  <?php echo __('User sent', array(), 'messages') ?>
  <div><?php echo __('User sent', array(), 'messages') ?></div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php

$name_field = 'title';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder') :
	$style_order = 'style="width:60px;"';
endif;

?>

<th class="sf_admin_text sf_admin_list_th_title"	<?php echo $style_order; ?>>
  <?php echo __('Title', array(), 'messages') ?>
  <div><?php if ('title' == $sort[0]): ?>
    <?php echo link_to(__('Title', array(), 'messages'), '@ps_cms_notifications_ps_cms_notification', array('query_string' => 'sort=title&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Title', array(), 'messages'), '@ps_cms_notifications_ps_cms_notification', array('query_string' => 'sort=title&sort_type=asc')) ?>
  <?php endif; ?></div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php

$name_field = 'date_at';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder') :
	$style_order = 'style="width:60px;"';
endif;

?>

<th class="sf_admin_text sf_admin_list_th_date_at"	<?php echo $style_order; ?>>
  <?php echo __('Date at', array(), 'messages') ?>
  <div><?php if ('date_at' == $sort[0]): ?>
    <?php echo link_to(__('Date at', array(), 'messages'), '@ps_cms_notifications_ps_cms_notification', array('query_string' => 'sort=date_at&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Date at', array(), 'messages'), '@ps_cms_notifications_ps_cms_notification', array('query_string' => 'sort=date_at&sort_type=asc')) ?>
  <?php endif; ?></div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php

$name_field = 'list_received';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder') :
	$style_order = 'style="width:60px;"';
endif;

?>

<th class="sf_admin_text sf_admin_list_th_list_received" <?php echo $style_order; ?>>
  <?php echo __('List received', array(), 'messages') ?>
  <div><?php echo __('List received', array(), 'messages') ?></div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?>
			
			<th data-hide="phone,tablet" id="sf_admin_list_th_actions"
				class="text-center" style="width: 57px;"><?php echo __('Actions', array(), 'sf_admin') ?>
				<div><?php echo __('Actions', array(), 'sf_admin') ?></div></th>

			<th data-hide="phone,tablet" id="sf_admin_list_batch_actions"
				class="text-center" style="width: 31px;"><div><label
				class="checkbox-inline"> <input id="sf_admin_list_batch_checkbox"
					class="sf_admin_list_batch_checkbox checkbox style-0"
					type="checkbox" /> <span></span>
			</label></div></th>
			</tr>
		<tr class="hidden-lg hidden-md">
          <?php include_partial('psCmsNotification/list_th_tabular', array('sort' => $sort)) ?>
          
          <th data-hide="phone,tablet" id="sf_admin_list_th_actions"
				class="text-center" style="width: 57px;"><?php echo __('Actions', array(), 'sf_admin') ?></th>

			<th data-hide="phone,tablet" id="sf_admin_list_batch_actions"
				class="text-center" style="width: 31px;"><label
				class="checkbox-inline"> <input id="sf_admin_list_batch_checkbox"
					class="sf_admin_list_batch_checkbox checkbox style-0"
					type="checkbox" /> <span></span>
			</label></th>

		</tr>
	</thead>
	<tfoot>
		<tr>
			<th colspan="6">
				<span class="text-results">
          <?php echo __('View %%from_item%% - %%to_item%% /%%nbResults%% results', array('%%from_item%%' => $pager->getFirstIndice(), '%%to_item%%' => $pager->getLastIndice(), '%%nbResults%%' => $pager->getNbResults())) ?>
          </span>
			</th>
		</tr>
	</tfoot>
	<tbody>
        <?php foreach ($pager->getResults() as $i => $ps_cms_notifications): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?>
          <tr class="sf_admin_row <?php echo $odd ?>">			
			    <?php include_partial('psCmsNotification/list_td_tabular', array('ps_cms_notifications' => $ps_cms_notifications, 'filter_value' => $filter_value)) ?>
			    <?php include_partial('psCmsNotification/list_td_actions', array('ps_cms_notifications' => $ps_cms_notifications, 'helper' => $helper, 'filter_value' => $filter_value)) ?>
			    <?php include_partial('psCmsNotification/list_td_batch_actions', array('ps_cms_notifications' => $ps_cms_notifications, 'helper' => $helper)) ?>
          </tr>
        <?php endforeach; ?>
      </tbody>
</table>
<?php endif; ?>