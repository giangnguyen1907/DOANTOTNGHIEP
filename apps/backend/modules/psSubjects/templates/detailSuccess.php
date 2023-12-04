<?php use_helper('I18N', 'Date')?>
<style type="text/css">
.control-label {
	font-weight: bold;
}

.mt-1 {
	margin-top: 2rem;
}

.pb-1 {
	padding-bottom: 1rem;
}
</style>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">×</button>
	<h4 class="modal-title" id="myModalLabel">
		<strong><?php echo __('Subject information: %%name%%', array('%%name%%' => 'test')) ?></strong>
	</h4>
</div>
<div class="modal-body">
	<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
    <?php

echo image_tag ( '/sys_icon/' . $subject_detail->getPsImages ()
					->getFileName (), array (
						'style' => 'width:80%;text-align:center;max-width:120px;' ) )?>;
  </div>

	<div class=" col-lg-9 col-md-9 col-sm-12 col-xs-12">

		<ul class="nav nav-tabs tabs-pull-right">
			<li class="active"><a data-toggle="tab" href="#subject_information"><?php echo __('Subject information') ?></a></li>
			<li><a data-toggle="tab" href="#subject_price_time_apply"><?php echo __('Subject price and time apply') ?></a></li>
		</ul>

		<div class="tab-content mt-1">

			<div id="subject_information" class="tab-pane fade in active"
				style="padding-left: 30px;">
				<div class="row">
					<div class="form-group">
						<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
							<label class="control-label"><?php echo __('Subject name') ?></label>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
							<p>
                <?php echo $subject_detail->getTitle() ?>
              </p>
						</div>
					</div>

					<div class="form-group">
						<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
							<label class="control-label"><?php echo __('Subject group') ?></label>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
							<p>
                <?php echo $subject_detail->getServiceGroup()->getTitle() ?>
              </p>
						</div>
					</div>

					<div class="form-group">
						<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
							<label class="control-label"><?php echo __('Subject mode') ?></label>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
							<p>
                <?php if($subject_detail->getMode() == '2'): ?>
                  
							
							
							<div class="pb-1">
								<i class="fa fa-check-square-o txt-color-green"></i> Chọn nhiều
							</div>

							<div>
								<i class="fa fa-square-o txt-color-green"></i> Chọn một
							</div>

                <?php elseif($subject_detail->getMode() == '1'): ?>
                  <div class="pb-1">
								<i class="fa fa-square-o txt-color-green"></i> Chọn nhiều
							</div>

							<div>
								<i class="fa fa-check-square-o txt-color-green"></i> Chọn một
							</div>                  
                <?php endif; ?>
              </p>
						</div>
					</div>

					<div class="form-group">
						<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
							<label class="control-label"><?php echo __('Is activated') ?></label>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
							<p>
                <?php echo $subject_detail->getIsActivated() ? '<i class="fa fa-check txt-color-green"></i> '.__('Activated') : '<i class="fa fa-times txt-color-red"></i> '.__('Not activated')?>
              </p>
						</div>
					</div>

					<div class="form-group">
						<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
							<label class="control-label"><?php echo __('Number course') ?></label>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
							<p>
                <?php echo $subject_detail->getNumberCourse()?>
              </p>
						</div>
					</div>

					<div class="form-group">
						<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
							<label class="control-label"><?php echo __('Note') ?></label>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
							<p>
                <?php echo $subject_detail->getNote() ? $subject_detail->getNote() : '...' ?>
              </p>
						</div>
					</div>

					<div class="form-group">
						<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
							<label class="control-label"><?php echo __('Description') ?></label>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
							<p>
                <?php echo $subject_detail->getDescription() ? $subject_detail->getDescription() : '...' ?>
              </p>
						</div>
					</div>
				</div>
			</div>

			<div id="subject_price_time_apply" class="tab-pane fade">
				<div class="table-responsive">
					<table class="table table-bordered table-striped table-hover">
						<thead>
							<th><?php echo __('Amount') ?></th>
							<th><?php echo __('By number') ?></th>
							<th><?php echo __('From day') ?></th>
							<th><?php echo __('To day') ?></th>
						</thead>
						<tbody>
              <?php foreach ($service_details as $service_detail): ?>
                <tr>
								<td> <?php echo $service_detail->getAmount() ?> </td>
								<td> <?php echo $service_detail->getByNumber() ?> </td>
								<td> <?php echo format_date($service_detail->getDetailAt(), 'dd-MM-yyyy') ?> </td>
								<td> <?php echo format_date($service_detail->getDetailEnd(), 'dd-MM-yyyy')?> </td>
							</tr>
              <?php endforeach ?>
            </tbody>
					</table>
				</div>
			</div>

		</div>
	</div>

	<div class="clearfix"></div>
</div>

<div class="modal-footer">
  <?php
		if ($sf_user->hasCredential ( 'PS_SUBJECTS_EDIT' )) {
			echo link_to ( '<i class="fa-fw fa fa-pencil"></i> ' . __ ( 'Edit' ), 'ps_subjects_edit', array (
					'id' => $subject_detail->getId () ), array (
					'class' => 'btn btn-default btn-success btn-sm btn-psadmin' ) );
		}
		?>
  <button type="button" class="btn btn-default" data-dismiss="modal">
		<i class="fa-fw fa fa-ban"></i>&nbsp;<?php echo __('Close') ?></button>
</div>


