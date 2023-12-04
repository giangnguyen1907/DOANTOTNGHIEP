<?php use_helper('I18N', 'Date') ?>
<?php include_partial('psServiceCourseSchedules/assets') ?>
<?php include_partial('global/include/_box_modal_warning');?>

<section id="widget-grid">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      
      <?php include_partial('psServiceCourseSchedules/flashes') ?>

      <div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-togglebutton="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-pencil-square-o"></i></span>
					<h2><?php echo __('Course schedules', array(), 'messages') ?></h2>
				</header>
				<div>
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
							<div class="jarviswidget" id="wid-id-2"
								data-widget-editbutton="false" data-widget-colorbutton="false"
								data-widget-editbutton="false" data-widget-togglebutton="false"
								data-widget-deletebutton="false"
								data-widget-fullscreenbutton="false"
								data-widget-custombutton="false" data-widget-collapsed="false"
								data-widget-sortable="false">
								<header>
									<span class="widget-icon"> <i class="fa fa-calendar"></i>
									</span>
									<h2><?php echo __('Schedules', array(), 'messages') ?></h2>
								</header>				
						<?php include_partial('psServiceCourseSchedules/week_schedules', array('list_course_schedules' => $list_course_schedules, 'week_list' => $week_list, 'width_th' => (100 / (count($week_list) + 1)),'formFilter' => $formFilter, 'form' => $form, 'ps_service_course_schedules' => $ps_service_course_schedules));?>
					</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
							<div class="jarviswidget" id="wid-id-1"
								data-widget-editbutton="false" data-widget-colorbutton="false"
								data-widget-editbutton="false" data-widget-togglebutton="false"
								data-widget-deletebutton="false"
								data-widget-fullscreenbutton="false"
								data-widget-custombutton="false" data-widget-collapsed="false"
								data-widget-sortable="false">
								<header>
							<?php if ($form->isNew()) : ?>
                            <h2><?php echo __('New course schedules', array(), 'messages') ?></h2>
                            <?php else:?>
                            <h2><?php echo __('Edit course schedules', array(), 'messages') ?></h2>
                            <?php endif;?>
                            </header>
								<div>
							<?php include_partial('psServiceCourseSchedules/form', array('ps_service_course_schedules' => $ps_service_course_schedules, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
				        	</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</article>
	</div>
</section>