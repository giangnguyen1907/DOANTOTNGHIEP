<style>
h1{
  font-size: 30px;
  color: #fff;
  text-transform: uppercase;
  font-weight: 300;
  text-align: center;
  margin-bottom: 15px;
}
table{
  width:100%;
  table-layout: fixed;
}
.tbl-header{
  background-color: rgba(255,255,255,0.3);
 }

th{
  padding: 5px 3px;
  text-align: left;
  font-weight: 500;
  font-size: 12px;
  border: 1px solid #ccc;
}
td{
  padding: 5px 3px;
  text-align: left;
  vertical-align:middle;
  font-weight: 300;
  font-size: 12px;border-bottom: solid 1px #ccc;
  border: 1px solid #ccc;
  
}

@media (min-width: 768px){
table.dataTable{margin: 0px!important}
.table>thead>tr>th{line-height: 0}
.checkbox-inline span{margin-left: 0px!important}
.checkbox-inline{padding-left: 0px}
span.text-results{color: #333;line-height: 35px;}
section.table_scroll {
  position: relative;
  padding-top: 80px;
  background: #ddd;
}
section.positioned {
  position: absolute;
  top:100px;
  left:100px;
  width:800px;
  box-shadow: 0 0 15px #333;
}
.container_table {
  overflow-y: auto;
  max-height: 500px;
  border-top: 1px solid #eee;
}
table {
  border-spacing: 0;
  width:100%;
}
td + td {
  border-left:1px solid #eee;
}
td, th {
  border-bottom:1px solid #eee;
  background: #fff;
  color: #000;
  padding: 5px 5px;
}
tr.header th {
    height: 0;
    line-height: 0;
    padding-top: 0!important;
    padding-bottom: 0!important;
    color: transparent;
    border: none;
    white-space: nowrap;
}
th div{
  position: absolute;
  background: transparent;
  color: #000;
  padding: 10px 3px;
  top: 0;
  margin-left: -5px;
  line-height: normal;
  border-left: 1px solid #ccc;
  height: 81px;
  
}
th:first-child div{
  border: none;
}
td label.select .form-control{padding: 0px!important;font-size: 12px;}
}
@media (max-width: 767px){
	tbody>tr>td, tbody>tr>th, tfoot>tr>td, tfoot>tr>th, thead>tr>td,thead>tr>th {
    padding: 8px 10px;border: 1px solid #ddd;}

}

</style>
<?php

// Find $needle[student_id, criteria_id, date_at] from PsEvaluateIndexStudent
// Return array()
function searchIndexKey($needle, $array) {

	foreach ( $array as $key => $val ) {

		if (($val ['evaluate_index_criteria_id'] == $needle ['criteria_id']) && ($val ['ps_student_id'] == $needle ['student_id']) && (date ( 'm-Y', strtotime ( $val ['date_at'] ) ) == $needle ['date_at'])) {

			//array_pop ( $array [$key] );
			return array (
					'evaluate_index_symbol_id' => $val ['evaluate_index_symbol_id'],
					'symbol_code' => $val ['symbol_code'],
					'is_public' => $val ['is_public'],
					'is_awaiting_approval' => $val ['is_awaiting_approval'] );
		}
	}
	return false;
}
?>
<?php
// Tong so hoc sinh
$total_student = count ( $students );
$table_content = array ();

foreach ( $evaluate as $key => $evaluate ) {

	if (isset ( $evaluate ['criteria_id'] )) {
		$table_content [$key] = array (
				'criteria_id' => $evaluate ['criteria_id'],
				'criteria_code' => $evaluate ['criteria_code'],
				'criteria_title' => $evaluate ['criteria_title'],
				'evaluate' => array () );

		foreach ( $students as $student ) {

			$needle = array ();
			$needle ['student_id'] = $student ['student_id'];
			$needle ['criteria_id'] = $evaluate ['criteria_id'];
			$needle ['date_at'] = $filterValue ['ps_month'];

			$array_result = searchIndexKey ( $needle, $students_evaluate );

			if ($array_result) {
				array_push ( $table_content [$key] ['evaluate'], array (
						'criteria_id' => $evaluate ['criteria_id'],
						'student_id' => $student ['student_id'],
						'symbol_id' => $array_result ['evaluate_index_symbol_id'],
						'symbol_code' => $array_result ['symbol_code'],
						'is_public' => $array_result ['is_public'],
						'is_awaiting_approval' => $array_result ['is_awaiting_approval'] ) );
			} else {
				array_push ( $table_content [$key] ['evaluate'], array (
						'criteria_id' => $evaluate ['criteria_id'],
						'student_id' => $student ['id'],
						'symbol_id' => 0,
						'symbol_code' => 0,
						'is_public' => 0,
						'is_awaiting_approval' => 0 ) );
			}
		}
	} else {

		$table_content [$key] = array (
				'subject_id' => $evaluate ['subject_id'],
				'subject_code' => $evaluate ['subject_code'],
				'subject_title' => $evaluate ['subject_title'] );
		// array_push($table_content, $evaluate);
	}
}
unset ( $evaluate );
// print_r(count($students_evaluate));die;
?>

<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<div class="row">
		<div class="jarviswidget" id="wid-id-2" data-widget-editbutton="false"
			data-widget-colorbutton="false" data-widget-grid="false"
			data-widget-collapsed="false" data-widget-fullscreenbutton="false"
			data-widget-deletebutton="false" data-widget-togglebutton="false">

			<div>
				<div class="widget-body no-padding" style="overflow: hidden;">
					<div id="datatable_fixed_column_wrapper"
						class="dataTables_wrapper form-inline no-footer no-padding">
				
				<?php if(count($symbols) > 0 ):?>
				
				<h5><?php echo __('Rule of symbol evaluate in year %%year%%', array('%%year%%' => $schoolyear->getTitle()),'messages')?></h5>
				<p>
				<?php foreach ($symbols as $symbol): ?>
					<span class="code_symbol"><?php echo $symbol->getSymbolCode(); ?></span> : <span class="title_symbol"><?php echo $symbol->getTitle(); ?></span>, &nbsp;
				<?php endforeach;?>
				</p>
				
				<?php else:?>
					<span class="label bg-color-greenLight"><?php echo __('You doesn\'t define any rule of symbol evaluate in year %%year%%', array('%%year%%' => $schoolyear->getTitle()),'messages')?></span>
				<?php endif; ?>
				<br> <label><strong><?php echo __('Activate state for this class')?></strong></label>
				<?php if(myUser::credentialPsCustomers('PS_EVALUATE_INDEX_STUDENT_EDIT') || myUser::credentialPsCustomers('PS_EVALUATE_INDEX_STUDENT_ADD')):?>
				<div style="padding-left: 15px;">
							<label><input type="checkbox" id="is_publish" class="chk"
								checked="true" value="is_publish"><?php echo __('Is public')?></label>
                    <?php if(myUser::isAdministrator()):?>
                    <br> <label><input type="checkbox" id="is_awaiting"
								class="chk" checked="true" value="is_awaiting"><?php echo __('Is awaiting')?></label>
                    <?php endif; ?>
                    <br> <a
								class="btn btn-xs btn-default btn-save-state"
								href="javascript:;"
								data-date="<?php echo $filterValue['ps_month']; ?>"
								data-class=<?php echo $filterValue['ps_class_id']; ?>><i
								class="fa-fw fa fa-save"></i>&nbsp;<?php echo __('Apply')?></a>
						</div>
				<?php endif;?>
				 

					</div>
					<div class="clear" style="clear: both;"></div>
				 <section class="tbl-header table_scroll">
				 <div class="container_table custom-scroll table-responsive">
						<table cellpadding="0" cellspacing="0" border="0" >
								<thead>
									<tr class="header hidden-sm hidden-xs">
										<th class="text-center"><?php echo __('Criteria code')?>
										<div><?php echo str_replace( " ", "<br/>", __('Criteria code') )?></div></th>
										<th class="text-center"><?php echo __('Title')?>
										<div><?php echo str_replace( " ", "<br/>", __('Title') )?></div></th>

										<!-- $student_arr: Luu id hoc sinh -->
										<!-- $key_arr: luu chi so index cua column -->
								
								<?php $student_arr= array(); $key_arr = array(); ?>
								<?php foreach ($students as $key => $student): ?>
								<?php array_push($student_arr, $student['student_id']); array_push($key_arr, $key); ?>
                    			<th class="text-center"><?php echo "{$student['full_name']}" ?>
                    			<div><?php echo str_replace( " ", "<br/>", "{$student['full_name']}" ) ?></div></th>
                    			<?php endforeach; ?>
                    			<?php $key_arr = implode(",", $key_arr); $student_arr = implode(",", $student_arr); ?>
                    			
                    			<th class="text-center"><?php echo __('Actions')?><div><?php echo str_replace( " ", "<br/>", __('Actions') )?></div></th>
                    			
									</tr>
								</thead>
								
								<tbody>
                    	<?php foreach ($table_content as $cell): ?>
                    		<?php if(isset($cell['criteria_id'])): ?>
                        	<tr>
										<td>&emsp;<?php echo $cell['criteria_code']?></td>
										<td><?php echo $cell['criteria_title']?></td>
                        		
                        		<?php foreach ($cell['evaluate'] as $key => $evaluate_cell): ?>
                            	
                            	<td><?php include_partial('psEvaluateIndexStudent/cell_td_row', array('evaluate_cell' => $evaluate_cell, 'symbols' => $symbols, 'key' => $key))?></td>
                            	<?php endforeach;?>
                            	
                            	<td
											class="sf_admin_foreignkey sf_admin_list_td_action text-center">
                            	  <?php if(myUser::credentialPsCustomers('PS_EVALUATE_INDEX_STUDENT_EDIT') || myUser::credentialPsCustomers('PS_EVALUATE_INDEX_STUDENT_ADD')):?>	
                            	  <button style="margin-right: 5px;"
												type="button"
												class="btn btn-default btn-xs btn-save-evaluate "
												data-criteria-id="<?php echo $cell['criteria_id']?>"
												data-student-list="<?php echo $student_arr?>"
												data-date="<?php echo $filterValue['ps_month'] ?>"
												data-cell="<?php echo $key_arr ?>">
												<i class="fa-fw fa fa-floppy-o" aria-hidden="true"
													title="<?php echo __('Save')?>"></i>
											</button>
                            	  <?php else: ?>
                            	  <button style="margin-right: 5px;"
												type="button" class="btn btn-default btn-xs disabled">
												<i class="fa-fw fa fa-floppy-o" aria-hidden="true"
													title="<?php echo __('Save')?>"></i>
											</button>
                            	  <?php endif; ?>
                                </td>
									</tr>
                        	<?php else:?>
                        	<tr>
										<td style="background: yellow; font-weight: bold;"><?php echo strtoupper($cell['subject_code']) ?></td>
										<td
											style="background: yellow; font-weight: bold; text-transform: uppercase;"><?php echo $cell['subject_title'] ?></td>
										<td style="background: yellow;"
											colspan="<?php echo ($total_student+1) ?>"></td>
								<?php for ($i = 0; $i <= $total_student; $i++):?>
								<td style="display: none;"?>"></td>
								<?php endfor;?>
                        	</tr>
                        	<?php endif;?>
                    	<?php endforeach;?>
                    	</tbody>
							</table>
						</div>
						</section>

						<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6"></div>
							<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
								<input class="evaluate-student hidden"
									value="<?php echo $evaluate_student;?>"> <a
									href="<?php echo url_for(@ps_evaluate_index_student)."/{$filterValue['school_year_id']}/{$filterValue['ps_customer_id']}/{$filterValue['ps_workplace_id']}/{$filterValue['ps_class_id']}/{$filterValue['ps_month']}/export"?>"
									class="btn btn-default btn-save-xls"><i
									class="fa-fw fa fa-info"></i>&nbsp;<?php echo __('Export xls')?></a>


								<span id="">
									<button type="button"
										class="btn btn-default btn-danger btn-sm btn-psadmin hidden-xs"
										id="btn_delete_evaluate_class"
										data-sudent-list="<?php echo $student_arr?>"
										data-class="<?php echo $filterValue['ps_class_id']?>"
										data-date="<?php echo $filterValue['ps_month']?>">
										<span class="fa fa-trash-o"></span> <?php echo __('Delete')?></button>
								</span>
							</div>
						</article>
					
				</div>
			</div>
			
			
		</div>
	</div>
</article>

<script type="text/javascript">

    function ConfirmDelete(){
    	var x =  confirm("<?php echo __('Are you sure you want to Remove?') ?>");
    
    	if(x) {
    		return true;
    	} else {
    		return false;
    	}
    }

	//Delete 
	$('#btn_delete_evaluate_class').click(function(){

		var confirm = ConfirmDelete();

		if(confirm) {
    		var class_id  = $(this).attr('data-class');
    		var date = <?php echo date('d')?> +'-' + $(this).attr('data-date');
    		var list_student_id = $(this).attr('data-sudent-list');
    
    		$.ajax({
    	        url: '<?php echo url_for('@ps_evaluate_index_student_delete_all_by_class') ?>',
    		        type: 'POST',
    		        data: 'class_id=' + class_id + '&date=' + date + '&student_list=' + list_student_id,
    		        success: function(data) {
    //	 		        alert(data);
    		        	location.reload();
    		        },
    		        error: function (request, error) {
    		            alert(" <?php echo __ ('Can\'t do because: ') ?>" + error);
    	        },
    		});
		}
	});
	//End delete
	
	
</script>