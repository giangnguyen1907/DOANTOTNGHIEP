<?php use_helper('I18N', 'Date') ?>
<?php //include_partial('psFeatureBranchTimes/assets') ?>
<?php include_partial('global/include/_box_modal_warning');?>
<?php include_partial('global/include/_box_modal')?>
<script type="text/javascript">
$(document).on("ready", function(){

	$('#remoteModal').on('hide.bs.modal', function(e) {
		$(this).removeData('bs.modal');
	});
	
});
</script>
<script type="text/javascript">

$(document).ready(function() {
	
	$(".widget-body-toolbar a, .btn-group a, .btn-filter-reset").on("contextmenu",function(){
	    return false;
	});

	$('#feature_branch_times_filters_ps_customer_id').change(function() {

		resetOptions('feature_branch_times_filters_ps_workplace_id');
		$('#feature_branch_times_filters_ps_workplace_id').select2('val','');
		resetOptions('feature_branch_times_filters_ps_class_id');
		$('#feature_branch_times_filters_ps_class_id').select2('val','');
		resetOptions('feature_branch_times_filters_ps_feature_branch_id');
		$('#feature_branch_times_filters_ps_feature_branch_id').select2('val','');
		
		if ($(this).val() > 0) {

			$("#feature_branch_times_filters_ps_workplace_id").attr('disabled', 'disabled');
			$("#feature_branch_times_filters_ps_class_id").attr('disabled', 'disabled');
			
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

		    	$('#feature_branch_times_filters_ps_workplace_id').select2('val','');

				$("#feature_branch_times_filters_ps_workplace_id").html(msg);

				$("#feature_branch_times_filters_ps_workplace_id").attr('disabled', null);

				$("#feature_branch_times_filters_ps_class_id").attr('disabled', 'disabled');

				$.ajax({
					url: '<?php echo url_for('@ps_class_by_params') ?>',
			        type: "POST",
			        data: 'c_id=' + $('#feature_branch_times_filters_ps_customer_id').val() + '&w_id=' + $('#feature_branch_times_filters_ps_workplace_id').val() + '&y_id=' + $('#feature_branch_times_filters_school_year_id').val(),
			        processResults: function (data, page) {
		          		return {
		            		results: data.items
		          		};
		        	},
			    }).done(function(msg) {
			    	$('#feature_branch_times_filters_ps_class_id').select2('val','');
					$("#feature_branch_times_filters_ps_class_id").html(msg);
					$("#feature_branch_times_filters_ps_class_id").attr('disabled', null);
			    });
		    });
		}		
	});

	$('#feature_branch_times_filters_ps_workplace_id').change(function() {

		resetOptions('feature_branch_times_filters_ps_class_id');

		resetOptions('feature_branch_times_filters_ps_feature_branch_id');
		
		$('#feature_branch_times_filters_ps_class_id').select2('val','');
		
		if ($('#feature_branch_times_filters_ps_customer_id').val() <= 0) {
			return;
		}

		$("#feature_branch_times_filters_ps_class_id").attr('disabled', 'disabled');
		
		$.ajax({
			url: '<?php echo url_for('@ps_class_by_params') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#feature_branch_times_filters_ps_customer_id').val() + '&w_id=' + $('#feature_branch_times_filters_ps_workplace_id').val() + '&y_id=' + $('#feature_branch_times_filters_school_year_id').val(),
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#feature_branch_times_filters_ps_class_id').select2('val','');
			$("#feature_branch_times_filters_ps_class_id").html(msg);
			$("#feature_branch_times_filters_ps_class_id").attr('disabled', null);
	    });
	    
		$('#feature_branch_times_filters_ps_feature_branch_id').select2('val','');
		
		$("#feature_branch_times_filters_ps_feature_branch_id").attr('disabled', 'disabled');
		
		if ($('#feature_branch_times_filters_ps_customer_id').val() <= 0 || $(this).val() <= 0) {
			return;
		}
				
		$.ajax({
			url: '<?php echo url_for('@ps_feature_branch_group_filters') ?>',
			type: "POST",
			data: 'c_id=' + $('#feature_branch_times_filters_ps_customer_id').val() + '&w_id=' + $('#feature_branch_times_filters_ps_workplace_id').val() + '&y_id=' + $('#feature_branch_times_filters_school_year_id').val() + '&i_at=1',
			processResults: function (data, page) {
				return {
				  results: data.items  
				};
			},
		}).done(function(msg) {
			$('#feature_branch_times_filters_ps_feature_branch_id').select2('val','');
			$("#feature_branch_times_filters_ps_feature_branch_id").html(msg);
			$("#feature_branch_times_filters_ps_feature_branch_id").attr('disabled', null);               
		});	
	});

	$('#feature_branch_times_filters_school_year_id').change(function() {

		$("#feature_branch_times_filters_ps_class_id").attr('disabled', 'disabled');
		
		$.ajax({
			url: '<?php echo url_for('@ps_class_by_params') ?>',
	        type: "POST",
	        data: 'c_id=' + $('#feature_branch_times_filters_ps_customer_id').val() + '&w_id=' + $('#feature_branch_times_filters_ps_workplace_id').val() + '&y_id=' + $('#feature_branch_times_filters_school_year_id').val(),
	        processResults: function (data, page) {
          		return {
            		results: data.items
          		};
        	},
	    }).done(function(msg) {
	    	$('#feature_branch_times_filters_ps_class_id').select2('val','');
			$("#feature_branch_times_filters_ps_class_id").html(msg);
			$("#feature_branch_times_filters_ps_class_id").attr('disabled', null);
	    });
		
	});


    $('#feature_branch_times_filters_ps_year').change(function() {

    	if ($(this).val() <= 0) {
			return;
		}
		
    	$("#feature_branch_times_filters_ps_week").attr('disabled', 'disabled');
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
  			 $('#feature_branch_times_filters_ps_week').select2('val','');
 			 $("#feature_branch_times_filters_ps_week").html(msg);
  			 $("#feature_branch_times_filters_ps_week").attr('disabled', null);

  			$('#feature_branch_times_filters_ps_week').val(1);
			$('#feature_branch_times_filters_ps_week').change();
  		});
    });

	$('#feature_branch_times_filters_ps_class_id').change(function() {
		
		resetOptions('feature_branch_times_filters_ps_feature_branch_id');
		
		$('#feature_branch_times_filters_ps_feature_branch_id').select2('val','');
		
		$("#feature_branch_times_filters_ps_feature_branch_id").attr('disabled', 'disabled');
		
		if ($('#feature_branch_times_filters_ps_customer_id').val() <= 0 || $(this).val() <= 0) {
			return;
		}
				
		$.ajax({
			url: '<?php echo url_for('@ps_feature_branch_group_filters') ?>',
			type: "POST",
			data: 'c_id=' + $('#feature_branch_times_filters_ps_customer_id').val() + '&w_id=' + $('#feature_branch_times_filters_ps_workplace_id').val() + '&y_id=' + $('#feature_branch_times_filters_school_year_id').val() + '&cl_id=' + $('#feature_branch_times_filters_ps_class_id').val() + '&i_at=1',
			processResults: function (data, page) {
				return {
				  results: data.items  
				};
			},
		}).done(function(msg) {
			$('#feature_branch_times_filters_ps_feature_branch_id').select2('val','');
			$("#feature_branch_times_filters_ps_feature_branch_id").html(msg);
			$("#feature_branch_times_filters_ps_feature_branch_id").attr('disabled', null);               
		});		
	});
});
</script>
<style>
.lichhoatdong{border-bottom: 1px solid #ddd;padding-top: 10px;}
</style>
<section id="widget-grid">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">      
      <?php include_partial('psFeatureBranchTimes/flashes') ?>
      <div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-togglebutton="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-cutlery"></i></span>
					<h2><?php echo __('Schedule activities', array(), 'messages') ?></h2>
					<div class="widget-toolbar">
						<a target="_blank"
							class="btn btn-default btn-success btn-sm btn-psadmin btn-delete hidden-xs"
							href="<?php echo url_for(@ps_feature_branch_import) ?>"><i
							class="fa-fw fa fa-upload"
							title="<?php echo __('Import file') ?>"></i> <?php echo __('Import file') ?></a>
					</div>
				</header>

				<div>
	    
		<?php $width_th = (100 / (count($week_list) + 1));?>

            <div class="widget-body no-padding">
						<div class="dt-toolbar">
							<div class="col-xs-12 col-sm-12">
								<div id="dt_basic_filter"
									class="sf_admin_filter dataTables_filter">
                    	<?php if ($formFilter->hasGlobalErrors()): ?>
                          <?php echo $formFilter->renderGlobalErrors() ?>
                        <?php endif; ?>
                        	<form id="ps-filter"
										class="form-inline pull-left"
										action="<?php echo url_for('ps_feature_branch_times_collection', array('action' => 'show')) ?>"
										method="post">
										<div class="pull-left">
                            	 	<?php echo $formFilter->renderHiddenFields(true) ?>
                            	 	<div class="form-group">
												<label>
                                		 	<?php echo $formFilter['school_year_id']->render() ?>
                                		 </label>
											</div>

											<div class="form-group">
												<label>
                                		 	<?php echo $formFilter['ps_customer_id']->render() ?>
                                		</label>
											</div>
											<div class="form-group">
												<label>
                                		 	<?php echo $formFilter['ps_workplace_id']->render() ?>
                                		</label>
											</div>

											<div class="form-group">
												<label>
                            		 		<?php echo $formFilter['ps_class_id']->render() ?>
                            		  	</label>
											</div>

											<div class="form-group">
												<label>
                            		 		<?php echo $formFilter['ps_feature_branch_id']->render() ?>
                            		  	</label>
											</div>

											<div class="form-group">
												<label>
                            		 		<?php echo $formFilter['ps_year']->render() ?>
                            		  	</label>
											</div>

											<div class="form-group">
												<label>
                            		 		<?php echo $formFilter['ps_week']->render() ?>
                            		  	</label>
											</div>

											<div class="form-group">
												<label>
                            				<?php echo $helper->linkToFilterSearch() ?>
                            			</label>
											</div>

											<div class="form-group">
												<label>
                            				<?php echo $helper->linkToFilterReset2() ?>
                            			</label>
											</div>

										</div>
									</form>
								</div>
							</div>
						</div>
				
						<table id="tbl-List-User" class="table table-bordered table-striped">
							<input type="hidden" id="count_list_menus" value="<?php echo count($list_menu)?>">
							<thead>
								<tr>
            			<?php foreach ($week_list as $date => $monday):?>
            			<th class="text-center <?php if (date('N', strtotime($date)) == 6) echo 'bg-color-yellow'; elseif (date('N', strtotime($date)) == 7) echo 'bg-color-pink';?>" style="width: <?php echo $width_th?>%;"><b><?php echo __($monday)?><br>
											<div class="date"><?php echo format_date($date, "dd-MM-yyyy");?></div></b>
									</th>
            			<?php endforeach;?>
            		</tr>
							</thead>

							<tbody>
							
            	<?php if(count($list_menu) > 0):?>
        		
        		
        		<tr>
        		
        			<?php foreach ($week_list as $date => $monday): ?> 
        			<td>
        			<?php foreach ($list_menu as $key => $fbtimes):?>
        			
	        			<?php
						// Xac dinh lich hoat dong co vao thu 7 hoac chu nhat khong
						if ($date >= $fbtimes->getStartAt () && $date <= $fbtimes->getEndAt ()) :
							if ($fbtimes->getIsSaturday () == 0 && date ( 'N', strtotime ( $date ) ) == 6) :
								continue;
							 elseif ($fbtimes->getIsSunday () == 0 && date ( 'N', strtotime ( $date ) ) == 7) :
								continue;
							endif;
							?>
        					<div class="lichhoatdong">
        					<p>
        						<?php echo $fbtimes->getFbName()?>
        						<a class="btn btn-default btn-xs txt-color-blueLight"
									rel="popover-hover" data-placement="bottom"
									data-original-title="<?php echo $fbtimes->getFbName() . '&emsp;' . format_date($fbtimes->getStartTime(), "HH:mm") . '&rarr;' . format_date($fbtimes->getEndTime(), "HH:mm") ?>"
									data-content="<?php if($ps_class_id > 0){echo $fbtimes->getFbtcNote().'<br/>';}else{ echo $fbtimes->getNote().'<br/>';} ?><?php echo __('List class apply').': '.$fbtimes->getNoteClassName(); ?>"
									data-html="true"><i class="fa fa-gear"></i></a> 
								<a class="btn btn-xs btn-default btn-edit-td-action" data-backdrop="static" data-toggle="modal" data-target="#remoteModal"
									href="<?php echo url_for(@ps_feature_branch_times).'/'.$fbtimes->getId().'/'?>edit"><i
									class="fa-fw fa fa-pencil txt-color-orange"
									title="<?php echo __('Edit')?>"></i></a>
							</p>
							<p><?php echo format_date($fbtimes->getStartTime(), "HH:mm") ?> &rarr; <?php echo format_date($fbtimes->getEndTime(), "HH:mm") ?></p>
							</div>
        					<?php endif;?>
        				<?php endforeach;?>	
					</td>
					
        			<?php endforeach;?>
        		</tr>
        		
        		<?php else:?>
        			<tr>
						<td colspan="7"><b><?php echo __('Not feature branch times by week')?></b>
						</td>
					</tr>
        		<?php endif;?>
        	</tbody>
						</table>

					</div>

				</div>

				<div class="form-actions" style="padding: 10px;">
					<div class="sf_admin_actions">
						<a target="_blank"
							class="btn btn-default btn-success btn-sm btn-psadmin btn-delete hidden-xs"
							href="<?php echo url_for(@ps_feature_branch_import) ?>"><i
							class="fa-fw fa fa-upload"
							title="<?php echo __('Import file') ?>"></i> <?php echo __('Import file') ?></a>
					</div>
				</div>

			</div>
		</article>

	</div>
</section>