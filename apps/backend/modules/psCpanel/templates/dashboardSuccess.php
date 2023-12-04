<?php use_helper('I18N', 'Date') ?>
<section id="widget-grid">
	<div class="row ">
		<article class="col-sm-12 col-md-12 col-lg-12 text-center">			
			<?php include_partial('psCpanel/function_top', array('total_workplaces' => $total_workplaces,'total_class' => $total_class, 'total_student' => $total_student, 'total_student_not_in_class' => $total_student_not_in_class,'schoolYears' => $schoolYears)) ?>
		</article>
	</div>
	<div class="row">
		<article class="col-sm-12 col-md-12 col-lg-12">			
			<?php //include_partial('psCpanel/widget_top', array('total_workplaces' => $total_workplaces,'total_class' => $total_class, 'total_student' => $total_student, 'total_student_not_in_class' => $total_student_not_in_class,'schoolYears' => $schoolYears)) ?>
		</article>
	</div>
	<div class="row">
		<article class="col-sm-12 col-md-8 col-lg-8 sortable-grid ui-sortable">
			<?php include_partial('psCpanel/widget_attendance_new', array('workplaces' => $workplaces));?>
		</article>
		
		<article class="col-sm-12 col-md-4 col-lg-4 sortable-grid ui-sortable">
			<?php include_partial('psCpanel/widget_student_birthday', array('student_birthday' => $student_birthday,'workplaces' => $workplaces));?>
		</article>

	</div>

	<div class="row">
		<article class="col-sm-12 col-md-4 col-lg-4 sortable-grid ui-sortable">
			<?php include_partial('psCpanel/widget_article', array('articles' => $articles));?>
		</article>

		<article class="col-sm-12 col-md-4 col-lg-4 sortable-grid ui-sortable">
			<?php include_partial('psCpanel/widget_student_statistic', array('student_statistic' => $student_statistic));?>
		</article>


		<article class="col-sm-12 col-md-4 col-lg-4 sortable-grid ui-sortable">
			<?php include_partial('psCpanel/widget_user_relatives_statistic', array('number_users_active' => $number_users_active, 'number_users' => $number_users, 'number_users_online' => $number_users_online )) ?>
		</article>

	</div>

	</div>

	<!--  
	<div class="row">		
		
		<article class="col-sm-12 col-md-12 col-lg-6 sortable-grid ui-sortable">
			<?php include_partial('psCpanel/widget_feature') ?>
		</article>
		
		<article class="col-sm-12 col-md-12 col-lg-6 sortable-grid ui-sortable">
			<?php include_partial('psCpanel/widget_statistical') ?>
		</article>
	</div>-->
</section>
