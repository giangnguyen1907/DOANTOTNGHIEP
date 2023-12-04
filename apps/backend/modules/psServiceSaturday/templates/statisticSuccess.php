<?php use_helper('I18N', 'Date')?>
<?php include_partial('psServiceSaturday/assets')?>

<section id="widget-grid">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<?php //include_partial('psServiceSaturday/flashes')?>
			<div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-grid="false" data-widget-collapsed="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false" data-widget-togglebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-table"></i></span>
					<h2><?php echo __('Statistic service saturday', array(), 'messages') ?></h2>
				</header>

				<div>
					<div class="widget-body no-padding">
						<div class="dt-toolbar">
							<div class="col-xs-12 col-sm-12">
			    <?php include_partial('psServiceSaturday/filter_saturday', array('formFilter' => $formFilter, 'helper' => $helper)) ?>
			  </div>
						</div>
						<div class="clear" style="clear: both;"></div>
						<section class="table_scroll">
							<div class="container_table custom-scroll table-responsive">
							<table id="dt_basic" class="table table-bordered table-striped" width="100%">
								<thead>
									<tr class="header hidden-sm hidden-xs">
										<th class="text-center"><?php echo __('Student name', array(), 'messages') ?>
										<div><?php echo __('Student name', array(), 'messages') ?></div></th>
										<th class="text-center"><?php echo __('Service name', array(), 'messages') ?>
										<div><?php echo __('Service name', array(), 'messages') ?></div></th>
										<th class="text-center"><?php echo __('Service date', array(), 'messages') ?>
										<div><?php echo __('Service date', array(), 'messages') ?></div></th>
										<th class="text-center"><?php echo __('Relative', array(), 'messages') ?>
										<div><?php echo __('Relative', array(), 'messages') ?></div></th>
										<th class="text-center"><?php echo __('Input date at', array(), 'messages') ?>
										<div><?php echo __('Input date at', array(), 'messages') ?></div></th>
										<th class="text-center"><?php echo __('Note', array(), 'messages') ?>
										<div><?php echo __('Note', array(), 'messages') ?></div></th>
										<th class="text-center"><?php echo __('Updated by', array(), 'messages') ?>
										<div><?php echo __('Updated by', array(), 'messages') ?></div></th>
									</tr>							
									<tr class="hidden-lg hidden-md">
										<th class="text-center"><?php echo __('Student name', array(), 'messages') ?></th>
										<th class="text-center"><?php echo __('Service name', array(), 'messages') ?></th>
										<th class="text-center"><?php echo __('Service date', array(), 'messages') ?></th>
										<th class="text-center"><?php echo __('Relative', array(), 'messages') ?></th>
										<th class="text-center"><?php echo __('Input date at', array(), 'messages') ?></th>
										<th class="text-center"><?php echo __('Note', array(), 'messages') ?></th>
										<th class="text-center"><?php echo __('Updated by', array(), 'messages') ?></th>
									</tr>
								</thead>
						<?php $count = count($filter_list_student)?>
						<tbody>
							<?php foreach ($filter_list_student as $list_student ): ?>
							<tr>
											<td><?php echo $list_student->getStudentName(); ?></td>
											<td><?php echo $list_student->getSvTitle(); ?></td>
											<td><?php echo false !== strtotime($list_student->getServiceDate()) ? format_date($list_student->getServiceDate(), "dd/MM/yyyy") : '&nbsp;' ?></td>
											<td><?php echo $list_student->getFullName(); ?></td>
											<td><?php echo false !== strtotime($list_student->getInputDateAt()) ? format_date($list_student->getInputDateAt(), "dd/MM/yyyy") : '&nbsp;' ?></td>
											<td><?php echo $list_student->getNote(); ?></td>
											<td><?php echo $list_student->getUpdatedBy() ?><br />
  									<?php echo false !== strtotime($list_student->getUpdatedAt()) ? format_date($list_student->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?>
  								</td>
										</tr>
                            <?php endforeach; ?>
						</tbody>
								</table>
							</div>
						</section>
					</div>
				</div>
			</div>

		</article>
		<?php if($count > 0){ ?>
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right">
			<?php if($ps_workplace_id > 0 && $class_id >0){  ?>
        		<a class="btn btn-default"
				href="<?php echo url_for(@ps_service_saturday).'/'.$ps_workplace_id.'/'.$class_id.'/'.$saturday.'/'; ?>export_saturday"
				id="btn-export-growths"><i class="fa-fw fa fa-cloud-download"></i>&nbsp;<?php echo __('Export xls')?></a>
        	<?php }elseif($class_id == ''){ $class_id = 0; ?>
        		<a class="btn btn-default"
				href="<?php echo url_for(@ps_service_saturday).'/'.$ps_workplace_id.'/'.$class_id.'/'.$saturday.'/'; ?>export_saturday"
				id="btn-export-growths"><i class="fa-fw fa fa-cloud-download"></i>&nbsp;<?php echo __('Export xls')?></a>
        	<?php }else{ ?>
        		<a class="btn btn-default" href="javascript:;"
				id="btn-export-growths"><i class="fa-fw fa fa-cloud-download"></i>&nbsp;<?php echo __('Export xls')?></a>
        	<?php } ?>
        </article>
        <?php } ?>
	</div>
</section>