<?php use_helper('I18N', 'Date')?>
<form id="frm-batch" action="" method="post">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal"
			aria-hidden="true">×</button>
		<h4 class="modal-title" id="myModalLabel">
			<strong><?php echo __('Service courses') ?></strong>
		</h4>
	</div>

	<div class="modal-body">
		<input class="student_id hidden" value=<?php echo $student_id?>> <input
			class="service_id hidden" value=<?php echo $service_id?>>
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div id="service_courses_student">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<h4><?php //echo __('Service cources class') ?></h4>
					</div>
					<table id="dt_basic"
						class="table table-striped table-bordered table-hover"
						width="100%">
						<tr class="info">
							<th></th>
							<th><?php echo __('Course title') ?></th>
							<th><?php echo __('Subjects title') ?></th>
							<th><?php echo __('Teacher') ?></th>
							<th><?php echo __('Start at') ?></th>
							<th><?php echo __('End at') ?></th>
							<th><?php echo __('Note') ?></th>
						</tr>
					<?php foreach($service_couse as $couse): ?>
					<tr>
							<td><input type="radio" name="service_cource_id"
								value="<?php echo $couse->getId(); ?>"></td>
							<td><?php echo $couse->getTitle(); ?></td>
							<td><?php echo $couse->getSubjectsTitle (); ?></td>
							<td><?php echo $couse->getTeacher (); ?></td>
							<td><?php echo $couse->getStartAt (); ?></td>
							<td><?php echo $couse->getEndAt (); ?></td>
							<td><?php echo $couse->getNote (); ?></td>
						</tr>
					<?php endforeach;?>
						</table>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<a href="javascript:;"
			class="btn btn-default btn-success btn-sm btn-psadmin btn-save-course"><i
			class="fa-fw fa fa-floppy-o" aria-hidden="true"
			title="<?php echo __('Save')?>"></i> <?php echo __('Save')?></a>
		<button type="button" class="btn btn-default btn-close"
			data-dismiss="modal">
			<i class="fa-fw fa fa-ban"></i>&nbsp;<?php echo __('Close') ?></button>
		<div>

</form>
<script>
$(document).ready(function() {
	$('.btn-save-course').click(function(){
		var student_id = $('.student_id').val();
		var service_id = $('.service_id').val();
		var course_id = $('input[name="service_cource_id"]:checked').val();
		
		if(course_id > 0) {
			$.ajax({
		        url: '<?php echo url_for('@ps_service_courses_save') ?>',
		        type: "POST",
		        data: 'student_id=' + student_id + '&service_id=' + service_id + '&course_id=' +course_id,
		        processResults: function (data, page) {
	          		return {
	            		results: data.items
	          		};
	        	},
		    }).done(function(msg) {
			    
			    //alert(msg);
				$('.btn-close').trigger('click');
				location.reload();
		    });
		}else{
			alert('"<?php echo __('Please choose any one course')?>"');
			return;
		}

		
		
	});
});
</script>