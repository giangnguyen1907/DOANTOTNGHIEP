<div class="jarviswidget" id="wid-id-1" data-widget-editbutton="false"
	data-widget-colorbutton="false" data-widget-grid="false"
	data-widget-collapsed="false" data-widget-fullscreenbutton="false"
	data-widget-deletebutton="false" data-widget-togglebutton="false">
	<header>
		<span class="widget-icon"><i class="fa fa-arrow-circle-left"></i></span>
		<h2><?php echo __('Select Featureoptionsubject')?></h2>
	</header>
	<div>
		<div class="widget-body no-padding">
			<div id="datatable_fixed_column_wrapper"
				class="dataTables_wrapper form-inline no-footer no-padding">
				<div class="dt-toolbar no-margin no-padding no-border">
					<div class="col-xs-12 col-sm-12">
						<div id="sf_admin_header">
							<div class="alert alert-info no-margin fade in">
								<i class="fa-fw fa fa-info"></i>
							</div>
						</div>
					</div>
				</div>

				<form id="feature_option_list" name="feature_option_list"
					method="post"
					action="<?php echo url_for('ps_feature_option_subject_collection', array('action' => 'SaveOptionSubject')) ?>">
					<input type="hidden" name="service_id"
						value="<?php echo $service_id;?>"> <input
						id="form_list_boxchecked" type="hidden">
					<?php $form = new BaseForm(); if ($form->isCSRFProtected()): ?>
				    <input type="hidden"
						name="<?php echo $form->getCSRFFieldName() ?>"
						value="<?php echo $form->getCSRFToken() ?>" />
				    <?php endif; ?>
					<table id="tbl_feature_option_list"
						class="table_list table table-striped table-bordered table-hover no-footer no-padding"
						width="100%">
						<tr>
							<th class="title"><?php echo __('Feature option')?></th>
							<th class="title"><?php echo __('Mode');?></th>
							<th class="title" style="width: 20px;"><input
								id="sf_admin_list_batch_checkbox" type="hidden"></th>
						</tr>
						<?php
						$ps_featureOptionFeatures = array (
								"" => null ) + PreSchool::loadPsFeatureOptionFeature ();
						foreach ( $feature_options as $i => $feature_option ) :
							?>
							<tr class="<?php echo fmod($i, 2) ? 'color' : 'odd' ?>"
							id="tr_<?php echo $feature_option->getId();?>">
							<td>
									<?php echo $feature_option->getName(); ?>
									<b class="tooltip tooltip-top"> <i
									class="fa fa-question txt-color-white"></i> 
										<?php echo $feature_option->getDescription(); ?>
									</b>
							</td>
							<td><select id="_type[<?php echo $feature_option->getId()?>]"
								name="type[<?php echo $feature_option->getId()?>]"
								class="sf_admin_batch_checkbox form-control list_feature_option"
								onchange="javascript:setOptionFeature('<?php echo $feature_option->getId();?>');">
									<?php
							foreach ( $ps_featureOptionFeatures as $key => $ps_featureOptionFeature ) {
								echo '<option  value="' . $key . '">' . __ ( $ps_featureOptionFeature ) . '</option>';
							}
							?>
									</select></td>
							<td class="action"><label class="checkbox-inline"> <input
									id="of_chk_id_<?php echo $feature_option->getId();?>"
									name="ids[]" value="<?php echo $feature_option->getId();?>"
									class="sf_admin_list_batch_checkbox checkbox style-0"
									type="checkbox"> <span></span>
							</label></td>
						</tr>
						<?php endforeach;?>				
					</table>

					<div
						class="sf_admin_actions dt-toolbar-footer no-border-transparent">
				      <?php include_partial('psFeatureOptionFeature/tpl_custom/feature_option_list_actions') ?>
				    </div>
				</form>
			</div>
		</div>
	</div>
</div>
