<?php use_helper('I18N', 'Date')?>
<?php include_partial('psAttendances/assets')?>
<?php include_partial('global/include/_box_modal_messages');?>
<style>
.sunday {
	background: #999 !important;
}
.saturday {
	background: #ccc !important;
}
a.class_name {
	color: #058dc7 !important;
}
.title_symbol{font-weight: bold}
.note_synthetic{font-weight: bold}
</style>
<script type="text/javascript">
$(document).ready(function() {

	// xuat so tong hop cua thang
	$('#btn-export-synthetic').click(function() {

		if ($('#synthetic_month_filter_class_id').val() <= 0) {
			alert('<?php echo __('Select class')?>');
			return false;
		}

		$('#synthetic_export_school_year_id').val($('#synthetic_month_filter_ps_school_year_id').val());
		$('#synthetic_export_ps_customer_id').val($('#synthetic_month_filter_ps_customer_id').val());
		$('#synthetic_export_ps_workplace_id').val($('#synthetic_month_filter_ps_workplace_id').val());
		$('#synthetic_export_class_id').val($('#synthetic_month_filter_class_id').val());
		$('#synthetic_export_ps_month').val($('#synthetic_month_filter_year_month').val());
		
		$('#frm_export_02').submit();
		
		return true;
				
    });
});
</script>
<script type="text/javascript">
$(document).ready(function() {
	$('#synthetic_month_filter_ps_customer_id').change(function() {
		resetOptions('synthetic_month_filter_ps_workplace_id');
		$('#synthetic_month_filter_ps_workplace_id').select2('val','');
		$("#synthetic_month_filter_ps_workplace_id").attr('disabled', 'disabled');
		resetOptions('synthetic_month_filter_class_id');
		$('#synthetic_month_filter_class_id').select2('val','');
		$("#synthetic_month_filter_class_id").attr('disabled', 'disabled');
		
	if ($(this).val() > 0) {

		$("#synthetic_month_filter_ps_workplace_id").attr('disabled', 'disabled');
		$("#synthetic_month_filter_class_id").attr('disabled', 'disabled');
		
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

	    	$('#synthetic_month_filter_ps_workplace_id').select2('val','');

			$("#synthetic_month_filter_ps_workplace_id").html(msg);

			$("#synthetic_month_filter_ps_workplace_id").attr('disabled', null);

			$("#synthetic_month_filter_class_id").attr('disabled', 'disabled');

	    });
	}		
});
 
$('#synthetic_month_filter_ps_workplace_id').change(function() {
	
	$("#synthetic_month_filter_class_id").attr('disabled', 'disabled');
	
	$.ajax({
		url: '<?php echo url_for('@ps_class_by_params') ?>',
        type: "POST",
        data: 'c_id=' + $('#synthetic_month_filter_ps_customer_id').val() + '&w_id=' + $('#synthetic_month_filter_ps_workplace_id').val() + '&y_id=' + $('#synthetic_month_filter_ps_school_year_id').val(),
        processResults: function (data, page) {
      		return {
        		results: data.items
      		};
    	},
    }).done(function(msg) {
    	$('#synthetic_month_filter_class_id').select2('val','');
		$("#synthetic_month_filter_class_id").html(msg);
		$("#synthetic_month_filter_class_id").attr('disabled', null);
    });
});

$('#synthetic_month_filter_ps_school_year_id').change(function() {

	$("#synthetic_month_filter_class_id").attr('disabled', 'disabled');
	$("#synthetic_month_filter_year_month").attr('disabled', 'disabled');
	
	if ($(this).val() > 0) {
		
    	$("#synthetic_month_filter_year_month").attr('disabled', 'disabled');
    	$("#synthetic_month_filter_class_id").attr('disabled', 'disabled');
	
    	$.ajax({
    		url: '<?php echo url_for('@ps_class_by_params') ?>',
            type: "POST",
            data: 'c_id=' + $('#synthetic_month_filter_ps_customer_id').val() + '&w_id=' + $('#synthetic_month_filter_ps_workplace_id').val() + '&y_id=' + $('#synthetic_month_filter_ps_school_year_id').val(),
            processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
        }).done(function(msg) {
        	$('#synthetic_month_filter_class_id').select2('val','');
    		$("#synthetic_month_filter_class_id").html(msg);
    		$("#synthetic_month_filter_class_id").attr('disabled', null);
        });
        
    	$.ajax({
    		url: '<?php echo url_for('@ps_year_month?ym_id=') ?>' + $(this).val(),
            type: "POST",
            data: {'ym_id': $(this).val()},
            processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
    	    }).done(function(msg) {
    	    	$('#synthetic_month_filter_year_month').select2('val','');
    			$("#synthetic_month_filter_year_month").html(msg);
    			$("#synthetic_month_filter_year_month").attr('disabled', null);
    	    });
	}
});

});
</script>

