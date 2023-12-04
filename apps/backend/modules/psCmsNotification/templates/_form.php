<?php use_stylesheets_for_form($form)?>
<?php use_javascripts_for_form($form)?>
<script type="text/javascript">
	$(document).on("ready", function(){

		$('.input_text').keyup(function(){
		    $( "#remainingInput_text" ).html( this.value.length + '/' + $(this).attr('maxLength') );
		  });

// 		$('.input_textarea').keyup(function(){
// 		    $( "#remainingInput_textarea" ).html( this.value.length + '/' + $(this).attr('maxLength') );
// 		  });
		
		$('#ps_cms_notifications_is_system').on('change', function() {
			
			if($("#ps_cms_notifications_is_system").val() >= 1){

                $('#ps_cms_notifications_ps_customer_id').val(null);
                $('#ps_cms_notifications_ps_customer_id').select2('val',null);
                
                $('#ps_cms_notifications_ps_workplace_id').val(null);
                $('#ps_cms_notifications_ps_workplace_id').select2('val',null);

                $('#ps_cms_notifications_ps_customer_id').attr('disabled', 'disabled');
                $('#ps_cms_notifications_ps_workplace_id').attr('disabled', 'disabled');
				
//                 $('.checkbox').prop('checked',true);
                $('.checkbox').attr('disabled','disabled');
                $('.btn-psadmin').attr('disabled', false);
                $('#ps_cms_notifications_is_all').attr('disabled', 'disabled');
                $('#ps_cms_notifications_is_object').attr('disabled', 'disabled');

			} else if ($("#ps_cms_notifications_is_system").val() == '0'){
				
			   $('.checkbox').prop('checked',false);
			   $('.checkbox').attr('disabled',false);
			   $('#ps_cms_notifications_is_all').attr('disabled', false);
			   $('#ps_cms_notifications_is_object').attr('disabled', false);
			   $('#ps_cms_notifications_ps_customer_id').attr('disabled', false);
			   $('#ps_cms_notifications_ps_workplace_id').attr('disabled', false);
			   
		   }
		});
		
		$('#ps_cms_notifications_is_all').on('change', function() {
			
			if($("#ps_cms_notifications_is_all").val() == '1'){
			   $('.checkbox').attr('disabled','disabled');
			   $('.btn-psadmin').attr('disabled', false);
			   $('#ps_cms_notifications_is_object').attr('disabled', 'disabled');
			}else if ($("#ps_cms_notifications_is_all").val() == '2'){
			   $('.checkbox').prop('checked',true);
			   $('.checkbox').attr('disabled','disabled');
			   $('.btn-psadmin').attr('disabled', false);
			   $('#ps_cms_notifications_is_object').attr('disabled', 'disabled');
			}else if ($("#ps_cms_notifications_is_all").val() == '0'){
			   $('.checkbox').prop('checked',false);
			   $('.checkbox').attr('disabled',false);
			   $('.btn-psadmin').attr('disabled', 'disabled');
			   $('.check-all-teacher').attr('disabled',false);
			   $('.check-all-relative').attr('disabled',false);
			   $('#ps_cms_notifications_is_object').attr('disabled', false);
			   $('.checkbox-all').attr('disabled',false);
			}
		});

		$('#ps_cms_notifications_is_object').on('change', function() {

		   if($("#ps_cms_notifications_is_object").val() == '1'){
			   $('._check_teacher').prop('checked',true);
			   $('._check_relative').prop('checked',false);
			   $('.checkbox').attr('disabled','disabled');
			   $('.btn-psadmin').attr('disabled', false);
			   $('.check-all-teacher').prop('checked',true);
			   $('.check-all-relative').prop('checked',false);
			   $('.check-all').prop('checked',false);
			   $('.check_class').prop('checked',false);
			   }
		   else if ($("#ps_cms_notifications_is_object").val() == '2'){
			   $('._check_relative').prop('checked',true);
			   $('._check_teacher').prop('checked',false);
			   $('.checkbox').attr('disabled','disabled');
			   $('.btn-psadmin').attr('disabled', false);
			   $('.check-all-relative').prop('checked',true);
			   $('.check-all-teacher').prop('checked',false);
			   $('.check-all').prop('checked',false);
			   $('.check_class').prop('checked',false);
			   }
		   else if ($("#ps_cms_notifications_is_object").val() == '0'){
			   $('._check_all').prop('checked',false);
			   $('._check_all').attr('disabled',false);
			   $('.check-all-teacher').attr('disabled',false);
			   $('.check-all-relative').attr('disabled',false);
			   $('.btn-psadmin').attr('disabled', 'disabled');
			   $('.check-all-teacher').prop('checked',false);
			   $('.check-all-relative').prop('checked',false);
			   $('.checkbox').attr('disabled',false);
			   $('.checkbox').prop('checked',false);
			   }
		});
		
		// BEGIN: filters
		$('#ps_cms_notifications_ps_customer_id').change(function() {

			resetOptions('ps_cms_notifications_ps_workplace_id');
			$('#ps_cms_notifications_ps_workplace_id').select2('val','');
			
			if ($(this).val() > 0) {

				$("#ps_cms_notifications_ps_workplace_id").attr('disabled', 'disabled');
				
				$.ajax({
					url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' + $(this).val(),
			        type: "POST",
			        data: {'psc_id': $(this).val()},
			        processResults: function (data, page) {
		          		return {
		            		results: data.items
		          		};
		        	},
			    }).done(function(msg) {

			    	$('#ps_cms_notifications_ps_workplace_id').select2('val','');

					$("#ps_cms_notifications_ps_workplace_id").html(msg);

					$("#ps_cms_notifications_ps_workplace_id").attr('disabled', null);

			    });
			}		
		});

		$('#ps_cms_notifications_ps_workplace_id').change(function() {
			var wp_id = $(this).val();

			$('#ic-loading').show();		
			$.ajax({
		        url: '<?php echo url_for('@ps_cms_notification_load_ajax') ?>',
		        type: 'POST',
		        data: 'c_id=' + $('#ps_cms_notifications_ps_customer_id').val() + '&w_id=' + $('#ps_cms_notifications_ps_workplace_id').val() + '&y_id=' + $('#ps_cms_notifications_ps_school_year_id').val(),
		        success: function(data) {
		        	$('#ic-loading').hide();
		        	$('#load-ajax').html(data);
		        },
		        error: function (request, error) {
		            alert(" Can't do because: " + error);
		        },
			});
		});
	});
	
