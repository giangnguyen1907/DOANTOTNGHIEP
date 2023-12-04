<?php use_helper('I18N', 'Date')?>
<style>
@media ( min-width : 992px) .modal-lg {
	min-width
	:
	 
	900
	px
	;
	
	    
	width
	:
	 
	1200
	px
	;
	
	
}

.modal-lg {
	min-width: 900px;
	width: 1200px;
}
</style>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">×</button>

	<h4 class="modal-title">
	<?php echo __('Assigned relative for student: %%ps_student%%', array('%%ps_student%%' => $ps_student->getFirstName().' '.$ps_student->getLastName()), 'messages') ?></h4>
</div>

<?php echo form_tag_for($form, '@ps_relative_student', array('class' => 'form-horizontal', 'id' => 'ps-form', 'data-fv-addons' => 'i18n')) ?>
<div class="modal-body" style="overflow: hidden;">
	<div class="form-group">
		<div class="col-md-4"><?php echo $formFilter['keywords']->render(array('class' => 'form-control', 'placeholder' => sfContext::getInstance ()->getI18n ()->__ ( 'Keywords: First name, last name, mobile' )))?></div>
		<div class="col-md-4">
			<a class="btn btn-default btn-sm btn-success" id="btn_search"><i
				class="fa fa-search"></i> <?php echo __('Search')?></a>
		</div>
	</div>
	
<?php include_partial('psRelativeStudent/form', array('relative_student' => $relative_student,'ps_student' => $ps_student, 'list_relative' => $list_relative, 'form' => $form, 'pager' => $pager))?>
</div>

<div class="modal-footer">
	<?php include_partial('psRelativeStudent/form_actions', array('relative_student' => $relative_student, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper))?>
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
	
	$("#relative_filter_keywords").bind("keypress", function (e) {
	    if (e.keyCode == 13) {
	    	psOnSearchRelative();
	    	event.preventDefault();
	        return false;
	    }
	});

	$( "#btn_search" ).click(function() {
		psOnSearchRelative();
	});
});

function psOnSearchRelative() {
	$("#loading").show();
	$('#list_relative_main').html('');		
	$.ajax({
        url: '<?php echo url_for('@ps_relative_by_keywords?keywords=') ?>' + $('#relative_filter_keywords').val() + '&student_id=<?php echo $ps_student->getId()?>',          
        type: 'POST',
        data: 'f=<?php echo md5(time().time().time().time())?>&keywords=' + $('#relative_filter_keywords').val()+ '&student_id=<?php echo $ps_student->getId()?>',
        success: function(data) {
        	$('#list_relative_main').html(data);
        	$("#loading").hide();    			
        }
	});
}


function addRelative_Click() {
	
	if (!CheckRelationship()) {
		alert('<?php echo __('You have not selected any relationship !')?>');
		return false;
	}
}
function CheckRelationship() {
	 var boxes = document.getElementsByTagName('select');
	 for (i = 0; i < boxes.length; i++ ) {
			box = boxes[i];
			if ( box.type == 'select-one' && box.className == 'select3 select2-offscreen') {				
				if (box.value > 0)						
		  		 return true;	
		  	}
		  }
  return false;		   
}
	
</script>




