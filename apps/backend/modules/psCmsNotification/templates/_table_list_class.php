
<script>
$(document).ready(function() {

	$('.check-all').on('change', function() {
		
	   if($(".check-all:checked").val() == 0){
		   $('._check_all').prop('checked',true);
		   $('.check-all-teacher').prop('checked',true);
		   $('.check-all-relative').prop('checked',true);
	   }else{
		   $('._check_all').prop('checked',false);
		   $('.check-all-teacher').prop('checked',false);
		   $('.check-all-relative').prop('checked',false);
		}
	});

	$('.check_class').on('change', function() {
		var class_id = $(this).val();
		
	   if($("#check_class_"+class_id+":checked").val() == class_id){
		   $('._check_all_'+class_id).prop('checked',true);
		}else{
		   $('._check_all_'+class_id).prop('checked',false);
		}
	   $('.check-all-teacher').prop('checked',false);
	   $('.check-all-relative').prop('checked',false);
	});

	$('._check_all').on('change', function() {
		var class_id = $(this).attr('data-value');
		$('.check-all').attr('disabled', false);
		$('.check-all').prop('checked',false);
		$('#check_class_'+class_id).prop('checked',false);
	});

	$('.btn-psadmin').attr('disabled', 'disabled');
	
	$('.checkbox').on('change', function() {
		if($(".checkbox:checked").val() >= 0){
			$('.btn-psadmin').attr('disabled', false);
		}else{
			$('.btn-psadmin').attr('disabled', 'disabled');
		}
	});

	$('.check-all-teacher').on('change', function() {
		if($(".check-all-teacher:checked").val() == 0){
			$('._check_teacher').prop('checked',true);
	   }else{
		   $('._check_teacher').prop('checked',false);
		}
		$('.check-all').prop('checked',false);
		$('.check_class').prop('checked',false);
	});
	
	$('.check-all-relative').on('change', function() {
		if($(".check-all-relative:checked").val() == 0){
			$('._check_relative').prop('checked',true);
	   }else{
		   $('._check_relative').prop('checked',false);
		}
		$('.check-all').prop('checked',false);
		$('.check_class').prop('checked',false);
	});

	$('._check_teacher').on('change', function() {
		$('.check-all').prop('checked',false);
// 		$('.check_class').prop('checked',false);
		$('.check-all-teacher').prop('checked',false);
	});
	
	$('._check_relative').on('change', function() {
		$('.check-all').prop('checked',false);
// 		$('.check_class').prop('checked',false);
		$('.check-all-relative').prop('checked',false);
	});
});

</script>
<section class="tbl-header table_scroll">
<div class="container_table custom-scroll table-responsive no-margin" style="max-height: 600px; overflow-y: scroll;">
	<div id="dt_basic_wrapper" class="dataTables_wrapper form-inline"></div>
	<table id="load-ajax"
		class="table table-striped table-bordered table-hover no-footer"
		width="100%" style="border: 1px solid #ccc !important">
		<thead>
			<tr>
				<th class="col-xs-2"><label><input class="checkbox check-all"
						type="checkbox" name="notification[]" value="0" /><span></span></label><?php echo __('Class', array(), 'messages') ?></th>
				<th class="col-xs-3"><label><input
						class="checkbox check-all-teacher" type="checkbox"
						name="array_teacher[]" value="0" /><span></span></label><?php echo __('Member', array(), 'messages') ?></th>
				<th class="col-xs-7"><label><input
						class="checkbox check-all-relative" type="checkbox"
						name="array_relative[]" value="0" /><span></span></label><?php echo __('Relative', array(), 'messages') ?></th>
			</tr>
		</thead>

		<tbody>

			<div id="ic-loading" style="display: none;">
				<i class="fa fa-spinner fa-2x fa-spin text-success"
					style="padding: 3px;"></i><?php echo __('Loading...')?>
        </div>
        <?php

if ($sf_user->hasCredential ( 'PS_CMS_NOTIFICATIONS_ALL' ) || $sf_user->hasCredential ( 'PS_CMS_NOTIFICATIONS_WORKPLACE' )) {
									foreach ( $my_class as $class ) {
										?>
            <tr>
				<td class=""><label> <input class="_check_all checkbox check_class"
						id="check_class_<?php echo $class->getId()?>" type="checkbox"
						name="notification[]" value="<?php echo $class->getId();?>" /> <span></span>
				</label><?php echo $class->getName() ?>
            	</td>

				<td class="">
            		<?php include_partial('psCmsNotification/td_list_member', array('class' => $class)) ?>
            	</td>
				<td class="">
            		<?php include_partial('psCmsNotification/td_list_relative', array('class' => $class)) ?>
            	</td>
			</tr>
            
         <?php

}
								} else {

									$member_id = myUser::getUser ()->getMemberId ();

									$array_class = array ();

									$list_class = Doctrine::getTable ( 'PsTeacherClass' )->getClassByMemberIds ( $member_id );
									foreach ( $list_class as $list ) {
										array_push ( $array_class, $list->getPsMyclassId () );
									}

									foreach ( $my_class as $class ) {

										if (in_array ( $class->getId (), $array_class )) {
											?>
            <tr>
				<td class=""><label> <input class="_check_all checkbox check_class"
						id="check_class_<?php echo $class->getId()?>" type="checkbox"
						name="notification[]" value="<?php echo $class->getId();?>" /> <span></span>
				</label>
            		<?php echo $class->getName() ?>
            	</td>

				<td class="">
            		<?php include_partial('psCmsNotification/td_list_member', array('class' => $class)) ?>
            	</td>
				<td class="">
            		<?php include_partial('psCmsNotification/td_list_relative', array('class' => $class)) ?>
            	</td>
			</tr>
            <?php } ?>
        <?php } }?>
        </tbody>

	</table>
</div>
</section>