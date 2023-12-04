<?php use_helper('I18N', 'Date') ?>
<?php include_partial('psAttendances/assets') ?>
<?php
// Nếu khong phai nguoi quan ly co quyen sua; xoa
$check_current_date = true;
if ($sf_user->hasCredential ( 'PS_STUDENT_ATTENDANCE_TEACHER' ) && ! myUser::isAdministrator ()) {
	$current_date = date ( "Ymd" );
	$tracked_at = $filters ['tracked_at']->getValue ();
	$check_current_date = (PsDateTime::psDatetoTime ( $tracked_at ) - PsDateTime::psDatetoTime ( $current_date ) >= 0) ? true : false; // Ngay hien tai
}

$configChooseAttendancesRelative = 0;
$ps_workplace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlacesByWorkPlacesId ( $filter_value ['ps_workplace_id'] );
if($ps_workplace){ $configChooseAttendancesRelative = $ps_workplace->getConfigChooseAttendancesRelative();}
?>
<script type="text/javascript">
	var number_page = <?php echo $pager->getNbResults();?>;
</script>

<section id="widget-grid">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<?php include_partial('psAttendances/flashes') ?>
		<div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false"
				data-widget-colorbutton="false" data-widget-grid="false"
				data-widget-collapsed="false" data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false" data-widget-togglebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-table"></i></span>
					<h2><?php echo __($title_page, array(), 'messages') ?></h2>
				</header>
				<div>
					<div class="widget-body no-padding">
						<div id="datatable_fixed_column_wrapper"
							class="dataTables_wrapper form-inline no-footer no-padding">
							<div class="dt-toolbar no-margin no-padding no-border">
								<div class="col-xs-12 col-sm-12">
									<div id="sf_admin_header"><?php include_partial('psAttendances/list_header', array('pager' => $pager)) ?></div>
								</div>
							</div>
							<div id="sf_admin_bar" class="dt-toolbar">
								<div class="col-xs-12 col-sm-12">
								<?php include_partial('psAttendances/filters', array('form' => $filters, 'configuration' => $configuration,'helper' => $helper)) ?>
							</div>
							</div>
												
						<?php if (!$pager->getNbResults()): ?>
						<?php include_partial('global/include/_no_result') ?>  
					  	<?php endif;?>
					  	
					<form id="frm_batch" class="form-horizontal" action="<?php echo url_for('@ps_attendances_save') ?>" method="post">
						<input type="hidden" name="attendances_relative" id="config_choose_attendances_relative" value="<?php echo $configChooseAttendancesRelative ?>" />
						<input type="hidden" name="tracked_at" value="<?php echo $filter_value['tracked_at'] ;?>" /> 
						<input type="hidden" name="ps_customer_id" value="<?php echo $filter_value['ps_customer_id'] ;?>" /> 
						<input type="hidden" name="ps_workplace_id" value="<?php echo $filter_value['ps_workplace_id'] ;?>" /> 
						<input type="hidden" name="ps_class_id" value="<?php echo $filter_value['ps_class_id'] ;?>" />
					
						<?php include_partial('psAttendances/'.$list_layout, array('pager' => $pager, 'sort' => $sort, 'helper' => $helper, 'filter_value' => $filter_value, 'check_current_date' => $check_current_date)) ?>
					
						<div class="sf_admin_actions dt-toolbar-footer no-border-transparent">
							<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-left">				    	
						    	<?php if ($pager->haveToPaginate()): ?>
		      					<?php include_partial('psAttendances/pagination', array('pager' => $pager)) ?>
		    					<?php endif; ?>
				    		</div>

									<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-right">
				    	<?php if ($check_current_date):?>
							<?php if ($sf_user->hasCredential(array(  0 =>   array(    0 => 'PS_STUDENT_ATTENDANCE_ADD',    1 => 'PS_STUDENT_ATTENDANCE_EDIT', 2 => 'PS_STUDENT_ATTENDANCE_TEACHER'),))): ?>
							<button type="submit"
											class="btn btn-default btn-success btn-sm btn-psadmin">
											<i class="fa-fw fa fa-floppy-o" aria-hidden="true"
												title="<?php echo __('Save')?>"></i> <?php echo __('Save')?></button>
							<?php endif; ?>
						<?php endif; ?>
				      	</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</article>
	</div>
</section>
