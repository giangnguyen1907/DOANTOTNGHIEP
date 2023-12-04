<?php use_helper('I18N', 'Date')?>
<?php include_partial('psEvaluateIndexStudent/assets')?>
<script type="text/javascript">

$(document).ready(function() {
	
	$('#semester_statistic_ps_school_year_id').change(function() {

		resetOptions('semester_statistic_class_id');
		$('#semester_statistic_class_id').select2('val','');

		$("#semester_statistic_ps_workplace_id").trigger('change');
	});
	
	//customer
	$('#semester_statistic_ps_customer_id').change(function() {

		resetOptions('semester_statistic_ps_workplace_id');
		$('#semester_statistic_ps_workplace_id').select2('val','');

		resetOptions('semester_statistic_class_id');
		$('#semester_statistic_class_id').select2('val','');
		
		if ($(this).val() > 0) {

			$("#semester_statistic_ps_workplace_id").attr('disabled', 'disabled');
			//$("#semester_statistic_class_id").attr('disabled', 'disabled');
			
			$.ajax({
				url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' + $(this).val(),
		        type: "POST",
		        data: {'psc_id': $(this).val()},
		        processResults: function (data, page) {
	          		return {
	            		results: data.items
	          		};
	        	},
		    }).done(function(msg) {

		    	$('#semester_statistic_ps_workplace_id').select2('val','');

				$("#semester_statistic_ps_workplace_id").html(msg);

				$("#semester_statistic_ps_workplace_id").attr('disabled', null);

				$("#semester_statistic_ps_workplace_id").trigger('change');

		    });
		}
	
	});
	//end-customer

	//workplace
	$('#semester_statistic_ps_workplace_id').change(function() {

		resetOptions('semester_statistic_class_id');
		$('#semester_statistic_class_id').select2('val','');

		if ($(this).val() <= 0) {
			return;
		}
		$("#semester_statistic_class_id").attr('disabled', 'disabled');

			$.ajax({
				url: '<?php echo url_for('@ps_class_by_params') ?>',
		        type: "POST",
		        data: 'c_id=' + $('#semester_statistic_ps_customer_id').val() + '&w_id=' + $('#semester_statistic_ps_workplace_id').val() + '&y_id=' + $('#semester_statistic_ps_school_year_id').val(),
		        processResults: function (data, page) {
	          		return {
	            		results: data.items
	          		};
	        	},
		    }).done(function(msg) {
		    	$('#semester_statistic_class_id').select2('val','');
				$("#semester_statistic_class_id").html(msg);
				$("#semester_statistic_class_id").attr('disabled', null);
		    });

	});

});
</script>
<?php $array_symbol_id = array()?>
<section id="widget-grid">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

			<div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-grid="false" data-widget-collapsed="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false" data-widget-togglebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-table"></i></span>
					<h2><?php echo __('Evaluate student by semester') ?></h2>
				</header>

				<div>
					<div class="widget-body no-padding">
						<div class="dt-toolbar">
							<div class="col-xs-12 col-sm-12">
            			    <?php include_partial('psEvaluateIndexStudent/filters_statistic', array('formFilter' => $formFilter, 'helper' => $helper)) ?>
            			  </div>
						</div>

						<form id="frm_batch" class="form-horizontal" action="<?php echo url_for('@ps_attendances_statistic_export')?>" method="post">
							
							<div id="datatable_fixed_column_wrapper"
								class="dataTables_wrapper form-inline no-footer no-padding">
								<div class="custom-scroll table-responsive">
									<?php if($ps_semester == 3){
										echo get_partial('psEvaluateIndexStudent/table_semester', array('list_student' => $list_student,'list_symbols'=>$list_symbols,'getDataEvaluate'=>$getDataEvaluate));
									}else{?>
									<table id="dt_basic" class="table table-striped table-bordered table-hover no-footer no-padding"	width="100%">
										<thead>
											<tr>
												<th class="text-center"><?php echo __('STT') ?></th>
												<th class="text-center"><?php echo __('Student') ?></th>
												<?php foreach ($list_symbols as $symbols){
													array_push($array_symbol_id,$symbols->getId());
													?>
													<th class="text-center"><?php echo $symbols->getTitle(); ?></th>
												<?php }?>
                                        	</tr>
										</thead>

										<tbody>
            								<?php foreach ($list_student as $key=>$student){?>
            								<tr>
            									<td><?php echo $key+1?></td>
            									<td><?php echo $student->getStudentName()?><br/>
            									<code><?php echo $student->getStudentCode()?></code></td>
            									<?php foreach ($array_symbol_id as $symbol_id){?>
            									<td class="text-center">
            										<?php foreach ($getDataEvaluate as $evaluate){
            											if($student->getId() == $evaluate->getStudentId() && $evaluate->getSymbolId() == $symbol_id){
            												echo $evaluate->getNumber();
            											}
            										}?>
            									</td>
            									<?php }?>
            								</tr>
            								<?php }?>
            							</tbody>
									</table>
									<?php }?>
								</div>
							</div>
						</form>
						
					</div>
				</div>
			</div>
		</article>
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="widget-body-toolbar">
				<div class="pull-right">
				</div>
			</div>
		</article>
	</div>
	</div>
</section>
