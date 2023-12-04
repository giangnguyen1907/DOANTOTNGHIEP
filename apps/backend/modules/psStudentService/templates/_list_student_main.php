<?php if ($pager->getNbResults()) : ?>
<div class="custom-scroll table-responsive"
	style="height: 400px; overflow-y: scroll;">
	<div id="list_student">

	     <?php include_partial('psStudentService/table_student', array('list_student' => $list_student, 'form' => $form, 'ps_service_courses' => $ps_service_courses)) ?>
	</div>
</div>
<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-left">
        <?php if ($pager->haveToPaginate()): ?>
        <?php include_partial('psStudentService/pagination', array('pager' => $pager)) ?>
        <?php endif; ?>
</div>
<?php else:?>
<div class="alert alert-warning fade in">
		<?php echo __('No result', array(), 'sf_admin') ?>
</div>
<?php endif;?>

<script type="text/javascript">
$(document).ready(function() {	
	$( ".page" ).click(function() {

		$("#loading").show();
		$('#list_student').html('');
		
		var $this = $(this);		
		var page = $this.attr('data-page');

		$('#page-student li.active').removeClass('active');			    
	    if (!$this.parent('li').hasClass('active')) {
	    	$this.parent('li').addClass('active');
	    }
	    	    		
		$.ajax({
            url: '<?php echo url_for('@ps_student_by_keywords?keywords=') ?>' + $('#student_filter_keywords').val() + '&ps_service_course_id=<?php echo $ps_service_courses->getId()?>',          
            type: 'POST',
            data: 'page=' + page + '&html=table&keywords=' + $('#student_filter_keywords').val()+ '&ps_service_course_id=<?php echo $ps_service_courses->getId()?>',
            success: function(data) {
            	$('#list_student').html(data);
            	$("#loading").hide();    			
            }
    	});
		
	});
	
});
	
</script>