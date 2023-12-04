<fieldset>
	<legend><?php echo __('List class apply')?></legend>
	<input class="criteria_id hidden"
		value="<?php echo $ps_evaluate_index_criteria->getId()?>">
	<div class="custom-scroll table-responsive"
		style="max-height: 300px; overflow-y: scroll;">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th class="text-center"><?php echo __('Choose')?></th>
					<th class="text-center"><?php echo __('Class name')?></th>
					<th class="text-center"><?php echo __('Date start') ?></th>
					<th class="text-center"><?php echo __('Date end') ?></th>

				</tr>
			</thead>
			<tbody>
			<?php foreach ($list_class as $obj) :?>
			<tr>
					<td class="text-center"><label class="checkbox-inline"> <input
							class="select checkbox chk class_apply" type="checkbox"
							name="class_apply[my_class][<?php echo $obj->getId() ?>][ids]"
							id="psactivitie_my_class_<?php echo $obj->getId();?>_select"
							value="<?php echo $obj->getId()?>"
							<?php if($obj->getEvaluateMyclassId() == $obj->getId()) echo "checked"?>><span></span>
					</label></td>
					<td>
					<?php echo $obj->getClassName()?>
					<br> <small class="text-muted"> <i> <?php echo __('Object group').': '.$obj->getGroupName()?> - <?php echo $obj->getWorkplaceName()?></i></small>
					</td>
					<td class="text-center input_date">
						<!-- 					<div class="col-md-6 col-sm-12 col-lg-6 col-xs-12"> -->
						<div data-dateformat="dd-mm-yyyy" placeholder="dd-mm-yyyy"
							class="icon-addon">
							<input data-dateformat="dd-mm-yyyy" readonly
								placeholder="dd-mm-yyyy" class="form-control date_picker"
								type="text"
								name="class_apply[my_class][<?php echo $obj->getId() ?>][from_date]"
								id="psactivitie_my_class_<?php echo $obj->getId();?>_from_date"
								value="<?php echo (false !== strtotime($schoolyear->getFromDate())) ? format_date($schoolyear->getFromDate(), "dd-MM-yyyy") : '';?>" />
							<label
								for="psactivitie_my_class_<?php echo $obj->getId();?>_from_date"
								class="icon-append fa fa-calendar padding-left-5" rel="tooltip"></label>
						</div> <!-- 					</div> -->
					</td>
					<td class="text-center input_date">
						<!-- 					<div class="col-md-6 col-sm-12 col-lg-6 col-xs-12"> -->
						<div data-dateformat="dd-mm-yyyy" placeholder="dd-mm-yyyy"
							class="icon-addon">
							<input data-dateformat="dd-mm-yyyy" readonly
								placeholder="dd-mm-yyyy" class="form-control date_picker"
								type="text"
								name="class_apply[my_class][<?php echo $obj->getId() ?>][to_date]"
								id="psactivitie_my_class_<?php echo $obj->getId();?>_to_date"
								value="<?php echo (false !== strtotime($schoolyear->getToDate())) ? format_date($schoolyear->getToDate(), "dd-MM-yyyy") : '';?>" />
							<label
								for="psactivitie_my_class_<?php echo $obj->getId();?>_to_date"
								class="icon-append fa fa-calendar padding-left-5" rel="tooltip"></label>
						</div> <!-- 					</div> -->
					</td>
					<!-- 
				<td class="text-center">
					<a class="btn btn-default btn-sm" href="javascript:;"><i class="fa fa-plus" aria-hidden="true"></i></a>
				</td>
 -->
				</tr>
			<?php endforeach;?>
		</tbody>
		</table>
	</div>