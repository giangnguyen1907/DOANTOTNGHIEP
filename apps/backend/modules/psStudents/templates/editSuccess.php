<?php use_helper('I18N', 'Date') ?>
<?php include_partial('psStudents/assets') ?>

<section id="widget-grid">
	<!--  sf_admin_container -->
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      
       <?php include_partial('psStudents/flashes') ?>

       <div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-togglebutton="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-pencil-square-o"></i></span>
					<h2><?php echo __('Edit Students: %%first_name%% %%last_name%%', array('%%first_name%%' => $student->getFirstName(), '%%last_name%%' => $student->getLastName()), 'messages') ?>
           <span>
				(<?php if (false !== strtotime($student->getBirthday())) echo format_date($student->getBirthday(), "dd-MM-yyyy").'<code>'.PreSchool::getAge($student->getBirthday(),false).'</code>'; if ($currentClass) echo ', '.__('Class').': '.$currentClass->getName();?>)
		   </span>
					</h2>
					<ul class="nav nav-tabs pull-right in" id="myTab">
    		<?php

$index = 1;
						foreach ( $configuration->getFormFields ( $form, $form->isNew () ? 'new' : 'edit' ) as $fieldset => $fields ) :
							?>
    		
    		<?php
							$count_field = count ( $fields );
							$not_show = 0;

							foreach ( $fields as $name => $field ) :
								?>
    		
    		<?php if ((isset($form[$name]) && $form[$name]->isHidden()) || (!isset($form[$name]) && $field->isReal())) $not_show++; ?>
    		
    		<?php endforeach; ?>
    		
    		<?php if($count_field != $not_show): ?>
    		
    		<li
							class="<?php if ($index == 1):?> active <?php endif;?> pull-right">
							<a data-toggle="tab" href="#pstab_<?php echo $index;?>"> <span>
            			<?php if ('NONE' != $fieldset):?>
                    		<?php echo __($fieldset, array(), 'messages') ?>
                    	<?php endif;?>
        			</span>
						</a>
						</li>
    		<?php endif;?>
    		<?php $index++;endforeach;?>
    		</ul>
				</header>

				<div id="sf_admin_header" class="no-margin no-padding no-border">
          <?php include_partial('psStudents/form_header', array('student' => $student, 'form' => $form, 'configuration' => $configuration)) ?>
        </div>

				<div id="sf_admin_content">
          <?php include_partial('psStudents/form', array('student' => $student,'list_class' => $list_class, 'list_service' => $list_service, 'list_relative' => $list_relative , 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
        </div>

				<div id="sf_admin_footer" class="no-border no-padding">
          <?php include_partial('psStudents/form_footer', array('student' => $student, 'form' => $form, 'configuration' => $configuration)) ?>
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