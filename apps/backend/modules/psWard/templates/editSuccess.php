<?php use_helper('I18N', 'Date') ?>
<?php include_partial('psWard/assets') ?>

<section id="widget-grid">
	<!--  sf_admin_container -->
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      
      <?php include_partial('psWard/flashes') ?>

      <div class="jarviswidget jarviswidget-color-blue" id="wid-id-0"
				data-widget-editbutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-pencil-square-o"></i></span>
					<h2><?php echo __('Edit PsWard', array(), 'messages') ?></h2>
				</header>

				<div id="sf_admin_header" class="no-margin no-padding no-border">
          <?php include_partial('psWard/form_header', array('ps_ward' => $ps_ward, 'form' => $form, 'configuration' => $configuration)) ?> 
        </div>

				<div id="sf_admin_content">
          <?php include_partial('psWard/form', array('ps_ward' => $ps_ward, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
        </div>

				<div id="sf_admin_footer" class="no-border no-padding">
          <?php include_partial('psWard/form_footer', array('ps_ward' => $ps_ward, 'form' => $form, 'configuration' => $configuration)) ?>
        </div>
			</div>
		</article>
	</div>
	</div>