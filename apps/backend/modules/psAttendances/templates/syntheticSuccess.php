<?php use_helper('I18N', 'Date')?>
<?php include_partial('psAttendances/assets')?>
<?php include_partial('global/include/_box_modal_messages');?>
<style>
td, th {border: 1px solid #ccc;padding: 5px;}
.title_symbol{font-weight: bold}
.note_synthetic{font-weight: bold}
@media (min-width: 768px){
table {table-layout: fixed;max-width: 100%;}
td, th {border: 1px solid #ccc;padding: 5px;}
.sunday {background: #999 !important;}
.saturday {background: #ccc !important;}
.hard_left {position: absolute;left: -1px;width: 46px;border-bottom: none;}
.next_left {position: absolute;left: 44px;width: 120px;border-bottom: none;}
.end_left {width: 170px;}
.border_solid { padding: 5px 0px;}
.last-child {border-bottom: 1px solid #ccc !important;height: 59px;padding: 20px 0px;}
.inner {overflow-x: scroll;overflow-y: visible;margin-left: 150px;margin-right: 0px;margin-top: 10px;}
}
</style>
<script type="text/javascript">
$(document).ready(function() {
	$('.time_picker').timepicker({
		timeFormat : 'HH:mm',
		showMeridian : false,
		defaultTime : null
	});
});
</script>
<script type="text/javascript">
$(document).ready(function() {

	// xuat so tong hop cua thang
	$('#btn-export-synthetic-date').click(function() {

		if ($('#synthetic_month_filter_class_id').val() <= 0) {
			alert('<?php echo __('Select class')?>');
			return false;
		}

		$('#synthetic_export_date_school_year_id').val($('#delay_filter_ps_school_year_id').val());
		$('#synthetic_export_date_ps_customer_id').val($('#delay_filter_ps_customer_id').val());
		$('#synthetic_export_date_ps_workplace_id').val($('#delay_filter_ps_workplace_id').val());
		$('#synthetic_export_date_at').val($('#delay_filter_date_at').val());
		
		$('#frm_export_03').submit();
		
		return true;
				
    });
});
</script>
<?php
$array_list = array ();
foreach ( $list_feture_branch as $list_feture ) {
	$array_list [$list_feture->getPsClassId () . $list_feture->getFeatureId ()] = $list_feture->getFeatureSum ().'_'.$list_feture->getNoteSum ();
}
$array_album_number = array();
foreach ($ps_album as $album){
	$array_album_number[$album->getId()] = $album->getPsClassId();
}
$array_image_number = array();
foreach ($ps_album_items as $album_items){
	$array_image_number[$album_items->getId()] = $album_items->getPsClassId();
}

$array_data_album = (array_count_values($array_album_number));
$array_data_image = (array_count_values($array_image_number));

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
					<h2><?php echo __('Synthetic statitic by day', array(), 'messages') ?></h2>
				</header>

				<div>
					<div class="widget-body no-padding">
						<div class="dt-toolbar">
							<div class="col-xs-12 col-sm-12">
            			    <?php include_partial('psAttendances/filters_synthetic', array('formFilter' => $formFilter, 'helper' => $helper)) ?>
            			  </div>
						</div>

						<form class="form-horizontal">
							<input type="hidden" name="ps_logtimes_delay[date_time]"
								value="<?php echo $date_at; ?>" id="ps_logtimes_delay_date_time">
							
							<div class="outer col-md-12">
							<div class="inner custom-scroll table-responsive">
									<table>

										<thead>
											<tr>
												<th class="hard_left text-center"> <?php echo __('STT', array(), 'messages') ?> </th>
												<th class="next_left text-center"> <?php echo __('Class name', array(), 'messages') ?> </th>
												<th class="end_left text-center"> <?php echo __('Teacher', array(), 'messages') ?> </th>
												  
												<th class="text-center"> <?php echo __('Album', array(), 'messages') ?> </th>
												<th class="text-center"> <?php echo __('Total image', array(), 'messages') ?> </th>
												<th class="text-center"> <?php echo __('Notification', array(), 'messages') ?> </th>
												<th class="text-center"> <?php echo __('News', array(), 'messages') ?> </th>
												
												
												<th class="text-center"> <?php echo __('Attendance go', array(), 'messages') ?> </th>
												<th class="text-center"> <?php echo __('Attendance out', array(), 'messages') ?> </th>
	                                           
	                                            <?php foreach ($feture_branch as $branchs){?>
	                                            <th class="text-center"> <?php echo $branchs->getTitle() ?> </th>
	                                        	<?php }?>
	                                        </tr>
										</thead>

										<tbody>
            							<?php foreach ($my_class as $ky=> $class):?>
            							<?php
            							    $class_id = $class->getMcId ();
            							    $number_notication = $number_articles = $a = $b = 0;
											foreach ( $filter_list_logtime as $list_class ) {
												if ($list_class->getPsClassId () == $class->getMcId ()) {
													$a = $list_class->getLoginSum ();
													$b = $list_class->getLogoutSum ();
												}
											}
										?>
            							<tr>
											<td class="hard_left text-center"><?php echo $ky+1?></td>
											<td class="next_left"><?php echo $class->getTitle().' ( '.$total_active_student = Doctrine::getTable('StudentClass')->getNumberStudentActivitie($class->getMcId ()).' H/s ) '; ?></td>
											<td class="end_left">
                							<?php
												foreach ( $list_member as $member ) {
													if($member->getMyclassId() == $class->getMcId ()){
														echo $member->getTitle () . '<br/>';
													}
												}
											?>
                							</td>
                							
                							<td class="text-center"><?php echo $array_data_album[$class_id];?></td>
                							<td class="text-center"><?php echo $array_data_image[$class_id];?></td>
                							<?php 
                							foreach ($ps_notications as $notications){
                								if($notications->getPsClassId() == '' || $notications->getPsClassId() == $class_id){
                									$number_notication ++;
                								}
                							}
                							?>
                							<td class="text-center"><?php echo $number_notication;?></td>
                							
                							<?php 
                							foreach ($ps_cms_articles as $articles){
                								if($articles->getPsClassId() == '' || $articles->getPsClassId() == $class_id){
                									$number_articles ++;
                								}
                							}
                							?>
                							<td class="text-center"><?php echo $number_articles;?></td>
                							
											<td class="text-center"><?php echo $a;?></td>
											<td class="text-center"><?php echo $b;?></td>
											
            								<?php
												foreach ( $feture_branch as $branch ) {
													$c = $d = 0;
													$check_data = $class->getMcId () . $branch->getId ();
													if(isset($array_list[$check_data])){
														$array_chuoi = explode('_', $array_list[$check_data]);
														
														$c = $array_chuoi['0'];
														$d = $array_chuoi['1'];
														unset($array_list[$check_data]);
														
													}
													
												?>
        								    <td class="text-center">
        								    <?php echo $c;
        								    if($d > 0){
        								    	echo ' | '.$d.__('Write comment');
        								    }
        								    ?>
        								    </td>
            								<?php }?>
            							</tr>
            							<?php endforeach;?>
            							</tbody>
									</table>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</article>
		<?php if(count($filter_list_logtime) > 0){ ?>
		
		<form id="frm_export_03" action="<?php echo url_for('@ps_attendances_synthetic_by_date_export') ?>">
		
		<input type="hidden" name="synthetic_export_date_school_year_id" id="synthetic_export_date_school_year_id">
		<input type="hidden" name="synthetic_export_date_ps_customer_id" id="synthetic_export_date_ps_customer_id">
		<input type="hidden" name="synthetic_export_date_ps_workplace_id" id="synthetic_export_date_ps_workplace_id">		
		<input type="hidden" name="synthetic_export_date_at" id="synthetic_export_date_at">
		
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right">
			<a class="btn btn-default btn-export-synthetic-date" href="javascript:void(0);" id="btn-export-synthetic-date"><i class="fa-fw fa fa-cloud-download"></i>&nbsp;<?php echo __('Export xls')?></a>
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
	var msg_select_ps_schoolyear_id	= '<?php echo __('Please select school year to filter the data.')?>';

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
			"delay_filter[ps_customer_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_customer_id,
                        		  en_US: msg_select_ps_customer_id
                        }
                    }
                }
            },
            
            "delay_filter[ps_school_year_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_schoolyear_id,
                        		  en_US: msg_select_ps_schoolyear_id
                        }
                    }
                }
            },
            
            "delay_filter[ps_workplace_id]": {
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
    $('#ps-filter').formValidation('setLocale', PS_CULTURE);

});
</script>