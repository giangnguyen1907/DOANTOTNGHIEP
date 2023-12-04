<?php use_helper('I18N', 'Date') ?>
<?php use_helper('I18N', 'Number') ?>
<?php include_partial('psServiceSplits/assets') ?>
<?php $servicedetails = $service->getServiceDetailByDate(time());?>
<section id="widget-grid">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      
      <?php include_partial('psServiceSplits/flashes') ?>

      <div class="jarviswidget " id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-togglebutton="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-pencil-square-o"></i></span>
					<h2><?php echo __('Formula for calculating fees: %%Service%%', array('%%Service%%' => $service->getTitle()), 'messages') ?></h2>
				</header>
				<div>
					<div class="widget-body no-padding">
						<div class="dt-toolbar no-margin no-padding no-border">
							<div class="col-xs-12 col-sm-12">
								<div id="sf_admin_header">
									<div class="alert alert-info no-margin fade in">
										<i class="fa-fw fa fa-info"></i> <span class="lable"><?php echo __('Service amount');?>:</span>
										<code><?php echo PreNumber::number_format($servicedetails['amount']);?></code>,<?php echo __('By number');?>:  <code><?php echo PreNumber::number_format($servicedetails['by_number']);?></code><?php echo __('Service detail at');?>: <code><?php echo format_date($servicedetails['detail_at'],"MM-yyyy" )?></code>
							<?php

echo __ ( 'Is type fee' ) . ': ';
							echo isset ( PreSchool::loadPsIsTypeFee () [$service->getIsTypeFee ()] ) ? '<code>' . __ ( PreSchool::loadPsIsTypeFee () [$service->getIsTypeFee ()] ) . '</code>' : '';
							?>
							</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
								<div class="jarviswidget " id="wid-id-2"
									data-widget-editbutton="false" data-widget-colorbutton="false"
									data-widget-togglebutton="false"
									data-widget-fullscreenbutton="false"
									data-widget-deletebutton="false">
									<header>
										<span class="widget-icon"> <i class="fa fa-table"></i>
										</span>
										<h2><?php echo __('Split level fee', array(), 'messages') ?></h2>
									</header>
				    	<?php include_partial('psServiceSplits/list_service_split', array('service' => $service,'ps_service_splits' => $ps_service_splits, 'service_split' => $service_split,'service_detail' => $service_detail,'configuration' => $configuration, 'helper' => $helper)) ?>
				    	</div>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
								<div class="jarviswidget " id="wid-id-1"
									data-widget-editbutton="false" data-widget-colorbutton="false"
									data-widget-togglebutton="false"
									data-widget-fullscreenbutton="false"
									data-widget-deletebutton="false">
									<header>
								<?php if ($form->isNew()) : ?>
	                            <h2><?php echo __('New Splits', array(), 'messages') ?></h2>
	                            <?php else:?>
	                            <h2><?php echo __('Edit Splits', array(), 'messages') ?></h2>
	                            <?php endif;?>
	                        </header>
									<div>
					          <?php include_partial('psServiceSplits/form', array('service_split' => $service_split, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
					        </div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</article>
	</div>
</section>