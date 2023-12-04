<?php use_helper('I18N', 'Date') ?>
<?php use_helper('I18N', 'Number') ?>
<?php include_partial('psSubjectSplits/assets') ?>

<section id="widget-grid">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      
      <?php include_partial('psSubjectSplits/flashes') ?>

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
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
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
				          <?php include_partial('psSubjectSplits/form', array('service_split' => $service_split, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
				        </div>
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
							<div class="jarviswidget " id="wid-id-2"
								data-widget-editbutton="false" data-widget-colorbutton="false"
								data-widget-togglebutton="false"
								data-widget-fullscreenbutton="false"
								data-widget-deletebutton="false">
								<header>
									<span class="widget-icon"> <i class="fa fa-calendar"></i>
									</span>
									<h2><?php echo __('Split level fee', array(), 'messages') ?></h2>
								</header>
			    	<?php include_partial('psSubjectSplits/list_service_split', array('ps_service_splits' => $ps_service_splits, 'service_split' => $service_split,'service_detail' => $service_detail,'configuration' => $configuration, 'helper' => $helper)) ?>
			    	</div>
						</div>
					</div>
				</div>
			</div>
		</article>
	</div>
</section>