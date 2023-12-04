<?php use_helper('I18N', 'Date')?>
<?php include_partial('psReceiptTemporary/assets')?>
<script>
$(document).ready(function() {
    $('#history_filter_date_at_from').datepicker({
    	dateFormat : 'dd-mm-yy',
    	maxDate : new Date(),
    	prevText : '<i class="fa fa-chevron-left"></i>',
    	nextText : '<i class="fa fa-chevron-right"></i>',
    	changeMonth : true,
    	changeYear : true,
    
    })
    .on('change', function(e) {
    	// Revalidate the date field
    	$('#ps-filter').formValidation('revalidateField', $(this).attr('name'));
    });

    $('#history_filter_date_at_to').datepicker({
    	dateFormat : 'dd-mm-yy',
    	maxDate : new Date(),
    	prevText : '<i class="fa fa-chevron-left"></i>',
    	nextText : '<i class="fa fa-chevron-right"></i>',
    	changeMonth : true,
    	changeYear : true,
    
    })
    .on('change', function(e) {
    	// Revalidate the date field
    	$('#ps-filter').formValidation('revalidateField', $(this).attr('name'));
    });
})
</script>
<style>
section.table_scroll {
  position: relative;
  padding-top: 37px;
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
  height: 500px;
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
  background: #e8dfdf;
  color: #000;
  padding: 10px 25px;
}
th {
  height: 0;
  line-height: 0;
  padding-top: 0;
  padding-bottom: 0;
  color: transparent;
  border: none;
  white-space: nowrap;
}
th div{
  position: absolute;
  background: transparent;
  color: #000;
  padding: 9px 25px;
  top: 0;
  margin-left: -25px;
  line-height: normal;
  border-left: 1px solid #eee;
}
th:first-child div{
  border: none;
}
</style>
<section id="widget-grid">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<?php include_partial('psReceiptTemporary/flashes')?>
			<div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-grid="false" data-widget-collapsed="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false" data-widget-togglebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-table"></i></span>
					<h2><?php echo __('History Import', array(), 'messages') ?></h2>
				</header>
				<div>
					<div class="widget-body no-padding">
						<div class="dt-toolbar">
							<div class="col-xs-12 col-sm-12">
            			    <?php include_partial('psReceiptTemporary/filters_history', array('formFilter' => $formFilter, 'helper' => $helper)) ?>
            			  </div>
						</div>

						<div class="clear" style="clear: both;"></div>
						<section class="table_scroll">
						  <div class="container_table">
						    <table>
						      <thead>
									<tr class="header">
										<th class="text-center"><?php echo __('STT', array(), 'messages') ?>
										<div><?php echo __('STT', array(), 'messages') ?></div></th>
										<th class="text-center"><?php echo __('File name', array(), 'messages') ?>
										<div><?php echo __('File name', array(), 'messages') ?></div></th>
										<th class="text-center"><?php echo __('File link', array(), 'messages') ?>
										<div><?php echo __('File link', array(), 'messages') ?></div></th>
										<th class="text-center"><?php echo __('File classify', array(), 'messages') ?>
										<div><?php echo __('File classify', array(), 'messages') ?></div></th>
										<th class="text-center"><?php echo __('Created by', array(), 'messages') ?>
										<div><?php echo __('Created by', array(), 'messages') ?></div></th>
									</tr>
								</thead>
						      	<tbody>
            						<?php  ?>
            						<?php foreach ($filter_list_history as $ky=> $list_history){ ?>
            							<tr>
											<td class="text-center"><?php echo $ky+1 ?></td>
											<td><?php echo $list_history->getFileName() ?></td>
											<td><?php echo $list_history->getFileLink() ?></td>
											<td><?php echo $list_history->getFileClassify() ?></td>
											<td class="text-center">
	                								<?php echo $list_history->getCreatedBy() ?><br />
											<code><?php echo false !== strtotime($list_history->getCreatedAt()) ? format_date($list_history->getCreatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?></code>
												</td>
											</tr>
	                                    <?php } ?>
            					</tbody>
						    </table>
						  </div>
						</section>
						
					</div>
				</div>
			</div>
		</article>
	</div>
</section>