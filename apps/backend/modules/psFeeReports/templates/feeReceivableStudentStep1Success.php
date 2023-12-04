<?php use_helper('I18N', 'Date') ?>
<?php use_helper('I18N', 'Number') ?>

<?php include_partial('psFeeReports/assets', array('formFilter' => $formFilter));?>

<script type="text/javascript">
$(document).ready(function() {

	function checkStudent() {		
		var boxes = document.getElementsByTagName('input');
		for (i = 0; i < boxes.length; i++ ) {
			box = boxes[i];
			if ( box.type == 'checkbox' && box.className == 'sf_admin_batch_checkbox checkbox style-0') {						
				if (box.checked == true)
		  		 return true;	
		  	}
		}

		return false;		   
	}

	function checkReceivable() {		
		var boxes = document.getElementsByTagName('input');
		for (i = 0; i < boxes.length; i++ ) {
			box = boxes[i];
			if ( box.type == 'checkbox' && box.className == 'select checkbox chk_ids') {						
				if (box.checked == true)
		  		 return true;	
		  	}
		}
		return false;
	}

	$('.btnReceivableStudent').click(function(){

		if (!checkStudent()) {			
			$("#errors").html("<?php echo __('You do not select students to perform')?>");
		    $('#messageModal').modal({show: true,backdrop:'static'});
		    return false;
		}
		
		if (!checkReceivable()) {			
			$("#errors").html("<?php echo __('You do not select receivable to perform')?>");
		    $('#messageModal').modal({show: true,backdrop:'static'});
		    return false;
		}

		//$('#ps_fee_reports_receivable_student action').val('<?php echo url_for('@ps_fee_reports_receivable_student_save')?>');

    	$('#ps_fee_reports_receivable_student').submit();

		return true;		
	});
	
	$('#fee_receivable_student_filter_ps_customer_id').change(function() {
	
		$("#fee_receivable_student_filter_ps_workplace_id").attr('disabled', 'disabled');
	
		resetOptions('fee_receivable_student_filter_ps_class_id');
		$('#fee_receivable_student_filter_ps_class_id').select2('val','');
		
		$.ajax({
	        url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' + $(this).val(),
	        type: 'POST',
	        data: 'f=<?php echo md5(time().time().time().time())?>&psc_id=' + $(this).val(),
	        success: function(data) {
	        	$('#fee_receivable_student_filter_ps_workplace_id').select2('val','');
				$('#fee_receivable_student_filter_ps_workplace_id').html(data);
				$("#fee_receivable_student_filter_ps_workplace_id").attr('disabled', null);
				
	        }
		});
	});

	$('#fee_receivable_student_filter_ps_workplace_id').change(function() {
		resetOptions('fee_receivable_student_filter_ps_class_id');
		$('#fee_receivable_student_filter_ps_class_id').select2('val','');
		
		if ($('#fee_receivable_student_filter_ps_customer_id').val() <= 0) {
			return;
		}

		$("#fee_receivable_student_filter_ps_class_id").attr('disabled', 'disabled');

		$.ajax({
			url: '<?php echo url_for('@ps_class_by_params') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#fee_receivable_student_filter_ps_customer_id').val() + '&w_id=' + $('#fee_receivable_student_filter_ps_workplace_id').val() + '&y_id=' + <?php echo $schoolYearsDefault->getId();?>,
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#fee_receivable_student_filter_ps_class_id').select2('val','');
			$("#fee_receivable_student_filter_ps_class_id").html(msg);
			$("#fee_receivable_student_filter_ps_class_id").attr('disabled', null);
	    });
	});

});
</script>
<script type="text/javascript">
$(document).ready(function() {	

	$('#sf_admin_list_batch_checkbox').click(function() {

		var boxes = document.getElementsByTagName('input');

		for (var index = 0; index < boxes.length; index++) {
			box = boxes[index];
			if (box.type == 'checkbox' && box.name == 'ids[]')
				box.checked = $(this).is(":checked");
		}

		return true;
	});

	$( ".page" ).click(function() {

		$("#loading").show();
		$('#list_student').html('');
		
		var $this = $(this);		
		var page = $this.attr('data-page');

		$('.pagination li.active').removeClass('active');			    
	    if (!$this.parent('li').hasClass('active')) {
	    	$this.parent('li').addClass('active');
	    }

	    $.ajax({
            url: '<?php echo url_for('@ps_fee_reports_receivable_student_search') ?>',          
            type: 'POST',
            data: 'page=' + page,
            success: function(data) {
            	$('#list_student').html(data);
            	$("#loading").hide();   			
            }
    	});	
	    	
	});	
});	
</script>
<?php include_partial('global/include/_box_modal');?>
<?php include_partial('global/include/_box_modal_dynamic_content');?>
<?php include_partial('global/include/_box_modal_messages');?>

<section id="widget-grid">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<?php include_partial('psFeeReports/flashes') ?>
			<div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-grid="false" data-widget-collapsed="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false" data-widget-togglebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-table"></i></span>
					<h2><?php echo __('Receivables of the month', array(), 'messages') ?></h2>
					<div class="jarviswidget-ctrls" role="menu">
						<a data-backdrop="static" data-toggle="modal"
							data-target="#remoteModalContent"
							class="button-icon jarviswidget-question-btn"
							data-placement="auto" rel="tooltip"
							title="<?php echo __('Help')?>"><i class="fa fa-question-circle"></i></a>
					</div>
				</header>
				<div class="widget-body" style="overflow: hidden;">
					<div class="widget-body-toolbar">
						<?php include_partial('psFeeReports/box/_feeReceivableStudentFilters', array('formFilter' => $formFilter, 'helper' => $helper)) ?>
					</div>
					<div class="widget-body-toolbar text-right">
						<div class="row">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<?php include_partial('psFeeReports/box_button/_list_action_receivable');?>
							</div>
						</div>
					</div>
					<form id="ps_fee_reports_receivable_student" method="post"
						action="<?php echo url_for('@ps_fee_reports_receivable_student_save')?>">
						<div class="widget-body">
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
									<?php include_partial('psFeeReports/table_list_student', array('list_student' => $list_student, 'formFilter' => $formFilter)) ?>
									<?php if ($pager->haveToPaginate()): ?>
							        <?php include_partial('global/include/_pagination', array('pager' => $pager)) ?>
							        <?php endif; ?>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
								<?php include_partial('psFeeReports/box/_table_receivable_student_month', array('receivables' => $receivables,'date_at' => $date_at)) ?>
								<?php //include_partial('psFeeReports/box/_table_receivable_student_month', array('receivables' => $receivables, 'list_service' => $list_service)) ?>
								</div>
							</div>
						</div>
						<div class="widget-body-toolbar">
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right">
								<?php include_partial('psFeeReports/box_button/_list_action_receivable');?>
							</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</article>
	</div>
</section>