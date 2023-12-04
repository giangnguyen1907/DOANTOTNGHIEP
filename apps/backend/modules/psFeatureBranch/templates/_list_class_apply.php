<?php
$list_class = $form->getObject ()
	->getPsFeatureBranchMyClass ();
?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<div class="widget-body-toolbar">
		<a rel="tooltip" data-placement="left"
			data-original-title="<?php echo __('Choose the class from the list');?>"
			data-toggle="modal" data-target="#remoteModal" data-backdrop="static"
			class="btn btn-default btn-success btn-sm btn-psadmin pull-right"
			href="<?php echo url_for('@ps_feature_branch_set_myclass?id='.$form->getObject ()->getId()) ?>"><i
			class="fa fa-plus-circle fa-lg" aria-hidden="true"></i></a>
	</div>
	<div class="custom-scroll table-responsive"
		style="height: 290px; overflow-y: scroll;">
		<table id="dt_basic_class"
			class="table table-striped table-bordered table-hover" width="100%">
			<thead>
				<tr>
					<th class="no-order"><?php echo __('Class name');?></th>
					<th class="no-order"><?php echo __('Obj group title');?></th>
					<th class="no-order"><?php echo __('HTeacher');?></th>
					<th class="no-order" style="max-width: 80px;"><?php echo __('Actions', array(), 'sf_admin') ?></th>
				</tr>
			</thead>
			<tbody>
					<?php
					foreach ( $list_class as $obj ) {
						?>
					<tr>
					<td><?php echo $obj->getClassName();?></td>
					<td><?php echo $obj->getGroupName();?></td>
					<td></td>
					<td><?php echo $obj->getId();?></td>
				</tr>
					<?php }?>
				</tbody>
		</table>
	</div>
</div>