</script>
<div class="sf_admin_form widget-body">
  <?php echo form_tag_for($form, '@ps_cms_notifications_ps_cms_notification', array('class' => 'form-horizontal', 'id' => 'ps-form-notication', 'data-fv-addons' => 'i18n'))?>
    <?php echo $form->renderHiddenFields(true)?>

    <?php if ($form->hasGlobalErrors()): ?>
      <?php echo $form->renderGlobalErrors()?>
    <?php endif; ?>
	
	<div class="row">
	
	<?php foreach ($configuration->getFormFields($form, $form->isNew() ? 'new' : 'edit') as $fieldset => $fields): ?>
	  
      <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
      <?php //include_partial('psCmsNotification/form_fieldset', array('ps_cms_notifications' => $ps_cms_notifications, 'form' => $form, 'fields' => $fields, 'fieldset' => $fieldset)) ?>
			<div class="col-md-12">
				<input class="form-control input_text form-control"  type="hidden" name="ps_cms_notifications[root_screen]" id="ps_cms_notifications_root_screen" value="Notification">
				<input class="form-control input_text form-control"  type="hidden" name="ps_cms_notifications[is_status]" id="ps_cms_notifications_is_status" value="sent">
			</div>
          <div class="form-group">
				<div class='col-md-12'>
              	<?php echo $form['title']->renderLabel()?>
              </div>
				<div class="col-md-12">
            	<?php echo $form['title']->render()?>
            	<span id='remainingInput_text' class="note pull-right">0/150</span>
					<small class="help-block" data-fv-result="INVALID"><?php echo $form['title']->renderError() ?></small>
				</div>
			</div>

			<div class="form-group">
				<div class='col-md-12'>
	            	<?php echo $form['description']->renderLabel()?>
	            </div>
				<div class="col-md-12">
            		<?php echo $form['description']->render()?>
				</div>
			</div>
           
           <?php if (myUser::credentialPsCustomers('PS_CMS_NOTIFICATIONS_SYSTEM')) :?>
            <div class="form-group">
				<div class='col-md-12'>
            <?php echo $form['is_system']->renderLabel()?>
            </div>
				<div class="col-md-12">
            	<?php echo $form['is_system']->render()?>
            	<small class="help-block" data-fv-result="INVALID"><?php echo $form['is_system']->renderError() ?></small>
				</div>
			</div>
            <?php endif;?>
            
            <?php if ($sf_user->hasCredential(array('PS_CMS_NOTIFICATIONS_SYSTEM')) || $sf_user->hasCredential(array('PS_CMS_NOTIFICATIONS_ALL'))): ?>
            <div class="form-group">
				<div class='col-md-12'>
            	<?php echo $form['is_all']->renderLabel()?>
            	</div>
				<div class="col-md-12">
            	<?php echo $form['is_all']->render()?>
            	<small class="help-block" data-fv-result="INVALID"><?php echo $form['is_all']->renderError() ?></small>
				</div>
			</div>
            <?php endif;?>
            
            <?php if ($sf_user->hasCredential(array('PS_CMS_NOTIFICATIONS_WORKPLACE')) || $sf_user->hasCredential(array('PS_CMS_NOTIFICATIONS_ALL'))): ?>
            <div class="form-group">
				<div class='col-md-12'>
            	<?php echo $form['is_object']->renderLabel()?>
            	</div>
				<div class="col-md-12">
            	<?php echo $form['is_object']->render()?>
            	<small class="help-block" data-fv-result="INVALID"><?php echo $form['is_object']->renderError() ?></small>
				</div>
			</div>
	  		<?php endif;?>
	  </div>

		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
	  	 <?php if ($sf_user->hasCredential(array('PS_CMS_NOTIFICATIONS_FILTER_SCHOOL'))): ?>
	  	 <div class="form-controll">
				<label>
            		<?php echo $form['ps_customer_id']->render()?>
            		<?php echo $form['ps_customer_id']->renderError()?>
            	</label> <label>
            		<?php echo $form['ps_workplace_id']->render()?>
            		<?php echo $form['ps_workplace_id']->renderError()?>
            	</label>
			</div>
         <?php elseif ($sf_user->hasCredential(array('PS_CMS_NOTIFICATIONS_WORKPLACE'))): ?>
	  	 <div class="form-controll">
				<label>
            		<?php echo $form['ps_workplace_id']->render()?>
            		<?php echo $form['ps_workplace_id']->renderError()?>
            	</label>
			</div> 
         <?php endif;?>
         
         <?php
		$school_id = $ps_school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()
			->fetchOne ()
			->getId ();
		$customer_id = myUser::getPscustomerID ();
		$ps_member_id = myUser::getUser ()->getMemberId ();
		$workplace_id = $ps_workplace_id = myUser::getWorkPlaceId ( $ps_member_id );

		$my_class = Doctrine::getTable ( 'MyClass' )->setClassByParams ( array (
				'ps_school_year_id' => $school_id,
				'ps_customer_id' => $customer_id,
				'ps_workplace_id' => $workplace_id,
				'is_activated' => PreSchool::ACTIVE ) )
			->execute ();
		?>
	  	 <div id="load-ajax">
    		<?php include_partial('psCmsNotification/table_list_class', array('my_class' => $my_class))?>
    	 </div>
		</div>
	  
    <?php endforeach; ?>
    </div>
    <?php include_partial('psCmsNotification/form_actions', array('ps_cms_notifications' => $ps_cms_notifications, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper))?>
  </form>
</div>
