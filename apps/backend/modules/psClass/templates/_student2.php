<div class="widget-body">
	<div class="dt-toolbar">
	<?php if ($formFilter->hasGlobalErrors()): ?>
      <?php echo $formFilter->renderGlobalErrors() ?>
    <?php endif; ?>
	<form id="ps-filter-2" class="form-inline pull-left"
			action="<?php echo url_for('ps_class_collection', array('action' => 'move')) ?>"
			method="post">
			<div class="pull-left">		
		 	 <?php echo $formFilter->renderHiddenFields(true) ?>
			 	 <div class="form-group">
				 <?php echo $formFilter['school_year_id']->render(array('name' => 'student_filter2[school_year_id]','id' => 'student_filter2_school_year_id')) ?>
				 <?php echo $formFilter['school_year_id']->renderError() ?>
				 </div>
				<div class="form-group">
				 <?php echo $formFilter['ps_workplace_id']->render(array('name' => 'student_filter2[ps_workplace_id]','id' => 'student_filter2_ps_workplace_id')) ?>
				 <?php echo $formFilter['ps_workplace_id']->renderError() ?>
				 </div>
				<div class="form-group">
				 <?php echo $formFilter['class_to_id']->render(array('name' => 'student_filter2[class_id]','id' => 'student_filter2_class_id')) ?>
				 <?php echo $formFilter['class_to_id']->renderError() ?>
				 </div>
				 <!-- <div class="form-group">
				 <?php echo $formFilter['statistic_class_id']->render(array('name' => 'student_filter2[statistic_class_id]','id' => 'student_filter2_statistic_class_id')) ?>
				 <?php echo $formFilter['statistic_class_id']->renderError() ?>
				 </div> -->
			</div>
		</form>
	</div>
	<?php include_partial('global/include/_ic_loading');?>
	<div id="list_student">
	<?php include_partial('psClass/table_student', array('list_student_class_to' => $list_student_class_to));?>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() {

	$('#student_filter2_ps_workplace_id').change(function() {
		resetOptions('student_filter2_class_id');
		$('#student_filter2_class_id').select2('val','');
		$('#student_filter2_class_id').change();
		if ($('#student_filter_ps_customer_id').val() <= 0) {
			return;
		}

		if ($('#student_filter_class_id').val() > 0){
		$.ajax({
			url: '<?php echo url_for('@ps_class_by_params') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#student_filter_ps_customer_id').val() + '&w_id=' + $('#student_filter2_ps_workplace_id').val() + '&y_id=' + $('#student_filter2_school_year_id').val() + '&class_from_id=' + $('#student_filter_class_id').val(),
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#student_filter2_class_id').select2('val','');
			$("#student_filter2_class_id").html(msg);
			$("#student_filter2_class_id").attr('disabled', null);
	    });
		}
	});

	$('#student_filter2_school_year_id').change(function() {
		
		resetOptions('student_filter2_class_id');
		$('#student_filter2_class_id').select2('val','');
		
		if ($('#student_filter2_ps_customer_id').val() <= 0) {
			return;
		}

		if ($('#student_filter_class_id').val() > 0){
		$.ajax({
			url: '<?php echo url_for('@ps_class_by_params') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#student_filter2_ps_customer_id').val() + '&w_id=' + $('#student_filter2_ps_workplace_id').val() + '&y_id=' + $('#student_filter2_school_year_id').val()+'&class_from_id='+$('#student_filter_class_id').val(),
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#student_filter2_class_id').select2('val','');
			$("#student_filter2_class_id").html(msg);
			$("#student_filter2_class_id").attr('disabled', null);
	    });
		}
	});

	$('#student_filter2_class_id').change(function()  {
		
		var class_to = parseInt($('#student_filter2_class_id').val());
		$('#form_student_class_to_id').val(class_to);
		$('#form_student_class_to_id').change();
		
		$("#loading").show();
		$('#list_student').html('');
		if (class_to > 0) {
		
			
		$.ajax({
	        url: '<?php echo url_for('@ps_student_by_class?cid=') ?>' + class_to,          
	        type: 'POST',
	        data: 'f=<?php echo md5(time().time().time().time())?>cid=' + class_to,
	        success: function(data) {
	        	$('#list_student').html(data);
	        	$("#loading").hide();    			
	        }
		});
		}
		
	});

	$('#student_filter2_statistic_class_id').change(function()  {
		
		var class_to = parseInt($('#student_filter2_statistic_class_id').val());
		$('#form_student_class_to_id').val(class_to);
		$('#form_student_class_to_id').change();
		
		$("#loading").show();
		$('#list_student').html('');
		if (class_to > 0) {
		
			
		$.ajax({
	        url: '<?php echo url_for('@ps_student_by_class?cid=') ?>' + class_to,          
	        type: 'POST',
	        data: 'f=<?php echo md5(time().time().time().time())?>cid=' + class_to,
	        success: function(data) {
	        	$('#list_student').html(data);
	        	$("#loading").hide();    			
	        }
		});
		}
		
	});
	
	// END: filters
});

</script>

