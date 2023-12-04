<?php use_helper('I18N', 'Date') ?>
<?php include_partial('psServiceSplits/assets') ?>

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
					<h2><?php echo __('Edit PsServiceSplits', array(), 'messages') ?></h2>
				</header>

				<div id="sf_admin_header" class="no-margin no-padding no-border">
          <?php include_partial('psServiceSplits/form_header', array('service_split' => $service_split, 'form' => $form, 'configuration' => $configuration)) ?>
        </div>

				<div id="sf_admin_content">
          <?php include_partial('psServiceSplits/form', array('service_split' => $service_split, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
        </div>

				<div id="sf_admin_footer" class="no-border no-padding">
          <?php include_partial('psServiceSplits/form_footer', array('service_split' => $service_split, 'form' => $form, 'configuration' => $configuration)) ?>
        </div>
			</div>
		</article>
	</div>
</section>