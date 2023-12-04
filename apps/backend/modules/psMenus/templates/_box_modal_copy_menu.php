<?php
$baseForm = new BaseForm ();
?>
<form class="form-horizontal" id="ps-form-copy" data-fv-addons="i18n"
	method="post" action="<?php echo url_for('@ps_menus_copy')?>">
	<input type="hidden" name="sf_method" value="delete" />
<?php echo $baseForm->renderHiddenFields(true);?>
<div class="modal fade" id="copyModal" role="dialog"
		aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"
						aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">
						<i class="fa fa-cutlery" aria-hidden="true"></i> <?php echo __('Copy menu')?></h4>
				</div>

				<div class="modal-body">
					<label class="control-label"><?php echo __('Week source')?></label>
					<div class="form-group">
						<div class="col-md-12">
				 	<?php echo $formFilter['ps_week']->render(array('disabled' => 'disabled','id' => 'week_source')) ?>
				 	<?php echo $formFilter['ps_week']->renderError()?>
				 	</div>
						<input type="hidden" name="form[ps_year_source]"
							id="form_ps_year_source"> <input type="hidden"
							name="form[week_source]" id="form_ps_week_source"> <input
							type="hidden" name="form[ps_customer_id]"
							id="form_ps_customer_id"> <input type="hidden"
							name="form[ps_workplace_id]" id="form_ps_workplace_id"> <input
							type="hidden" name="form[ps_object_group_id]"
							id="form_ps_object_group_id"> <input type="hidden"
							name="form[ps_workplace_id_destination]"
							id="form_ps_workplace_id_destination">
					</div>

					<label class="control-label"><?php echo __('Week destination')?></label>
					<div class="form-group ">
						<div class="col-md-12">
						<?php echo $formFilter['ps_object_group_id']->render(array('id' => 'form_ps_object_group_id_destination', 'name' => 'form[ps_object_group_id_destination]', 'required' => true)) ?>
					 	<?php echo $formFilter['ps_object_group_id']->renderError()?>
					</div>
					</div>

					<div class="form-group ">
						<div class="col-md-2">				
				 		<?php echo $formFilter['ps_year']->render(array('id' => 'form_ps_year_destination', 'name' => 'form[ps_year_destination]', 'required' => true)) ?>
				 		<?php echo $formFilter['ps_year']->renderError()?>
				 	</div>
						<div class="col-md-10">					
				 		<?php echo $formFilter['ps_week']->render(array('id' => 'form_ps_week_destination', 'name' => 'form[week_destination]', 'required' => true)) ?>
				 		<?php echo $formFilter['ps_week']->renderError()?>			 
				 	</div>
					</div>
				</div>

				<div class="modal-footer">				
				<?php if(myUser::credentialPsCustomers ( 'PS_NUTRITION_MENUS_ADD' )) :?>
				<button type="submit"
						class="btn btn-default btn-success btn-sm btn-psadmin btn-submit">
						<i class="fa-fw fa fa fa-check-circle"></i> <?php echo __('OK')?></button>
				<?php endif;?>
				<button type="button"
						class="btn btn-default btn-sm btn-psadmin btn-cancel"
						data-dismiss="modal">
						<i class="fa-fw fa fa-ban"></i> <?php echo __('Cancel')?></button>
				</div>
			</div>
		</div>
	</div>
</form>