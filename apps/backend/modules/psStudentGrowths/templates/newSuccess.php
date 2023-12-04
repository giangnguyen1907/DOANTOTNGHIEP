<?php use_helper('I18N', 'Date')?>
<?php include_partial('psStudentGrowths/assets')?>

<section id="widget-grid">
 <?php include_partial('psStudentGrowths/flashes')?>
	<div class="row">
		<article
			class="col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">
			<div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" style="margin-bottom: 5px;">
				<header>
					<span class="widget-icon"><i class="fa fa-pencil-square-o"></i></span>
					<h2><?php echo __('Medical watchdog', array(), 'messages') ?></h2>
				</header>
			</div>
		</article>
		<article
			class="col-xs-12 col-sm-12 col-md-8 col-lg-8 sortable-grid ui-sortable">
			<div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-editbutton="false" data-widget-togglebutton="false"
				data-widget-deletebutton="false"
				data-widget-fullscreenbutton="false"
				data-widget-custombutton="false" data-widget-collapsed="false"
				data-widget-sortable="false">
				<header>
					<h2><?php echo __('List student', array(), 'messages') ?></h2>
				</header>				
          <?php include_partial('psStudentGrowths/student', array( 'ps_student_growths' => $ps_student_growths, 'formFilter' => $formFilter, 'filter_list_student' => $filter_list_student, 'helper' => $helper))?>
			</div>
		</article>

		<article
			class="col-xs-12 col-sm-12 col-md-4 col-lg-4 sortable-grid ui-sortable">
			<div class="jarviswidget" id="wid-id-1"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-editbutton="false" data-widget-togglebutton="false"
				data-widget-deletebutton="false"
				data-widget-fullscreenbutton="false"
				data-widget-custombutton="false" data-widget-collapsed="false"
				data-widget-sortable="false">
				<header>
					<h2><?php echo __( ($form->isNew()) ? 'Enter health data' : 'Update health data', array(), 'messages') ?></h2>
				</header>				
          <?php include_partial('psStudentGrowths/form', array('ps_student_growths' => $ps_student_growths, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper))?>
			</div>
		</article>
	</div>
</section>
