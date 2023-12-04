<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<?php  echo $form['student_id']->render()?>
<div class="form-group"></div>

<div class="txt-color-green" id="loading" style="display: none;">
	<i class="fa fa-refresh fa-spin fa-1x fa-fw" style="padding: 3px;"></i> <?php echo __('Loading...')?>
	</div>
<div id="list_relative_main">
		<?php include_partial('psRelativeStudent/list_relative_main', array('list_relative' => $list_relative, 'form' => $form, 'ps_student' => $ps_student, 'pager' => $pager)) ?>	
	</div>
