<?php use_helper('I18N', 'Date') ?>
<?php include_partial('psClass/assets') ?>
<section id="widget-grid">
	<?php include_partial('psClass/flashes')?>
	<div class="row">
		<article class="col-sm-12 col-md-12 col-lg-12">
			<div class="jarviswidget" id="wid-id-0"
				data-widget-colorbutton="false" data-widget-editbutton="false"
				data-widget-togglebutton="false" data-widget-deletebutton="false"
				data-widget-fullscreenbutton="false"
				data-widget-custombutton="false" data-widget-collapsed="false"
				data-widget-sortable="false">
				<header>
					<span class="widget-icon"><i class="fa fa-random"></i></span>
					<h2><?php echo __('Move class', array(), 'messages') ?></h2>
				</header>
				<div class="widget-body" style="overflow: hidden;">
					<div class="row">
						<article class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
							<div class="jarviswidget" id="wid-id-1"
								data-widget-editbutton="false" data-widget-colorbutton="false"
								data-widget-editbutton="false" data-widget-togglebutton="false"
								data-widget-deletebutton="false"
								data-widget-fullscreenbutton="false"
								data-widget-custombutton="false" data-widget-collapsed="false"
								data-widget-sortable="false">
								<header>
									<h2><?php echo __('From class', array(), 'messages') ?></h2>
								</header>
						<?php include_partial('psClass/student', array('form' => $form,'formFilter' => $formFilter,'filter_list_student' => $filter_list_student,'class_from_id' => $class_id, 'class_to_id' => $class_to_id))?>
						</div>
						</article>
						<article class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
							<div class="jarviswidget" id="wid-id-2"
								data-widget-editbutton="false" data-widget-colorbutton="false"
								data-widget-editbutton="false" data-widget-togglebutton="false"
								data-widget-deletebutton="false"
								data-widget-fullscreenbutton="false"
								data-widget-custombutton="false" data-widget-collapsed="false"
								data-widget-sortable="false">
								<header>
									<h2><?php echo __('To class', array(), 'messages') ?></h2>
								</header>				
		           		<?php include_partial('psClass/student2', array('formFilter' => $formFilter, 'list_student_class_to' => $list_student_class_to))?>
						</div>
						</article>
					</div>
				</div>
			</div>
		</article>
	</div>
</section>
<?php include_partial('global/include/_box_modal_warning');?>