<?php use_helper('I18N', 'Date') ?>
<?php include_partial('psClass/assets') ?>
<script type="text/javascript">
$(document).ready(function() {

	$('#student_filter_ps_customer_id').change(function() {
		resetOptions('student_filter_ps_workplace_id');
		$('#student_filter_ps_workplace_id').select2('val','');

		resetOptions('student_filter2_ps_workplace_id');
		$('#student_filter2_ps_workplace_id').select2('val','');

		$('student_filter_ps_workplace_id, #student_filter2_ps_workplace_id').change();

		resetOptions('student_filter_class_id');
		$('#student_filter_class_id').select2('val','');
		
		if ($(this).val() > 0 ) {

			$("#student_filter_ps_workplace_id, #student_filter2_ps_workplace_id").attr('disabled', 'disabled');
			$("#student_filter_class_id").attr('disabled', 'disabled');
			
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
		    	$('#student_filter_ps_workplace_id, #student_filter2_ps_workplace_id').select2('val','');

				$("#student_filter_ps_workplace_id, #student_filter2_ps_workplace_id").html(msg);

				$("#student_filter_ps_workplace_id, #student_filter2_ps_workplace_id").attr('disabled', null);

				$.ajax({
					url: '<?php echo url_for('@ps_class_by_params') ?>',
			        type: "POST",
			        data: 'c_id=' + $('#student_filter_ps_customer_id').val() + '&w_id=' + $('#student_filter_ps_workplace_id').val() + '&y_id=' + $('#student_filter_school_year_id').val(),
			        processResults: function (data, page) {
		          		return {
		            		results: data.items
		          		};
		        	},
			    }).done(function(msg) {
			    	$('#student_filter_class_id').select2('val','');
					$("#student_filter_class_id").html(msg);
					$("#student_filter_class_id").attr('disabled', null);
			    });

				$("#ic-loading-01").show();
				$('#list_for_assign_students').html('');
				$.ajax({
			        url: '<?php echo url_for('@ps_class_student_not_exits?cus_id=') ?>' + $('#student_filter_ps_customer_id').val(),          
			        type: 'POST',
			        data: 'cus_id=' + $('#student_filter_ps_customer_id').val() + '&w =' + $('#student_filter_ps_workplace_id').val(),
			        success: function(data) {
			        	$("#ic-loading-01").hide();
			        	$('#list_for_assign_students').html(data);
			        }
				});
			    
		    });
		}		
	});

	$('#student_filter_ps_workplace_id').change(function() {
		$("#ic-loading-01").show();
		$('#list_for_assign_students').html('');
		$.ajax({
	        url: '<?php echo url_for('@ps_class_student_not_exits?cus_id=') ?>' + $('#student_filter_ps_customer_id').val(),          
	        type: 'POST',
	        data: 'cus_id=' + $('#student_filter_ps_customer_id').val() + '&w_id=' + $('#student_filter_ps_workplace_id').val(),
	        success: function(data) {
	        	$("#ic-loading-01").hide();
	        	$('#list_for_assign_students').html(data);
	        }
		});		
	});

	$('#student_filter_school_year_id').change(function() {
		
		resetOptions('student_filter_class_id');
		$('#student_filter_class_id').select2('val','');
		
		if ($('#student_filter_ps_customer_id').val() <= 0) {
			return;
		}

		$("#student_filter_class_id").attr('disabled', 'disabled');
		$.ajax({
			url: '<?php echo url_for('@ps_class_by_params') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#student_filter_ps_customer_id').val() + '&w_id=' + $('#student_filter2_ps_workplace_id').val() + '&y_id=' + $('#student_filter_school_year_id').val(),
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#student_filter_class_id').select2('val','');
			$("#student_filter_class_id").html(msg);
			$("#student_filter_class_id").attr('disabled', null);
	    });
	});

	// Get list student of class
	$('#student_filter_class_id').change(function()  {
		
		var class_id = parseInt($('#student_filter_class_id').val());
				
		$("#ic-loading").show();
		$('#list_student').html('');
		if (class_id > 0) {
		
			$.ajax({
		        url: '<?php echo url_for('@ps_student_by_class?cid=') ?>' + class_id,          
		        type: 'POST',
		        data: 'f=<?php echo md5(time().time().time().time())?>cid=' + class_id,
		        success: function(data) {
		        	$('#list_student').html(data);
		        	$("#ic-loading").hide();    			
		        }
			});
		}
		
	});

	$('.btn-subclass').click(function()  {
		
		var class_id = parseInt($('#student_filter_class_id').val());
				
		if (!class_id) {

			$("#errors").html("<?php echo __('You have not selected class!')?>");

	   		$('#warningModal').modal({show: true,backdrop:'static'});

			return false;
		}		
	});
	
});
</script>
<?php echo form_tag_for($formAssignStudents, '@ps_student_class', array('class' => 'form-horizontal', 'id' => 'ps-form-student-class', 'data-fv-addons' => 'i18n')) ?>
<?php echo $formFilter->renderHiddenFields(true)?>
<section id="widget-grid">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<?php include_partial('psClass/flashes') ?>
		<div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false"
				data-widget-colorbutton="false" data-widget-grid="false"
				data-widget-collapsed="false" data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false" data-widget-togglebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-table"></i></span>
					<h2><?php echo __('Subclass for students', array(), 'messages') ?></h2>
				</header>
				<div class="widget-body">
					<div class="row">
						<article class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
							<div class="jarviswidget" id="wid-id-1"
								data-widget-editbutton="false" data-widget-colorbutton="false"
								data-widget-editbutton="false" data-widget-togglebutton="false"
								data-widget-deletebutton="false"
								data-widget-fullscreenbutton="false"
								data-widget-custombutton="false" data-widget-collapsed="false"
								data-widget-sortable="false">
								<header>
									<h2><?php echo __('List of students enrolled', array(), 'messages') ?></h2>
								</header>
								<div class="well well-sm">
									<div class="form-group">
										<label>
								 	<?php echo $formFilter['ps_customer_id']->render()?>
								 	<?php echo $formFilter['ps_customer_id']->renderError()?>
									</label>
									</div>
									<div class="form-group">
										<label>
								 	<?php echo $formFilter['ps_workplace_id']->render()?>
								 	<?php echo $formFilter['ps_workplace_id']->renderError()?>
								  	</label>
									</div>
								</div>
								<div id="ic-loading-01" style="display: none;">
									<i class="fa fa-spinner fa-2x fa-spin text-success"
										style="padding: 3px;"></i><?php echo __('Loading...');?>
							</div>
								<div id="list_for_assign_students">
								<?php include_partial('psClass/list_for_assign_students', array('students_not_exits_class' => $students_not_exits_class))?>
							</div>
							</div>
						</article>
						<article class="col-xs-12 col-sm-12 col-md-1 col-lg-1">
							<div>
								<button type="button"
									class="btn btn-default btn-success btn-sm btn-psadmin btn-subclass">
									<i class="fa-fw fa fa-save"></i>&nbsp;<?php echo __('Subclass') ?></button>
								<a data-backdrop="static" data-toggle="modal"
									data-target="#assignStudentsModal" href="#" id="detail_name"
									class="btn btn-default btn-success btn-sm btn-psadmin btn-subclass"><i
									class="fa-fw fa fa-save"></i>&nbsp;<?php echo __('Subclass') ?></a>
							</div>
						</article>
						<article class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
							<div class="jarviswidget" id="wid-id-2"
								data-widget-editbutton="false" data-widget-colorbutton="false"
								data-widget-editbutton="false" data-widget-togglebutton="false"
								data-widget-deletebutton="false"
								data-widget-fullscreenbutton="false"
								data-widget-custombutton="false" data-widget-collapsed="false"
								data-widget-sortable="false">
								<header>
									<h2><?php echo __('List student', array(), 'messages') ?></h2>
								</header>
								<div class="well well-sm">
									<div class="form-group">
										<label>
									 <?php echo $formFilter['school_year_id']->render()?>
									 <?php echo $formFilter['school_year_id']->renderError()?>
									 </label>
									</div>
									<div class="form-group">
										<label>
								 	<?php echo $formFilter['ps_workplace_id']->render(array('name' => 'student_filter2[ps_workplace_id]','id' => 'student_filter2_ps_workplace_id')) ?>
								 	<?php echo $formFilter['ps_workplace_id']->renderError()?>
								  	</label>
									</div>
									<div class="form-group">
										<label>
								 	<?php echo $formFilter['class_id']->render()?>
								 	<?php echo $formFilter['class_id']->renderError()?>
									</label>
									</div>
								</div>							
							<?php include_partial('global/include/_ic_loading');?>
							<div id="list_student">
							<?php include_partial('psClass/table_student', array('list_student_class_to' => $list_student_class_to));?>
							</div>
							</div>
						</article>
					</div>
				</div>
			</div>
		</article>
	</div>
</section>
<?php include_partial('psClass/box_modal_assign_students_to_class');?>
</form>
<?php include_partial('global/include/_box_modal_warning');?>
