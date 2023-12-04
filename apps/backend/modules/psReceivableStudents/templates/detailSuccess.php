<?php use_helper('I18N', 'Date')?>
<?php
// Su dung bien global
sfConfig::set ( 'enableRollText', PreSchool::loadPsRoll () );
?>
<style>
@media ( min-width : 992px)
.modal-lg {
	min-width:900px;
	width:1200px;
}
.modal-lg {
	min-width:900px;
	width: 1200px;
}
</style>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">×</button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $student->getFirstName().' '.$student->getLastName() ?></h4>
	<h5 class="modal-title"><?php echo __('Birthday').': '.format_date($student->getBirthday(),'dd-MM-yyyy') ?> <?php echo __('Gender') ?><?php echo get_partial('global/field_custom/_field_sex', array('value' => $student->getSex())) ?></h5>
</div>
<div class="modal-body">
	<div class="row">

		<div class="tab-content">
			<br>
			<div id="home" class="tab-pane fade in active">
				<div class="table-responsive">
					<table class="table table-bordered table-striped table-hover">
						<thead>
							<th class="text-center"><?php echo __('STT') ?></th>
							<th class="text-center"><?php echo __('Receivable') ?></th>
							<th class="text-center"><?php echo __('Amount') ?></th>
							<th class="text-center"><?php echo __('Is number') ?></th>
							<th class="text-center"><?php echo __('Receivable at') ?></th>
							<th class="text-center"><?php echo __('Note') ?></th>
							<th class="text-center"><?php echo __('Updated by') ?></th>
						</thead>
						<tbody>
              <?php foreach ($list_receivable as $ky=> $receivable): ?>
                <tr>
					<td class="text-center"> <?php echo $ky+1 ?> </td>
					<td class="text-center"> <?php echo $receivable->getTitle() ?> </td>
					<td class="text-center"> <?php echo $receivable->getAmount() ?> </td>
					<td class="text-center"> <?php echo $receivable->getIsNumber() ?> </td>
					<td class="text-center"> <?php echo false !== strtotime($receivable->getReceivableAt()) ? format_date($receivable->getReceivableAt(), "MM/yyyy") : '&nbsp;' ?> </td>
					<td class="text-center"> <?php echo $receivable->getNote()?> </td>
					<td class="text-center">
					<?php echo $receivable->getUpdatedBy() ?><br />
  					<?php echo false !== strtotime($receivable->getUpdatedAt()) ? format_date($receivable->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;'?>
				  </td>
							</tr>
              <?php endforeach ?>
            </tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">
		<i class="fa-fw fa fa-ban"></i>&nbsp;<?php echo __('Close')?></button>
</div>
