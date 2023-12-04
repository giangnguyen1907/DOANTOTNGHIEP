
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

<?php $name_field = 'meal_title';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder'):
	$style_order = 'style="width:60px;"';
endif;
?>

<th class="sf_admin_text sf_admin_list_th_meal_title" <?php echo $style_order; ?>>
<?php echo __('Meal title', array(), 'messages')?>
<div>
  <?php echo __('Meal title', array(), 'messages') ?></div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php $name_field = 'food_title';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder'):
	$style_order = 'style="width:60px;"';
endif;
?>

<th class="sf_admin_text sf_admin_list_th_food_title" <?php echo $style_order; ?>>
<?php echo __('Food title', array(), 'messages')?>
<div>
  <?php echo __('Food title', array(), 'messages') ?></div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php $name_field = 'date_at';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder'):
	$style_order = 'style="width:60px;"';
endif;
?>

<th class="sf_admin_date sf_admin_list_th_date_at" <?php echo $style_order; ?>>
<?php echo __('Date at', array(), 'messages')?>
<div>
  <?php if ('date_at' == $sort[0]): ?>
    <?php echo link_to(__('Date at', array(), 'messages'), '@ps_menus', array('query_string' => 'sort=date_at&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Date at', array(), 'messages'), '@ps_menus', array('query_string' => 'sort=date_at&sort_type=asc')) ?>
  <?php endif; ?></div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php $name_field = 'note';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder'):
	$style_order = 'style="width:60px;"';
endif;
?>

<th class="sf_admin_text sf_admin_list_th_note" <?php echo $style_order; ?>>
<?php echo __('Note', array(), 'messages')?>
<div>
  <?php if ('note' == $sort[0]): ?>
    <?php echo link_to(__('Note', array(), 'messages'), '@ps_menus', array('query_string' => 'sort=note&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Note', array(), 'messages'), '@ps_menus', array('query_string' => 'sort=note&sort_type=asc')) ?>
  <?php endif; ?></div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php $name_field = 'object_group_title';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder'):
	$style_order = 'style="width:60px;"';
endif;
?>

<th class="sf_admin_text sf_admin_list_th_object_group_title" <?php echo $style_order; ?>>
<?php echo __('Object group title', array(), 'messages')?>
<div>
  <?php echo __('Object group title', array(), 'messages') ?></div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php $name_field = 'updated_by';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder'):
	$style_order = 'style="width:60px;"';
endif;
?>

<th class="sf_admin_text sf_admin_list_th_updated_by" <?php echo $style_order; ?>>
<?php echo __('Updated by', array(), 'messages')?>
<div>
  <?php echo __('Updated by', array(), 'messages') ?></div>
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
<?php echo __('Updated at', array(), 'messages')?>
<div>
  <?php if ('updated_at' == $sort[0]): ?>
    <?php echo link_to(__('Updated at', array(), 'messages'), '@ps_menus', array('query_string' => 'sort=updated_at&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Updated at', array(), 'messages'), '@ps_menus', array('query_string' => 'sort=updated_at&sort_type=asc')) ?>
  <?php endif; ?></div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?>
			
			<th data-hide="phone,tablet" id="sf_admin_list_th_actions"
				class="text-center" style="width: 85px;"><?php echo __('Actions', array(), 'sf_admin') ?>
				<div><?php echo __('Actions', array(), 'sf_admin') ?></div></th>

			<th data-hide="phone,tablet" id="sf_admin_list_batch_actions"
				class="text-center" style="width: 31px;"><div><label
				class="checkbox-inline"> <input id="sf_admin_list_batch_checkbox"
					class="sf_admin_list_batch_checkbox checkbox style-0"
					type="checkbox" /> <span></span>
			</label></div></th>
			</tr>
		<tr class="hidden-lg hidden-md">
          <?php include_partial('psMenus/list_th_tabular', array('sort' => $sort)) ?>
          
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
        <?php foreach ($pager->getResults() as $i => $ps_menus): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?>
          <tr class="sf_admin_row <?php echo $odd ?>">
			
			            <?php include_partial('psMenus/list_td_tabular', array('ps_menus' => $ps_menus)) ?>
						            <?php include_partial('psMenus/list_td_actions', array('ps_menus' => $ps_menus, 'helper' => $helper)) ?>
			                        <?php include_partial('psMenus/list_td_batch_actions', array('ps_menus' => $ps_menus, 'helper' => $helper)) ?>
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