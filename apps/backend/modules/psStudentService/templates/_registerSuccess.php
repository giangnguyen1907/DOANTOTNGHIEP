<?php use_helper('I18N', 'Date')?>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">×</button>

	<h4 class="modal-title"><?php echo __('Register courses for student', array(), 'messages') ?>. <?php echo __('Subject');?>: <?php echo $ps_service_courses->getPsService()->getTitle()?> - <?php echo __('Courses');?>: <?php echo $ps_service_courses->getTitle()?></h4>
</div>

<?php echo form_tag_for($form, '@ps_student_service_register', array('class' => 'form-horizontal', 'id' => 'ps-form', 'data-fv-addons' => 'i18n')) ?>
<div class="modal-body" style="overflow: hidden;">
	<div class="form-group">
		<div class="col-md-4"><?php echo $formFilter['keywords']->render(array('class' => 'form-control', 'placeholder' => sfContext::getInstance ()->getI18n ()->__ ( 'Keywords: Student code, first name, last name' )))?></div>
		<div class="col-md-4">
			<a class="btn btn-default btn-sm btn-success" id="btn_search"><i
				class="fa fa-search"></i> <?php echo __('Search')?></a>
		</div>


	</div>
	
<?php include_partial('psStudentService/form_register', array('list_student' => $list_student, 'ps_service_courses' => $ps_service_courses, 'form' => $form, 'pager' => $pager))?>

</div>

<div class="modal-footer">

<?php echo $helper->linkToCancel(array(  'params' =>   array(  ),  'class_suffix' => 'cancel',  'label' => 'Cancel',)) ?>
<button type="submit" onclick="return addService_Click();"
		class="btn btn-default btn-success btn-sm btn-psadmin">
		<i class="fa-fw fa fa-floppy-o" aria-hidden="true"></i><?php echo __('Save')?>
</button>

</div>

</form>
<style>
<!--
.no-search .select2-search {
	display: none
}
-->
</style>
<script type="text/javascript">
$(document).ready(function() {	
	
	$( "#btn_search" ).click(function() {
		psOnSearchStudent();
	});
});

function psOnSearchStudent() {
	$("#loading").show();
	$('#list_student_main').html('');		
	$.ajax({
        url: '<?php echo url_for('@ps_student_by_keywords?keywords=') ?>' + $('#student_filter_keywords').val() + '&ps_service_course_id=<?php echo $ps_service_courses->getId()?>',          
        type: 'POST',
        data: 'f=<?php echo md5(time().time().time().time())?>&keywords=' + $('#student_filter_keywords').val()+ '&ps_service_course_id=<?php echo $ps_service_courses->getId()?>',
        success: function(data) {
        	$('#list_student_main').html(data);
        	$("#loading").hide();    			
        }
	});
}



function addService_Click() {

	if (!CheckRelationship()) {
		alert('<?php echo __('You have not selected any student !')?>');
		
		return false;
	}

}
function CheckRelationship() {
	 var boxes = document.getElementsByTagName('input');
	 for (i = 0; i < boxes.length; i++ ) {
			box = boxes[i];
			if ( box.type == 'checkbox' && box.className == 'select checkbox') {						
				if (box.checked == true)
		  		 return true;	
		  	}
		  }
  return false;		   
}

	
</script>




