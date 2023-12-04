<?php use_helper('I18N', 'Date')?>
<?php include_partial('psLogtimes/assets')?>
<?php
$current_date = date ( "Ymd" );
$tracked_at = $formFilter ['tracked_at']->getValue ();
$check_current_date = (PsDateTime::psDatetoTime ( $tracked_at ) - PsDateTime::psDatetoTime ( $current_date ) >= 0) ? true : false; // Ngay hien tai

?>
<section id="widget-grid">
<?php include_partial('psLogtimes/flashes')?>
	<div class="row">
		<div>
			<article
				class="col-xs-12 col-sm-12 col-md-7 col-lg-7 sortable-grid ui-sortable">
				<div class="jarviswidget" id="wid-id-0"
					data-widget-editbutton="false" data-widget-colorbutton="false"
					data-widget-editbutton="false" data-widget-togglebutton="false"
					data-widget-deletebutton="false"
					data-widget-fullscreenbutton="false"
					data-widget-custombutton="false" data-widget-collapsed="false"
					data-widget-sortable="false">
					<header>
						<span class="widget-icon"><i class="fa fa-table"></i></span>
						<h2><?php echo __('List student', array(), 'messages') ?></h2>
					</header>				
          			<?php include_partial('psLogtimes/students', array('formFilter' => $formFilter,'filter_list_student' => $filter_list_student, 'form' => $form))?>
			</div>
			</article>
			<article
				class="col-xs-12 col-sm-12 col-md-5 col-lg-5 sortable-grid ui-sortable">
				<div class="jarviswidget" id="wid-id-0"
					data-widget-editbutton="false" data-widget-colorbutton="false"
					data-widget-editbutton="false" data-widget-togglebutton="false"
					data-widget-deletebutton="false"
					data-widget-fullscreenbutton="false"
					data-widget-custombutton="false" data-widget-collapsed="false"
					data-widget-sortable="false">
					<header>
						<span class="widget-icon"><i class="fa fa-pencil-square-o"></i></span>
						<h2><?php echo __('Attendance infomation', array(), 'messages') ?></h2>
					</header>				
	          <?php include_partial('psLogtimes/form', array('ps_logtimes' => $ps_logtimes, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper, 'check_current_date' => $check_current_date))?>
				</div>
			</article>
			<div id="sf_admin_footer" class="no-border no-padding">
          		<?php include_partial('psLogtimes/form_footer', array('ps_logtimes' => $ps_logtimes, 'form' => $form, 'configuration' => $configuration))?>
        	</div>
		</div>
	</div>
</section>
