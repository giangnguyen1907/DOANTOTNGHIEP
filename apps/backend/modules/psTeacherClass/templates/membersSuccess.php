<?php use_helper('I18N', 'Date') ?>

<?php include_partial('global/include/_box_modal_messages');?>
<style>
.control-label{text-align: right;}
.radio-inline{margin-top: 0px;}
</style>

<section id="widget-grid">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<?php include_partial('psTeacherClass/flashes') ?>
			<div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-grid="false" data-widget-collapsed="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false" data-widget-togglebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-table"></i></span>
					<h2><?php echo __('Move teacher class', array(), 'messages') ?></h2>
				</header>
				<div class="widget-body" style="overflow: hidden;">
					<form id="ps_teacher_class" method="post"
						action="<?php echo url_for('ps_teacher_class_collection', array('action' => 'members')) ?>">
						<div class="widget-body">
							<div class="row">
								
								<div class='col-md-6 col-xs-12 col-sm-12'>
									<div class="sf_admin_form_row sf_admin_foreignkey" style="margin-top: 25px">
										<label class="col-md-3 control-label">
						                	<?php echo $formFilter['ps_class_id']->renderLabel() ?> *
										</label>
										<div class="col-md-9">
						                	<?php echo $formFilter['ps_class_id']->render() ?>
						                    
						                </div>
									</div>
								</div>
								
								<div class='col-md-6 col-xs-12 col-sm-12'>
									<div class="sf_admin_form_row sf_admin_foreignkey" style="margin-top: 25px">
										<label class="col-md-3 control-label">
						                	<?php echo $formFilter['ps_member_id']->renderLabel() ?> *
										</label>
										<div class="col-md-9">
						                	<?php echo $formFilter['ps_member_id']->render() ?>
						                    
						                </div>
									</div>
								</div>
								
								<div class='col-md-6 col-xs-12 col-sm-12'>
									<div class="sf_admin_form_row sf_admin_foreignkey" style="margin-top: 25px">
										<label class="col-md-3 control-label">
						                	<?php echo $formFilter['start_at']->renderLabel() ?> *
										</label>
										<div class="col-md-9">
						                	<?php echo $formFilter['start_at']->render() ?>
						                    
						                </div>
									</div>
								</div>
								
								<div class='col-md-6 col-xs-12 col-sm-12'>
									<div class="sf_admin_form_row sf_admin_foreignkey" style="margin-top: 25px">
										<label class="col-md-3 control-label">
						                	<?php echo $formFilter['stop_at']->renderLabel() ?> *
										</label>
										<div class="col-md-9">
						                	<?php echo $formFilter['stop_at']->render() ?>
						                    
						                </div>
									</div>
								</div>
								
								<div class='col-md-6 col-xs-12 col-sm-12'>
									<div class="sf_admin_form_row sf_admin_foreignkey" style="margin-top: 25px">
										<label class="col-md-3 control-label">
						                	<?php echo $formFilter['primary_teacher']->renderLabel() ?> *
										</label>
										<div class="col-md-9">
						                	<?php echo $formFilter['primary_teacher']->render() ?>
						                    <?php echo $formFilter['primary_teacher']->renderError() ?>
						                </div>
									</div>
								</div>
								
							</div>

						</div>
						<div class="widget-body-toolbar">
							<div class="row">
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
									<a class="btn btn-default btn-success" href="<?php echo url_for('@ps_class_edit?id=' . $ps_class_id . '#pstab_3')?>">
										<i class="fa-fw fa fa-undo" aria-hidden="true"
										title="<?php echo __('Roll back');?>"></i><?php echo __('Roll back', array(), 'messages') ?>
                   					</a>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
									<button type="submit" class="btn btn-default btn-success btn-sm">
										<i class="fa-fw fa fa-floppy-o" aria-hidden="true"
										title="<?php echo __('Save');?>"></i><?php echo __('Save', array(), 'messages') ?>
                   					</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</article>
	</div>
</section>	

<script>
$(document).on("ready", function(){

	var msg_select_ps_class_id	= '<?php echo __('Please select class to filter the data.')?>';
	var msg_select_ps_member_id 	= '<?php echo __('Please select teacher to filter the data.')?>';
	var msg_select_start_at	= '<?php echo __('Please select start at to filter the data.')?>';
	var msg_select_stop_at	= '<?php echo __('Please select stop at to filter the data.')?>';

	$('#ps_teacher_class').formValidation({
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
			"ps_teacher_class[ps_class_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_class_id,
                        		  en_US: msg_select_ps_class_id
                        }
                    }
                }
            },
            
            "ps_teacher_class[ps_member_id]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_ps_member_id,
                        		  en_US: msg_select_ps_member_id
                        }
                    }
                }
            },
            "ps_teacher_class[start_at]": {
                validators: {
                    notEmpty: {
                        message: {vi_VN: msg_select_start_at,
                        		  en_US: msg_select_start_at
                        }
                    }
                }
            },
		}
    }).on('err.form.fv', function(e) {
    	$('#messageModal').modal('show');
    });
    $('#ps_teacher_class').formValidation('setLocale', PS_CULTURE);

    $('#ps_teacher_class_start_at').datepicker({
		dateFormat : 'dd-mm-yy',
		prevText : '<i class="fa fa-chevron-left"></i>',
		nextText : '<i class="fa fa-chevron-right"></i>',
		changeMonth : true,
		changeYear : true,
	});
	$('#ps_teacher_class_stop_at').datepicker({
		dateFormat : 'dd-mm-yy',
		prevText : '<i class="fa fa-chevron-left"></i>',
		nextText : '<i class="fa fa-chevron-right"></i>',
		changeMonth : true,
		changeYear : true,
	})
});
</script>