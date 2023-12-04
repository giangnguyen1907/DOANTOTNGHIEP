<?php use_helper('I18N', 'Date')?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">X</button>
	<h4 class="modal-title"><?php echo __('Comment week of student').$ps_student->getFirstName().' '.$ps_student->getLastName().', '.__('Birthday').': '.date('d/m/Y', strtotime($ps_student->getBirthday())) ?></h4>
</div>

<?php echo form_tag_for($form, '@ps_comment_week', array('class' => 'form-horizontal', 'id' => 'form_ps_comment_week', 'data-fv-addons' => 'i18n')) ?>
<div class="modal-body">
	<?php include_partial('psCommentWeek/form', array('ps_comment_week' => $ps_comment_week,'form' => $form, 'configuration' => $configuration, 'helper' => $helper))?>
</div>

<div class="modal-footer">	
		<?php //include_partial('psFeatureBranchTimes/form_actions_custom', array('ps_fee_receivable_student' => $ps_fee_receivable_student, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
</div>
</form>

<script type="text/javascript"
	src="<?php echo sfContext::getInstance()->getRequest()->getRelativeUrlRoot();?>/psAdminThemePlugin/js/plugin/tinymce/tinymce.min.js"></script>
<script type="text/javascript"
	src="<?php echo sfContext::getInstance()->getRequest()->getRelativeUrlRoot();?>/psAdminThemePlugin/js/modules/ps_comment_week.js"></script>

<script>
$('#ps_comment_week_ps_year, #ps_comment_week_ps_week,#ps_comment_week_ps_month').select2({
	dropdownParent: $('#remoteModal')
});
$(document).ready(function() {	
    $('#ps_comment_week_ps_month').change(function() {

    	if ($(this).val() > 0) {
    		$("#ps_comment_week_ps_week").attr('disabled', 'disabled');
        }else{
        	$("#ps_comment_week_ps_week").attr('disabled', false);
        }
    	
    });

    $('#ps_comment_week_ps_year').change(function() {

    	$("#ps_comment_week_ps_week").attr('disabled', 'disabled');
    	resetOptions('ps_comment_week_ps_week');
    	
    	$.ajax({
            url: '<?php echo url_for('@ps_menus_weeks_year') ?>',
            type: "POST",
            data: {'ps_year': $(this).val()},
            processResults: function (data, page) {
                return {
                  results: data.items  
                };
            },
  		}).done(function(msg) {
  			 $('#ps_comment_week_ps_week').select2('val','');
 			 $("#ps_comment_week_ps_week").html(msg);
  			 $("#ps_comment_week_ps_week").attr('disabled', null);

  			$('#ps_comment_week_ps_week').val(1);
			$('#ps_comment_week_ps_week').change();
  		});
    });
    
});
</script>
