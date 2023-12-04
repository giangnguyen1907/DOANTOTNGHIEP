<?php use_helper('I18N', 'Date')?>
<?php //include_partial('psOffSchool/assets') ?>
<style>
.control-label{text-align: right;}
</style>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">×</button>
	<h5 class="modal-title"><?php echo __('New PsOffSchool').': <b>' .$ps_student->getFirstName().' '.$ps_student->getLastName() ; ?></b>
		<small>
		(<?php if (false !== strtotime($ps_student->getBirthday())) echo format_date($ps_student->getBirthday(), "dd-MM-yyyy").'<code>'.PreSchool::getAge($ps_student->getBirthday(),false).'</code>';?>) - <?php echo __('Class')?>: <?php echo ($student_class) ? $student_class->getName() : '';?>, <?php echo ($config_time_receive_valid) ? __('config time receive valid').' '.$config_time_receive_valid : ''?>
		</small>
	</h5>
</div>
<?php echo form_tag_for($form, '@ps_off_school') ?>
    <?php echo $form->renderHiddenFields(false) ?>
	<input type="hidden" name="url_ps_students" value="url_ps_students">
    <?php if ($form->hasGlobalErrors()): ?>
      <?php echo $form->renderGlobalErrors() ?>
    <?php endif; ?>

<div class="modal-body" style="overflow: hidden;" id="sf_admin_content">
	<?php if(count($list_informed) > 0){?>
  <div class="col-md-12">
  	<div id="datatable_fixed_column_wrapper" class=" dataTables_wrapper form-inline no-footer no-padding">
	<div class="custom-scroll table-responsive" style="max-height: 300px;overflow: scroll;">
		<table id="dt_basic" class="table table-striped table-bordered table-hover no-footer no-padding" width="100%">
    	<thead>
			<tr>
				<th><?php echo __('Relative') ?></th>
				<th><?php echo __('Reason', array(), 'messages') ?></th>
				<th><?php echo __('Status', array(), 'messages') ?></th>
				<th><?php echo __('Date', array(), 'messages') ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($list_informed as $informed){ ?>
			<tr>
				<td><?php echo $informed->getRelativeName()?></td>
				<td>
    				<p style="word-break: break-all;">
                      <?php echo $informed->getDescription().'<br/>' ?>
                      <?php if($informed->getIsActivated() == 2){
                          echo __('Reason illegal').' : '. $informed->getReasonIllegal();
                      } ?>
                    </p>
                </td>
				<td><?php echo get_partial('psOffSchool/ajax_activated', array('ps_off_school' => $informed)) ?></td>
				<td><?php echo $date = date('d/m/Y',strtotime($informed->getFromDate())) . ' - ' . date('d/m/Y',strtotime($informed->getToDate()))?></td>
			</tr>
			<?php }?>
		</tbody>
    </table>
    </div>
    </div>
  </div>
	<?php }?>
<div style="clear: both;"></div>
  <?php include_partial('psOffSchool/form2', array('ps_off_school' => $ps_off_school, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
</div>
</form>
<script type="text/javascript">
	$(".select2").addClass("form-control");
	$('#student_class_myclass_id, #student_class_type').select2({
		dropdownParent: $('#remoteModal')
	});
	$.datepicker.setDefaults($.datepicker.regional[ "vi" ]);
</script>