<?php
$array_goschool = array ();
$array_outschool = array ();
foreach ( $filter_list_logtime as $list_logtime ) {
	$array_goschool [$list_logtime->getPsClassId () . date ( "Ymd", strtotime ( $list_logtime->getTrackedAt () ) )] = $list_logtime->getLoginSum ();
	$array_outschool [$list_logtime->getPsClassId () . date ( "Ymd", strtotime ( $list_logtime->getTrackedAt () ) )] = $list_logtime->getLogoutSum ();
}
$array_list = array ();
foreach ( $list_feture_branch as $list_fetures ) {
	$array_list [$list_fetures->getPsClassId () . $list_fetures->getFeatureId () . date ( "Ymd", strtotime ( $list_fetures->getTrackedAt () ) )] = $list_fetures->getFeatureSum ();
}
if ($class_id > 0) {
	$class = Doctrine::getTable ( 'MyClass' )->findOneById ( $class_id );
	$class_name = $class->getName () . ' ( ' . $class->getNumberStudentActivitie () . ' H/s ) ';
} else {
	$class_name = __ ( 'Class' );
}
$array_album_number = array();
foreach ($ps_album as $album){
	$array_album_number[$album->getId()] = date('Ymd',strtotime($album->getCreatedAt()));
}
$array_image_number = array();
foreach ($ps_album_items as $album_items){
	$array_image_number[$album_items->getId()] = date('Ymd',strtotime($album_items->getCreatedAt()));
}
$array_notication_number = array();
foreach ($ps_notication as $notication){
	$array_notication_number[$notication->getId()] = date('Ymd',strtotime($notication->getUpdatedAt()));
}
$array_article_number = array();
foreach ($ps_cms_articles as $articles){
	$array_article_number[$articles->getId()] = date('Ymd',strtotime($articles->getUpdatedAt()));
}

