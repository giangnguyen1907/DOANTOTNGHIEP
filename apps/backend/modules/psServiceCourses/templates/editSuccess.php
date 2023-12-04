<?php use_helper('I18N', 'Date') ?>
<?php include_partial('psServiceCourses/assets') ?>

<section id="widget-grid">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      
      <?php include_partial('psServiceCourses/flashes') ?>

      <div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-togglebutton="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-pencil-square-o"></i></span>
					<h2><?php echo __('Edit service courses', array(), 'messages') ?></h2>
					<ul class="nav nav-tabs pull-right in" id="myTab">
    		<?php
						$index = 1;
						foreach ( $configuration->getFormFields ( $form, $form->isNew () ? 'new' : 'edit' ) as $fieldset => $fields ) :
							?>
    		<li class="pull-right"><a data-toggle="tab"
							href="#pstab_<?php echo $index;?>"> <span>
            			<?php if ('NONE' != $fieldset):?>
                    		<?php echo __($fieldset, array(), 'messages') ?>
                    	<?php endif;?>
        			</span>
						</a></li>
    		<?php

$index ++;
						endforeach
						;
						?>
    		</ul>
				</header>

				<div id="sf_admin_header" class="no-margin no-padding no-border">
          <?php include_partial('psServiceCourses/form_header', array('ps_service_courses' => $ps_service_courses, 'form' => $form, 'configuration' => $configuration)) ?>
        </div>

				<div id="sf_admin_content">
          <?php include_partial('psServiceCourses/form', array('ps_service_courses' => $ps_service_courses,'list_student' => $list_student, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
        </div>

				<div id="sf_admin_footer" class="no-border no-padding">
          <?php include_partial('psServiceCourses/form_footer', array('ps_service_courses' => $ps_service_courses, 'form' => $form, 'configuration' => $configuration)) ?>
        </div>
			</div>
		</article>
	</div>
</section>

<script type="text/javascript">
$(document).ready(function() {    

    var hash = window.location.hash;
    if (hash == '')
    hash = '#pstab_1';	
	$('#myTab a[href="' + hash + '"]').tab('show');    

});    
</script>