<?php use_helper('I18N', 'Date') ?>
<?php include_partial('psCustomer/assets') ?>

<section id="widget-grid">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

    <?php include_partial('psCustomer/flashes') ?>

    <div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-togglebutton="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-eye"></i></span>
					<h2><?php echo $customer_detail->getSchoolName()?></h2>
				</header>
				<div id="sf_admin_header" class="no-margin no-padding no-border">
				<?php include_partial('psCustomer/form_header', array('ps_menus' => $ps_menus, 'form' => $form, 'configuration' => $configuration)) ?>
				</div>

				<div id="sf_admin_content">
					<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9"
						style="border-right: 1px dashed #D3D3D3;">
						<ul class="nav nav-tabs">
							<li class="active"><a data-toggle="tab" href="#home"><?php echo __('School information') ?></a></li>
							<li><a data-toggle="tab" href="#menu1"><?php echo __('School workplace') ?></a></li>
						</ul>

						<div class="tab-content">
							<div id="home" class="tab-pane fade in active">
								<br>
								<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">

									<div class="row">
										<div class="form-group">
											<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
												<label class="control-label"><strong><?php echo __('School code') ?></strong></label>
											</div>
											<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
												<p>
                      <?php echo $customer_detail->getSchoolCode()?>
                  </p>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="form-group">
											<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
												<label class="control-label"><strong><?php echo __('School name') ?></strong></label>
											</div>
											<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
												<p>
                      <?php echo $customer_detail->getSchoolName()?>
                  </p>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="form-group">
											<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
												<label class="control-label"><strong><?php echo __('Type School') ?></strong></label>
											</div>
											<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
												<p><?php echo $customer_detail->getTypeSchool()?></p>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="form-group">
											<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
												<label class="control-label"><strong><?php echo __('Principal') ?></strong></label>
											</div>
											<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
												<p><?php echo $customer_detail->getPrincipal()?></p>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="form-group">
											<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
												<label class="control-label"><strong><?php echo __('Is Activated') ?></strong></label>
											</div>
											<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
												<p>
												<?php
												switch ($customer_detail->getIsActivated ()) {
													case PreSchool::CUSTOMER_NOT_ACTIVATED :
														echo '<span class="label-warning" style="color:white;">' . __ ( 'Not activated' ) . '</span>';
														break;
													case PreSchool::CUSTOMER_ACTIVATED :
														echo '<span class="label-success" style="color:white;">' . __ ( 'Activated' ) . '</span>';
														break;
													case PreSchool::CUSTOMER_LOCK :
														echo '<span class="label-danger" style="color:white;">' . __ ( 'Lock' ) . '</span>';
														break;
													default :
														echo '<span class="label-warning" style="color:white;">' . __ ( 'Not activated' ) . '</span>';
														break;
												}
												?>
												</p>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="form-group">
											<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
												<label class="control-label"><strong><?php echo __('Address') ?></strong></label>
											</div>
											<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
												<p><?php echo $customer_detail->getAddress()?></p>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="form-group">
											<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
												<label class="control-label"><strong><?php echo __('Ward') ?></strong></label>
											</div>
											<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
												<p>
                      <?php echo $customer_detail->getPsWard()->getName()?>
                  </p>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="form-group">
											<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
												<label class="control-label"><strong><?php echo __('District') ?></strong></label>
											</div>
											<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
												<p>
                      <?php echo $customer_detail->getPsWard()->getPsDistrict()->getName()?>
                  </p>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="form-group">
											<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
												<label class="control-label"><strong><?php echo __('Province') ?></strong></label>
											</div>
											<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
												<p>
                      <?php echo $customer_detail->getPsWard()->getPsDistrict()->getPsProvince()->getName()?>
                  </p>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="form-group">
											<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
												<label class="control-label"><strong><?php echo __('Email') ?></strong></label>
											</div>
											<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
												<p><?php echo $customer_detail->getEmail()?></p>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="form-group">
											<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
												<label class="control-label"><strong><?php echo __('Phone') ?></strong></label>
											</div>
											<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
												<p><?php echo $customer_detail->getTel() ? $customer_detail->getTel() : "Đang cập nhật" ?> <?php echo $customer_detail->getMobile() ? "-".$customer_detail->getMobile() : null ?></p>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="form-group">
											<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
												<label class="control-label"><strong><?php echo __('Description') ?></strong></label>
											</div>
											<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
												<p><?php echo $customer_detail->getDescription() ? $customer_detail->getDescription() : '' ?></p>
											</div>
										</div>

										<div class="form-group">
											<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
												<label class="control-label"><strong><?php echo __('Note') ?></strong></label>
											</div>
											<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
												<p><?php echo $customer_detail->getNote() ? $customer_detail->getNote() : '' ?></p>
											</div>
										</div>
									</div>

								</div>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
            <?php if($customer_detail->getLogo()): ?>
                <img style="width: 100%; text-align: center;"
										src="<?php echo '/media-web/'.$customer_detail->getYearData().'/logo/'.$customer_detail->getLogo();?>">
            <?php endif ?>
          </div>
							</div>

							<div id="menu1" class="tab-pane fade">
          <?php if($work_places_number): ?>
            <?php foreach ($work_places as $work_place): ?>
              <br>
								<div class="row">
									<div class="form-group">
										<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
											<label class="control-label"><strong><?php echo __('Workplace title') ?></strong></label>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
											<p><?php echo $work_place->getTitle()?></p>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="form-group">
										<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
											<label class="control-label"><strong><?php echo __('Address') ?></strong></label>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
											<p><?php echo $work_place->getAddress()?></p>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="form-group">
										<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
											<label class="control-label"><strong><?php echo __('Phone') ?></strong></label>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
											<p><?php echo $work_place->getPhone()?></p>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="form-group">
										<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
											<label class="control-label"><strong><?php echo __('Note') ?></strong></label>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
											<p><?php echo $work_place->getNote()?></p>
										</div>
									</div>
								</div>       
            <?php endforeach; ?>
          <?php else: ?>
              <br>
              <?php echo __('Updating...') ?>
          <?php endif; ?>
      </div>
						</div>
					</div>

					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
						<h6><?php echo __('History') ?></h6>
						<br>
						<div class="row">
							<div class="form-group">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<label class="control-label"><strong><?php echo __('Created At') ?></strong></label>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<p>
                <?php echo $customer_detail->getCreatedAt()?>
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
                <?php echo $customer_detail->getCreatedBy()?>
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
                <?php echo $customer_detail->getUpdatedAt()?>
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
                <?php echo $customer_detail->getUpdatedBy()?>
            </p>
								</div>
							</div>
						</div>
					</div>
					<div class="clearfix"></div>
				</div>
				
				<div id="sf_admin_footer" class="no-border no-padding">
		          <div class="form-actions">
					<?php if ( ($customer_detail->getIsRoot() != PreSchool::ACTIVE && $sf_user->hasCredential('PS_SYSTEM_CUSTOMER_EDIT')) || myUser::isAdministrator()): ?>
				    <a class="btn btn-success" href="<?php echo url_for('@ps_customer_edit?id='.$customer_detail->getId()) ?>"><i class="fa-fw fa fa-pencil"></i>&nbsp;<?php echo __('Edit')?></a>
				     <?php endif ?>
					</div>
		        </div>				
			</div>
		</article>
	</div>
	</div>