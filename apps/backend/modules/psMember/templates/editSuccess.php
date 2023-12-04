<?php use_helper('I18N', 'Date') ?>
<?php include_partial('psMember/assets') ?>

<section id="widget-grid">
	<!--  sf_admin_container -->
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      
      <?php include_partial('psMember/flashes') ?>

      <div class="jarviswidget " id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-togglebutton="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-pencil-square-o"></i></span>
					<h2><?php echo __('Edit PsMember: %%name%%', array('%%name%%' => $ps_member->getFirstName() . " " . $ps_member->getLastName()), 'messages') ?></h2>

					<ul class="nav nav-tabs pull-right in" id="myTab">
            <?php
												$index = 1;
												foreach ( $configuration->getFormFields ( $form, $form->isNew () ? 'new' : 'edit' ) as $fieldset => $fields ) :
													?>
                <li class="pull-right"><a data-toggle="tab"
							href="#pstab_<?php echo $index; ?>"> <span>
                			<?php
													if ('NONE' != $fieldset) :
														echo __ ( $fieldset, array (), 'messages' );
                			 endif;

													?>
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
          <?php include_partial('psMember/form_header', array('ps_member' => $ps_member, 'form' => $form, 'configuration' => $configuration)) ?>
        </div>

				<div id="sf_admin_content">
          <?php include_partial('psMember/form', array('ps_member' => $ps_member, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
        </div>

				<div id="sf_admin_footer" class="no-border no-padding">
          <?php include_partial('psMember/form_footer', array('ps_member' => $ps_member, 'form' => $form, 'configuration' => $configuration)) ?>
        </div>
			</div>
		</article>
	</div>
  <?php
		$url_callback = PsEndCode::ps64EndCode ( (sfContext::getInstance ()->getRouting ()
			->getCurrentRouteName () . '?id=' . $form->getObject ()
			->getId ()) );
		include_partial ( 'psMember/box_modal_confirm_remover_member_department', array (
				'url_callback' => $url_callback ) );
		?>
</section>

<script type="text/javascript">
$(document).ready(function() {    

    var hash = window.location.hash;
    if (hash == '')
    hash = '#pstab_1';	
	$('#myTab a[href="' + hash + '"]').tab('show');    

});    
</script>