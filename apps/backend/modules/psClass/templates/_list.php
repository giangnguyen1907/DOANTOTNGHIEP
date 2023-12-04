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

<?php $name_field = 'id';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder'):
	$style_order = 'style="width:60px;"';
endif;
?>

<th class="sf_admin_text sf_admin_list_th_id" <?php echo $style_order; ?>>
<?php echo __('ID', array(), 'messages')?>
<div>
  <?php if ('id' == $sort[0]): ?>
    <?php echo link_to(__('ID', array(), 'messages'), '@ps_class', array('query_string' => 'sort=id&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('ID', array(), 'messages'), '@ps_class', array('query_string' => 'sort=id&sort_type=asc')) ?>
  <?php endif; ?></div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php $name_field = 'code';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder'):
	$style_order = 'style="width:60px;"';
endif;
?>

<th class="sf_admin_text sf_admin_list_th_code" <?php echo $style_order; ?>>
<?php echo __('Code', array(), 'messages')?>
<div>
  <?php if ('code' == $sort[0]): ?>
    <?php echo link_to(__('Code', array(), 'messages'), '@ps_class', array('query_string' => 'sort=code&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Code', array(), 'messages'), '@ps_class', array('query_string' => 'sort=code&sort_type=asc')) ?>
  <?php endif; ?></div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php $name_field = 'name';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder'):
	$style_order = 'style="width:60px;"';
endif;
?>

<th class="sf_admin_text sf_admin_list_th_name" <?php echo $style_order; ?>>
<?php echo __('Name', array(), 'messages')?>
<div>
  <?php if ('name' == $sort[0]): ?>
    <?php echo link_to(__('Name', array(), 'messages'), '@ps_class', array('query_string' => 'sort=name&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Name', array(), 'messages'), '@ps_class', array('query_string' => 'sort=name&sort_type=asc')) ?>
  <?php endif; ?></div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php $name_field = 'iorder';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder'):
	$style_order = 'style="width:60px;"';
endif;
?>

<th class="sf_admin_text sf_admin_list_th_iorder" <?php echo $style_order; ?>>
<?php echo __('Iorder', array(), 'messages')?>
<div>
  <?php if ('iorder' == $sort[0]): ?>
    <?php echo link_to(__('Iorder', array(), 'messages'), '@ps_class', array('query_string' => 'sort=iorder&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Iorder', array(), 'messages'), '@ps_class', array('query_string' => 'sort=iorder&sort_type=asc')) ?>
  <?php endif; ?></div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php $name_field = 'obj_group_title';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder'):
	$style_order = 'style="width:60px;"';
endif;
?>

<th class="sf_admin_text sf_admin_list_th_obj_group_title" <?php echo $style_order; ?>>
<?php echo __('Object group', array(), 'messages')?>
<div>
  <?php echo __('Object group', array(), 'messages') ?></div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php $name_field = 'list_field_class_room';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder'):
	$style_order = 'style="width:60px;"';
endif;
?>

<th class="sf_admin_text sf_admin_list_th_list_field_class_room" <?php echo $style_order; ?>>
<?php echo __('Class room', array(), 'messages')?>
<div>
  <?php echo __('Class room', array(), 'messages') ?></div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php $name_field = 'list_field_teacher_class';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder'):
	$style_order = 'style="width:60px;"';
endif;
?>

<th class="sf_admin_text sf_admin_list_th_list_field_teacher_class" <?php echo $style_order; ?>>
<?php echo __('Teacher class', array(), 'messages')?>
<div>
  <?php echo __('Teacher class', array(), 'messages') ?></div>
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
    <?php echo link_to(__('Note', array(), 'messages'), '@ps_class', array('query_string' => 'sort=note&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Note', array(), 'messages'), '@ps_class', array('query_string' => 'sort=note&sort_type=asc')) ?>
  <?php endif; ?></div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php $name_field = 'is_activated';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder'):
	$style_order = 'style="width:60px;"';
endif;
?>

<th class="sf_admin_boolean sf_admin_list_th_is_activated" <?php echo $style_order; ?>>
<?php echo __('Is activated', array(), 'messages')?>
<div>
  <?php if ('is_activated' == $sort[0]): ?>
    <?php echo link_to(__('Is activated', array(), 'messages'), '@ps_class', array('query_string' => 'sort=is_activated&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Is activated', array(), 'messages'), '@ps_class', array('query_string' => 'sort=is_activated&sort_type=asc')) ?>
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
<?php echo __('Updated by', array(), 'messages')?>
<div>
  <?php if ('updated_at' == $sort[0]): ?>
    <?php echo link_to(__('Updated by', array(), 'messages'), '@ps_class', array('query_string' => 'sort=updated_at&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Updated by', array(), 'messages'), '@ps_class', array('query_string' => 'sort=updated_at&sort_type=asc')) ?>
  <?php endif; ?></div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?>
			
			<th data-hide="phone,tablet" id="sf_admin_list_th_actions"
					class="text-center" style="width: 85px;"><?php echo __('Actions', array(), 'sf_admin') ?>
					<div><?php echo __('Actions', array(), 'sf_admin') ?></div></th>
			</tr>
			
			<tr class="hidden-lg hidden-md">
          <?php include_partial('psClass/list_th_tabular', array('sort' => $sort)) ?>          
          <th data-hide="phone,tablet" id="sf_admin_list_th_actions"
					class="text-center" style="width: 85px;"><?php echo __('Actions', array(), 'sf_admin') ?></th>
			</tr>
		</thead>
		<tbody>
        <?php foreach ($pager->getResults() as $i => $my_class): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?>
          <tr class="sf_admin_row <?php echo $odd ?>">
          <?php include_partial('psClass/list_td_tabular', array('my_class' => $my_class)) ?>
          <?php include_partial('psClass/list_td_actions', array('my_class' => $my_class, 'helper' => $helper)) ?>
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