<?php use_helper('I18N', 'Date') ?>
<?php include_partial('psStudentGrowths/assets') ?>

<section id="widget-grid">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      
      <?php include_partial('psStudentGrowths/flashes') ?>

      <div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-pencil-square-o"></i></span>
					<h2><?php echo __('Update growth index', array(), 'messages') ?></h2>
				</header>

				<div id="sf_admin_header" class="no-margin no-padding no-border">
          <?php include_partial('psStudentGrowths/form_header', array('ps_student_growths' => $ps_student_growths, 'form' => $form, 'configuration' => $configuration)) ?>
        </div>

				<div id="sf_admin_content">
          <?php include_partial('psStudentGrowths/form', array('ps_student_growths' => $ps_student_growths, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
        </div>

				<div id="sf_admin_footer" class="no-border no-padding">
          <?php include_partial('psStudentGrowths/form_footer', array('ps_student_growths' => $ps_student_growths, 'form' => $form, 'configuration' => $configuration)) ?>
        </div>
			</div>
		</article>
	</div>
</section>