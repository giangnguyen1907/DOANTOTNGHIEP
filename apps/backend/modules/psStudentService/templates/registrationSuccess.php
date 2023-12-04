<?php use_helper('I18N', 'Date') ?>
<?php use_helper('I18N', 'Number') ?>

<?php include_partial('psStudentService/assets');?>
<input type="text" id="filter-form" class="hidden"
	data-schoolyear="<?php echo $ps_school_year_id ?>"
	data-customer="<?php echo $ps_customer_id ?>"
	data-workplace="<?php echo $ps_workplace_id ?>"
	data-class="<?php echo $class_id ?>" />
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
		
		$('#ps_service_registration_student').submit();
		return true;		
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
		var year_id = $('#filter-form').attr('data-schoolyear');
		var cus_id = $('#filter-form').attr('data-customer');
		var wp_id = $('#filter-form').attr('data-workplace');
		var c_id = $('#filter-form').attr('data-class');

		$("#loading").show();
		$('#list_student').html('');
		
		var $this = $(this);		
		var page = $this.attr('data-page');

		//alert(year_id);

		$('.pagination li.active').removeClass('active');			    
	    if (!$this.parent('li').hasClass('active')) {
	    	$this.parent('li').addClass('active');
	    }

	    $.ajax({
            url: '<?php echo url_for('@ps_service_registration_student_search') ?>',          
            type: 'POST',
            data: 'page=' + page + '&year_id=' + year_id + '&cus_id=' + cus_id + '&wp_id=' + wp_id + '&c_id=' + c_id,
            success: function(data) {
            	$('#list_student').html(data);
            	$("#loading").hide();   			
            }
    	});	
	    	
	});	


	$('#remoteModal').on('hide.bs.modal', function(e) {
		$(this).removeData('bs.modal');
	});
	
	var msg_select_ps_customer_id	= '<?php echo __('Please select school to filter the data.')?>';
	var msg_select_ps_workplace_id 	= '<?php echo __('Please select workplace to filter the data.')?>';
	var msg_select_ps_schoolyear_id	= '<?php echo __('Please select school year to filter the data.')?>';
	
	$('#ps-filter-form').formValidation({
    	framework : 'bootstrap',
    	addOns : {
			i18n : {}
		},
		err : {
			container: '#errors'
		},
		message : {
			vi_VN : 'This value is not valid'
		},
		icon : {},
    	fields : {
			"student_filter[ps_customer_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_customer_id,
                        		  en_US: msg_select_ps_customer_id
                        }
                    }
                }
            },
            
            "student_filter[ps_school_year_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_schoolyear_id,
                        		  en_US: msg_select_ps_schoolyear_id
                        }
                    }
                }
            },
            
            "student_filter[ps_workplace_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_workplace_id,
                        		  en_US: msg_select_ps_workplace_id
                        }
                    }
                }
            },
            
		}
    }).on('err.form.fv', function(e) {
    	$('#messageModal').modal('show');
    });
    $('#ps-filter-form').formValidation('setLocale', PS_CULTURE);
	
});	
</script>
<?php include_partial('global/include/_box_modal');?>
<?php include_partial('global/include/_box_modal_dynamic_content');?>
<?php include_partial('global/include/_box_modal_messages');?>

<section id="widget-grid">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<?php include_partial('psStudentService/flashes') ?>
			<div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-grid="false" data-widget-collapsed="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false" data-widget-togglebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-table"></i></span>
					<h2><?php echo __('Student service', array(), 'messages') ?></h2>
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
						<?php include_partial('psStudentService/registration_filters', array('formFilter' => $formFilter, 'helper' => $helper)) ?>
					</div>
					<div class="widget-body-toolbar text-right">
						<div class="row">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<button type="button"
									class="btn btn-default btn-success btn-sm btnReceivableStudent">
									<span class="fa-fw fa fa-floppy-o"></span> <?php echo __('Save service') ?></button>
							</div>
						</div>
					</div>
					<form id="ps_service_registration_student" method="post"
						action="<?php echo url_for('@ps_service_registration_student_save')?>">
						<input type="hidden" name="ps_customer_id" value="<?=$ps_customer_id?>">
						<div class="widget-body">
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
									<?php include_partial('psStudentService/table_list_student', array('list_student' => $list_student,'formFilter' => $formFilter,'ps_school_year_id' =>$ps_school_year_id)) ?>
									<?php if ($pager->haveToPaginate()): ?>
							        <?php include_partial('global/include/_pagination', array('pager' => $pager)) ?>
							        <?php endif; ?>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-8">
									<?php include_partial('psStudentService/table_list_service', array('list_service' => $list_service,'ps_workplace_id' => $ps_workplace_id,'ps_customer_id' => $ps_customer_id,'psRegularity'=>$psRegularity)) ?>
								</div>
							</div>
						</div>
						<div class="widget-body-toolbar">
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right">
									<button type="button"
										class="btn btn-default btn-success btn-sm btnReceivableStudent">
										<span class="fa-fw fa fa-floppy-o"></span> <?php echo __('Save service') ?></button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</article>
	</div>
</section>