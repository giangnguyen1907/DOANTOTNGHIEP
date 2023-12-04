<?php use_helper('I18N', 'Date')?>
<?php include_partial('global/include/_box_modal');?>
<?php include_partial('global/include/_box_modal_messages');?>
<?php 
$mumber_malnutrition = count ($list_student_malnutrition);
$dakham = $caodat = $nangdat = $nang1 = $thap1 = $thap2 = $caohon = $nanghon = $nang2 = 0;
?>

<section id="widget-grid">
	<!--  sf_admin_container -->
		
		<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<?php include_partial('psStudentGrowths/flashes')?>
		<!-- sf_admin_container -->
			<div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-grid="false" data-widget-collapsed="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false" data-widget-togglebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-table"></i></span>
					<h2><?php echo __('Classroom performance assessment') ?></h2>
				</header>
				<div>
					<div class="widget-body no-padding">
						<div id="datatable_fixed_column_wrapper"
							class="dataTables_wrapper form-inline no-footer no-padding">
							<div class="dt-toolbar no-margin no-padding no-border">
								<div class="col-xs-12 col-sm-12">
									<div id="sf_admin_header"></div>
								</div>
							</div>
							<div class="dt-toolbar">
								<div class="col-xs-12 col-sm-12">
            			    <?php include_partial('psStudentGrowths/filters_statistic', array('formFilter' => $formFilter, 'helper' => $helper)) ?>
            			  </div>
							</div>
							<div class="sf_admin_actions dt-toolbar-footer no-border-transparent">
								<div class="col-xs-4 col-sm-4 col-md-6 col-lg-6 text-left">
								<a class="btn btn-psadmin pull-left" style="-webkit-box-shadow: none;">
								<?php if($mumber_malnutrition > 0){
									echo $pager->getNbResults().' '.__('Student');
								}?>
								</a>
								</div>
					
								<div class="col-xs-8 col-sm-8 col-md-6 col-lg-6 text-right">					      
								<?php if (count ( $all_students ) > 0) { ?>
								<a class="btn btn-default btn-export-growths" href="javascript:void(0);" id="btn-export-growths"><i class="fa-fw fa fa-cloud-download"></i>&nbsp;<?php echo __('Export xls')?></a>
								<?php }?>
								<?php if($mumber_malnutrition > 0){?>
								<a class="btn btn-default btn-export-growths2" href="javascript:void(0);" id="btn-export-growths2"><i class="fa-fw fa fa-cloud-download"></i>&nbsp;<?php echo __('Export2 xls')?></a>
								<?php }?>
								</div>
							</div>
							<?php if($malnutrition == 1){
								echo get_partial('psStudentGrowths/table_malnutrition', array('list_student_malnutrition' => $list_student_malnutrition,'pager'=>$pager));
							}else{?>
							
							<div class="clear" style="clear: both;"></div>
							<section class="table_scroll">
							  <div class="container_table custom-scroll table-responsive">
							    <table id="dt_basic" width="100%">
							      <thead>
										<tr class="header hidden-sm hidden-xs">
											<th class="text-center"><?php echo __('Class name') ?>
											<div><?php echo __('Class name') ?></div></th>
											<th class="text-center"><?php echo __('Total student') ?>
											<div><?php echo __('Total student') ?></div></th>
											<th class="text-center"><?php echo __('Not student') ?>
											<div><?php echo __('Not student') ?></div></th>
											<th class="text-center"><?php echo __('Height nonal') ?>
											<div><?php echo __('Height nonal') ?></div></th>
											<th class="text-center"><?php echo __('Weight nonal') ?>
											<div><?php echo __('Weight nonal') ?></div></th>
											<th class="text-center"><?php echo __('Tall') ?>
											<div><?php echo __('Tall') ?></div></th>
											<th class="text-center"><?php echo __('Low level 2') ?>
											<div><?php echo __('Low level 2') ?></div></th>
											<th class="text-center"><?php echo __('Low level 1') ?>
											<div><?php echo __('Low level 1') ?></div></th>
											<th class="text-center"><?php echo __('Fat') ?>
											<div><?php echo __('Fat') ?></div></th>
											<th class="text-center"><?php echo __('Thin level 2') ?>
											<div><?php echo __('Thin level 2') ?></div></th>
											<th class="text-center"><?php echo __('Thin level 1') ?>
											<div><?php echo __('Thin level 1') ?></div></th>
										</tr>
										<tr class="hidden-md hidden-lg">
											<th class="text-center"><?php echo __('Class name')?></th>
											<th class="text-center"><?php echo __('Total student')?></th>
											<th class="text-center"><?php echo __('Not student')?></th>
											<th class="text-center"><?php echo __('Height nonal')?></th>
											<th class="text-center"><?php echo __('Weight nonal')?></th>
											<th class="text-center"><?php echo __('Tall')?></th>
											<th class="text-center"><?php echo __('Low level 2') ?></th>
											<th class="text-center"><?php echo __('Low level 1') ?></th>
											<th class="text-center"><?php echo __('Fat')?></th>
											<th class="text-center"><?php echo __('Thin level 2') ?></th>
											<th class="text-center"><?php echo __('Thin level 1') ?></th>
										</tr>
									</thead>
							      	<tbody>

										<tr>
											<td colspan="2"><b><?php echo $workplace->getTitle() ?></b></td>
											<td colspan="9">
			<?php echo __('Kid groups');?>
			<b><?php
			if (isset ( $object_groups ))
				echo $object_groups->getTitle ();
			?></b>
											</td>

										</tr>
		<?php foreach ($psexamination as $examination): ?>
		<?php if($workplace->getId() == $examination->getPsWorkplaceId()){ ?>
		<tr>
											<td colspan="11">
			<?php echo __('Examination :'); ?>
			<b><?php
				echo $examination->getExaminationName () . ', ' . __ ( 'Date examination' ) . ' ' . format_date ( $examination->getInputDateAt (), "dd-MM-yyyy" );
				?></b>
											</td>
										</tr>					
		<?php foreach($list_my_class as $my_class):  ?>
		<?php

					$invalue = $my_class->getId () . $examination->getExid ();
					$invalue1 = $my_class->getId () . $examination->getExid () . '-2';
					$invalue2 = $my_class->getId () . $examination->getExid () . '-1';
					$invalue3 = $my_class->getId () . $examination->getExid () . '0';
					$invalue4 = $my_class->getId () . $examination->getExid () . '1';

					foreach ( $all_students as $all_student ) {

						// echo $all_student->getMcid().'_'.$all_student->getExaminationId().'<br/>';

						if ($invalue == $all_student->getMcid () . $all_student->getExaminationId ()) {
							$dakham ++;
						}
						if ($invalue4 == $all_student->getMcid () . $all_student->getExaminationId () . $all_student->getIndexHeight ()) {
							$caohon ++;
						}
						if ($invalue4 == $all_student->getMcid () . $all_student->getExaminationId () . $all_student->getIndexWeight ()) {
							$nanghon ++;
						}
						if ($invalue3 == $all_student->getMcid () . $all_student->getExaminationId () . $all_student->getIndexHeight ()) {
							$caodat ++;
						}
						if ($invalue3 == $all_student->getMcid () . $all_student->getExaminationId () . $all_student->getIndexWeight ()) {
							$nangdat ++;
						}
						if ($invalue2 == $all_student->getMcid () . $all_student->getExaminationId () . $all_student->getIndexHeight ()) {
							$thap1 ++;
						}
						if ($invalue1 == $all_student->getMcid () . $all_student->getExaminationId () . $all_student->getIndexHeight ()) {
							$thap2 ++;
						}
						if ($invalue2 == $all_student->getMcid () . $all_student->getExaminationId () . $all_student->getIndexWeight ()) {
							$nang1 ++;
						}
						if ($invalue1 == $all_student->getMcid () . $all_student->getExaminationId () . $all_student->getIndexWeight ()) {
							$nang2 ++;
						}
					}
					
					?>
		<tr>
				<td><?php echo $my_class->getName() ?></td>
				<td class='text-center'>
				<?php $total_active_student = Doctrine::getTable('StudentClass')->getNumberStudentActivitieDateAt($my_class->getId (),$examination->getInputDateAt ()); ?>
				<a class="btn btn-labeled btn-primary"> <span class="btn-label">
						<?php echo $total_active_student; ?>
				</span> <span class="btn-control">
					<?php echo round(($total_active_student/$total_active_student*100),2).'%' ?>
				</span>
											</a>
											</td>
											<td class='text-center'>
				<?php if( $dakham > 0){ ?>
					<a class="btn btn-labeled btn-primary"
												href="<?php echo url_for(@ps_student_growths).'/'.$my_class->getId().'/'.$examination->getExid().'/'; ?>viewch"
												data-backdrop="static" data-toggle="modal"
												data-target="#remoteModal"> <span class="btn-label">
							<?php echo $dakham;  ?>
						</span> <span class="btn-control">
    						<?php echo round(($dakham/$total_active_student*100),2).'%' ?>
    					</span>
											</a>
				<?php }else{ ?>
					<a class="btn btn-labeled btn-primary"> <span class="btn-label">
							<?php echo 0; ?>
						</span> <span class="btn-control">
    						<?php echo '0%' ?>
    					</span>
											</a>
					
				<?php }  $dakham = 0; ?>
				
			</td>
											<td class='text-center'>
				
				<?php if( $caodat > 0){ ?>
				<a class="btn btn-labeled btn-success"
												href="<?php echo url_for(@ps_student_growths).'/'.$examination->getExid().'/'.$my_class->getId().'/0/'; ?>viewheight"
												data-backdrop="static" data-toggle="modal"
												data-target="#remoteModal"> <span class="btn-label">
						<?php echo $caodat; ?>
					</span> <span class="btn-control">
    					<?php echo round(($caodat/$total_active_student*100),2).'%' ?>
    				</span>

											</a>
				<?php }else{ ?>
				<a class="btn btn-labeled btn-success"> <span class="btn-label">
						<?php echo 0; ?>
					</span> <span class="btn-control">
    					<?php echo '0%' ?>
    				</span>
											</a>
				<?php } $caodat = 0;  ?>
			</td>
											<td class='text-center'>
				
				<?php if( $nangdat > 0){ ?>
				<a class="btn btn-labeled btn-success"
												href="<?php echo url_for(@ps_student_growths).'/'.$examination->getExid().'/'.$my_class->getId().'/0/'; ?>viewweight"
												data-backdrop="static" data-toggle="modal"
												data-target="#remoteModal"> <span class="btn-label">
						<?php echo $nangdat; ?>
					</span> <span class="btn-control">
    					<?php echo round(($nangdat/$total_active_student*100),2).'%' ?>
    				</span>

											</a>
				<?php }else{ ?>	
				<a class="btn btn-labeled btn-success"> <span class="btn-label">
						<?php echo 0; ?>
					</span> <span class="btn-control">
    					<?php echo '0%' ?>
    				</span>
											</a>
				<?php } $nangdat = 0; ?>
			</td>

											<td class='text-center'>
				
				<?php if( $caohon > 0){ ?>
				<a class="btn btn-labeled btn-info"
												href="<?php echo url_for(@ps_student_growths).'/'.$examination->getExid().'/'.$my_class->getId().'/1/'; ?>viewheight"
												data-backdrop="static" data-toggle="modal"
												data-target="#remoteModal"> <span class="btn-label">
						<?php echo $caohon; ?>
					</span> <span class="btn-control">
    					<?php echo round(($caohon/$total_active_student*100),2).'%' ?>
    				</span>

											</a>
				<?php }else{ ?>
				<a class="btn btn-labeled btn-info"> <span class="btn-label">
						<?php echo 0; ?>
					</span> <span class="btn-control">
    					<?php echo '0%' ?>
    				</span>
											</a>
				<?php } $caohon = 0; ?>
			</td>

											<td class='text-center'>
				
				<?php if( $thap2 > 0){ ?>
				<a class="btn btn-labeled btn-danger"
												href="<?php echo url_for(@ps_student_growths).'/'.$examination->getExid().'/'.$my_class->getId().'/-2/'; ?>viewheight"
												data-backdrop="static" data-toggle="modal"
												data-target="#remoteModal"> <span class="btn-label">
						<?php echo $thap2; ?>
					</span> <span class="btn-control">
    					<?php echo  round(($thap2/$total_active_student*100),2).'%' ?>
    				</span>

											</a>
				<?php }else{ ?>
				<a class="btn btn-labeled btn-danger"> <span class="btn-label">
						<?php echo 0; ?>
					</span> <span class="btn-control">
    					<?php echo  '0%' ?>
    				</span>
											</a>
				<?php } $thap2 = 0; ?>
			</td>
											<td class='text-center'>
				
				<?php if( $thap1 > 0){ ?>
				<a class="btn btn-labeled btn-warning"
												href="<?php echo url_for(@ps_student_growths).'/'.$examination->getExid().'/'.$my_class->getId().'/-1/'; ?>viewheight"
												data-backdrop="static" data-toggle="modal"
												data-target="#remoteModal"> <span class="btn-label">
						<?php echo $thap1; ?>
					</span> <span class="btn-control">
    					<?php echo  round(($thap1/$total_active_student*100),2).'%' ?>
    				</span>

											</a>
				<?php }else{ ?>
				<a class="btn btn-labeled btn-warning"> <span class="btn-label">
						<?php echo 0; ?>
					</span> <span class="btn-control">
    					<?php echo  '0%' ?>
    				</span>
											</a>
				<?php }  $thap1 = 0; ?>
			</td>

											<td class='text-center'>
				<?php if( $nanghon > 0){ ?>
				<a class="btn btn-labeled btn-info"
												href="<?php echo url_for(@ps_student_growths).'/'.$examination->getExid().'/'.$my_class->getId().'/1/'; ?>viewweight"
												data-backdrop="static" data-toggle="modal"
												data-target="#remoteModal"> <span class="btn-label">
						<?php echo $nanghon; ?>
					</span> <span class="btn-control">
    					<?php echo round(($nanghon/$total_active_student*100),2).'%' ?>
    				</span>

											</a>
				<?php }else{ ?>	
				<a class="btn btn-labeled btn-info"> <span class="btn-label">
						<?php echo 0; ?>
					</span> <span class="btn-control">
    					<?php echo '0%' ?>
    				</span>
											</a>
				<?php }  $nanghon = 0; ?>
				
			</td>

											<td class='text-center'>
				
				<?php if( $nang2 > 0){ ?>
				<a class="btn btn-labeled btn-danger"
												href="<?php echo url_for(@ps_student_growths).'/'.$examination->getExid().'/'.$my_class->getId().'/-2/'; ?>viewweight"
												data-backdrop="static" data-toggle="modal"
												data-target="#remoteModal"> <span class="btn-label">
						<?php echo $nang2; ?>
					</span> <span class="btn-control">
    					<?php echo round(($nang2/$total_active_student*100),2).'%' ?>
    				</span>

											</a>
				<?php }else{ ?>	
				<a class="btn btn-labeled btn-danger"> <span class="btn-label">
						<?php echo 0; ?>
					</span> <span class="btn-control">
    					<?php echo '0%' ?>
    				</span>
											</a>
				<?php } $nang2 = 0; ?>
				
			</td>
											<td class='text-center'>
				
				<?php if( $nang1 > 0){ ?>
				<a class="btn btn-labeled btn-warning"
												href="<?php echo url_for(@ps_student_growths).'/'.$examination->getExid().'/'.$my_class->getId().'/-1/'; ?>viewweight"
												data-backdrop="static" data-toggle="modal"
												data-target="#remoteModal"> <span class="btn-label">
						<?php echo $nang1; ?>
					</span> <span class="btn-control">
    					<?php echo round(($nang1/$total_active_student*100),2).'%' ?>
    				</span>

											</a>
				<?php }else{ ?>	
				<a class="btn btn-labeled btn-warning"> <span class="btn-label">
						<?php echo 0; ?>
					</span> <span class="btn-control">
    					<?php echo '0%' ?>
    				</span>
											</a>
				<?php } $nang1 = 0;  ?>
				
			</td>

										</tr>
		
		<?php endforeach ?>
		<?php } ?>
		<?php endforeach ?>
        </tbody>
							    </table>
							  </div>
							</section>
							
							
							
							<?php }?>
							
							<form id="frm_export_04" action="<?php echo url_for('@ps_student_growths_statistic_export') ?>">
		<input type="hidden" name="growths_school_year_id" id="growths_school_year_id">
		<input type="hidden" name="growths_ps_customer_id" id="growths_ps_customer_id">
		<input type="hidden" name="growths_ps_workplace_id" id="growths_ps_workplace_id">
		<input type="hidden" name="growths_ps_group_id" id="growths_ps_group_id">
		<input type="hidden" name="growths_ps_class_id" id="growths_ps_class_id">
		<input type="hidden" name="growths_examination" id="growths_examination">
		<input type="hidden" name="growths_index" id="growths_index">
		
		<div class="sf_admin_actions dt-toolbar-footer no-border-transparent">
			<div class="col-xs-4 col-sm-4 col-md-6 col-lg-6 text-left">
			<?php  
			if($malnutrition == 1){
				if ($pager->haveToPaginate()){
					include_partial('global/include/_pagination', array('pager' => $pager));
				}else{?>
				<a class="btn btn-psadmin pull-left" style="-webkit-box-shadow: none;">
				<?php echo $mumber_malnutrition.' '.__('Student'); ?>
				</a>
			<?php }}?>
			</div>

			<div class="col-xs-8 col-sm-8 col-md-6 col-lg-6 text-right">					      
			<?php if (count ( $all_students ) > 0) { ?>
			<a class="btn btn-default btn-export-growths" href="javascript:void(0);" id="btn-export-growths"><i class="fa-fw fa fa-cloud-download"></i>&nbsp;<?php echo __('Export xls')?></a>
			<?php }?>
			<?php if($mumber_malnutrition > 0){?>
			<a class="btn btn-default btn-export-growths2" href="javascript:void(0);" id="btn-export-growths2"><i class="fa-fw fa fa-cloud-download"></i>&nbsp;<?php echo __('Export2 xls')?></a>
			<?php }?>
			</div>
		</div>
		</form>
						</div>
					</div>
				</div>
			</div>

		</article>
		
		
			
	</div>
