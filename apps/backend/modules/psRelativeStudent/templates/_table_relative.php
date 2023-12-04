<?php use_helper('I18N', 'Date') ?>
<?php $school_code = $ps_student->getPsCustomer()->getSchoolCode();?>
<table id="dt_basic"
	class="table table-striped table-bordered table-hover" width="100%">
	<thead>
		<tr>
			<th class="text-center" style="width: 10%;"><?php echo __('Image');?></th>
			<th class="text-center" style="width: 10%;"><?php echo __('Full name');?></th>
			<th class="text-center" style="width: 10%;"><?php echo __('Birthday');?></th>
			<th class="text-center" style="width: 10%;"><?php echo __('Sex');?></th>
			<th class="text-center" style="width: 10%"><?php echo __('Mobile');?></th>
			<th class="text-center" style="width: 10%"><?php echo __('Relation');?></th>
			<th class="text-center" style="width: 10%"><?php echo __('Is order');?></th>
			<th class="text-center sorting_disabled" rowspan="1" colspan="1"
				style="width: 30%;">
              <?php echo __('Role');?>
              <ul class="col-md-12" style="list-style-type: none;">
					<li class="col-md-3 text-center"><?php echo __('Main');?></li>
					<li class="col-md-3 text-center"><?php echo __('Parent');?></li>
					<li class="col-md-3 text-center"><?php echo __('Avatar');?></li>
					<li class="col-md-3 text-center"><?php echo __('Service');?></li>

				</ul>
			</th>

		</tr>
	</thead>
	<tbody>
		
		<?php foreach($list_relative as $relative): ?>
		<tr>
			<td>			
			<?php
			if ($relative->getImage () != '') {
				$path_file = '/media-web/' . PreSchool::MEDIA_TYPE_RELATIVE . '/' . $school_code . '/' . $relative->getYearData () . '/' . $relative->getImage ();
				echo '<img style="max-width: 45px; text-align: center;" src="' . $path_file . '">';
			}
			?>			
			</td>
			<td><?php echo $relative->getFullName();?></td>

			<td><?php echo $relative->getRelativeBirthday() ? format_date($relative->getRelativeBirthday(), "dd-MM-yyyy") : '';?></td>

			<td><?php echo get_partial('global/field_custom/_field_sex', array('value' => $relative->getSex())) ?></td>
			<td><?php echo $relative->getMobile();?></td>
			<td><?php echo $form['relationship_id']->render(array('name' => 'form_relative_student['.$relative->id.'][relationship_id]')) ?></td>

			<td><label class="checkbox-inline">
			<?php echo $form['iorder']->render(array('name' => 'form_relative_student['.$relative->id.'][iorder]')) ?>
			<span></span>
			</label>
			
			<td class="text-center">
				<ul class="col-md-12" style="list-style-type: none;">
					<li class="col-md-3 pull-left text-center"><label
						class="checkbox-inline">
			<?php echo $form['is_parent_main']->render(array('name' => 'form_relative_student['.$relative->id.'][is_parent_main]')) ?>
			<span></span>
					</label></li>
					<li class="col-md-3 pull-left text-center"><label
						class="checkbox-inline">
			<?php echo $form['is_parent']->render(array('name' => 'form_relative_student['.$relative->id.'][is_parent]')) ?><span></span>
					</label></li>
					<li class="col-md-3 pull-left text-center"><label
						class="checkbox-inline">
			<?php echo $form['is_role']->render(array('name' => 'form_relative_student['.$relative->id.'][is_role]')) ?><span></span>
					</label></li>
					<li class="col-md-3 pull-left text-center"><label
						class="checkbox-inline">
			<?php echo $form['role_service']->render(array('name' => 'form_relative_student['.$relative->id.'][role_service]')) ?>
			<span></span>
					</label></li>
				</ul>
			</td>
		</tr>
		<?php endforeach;?>
		</tbody>
</table>

<script type="text/javascript">
$(document).ready(function() {	
	$('.select3').select2({
		  dropdownParent: $('#remoteModal'),
		  dropdownCssClass : 'no-search'
	});
});
</script>

