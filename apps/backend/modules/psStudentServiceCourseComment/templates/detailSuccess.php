<?php use_helper('I18N', 'Date') ?>
<?php
// Su dung bien global
sfConfig::set ( 'enableRollText', PreSchool::loadPsRoll () );
// echo $ps_customer_id;
?>
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

<script type="text/javascript">
$(document).ready(function() {
	$('#course_schedules_filter_ps_year').change(function() {

    	$("#course_schedules_filter_ps_week").attr('disabled', 'disabled');
    	resetOptions('course_schedules_filter_ps_week');
    	
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
  			 $('#course_schedules_filter_ps_week').select2('val','');
 			 $("#course_schedules_filter_ps_week").html(msg);
  			 $("#course_schedules_filter_ps_week").attr('disabled', null);

  			$('#course_schedules_filter_ps_week').val(1);
			$('#course_schedules_filter_ps_week').change();
  		});
    	
    });
	$('#course_schedules_filter_ps_customer_id, #course_schedules_filter_ps_workplace_id, #course_schedules_filter_ps_week, #course_schedules_filter_ps_service_id, #course_schedules_filter_ps_service_course_id').change(function() {

    	$("#ic-loading").show();
    	$("#tbl-menu").html('');
    	
    	$.ajax({
	          url: '<?php echo url_for('@ps_student_service_course_comment_week');?>',
	          type: "POST",
	          data: $("#ps-filter").serialize(),
	          processResults: function (data, page) {
	              return {
	                results: data.items  
	              };
	          },
			}).done(function(msg) {
				 $("#ic-loading").hide();
				 $("#tbl-menu").html(msg);				 	
			});
    	
    });

	$('#course_schedules_filter_ps_customer_id').change(function() {

    	resetOptions('course_schedules_filter_ps_workplace_id');
		$('#course_schedules_filter_ps_workplace_id').select2('val','');
		$('#course_schedules_filter_ps_workplace_id').change();
	
    	resetOptions('course_schedules_filter_ps_service_id');
		$('#course_schedules_filter_ps_service_id').select2('val','');

		if ($('#course_schedules_filter_ps_customer_id').val() <= 0) {
			return;
		}
		
		$("#course_schedules_filter_ps_service_id").attr('disabled', 'disabled');
		
		$.ajax({
	        url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' + $(this).val(),
	        type: 'POST',
	        async: false,
	        data: 'f=<?php echo md5(time().time().time().time())?>&psc_id=' + $(this).val(),
	        success: function(data) {
	        	$('#course_schedules_filter_ps_workplace_id').select2('val','');
				$('#course_schedules_filter_ps_workplace_id').html(data);
				$("#course_schedules_filter_ps_workplace_id").attr('disabled', null);
			}
		});
		
		$.ajax({
	        url: '<?php echo url_for('@ps_service_courses_by_ps_customer') ?>',
	        type: "POST",
	        async: false,
	        data: {'psc_id': $(this).val()},
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#course_schedules_filter_ps_service_id').select2('val','');
			$("#course_schedules_filter_ps_service_id").html(msg);
			$("#course_schedules_filter_ps_service_id").attr('disabled', null);
	    });
	    
	});

    $('#course_schedules_filter_ps_service_id').change(function() {
    	resetOptions('course_schedules_filter_ps_service_course_id');
		$('#course_schedules_filter_ps_service_course_id').select2('val','');
		$("#course_schedules_filter_ps_service_course_id").attr('disabled','disabled');
	
		if ($('#course_schedules_filter_ps_service_id').val() <= 0) {        			
			return;
		}
		$.ajax({
	        url: '<?php echo url_for('@ps_service_course_by_ps_service?sid=') ?>' + $(this).val(),
	        type: 'POST',
	        data: 'f=<?php echo md5(time().time().time().time())?>sid=' + $(this).val(),
	        success: function(data) {
	        	$('#course_schedules_filter_ps_service_course_id').select2('val','');
				$('#course_schedules_filter_ps_service_course_id').html(data);
				$("#course_schedules_filter_ps_service_course_id").attr('disabled',null);
	        }
		});
    });

})
</script>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">×</button>
	<h4><?php echo __('Course schedules', array(), 'messages') ?></h4>
</div>
<div class="modal-body">
	<div class="box-body">
		<form id="ps-filter" class="form-inline pull-left dataTables_filter"
			action="<?php //echo url_for('ps_service_course_schedules_collection', array('action' => 'new')) ?>"
			method="post">
			<div class="dt-toolbar" style="padding-bottom: 10px;">
				<div class="form-group ">
					<label>
			 <?php echo $formFilter['ps_customer_id']->render()?>
			 <?php echo $formFilter['ps_customer_id']->renderError()?>
			  </label>
				</div>

				<div class="form-group ">
					<label> 
			 <?php echo $formFilter['ps_workplace_id']->render()?>
			 <?php echo $formFilter['ps_workplace_id']->renderError()?>
			  </label>
				</div>

				<div class="form-group ">
					<label>	 
			 <?php echo $formFilter['ps_service_id']->render()?>
			 <?php echo $formFilter['ps_service_id']->renderError()?>
			  </label>
				</div>

				<div class="form-group">
					<label>
			 	<?php echo $formFilter['ps_service_course_id']->render()?>
			 	<?php echo $formFilter['ps_service_course_id']->renderError()?>
			 	 </label>
				</div>

				<div class="form-group">
					<label>
			 	<?php echo $formFilter['ps_year']->render()?>
			 	<?php echo $formFilter['ps_year']->renderError()?>
			 	 </label>
				</div>
				<div class="form-group ">
					<label>
			 	<?php echo $formFilter['ps_week']->render()?>
			 	<?php echo $formFilter['ps_week']->renderError()?>
			 	 </label>
				</div>

			</div>
		
		<?php include_partial('global/include/_ic_loading');?>
		
		<div id="tbl-menu">	
		<?php include_partial('psStudentServiceCourseComment/table_schedules', array('list_course_schedules' => $list_course_schedules, 'week_list' => $week_list, 'width_th' => (100 / (count($week_list) + 1))));?>
		</div>
		</form>
	</div>
</div>

<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">
		<i class="fa-fw fa fa-ban"></i>&nbsp;<?php echo __('Close')?></button>
</div>
