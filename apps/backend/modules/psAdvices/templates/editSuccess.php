<?php use_helper('I18N', 'Date') ?>
<?php include_partial('psAdvices/assets') ?>

<section id="widget-grid">
	<!--  sf_admin_container -->
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      
      <?php include_partial('psAdvices/flashes') ?>

      <div class="jarviswidget " id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-togglebutton="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-pencil-square-o"></i></span>
					<!--    Lay ra thong tin hoc sinh, giao vien day -->
        <?php
								$obj_student = Doctrine::getTable ( 'Student' )->findOneById ( $ps_advices->getStudentId () );
								$teacher = ($ps_advices->getUserId ()) ? (__ ( 'Teacher' ) . ' ' . $ps_advices->getUserId ()) : '';
								$student = ($obj_student) ? ($obj_student->getStudentCode () . ' ' . $obj_student->getFirstName () . ' ' . $obj_student->getLastName ()) : '';
								?>
        <h2><?php echo __('Edit PsAdvices: %%student%% - %%teacher%%', array('%%student%%' => $student, '%%teacher%%' => $teacher), 'messages') ?></h2>
				</header>

				<div id="sf_admin_header" class="no-margin no-padding no-border">
          <?php include_partial('psAdvices/form_header', array('ps_advices' => $ps_advices, 'form' => $form, 'configuration' => $configuration)) ?>
        </div>

				<div id="sf_admin_content">
          <?php include_partial('psAdvices/form', array('ps_advices' => $ps_advices, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
        </div>

				<div id="sf_admin_footer" class="no-border no-padding">
          <?php include_partial('psAdvices/form_footer', array('ps_advices' => $ps_advices, 'form' => $form, 'configuration' => $configuration)) ?>
        </div>
			</div>
		</article>
	</div>
</section>