</section>

<script>

$(document).on("ready", function(){

	$('#remoteModal').on('hide.bs.modal', function(e) {
		$(this).removeData('bs.modal');
	});
	
	var msg_select_ps_customer_id	= '<?php echo __('Please select school to filter the data.')?>';
	var msg_select_ps_workplace_id 	= '<?php echo __('Please select workplace to filter the data.')?>';
	var msg_select_examination		= '<?php echo __('Please enter examination filter the data.')?>';
	var msg_select_ps_schoolyear_id	= '<?php echo __('Please select school year to filter the data.')?>';

	$('#growths_filter_ps_customer_id').change(function() {

		resetOptions('growths_filter_ps_workplace_id');
		$('#growths_filter_ps_workplace_id').select2('val','');
		$("#growths_filter_ps_workplace_id").attr('disabled', 'disabled');

		resetOptions('growths_filter_class_id');
		$('#growths_filter_class_id').select2('val','');
		
		resetOptions('growths_filter_examination_id');
		$('#growths_filter_examination_id').select2('val','');
		
		
		if ($(this).val() > 0) {
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

		    	$('#growths_filter_ps_workplace_id').select2('val','');

				$("#growths_filter_ps_workplace_id").html(msg);

				$("#growths_filter_ps_workplace_id").attr('disabled', null);

		    });
		}		
		});
	 
	    $('#growths_filter_ps_workplace_id').change(function() {
	    	
	    	resetOptions('growths_filter_examination_id');
	    	$('#growths_filter_examination_id').select2('val','');
	    	if ($('#growths_filter_ps_customer_id').val() <= 0) {
	    		return;
	    	}
	    	
	    	$("#growths_filter_class_id").attr('disabled', 'disabled');
	    	
	    	$.ajax({
	    		url: '<?php echo url_for('@ps_class_object_by_params') ?>',
	            type: "POST",
	            data: 'c_id=' + $('#growths_filter_ps_customer_id').val() + '&w_id=' + $('#growths_filter_ps_workplace_id').val() + '&y_id=' + $('#growths_filter_ps_school_year_id').val() + '&o_id=' + $('#growths_filter_ps_obj_group_id').val(),
	            processResults: function (data, page) {
	          		return {
	            		results: data.items
	          		};
	        	},
	        }).done(function(msg) {
	        	$('#growths_filter_class_id').select2('val','');
	    		$("#growths_filter_class_id").html(msg);
	    		$("#growths_filter_class_id").attr('disabled', null);
	        });
	    	
	    	$("#growths_filter_examination_id").attr('disabled', 'disabled');
	    	
	    	$.ajax({
	    		url: '<?php echo url_for('@ps_student_growths_examination') ?>',
	            type: "POST",
	            data: 'c_id=' + $('#growths_filter_ps_customer_id').val() + '&w_id=' + $('#growths_filter_ps_workplace_id').val() + '&y_id=' + $('#growths_filter_ps_school_year_id').val(),
	            processResults: function (data, page) {
	          		return {
	            		results: data.items
	          		};
	        	},
	        }).done(function(msg) {
	        	$('#growths_filter_examination_id').select2('val','');
	    		$("#growths_filter_examination_id").html(msg);
	    		$("#growths_filter_examination_id").attr('disabled', null);
	        });
	        
	    });
	    
	    $('#growths_filter_ps_school_year_id').change(function() {
	    	
	    	if ($('#growths_filter_ps_school_year_id').val() <= 0) {
	    		return;
	    	}
	    	
	    	$("#growths_filter_class_id").attr('disabled', 'disabled');
    		
    		$.ajax({
    			url: '<?php echo url_for('@ps_class_object_by_params') ?>',
    	        type: "POST",
    	        data: 'c_id=' + $('#growths_filter_ps_customer_id').val() + '&w_id=' + $('#growths_filter_ps_workplace_id').val() + '&y_id=' + $('#growths_filter_ps_school_year_id').val() + '&o_id=' + $('#growths_filter_ps_obj_group_id').val(),
    	        processResults: function (data, page) {
              		return {
                		results: data.items
              		};
            	},
    	    }).done(function(msg) {
    	    	$('#growths_filter_class_id').select2('val','');
    			$("#growths_filter_class_id").html(msg);
    			$("#growths_filter_class_id").attr('disabled', null);
    	    });

	    	$('#growths_filter_ps_obj_group_id').change(function() {
	    		
	    		$("#growths_filter_class_id").attr('disabled', 'disabled');
	    		
	    		$.ajax({
	    			url: '<?php echo url_for('@ps_class_object_by_params') ?>',
	    	        type: "POST",
	    	        data: 'c_id=' + $('#growths_filter_ps_customer_id').val() + '&w_id=' + $('#growths_filter_ps_workplace_id').val() + '&y_id=' + $('#growths_filter_ps_school_year_id').val() + '&o_id=' + $('#growths_filter_ps_obj_group_id').val(),
	    	        processResults: function (data, page) {
	              		return {
	                		results: data.items
	              		};
	            	},
	    	    }).done(function(msg) {
	    	    	$('#growths_filter_class_id').select2('val','');
	    			$("#growths_filter_class_id").html(msg);
	    			$("#growths_filter_class_id").attr('disabled', null);
	    	    });

	    		$.ajax({
	        		url: '<?php echo url_for('@ps_student_growths_examination') ?>',
	                type: "POST",
	                data: 'c_id=' + $('#growths_filter_ps_customer_id').val() + '&w_id=' + $('#growths_filter_ps_workplace_id').val() + '&y_id=' + $('#growths_filter_ps_school_year_id').val(),
	                processResults: function (data, page) {
	              		return {
	                		results: data.items
	              		};
	            	},
	            }).done(function(msg) {
	            	$('#growths_filter_examination_id').select2('val','');
	        		$("#growths_filter_examination_id").html(msg);
	        		$("#growths_filter_examination_id").attr('disabled', null);
	            });
	    
	    	});
	    
	    	
	    	$("#growths_filter_examination_id").attr('disabled', 'disabled');
	    	
	    	$.ajax({
	    		url: '<?php echo url_for('@ps_student_growths_examination') ?>',
	            type: "POST",
	            data: 'c_id=' + $('#growths_filter_ps_customer_id').val() + '&w_id=' + $('#growths_filter_ps_workplace_id').val() + '&y_id=' + $('#growths_filter_ps_school_year_id').val(),
	            processResults: function (data, page) {
	          		return {
	            		results: data.items
	          		};
	        	},
	        }).done(function(msg) {
	        	$('#growths_filter_examination_id').select2('val','');
	    		$("#growths_filter_examination_id").html(msg);
	    		$("#growths_filter_examination_id").attr('disabled', null);
	        });
	    
	    });
	    
	    $('#growths_filter_ps_obj_group_id').change(function() {
	    
	    	$("#growths_filter_class_id").attr('disabled', 'disabled');
	    	
	    	$.ajax({
	    		url: '<?php echo url_for('@ps_class_object_by_params') ?>',
	            type: "POST",
	            data: 'c_id=' + $('#growths_filter_ps_customer_id').val() + '&w_id=' + $('#growths_filter_ps_workplace_id').val() + '&y_id=' + $('#growths_filter_ps_school_year_id').val() + '&o_id=' + $('#growths_filter_ps_obj_group_id').val(),
	            processResults: function (data, page) {
	          		return {
	            		results: data.items
	          		};
	        	},
	        }).done(function(msg) {
	        	$('#growths_filter_class_id').select2('val','');
	    		$("#growths_filter_class_id").html(msg);
	    		$("#growths_filter_class_id").attr('disabled', null);
	        });
	    });


	$('#psnew-filter').formValidation({
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
			"growths_filter[ps_customer_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_customer_id,
                        		  en_US: msg_select_ps_customer_id
                        }
                    }
                }
            },
            
            "growths_filter[ps_school_year_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_schoolyear_id,
                        		  en_US: msg_select_ps_schoolyear_id
                        }
                    }
                }
            },
            
            "growths_filter[ps_workplace_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_workplace_id,
                        		  en_US: msg_select_ps_workplace_id
                        }
                    }
                }
            },
            
            "growths_filter[examination_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_examination,
                        		  en_US: msg_select_examination
                        }
                    },
                }
            },

		}
    }).on('err.form.fv', function(e) {
    	$('#messageModal').modal('show');
    });
    $('#psnew-filter').formValidation('setLocale', PS_CULTURE);

});
</script>
<script type="text/javascript">
$(document).ready(function() {

	// xuat so tong hop cua thang
	$('.btn-export-growths').click(function() {

		if ($('#growths_filter_examination_id').val() <= 0) {
			alert('<?php echo __('Select examination')?>');
			return false;
		}

		$('#growths_school_year_id').val($('#growths_filter_ps_school_year_id').val());
		$('#growths_ps_customer_id').val($('#growths_filter_ps_customer_id').val());
		$('#growths_ps_workplace_id').val($('#growths_filter_ps_workplace_id').val());
		$('#growths_ps_group_id').val($('#growths_filter_ps_obj_group_id').val());
		$('#growths_ps_class_id').val($('#growths_filter_class_id').val());
		$('#growths_examination').val($('#growths_filter_examination_id').val());
		$('#growths_index').val(0);
		
		$('#frm_export_04').submit();
		
		return true;
				
    });

	$('.btn-export-growths2').click(function() {

		$('#growths_school_year_id').val($('#growths_filter_ps_school_year_id').val());
		$('#growths_ps_customer_id').val($('#growths_filter_ps_customer_id').val());
		$('#growths_ps_workplace_id').val($('#growths_filter_ps_workplace_id').val());
		$('#growths_ps_group_id').val($('#growths_filter_ps_obj_group_id').val());
		$('#growths_ps_class_id').val($('#growths_filter_class_id').val());
		$('#growths_examination').val($('#growths_filter_examination_id').val());
		$('#growths_index').val(1);
		
		$('#frm_export_04').submit();
		
		return true;
				
	});
	
	$( ".page" ).click(function() {

		$("#loading").show();
		$('#list_student').html('');
		
		var $this = $(this);		
		var page = $this.attr('data-page');

		var y_id = $('#growths_filter_ps_school_year_id').val();

		var c_id = $('#growths_filter_ps_customer_id').val();

		var w_id = $('#growths_filter_ps_workplace_id').val();

		var o_id = $('#growths_filter_ps_obj_group_id').val();

		var cl_id = $('#growths_filter_class_id').val();

		var e_id = $('#growths_filter_examination_id').val();

		$('.pagination li.active').removeClass('active');			    
	    if (!$this.parent('li').hasClass('active')) {
	    	$this.parent('li').addClass('active');
	    }

	    $.ajax({
            url: '<?php echo url_for('@ps_student_growths_search') ?>',          
            type: 'POST',
            data: 'page=' + page + '&y_id=' + y_id + '&c_id=' + c_id + '&w_id=' + w_id + '&o_id=' + o_id + '&cl_id=' + cl_id + '&e_id=' + e_id,
            success: function(data) {
            	$('#list_student').html(data);
            	$("#loading").hide();   			
            }
    	});	
	    	
	});	
});
</script>
