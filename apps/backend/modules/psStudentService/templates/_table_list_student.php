
<span class="label label-warning text-left"><?php echo __('List student')?></span>
<span class="txt-color-green text-left" id="loading"
	style="display: none;"> <i class="fa fa-refresh fa-spin fa-1x fa-fw"></i> <?php echo __('Loading...')?>
</span>
<div class="custom-scroll table-responsive"
	style="height: 500px; overflow-y: scroll;">
	<table id="dt_basic"
		class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th style="width: 50px;" class="text-center"><?php echo __('Image');?></th>
				<th><?php echo __('Student', array(), 'messages') ?></th>
				<th style="width: 110px;" class="text-center"><?php echo __('Birthday', array(), 'messages') ?></th>
				<th style="width: 85px;" class="text-center"><?php echo __('Actions') ?></th>
				<th id="sf_admin_list_batch_actions" class="text-center"
					style="width: 31px;"><label class="checkbox-inline"> <input
						id="sf_admin_list_batch_checkbox"
						class="sf_admin_list_batch_checkbox checkbox style-0"
						type="checkbox" /> <span></span>
				</label></th>
			</tr>
		</thead>
		<tbody id="list_student">
			<?php include_partial('psStudentService/list_student', array('list_student' => $list_student,'ktime' => $ps_school_year_id)) ?>
		</tbody>
	</table>
</div>