$array_data_album = (array_count_values($array_album_number));
$array_data_image = (array_count_values($array_image_number));
$array_data_notication = (array_count_values($array_notication_number));
$array_data_articles = (array_count_values($array_article_number));
?>
<section id="widget-grid">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<?php include_partial('psAttendances/flashes')?>
			<div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-grid="false" data-widget-collapsed="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false" data-widget-togglebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-table"></i></span>
					<h2><?php echo __('Synthetic statistic month', array(), 'messages').__('Day total', array(), 'messages').$year_month.' : '.$number_day['saturday_day'].' '.__('Day', array(), 'messages') ?></h2>

				</header>

				<div>
					<div class="widget-body no-padding">
						<div class="dt-toolbar">
							<div class="col-xs-12 col-sm-12">
            			    <?php include_partial('psAttendances/filters_synthetic_month', array('formFilter' => $formFilter, 'helper' => $helper)) ?>
            			  </div>
						</div>
						<div id="datatable_fixed_column_wrapper"
							class="dataTables_wrapper form-inline no-footer no-padding">
							<div class="custom-scroll table-responsive">
								<div class="row1">
							
									<div class="tab-content">
										<div id="home" class="tab-pane fade in active">
											<div class="table-responsive">
												<table id="dt_basic"
													class="table table-striped table-bordered table-hover no-footer no-padding"
													width="100%">
    						<?php $sunday = PsDateTime::psSundaysOfMonth($year_month);?>
    						<?php $saturday = PsDateTime::psSaturdaysOfMonth($year_month);?>
    						<thead>
								<tr>
									<th>
    									<?php echo $class_name;?>
    								</th>
    								<?php for ($k =1 ;$k <= $number_day['number_day_month']; $k++ ){ ?>
                                    	<th class="text-center <?php if(in_array($k, $sunday)){ echo 'bg-color-red';} if(in_array($k, $saturday)){ echo 'bg-color-orange'; }?>"><?php echo $k ?></th>
                                    <?php } ?>
                                </tr>
							</thead>
							<tbody>
								<tr>
									<td style="padding-left: 30px;"><?php echo __('Attendance go') ?></td>
                                	<?php for ($k =1 ;$k <= $number_day['number_day_month']; $k++ ){ ?>
                                	<?php if(date("Ymd", strtotime($k.'-'.$year_month)) <= date("Ymd")){?>
                                    <td class="text-center <?php if(in_array($k, $sunday)){ echo 'bg-color-red';} if(in_array($k, $saturday)){ echo 'bg-color-orange'; }?>">
                                    <?php 
                                    if(isset($array_goschool[$class_id . date ( "Ymd", strtotime ( $k . '-' . $year_month ) )])){
                                    	echo $goschool = $array_goschool[$class_id . date ( "Ymd", strtotime ( $k . '-' . $year_month ) )];
                                    }?>
                                    </td>
                                    <?php }else{?>
                                    	<td style='background: #eee'></td>
                                    <?php } } ?>
                                </tr>
								<tr>
									<td style="padding-left: 30px;"><?php echo __('Attendance out') ?></td>
                                	<?php for ($k =1 ;$k <= $number_day['number_day_month']; $k++ ){ ?>
                                    <?php if(date("Ymd", strtotime($k.'-'.$year_month)) <= date("Ymd")){?>
                                    <td class="text-center <?php if(in_array($k, $sunday)){ echo 'bg-color-red';} if(in_array($k, $saturday)){ echo 'bg-color-orange'; }?>">
                                    <?php
                                    if(isset($array_outschool[$class_id . date ( "Ymd", strtotime ( $k . '-' . $year_month ) )])){
										echo $outschool = $array_outschool[$class_id . date ( "Ymd", strtotime ( $k . '-' . $year_month ) )];
                                    }
									?>
                                    </td>
                                    <?php }else{?>
                                    	<td style='background: #eee'></td>
                                    <?php } } ?>
                                </tr>
								<tr>
									<td style="padding-left: 30px;"><?php echo __('Album') ?></td>
                                	<?php for ($k =1 ;$k <= $number_day['number_day_month']; $k++ ){ ?>
                                	<?php if(date("Ymd", strtotime($k.'-'.$year_month)) <= date("Ymd")){?>
                                    <td class="text-center <?php if(in_array($k, $sunday)){ echo 'bg-color-red';} if(in_array($k, $saturday)){ echo 'bg-color-orange'; }?>">
                                    <?php 
                                       echo $array_data_album[date("Ymd", strtotime($k.'-'.$year_month))];
                                    ?>
                                    </td>
                                    <?php }else{?>
                                    	<td style='background: #eee'></td>
                                    <?php } } ?>
                                </tr>
                                <tr>
									<td style="padding-left: 30px;"><?php echo __('Total image') ?></td>
                                	<?php for ($k =1 ;$k <= $number_day['number_day_month']; $k++ ){ ?>
                                	<?php if(date("Ymd", strtotime($k.'-'.$year_month)) <= date("Ymd")){?>
                                    <td class="text-center <?php if(in_array($k, $sunday)){ echo 'bg-color-red';} if(in_array($k, $saturday)){ echo 'bg-color-orange'; }?>">
                                    <?php 
                                       echo $array_data_image[date("Ymd", strtotime($k.'-'.$year_month))];
                                    ?>
                                    </td>
                                    <?php }else{?>
                                    	<td style='background: #eee'></td>
                                    <?php } } ?>
                                </tr>
                                <tr>
									<td style="padding-left: 30px;"><?php echo __('Number notication') ?></td>
                                	<?php for ($k =1 ;$k <= $number_day['number_day_month']; $k++ ){ ?>
                                	<?php if(date("Ymd", strtotime($k.'-'.$year_month)) <= date("Ymd")){?>
                                    <td class="text-center <?php if(in_array($k, $sunday)){ echo 'bg-color-red';} if(in_array($k, $saturday)){ echo 'bg-color-orange'; }?>">
                                    <?php 
                                       echo $array_data_notication[date("Ymd", strtotime($k.'-'.$year_month))];
                                    ?>
                                    </td>
                                    <?php }else{?>
                                    	<td style='background: #eee'></td>
                                    <?php } } ?>
                                </tr>
                                <tr>
									<td style="padding-left: 30px;"><?php echo __('Number articles') ?></td>
                                	<?php for ($k =1 ;$k <= $number_day['number_day_month']; $k++ ){ ?>
                                	<?php if(date("Ymd", strtotime($k.'-'.$year_month)) <= date("Ymd")){?>
                                    <td class="text-center <?php if(in_array($k, $sunday)){ echo 'bg-color-red';} if(in_array($k, $saturday)){ echo 'bg-color-orange'; }?>">
                                    <?php 
                                    	echo $array_data_articles[date("Ymd", strtotime($k.'-'.$year_month))];
                                    ?>
                                    </td>
                                    <?php }else{?>
                                    	<td style='background: #eee'></td>
                                    <?php } } ?>
                                </tr>
                                <tr>
									<th colspan="<?php echo $number_day['number_day_month']+1 ?>">
	                                	<?php echo __('Feature branch').' : '.$year_month;?>
	                                </th>
                                </tr>
                                <?php
								foreach ( $feture_branch as $branch ) {
								if ($branch->getPsMyclassId () == $class_id) {
								?>
    							<tr>
									<td style="padding-left: 30px;" class=""><?php echo $branch->getName() ?></td>
                                	<?php for ($k =1 ;$k <= $number_day['number_day_month']; $k++ ){ ?>
                                	<?php if(date("Ymd", strtotime($k.'-'.$year_month)) <= date("Ymd")){?>
                                    <td class="text-center <?php if(in_array($k, $sunday)){ echo 'bg-color-red';} if(in_array($k, $saturday)){ echo 'bg-color-orange'; }?>">
                                    
                                    <?php
									$check = $class_id . $branch->getFbId () . date ( "Ymd", strtotime ( $k . '-' . $year_month ) );
									$e = '';
									if(isset($array_list[$check])){
										$e = $array_list[$check];
									}
									if($e > 0){
										echo $e;
									}
									?>
                                    </td>
                                    <?php }else{?>
                                    	<td style='background: #eee'></td>
                                    <?php } } ?>
                                </tr>
                                <?php }}?>
                               
    						</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

		</article>
		<?php if($class_id > 0){ ?>
		<form id="frm_export_02" action="<?php echo url_for('@ps_attendances_synthetic_export') ?>">
		
		<input type="hidden" name="synthetic_export_school_year_id" id="synthetic_export_school_year_id">
		<input type="hidden" name="synthetic_export_ps_customer_id" id="synthetic_export_ps_customer_id">
		<input type="hidden" name="synthetic_export_ps_workplace_id" id="synthetic_export_ps_workplace_id">		
		<input type="hidden" name="synthetic_export_class_id" id="synthetic_export_class_id">
		<input type="hidden" name="synthetic_export_ps_month" id="synthetic_export_ps_month">
		
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right">
			<a class="btn btn-default btn-export-synthetic" href="javascript:void(0);" id="btn-export-synthetic"><i class="fa-fw fa fa-cloud-download"></i>&nbsp;<?php echo __('Export xls')?></a>
		</article>
		
		</form>
        <?php } ?>
        
	</div>
