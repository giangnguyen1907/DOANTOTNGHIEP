<?php use_helper('I18N', 'Date')?>
<?php include_partial('psCmsNotification/assets')?>
<?php $type='drafts'?>
<section id="widget-grid">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <?php include_partial('psCmsNotification/flashes')?>
       <div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-togglebutton="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-pencil-square-o"></i></span>
					<h2><?php echo __('Edit Notification', array(), 'messages') ?></h2>
				</header>


				<div class="no-padding">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
						<?php include_partial('psCmsNotification/menu', array('type' => $type))?>
					
                    	</div>
						<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">


							<br> <br>
							<div id="sf_admin_content">
          <?php include_partial('psCmsNotification/form', array('ps_cms_notifications' => $ps_cms_notifications, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper))?>
        </div>

						</div>
					</div>

				</div>
			</div>
		</article>
	</div>
</section>
<script>
$(document).ready(function() {


	$('.input_text').keyup(function(){
	    $( "#remainingInput_text" ).html( this.value.length + '/' + $(this).attr('maxLength') );
	  });

	$('.input_textarea').keyup(function(){
	    $( "#remainingInput_textarea" ).html( this.value.length + '/' + $(this).attr('maxLength') );
	  });
	  

	$('.is_all').on('change', function() {

		   if($(".is_all:checked").val() == '1'){
			   $('._checkbox').prop('checked',true);
			   }
		   else if ($(".is_all:checked").val() == '0'){
			   $('._checkbox').prop('checked',false);
			   }
		});
	$('.is_system').on('change', function() {

		   if($(".is_system:checked").val() == '1'){
			   $('._checkbox').prop('checked',true);
			   $('.is_all').attr('disabled', true);
			   }
		   else if ($(".is_system:checked").val() == '0'){
			   $('._checkbox').prop('checked',false);
			   $('.is_all').attr('disabled', false);
			   $('.is_all[value="0"]').prop('checked',true);
			   }
		});
	$('._checkbox').on('change', function() {
		$('.is_all').attr('disabled', false);
		$('.is_all[value="0"]').prop('checked',true);
		$('.is_system[value="0"]').prop('checked',true);		
	});
	
});
</script>