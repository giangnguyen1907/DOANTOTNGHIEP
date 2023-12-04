
  <?php if ($pager->getNbResults()): ?>
  	<div class="clear" style="clear: both;"></div>
	<section class="table_scroll">
	<div class="container_table custom-scroll table-responsive">
	<table id="dt_basic" class="table table-bordered table-striped" width="100%">
		<thead>
			<tr class="header hidden-sm hidden-xs">
				<?php slot('sf_admin.current_header') ?>

<?php $name_field = 'title';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder'):
	$style_order = 'style="width:60px;"';
endif;
?>

<th class="sf_admin_text sf_admin_list_th_title" <?php echo $style_order; ?>>
  <?php echo __('Title', array(), 'messages')?>
  <div><?php if ('title' == $sort[0]): ?>
    <?php echo link_to(__('Title', array(), 'messages'), '@ps_advices', array('query_string' => 'sort=title&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Title', array(), 'messages'), '@ps_advices', array('query_string' => 'sort=title&sort_type=asc')) ?>
  <?php endif; ?></div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php $name_field = 'content';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder'):
	$style_order = 'style="width:60px;"';
endif;
?>

<th class="sf_admin_text sf_admin_list_th_content" <?php echo $style_order; ?>>
  <?php echo __('Content', array(), 'messages')?>
  <div><?php if ('content' == $sort[0]): ?>
    <?php echo link_to(__('Content', array(), 'messages'), '@ps_advices', array('query_string' => 'sort=content&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Content', array(), 'messages'), '@ps_advices', array('query_string' => 'sort=content&sort_type=asc')) ?>
  <?php endif; ?></div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php $name_field = 'student_id';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder'):
	$style_order = 'style="width:60px;"';
endif;
?>

<th class="sf_admin_foreignkey sf_admin_list_th_student_id" <?php echo $style_order; ?>>
  <?php echo __('Student', array(), 'messages')?>
  <div><?php if ('student_id' == $sort[0]): ?>
    <?php echo link_to(__('Student', array(), 'messages'), '@ps_advices', array('query_string' => 'sort=student_id&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Student', array(), 'messages'), '@ps_advices', array('query_string' => 'sort=student_id&sort_type=asc')) ?>
  <?php endif; ?></div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php $name_field = 'user_created_id';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder'):
	$style_order = 'style="width:60px;"';
endif;
?>

<th class="sf_admin_foreignkey sf_admin_list_th_user_created_id" <?php echo $style_order; ?>>
  <?php echo __('User created', array(), 'messages')?>
  <div><?php if ('user_created_id' == $sort[0]): ?>
    <?php echo link_to(__('User created', array(), 'messages'), '@ps_advices', array('query_string' => 'sort=user_created_id&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('User created', array(), 'messages'), '@ps_advices', array('query_string' => 'sort=user_created_id&sort_type=asc')) ?>
  <?php endif; ?></div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php $name_field = 'user_id';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder'):
	$style_order = 'style="width:60px;"';
endif;
?>

<th class="sf_admin_foreignkey sf_admin_list_th_user_id" <?php echo $style_order; ?>>
  <?php echo __('User', array(), 'messages')?>
  <div><?php if ('user_id' == $sort[0]): ?>
    <?php echo link_to(__('User', array(), 'messages'), '@ps_advices', array('query_string' => 'sort=user_id&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('User', array(), 'messages'), '@ps_advices', array('query_string' => 'sort=user_id&sort_type=asc')) ?>
  <?php endif; ?></div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php $name_field = 'feedback_content';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder'):
	$style_order = 'style="width:60px;"';
endif;
?>

<th class="sf_admin_text sf_admin_list_th_feedback_content" <?php echo $style_order; ?>>
  <?php echo __('Feedback content', array(), 'messages')?>
  <div><?php echo __('Feedback content', array(), 'messages') ?></div>
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
  <?php echo __('Status', array(), 'messages')?>
  <div><?php if ('is_activated' == $sort[0]): ?>
    <?php echo link_to(__('Status', array(), 'messages'), '@ps_advices', array('query_string' => 'sort=is_activated&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Status', array(), 'messages'), '@ps_advices', array('query_string' => 'sort=is_activated&sort_type=asc')) ?>
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
	    			</label></div>				
			  </th>
	
			</tr>
	        <tr class="hidden-lg hidden-md">
	          <?php include_partial('psAdvices/list_th_tabular', array('sort' => $sort)) ?>
	          
	          <th id="sf_admin_list_th_actions" class="text-center" style="width: 57px;"><?php echo __('Actions', array(), 'sf_admin') ?></th>
	
	          <th id="sf_admin_list_batch_actions" class="text-center" style="width:31px;">
	    			<label class="checkbox-inline">
	    				<input id="sf_admin_list_batch_checkbox" class="sf_admin_list_batch_checkbox checkbox style-0" type="checkbox" />
	    				<span></span>
	    			</label>						
			  </th>
	
	        </tr>
      </thead>
      <tfoot>
        <tr>
          <th colspan="9">
          <span class="text-results">
          <?php echo __('View %%from_item%% - %%to_item%% /%%nbResults%% results', array('%%from_item%%' => $pager->getFirstIndice(), '%%to_item%%' => $pager->getLastIndice(), '%%nbResults%%' => $pager->getNbResults())) ?>
          </span>            
          </th>
        </tr>
      </tfoot>
      <tbody>
        <?php foreach ($pager->getResults() as $i => $ps_advices): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?>
          <tr class="sf_admin_row <?php echo $odd ?>">
			
			            <?php include_partial('psAdvices/list_td_tabular', array('ps_advices' => $ps_advices)) ?>
						            <?php include_partial('psAdvices/list_td_actions', array('ps_advices' => $ps_advices, 'helper' => $helper)) ?>
			                        <?php include_partial('psAdvices/list_td_batch_actions', array('ps_advices' => $ps_advices, 'helper' => $helper)) ?>
                </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    </div>
    </section>
  <?php endif; ?>