</section>
<script>

$(document).on("ready", function(){

	$('#remoteModal').on('hide.bs.modal', function(e) {
		$(this).removeData('bs.modal');
	});
	
	var msg_select_ps_customer_id	= '<?php echo __('Please select school to filter the data.')?>';
	var msg_select_ps_workplace_id 	= '<?php echo __('Please select workplace to filter the data.')?>';
	var msg_select_class_id		= '<?php echo __('Please enter class to filter the data.')?>';
	var msg_select_ps_schoolyear_id	= '<?php echo __('Please select school year to filter the data.')?>';
	var msg_select_ps_month		= '<?php echo __('Please enter month to filter the data.')?>';

	$('#ps-filter').formValidation({
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
			"synthetic_month_filter[ps_customer_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_customer_id,
                        		  en_US: msg_select_ps_customer_id
                        }
                    }
                }
            },
            
            "synthetic_month_filter[ps_school_year_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_schoolyear_id,
                        		  en_US: msg_select_ps_schoolyear_id
                        }
                    }
                }
            },
            "synthetic_month_filter[ps_workplace_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_workplace_id,
                        		  en_US: msg_select_ps_workplace_id
                        }
                    }
                }
            },
            
            "synthetic_month_filter[class_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_class_id,
                        		  en_US: msg_select_class_id
                        }
                    },
                }
            },

            "synthetic_month_filter[ps_month]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_month,
                        		  en_US: msg_select_ps_month
                        }
                    },
                }
            },

		}
    }).on('err.form.fv', function(e) {
    	$('#messageModal').modal('show');
    });
    $('#ps-filter').formValidation('setLocale', PS_CULTURE);

});
</script>