<?php use_helper('I18N', 'Date') ?>
<?php include_partial('psChatTime/assets') ?>

<section id="widget-grid">
	<!--  sf_admin_container -->
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      
      <?php include_partial('psChatTime/flashes') ?>

      <div class="jarviswidget " id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-togglebutton="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-pencil-square-o"></i></span>
					<h2><?php echo __('Edit PsChatTime', array(), 'messages') ?></h2>
				</header>

				<div id="sf_admin_header" class="no-margin no-padding no-border">
          <?php include_partial('psChatTime/form_header', array('ps_chat_time' => $ps_chat_time, 'form' => $form, 'configuration' => $configuration)) ?>
        </div>

				<div id="sf_admin_content">
          <?php include_partial('psChatTime/form', array('ps_chat_time' => $ps_chat_time, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
        </div>

				<div id="sf_admin_footer" class="no-border no-padding">
          <?php include_partial('psChatTime/form_footer', array('ps_chat_time' => $ps_chat_time, 'form' => $form, 'configuration' => $configuration)) ?>
        </div>
			</div>
		</article>
	</div>
</section>
<script type="text/javascript">
  $(document).ready(function(){
        $('.time_picker').timepicker({
        timeFormat : 'HH:mm',
        showMeridian : false,
        defaultTime : null
      });
  });
  
</script>
