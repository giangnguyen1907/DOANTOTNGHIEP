<?php use_helper('I18N', 'Date') ?>
<?php include_partial('global/include/_box_modal_warning');?>
<?php include_partial('psStudentFeatures/assets') ?>
<script type="text/javascript">
	var number_page = <?php echo $pager->getNbResults();?>;
</script>
<?php
$current_date = date ( "Ymd" );
$tracked_at = $filters ['tracked_at']->getValue ();
$check_current_date = (PsDateTime::psDatetoTime ( $tracked_at ) - PsDateTime::psDatetoTime ( $current_date ) >= 0) ? true : false; // Ngay hien tai
?>
<section id="widget-grid">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<?php include_partial('psStudentFeatures/flashes') ?>
		
		<div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false"
				data-widget-colorbutton="false" data-widget-grid="false"
				data-widget-collapsed="false" data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false" data-widget-togglebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-table"></i></span>
					<h2><?php echo __('Classroom performance assessment', array(), 'messages') ?></h2>
				</header>
				<div>
					<div class="widget-body no-padding">
						<div id="datatable_fixed_column_wrapper"
							class="dataTables_wrapper form-inline no-footer no-padding">
							<div class="dt-toolbar no-margin no-padding no-border">
								<div class="col-xs-12 col-sm-12">
									<div id="sf_admin_header"><?php include_partial('psStudentFeatures/list_header', array('pager' => $pager)) ?></div>
								</div>
							</div>

							<div id="sf_admin_bar" class="dt-toolbar">
								<div class="col-xs-12 col-sm-12">
							    <?php include_partial('psStudentFeatures/filters', array('form' => $filters, 'configuration' => $configuration,'helper' => $helper)) ?>
							  </div>
							</div>
												
					<?php if (!$pager->getNbResults()): ?>
					<?php include_partial('global/include/_no_result') ?>  
				  	<?php else:?>				  	
					  	<form id="frm_batch"
								action="<?php echo url_for('@ps_student_feature_save') ?>"
								method="post">
								<input type="hidden" name="tracked_at"
									value="<?php echo $filter_value['tracked_at'] ;?>" /> <input
									type="hidden" name="feature_branch_id"
									value="<?php echo $filter_value['feature_branch_id'] ;?>" />
						<?php include_partial('psStudentFeatures/list', array('pager' => $pager, 'sort' => $sort, 'helper' => $helper, 'filter_value' => $filter_value, 'check_current_date' => $check_current_date)) ?>
						
						<div
									class="sf_admin_actions dt-toolbar-footer no-border-transparent">
									<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-left">				    	
					    	<?php if ($pager->haveToPaginate()): ?>
	      					<?php include_partial('psStudentFeatures/pagination', array('pager' => $pager)) ?>
	    					<?php endif; ?>
					    	</div>

									<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-right">	
					    	<?php //if($check_current_date): ?>					     
						        <!-- <button type="submit" class="btn btn-default btn-success btn-sm btn-psadmin" onclick="return submit_Click();"><i class="fa-fw fa fa-floppy-o" aria-hidden="true"></i> <?php echo __('Save')?></button>-->
					        	<?php include_partial('psStudentFeatures/list_batch_actions', array('helper' => $helper)) ?>
					        <?php //endif; ?>
					        </div>
								</div>
							</form>
					<?php endif;?>
					</div>
					</div>
				</div>
			</div>
		</article>
	</div>
</section>