<?php if ($pager->getNbResults()) : ?>
<div class="custom-scroll table-responsive"
	style="height: 400px; overflow-y: scroll;">
	<div id="list_relative">
	     <?php include_partial('psRelativeStudent/table_relative', array('list_relative' => $list_relative, 'form' => $form, 'ps_student' => $ps_student)) ?>
	</div>
</div>
<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-left">
        <?php if ($pager->haveToPaginate()): ?>
        <?php include_partial('psRelativeStudent/pagination', array('pager' => $pager)) ?>
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
		$('#list_relative').html('');
		
		var $this = $(this);		
		var page = $this.attr('data-page');

		$('#page-relative li.active').removeClass('active');			    
	    if (!$this.parent('li').hasClass('active')) {
	    	$this.parent('li').addClass('active');
	    }
	    	    		
		$.ajax({
            url: '<?php echo url_for('@ps_relative_by_keywords?keywords=') ?>' + $('#relative_filter_keywords').val() + '&student_id=<?php echo $ps_student->getId()?>',          
            type: 'POST',
            data: 'page=' + page + '&html=table&keywords=' + $('#relative_filter_keywords').val()+ '&student_id=<?php echo $ps_student->getId()?>',
            success: function(data) {
            	$('#list_relative').html(data);
            	$("#loading").hide();   			
            }
    	});		
	});	
});	
</script>