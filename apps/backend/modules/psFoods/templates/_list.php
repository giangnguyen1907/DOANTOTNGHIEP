
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

<?php $name_field = 'file_name';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder'):
	$style_order = 'style="width:60px;"';
endif;
?>

<th class="sf_admin_text sf_admin_list_th_file_name" <?php echo $style_order; ?>>
  <?php echo __('File name', array(), 'messages')?>
  <div><?php echo __('File name', array(), 'messages') ?></div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php $name_field = 'title';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder'):
	$style_order = 'style="width:60px;"';
endif;
?>

<th class="sf_admin_text sf_admin_list_th_title" <?php echo $style_order; ?>>
  <?php echo __('Title', array(), 'messages')?>
  <div><?php if ('title' == $sort[0]): ?>
    <?php echo link_to(__('Title', array(), 'messages'), '@ps_foods', array('query_string' => 'sort=title&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Title', array(), 'messages'), '@ps_foods', array('query_string' => 'sort=title&sort_type=asc')) ?>
  <?php endif; ?></div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php $name_field = 'customer_title';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder'):
	$style_order = 'style="width:60px;"';
endif;
?>

<th class="sf_admin_text sf_admin_list_th_customer_title" <?php echo $style_order; ?>>
  <?php echo __('Customer title', array(), 'messages')?>
  <div><?php echo __('Customer title', array(), 'messages') ?></div>
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
  <div><?php if ('note' == $sort[0]): ?>
    <?php echo link_to(__('Note', array(), 'messages'), '@ps_foods', array('query_string' => 'sort=note&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Note', array(), 'messages'), '@ps_foods', array('query_string' => 'sort=note&sort_type=asc')) ?>
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
  <div><?php if ('iorder' == $sort[0]): ?>
    <?php echo link_to(__('Iorder', array(), 'messages'), '@ps_foods', array('query_string' => 'sort=iorder&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Iorder', array(), 'messages'), '@ps_foods', array('query_string' => 'sort=iorder&sort_type=asc')) ?>
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
  <div><?php if ('is_activated' == $sort[0]): ?>
    <?php echo link_to(__('Is activated', array(), 'messages'), '@ps_foods', array('query_string' => 'sort=is_activated&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Is activated', array(), 'messages'), '@ps_foods', array('query_string' => 'sort=is_activated&sort_type=asc')) ?>
  <?php endif; ?></div>
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
  <div><?php echo __('Updated by', array(), 'messages') ?></div>
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
  <div><?php if ('updated_at' == $sort[0]): ?>
    <?php echo link_to(__('Updated at', array(), 'messages'), '@ps_foods', array('query_string' => 'sort=updated_at&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Updated at', array(), 'messages'), '@ps_foods', array('query_string' => 'sort=updated_at&sort_type=asc')) ?>
  <?php endif; ?></div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?>
			
			
			<th id="sf_admin_list_th_actions" class="text-center" style="width: 57px;">
			<?php echo __('Actions', array(), 'sf_admin') ?>
			<div><?php echo __('Actions', array(), 'sf_admin') ?></div></th>

          <th id="sf_admin_list_batch_actions" class="text-center" style="width:31px;">
    			<div><label class="checkbox-inline">
    				<input id="sf_admin_list_batch_checkbox" class="sf_admin_list_batch_checkbox checkbox style-0" type="checkbox" />
    				<span></span>
    			</label>	</div>					
		  </th>
			</tr>
        <tr class="hidden-lg hidden-md">
          <?php include_partial('psFoods/list_th_tabular', array('sort' => $sort)) ?>
          
          <th id="sf_admin_list_th_actions" class="text-center" style="width: 57px;"><?php echo __('Actions', array(), 'sf_admin') ?></th>

          <th id="sf_admin_list_batch_actions" class="text-center" style="width:31px;">
    			<label class="checkbox-inline">
    				<input id="sf_admin_list_batch_checkbox" class="sf_admin_list_batch_checkbox checkbox style-0" type="checkbox" />
    				<span></span>
    			</label>						
		  </th>

        </tr>
      </thead>
      <tbody>
        <?php foreach ($pager->getResults() as $i => $ps_foods): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?>
          <tr class="sf_admin_row <?php echo $odd ?>">
			
			            <?php include_partial('psFoods/list_td_tabular', array('ps_foods' => $ps_foods)) ?>
						            <?php include_partial('psFoods/list_td_actions', array('ps_foods' => $ps_foods, 'helper' => $helper)) ?>
			                        <?php include_partial('psFoods/list_td_batch_actions', array('ps_foods' => $ps_foods, 'helper' => $helper)) ?>
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