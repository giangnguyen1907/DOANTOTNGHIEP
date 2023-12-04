<?php use_helper('I18N', 'Date') ?>
<style>
.datepicker {
	z-index: 1051 !important;
}

.ui-datepicker {
	z-index: 1051 !important;
}
</style>
<?php
$ps_class_id = $my_class->getId ();
$teachers_not_assignment_class = $my_class->getTeacherNotInClass ();
$school_code = $my_class->getPsCustomer ()
	->getSchoolCode ();
?>
<form class="form-horizontal" id="ps-form-list-teacher"
	name="ps-form-list-teacher"
	action="<?php echo url_for("@ps_teachers_class?id=".$ps_class_id)?>"
	method="post" enctype="application/x-www-form-urlencoded">
	<input type="hidden" value="<?php echo $ps_class_id;?>"
		name="my_class_id" /> <input type="hidden"
		value="<?php echo $ps_class_id;?>" name="id" />
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal"
			aria-hidden="true">×</button>
		<h4 class="modal-title" id="myModalLabel"><?php echo __('Assigned teachers').": ".$my_class->getName();?></h4>
	</div>
	<div class="modal-body">
		<table id="datatable_fixed_column"
			class="table table-striped table-bordered dataTable no-footer"
			width="100%">
			<thead>
				<tr>
					<th></th>
					<th class="hasinput"><input type="text" class="form-control"
						placeholder="Filter Name" /></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
				</tr>
				<tr>
					<th style="width: 50px;" class="text-center"><?php echo __('Image');?></th>
					<th><?php echo __('Full name');?></th>
					<th style="width: 70px;">
						<div class="checkbox" style="padding-top: 0px; min-height: 10px">
							<label><input id="remove_all" class="remove_all checkbox style-0"
								type="checkbox" style="height: 0px;" /> <span><?php echo __('HTeacher');?></span></label>
						</div>
					</th>
					<th style="width: 130px;"><?php echo __('Start at');?></th>
					<th style="width: 130px;"><?php echo __('End at');?></th>
					<th class="text-center" style="width: 50px;">
						<div class="checkbox" style="padding-top: 0px; min-height: 10px">
							<label> <input id="sf_admin_list_batch_checkbox"
								class="sf_admin_list_batch_checkbox checkbox style-0"
								type="checkbox" style="height: 0px;" /><span></span>
							</label>
						</div>
					</th>
				</tr>
			</thead>
			<tbody>
        	<?php
									$ps_teacher_class_form = new PsTeacherClassForm ();
									foreach ( $teachers_not_assignment_class as $teacher ) :
										?>
        	<tr row-id="<?php echo $teacher->getId()?>">
					<td>
        	<?php
										if ($teacher->getImage () != '') {
											echo image_tag ( '/pschool/' . $school_code . '/hr/thumb/' . $teacher->getImage (), array (
													'style' => 'max-width:45px;text-align:center;' ) );
										}
										?>					
					</td>
					<td>
					<?php echo $teacher->getFirstName().' '.$teacher->getLastName();?>
					<?php echo '<br/>'.__('Birthday short').': '.(false !== strtotime($teacher->getBirthday()) ? format_date($teacher->getBirthday(), "dd-MM-yyyy") : '&nbsp;');?>
					<?php echo '<br/>'.__('Sex short').': '.get_partial('global/field_custom/_field_sex', array('value' => $teacher->getSex())) ?>
					</td>

					<td class="text-center">
						<div class="form-group">
							<div class="col-md-12">
								<div class="radio text-center">
									<label> <input type="radio" disabled="disabled"
										name="ps_teacher_class[][primary_teacher]"
										id="ps_teacher_class_<?php echo $teacher->getId()?>_primary_teacher"
										value="1"
										class="sf_admin_batch_checkbox radiobox style-0 primary_teacher" />
										<span></span>
									</label>
								</div>
							</div>
						</div>
					</td>
					<td>
						<div class="form-group">
							<div class="col-md-12">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
									<input type="text" placeholder="dd-mm-yyyy" required="required"
										disabled="disabled" class="form-control start_at"
										data-dateformat="dd-mm-yy"
										id="ps_teacher_class_<?php echo $teacher->getId()?>_start_at"
										name="teacher_class[][start_at]">
								</div>
							</div>
						</div>
					</td>
					<td>
						<div class="form-group">
							<div class="col-md-12">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
									<input type="text" placeholder="dd-mm-yyyy"
										class="form-control end_at" data-dateformat="dd-mm-yy"
										disabled="disabled"
										id="ps_teacher_class_<?php echo $teacher->getId()?>_end_at"
										name="teacher_class[][end_at]">
								</div>
							</div>
						</div>
					</td>
					<td class="text-center">
						<div class="form-group">
							<div class="col-md-12">
								<label> <input type="checkbox" name="teachers_id[]"
									id="chk_id_<?php echo $teacher->getId();?>"
									value="<?php echo $teacher->getId();?>"
									class="sf_admin_action_checkbox checkbox style-0" /> <span></span>
								</label>
							</div>
						</div>
					</td>
				</tr>
				<?php endforeach;?>
			</tbody>
		</table>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Cancel')?></button>
		<button type="submit" name="ps_teacher_class" id="ps_teacher_class"
			class="btn btn-default btn-success btn-sm btn-psadmin">
			<i class="fa-fw fa fa-floppy-o" aria-hidden="true"
				title="<?php echo _('Save')?>"></i><?php echo __('Save')?>
		</button>
	</div>
