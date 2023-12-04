<?php use_helper('I18N', 'Date') ?>
<?php include_partial('psLogtimes/assets') ?>
<?php
// Nếu khong phai nguoi quan ly co quyen sua; xoa
$check_current_date = true;
if ($sf_user->hasCredential ( 'PS_STUDENT_ATTENDANCE_TEACHER' ) && ! myUser::isAdministrator ()) {
	$current_date = date ( "Ymd" );
	$tracked_at = $filters ['tracked_at']->getValue ();
	$check_current_date = (PsDateTime::psDatetoTime ( $tracked_at ) - PsDateTime::psDatetoTime ( $current_date ) >= 0) ? true : false; // Ngay hien tai
}
?>
<script type="text/javascript">
	var number_page = <?php echo $pager->getNbResults();?>;
</script>

<section id="widget-grid">
	<!--  sf_admin_container -->
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

		<?php include_partial('psLogtimes/flashes') ?>

		<!-- sf_admin_container -->
			<div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-grid="false" data-widget-collapsed="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false" data-widget-togglebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-table"></i></span>
					<h2><?php echo __('PsLogtimes List', array(), 'messages') ?></h2>
				</header>
				<div>
					<div class="widget-body no-padding">
						<div id="datatable_fixed_column_wrapper"
							class="dataTables_wrapper form-inline no-footer no-padding">
							<div class="dt-toolbar no-margin no-padding no-border">
								<div class="col-xs-12 col-sm-12">
									<div id="sf_admin_header"><?php include_partial('psLogtimes/list_header', array('pager' => $pager)) ?></div>
								</div>
							</div>
							<div id="sf_admin_bar" class="dt-toolbar">
								<div class="col-xs-12 col-sm-12">
								<?php include_partial('psLogtimes/filters', array('form' => $filters, 'configuration' => $configuration,'helper' => $helper)) ?>
							</div>
							</div>
												
						<?php if (!$pager->getNbResults()): ?>
						<?php include_partial('global/include/_no_result') ?>  
					  	<?php endif;?>
					  					
					<form id="frm_batch" class="form-horizontal"
								action="<?php echo url_for('@ps_logtime_save') ?>" method="post">
								<input type="hidden" name="tracked_at"
									value="<?php echo $filter_value['tracked_at'] ;?>" />
					<?php include_partial('psLogtimes/list', array('pager' => $pager, 'sort' => $sort, 'helper' => $helper, 'filter_value' => $filter_value, 'ps_constant_option' => $ps_constant_option, 'check_current_date' => $check_current_date)) ?>
					
					<!-- sf_admin_footer -->
								<div
									class="sf_admin_actions dt-toolbar-footer no-border-transparent">
									<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-left">				    	
				    	<?php if ($pager->haveToPaginate()): ?>
      					<?php include_partial('psLogtimes/pagination', array('pager' => $pager)) ?>
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
							
							<?php if ($sf_user->hasCredential(array(  0 =>   array(    0 => 'PS_STUDENT_ATTENDANCE_ADD',    1 => 'PS_STUDENT_ATTENDANCE_EDIT', 2 => 'PS_STUDENT_ATTENDANCE_TEACHER'),))): ?>
							<?php $tracked_at = strtotime($filter_value['tracked_at'])?>
							<a class="btn btn-default btn-success btn-sm btn-psadmin"
											href="<?php echo url_for('@ps_logtimes_new?tracked_at='.$tracked_at.'&class_id='.$filter_value['ps_class_id']);?>"><i
											class="fa-fw fa fa-plus"></i> <?php echo  __('Attendance by baby')?></a>
							<?php endif; ?>
						<?php endif; ?>
				      	</div>
								</div>
								<!-- END: sf_admin_footer -->
							</form>
						</div>
					</div>
				</div>
			</div>
			<!-- END: sf_admin_container -->
		</article>
	</div>
</section>
