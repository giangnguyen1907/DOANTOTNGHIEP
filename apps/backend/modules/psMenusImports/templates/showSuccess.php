<?php use_helper('I18N', 'Date') ?>
<?php //include_partial('psMenusImports/assets') ?>
<?php //include_partial('global/include/_box_modal_warning');?>
<?php include_partial('global/include/_box_modal')?>
<?php include_partial('global/include/_box_modal_messages');?>
<script type="text/javascript">
$(document).on("ready", function(){

	$('#remoteModal').on('hide.bs.modal', function(e) {
		$(this).removeData('bs.modal');
	});

});
</script>
<style>
@media (min-width: 768px){
	section.table_scroll {
	  padding-top: 50px;
	}
}
select.select2 {
    display: block;
    width: 100%;
    height: 32px;
    padding: 6px 12px;
    font-size: 13px;
    line-height: 1.42857143;
    color: #555;
    background-color: #fff;
    background-image: none;
    border: 1px solid #ccc;
    border-radius: 0;
    -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
    box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
    -webkit-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
    -o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
}
</style>

<script type="text/javascript">

$(document).ready(function() {
	
	$(".widget-body-toolbar a, .btn-group a, .btn-filter-reset").on("contextmenu",function(){
	    return false;
	});

	$('#feature_branch_times_filters_ps_customer_id').change(function() {

		resetOptions('feature_branch_times_filters_ps_workplace_id');
		$('#feature_branch_times_filters_ps_workplace_id').select2('val','');
		
		if ($(this).val() > 0) {

			$("#feature_branch_times_filters_ps_workplace_id").attr('disabled', 'disabled');
			
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

		    });
		}		
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

});
</script>
<style>
.lichhoatdong{border-bottom: 1px solid #ddd;}
.lichhoatdong p{white-space: pre-line;}
.lichhoatdong:last-child{border-bottom: none;}
</style>
<section id="widget-grid">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">      
      <?php include_partial('psMenusImports/flashes') ?>
      <div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-togglebutton="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-cutlery"></i></span>
					<h2><?php echo __('PsMenusImports List', array(), 'messages') ?></h2>
					<div class="widget-toolbar">
						<?php if($sf_user->hasCredential('PS_NUTRITION_MENUS_IMPORT')){ ?>
						<a target="_blank"
							class="btn btn-default btn-success btn-sm btn-psadmin btn-delete hidden-xs"
							href="<?php echo url_for(@ps_menus_import) ?>"><i
							class="fa-fw fa fa-cloud-upload"
							title="<?php echo __('Imports menus') ?>"></i> <?php echo __('Imports menus') ?></a>
						<?php } ?>
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
										action="<?php echo url_for('ps_menus_imports_collection', array('action' => 'show')) ?>"
										method="post">
										<div class="pull-left">
                            	 	<?php echo $formFilter->renderHiddenFields(true) ?>
                            	 	
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
                            		 		<?php echo $formFilter['ps_obj_group_id']->render() ?>
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
						<div class="clear" style="clear: both;"></div>
						<section class="table_scroll">
						<div class="container_table custom-scroll table-responsive">
						<table id="tbl-List-User" class="table table-bordered table-striped" width="100%">
							<input type="hidden" id="count_list_menus" value="<?php echo count($list_menu)?>">
							<thead>
								<tr class="header hidden-sm hidden-xs">
									<th class="text-center">
									<div><?php echo __('Ps meal')?></div></th>
									<?php foreach ($week_list as $date => $monday):?>
				            			<th style="width: <?php echo $width_th?>%;">
											<div><?php echo __($monday)?><br>
											<span class="date"><?php echo format_date($date, "dd-MM-yyyy");?></span></div>
										</th>
			            			<?php endforeach;?>
								</tr>
								<tr class="hidden-md hidden-lg">
									<th class="text-center" style=""><?php echo __('Hour working')?> </th>
			            			<?php foreach ($week_list as $date => $monday):?>
			            			<th class="text-center <?php if (date('N', strtotime($date)) == 6) echo 'bg-color-yellow'; elseif (date('N', strtotime($date)) == 7) echo 'bg-color-pink';?>" style="width: <?php echo $width_th?>%;"><b><?php echo __($monday)?><br>
										<div class="date"><?php echo format_date($date, "dd-MM-yyyy");?></div></b>
									</th>
			            			<?php endforeach;?>
								</tr>
								
							</thead>

							<tbody>
							
            	<?php //if($ps_obj_group_id > 0):?>
        		
        		<?php foreach ($ps_meals as $meals):?>
        		<tr>
        			<td class=""><?php echo $meals->getTitle() ?>  </td>
        			<?php foreach ($week_list as $date => $monday): ?> 
        			<td>
        			<?php $tem = 0;?>
        			<?php foreach ($list_menu as $key => $menu):?>
        			
	        			<?php if($menu->getMealId() == $meals->getId() && strtotime($menu->getDateAt()) == strtotime($date)){?>
	        			<?php if($menu->getFileImage() !=''){
	        			    // $path_file = '/uploads/ps_nutrition/'.$menu->getFileImage();
							$path_file = $menu->getFileImage();
	        			}else{
	        			    // $path_file = '/sys_icon/'.$menu->getFileName();
							$path_file = $menu->getFileName();
	        			}?>
	        			<div class="lichhoatdong">
        					<p><img style="max-width: 60px; max-height: 60px;float: left;" src="<?php echo $path_file?>"/> <?php echo $menu->getDescription(); $tem = 1;?></p>
        					<a class="btn btn-xs btn-default btn-edit-td-action" data-backdrop="static" data-toggle="modal" data-target="#remoteModal"
									href="<?php echo url_for(@ps_menus_imports).'/'.$menu->getId().'/'?>edit"><i class="fa-fw fa fa-pencil txt-color-orange" title="<?php echo __('Edit')?>"></i></a>
        				</div>
	        			<?php }?>
	        			
        				<?php endforeach;?>	
        				<?php if($tem == 0){?>
        				<a class="btn btn-xs btn-default btn-edit-td-action" data-backdrop="static" data-toggle="modal" data-target="#remoteModal"
									href="<?php echo url_for(@ps_menus_imports_new).'?c='.$ps_customer_id.'&w='.$ps_workplace_id.'&o='.$ps_obj_group_id.'&m='.$meals->getId().'&date='.strtotime($date); ?>"><i class="fa-fw fa fa-plus txt-color-orange" title="<?php echo __('New')?>"></i></a>
        				<?php }?>
					</td>
					
        			<?php endforeach;?>
        		</tr>
        		<?php endforeach;?>
        		<?php //else:?>
        			<!--<tr>
						<td colspan="8"><b><?php echo __('You can choise object group')?></b>
						</td>
					</tr>-->
        		<?php //endif;?>
        	</tbody>
						</table>
						</div>
						</section>
					</div>

				</div>

				<div class="form-actions" style="padding: 10px;">
					<div class="sf_admin_actions">
						<?php if($sf_user->hasCredential('PS_NUTRITION_MENUS_IMPORT')){ ?>
						<a target="_blank"
							class="btn btn-default btn-success btn-sm btn-psadmin btn-delete hidden-xs"
							href="<?php echo url_for(@ps_menus_import) ?>"><i
							class="fa-fw fa fa-cloud-upload"
							title="<?php echo __('Imports menus') ?>"></i> <?php echo __('Imports menus') ?></a>
						<?php }?>
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
	var msg_select_ps_obj_group_id 	= '<?php echo __('Please select object group to filter the data.')?>';
	var msg_select_ps_year	= '<?php echo __('Please select school year to filter the data.')?>';
	var msg_select_ps_week	= '<?php echo __('Please select week to filter the data.')?>';

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
			"feature_branch_times_filters[ps_customer_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_customer_id,
                        		  en_US: msg_select_ps_customer_id
                        }
                    }
                }
            },
            
            "feature_branch_times_filters[ps_year]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_year,
                        		  en_US: msg_select_ps_year
                        }
                    }
                }
            },

            "feature_branch_times_filters[ps_week]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_week,
                        		  en_US: msg_select_ps_week
                        }
                    }
                }
            },
            
            "feature_branch_times_filters[ps_workplace_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_workplace_id,
                        		  en_US: msg_select_ps_workplace_id
                        }
                    }
                }
            },

            "feature_branch_times_filters[ps_obj_group_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_obj_group_id,
                        		  en_US: msg_select_ps_obj_group_id
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