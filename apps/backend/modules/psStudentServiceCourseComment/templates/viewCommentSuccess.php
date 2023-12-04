<?php use_helper('I18N', 'Date')?>
<?php include_partial('psStudentServiceCourseComment/assets')?>

<section id="widget-grid">
 <?php include_partial('psStudentServiceCourseComment/flashes')?>
	<div class="row">
		<article
			class="col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">
			<div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-pencil-square-o"></i></span>
					<h2><?php echo __('Service course comment', array(), 'messages') ?></h2>
				</header>
				<div id="sf_admin_header" class="no-margin no-padding no-border"></div>
				<br>
				<article
					class="col-xs-12 col-sm-12 col-md-4 col-lg-4 sortable-grid ui-sortable">
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
				<?php include_partial('psStudentServiceCourseComment/student', array('formFilter' => $formFilter, 'filter_list_student' => $filter_list_student, 'helper' => $helper, 'student_id' => $student_id))?>		
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
							<h2><?php echo __('Course comment', array(), 'messages') ?></h2>
						</header>	
				<?php include_partial('psStudentServiceCourseComment/comment', array('formFilter' => $formFilter, 'helper' => $helper, 'comment_list' => $comment_list))?>

			</div>
					<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-right">			
 				<?php echo $helper->linkToList(array(  'params' =>   array(  ),  'class_suffix' => 'list',  'label' => 'Back to list',)) ?>
			</div>
				</article>

			</div>
		</article>
	</div>
</section>
