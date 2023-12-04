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

?>
<script type="text/javascript">
	var number_page = <?php echo $pager->getNbResults();?>;
</script>

<section id="widget-grid">
	<!--  sf_admin_container -->
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

		<?php include_partial('psAttendances/flashes') ?>

		<!-- sf_admin_container -->
			<div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-grid="false" data-widget-collapsed="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false" data-widget-togglebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-table"></i></span>
					<h2><?php echo __('psAttendances Logout', array(), 'messages') ?></h2>
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
								<?php include_partial('psAttendances/filters2', array('form' => $filters, 'configuration' => $configuration,'helper' => $helper)) ?>
							</div>
							</div>
												
					<?php if (!$pager->getNbResults()): ?>
					<?php include_partial('global/include/_no_result') ?>  
				  	<?php endif;?>
					  					
					<form id="frm_batch" class="form-horizontal"
								action="<?php //echo url_for('@ps_logtime_save') ?>"
								method="post">
								<input type="hidden" name="tracked_at"
									value="<?php echo $filter_value['tracked_at'] ;?>" />
					<?php include_partial('psAttendances/list_logout', array('pager' => $pager, 'helper' => $helper, 'filter_value' => $filter_value, 'check_current_date' => $check_current_date));?>
					<!-- END: sf_admin_footer -->
							</form>
							<div class="diemdanh" style="float: right">
								<a class="btn bg-color-green txt-color-white"
									href="<?php echo url_for('@ps_attendances') ?>"><?php echo __('Attendances Login', array(), 'messages') ?></a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- END: sf_admin_container -->
		</article>
	</div>
</section>
