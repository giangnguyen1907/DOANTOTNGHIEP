<?php use_helper('I18N', 'Date') ?>
<?php include_partial('psServiceCourseSchedules/assets2') ?>
<?php include_partial('global/include/_box_modal_warning');?>
<section id="widget-grid">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
          
      <?php include_partial('psServiceCourseSchedules/flashes')?>
      
      <div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-togglebutton="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-calendar-o"></i></span>
					<h2><?php echo __('Course schedules', array(), 'messages') ?></h2>
				</header>
        <?php include_partial('psServiceCourseSchedules/week_schedules_personal', array('list_course_schedules' => $list_course_schedules, 'week_list' => $week_list, 'width_th' => (100 / (count($week_list) + 1)),'formFilter' => $formFilter));?>
      </div>
		</article>
	</div>
</section>