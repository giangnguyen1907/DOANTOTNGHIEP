<div class="box-body">
	<form id="psnew-filter" class="form-inline pull-left dataTables_filter"
		action="<?php echo url_for('ps_menus_collection', array('action' => 'new')) ?>"
		method="post">
		<div class="dt-toolbar" style="padding-bottom: 10px;">
			<div class="form-group ">
				<label> 
			 <?php echo $formFilter['ps_customer_id']->render()?>
			 <?php echo $formFilter['ps_customer_id']->renderError()?>
			 </label>
			</div>
			<div class="form-group ">
				<label> 
			 <?php echo $formFilter['ps_workplace_id']->render()?>
			 <?php echo $formFilter['ps_workplace_id']->renderError()?>
			 </label>
			</div>
			<div class="form-group">
				<label>
			 	<?php echo $formFilter['ps_object_group_id']->render()?>
			 	<?php echo $formFilter['ps_object_group_id']->renderError()?>	
			 	 </label>
			</div>
			<div class="form-group">
				<label>
			 	<?php echo $formFilter['ps_year']->render()?>
			 	<?php echo $formFilter['ps_year']->renderError()?>
			 	 </label>
			</div>
			<div class="form-group ">
				<label>
			 	<?php echo $formFilter['ps_week']->render()?>
			 	<?php echo $formFilter['ps_week']->renderError()?>
			 	 </label>
			</div>
			<div class="form-group ">
				<div class="btn-group">
					<label> <a href="javascript:void(0)" rel="tooltip"
						data-placement="top"
						data-original-title="<?php echo __("Week pre");?>"
						class="btn btn-default btn-sm" id="btn-prev"><i
							class="fa fa-chevron-left"></i></a> <a href="javascript:void(0)"
						class="btn btn-default btn-sm" id="btn-next"><i
							class="fa fa-chevron-right"></i></a>
					</label>
				</div>
			</div>
			<?php if(myUser::credentialPsCustomers ( 'PS_NUTRITION_MENUS_ADD' )) :?>
			<div class="form-group ">
				<label> <a data-toggle="modal" data-target="#copyModal"
					data-backdrop="static" id="btn-copy-menu"
					class="btn btn-sm btn-default btn-filter-search btn-psadmin"
					data-item="<?php echo count($list_menu)?>"><i
						class="fa-fw fa fa-files-o"
						title="<?php echo __('Copy', array())?>"></i> <?php echo __('Copy')?></a>
				</label>
			</div>
			<?php endif;?>
		</div>
		
		<?php include_partial('global/include/_ic_loading');?>
		
		<div id="tbl-menu">	
		<?php include_partial('psMenus/table_menu', array('list_meal' => $list_meal, 'list_menu'=>$list_menu, 'week_list' => $week_list, 'width_th' => (100 / (count($week_list) + 1)),'formFilter' => $formFilter,'form' => $form, 'ps_menus' => $ps_menus));?>
		</div>
	</form>
</div>
<?php if(myUser::credentialPsCustomers ( 'PS_NUTRITION_MENUS_ADD' )) :?>
	<?php include_partial('psMenus/box_modal_copy_menu', array('formFilter' => $formFilter));?>
<?php endif;?>