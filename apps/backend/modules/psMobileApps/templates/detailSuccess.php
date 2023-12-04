<?php use_helper('I18N', 'Date')?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">×</button>
	<h4 class="modal-title" id="myModalLabel"><?php echo __('Mobile apps actived history') ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-2">
			<div class="row">
				<div style="width: 100%; text-align: center; margin: 0 auto;">
    				<?php
								try {
									if ($_relative->getImage () != '') :
										$path_file = '/media-web/root/' . PreSchool::MEDIA_TYPE_RELATIVE . '/' . $_relative->getSchoolCode () . '/' . $_relative->getYearData () . '/' . $_relative->getImage ();
										// $path_file='http://static1.squarespace.com/static/589be54f893fc03f8b739cb8/t/5acb9872758d46742a861bbe/1523292276930/RK.png?format=1000w';
										echo '<img class="img-circle img-responsive" style="margin: 0 auto; max-width: 150px; max-height: 150px;" src="' . $path_file . '">';
    					endif;

								} catch ( Exception $e ) {
								}
								?>
    			     
    			<span class="margin-top-10 display-inline"><?php echo "{$_relative->getFirstName()} {$_relative->getLastName()}"?></span><br>
					<br>
				</div>
				<div class="custom-scroll"
					style="max-height: 220px; overflow-y: scroll;">
    				<?php foreach ($students as $key => $student): ?>
    					<span style="font-weight: bold;"><?php echo "{$student['relation_ship']}:"?>&nbsp;</span><?php echo "{$student['student_name']} ({$student['mc_name']})"; ?>
    					<br>     
    				<?php endforeach; ?>
    			</div>
			</div>
		</article>

		<div class="padding-10"></div>
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-10">

			<div class="widget-body">
				<div id="datatable_fixed_column_wrapper"
					class="dataTables_wrapper form-inline no-footer">
					<div class="custom-scroll table-responsive"
						style="max-height: 400px; overflow-y: scroll;">
						<table id="dt_basic"
							class="table table-striped table-bordered table-hover no-footer no-padding"
							width="80%">
							<thead>
								<tr>
									<th class="text-center"><?php echo __('Device') ?></th>
									<th class="text-center"><?php echo __('Network name') ?></th>
									<th class="text-center"><?php echo __('Network frequency band') ?></th>
									<th class="text-center"><?php echo __('Actived at') ?></th>
								</tr>
							</thead>
							<tbody>
    							<?php foreach ($ps_mobile_apps as $data):?>
    							<tr>
									<td><strong>OS:</strong><?php echo $data->getOsname(); ?><br> <strong>Os
											vesion:</strong><?php echo $data->getOsvesion(); ?>
    							</td>
									<td class="text-center"><?php echo $data->getNetworkName(); ?></td>
									<td class="text-center"><?php echo $data->getMobileNetworkType(); ?></td>
									<td class="text-center"><?php echo false !== strtotime($data->getActiveCreatedAt()) ? format_date($data->getActiveCreatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?></td>
								</tr>
    							<?php endforeach;?>
    						</tbody>
						</table>
					</div>


				</div>

			</div>
		</article>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">
		<i class="fa-fw fa fa-ban"></i>&nbsp;<?php echo __('Close') ?></button>
</div>