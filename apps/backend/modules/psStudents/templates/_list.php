<?php if ($pager->getNbResults()): ?>
<div class="clear" style="clear: both;"></div>
<section class="table_scroll">
	<div class="container_table custom-scroll table-responsive">
	<table id="dt_basic" class="table table-bordered table-striped" width="100%">
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
  <?php echo __('View img', array(), 'messages') ?>
  <div><?php echo __('View img', array(), 'messages') ?></div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php $name_field = 'student_code';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder'):
	$style_order = 'style="width:60px;"';
endif;
?>

<th class="sf_admin_text sf_admin_list_th_student_code" <?php echo $style_order; ?>>
  <?php echo __('Student code', array(), 'messages') ?>
  <div>
  <?php if ('student_code' == $sort[0]): ?>
    <?php echo link_to(__('Student code', array(), 'messages'), '@ps_students', array('query_string' => 'sort=student_code&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Student code', array(), 'messages'), '@ps_students', array('query_string' => 'sort=student_code&sort_type=asc')) ?>
  <?php endif; ?>
  </div>
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
  <div>
  <?php if ('first_name' == $sort[0]): ?>
    <?php echo link_to(__('First name', array(), 'messages'), '@ps_students', array('query_string' => 'sort=first_name&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('First name', array(), 'messages'), '@ps_students', array('query_string' => 'sort=first_name&sort_type=asc')) ?>
  <?php endif; ?>
  </div>
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
  <div>
  <?php if ('last_name' == $sort[0]): ?>
    <?php echo link_to(__('Last name', array(), 'messages'), '@ps_students', array('query_string' => 'sort=last_name&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Last name', array(), 'messages'), '@ps_students', array('query_string' => 'sort=last_name&sort_type=asc')) ?>
  <?php endif; ?>
  </div>
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
  <div>
  <?php if ('birthday' == $sort[0]): ?>
    <?php echo link_to(__('Birthday', array(), 'messages'), '@ps_students', array('query_string' => 'sort=birthday&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Birthday', array(), 'messages'), '@ps_students', array('query_string' => 'sort=birthday&sort_type=asc')) ?>
  <?php endif; ?>
  </div>
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
  <?php echo __('Gender', array(), 'messages') ?>
  <div>
  <?php if ('sex' == $sort[0]): ?>
    <?php echo link_to(__('Gender', array(), 'messages'), '@ps_students', array('query_string' => 'sort=sex&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Gender', array(), 'messages'), '@ps_students', array('query_string' => 'sort=sex&sort_type=asc')) ?>
  <?php endif; ?>
  </div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<?php $name_field = 'start_date_at';
$style_order = '';
if ($name_field == 'order' || $name_field == 'iorder'):
	$style_order = 'style="width:60px;"';
endif;
?>

<th class="sf_admin_date sf_admin_list_th_start_date_at" <?php echo $style_order; ?>>
  <?php echo __('Start date at', array(), 'messages') ?>
  <div>
  <?php if ('start_date_at' == $sort[0]): ?>
    <?php echo link_to(__('Start date at', array(), 'messages'), '@ps_students', array('query_string' => 'sort=start_date_at&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Start date at', array(), 'messages'), '@ps_students', array('query_string' => 'sort=start_date_at&sort_type=asc')) ?>
  <?php endif; ?>
  </div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>

<th class="sf_admin_date sf_admin_list_th_start_date_at" <?php echo $style_order; ?>>
  <?php echo __('Current class name', array(), 'messages') ?>
  <div>
  <?php echo __('Current class name', array(), 'messages') ?>
  </div>
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
  <div>
  <?php if ('updated_at' == $sort[0]): ?>
    <?php echo link_to(__('Updated at', array(), 'messages'), '@ps_students', array('query_string' => 'sort=updated_at&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Updated at', array(), 'messages'), '@ps_students', array('query_string' => 'sort=updated_at&sort_type=asc')) ?>
  <?php endif; ?>
  </div>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?>
				
			<th id="sf_admin_list_th_actions" class="text-center" style="width:180px;">
			<?php echo __('Actions', array(), 'sf_admin') ?>
			<div><?php echo __('Actions', array(), 'sf_admin') ?></div></th>
			<th id="sf_admin_list_batch_actions" class="text-center" style="width: 31px;">
			
			<div>
			<label class="checkbox-inline">
				<input
					id="sf_admin_list_batch_checkbox"
					class="sf_admin_list_batch_checkbox checkbox style-0"
					type="checkbox" /> <span></span>
			</label>
			</div>
			</th>
			</tr>
			
			<tr class="hidden-lg hidden-md">
          <?php include_partial('psStudents/list_th_tabular', array('sort' => $sort)) ?>
          
			<th id="sf_admin_list_th_actions" class="text-center" style="width:180px;"><?php echo __('Actions', array(), 'sf_admin') ?></th>
			<th id="sf_admin_list_batch_actions" class="text-center" style="width: 31px;">
			<label class="checkbox-inline">
				<input
					id="sf_admin_list_batch_checkbox"
					class="sf_admin_list_batch_checkbox checkbox style-0"
					type="checkbox" /> <span></span>
			</label>
			</th>

			</tr>
		</thead>
		<tbody>
        <?php foreach ($pager->getResults() as $i => $student): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?>
          <tr class="sf_admin_row <?php echo $odd ?>">			
			 <?php include_partial('psStudents/list_td_tabular', array('student' => $student, 'status_student' => $status_student)) ?>
			 <?php include_partial('psStudents/list_td_actions', array('student' => $student, 'helper' => $helper)) ?>
			 <?php include_partial('psStudents/list_td_batch_actions', array('student' => $student, 'helper' => $helper)) ?>
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