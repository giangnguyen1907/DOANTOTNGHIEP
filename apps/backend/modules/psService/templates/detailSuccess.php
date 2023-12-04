<?php use_helper('I18N', 'Date') ?>
<?php
// Su dung bien global
sfConfig::set ( 'enableRollText', PreSchool::loadPsRoll () );
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">×</button>
	<h4 class="modal-title" id="myModalLabel"><?php echo __('Service information') ?></h4>
</div>
<div class="modal-body">

	<div class="row">
		<ul class="nav nav-tabs">
			<li class="active"><a data-toggle="tab" href="#home"><?php echo __('Service information') ?></a></li>
			<li><a data-toggle="tab" href="#menu1"><?php echo __('Service detail') ?></a></li>
			<li><a data-toggle="tab" href="#menu2"><?php echo __('Service split') ?></a></li>
			<li><a data-toggle="tab" href="#menu3"><?php echo __('List of used') ?></a></li>
		</ul>
		<div class="tab-content">
			<br>
			<div id="home" class="tab-pane fade in active">

				<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
					<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
						<div class="row">
							<div class="form-group">
								<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
									<label class="control-label"><strong><?php echo __('Service name') ?></strong></label>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
									<p>
                    <?php echo $services->getTitle()?>
                  </p>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="form-group">
								<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
									<label class="control-label"><strong><?php echo __('Enable roll') ?></strong></label>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
									<p>
                  <?php
																		if (isset ( sfConfig::get ( 'enableRollText' ) [$services->getEnableRoll ()] ))
																			echo __ ( sfConfig::get ( 'enableRollText' ) [$services->getEnableRoll ()] );
																		?>
                  </p>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="form-group">
								<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
									<label class="control-label"><strong><?php echo __('Service group') ?></strong></label>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
									<p>
                    <?php echo $services->getServiceGroup()?>
                  </p>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="form-group">
								<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
									<label class="control-label"><strong><?php echo __('Order') ?></strong></label>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
									<p>
                    <?php echo $services->getIorder()?>
                  </p>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="form-group">
								<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
									<label class="control-label"><strong><?php echo __('Is activated') ?></strong></label>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
									<p>
                    <?php echo $services->getIsActivated() ?  '<span class="label-success" style="color:white;">'. __('Activated') .'</span>' :  '<span class="label-warning" style="color:white;">'. __('Not activated') .'</span>'; ?>
                  </p>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="form-group">
								<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
									<label class="control-label"><strong><?php echo __('Is default') ?></strong></label>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
									<p>
                    <?php echo $services->getIsDefault() ? '<span class="label-info" style="color:white;">'. __('Default') .'</span>' :  '<span class="label-default" style="color:white;">'. __('Not default') .'</span>'; ?>
                  </p>
								</div>
							</div>
						</div>
					</div>

					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
           <?php

echo image_tag ( '/sys_icon/' . $services->getFileName (), array (
													'style' => 'width:100%;text-align:center;' ) )?>;
          </div>
				</div>

				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"
					style="border-left: 1px dashed #D3D3D3">
					<div class="row">
						<div class="form-group">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<label class="control-label"><strong><?php echo __('Created At') ?></strong></label>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<p>
                    <?php echo $services->getCreatedAt()?>
                </p>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="form-group">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<label class="control-label"><strong><?php echo __('Created By') ?></strong></label>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<p>
                    <?php echo $services->getCreatedBy()?>
                </p>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="form-group">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<label class="control-label"><strong><?php echo __('Updated At') ?></strong></label>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<p>
                    <?php echo $services->getUpdatedAt()?>
                </p>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="form-group">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<label class="control-label"><strong><?php echo __('Updated By') ?></strong></label>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<p>
                    <?php echo $services->getUpdatedBy()?>
                </p>
							</div>
						</div>
					</div>
				</div>

			</div>

			<div id="menu1" class="tab-pane fade">
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

			<div id="menu2" class="tab-pane fade">
				<div class="table-responsive">
					<table class="table table-bordered table-striped table-hover">
						<thead>
							<th><?php echo __('Count value') ?></th>
							<th><?php echo __('Count ceil') ?></th>
							<th><?php echo __('Split value') ?></th>
							<th><?php echo __('Fee') ?></th>
						</thead>
						<tbody>
              <?php foreach ($service_splits as $service_split): ?>
                <tr>
								<td> <?php echo $service_split->getCountValue() ?> </td>
								<td> <?php echo $service_split->getCountCeil() ?> </td>
								<td> <?php echo $service_split->getSplitValue() ?> </td>
								<td> <?php echo (double)$service_split->getSplitValue() * (double)($amount->getAmount()/100) ?> </td>
							</tr>
              <?php endforeach ?>
            </tbody>
					</table>
				</div>
			</div>

			<div id="menu3" class="tab-pane fade">
        <?php include_partial('psService/list_student_service', array('services' => $services)) ?>
       </div>

		</div>

	</div>

</div>
<div class="modal-footer">  
  <?php if($sf_user->hasCredential('PS_STUDENT_SERVICE_EDIT')):?>
    <a class="btn btn-success"
		href="<?php echo url_for('@ps_service_edit?id='.$services->getId()) ?>"><i
		class="fa-fw fa fa-pencil"></i>&nbsp;<?php echo __('Edit')?></a>
  <?php endif ?>
  <button type="button" class="btn btn-default" data-dismiss="modal">
		<i class="fa-fw fa fa-ban"></i>&nbsp;<?php echo __('Close')?></button>
</div>