</form>
<script>
"use strict";
$(document).ready(function() {	

    $('a[data-toggle="tooltip"]').tooltip({
        animated: 'fade',
        placement: 'bottom',
        html: true
    });

    $('.sf_admin_action_checkbox').click(function (event) {

		 var $row  = $(this).parents('tr');

         if (this.checked) {

            var $_start_at_name = 'ps_teacher_class[][start_at]',
            	$_end_at_name = 'ps_teacher_class[][end_at]'
            ;                       

            $('#ps_teacher_class_' + $row.attr('row-id') + '_start_at').attr('disabled',null);
            $('#ps-form-list-teacher').formValidation('revalidateField',$_start_at_name);

            $('#ps_teacher_class_' + $row.attr('row-id') + '_end_at').attr('disabled',null);

            $('#ps-form-list-teacher').formValidation('revalidateField',$_end_at_name);

            $('#ps_teacher_class_' + $row.attr('row-id') + '_primary_teacher').attr('disabled',null);
            
			
         } else {

        	$('#ps_teacher_class_' + $row.attr('row-id') + '_primary_teacher').attr('disabled','disabled');
        	 
            var $_start_at_name = 'ps_teacher_class[][start_at]';
            var $_start_at_id   = 'ps_teacher_class_' + $row.attr('row-id') + '_start_at';

            var $_end_at_name   = 'ps_teacher_class[][end_at]';

            $('#ps_teacher_class_' + $row.attr('row-id') + '_start_at').attr('disabled','disabled');
            

            // Or
            $('#ps-form-list-teacher')
            	.formValidation('updateStatus', $_start_at_name, 'NOT_VALIDATED')
                .formValidation('resetField', $_start_at_name);

            //$('#ps-form-list-teacher').data('formValidation').resetForm();
            var fv = $('#ps-form-list-teacher').data('formValidation');
            fv.resetField($('#' + $_start_at_id), true);

            var $_end_at_name = 'ps_teacher_class[][end_at]';
            $('#ps-form-list-teacher').formValidation('revalidateField',$_end_at_name);

            $('#ps-form-list-teacher')
        	.formValidation('updateStatus', $_end_at_name, 'NOT_VALIDATED')
            .formValidation('resetField', $_end_at_name);
			
                     	
         }
         
    });

	$('.start_at').datepicker({
			prevText : '<i class="fa fa-chevron-left"></i>',
			nextText : '<i class="fa fa-chevron-right"></i>',
			changeMonth : true,
			changeYear : true,
			showButtonPanel : true,
			dateFormat : 'dd-mm-yy',
			onSelect : function(selectedDate) {									
				$('#ps-form-list-teacher').formValidation('revalidateField',$(this).attr('name'));
			}
		}).on('change',function(e) {
			//alert($(this).attr('name'));
			// Revalidate the date field
			$('#ps-form-list-teacher').formValidation('revalidateField',$(this).attr('name'));
	});

	$('.end_at').datepicker({
		prevText : '<i class="fa fa-chevron-left"></i>',
		nextText : '<i class="fa fa-chevron-right"></i>',
		changeMonth : true,
		changeYear : true,
		showButtonPanel : true,
		dateFormat : 'dd-mm-yy',
		onSelect : function(selectedDate) {									
			$('#ps-form-list-teacher').formValidation('revalidateField',$(this).attr('name'));
		}
	}).on('change',function(e) {
		// Revalidate the date field
		$('#ps-form-list-teacher').formValidation('revalidateField',$(this).attr('name'));
	});

	$('#ps-form-list-teacher')
    	.formValidation({
    		framework: 'bootstrap',
    		excluded: [':disabled', ':hidden'],
            addOns: {i18n: {}},
            icon: {},
    		fields: {
    			'ps_teacher_class[][start_at]': {
                    // The children's date of birth are inputs with class .childDob
                    //selector: '.start_at',
                    // The field is placed inside .col-xs-4 div instead of .form-group
                    row: '.col-md-12',
                    validators: {
                    	notEmpty: {
                    		message: 'Please fill out each field'
                        },
                        date: {
                            format: 'DD-MM-YYYY',
                            separator: '-',
                            //max: 'end_at',
                            message: 'The date of birth is not valid'
                        }
                    }
                },

                'end_at': {
                    // The children's date of birth are inputs with class .childDob
                    selector: '.end_at',
                    // The field is placed inside .col-xs-4 div instead of .form-group
                    row: '.col-md-12',
                    validators: {
                    	date: {
                            format: 'DD-MM-YYYY',
                            separator: '-',
                            //max: 'start_at',
                            message: 'The date of birth is not valid'
                        }
                    }
                }        		
    		}
	});
	$('#ps-form-list-teacher').formValidation('setLocale', 'vi_VN');		
});
</script>