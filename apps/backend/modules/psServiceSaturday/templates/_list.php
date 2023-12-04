
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

$name_field = 'student_id';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder') :
	$style_order = 'style="width:60px;"';
endif;

?>

<th class="sf_admin_foreignkey sf_admin_list_th_student_id"	<?php echo $style_order; ?>>
  <?php echo __('Student', array(), 'messages')?>
 <div><?php if ('student_id' == $sort[0]): ?>
    <?php echo link_to(__('Student', array(), 'messages'), '@ps_service_saturday', array('query_string' => 'sort=student_id&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Student', array(), 'messages'), '@ps_service_saturday', array('query_string' => 'sort=student_id&sort_type=asc')) ?>
  <?php endif; ?></div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php

$name_field = 'service_id';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder') :
	$style_order = 'style="width:60px;"';
endif;

?>

<th class="sf_admin_foreignkey sf_admin_list_th_service_id"	<?php echo $style_order; ?>>
 <?php echo __('Service', array(), 'messages')?>
 <div> <?php if ('service_id' == $sort[0]): ?>
    <?php echo link_to(__('Service', array(), 'messages'), '@ps_service_saturday', array('query_string' => 'sort=service_id&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Service', array(), 'messages'), '@ps_service_saturday', array('query_string' => 'sort=service_id&sort_type=asc')) ?>
  <?php endif; ?></div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php

$name_field = 'service_date';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder') :
	$style_order = 'style="width:60px;"';
endif;

?>

<th class="sf_admin_date sf_admin_list_th_service_date"	<?php echo $style_order; ?>>
<?php echo __('Service date', array(), 'messages')?>
 <div>  <?php if ('service_date' == $sort[0]): ?>
    <?php echo link_to(__('Service date', array(), 'messages'), '@ps_service_saturday', array('query_string' => 'sort=service_date&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Service date', array(), 'messages'), '@ps_service_saturday', array('query_string' => 'sort=service_date&sort_type=asc')) ?>
  <?php endif; ?></div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php

$name_field = 'relative_id';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder') :
	$style_order = 'style="width:60px;"';
endif;

?>

<th class="sf_admin_foreignkey sf_admin_list_th_relative_id"	<?php echo $style_order; ?>>
 <?php echo __('Relative', array(), 'messages')?>
 <div> <?php if ('relative_id' == $sort[0]): ?>
    <?php echo link_to(__('Relative', array(), 'messages'), '@ps_service_saturday', array('query_string' => 'sort=relative_id&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Relative', array(), 'messages'), '@ps_service_saturday', array('query_string' => 'sort=relative_id&sort_type=asc')) ?>
  <?php endif; ?></div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php

$name_field = 'input_date_at';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder') :
	$style_order = 'style="width:60px;"';
endif;

?>

<th class="sf_admin_date sf_admin_list_th_input_date_at"	<?php echo $style_order; ?>>
<?php echo __('Input date at', array(), 'messages')?>
 <div>  <?php if ('input_date_at' == $sort[0]): ?>
    <?php echo link_to(__('Input date at', array(), 'messages'), '@ps_service_saturday', array('query_string' => 'sort=input_date_at&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Input date at', array(), 'messages'), '@ps_service_saturday', array('query_string' => 'sort=input_date_at&sort_type=asc')) ?>
  <?php endif; ?></div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php

$name_field = 'note';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder') :
	$style_order = 'style="width:60px;"';
endif;

?>

<th class="sf_admin_text sf_admin_list_th_note"	<?php echo $style_order; ?>>
<?php echo __('Note', array(), 'messages')?>
 <div>  <?php if ('note' == $sort[0]): ?>
    <?php echo link_to(__('Note', array(), 'messages'), '@ps_service_saturday', array('query_string' => 'sort=note&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Note', array(), 'messages'), '@ps_service_saturday', array('query_string' => 'sort=note&sort_type=asc')) ?>
  <?php endif; ?></div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php

$name_field = 'updated_at';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder') :
	$style_order = 'style="width:60px;"';
endif;

?>

<th class="sf_admin_date sf_admin_list_th_updated_at"	<?php echo $style_order; ?>>
<?php echo __('Updated by', array(), 'messages')?>
 <div>  <?php if ('updated_at' == $sort[0]): ?>
    <?php echo link_to(__('Updated by', array(), 'messages'), '@ps_service_saturday', array('query_string' => 'sort=updated_at&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Updated by', array(), 'messages'), '@ps_service_saturday', array('query_string' => 'sort=updated_at&sort_type=asc')) ?>
  <?php endif; ?></div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?>
			
			<th id="sf_admin_list_th_actions" class="text-center" style="width: 57px;"><?php echo __('Actions', array(), 'sf_admin') ?>
			<div><?php echo __('Actions', array(), 'sf_admin') ?></div></th>

          <th id="sf_admin_list_batch_actions" class="text-center" style="width:31px;">
    			<div><label class="checkbox-inline">
    				<input id="sf_admin_list_batch_checkbox" class="sf_admin_list_batch_checkbox checkbox style-0" type="checkbox" />
    				<span></span>
    			</label></div>						
		  </th>
			</tr>
        <tr class="hidden-lg hidden-md">
          <?php include_partial('psServiceSaturday/list_th_tabular', array('sort' => $sort)) ?>
          
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
        <?php foreach ($pager->getResults() as $i => $ps_service_saturday): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?>
          <tr class="sf_admin_row <?php echo $odd ?>">
			
			            <?php include_partial('psServiceSaturday/list_td_tabular', array('ps_service_saturday' => $ps_service_saturday)) ?>
						            <?php include_partial('psServiceSaturday/list_td_actions', array('ps_service_saturday' => $ps_service_saturday, 'helper' => $helper)) ?>
			                        <?php include_partial('psServiceSaturday/list_td_batch_actions', array('ps_service_saturday' => $ps_service_saturday, 'helper' => $helper)) ?>
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